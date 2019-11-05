<?php
	require("../config/config.default.php");
	require("../config/config.function.php");
	require("../config/functions.crud.php");
	require("../config/dis.php");
	(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
	($id_pengawas==0) ? header('location:login.php'):null;
	error_reporting(0);	
	echo "<link rel='stylesheet' href='../dist/bootstrap/css/bootstrap.min.css'/>";
	$id_mapel = $_GET['m'];
	$mapel = mysql_fetch_array(mysql_query("SELECT mapel.tampil_pg,mapel.tampil_esai,mapel.bobot_pg,mapel.bobot_esai,mapel.`level`,mapel.kelas,mata_pelajaran.kode_mapel,mata_pelajaran.nama_mapel,pengawas.nip,pengawas.nama FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama INNER JOIN pengawas ON pengawas.id_pengawas = mapel.idguru WHERE mapel.id_mapel='$id_mapel'"));
	$torerata = mysql_fetch_array(mysql_query("SELECT ROUND((SUM(total)/COUNT(id_nilai)),2) AS jlh_siswa FROM nilai WHERE id_mapel='$id_mapel'"));
	$kelas = fetch('kelas',array('id_kelas'=>$id_kelas));
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
				<title>Analisa Soal $mapel[nama_mapel]</title>
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
					<b>ANALISA SOAL $setting[namaujian] - BK</b><br/>
					<b>MATA PELAJARAN ".strtoupper($mapel['nama_mapel'])."</b><br/>
					<b>TAHUN AJARAN $ajaran</b>
				</div><br>
													<table class='table table-bordered'> 
													<tr><th width='150'>Mata Pelajaran</th><td width='10'>:</td><td>$mapel[nama_mapel]</td><td width='150' align='center'>Nilai Rata-Rata</td></tr>
													<tr><th >Tingkat</th><td width='10'>:</td><td>$mapel[level]</td><td rowspan='2' width='150' align='center' style='font-size:30px; text-align: center; vertical-align:middle;'>$torerata[jlh_siswa]</td></tr>
													<tr><th >Kelas</th><td width='10'>:</td><td>";$dataArray = unserialize($mapel['kelas']);foreach ($dataArray as $key => $value) { echo "[$value]&nbsp;";}echo"</td></tr>
													</table>
				<table class='table table-bordered'>
														<thead>
															<tr>
																<th width='5px'>#</th>
																<th>Soal Pilihan Ganda</th>
																<th style='text-align:right'>Responden</th>
																<th style='text-align:right'>Benar</th>	
																<th style='text-align:right'>Salah</th>	
																<th style='text-align:center'>Analisis</th>		
																<th style='text-align:center'>Pencapaian</th>
															</tr>
														</thead>
														<tbody>";
														$isso = mysql_query("SELECT soal.id_soal,soal.nomor,soal.soal,soal.jawaban,soal.pilA,soal.pilB,soal.pilC,soal.pilD,soal.pilE FROM mapel INNER JOIN soal ON mapel.id_mapel = soal.id_mapel WHERE mapel.id_mapel='$id_mapel' and soal.jenis = 1 order by soal.nomor ASC");
														while($soal=mysql_fetch_array($isso)){
															echo"															
															<tr>
																<td>$soal[nomor]</td>
																<td>$soal[soal]<br>";
																if($soal['jawaban'] == 'A'){
																	if($soal['pilA'] == ''){
																		echo"Jawaban: Format Gambar/Audio";
																	}
																	else{
																		echo"Jawaban: $soal[pilA]";
																	}
																}
																if($soal['jawaban'] == 'B'){
																	if($soal['pilB'] == ''){
																		echo"Jawaban: Format Gambar/Audio";
																	}
																	else{
																		echo"Jawaban: $soal[pilB]";
																	}
																}
																if($soal['jawaban'] == 'C'){
																	if($soal['pilC'] == ''){
																		echo"Jawaban: Format Gambar/Audio";
																	}
																	else{
																		echo"Jawaban: $soal[pilC]";
																	}
																}
																if($soal['jawaban'] == 'D'){
																	if($soal['pilD'] == ''){
																		echo"Jawaban: Format Gambar/Audio";
																	}
																	else{
																		echo"Jawaban: $soal[pilD]";
																	}
																}
																if($soal['jawaban'] == 'E'){
																	if($soal['pilE'] == ''){
																		echo"Jawaban: Format Gambar/Audio";
																	}
																	else{
																		echo"Jawaban: $soal[pilE]";
																	}
																}
																echo"
																</td>
																<td style='text-align:right'>";$jsis = mysql_fetch_array(mysql_query("SELECT COUNT(id_jawaban) AS jsiswa FROM hasil_jawaban WHERE id_soal= '$soal[id_soal]'"));echo"$jsis[jsiswa]</td>
																<td style='text-align:right'>";$jben = mysql_fetch_array(mysql_query("SELECT SUM(IF(soal.jawaban = hasil_jawaban.jawaban,1,0)) AS kunci FROM soal INNER JOIN hasil_jawaban ON soal.id_soal = hasil_jawaban.id_soal WHERE soal.id_soal = '$soal[id_soal]'"));echo"$jben[kunci]</td>
																<td style='text-align:right'>";$jsal = mysql_fetch_array(mysql_query("SELECT SUM(IF(soal.jawaban <> hasil_jawaban.jawaban,1,0)) AS kunci FROM soal INNER JOIN hasil_jawaban ON soal.id_soal = hasil_jawaban.id_soal WHERE soal.id_soal = '$soal[id_soal]'"));echo"$jsal[kunci]</td>
																<td style='text-align:center'>";$anali = round((($jben['kunci']/$jsis['jsiswa'])*100),0); if($anali <= 30){ $hhsil = "<span class='label label-danger'>Sulit</span>"; } elseif($anali <= 70){ $hhsil = "<span class='label label-warning'>Sedang</span>"; }elseif($anali >= 71){ $hhsil = "<span class='label label-primary'>Mudah</span>"; } echo"$hhsil</td>
																<td style='text-align:center'>";$anali2 = round((($jben['kunci']/$jsis['jsiswa'])*100),2); 
																echo"$anali2 %</td>
															</tr>
															";
														}
														echo"
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
							Guru Mata Pelajaran<br/>
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
?>