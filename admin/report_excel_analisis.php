<?php
	require("../config/config.default.php");
	require("../config/config.function.php");
	require("../config/functions.crud.php");
	require("../config/dis.php");
	(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
	($id_pengawas==0) ? header('location:login.php'):null;
	echo "<style> .str{ mso-number-format:\@; } </style>";
	$id_mapel = $_GET['m'];
	$id_kelas = $_GET['k'];
	$pengawas = fetch('pengawas',array('id_pengawas'=>$id_pengawas));
	$mapel = fetch('mapel',array('id_mapel'=>$id_mapel));
	$kelas = fetch('kelas',array('id_kelas'=>$id_kelas));
	if(date('m')>=7 AND date('m')<=12) {
		$ajaran = date('Y')."/".(date('Y')+1);
	}
	elseif(date('m')>=1 AND date('m')<=6) {
		$ajaran = (date('Y')-1)."/".date('Y');
	}
	$file = "ANALISIS_SOAL_".$mapel['tgl_ujian']."_".$mapel['nama']."_".$kelas['nama'];
	$file = str_replace(" ","-",$file);
	$file = str_replace(":","",$file);
	header("Content-type: application/octet-stream");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Content-Disposition: attachment; filename=".$file.".xls");
	echo "
		Mata Pelajaran: $mapel[nama]<br/>
		Tanggal Ujian: ".buat_tanggal('D, d M Y - H:i',$mapel['tgl_ujian'])."<br/>
		Jumlah Soal: $mapel[jml_soal]<br/>
		
		Kelas: "; 
		$dataArray = unserialize($mapel['kelas']);
		foreach ($dataArray as $key => $value) {
		echo "<small class='label label-success'>$value</small>&nbsp;";
		}
		echo "<br/>
		<table border='1'>
											
															<tr>
																<th width='5px'>No</th>
																<th colspan='5'>Soal Pilihan Ganda</th>
																<th>Responden</th>
																<th>Benar</th>	
																<th>Salah</th>	
																<th>Kualitas Soal</th>	
																<th>Pencapaian</th>	
															</tr>";
														$isso = mysql_query("SELECT soal.id_soal,soal.nomor,soal.soal,soal.jawaban,soal.pilA,soal.pilB,soal.pilC,soal.pilD,soal.pilE FROM mapel INNER JOIN soal ON mapel.id_mapel = soal.id_mapel WHERE mapel.id_mapel='$id_mapel' and soal.jenis = 1 order by soal.nomor ASC");
														while($soal=mysql_fetch_array($isso)){
															echo"															
															<tr>
																<td>$soal[nomor]</td>
																<td colspan='5' width='500px'>$soal[soal]<br>";
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
																<td style='vertical-align:middle; text-align:center;'>";
																$jsis = mysql_fetch_array(mysql_query("SELECT COUNT(id_jawaban) AS jsiswa FROM hasil_jawaban WHERE id_soal= '$soal[id_soal]'"));
																echo"$jsis[jsiswa]
																</td>
																
																<td style='vertical-align:middle; text-align:center;'>";
																$jben = mysql_fetch_array(mysql_query("SELECT SUM(IF(soal.jawaban = hasil_jawaban.jawaban,1,0)) AS kunci FROM soal INNER JOIN hasil_jawaban ON soal.id_soal = hasil_jawaban.id_soal WHERE soal.id_soal = '$soal[id_soal]'"));
																echo"$jben[kunci]
																</td>
																
																<td style='vertical-align:middle; text-align:center;'>";
																$jsal = mysql_fetch_array(mysql_query("SELECT SUM(IF(soal.jawaban <> hasil_jawaban.jawaban,1,0)) AS kunci FROM soal INNER JOIN hasil_jawaban ON soal.id_soal = hasil_jawaban.id_soal WHERE soal.id_soal = '$soal[id_soal]'"));
																echo"$jsal[kunci]
																</td>
																
																<td style='vertical-align:middle; text-align:center;'>";
																$anali = round((($jben['kunci']/$jsis['jsiswa'])*100),0); 
																if($anali <= 30){
																	$hhsil = "<b><p style='color:#FF0000';>SULIT</p></b>"; 
																	} elseif($anali <= 60){ 
																	$hhsil = "<b><p style='color:#FF9900';>SEDANG</p></b>";
																	}elseif($anali >= 70){ 
																	$hhsil = "<b><p style='color:#0000FF';>MUDAH</p></b>"; 
																} 
																echo"$hhsil
																</td>
																<td style='vertical-align:middle; text-align:center;'>";
																$anali2 = round((($jben['kunci']/$jsis['jsiswa'])*100),2); 
																echo"$anali2 %</td>
															</tr>
															";
														}
														echo"
				</table>
	";
?>