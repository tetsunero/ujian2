<html>
<body>
<head></head>
<?php
(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
($id_pengawas==0) ? header('location:login.php'):null;
$pengawas = mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE id_pengawas='$id_pengawas'"));
if($ac==''){
	//cek_session_admin();
						echo "
								<div class='row'>
									<div class='col-md-8'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Manajemen Wali Kelas</h3>
												
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class=''>
											<div class='table-responsive'>
												<table id='example1' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>No</th>
															<th>Walikelas</th>
															<th>NIP</th>
															<th>Kelas</th>
															<th width=60px></th>
														</tr>
													</thead>
													<tbody>";
													$guruku = mysql_query("SELECT walikls.idwali,kelas.id_kelas,pengawas.nip,pengawas.nama FROM pengawas INNER JOIN walikls ON pengawas.id_pengawas = walikls.id_pengawas INNER JOIN kelas ON kelas.id_kelas = walikls.id_kelas where pengawas.level='guru' ORDER BY kelas.`level` ASC,kelas.id_kelas ASC");
													while($pengawas = mysql_fetch_array($guruku)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$pengawas[nama]</td>
																<td class='text-center'>$pengawas[nip]</td>
																<td class='text-center'>$pengawas[id_kelas]</td>
																<td align='center'>
																<div class='btn-group'>
																<a href='?pg=hapuswali&ac=hapus&id=$pengawas[idwali]' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-trash'></i></a>
																</div>
																</td>
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									</div>
									<div class='col-md-4'>";
										if($ac=='') {
											if(isset($_POST['submit'])) {
												$id= $_POST['idwali'];
												$idpengawas= $_POST['id_pengawas'];
												$namakelas = $_POST['id_kelas'];
												
												$cekwali = mysql_num_rows(mysql_query("SELECT * FROM walikls WHERE id_kelas='$namakelas'"));
												if($cekwali>0) {
													$info = info("MAAF! Nama Kelas $namakelas sudah ada!","NO");
												}else {
												$exec = mysql_query("INSERT INTO walikls(idwali,id_pengawas,id_kelas) VALUES ('$id','$idpengawas','$namakelas')");
														(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
											}
											}
											echo "
												<form action='' method='post'>
													<div class='box box-primary'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Tambah</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
															<div class='form-group'>
																<label>ID WALI</label>
																<select name='idwali' class='form-control' required='true'>";
																$walikelas = mysql_query("SELECT * from pengawas where level='guru'  ORDER BY nama ASC");	
																
																while($wali = mysql_fetch_array($walikelas)) {
																
																	echo "<option value='$wali[id_pengawas]'>$wali[id_pengawas] $wali[nama]</option>";
																}
																echo"
															</select>
															</div>
															
															<div class='form-group'>
																<label>NAMA WALI KELAS</label>
																<select name='id_pengawas' class='form-control' required='true'>";

																$walikelas = mysql_query("SELECT * from pengawas where level='guru'  ORDER BY nama ASC");	
																
																while($wali = mysql_fetch_array($walikelas)) {
																
																	echo "<option value='$wali[id_pengawas]'>$wali[nama]</option>";
																}
																echo"
																</select>
															</div>
															
															<div class='form-group'>
																<label>NAMA KELAS</label>
																<select name='id_kelas' class='form-control' required='true'>";
																$walikelas = mysql_query("SELECT * from kelas");	
																
																while($wali = mysql_fetch_array($walikelas)) {
																
																	echo "<option value='$wali[id_kelas]'>$wali[nama]</option>";
																}
																echo"
																</select>
															</div>
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}		
									
						}
?>
</body>
</html>