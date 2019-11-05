<?php
(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
($id_pengawas==0) ? header('location:login.php'):null;
$pengawas = mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE id_pengawas='$id_pengawas'"));
if($ac=='') {
								echo "
									<div class='row'>
										<div class='col-md-12'>										
												<div class='box box-primary'>
													<div class='box-header with-border'>
														<h3 class='box-title'>Rekapitulasi Wali Kelas</h3>
														<div class='box-tools pull-right btn-group'>															
														</div>
													</div><!-- /.box-header -->
													<div class='box-body'>$info
													<div class='table-responsive'>
														<table id='example1' class='table table-bordered table-striped'>
															<thead>
																<tr>
																	<th width='5px' class='text-center'>#</th>
																	<th class='text-center'>Kelas</th>
																	<th class='text-center'>NIP</th>
																	<th class='text-center'>Nama</th>
																	<th width=60px></th>
																</tr>
															</thead>
															<tbody>";
															if($pengawas['level']=='guru') {
																$guruku = mysql_query("SELECT walikls.idwali,kelas.id_kelas,pengawas.nip,pengawas.nama FROM pengawas INNER JOIN walikls ON pengawas.id_pengawas = walikls.id_pengawas INNER JOIN kelas ON kelas.id_kelas = walikls.id_kelas where pengawas.level='guru' and pengawas.id_pengawas='".$pengawas['id_pengawas']."' ORDER BY kelas.`level` ASC,kelas.id_kelas ASC");
															}
															elseif($pengawas['level']=='admin') {
																$guruku = mysql_query("SELECT walikls.idwali,kelas.id_kelas,pengawas.nip,pengawas.nama FROM pengawas INNER JOIN walikls ON pengawas.id_pengawas = walikls.id_pengawas INNER JOIN kelas ON kelas.id_kelas = walikls.id_kelas where pengawas.level='guru' ORDER BY kelas.`level` ASC,kelas.id_kelas ASC");
															}
															while($pengawas = mysql_fetch_array($guruku)) {
																$no++;
																echo "
																	<tr>
																		<td class='text-center'>$no</td>
																		<td class='text-center'>$pengawas[id_kelas]</td>
																		<td class='text-center'>$pengawas[nip]</td>
																		<td>$pengawas[nama]</td>
																		<td align='center'>
																		<div class='btn-group'>
																			<a href='?pg=$pg&ac=lihat&id=$pengawas[idwali]'> <button class='btn btn-xs btn-primary'><i class='fa fa-search'></i></button></a>
																		</div>
																		</td>
																	</tr>
																";
															}
															echo "
															</tbody>
														</table>
													</div>
													</div>
												</div>											
										</div>
									</div>
								";
							} // lihat nilai
							elseif($ac=='lihat') {
								$idwali = $_GET['id'];
                                $mapel = mysql_fetch_array(mysql_query("SELECT kelas.`level`,walikls.id_kelas,pengawas.nip,pengawas.nama FROM kelas INNER JOIN walikls ON kelas.id_kelas = walikls.id_kelas INNER JOIN pengawas ON pengawas.id_pengawas = walikls.id_pengawas WHERE walikls.idwali='$idwali'"));
								echo "
									<div class='row'>
										<div class='col-md-12'>
											<div class='box box-solid'>
												<div class='box-header with-border bg-blue'>
													<h3 class='box-title'>Rekapitulasi Nilai Siswa Kelas $mapel[id_kelas]</h3>
													<div class='box-tools pull-right btn-group'>
														<button class='btn btn-sm btn-primary' onclick=frames['frameresult'].print()><i class='fa fa-print'></i> Print</button>
														<a class='btn btn-sm btn-primary' href='report_excel_leger.php?id=$idwali'><i class='fa fa-times'></i> Excel</a>
														<a class='btn btn-sm btn-danger' href='?pg=rekapnilai'><i class='fa fa-times'></i> Keluar</a>
													</div>
												</div>
												<div class='box-body'>
												<div class='table-responsive'>
												<table class='table table-bordered table-striped'>
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
													<tfoot>
														<tr>
															<th width='5px' class='text-center'>#</th>
															<th class='text-center'>NIS</th>
															<th class='text-center'>Nama</th>";
																$pela2 = mysql_query("SELECT mata_pelajaran.kode_mapel,mata_pelajaran.nama_mapel,mapel.kelas FROM mata_pelajaran INNER JOIN mapel ON mata_pelajaran.kode_mapel = mapel.nama where mapel.`level`='".$mapel['level']."'");
																while($pelaj2 = mysql_fetch_array($pela2)) {
																		$dataArray2 = unserialize($pelaj2['kelas']);foreach ($dataArray2 as $key2 => $value2) {
																			if($value2 == $mapel['id_kelas']){
																				echo"<th class='text-center' title='".$pelaj2['nama_mapel']."'>".$pelaj2['kode_mapel']."</th>";
																			}
																		}
																}
														echo"
														</tr>
													</tfoot>
												</table>
													<iframe name='frameresult' src='reportwali.php?id=$idwali' style='border:none;width:1px;height:1px;'></iframe>
													</div>
												</div>
											</div>
										</div>
									</div>
								";
							clearstatcache();
							}
							?>