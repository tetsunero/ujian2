<?php
	require("../config/config.default.php");
	require("../config/config.function.php");
	require("../config/functions.crud.php");
	require("../config/dis.php");
	(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
	($id_pengawas==0) ? header('location:login.php'):null;
	error_reporting(0);
	echo "<link rel='stylesheet' href='../dist/bootstrap/css/bootstrap.min.css'/>";
	$idwali = $_GET['id'];
	$mapel = mysql_fetch_array(mysql_query("SELECT kelas.`level`,walikls.id_kelas,pengawas.nip,pengawas.nama FROM kelas INNER JOIN walikls ON kelas.id_kelas = walikls.id_kelas INNER JOIN pengawas ON pengawas.id_pengawas = walikls.id_pengawas WHERE walikls.idwali='$idwali'"));
	if(date('m')>=7 AND date('m')<=12) {
		$ajaran = date('Y')."/".(date('Y')+1);
	}
	elseif(date('m')>=1 AND date('m')<=6) {
		$ajaran = (date('Y')-1)."/".date('Y');
	}
	echo "
		<!DOCTYPE html>
		<html>
			<head>
				<title>Rekapitulasi Kelas $mapel[id_kelas]</title>
				<style>
					* { margin:auto; padding:0; line-height:100%; }
					body { margin: 0 auto;}
					td { padding:1px 3px 1px 3px; }
					.garis { border:1px solid #000; border-left:0px; border-right:0px; padding:1px; margin-top:5px; margin-bottom:5px; }
				</style>
			</head>
			<body>
				<table border='0' cellspacing='0' cellpadding='0' width='100%'>
					<tr>
						<td width='90px' align='left'><img src='$homeurl/$setting[logo_instansi]' width='70px' height='90px'/></td>
						<td style='text-align: center;'>
							<font size=+2><b>$setting[header]</b></font><br/>
							<font size=+3><b>$setting[sekolah]</b></font><br/>
							<small>$setting[alamat] &nbsp; Telp. $setting[telp] Fax. $setting[fax]</small><br/>
							<small><i>Email: $setting[email] &nbsp; Web: $setting[web]</i></small><br/>
						</td>
						<td width='90px' align='left'><img src='$homeurl/$setting[logo]' width='90px'/></td>
					</tr>
				</table>
				<div class='garis'></div>
				<div align='center'>
					<b><font size=10px>REKAPITULASI NILAI $setting[namaujian] - BK</b><br/>
					<b>KELAS ".strtoupper($mapel['id_kelas'])."</b><br/>
					<b>TAHUN AJARAN $ajaran</b>
				</div><br>
													<table class='table table-bordered'>
													<thead>
														<tr>
															<th width='5px' class='text-center'>#</th>
															<th class='text-center'>NIS</th>
															<th class='text-center'>Nama</th>";
																$pela = mysql_query("SELECT mata_pelajaran.kode_mapel,mata_pelajaran.nama_mapel,mapel.kelas FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama where mapel.`level`='".$mapel['level']."'");
																while($pelaj = mysql_fetch_array($pela)) {
																		$dataArray = unserialize($pelaj['kelas']);foreach ($dataArray as $key => $value) {
																			if($value == $mapel['id_kelas']){
																				echo"<th class='text-center' title='".$pelaj['nama_mapel']."'>".$pelaj['kode_mapel']."</th>";
																			}
																		}
																}
														echo"
														</tr>
													</thead>
													<tbody>";
													$guruku = mysql_query("SELECT siswa.id_siswa,siswa.nis,siswa.nama FROM walikls INNER JOIN siswa ON walikls.id_kelas = siswa.id_kelas where walikls.id_kelas='".$mapel['id_kelas']."' ORDER BY siswa.nama ASC");
													while($pengawas = mysql_fetch_array($guruku)) {
														$no++;
														echo "
															<tr>
																<td class='text-center'>$no</td>
																<td class='text-center'>$pengawas[nis]</td>
																<td>$pengawas[nama]</td>";
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
													</tbody>
				</table>
				<br/>
				<table border='0' width='793.700787402px' align='center' cellspacing='0' cellpadding='0'>
					<tr>
						<td>
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
						<td width='230px'>
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
			</body>
		</html>
	";
clearstatcache();
?>