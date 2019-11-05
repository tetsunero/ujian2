<?php
(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
($id_pengawas==0) ? header('location:login.php'):null;
$pengawas = mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE id_pengawas='$id_pengawas'"));
							if($ac=='') {
							echo "
								<div class='row'>
									<div class='col-md-12'>$pesan
										<div class='box box-solid '>
											<div class='box-header with-border bg-blue'>
												<h3 class='box-title'>Analisis Soal</h3>				
											</div>
											<div class='box-body'>
											<div class='table-responsive'>
												<table id='example1' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>Mata Pelajaran</th>
															<th>Soal PG</th>
															<th>Soal Esai</th>
															<th>Kelas</th>
															<th></th>
														</tr>
													</thead>
													<tbody>";
													if($pengawas['level']=='admin'){
														$mapelQ = mysql_query("SELECT mapel.*,mata_pelajaran.nama_mapel FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama ORDER BY mata_pelajaran.nama_mapel ASC");
													}
													elseif($pengawas['level']=='guru'){
														$mapelQ = mysql_query("SELECT mapel.*,mata_pelajaran.nama_mapel FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama WHERE idguru = '$id_pengawas' ORDER BY mata_pelajaran.nama_mapel ASC");
													}
													while($mapel = mysql_fetch_array($mapelQ)) {
														$cek=mysql_num_rows(mysql_query("select * from soal where id_mapel='$mapel[id_mapel]'"));
														//parsing array														
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>
																"; if($mapel['idpk']=='0'){$jur='Semua';}else{$jur=$mapel['idpk'];} echo "
																<b><small class='label bg-purple' title='$mapel[nama_mapel]'>$mapel[nama]</small></b> 
																<small class='label label-primary'>$mapel[level]</small>
																<small class='label label-primary'>$jur</small>
																</td>";
														echo"
																<td><small class='label label-warning'>$mapel[tampil_pg]/$mapel[jml_soal]</small> <small class='label label-danger'>$mapel[bobot_pg] %</small></td>
																<td><small class='label label-warning'>$mapel[tampil_esai]/$mapel[jml_esai]</small> <small class='label label-danger'>$mapel[bobot_esai] %</small></td>
																<td>"; 
																$dataArray = unserialize($mapel['kelas']);
																foreach ($dataArray as $key => $value) {
																	echo "<small class='label label-success'>$value</small>&nbsp;";
																}
																echo "</td>
																<td><a href='?pg=$pg&ac=lihat&id=$mapel[id_mapel]'><button class='btn btn-success btn-xs'><i class='fa fa-search'></i></button></a></td>
															</tr>";
													}
													echo"															
													</tbody>
												</table>
												</div>												
											</div>
										</div>
									</div>
								</div>";
								} // lihat nilai
							elseif($ac=='lihat') {								
								$id_mapel = $_GET['id'];
                                $mapel = mysql_fetch_array(mysql_query("SELECT mapel.tampil_pg,mapel.tampil_esai,mapel.bobot_pg,mapel.bobot_esai,mapel.`level`,mapel.kelas,mata_pelajaran.kode_mapel,mata_pelajaran.nama_mapel FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama WHERE mapel.id_mapel='$id_mapel'"));
								$torerata = mysql_fetch_array(mysql_query("SELECT ROUND((SUM(total)/COUNT(id_nilai)),2) AS jlh_siswa FROM nilai WHERE id_mapel='$id_mapel'"));
								echo "
									<div class='row'>
										<div class='col-md-12'>
											<div class='box box-solid'>
												<div class='box-header with-border bg-blue'>
													<h3 class='box-title'>Analisis Soal</h3>
													<div class='box-tools pull-right btn-group'>
														<button class='btn btn-sm btn-primary' onclick=frames['frameresult'].print()><i class='fa fa-print'></i> Print</button>
														
														<a class='btn btn-sm btn-primary' href='report_excel_analisis.php?m=$id_mapel&k=$id_kelas'><i class='fa fa-file-excel-o'></i> Excel</a>
														
														<a class='btn btn-sm btn-danger' href='?pg=anso'><i class='fa fa-times'></i> Keluar</a>
													</div>
												</div>
												<div class='box-body'>
													<table class='table table-bordered table-striped'> 
													<tr><th width='150'>Mata Pelajaran</th><td width='10'>:</td><td>$mapel[nama_mapel]</td><td width='150' align='center'>Nilai Rata-Rata</td></tr>
													<tr><th >Tingkat</th><td width='10'>:</td><td>$mapel[level]</td><td rowspan='2' width='150' align='center' style='font-size:30px; text-align: center; vertical-align:middle;'>$torerata[jlh_siswa]</td></tr>
													<tr><th >Kelas</th><td width='10'>:</td><td>";$dataArray = unserialize($mapel['kelas']);foreach ($dataArray as $key => $value) { echo "[$value]&nbsp;";}echo"</td></tr>
													</table><br>
												<div class='table-responsive'>
													<table class='table table-bordered table-striped'>
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
																		echo"<span class='text-bold'>Jawaban: A.</span>Format Gambar/Audio";
																	}
																	else{
																		echo"<span class='text-bold'>Jawaban: A.</span>$soal[pilA]";
																	}
																}
																if($soal['jawaban'] == 'B'){
																	if($soal['pilB'] == ''){
																		echo"<span class='text-bold'>Jawaban: B.</span>Format Gambar/Audio";
																	}
																	else{
																		echo"<span class='text-bold'>Jawaban: B.</span>$soal[pilB]";
																	}
																}
																if($soal['jawaban'] == 'C'){
																	if($soal['pilC'] == ''){
																		echo"<span class='text-bold'>Jawaban: C.</span>Format Gambar/Audio";
																	}
																	else{
																		echo"<span class='text-bold'>Jawaban: C.</span>$soal[pilC]";
																	}
																}
																if($soal['jawaban'] == 'D'){
																	if($soal['pilD'] == ''){
																		echo"<span class='text-bold'>Jawaban: D.</span>Format Gambar/Audio";
																	}
																	else{
																		echo"<span class='text-bold'>Jawaban: D.</span>$soal[pilD]";
																	}
																}
																if($soal['jawaban'] == 'E'){
																	if($soal['pilE'] == ''){
																		echo"<span class='text-bold'>Jawaban: E.</span>Format Gambar/Audio";
																	}
																	else{
																		echo"<span class='text-bold'>Jawaban: E.</span>$soal[pilE]";
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
													<iframe name='frameresult' src='reportanso.php?m=$id_mapel' style='border:none;width:1px;height:1px;'></iframe>
												</div>
												</div>
											</div>
										</div>
									</div>
								";
							}
?>