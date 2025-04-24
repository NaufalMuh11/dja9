<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Perbandingan_model extends CI_Model
{
    // Tambahkan fungsi ini ke Perbandingan_model.php
    private function get_hierarchical_data_from_table($kode, $thang)
    {
        try {
            $ref = $this->load->database('ref' . $thang, TRUE);

            // Cek jika tabel hierarki ada
            $table_exists = $ref->table_exists('t_sbu_hierarchy');

            if ($table_exists) {
                // Gunakan tabel hierarki baru
                $query = $ref->query("
                WITH RECURSIVE hierarchy AS (
                    -- Anchor: ambil title (level 1) dengan kode yang sesuai
                    SELECT 
                        h.kdsbu,
                        h.nmsbu,
                        h.prov,
                        h.level,
                        h.parent_kdsbu,
                        s.biaya,
                        s.biaya12,
                        CAST(h.kdsbu AS CHAR(50)) as path
                    FROM t_sbu_hierarchy h
                    LEFT JOIN t_sbu s ON h.kdsbu = s.kdsbu
                    WHERE h.level = 1 AND LEFT(h.kdsbu, 3) = ?
                    
                    UNION ALL
                    
                    -- Recursive: ambil semua anak dari setiap node di hierarki
                    SELECT 
                        c.kdsbu,
                        c.nmsbu,
                        c.prov,
                        c.level,
                        c.parent_kdsbu,
                        s.biaya,
                        s.biaya12,
                        CONCAT(h.path, ',', c.kdsbu) as path
                    FROM t_sbu_hierarchy c
                    JOIN hierarchy h ON c.parent_kdsbu = h.kdsbu
                    LEFT JOIN t_sbu s ON c.kdsbu = s.kdsbu
                )
                SELECT * FROM hierarchy
                ORDER BY path
            ", array($kode));

                if (!$query || $query->num_rows() == 0) {
                    // Fallback ke metode lama jika tidak ada data
                    return $this->get_hierarchical_data_old($kode, $thang);
                }

                $rows = $query->result_array();
                $hierarchy = array();

                foreach ($rows as $row) {
                    // Jika data SBU tidak ada, gunakan nilai default
                    $biaya = isset($row['biaya']) ? (float)$row['biaya'] : 0;
                    $biaya12 = isset($row['biaya12']) ? (float)$row['biaya12'] : 0;

                    $node = array(
                        'kdsbu' => $row['kdsbu'],
                        'nmsbu' => $row['nmsbu'],
                        'prov' => $row['prov'],
                        'biaya' => $biaya,
                        'biaya12' => $biaya12,
                        'level' => (int)$row['level'],
                        'parent' => $row['parent_kdsbu'],
                        'children' => array()
                    );

                    $hierarchy[$row['kdsbu']] = $node;

                    // Tambahkan ke children array dari parent
                    if ($row['parent_kdsbu'] && isset($hierarchy[$row['parent_kdsbu']])) {
                        $hierarchy[$row['parent_kdsbu']]['children'][] = $row['kdsbu'];
                    }
                }

                return $hierarchy;
            } else {
                // Gunakan metode lama jika tabel belum ada
                return $this->get_hierarchical_data_old($kode, $thang);
            }
        } catch (Exception $e) {
            log_message('error', 'Error in get_hierarchical_data_from_table: ' . $e->getMessage());
            // Fallback ke metode lama jika terjadi error
            return $this->get_hierarchical_data_old($kode, $thang);
        }
    }

    private function get_hierarchical_data_old($kode, $thang)
    {
        try {
            $ref = $this->load->database('ref' . $thang, TRUE);

            $query = $ref->query("
                SELECT 
                    kdsbu,
                    nmsbu,
                    biaya,
                    biaya12,
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
                    'biaya12' => isset($row['biaya12']) ? (float)$row['biaya12'] : 0,
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

    public function get_titles_from_hierarchy($thang)
    {
        try {
            $thang = $this->input->get('thang') ?: date('Y');

            $ref = $this->load->database('ref' . $thang, TRUE);

            // Cek jika tabel hierarki ada
            $table_exists = $ref->table_exists('t_sbu_hierarchy');

            if ($table_exists) {
                // Ambil semua judul (level 1)
                $query = $ref->query("
                    SELECT kdsbu, nmsbu 
                    FROM t_sbu_hierarchy 
                    WHERE level = 1 
                    ORDER BY kdsbu
                ");

                if ($query && $query->num_rows() > 0) {
                    $result = $query->result_array();
                    return $result;
                }
            }

            $result = $query->result_array();
            return $result;
        } catch (Exception $e) {
            log_message('error', 'Error in get_titles_from_hierarchy: ' . $e->getMessage());
            return array();
        }
    }

    function get_boxplot_data($kode, $thang)
    {
        try {
            $hierarchy = $this->get_hierarchical_data_from_table($kode, $thang);
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

    function get_comparison_data($kode, $thang, $sortOrder)
    {
        try {
            $hierarchy = $this->get_hierarchical_data_from_table($kode, $thang);
            if (empty($hierarchy)) return array();

            $result = array();

            // First, check if we have any level 3 nodes
            $hasLevel3 = false;
            foreach ($hierarchy as $key => $node) {
                if ($node['level'] == 3) {
                    $hasLevel3 = true;
                    break;
                }
            }

            foreach ($hierarchy as $key => $node) {
                // Only process data nodes
                if ($node['level'] == 9 && $node['biaya'] > 0) {
                    $biaya_current = $node['biaya'];
                    $biaya_previous = $node['biaya12'] ?? 0;
                    $difference = $biaya_current - $biaya_previous;

                    // Calculate percentage change, handle division by zero
                    $percentage_change = 0;
                    if ($biaya_previous > 0) {
                        $percentage_change = round(($difference / $biaya_previous) * 100, 2);
                    } elseif ($biaya_current > 0) {
                        $percentage_change = 100;
                    }

                    $comparison_data = array(
                        'name' => $node['prov'],
                        'biaya_current' => $biaya_current,
                        'biaya_previous' => $biaya_previous,
                        'difference' => $difference,
                        'percentage_change' => $percentage_change
                    );

                    // Find the immediate parent
                    if (isset($node['parent']) && isset($hierarchy[$node['parent']])) {
                        $parent = $hierarchy[$node['parent']];

                        // Check parent level
                        if ($parent['level'] == 2) {
                            // Parent is subtitle
                            if ($hasLevel3) {
                                // If we have level 3 nodes in hierarchy, treat level 2 as subtitle
                                $comparison_data['subtitle'] = $parent['nmsbu'];
                            } else {
                                // If no level 3 nodes, treat level 2 as sub-subtitle
                                $comparison_data['sub_subtitle'] = $parent['nmsbu'];

                                // Find level 1 parent and use as subtitle
                                if (isset($parent['parent']) && isset($hierarchy[$parent['parent']])) {
                                    $grandparent = $hierarchy[$parent['parent']];
                                    if ($grandparent['level'] == 1) {
                                        $comparison_data['subtitle'] = $grandparent['nmsbu'];
                                    }
                                }
                            }
                        } else if ($parent['level'] == 3) {
                            // Parent is sub-subtitle
                            $comparison_data['sub_subtitle'] = $parent['nmsbu'];

                            // Find subtitle parent (level 2)
                            if (isset($parent['parent']) && isset($hierarchy[$parent['parent']])) {
                                $grandparent = $hierarchy[$parent['parent']];
                                if ($grandparent['level'] == 2) {
                                    $comparison_data['subtitle'] = $grandparent['nmsbu'];
                                }
                            }
                        }
                    }

                    $result[] = $comparison_data;
                }
            }

            // Apply sorting
            if ($sortOrder === 'asc') {
                usort($result, function ($a, $b) {
                    return $a['biaya_current'] - $b['biaya_current'];
                });
            } else if ($sortOrder === 'desc') {
                usort($result, function ($a, $b) {
                    return $b['biaya_current'] - $a['biaya_current'];
                });
            } else if ($sortOrder === 'diff_asc') {
                usort($result, function ($a, $b) {
                    return $a['difference'] - $b['difference'];
                });
            } else if ($sortOrder === 'diff_desc') {
                usort($result, function ($a, $b) {
                    return $b['difference'] - $a['difference'];
                });
            } else if ($sortOrder === 'percent_asc') {
                usort($result, function ($a, $b) {
                    return $a['percentage_change'] - $b['percentage_change'];
                });
            } else if ($sortOrder === 'percent_desc') {
                usort($result, function ($a, $b) {
                    return $b['percentage_change'] - $a['percentage_change'];
                });
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Error in get_comparison_data: ' . $e->getMessage());
            return array();
        }
    }
}
