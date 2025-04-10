<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Perbandingan_model extends CI_Model
{
    private function get_hierarchical_data($kode, $thang)
    {
        try {
            $ref = $this->load->database('ref' . $thang, TRUE);

            $query = $ref->query("
                SELECT 
                    kdsbu,
                    nmsbu,
                    biaya,
                    LEFT(kdsbu, 3) as prefix
                FROM t_sbu
                WHERE LEFT(kdsbu, 3) = ?
                ORDER BY kdsbu
            ", array($kode));

            if (!$query || $query->num_rows() == 0) {
                return array();
            }

            $rows = $query->result_array();
            $hierarchy = array();
            $currentPrefix = null;
            $lastTitle = null;
            $lastSubtitle = null;
            $lastSubSubtitle = null;
            $zeroCounter = 0;

            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                $prefix = $row['prefix'];

                // Determine the level based on your logic
                if ($row['biaya'] == 0) {
                    // Rule 1: Different prefix means it's a title (level 1)
                    if ($prefix != $currentPrefix) {
                        $level = 1;
                        $lastTitle = $row['kdsbu'];
                        $lastSubtitle = null;
                        $lastSubSubtitle = null;
                        $zeroCounter = 1;
                    }
                    // Rule 2 & 3: Check consecutive zero values
                    else if ($zeroCounter == 1) {
                        // First zero after title
                        $level = 2;
                        $lastSubtitle = $row['kdsbu'];
                        $lastSubSubtitle = null;
                        $zeroCounter = 2;
                    } else if ($zeroCounter == 2) {
                        // Second consecutive zero
                        $level = 3;
                        $lastSubSubtitle = $row['kdsbu'];
                        $zeroCounter = 0; // Reset counter after establishing hierarchy
                    }
                    // Rule 5 & 6: Zero in the middle of data
                    else {
                        // Check next row to see if we have consecutive zeros
                        $nextRow = ($i + 1 < count($rows)) ? $rows[$i + 1] : null;

                        if ($nextRow && $nextRow['biaya'] == 0) {
                            // First of two consecutive zeros in the middle of data
                            $level = 2;
                            $lastSubtitle = $row['kdsbu'];
                            $lastSubSubtitle = null;
                            $zeroCounter = 2;
                        } else {
                            // Single zero - inherit level from previous subtitle/sub-subtitle
                            // If we had a sub-subtitle before, this is also a sub-subtitle
                            if ($lastSubSubtitle) {
                                $level = 3;
                                $lastSubSubtitle = $row['kdsbu'];
                            } else {
                                $level = 2;
                                $lastSubtitle = $row['kdsbu'];
                                $lastSubSubtitle = null;
                            }
                            $zeroCounter = 0;
                        }
                    }
                } else {
                    // Rule 7: Data directly under title
                    if ($zeroCounter == 1) {
                        $level = 9;
                        $parent = $lastTitle;
                        $zeroCounter = 0;
                    }
                    // Data under subtitle
                    else if ($lastSubtitle && !$lastSubSubtitle) {
                        $level = 9;
                        $parent = $lastSubtitle;
                    }
                    // Data under sub-subtitle
                    else if ($lastSubSubtitle) {
                        $level = 9;
                        $parent = $lastSubSubtitle;
                    } else {
                        // Default case - should not happen with well-formed data
                        $level = 9;
                        $parent = $lastTitle;
                    }

                    $zeroCounter = 0; // Reset counter when we hit data
                }

                // Create node structure
                $node = array(
                    'kdsbu' => $row['kdsbu'],
                    'nmsbu' => $row['nmsbu'],
                    'biaya' => (float)$row['biaya'],
                    'level' => $level,
                    'children' => array()
                );

                // Set parent relationships
                if ($level == 1) {
                    $node['parent'] = null;
                } else if ($level == 2) {
                    $node['parent'] = $lastTitle;
                    if (isset($hierarchy[$lastTitle])) {
                        $hierarchy[$lastTitle]['children'][] = $row['kdsbu'];
                    }
                } else if ($level == 3) {
                    $node['parent'] = $lastSubtitle;
                    if (isset($hierarchy[$lastSubtitle])) {
                        $hierarchy[$lastSubtitle]['children'][] = $row['kdsbu'];
                    }
                } else if ($level == 9) {
                    $node['parent'] = $parent;
                    if (isset($hierarchy[$parent])) {
                        $hierarchy[$parent]['children'][] = $row['kdsbu'];
                    }
                }

                $hierarchy[$row['kdsbu']] = $node;
                $currentPrefix = $prefix;
            }

            return $hierarchy;
        } catch (Exception $e) {
            log_message('error', 'Error in get_hierarchical_data: ' . $e->getMessage());
            return array();
        }
    }

    function get_bar_data($kode, $thang)
    {
        try {
            $hierarchy = $this->get_hierarchical_data($kode, $thang);
            if (empty($hierarchy)) return array();

            $result = array();

            // First, organize hierarchy information
            $level2_nodes = array(); // subtitle nodes
            $level3_nodes = array(); // sub-subtitle nodes

            foreach ($hierarchy as $key => $node) {
                if ($node['level'] == 2) {
                    $level2_nodes[$node['kdsbu']] = $node;
                } else if ($node['level'] == 3) {
                    $level3_nodes[$node['kdsbu']] = $node;

                    // Store the parent subtitle for each sub-subtitle
                    if (isset($node['parent']) && isset($hierarchy[$node['parent']]) && $hierarchy[$node['parent']]['level'] == 2) {
                        $level3_nodes[$node['kdsbu']]['subtitle_parent'] = $hierarchy[$node['parent']]['nmsbu'];
                    }
                }
            }

            foreach ($hierarchy as $key => $node) {
                // Only process data nodes
                if ($node['level'] == 9 && $node['biaya'] > 0) {
                    $bar_data = array(
                        'name' => $node['nmsbu'],
                        'data' => $node['biaya']
                    );

                    // Find the immediate parent
                    if (isset($node['parent']) && isset($hierarchy[$node['parent']])) {
                        $parent = $hierarchy[$node['parent']];

                        // Check parent level
                        if ($parent['level'] == 2) {
                            // Parent is subtitle
                            $bar_data['subtitle'] = $parent['nmsbu'];
                        } else if ($parent['level'] == 3 && isset($parent['subtitle_parent'])) {
                            // Parent is sub-subtitle and we have its subtitle parent
                            $bar_data['subtitle'] = $parent['subtitle_parent'];
                            $bar_data['sub_subtitle'] = $parent['nmsbu'];
                        } else if ($parent['level'] == 3) {
                            // Parent is sub-subtitle but we need to find its subtitle parent
                            if (isset($parent['parent']) && isset($hierarchy[$parent['parent']])) {
                                $grandparent = $hierarchy[$parent['parent']];
                                if ($grandparent['level'] == 2) {
                                    $bar_data['subtitle'] = $grandparent['nmsbu'];
                                    $bar_data['sub_subtitle'] = $parent['nmsbu'];
                                }
                            }
                        }
                    }

                    $result[] = $bar_data;
                }
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Error in get_bar_data: ' . $e->getMessage());
            return array();
        }
    }

    function get_boxplot_data($kode, $thang)
    {
        try {
            $hierarchy = $this->get_hierarchical_data($kode, $thang);
            if (empty($hierarchy)) return array();

            $groups = array();

            // Group data by their immediate parent
            foreach ($hierarchy as $node) {
                if ($node['level'] == 9 && $node['biaya'] > 0 && $node['parent']) {
                    $parent = $hierarchy[$node['parent']];
                    $group_key = $parent['kdsbu'];

                    if (!isset($groups[$group_key])) {
                        $groups[$group_key] = array(
                            'x' => $parent['nmsbu'],
                            'level' => $parent['level'],
                            'parent' => $parent['parent'],
                            'values' => array()
                        );
                    }

                    $groups[$group_key]['values'][] = $node['biaya'];
                }
            }

            // Add hierarchical information
            foreach ($groups as $key => &$group) {
                if (isset($group['parent']) && isset($hierarchy[$group['parent']])) {
                    $parent = $hierarchy[$group['parent']];

                    if ($group['level'] == 3 && $parent['level'] == 2) {
                        $group['subtitle'] = $parent['nmsbu'];
                    }
                }
            }

            // Calculate statistics
            return $this->calculate_boxplot_statistics($groups);
        } catch (Exception $e) {
            log_message('error', 'Error in get_boxplot_data: ' . $e->getMessage());
            return array();
        }
    }

    private function calculate_boxplot_statistics($groups)
    {
        $result = array();

        foreach ($groups as $group) {
            if (empty($group['values'])) continue;

            sort($group['values'], SORT_NUMERIC);
            $count = count($group['values']);

            $boxplot = array(
                'x' => $group['x'],
                'y' => array(
                    $group['values'][0], // min
                    $group['values'][floor($count * 0.25)], // Q1
                    $group['values'][floor($count * 0.5)], // median
                    $group['values'][floor($count * 0.75)], // Q3
                    $group['values'][$count - 1] // max
                )
            );

            if (isset($group['subtitle'])) {
                $boxplot['subtitle'] = $group['subtitle'];
            }

            $result[] = $boxplot;
        }

        return $result;
    }
}
