<?php
if($ac=='') {
	echo "
									<div class='row'>
										<div class='col-md-12'>
										<div class='alert alert-warning '>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Status peserta akan muncul saat ujian berlangsung,<br>
													<i class='icon fa fa-info'></i>
													jeda waktu update realtime hasil siswa adalah 5 menit.<br>
													<i class='icon fa fa-info'></i>
													Tekan tombol <b>Refresh hasil siswa</b> untuk mempercepat.
													</div>
											<div class='box box-primary'>
												<div class='box-header with-border'>
													<h3 class='box-title'>Status Peserta </h3>
													<div class='box-tools pull-left btn-group'>";
															echo "																
																<a id='refreshstatus' class='btn btn-success btn-sm' title='Refresh Hasil Siswa'>
																<i class='glyphicon glyphicon-refresh'></i> Refresh Hasil Siswa</a>											
													</div>
												</div><!-- /.box-header -->
												<div class='box-body'>
												<div class='table-responsive'>
													<table  class='table table-bordered table-striped'>
														<thead>
															<tr>
																<th width='5px'>#</th>
																<th>NIS</th>
																<th>Nama</th>
																<th>Kelas</th>
																<th>Mapel</th>
																<th>Lama Ujian</th>
																<th>Jawaban</th>
																<th>Nilai</th>
																<th>Ip Address</th>
																<th >Status</th>
																
															</tr>
														</thead>
														<tbody id='divstatus'>	";
$pengawas = mysql_fetch_array(mysql_query("SELECT * FROM pengawas  WHERE id_pengawas='$_SESSION[id_pengawas]'"));
$tglsekarang = date('Y-m-d');							
if($pengawas['level']=='admin') {
	$nilaiq = mysql_query("SELECT *  FROM nilai  s LEFT JOIN ujian c ON s.id_mapel=c.id_mapel  where c.status='1' GROUP by s.id_nilai DESC");
}else{
	$nilaiq = mysql_query("SELECT *  FROM nilai  s LEFT JOIN ujian c ON s.id_mapel=c.id_mapel  where c.status='1' and c.id_guru='$_SESSION[id_pengawas]' GROUP by s.id_nilai DESC");
}

while($nilai = mysql_fetch_array($nilaiq)) {	
	$tglx=strtotime($nilai['ujian_mulai']);
	$tgl=date('Y-m-d',$tglx);
		if($tgl==$tglsekarang){
			$no++;
			$ket = '';
			$lama = $jawaban = $skor = '--';
			$siswa= mysql_fetch_array(mysql_query("SELECT * FROM siswa WHERE id_siswa='$nilai[id_siswa]'"));
			$kelas = mysql_fetch_array(mysql_query("SELECT * FROM kelas WHERE id_kelas='$siswa[id_kelas]'"));
			$mapel = mysql_fetch_array(mysql_query("SELECT * FROM mapel WHERE id_mapel='$nilai[id_mapel]'"));
			$nilaiQ = mysql_query("SELECT * FROM nilai WHERE id_siswa='$siswa[id_siswa]'");
			$nilaiC = mysql_num_rows($nilaiQ);
			
			if($nilai['ujian_selesai']=='') {
				$idm = $nilai['id_mapel'];
				$ids = $nilai['id_siswa'];
				$idk = $nilai['id_kelas'];				
				$where = array(
					'id_mapel' => $idm,
					'id_siswa' => $ids,
				);
				$benar = $salah = 0;
				$mapel = fetch('mapel',array('id_mapel'=>$idm));
				$siswa = fetch('siswa',array('id_siswa'=>$ids));
				$ceksoal = select('soal',array('id_mapel'=>$idm,'jenis'=>'1'));
				foreach($ceksoal as $getsoal) {
					$w = array(
						'id_siswa' => $ids,
						'id_mapel' => $idm,
						'id_soal' => $getsoal['id_soal'],
						'jenis' => '1'
					);
					$cekjwb = rowcount('jawaban',$w);
						if($cekjwb<>0) {
							$getjwb = fetch('jawaban',$w);
							($getjwb['jawaban']==$getsoal['jawaban']) ? $benar++ : $salah++;
						} else {
							//$salah++;
						}
				}
				$bagi = $mapel['jml_soal']/100;
				$bobot= $mapel['bobot_pg']/100;
				$skor = ($benar/$bagi)*$bobot;
				$data = array(
					'jml_benar' => $benar,
					'jml_salah' => $salah,
					'skor' => $skor
				);
				update('nilai',$data,$where);
			}
			
			$etong = $salah + $benar;
			if($nilaiC<>0) {
				$lama = '';
				
				if($nilai['ujian_mulai']<>'' AND $nilai['ujian_selesai']<>'') {
					$selisih = strtotime($nilai['ujian_selesai'])-strtotime($nilai['ujian_mulai']);
					$jam = round((($selisih%604800)%86400)/3600);
					$mnt = round((($selisih%604800)%3600)/60);
					$dtk = round((($selisih%604800)%60));
					($jam<>0) ? $lama .= "$jam jam ":null;
					($mnt<>0) ? $lama .= "$mnt menit ":null;
					($dtk<>0) ? $lama .= "$dtk detik ":null;
					$jawaban = "
					<small class='label bg-red'>Dikerjakan: $mapel[jml_soal] <i class='fa fa-times'></i></small>
					<small class='label bg-green'>Benar: $nilai[jml_benar] <i class='fa fa-check'></i></small>  
					<small class='label bg-red'>Salah: $nilai[jml_salah] <i class='fa fa-times'></i></small>
					";
					$skor = "<small class='label bg-green'>".number_format($nilai['skor'],2,'.','')."</small>";
					$ket = "<label class='label label-success'>Tes Selesai</label>";
					
				}
				elseif($nilai['ujian_mulai']<>'' AND $nilai['ujian_selesai']=='') {
					$selisih = strtotime($nilai['ujian_berlangsung'])-strtotime($nilai['ujian_mulai']);
					$jam = round((($selisih%604800)%86400)/3600);
					$mnt = round((($selisih%604800)%3600)/60);
					$dtk = round((($selisih%604800)%60));
					($jam<>0) ? $lama .= "$jam jam ":null;
					($mnt<>0) ? $lama .= "$mnt menit ":null;
					($dtk<>0) ? $lama .= "$dtk detik ":null;
					$jawaban = "
					<small class='label bg-red'>Dikerjakan: $etong dari $mapel[jml_soal]</small>
					<small class='label bg-green'>Benar: $nilai[jml_benar] <i class='fa fa-check'></i></small>  
					<small class='label bg-red'>Salah: $nilai[jml_salah] <i class='fa fa-times'></i></small>
					";
					$skor = "<small class='label bg-green'>".number_format($nilai['skor'],2,'.','')."</small>";
					$ket = "<label class='label label-danger'><i class='fa fa-spin fa-spinner' title='Sedang ujian'></i>&nbsp;Masih Dikerjakan</label> <a href='?pg=nilai&ac=selesai&idm=$mapel[id_mapel]&idk=$siswa[id_kelas]&ids=$siswa[id_siswa]' class='btn btn-xs btn-primary'>Selesaikan</a>";
					
				}
	
			}
	echo "
		<tr>
			<td>$no</td>
			<td>$siswa[nis]</td>
			<td>$siswa[nama]</td>
			<td>$kelas[nama]</td>
			<td><small class='label bg-purple'>$mapel[nama]</small> <small class='label bg-blue'>$mapel[level]</small></td>
			<td>$lama</td>
			<td>$jawaban</td>
			<td>$skor</td>
			<td>$nilai[ipaddress]</td>
			<td>$ket</td>
			
		</tr>
	";
	}
}
echo"
														</tbody>
													</table>
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>
									</div>
								";
}
?>