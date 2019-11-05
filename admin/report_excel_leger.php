<?php
	require("../config/config.default.php");
	require("../config/config.function.php");
	require("../config/functions.crud.php");
	require("../config/dis.php");
	(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
	($id_pengawas==0) ? header('location:login.php'):null;
	echo "<style> .str{ mso-number-format:\@; } </style>";
	$idwali = $_GET['id'];
	$mapel = mysql_fetch_array(mysql_query("SELECT kelas.`level`,walikls.id_kelas,pengawas.nip,pengawas.nama FROM kelas INNER JOIN walikls ON kelas.id_kelas = walikls.id_kelas INNER JOIN pengawas ON pengawas.id_pengawas = walikls.id_pengawas WHERE walikls.idwali='$idwali'"));
	if(date('m')>=7 AND date('m')<=12) {
		$ajaran = date('Y')."/".(date('Y')+1);
	}
	elseif(date('m')>=1 AND date('m')<=6) {
		$ajaran = (date('Y')-1)."/".date('Y');
	}
	$file = "LEGER_NILAI_".$mapel['id_kelas'];
	$file = str_replace(" ","-",$file);
	$file = str_replace(":","",$file);
	header("Content-type: application/octet-stream");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Content-Disposition: attachment; filename=".$file.".xls");
	echo "

		";
		echo "<br/>
		<table border='0'>
		<td style='vertical-align:middle; text-align:center;' colspan='16' rowspan='2'>
		<font size=+4><b>DAFTAR LEGER NILAI KELAS ".$mapel['id_kelas']."</b></font><br/>
		<font size=+2><b>TAHUN AJARAN ".$ajaran."</b>
		</td>
		</table>
		<br/>
		
		<table border='1'>
													
														<tr>
															<th width='5px'>No</th>
															<th colspan='2'>NIS</th>
															<th colspan='4'>Nama</th>";
																$pela = mysql_query("SELECT mata_pelajaran.kode_mapel,mata_pelajaran.nama_mapel,mapel.kelas FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama where mapel.`level`='".$mapel['level']."'");
																while($pelaj = mysql_fetch_array($pela)) {
																		$dataArray = unserialize($pelaj['kelas']);foreach ($dataArray as $key => $value) {
																			if($value == $mapel['id_kelas']){
																				echo"<th title='".$pelaj['nama_mapel']."'>".$pelaj['kode_mapel']."</th>";
																			}
																		}
																}
														echo"
														</tr>
													
													";
													$guruku = mysql_query("SELECT siswa.id_siswa,siswa.nis,siswa.nama FROM walikls INNER JOIN siswa ON walikls.id_kelas = siswa.id_kelas where walikls.id_kelas='".$mapel['id_kelas']."' ORDER BY siswa.nama ASC");
													while($pengawas = mysql_fetch_array($guruku)) {
														$no++;
														echo "
															<tr>
																<td width='5px'>$no</td>
																<td align='left' colspan='2'>$pengawas[nis]</td>
																<td colspan='4'>$pengawas[nama]</td>";
																$pelai = mysql_query("SELECT mata_pelajaran.kode_mapel,mata_pelajaran.nama_mapel,mapel.id_mapel,mapel.kelas FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama where mapel.`level`='".$mapel['level']."'");
																while($pelaji = mysql_fetch_array($pelai)) {
																		$dataArrayi = unserialize($pelaji['kelas']);foreach ($dataArrayi as $keyi => $valuei) {
																			if($valuei == $mapel['id_kelas']){
																				$toni = mysql_fetch_array(mysql_query("SELECT total FROM nilai where id_mapel='".$pelaji['id_mapel']."' and id_siswa='".$pengawas['id_siswa']."'"));
																				echo"<th class='text-center'>".$toni['total']."</th>";
																			}
																		}
																}
														echo"
															</tr>
														";
													}
													echo "
													
				</table>
				<br/>
				<table border='0'>
					<tr>
						<td style='vertical-align:middle; text-align:center;' colspan='8'>
							Mengetahui, <br/>
							Kepala Sekolah <br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<u>$setting[kepsek]</u><br/>
							NIP. $setting[nip]
						</td>
						<td style='vertical-align:middle; text-align:center;' colspan='8'>
							$setting[kota], ".buat_tanggal('d M Y')."<br/>
							Wali Kelas ".$mapel['id_kelas']."<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<u>".$mapel['nama']."</u><br/>
							NIP. ".$mapel['nip']."
						</td>
					</tr>
				</table>
	
	";
?>