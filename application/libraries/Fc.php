<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Fc {

	function MemVar($jns=null, $unit=null ) {
		$CI =& get_instance();
		// if (! $CI ) redirect("login/show_login");

		if ($jns == null) {
			// $CI->thang  	= $CI->session->userdata('thang');
			// $CI->user   	= $CI->session->userdata('iduser');
			// $CI->group  	= $CI->session->userdata('idusergroup');
			// $CI->whrdept	= $CI->session->userdata('whrdept');
			// $CI->dept   	= $CI->session->userdata('kddept');
			// $CI->unit   	= $CI->session->userdata('kdunit');
			// $CI->satker 	= $CI->session->userdata('kdsatker');

			// $CI->dbref  	= $CI->load->database("ref$CI->thang",TRUE);
			// $CI->survei  	= $CI->load->database("survei", TRUE);
	

			// $CI->dbrdn  = $CI->load->database("redesain2021", TRUE);
		} else {
			if ($unit == '00000') {
				if ($CI->dept == '') $CI->dept = '001';
				if ($CI->unit == '') $CI->unit = '01';
			} else { 
				$CI->dept = substr($unit,0,3);  
				$CI->unit = substr($unit,3,2); 
			}
			$CI->index 		= "jenis='$jns' AND kddept='$CI->dept' AND kdunit='$CI->unit'";	
			$CI->index_sbk 	= "kddept='$CI->dept' AND kdunit='$CI->unit'";			
		
		}
	}
//tes
	function GetPosBy($string, $position) {
		$hasil = explode(',', $string);
		return trim($hasil[$position-1]);
	}

	function ToArr($arr, $idkey) {
		if (! $arr) return array();
		if (! is_array($arr)) return array();
		if (count($arr) == 0) return array();
		$str = array();
		foreach($arr as $row) $str[ $row[$idkey] ] = $row;
		return $str;
	}

	function InVar($arr, $kode) {
		$in = "in (";
		foreach ($arr as $row) {
			if (trim($row[$kode])!='') {
			  if ( !strpos($in, $row[$kode]) ) $in .= "'$row[$kode]'";
			}
		}
		$in = str_replace("''", "','", $in) .")";
		return $in;
	}

	function array_index($a, $subkey) {
		if (count($a) == 0) return array();
		foreach($a as $k=>$v) $b[$k] = strtolower($v[$subkey]);
		asort($b);
		foreach($b as $key=>$val) $c[] = $a[$key];
		return $c;
	}

	function Ref_Keys( $table, $string=null ) {
		$attr='keys';  if ($string)  $attr=$string;

		$primekeys = array(
			't_dept' => array('kode'=>'kddept', 'nama'=>'nmdept'),
			't_unit' => array('kode'=>'kddept,kdunit', 'nama'=>'nmunit'),
			't_program' => array('kode'=>'kddept,kdunit,kdprogram', 'nama'=>'nmprogram'),

			't_user' => array('kode'=>'iduser', 'nama'=>'nmuser'),
			't_rapat_ruang' => array('kode'=>'idruang', 'nama'=>'nmruang'),
			'd_paket' => array('kode'=>'idpaket', 'nama'=>''),
			'd_forum_room' => array('kode'=>'idparent,idchild', 'nama'=>'nmroom'),

			't_esl3' => array('kode'=>'kode', 'nama'=>'uraian')
		);

		switch ($attr) {
	    case "keys": $str = $primekeys[ $table ]['kode']; break;
	    case "kode": $str = "concat(". str_replace(",", ",'.',", $primekeys[ $table ]['kode']) .")"; break;
	    case "nama": $str = $primekeys[ $table ]['nama']; break;
	    default: $str = "";
		}

		return $str;
	}

	function randomKey($length) {
	    // $pool = array_merge(range(0,9), range('a', 'z'), range('A', 'Z'), explode(' ', '@ $ '));
	    $pool = array_merge(range(0,9), range('a', 'z'), range('A', 'Z'));
	    $key  = '';
	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}

	function Download_Data( $db, $table, $string=null ) {
		$where ='';  if ($string)  $where="$string";
		$artbl = explode(',', $table);

		for ($i=0; $i<count($artbl); $i++) {
			$kdkey = $this->Ref_Keys( $artbl[$i], 'kode' );
			$query = $db->query("Select *, $kdkey kdkey From $artbl[$i] $where");

			if (count($artbl)==1) {
				$hasil = $this->ToArr( $query->result_array(), 'kdkey' );
			} else {
				$hasil[ $artbl[$i] ] = $this->ToArr( $query->result_array(), 'kdkey' );
			}
		}
		return $hasil;
	}

	function Ref_Join( $db, $arr_main, $table, $key ) {
		$invar = $this->InVar( $arr_main, $key );
		$kode  = $this->Ref_Keys( $table, 'kode' );
		$nama  = $this->Ref_Keys( $table, 'nama' );

		$query  = $db->query("Select *, $kode kdkey From $table where $kode $invar");
		$t_ref  = $this->ToArr( $query->result_array(), 'kdkey');
		return $this->Left_Join( $arr_main, $t_ref, "$key, $nama=$nama" );
	}

	function Left_Join( $arr_main, $arr_sub, $string ) {
		$arr_colomn = explode(',', $string);
		foreach($arr_main as $key=>$val) {
			$link = $val[ $arr_colomn[0] ];
			if ( count($arr_colomn)>1 ) {
				list($nm1, $nm2) = explode("=", trim($arr_colomn[1]));
				if ( array_key_exists($link, $arr_sub) )  $arr_main[ $key ][ $nm1 ] = $arr_sub[ $link ][ $nm2 ];
			}
			if ( count($arr_colomn)>2 ) {
				list($nm1, $nm2) = explode("=", trim($arr_colomn[2]));
				if ( array_key_exists($link, $arr_sub) )  $arr_main[ $key ][ $nm1 ] = $arr_sub[ $link ][ $nm2 ];
			}
			if ( count($arr_colomn)>3 ) {
				list($nm1, $nm2) = explode("=", trim($arr_colomn[3]));
				if ( array_key_exists($link, $arr_sub) )  $arr_main[ $key ][ $nm1 ] = $arr_sub[ $link ][ $nm2 ];
			}
			if ( count($arr_colomn)>4 ) {
				list($nm1, $nm2) = explode("=", trim($arr_colomn[4]));
				if ( array_key_exists($link, $arr_sub) )  $arr_main[ $key ][ $nm1 ] = $arr_sub[ $link ][ $nm2 ];
			}
			if ( count($arr_colomn)>5 ) {
				list($nm1, $nm2) = explode("=", trim($arr_colomn[5]));
				if ( array_key_exists($link, $arr_sub) )  $arr_main[ $key ][ $nm1 ] = $arr_sub[ $link ][ $nm2 ];
			}
		}
		return $arr_main;
	}

	function tahun($string, $param, $url=null) {
		$tahun = array('tahun','2015','2016','2017','2018','2019','2020');

		if ($string=='array') {
			return $tahun;
		} else if ($string=='combo') {
			$str = '';
			for ($i=0; $i<=count($tahun); $i++) {
				$val  = $i;
				if ($tahun[$i]==$param->tahun) {$sele=" selected=\"selected\"";} else {$sele="";}
				$str .= "<option value=\"$tahun[$i]\" $sele> $tahun[$i] </option>";
			}
			return $str;
		} else if ($string=='dropdown') {
			$str = "<div class=\"dropdown\">
					<button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"tahun\" value=\"$param->tahun\" data-toggle=\"dropdown\">$param->tahun
					<span class=\"caret\"></span></button>
					<ul class=\"dropdown-menu\" role=\"menu\" aria-labelledby=\"tahun\">
				   ";
			for ($i=0; $i<=count($tahun); $i++) {
				$site = str_replace("/$param->tahun/", "/$tahun[$i]/", $url);
				$str .= "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"$site\"> $tahun[$i] </a></li>";
			}
			$str .="</ul>
					</div>
				   ";
			return $str;
		} else if ($string=='readonly') {
			$nil = str_replace("'", "", $param->tahun);
			$str = "<button class=\"btn btn-default\" type=\"button\">$nil</button>";
			return $str;
		}
		return;
	}

	function bulan($string, $param, $url=null) {
		$bulan = array('bulan', 'Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');

		if ($string=='combo') {
			$str = '';
			for ($i=1; $i<=12; $i++) {
				$val = sprintf('%02d', $i);
				if ($i==$param->bulan) {$sele=" selected=\"selected\"";} else {$sele="";}
				$str .= "<option value=\"$val\"$sele> $bulan[$i] </option>";
			}
			return $str;
		} else if ($string=='dropdown') {
			$nil = $bulan[ (int)$param->bulan ];
			$str = "<div class=\"dropdown\">
					<button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"bulan\" value=\"$param->bulan\" data-toggle=\"dropdown\">$nil
					<span class=\"caret\"></span></button>
					<ul class=\"dropdown-menu\" role=\"menu\" aria-labelledby=\"bulan\">
				   ";
			for ($i=1; $i<=12; $i++) {
				$site = str_replace("/$param->bulan", "/".sprintf('%02d', $i), $url);
				$str .= "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"$site\"> $bulan[$i] </a></li>";
			}
			$str .="</ul>
					</div>
				   ";
			return $str;
		} else if ($string=='readonly') {
			$nil = str_replace("'", "", $param->bulan);
			$nil = $bulan[ (int)$nil ];
			$str = "<button class=\"btn btn-default\" type=\"button\">$nil</button>";
			return $str;
		}
		return;
	}

	function idtgl($tgl, $str=null) {
		$tgl = strtotime($tgl);
		if ( date('d-m-Y', $tgl)=='01-01-1970' ) return '&nbsp;';

		if ($str==null) {
				$var = date('d-m-Y', $tgl);
				if ($var=='01-01-1970') $var='&nbsp;';
		}

		if ($str=='tgljam') {
				$bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des');
				$var   = date('d', $tgl) .' '. $bulan[date('m', $tgl)] .' '. date('Y', $tgl) .' '. date('H:i', $tgl) ;
		}

		if ($str=='hari' || $str=='full') {
				$hari  = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jum\'at','Saturday'=>'Sabtu');
				$bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
				$var   = $hari[date('l', $tgl)] .', '. date('d', $tgl) .' '. $bulan[date('m', $tgl)] .' '. date('Y', $tgl);
				if ($str=='full') $var .= ' '. date('H:i', $tgl) . ' WIB';
		}

		if ($str=='hr') {
				$hari  = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jum\'at','Saturday'=>'Sabtu');
				$bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des');
				$var   = $hari[date('l', $tgl)] .', '. date('d', $tgl) .' '. $bulan[date('m', $tgl)] .' '. date('Y', $tgl);
		}

		if ($str=='hr_tgl_bln') {
				$hari  = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jum\'at','Saturday'=>'Sabtu');
				$bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des');
				$var   = $hari[date('l', $tgl)] .', '. date('d', $tgl) .' '. $bulan[date('m', $tgl)];
		}

		if ($str=='mig') {
				$hari  = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jum\'at','Saturday'=>'Sabtu');
				$var   = $hari[date('l', $tgl)];
		}
		if ($str=='tgl') {
				$bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des');
				$var   = date('d', $tgl) .' '. $bulan[date('m', $tgl)] .' '. date('Y', $tgl) ;
		}
		if ($str=='tglfull') {
				$bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
				$var   = date('d', $tgl) .' '. $bulan[date('m', $tgl)] .' '. date('Y', $tgl) ;
		}
		if ($str=='jam') {
				$var   = date('H:i', $tgl) ;
		}
		return $var;
	}

	function ustgl($tgl, $str=null) {
		if ( $tgl=='&nbsp;' ) return '&nbsp;';

		if ($str==null) {
				$arr = explode('-', $tgl);
				$var = $arr[2].'-'. $arr[1] .'-'.$arr[0] ;
		}
		if ($str=='hari' or $str=='full') {
				$bulan = array('Januari'=>'01','Februari'=>'02','Maret'=>'03','April'=>'04','Mei'=>'05','Juni'=>'06','Juli'=>'07','Agustus'=>'08','September'=>'09','Oktober'=>'10','November'=>'11','Desember'=>'12');
				$arr = explode(' ', $tgl);
				$var = $arr[3].'-'.$bulan[ $arr[2] ].'-'.$arr[1] ;
				if ($str=='full') $var .= ' '. $arr[6] .':00 ';
		}
		if ($str=='tgl') {
				$bulan = array('Jan'=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','Mei'=>'05','Jun'=>'06','Jul'=>'07','Agu'=>'08','Sep'=>'09','Okt'=>'10','Nov'=>'11','Des'=>'12');
				$arr = explode(' ', $tgl);
				$var = $arr[2].'-'.$bulan[ $arr[1] ].'-'.$arr[0] ;
		}
		return $var;
	}

	function browse($array, $number=null, $head=null) {
		if (! is_array($array) or ! $array) return;
		if (! isset($array[0])) { $i = 0; foreach ($array as $key=>$row) { $arr[$i] = $row; $i++; }}
		else $arr = $array;

		if (count($arr) > 0) {
			$hasil  = "<table border='1' style='font-size: 80%; border: 1px solid' cellpadding='0px' cellspacing='0px'>\n";
			$hasil .= "<thead>\n<tr style='text-align: center'>\n";

			if ($head == null) $hasil .= "\t<th>". implode("</th>\n\t<th>", array_keys(current($arr))) ."</th>\n";
			else {
				$hed = explode('-', $head);
				$hasil .= "\t<th style='text-align: center' colspan='". $hed[0] ."'>KODE</th>\n";
				$hasil .= "\t<th style='text-align: center' colspan='". $hed[1] ."'>SEMULA</th>\n";
				$hasil .= "\t<th style='text-align: center' colspan='". $hed[2] ."'>MENJADI</th>\n";
				$hasil .= "\t<th style='text-align: center' colspan='". $hed[3] ."'>Valid</th>\n";
				$hasil .= "</tr>\n<tr>\n";
				foreach (array_keys(current($arr)) as $row) $hasil .= "\t<th style='text-align: center'>". $row ."</th>\n";
			}

			$hasil .= "</tr>\n</thead>\n<tbody>\n";

			foreach ($arr as $row) {
				$i = 0; 
				$red = '';
				$colmn = array_keys($row);

				if (isset($row['status']) and ! $row['status']) $red = 'color:red;';

				$hasil .= "<tr>\n";
				foreach ($row as $sub) {
					if (is_array($sub)) $hasil .= "\t<td><a href=''>array</a></td>\n";
					else 
						if ($number == null) $hasil .= "\t<td>$sub</td>\n"; 
						else {
							if (strpos($number, $colmn[$i])) 
								if (fmod(floatval($sub), 1) != 0) $hasil .= "\t<td style='$red text-align:right'>". number_format($sub, 4, ',', '.') ."</td>\n";
								else $hasil .= "\t<td style='$red text-align:right'>". number_format(floatval($sub), 0, ',', '.') ."</td>\n";
							elseif (strpos('-status', $colmn[$i])) 
								if ($sub) $hasil .= "\t<td style='color:green; text-align:center'> true</td>\n";
								else 	  $hasil .= "\t<td style='color:red; text-align:center'> false</td>\n";
							else 
								$hasil .= "\t<td style='$red'>$sub</td>\n";
						}
					$i++;
				}
				$hasil .= "</tr>\n";
			}
			$hasil .= "</tbody>\n</table>";
		}
		return $hasil;
	}

	function read_more( $str ) {
		$arr = explode('<p>', $str);
		if ( count($arr)<3) {
			echo "$str";
		} else {
			$rand = chr(rand(97,122)) . chr(rand(97,122)) . rand();
			$satu = trim( $arr[1] );
			$dua  = str_replace($satu, '', $str);
			$dua  ="
				<div id='hidden_$rand' style='display: none;'> $dua </div>
				<button class='btn btn-sm' style='padding:2px;background-color:transparent;color:#3C8DBC' onclick=ReadMore('hidden_$rand'); return false;'> <em><span id='show_$rand' class='small'>Read More</span></em> </button>";

			echo "$satu $dua";
		}
		return;
	}

	function pagination( $base_url, $total_rows, $per_page, $uri_segment ) {
		$CI =& get_instance();
		$config['total_rows'] = $total_rows;
		$config['base_url'] = $base_url;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = $uri_segment;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li><a href=""  class="active">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="#">';
	    $config['cur_tag_close'] = '</a></li>';
		$CI->pagination->initialize($config);
		return $config;
	}

	function getUserIP(){
	 //   	ob_start(); // Turn on output buffering
		// system(‘ipconfig /all’); //Execute external program to display output
		// $mycom=ob_get_contents(); // Capture the output into a variable
		// ob_clean(); // Clean (erase) the output buffer
		// $findme = “Physical”;
		// $pmac = strpos($mycom, $findme); // Find the position of Physical text
		// $mac=substr($mycom,($pmac+36),17); // Get Physical Address
		// return $mac;
   		$ip=$_SERVER['REMOTE_ADDR'];
		$mac_string = shell_exec("arp -a $ip");
		$mac_array = explode(" ",$mac_string);
		$mac = $mac_array[3];
		return $mac;
	}

	function send_sms($nohp, $pesan) {
		$url   = "https://services.sibisnis.com/masking/api/sendsms";
		$pesan = str_replace('"', '`', $pesan);
		$list  = explode(';', $nohp);

		foreach ($list as $row) {
			$row = trim($row);
			if (substr($row,0,2) == '08') $row = '62'.substr($row, 1);
			$datauser = array(
				'token'  => "d3bUo5J88LJYlNGb89JDyfo5qb2wbL",
				'sender' => "SatuDJA",
				'msisdn' => $row,
				'msg'    => "$pesan"
			);

			$postdatauser = "";
			foreach($datauser as $k => $v) {
				$postdatauser .= $k . "=" . $v."&";
			}

			$curlHandle = curl_init();
			curl_setopt($curlHandle, CURLOPT_URL, $url);
			curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postdatauser);
			curl_setopt($curlHandle, CURLOPT_HEADER, 0);
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
			curl_setopt($curlHandle, CURLOPT_POST, 1);
			$string = curl_exec($curlHandle);
			curl_close($curlHandle);
			// print $string;
		}
	}

	function check_akses( $cari=null ) {
		$CI =& get_instance();
        if (! $CI->session->userdata('isLoggedIn') ) redirect("login/show_login");

		$whr   = "idusergroup in ('". str_replace(';', "','", $CI->session->userdata('idusergroup')) ."')";
		$query = $CI->db->query("Select menu From t_user_group_satu Where $whr");

		$arr = array();
		foreach ($query->result_array() as $row) {
			$str = explode(';', $row['menu']);
			for ($i=0; $i<count($str); $i++) $arr[ substr($str[$i],0,6) ] = substr($str[$i],0,6);
		}

		$whr = "('". implode("','", $arr) ."')";
		$query = $CI->db->query("Select trim(link) link From t_menu Where idmenu in $whr");
		$menu  = $this->ToArr($query->result_array(), 'link');

		if ($CI->session->userdata('idusergroup') == 'usersatker') {
			$thang  = $CI->session->userdata('thang');
			$kddept = $CI->session->userdata('kddept');
			$kdunit = $CI->session->userdata('kdunit');
			$kdsatk = $CI->session->userdata('kdsatker');
			$dbfrm  = $CI->load->database("forum$thang",TRUE);
			if (date('m') < '09') $jnsforum = 'PA'; else $jnsforum = 'AA';

			$qry = $dbfrm->query("SELECT user_satker user FROM d_forum WHERE thang='$thang' AND kddept='$kddept' AND kdunit='$kdunit' AND jnsforum='$jnsforum'");
			$arr = $qry->row_array();

			if ($arr and strpos($arr['user'], $kdsatk) > -1) {
				$query = $CI->db->query("SELECT *, if(right(idmenu,4)='0000','1', if(right(idmenu,2)='00','2', '3')) lvl, '' active FROM t_menu WHERE thang='$thang' AND left(idmenu,2)='25' AND aktif ='1' ORDER BY urutan, idmenu");
				$menu  = array_merge_recursive($query->result_array(), $menu);
			}
		}
		$menu  = $this->ToArr($menu, 'link');

		if (array_key_exists($cari, $menu)) return TRUE;
		else return FALSE;
	}

	function log_ubah_pass( $aktifitas, $userid, $email ) {
		$CI =& get_instance();

        $iduser = $userid;
        $nmuser = $email;
		  $lokasi = $this->get_client_ip();
		  $CI->db->query("Insert Into t_user_satu_aktifitas (iduser, nmuser, waktu, lokasi, aktifitas) Values ('$iduser', '$nmuser', current_timestamp(), '$lokasi', '$aktifitas')");
	}

	function check_depuni() {
		$CI =& get_instance();
		if (! $CI->session->userdata('isLoggedIn') ) redirect("login/show_login");
		$whr = $CI->session->userdata('whrdept');

		if ($CI->session->userdata('idusergroup') == 'usersatker') $whr .= " AND kdsatker='". $CI->session->userdata('kdsatker') ."'";
		if ($CI->session->userdata('idusergroup') == 'userunit')   $whr .= " AND kdunit='". $CI->session->userdata('kdunit') ."'";

		return $whr;
	}

	function checksum_revid( $rev_id ) {
		$CI =& get_instance();
		if (! $CI->session->userdata('isLoggedIn') ) redirect("login/show_login");

		$thang = $CI->session->userdata('thang');
		if(preg_match('/^[0-9\.]+$/i', $rev_id)) {
			if (substr($rev_id, 0, 4) != $thang or (int)substr($rev_id, -3) < 1) return TRUE;
			else return FALSE;
		} else {
			return FALSE;
		}
	}

	function log( $aktifitas ) {
		$CI =& get_instance();
		if (! $CI->session->userdata('isLoggedIn') ) redirect("login/show_login");

		$iduser = $CI->session->userdata('iduser');
		$nmuser = $CI->session->userdata('nmuser');
		$lokasi = $this->get_client_ip();
		$CI->db->query("Insert Into t_user_satu_aktifitas (iduser, nmuser, waktu, lokasi, aktifitas) Values ('$iduser', '$nmuser', current_timestamp(), '$lokasi', '$aktifitas')");
	}

	function logRevisi($rev_id, $aktifitas){
		$CI =& get_instance();
		$CI->thang  = $CI->session->userdata('thang');
		$CI->dbrvs  = $CI->load->database("revisi$CI->thang", TRUE);

		if (! $CI->session->userdata('isLoggedIn') ) redirect("login/show_login"); 

		$iduser = $CI->session->userdata('iduser');
		$nmuser = $CI->session->userdata('nmuser');
		$lokasi = $this->get_client_ip();
		$CI->dbrvs->query("Insert Into revisi_log (iduser, nmuser, waktu, lokasi, rev_id, aktifitas) Values ('$iduser', '$nmuser', current_timestamp(), '$lokasi','$rev_id' ,'$aktifitas')");
	}

	function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	function fileupload($dir, $id=null) {
		$old  = umask(0);
		is_dir($dir) || mkdir($dir, 0777, true);
		copy('files/index.html', "$dir/index.html");
		umask($old);

		if ($id == 0) $id = ''; else $id .= '@';
		$file = $_FILES[ $_POST['name'] ];
		$resp = array('upload'=>'Upload Gagal !');
		if ($file) {
			move_uploaded_file($file["tmp_name"], "$dir/$id". $file['name']);
			$resp = array('upload'=>'Upload Berhasil !', 'file'=>$file['name']);
		}
	    return $resp;
	}

	function arrayToCsvFile($dir, $file, $array) {
        $resp = array();
        if ($array) {
	        array_push($resp, implode('|', array_keys(current($array))) );
	        foreach ($array as $entry) {
	            $row = array();
	            foreach ($entry as $key=>$value) array_push($row, $value);
	            array_push($resp, implode('|', $row));
	        }
	        $csv = implode(PHP_EOL, $resp);
	    } else $csv = '';

		$old = umask(0);
		is_dir("$dir") || mkdir("$dir", 0777, true);
		umask($old);

		$fd  = fopen("$dir$file", "w");
		fputs($fd, $csv);
		fclose($fd);
	}

	function compress($source, $destination) {
		ini_set('max_execution_time', 5000);
	    if (!extension_loaded('zip') || !file_exists($source)) return false;

	    $zip = new ZipArchive();
	    if (!$zip->open($destination, ZIPARCHIVE::CREATE))  return false;

	    $source = realpath($source);
	    if (is_dir($source) === true) {
	        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

	        foreach ($files as $file) {
	            $file = str_replace('\\', '/', $file);
	            if ( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) continue;

	            $file = realpath($file);
	            if (is_dir($file) === true) $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
	            else if (is_file($file) === true) {
	            	$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file)); 	// aslinya '\\' diubah jadi '/'
	            }
	        }
	    } else if (is_file($source) === true) {
	        $zip->addFromString(basename($source), file_get_contents($source));
	    }

	    $zip->close();
        $rar = file_get_contents($destination);
        unlink($destination);
        return $rar;
	}

	function arrayToCsvRar($dir, $file, $array) {
        $resp = array();
        array_push($resp, implode('|', array_keys(current($array))) );
        foreach ($array as $entry) {
            $row = array();
            foreach ($entry as $key=>$value) array_push($row, $value);
            array_push($resp, implode('|', $row));
        }
        $csv = implode(PHP_EOL, $resp);

		$old = umask(0);
		is_dir("$dir") || mkdir("$dir", 0777, true);
		umask($old);

        $zip = new ZipArchive;
        $zip->open("$dir/$file.rar", ZIPARCHIVE::CREATE);
        $zip->addFromString("$file.csv", $csv);
        $zip->close();

        $rar = file_get_contents("$dir/$file.rar");
        unlink("$dir/$file.rar");
        return $rar;
	}

	function compress_rar($dir, $file, $ext='.csv') {
        $zip = new ZipArchive;
        $zip->open("$dir/$file", ZIPARCHIVE::CREATE);
        $iterasi = new RecursiveIteratorIterator(new RecursiveDirectoryIterator( "$dir/tmp" ));
        foreach ($iterasi as $key=>$value) {
            if (strpos($key, $ext)) {
            	$zip->addFile($key);
            	unlink($key);
            }
        }
        $zip->close();
	}

	function terbilang ($angka) {
        $angka = (float)$angka;
        $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . ' Belas';
        } else if ($angka < 100) {
            $hasil_bagi = (int)($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) { return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) { $hasil_bagi = (int)($angka / 100); $hasil_mod = $angka % 100; return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 2000) { return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
        } else if ($angka < 1000000) { $hasil_bagi = (int)($angka / 1000); $hasil_mod = $angka % 1000; return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
        } else if ($angka < 1000000000) { $hasil_bagi = (int)($angka / 1000000); $hasil_mod = $angka % 1000000; return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) { $hasil_bagi = (int)($angka / 1000000000); $hasil_mod = fmod($angka, 1000000000); return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) { $hasil_bagi = $angka / 1000000000000; $hasil_mod = fmod($angka, 1000000000000); return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else {
            return 'Data Salah';
        }
	}

	function hitsec ($start=null) {
		if ($start == null) {
			$start = microtime(true); 
			return $start;
		} else {
			$time  = (microtime(true) - $start) * 1000000;
			$seco  = floor($time / 1000000);
			$mili  = sprintf("%06d", $time);
			echo "<br> Waktu Proses : $seco.$mili detik";
		}
	}

	function del_column($arr, $col) {
		foreach ($arr as $key=>$row) unset( $arr[$key][$col] );
		return $arr;
	}

    function grid($arr, $head=null) {
        $hasil  = "<table border='1' style='font-size: 90%; border: 1px solid #eee' cellpadding='0px' cellspacing='0px'>\n";
        $hasil .= "<thead>\n<tr style='text-align: center'>\n";
        if ($head != null) $hasil .= "\t<th colspan='2'> $head </th>\n";
        $hasil .= "</tr>\n</thead>\n<tbody>\n";

        foreach ($arr as $key=>$row) {
            $hasil .= "\t<tr>\n";
            if (! is_array($row)) $hasil .= "\t\t<td style='background-color: #ddd'> $key </td>\n\t\t<td><b> $row </b></td>\n";
            else $hasil .= "\t\t<td style='background-color: #ddd'> $key </td>\n\t\t<td>". $this->grid( $row ) ."</td>\n";
            $hasil .= "\t</tr>\n";
        }
        $hasil .= "</tbody>\n</table>\n";
        return $hasil;
    }

    function curl( $ar ) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        	// CURLOPT_PORT => $ar['port'],
            CURLOPT_URL => $ar['url'],
            CURLOPT_CUSTOMREQUEST => $ar['post'],
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        if (isset($ar['data']))
            curl_setopt($curl, CURLOPT_POSTFIELDS, $ar['data']);

        if (isset($ar['token'])) 
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				"Authorization: Bearer " . $ar['token'],
				"Cache-Control: no-cache",
				"Connection: keep-alive",
				"Content-Length: 280605",
				"Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gWa"
			));

        if (isset($ar['header'])) 
			curl_setopt($curl, CURLOPT_HTTPHEADER, $ar['header']);

        if (isset($ar['file'])) { 
            foreach ($ar['file'] as $ky=>$ro) {
                if (strpos($ro, '.png')) $tp = 'image/png';
                if (strpos($ro, '.pdf')) $tp = 'application/pdf';

                $dt[$ky] = curl_file_create( realpath($ar['folder'].'/'.$ro), $tp); 
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dt); 
            curl_setopt($curl, CURLOPT_UPLOAD, 1);
            // echo '<pre>'; print_r($dt);
        }

        if (isset($ar['auth'])) {
            curl_setopt($curl, CURLOPT_USERPWD, $ar['auth']);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); }

        $resp = curl_exec($curl);
        $err  = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return array('code'=>$code, 'data'=>$resp);
    }

    function hitrun( $nil ) {
		$CI =& get_instance();

    	if ($nil == 0) {
			list($usec, $sec) = explode(" ", microtime());
			$CI->start = ((float)$usec + (float)$sec);
    	} else {
		   $end  = microtime(true);
		   $time = number_format((float)$end - $CI->start, 2, '.', '');
		   echo "Running in $time seconds\n <br><br>";
		   $CI->start = $this->hitrun(0);
		   return "Running in $time seconds";
    	}
  }

		function createNvim ()
		{
			$a = 10;
			$b = 20;
		}

    function browse_table($array, $set=null) {
			$CI =& get_instance();
			if (! is_array($array) or ! $array) return;
			if (! isset($array[0])) { $i = 0; foreach ($array as $key=>$row) { $arr[$i] = $row; $i++; }}
			else $arr = $array;

			// Declare Variabel
			$num      = (isset($set['num']))    ? $set['num']     : '';
			$center   = (isset($set['center'])) ? $set['center']  : '';
			$right    = (isset($set['right']))  ? $set['right']   : '';
			$font     = (isset($set['font']))   ? $set['font']    : '';
			$hide     = (isset($set['hide']))   ? $set['hide']    : '';

			$title    = (isset($set['title']))  ? $set['title']   : 'SatuDJA';
			$bread    = (isset($set['bread']))  ? $set['bread']   : '';
			$note     = (isset($set['note']))   ? $set['note']    : '';
			$show	  = (isset($set['show']))   ? true 			  : false;

			$bold = (isset($set['bold'])) ? $set['bold'] : '';
				if ($bold != '') { $bold_row = explode(':', $bold)[0]; $bold_nil = explode(':', $bold)[1]; }
			$grey = (isset($set['grey'])) ? $set['grey'] : '';
				if ($grey != '') { $grey_row = explode(':', $grey)[0]; $grey_nil = explode(':', $grey)[1]; }
			$bgcolor = (isset($set['bgcolor'])) ? $set['bgcolor'] : '';
				if ($bgcolor != '') {
					$c = explode(':', $bgcolor); 
					$bgcolor_row = $c[0];  $bgcolor_nil = $c[1];  $bgcolor_warna = 'bg'.$c[2]; }

			if ($font=='') {
				$n = count(array_keys(current($arr))) - count(explode('-', $hide));
				if ($n<4) $font='100%'; elseif ($n<8) $font='90%'; elseif ($n<12) $font='80%'; else $font='75%';
			}

		$width = ($show) ? "width='100%'" : '';
		$grid  = "<table id='iBrowse' border='1' style='font-size: $font; border: 1px solid' $width cellpadding='0px' cellspacing='0px'>\n";
		$grid .= "<thead>\n<tr>\n";

		foreach (array_keys(current($arr)) as $row) {
			if (! strpos("-$hide", $row))
			$grid .= "\t<th style='text-align: center'><b>&nbsp;". str_replace('_',' ',$row) . "&nbsp;</b></th>\n";
		}
		$grid .= "</tr>\n</thead>\n<tbody>\n";

		foreach ($arr as $v) {
			$class = $style = '';
			if ($bold != '' and $v[$bold_row] != '') if (strpos("-$bold_nil", $v[$bold_row])) $style .= 'font-weight: bold;';
			if ($grey != '' and $v[$grey_row] != '') if (strpos("-$grey_nil", $v[$grey_row])) $style .= 'color: #808080;';
			if ($bgcolor != '' and $v[$bgcolor_row] != '') 
				if (strpos("-$bgcolor_nil", $v[$bgcolor_row])) $class .= $bgcolor_warna;

			$grid .= "<tr class='$class' style='$style'>\n";
			foreach ($v as $ky=>$vl) {
				if (is_array($vl)) $grid .= "\t<td><a href=''>array</a></td>\n";
				elseif (! strpos("-$hide", $ky)) {
					$align = 'text-align: left ';
					if (strpos("-$center", $ky))  $align = 'text-align: center';
					if (strpos("-$right", $ky))   $align = 'text-align: right';
					if (strpos("-$num", $ky))     $align = 'text-align: right';

					if (strpos("-$num", $ky)) $vl = number_format($vl, 0, ',', '.');
					else 
						if (substr($vl,0,1) == '0') $vl = "&nbsp;$vl"; 
						else $vl = "$vl";

					$grid .= "\t<td style='$align' class='c$ky txt'>$vl</td>\n";
				} 
			}
			$grid .= "</tr>\n";
		}
		$grid .= "</tbody>\n</table>\n";

		if (! $show) return $grid; 
		else {
			$site = base_url();
			$time = (isset($CI->start)) ? '<b>'. $this->timer(1) .'</b> seconds with' : '';
			$info = "Page rendered in $time <b>". number_format(count($arr), 0, ',', '.') ."</b> records ";
			$view ="
				<head>
					<title> $title </title>
					<link  href='$site/favicon.ico' rel='icon'>
					<script src='$site/assets/plugins/jQueryUI/jquery-ui.1.11.4.min.js' type='text/javascript'></script>
					<script src='$site/assets/dist/js/excellentexport.js' type='text/javascript'></script>
				</head>				
				<style>
					#iTable  { overflow-y: auto; height: 85%; }
					#iBrowse th { position: sticky; top: 0px; }
					#iBrowse { font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; border-collapse: collapse; }
					#iBrowse td, #iBrowse th { border: 1px solid #ddd; padding: 6px; }
					#iBrowse tr:nth-child(even) { background-color: #F2F2F2; }
					#iBrowse tr:hover { background-color: #ddd; }
					#iBrowse th { padding-top: 10px; padding-bottom: 10px; text-align: left; background-color: #2760A9; color: white; }

					.iButton { font-size: 12px; color: #000; border-style: solid; border-width : 1px 1px 1px 1px; text-decoration: none; padding: 4px; background-color: #ddd; border-color: #bbb; text-align: right; float: right;}
					.iLink 	 { border: 0px solid black; text-decoration: none; padding: 4px; border-color: #222; }
					.bggreen { background-color: #98FB98 !important; }
				</style>

				<div>
					<div class='iHeader' style='background-color: #fff; height: 55px; padding-top: 5px;'>
						<div style='margin-bottom: 5px;' align='center'> 
							<span style='font-size: 125%; font-family: arial'><strong> $title </strong></span>
						</div>
						<div> 
							<span style='font-family: arial'>$bread</span>
							<span class='small'><a class='iButton' download='$title.xls' href='#' onclick=\"return ExcellentExport.excel(this, 'iBrowse', 'SatuDJA');\"> &nbsp; &nbsp;File Excel&nbsp; &nbsp;</a></span>
						</div>
					</div>
					<div class='iBody'>
				         <div id='iTable'> $grid </div>
					</div>
				</div>

				<div>
					<div style='font-size: 90%; float: left; padding-top:10px'><i> $note </i></div>
					<div style='float: right; text-align:right; padding-top:10px'> $info </div>
				</div>";
			echo $view;
			// $CI->load->view('main/v_browse_table', array('grid'=>$view, 'title'=>$title, 'judul'=>$judul, 'excel'=>$excel)); 
		}
    }

    function timer( $nil=null ) {
		$CI =& get_instance();

    	if ($nil == null) {
			list($usec, $sec) = explode(" ", microtime());
			$CI->start = ((float)$usec + (float)$sec);
    	} else {
			$end  = microtime(true);
			$time = number_format((float)$end - $CI->start, 2, '.', '');
			return $time;
    	}
    }

    function summary_v_adk_dipa($kode, $whr) {
		$CI =& get_instance();
		$rev = $CI->dbrvs->query("SELECT kdsatker, concat(kdsatker,max(revisike)) kode FROM v_adk_dipa WHERE $whr GROUP BY 1")->result_array();
		$inv = "concat(kdsatker,revisike) ". $this->Invar($rev, 'kode');
		$arr = $CI->dbrvs->query("SELECT concat($kode) kode, sum(pagu) jml FROM v_adk_dipa WHERE $inv GROUP BY 1")->result_array(); 
		return $arr;
    }

    function summary_d_realisasi($kode, $whr) {
		$CI =& get_instance();
		$a = array('kddept'=>'substr(baes1,1,3)', 'kdunit'=>'substr(baes1,4,2)');
		foreach ($a as $k=>$v) {
			$kode = str_replace($k, $v, $kode);  $whr  = str_replace($k, $v, $whr); }

		$arr = $CI->dbrvs->query("SELECT concat($kode) kode, sum(rphreal) jml FROM d_realisasi WHERE $whr GROUP BY 1")->result_array();
		return $arr;
    }

	function oaToken() {
		$CI 		=& get_instance();
		$resp 		= $CI->db->query("SELECT * FROM t_mytask_akses")->row();

		$url 		= $resp->url;
		$client_id  = $resp->client_id;
		$client_s   = $resp->client_sec;
		$grant_type = $resp->grant_type;
		$scope      = $resp->scope;
		$timeToken  = $resp->token_timestamp;
		$token 		= $resp->token;
		$now 	    = date('Y-m-d H:i:s');

		if($timeToken < $now){
			$data = array(
				'client_id'     => $client_id,
				'grant_type'    => $grant_type,
				'client_secret' => $client_s,
				'scope'         => $scope
			);
	
			$options = array(
				'http' => array(
					'header'    => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'    => 'POST',
					'content'   => http_build_query($data)
				)
			);
	
			$context 		= stream_context_create($options);
			$result  		= file_get_contents($url, false, $context);
			$result  		= json_decode($result, true);
			$token 			= $result['access_token'];
			$token_timestamp= date('Y-m-d H:i:s', strtotime('+1 hour'));
			$resp 			= $CI->db->query("UPDATE t_mytask_akses SET token='$token', token_timestamp='$token_timestamp' ");
	
		}else {
			$token = $resp->token;
		}
		
		return $token;
	}

	function CreateTaskOa($buttonid,$nip) 
	{
		// check userDJA
		$checkDJA 		= $this->checkDJA($nip);

		if(!$checkDJA){
			return false;
		}

		$CI 			=& get_instance();
		$resp 			= $CI->db->query("SELECT * FROM t_mytask_link WHERE buttonid='$buttonid'")->row();
		$utc_datetime 	= new DateTime('now', new DateTimeZone('UTC'));
		$utc_datetime->setTimezone(new DateTimeZone('Asia/Bangkok')); // Change 'Asia/Bangkok' to the desired timezone
		$starTime 		= $utc_datetime->format('Y-m-d\TH:i:s\Z');
		$endTime 		= $utc_datetime->modify('+'.$resp->duration.' minutes')->format('Y-m-d\TH:i:s\Z');

		if($resp){
			$produkId 		= $resp->produkid;
			$duration 		= $resp->duration;
			$startTime      = $starTime;
			$endTime 	    = $endTime;
			$userId 		= $nip;
			$tahapanid 		= $resp->tahapanid;
		}else{
			$produkId 		= '';
			$duration 		= '';
			$startTime      = '';
			$endTime 	    = '';
			$userId 		= '';
			$tahapanid 		= '';
		}

		if($tahapanid == '' || $tahapanid == 0){
			return false;
		}
		

		$url        = "https://service.kemenkeu.go.id/otomasi.agenda/api/SystemTask/Create";
		$token      = $this->oaToken();
        $req = array(
            "userID" => $userId,
            "message" => array(
                array (
                    "startTime" => $startTime,
                    "endTime" 	=> $endTime,
                    "duration"  => $duration,
                    "tahapanID" => $tahapanid,
                )
                
            )
        );

        // Set cURL options
        $curlOptions = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Content-Type: application/json"
            ),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($req)
        );

        
		// Initialize cURL session
		$ch = curl_init($url);
		curl_setopt_array($ch, $curlOptions);
		$response = curl_exec($ch);

		// Check for cURL errors
		if ($response === false) {
			$log = json_encode(['error' => curl_error($ch)]);
			$isError = 1;
		} else {
			$resp = json_decode($response);
			$log = json_encode($resp);

			if ($resp->statusCode == 200) {
				$isError = 0;
			} else {
				$isError = 1;
			}
		}
		// Close the cURL session
		curl_close($ch);
		$userId = $CI->db->escape($userId);
		$buttonid = $CI->db->escape($buttonid);
		$log = $CI->db->escape($log);
		$userId = trim($userId, "'");
		$buttonid = trim($buttonid, "'");
		$log = trim($log, "'");
		$log = $CI->db->escape_str($log);

		$sql = "INSERT INTO t_mytask_log (iduser, buttonid,tahapanid, iserror, datetime, logdetail) VALUES ('$userId', '$buttonid','$tahapanid', $isError, now(), '$log')";
		$CI->db->query($sql);
	}

	function checkDJA($nip)  {
		$CI 			=& get_instance();
		$arr 			= $CI->db->query("SELECT idusergroup FROM t_user_satu WHERE nip='$nip'")->row();
		$grp 			= substr($arr->idusergroup, 0, 3);

		if($grp == 'dja' || $grp == 'adm'){
			return true;
		}else{
			return false;
		}
	}
}
