<?php
	require("../config/config.default.php");
	require("../config/config.function.php");
	require("../config/functions.crud.php");
	require("../config/dis.php");

	(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
	($id_pengawas==0) ? header('location:index.php'):null;
	$idserver = $setting['kode_sekolah'];
	echo "<link rel='stylesheet' href='$homeurl/dist/css/cetak.min.css'>";
	
	$sesi =@$_GET['id_sesi'];
	$mapel =@$_GET['id_mapel'];
	$ruang =@$_GET['id_ruang'];
	$kelas =@$_GET['id_kelas'];
	$id_kelas =@$_GET['id_kelas'];
	if($sesi=='' and $ruang=='' and $mapel==''){
		die('Tidak ada data yang dicetak. Anda harus memilih semua variabel: mapel, sesi dan ruang');
	}
	$lebarusername='10%';
	$lebarnopes='17%';
	$namaruang = mysql_fetch_array(mysql_query("SELECT * FROM ruang WHERE kode_ruang='$ruang'"));

	$querytanggal=mysql_query("SELECT * FROM ujian WHERE id_mapel='$mapel'");
	$cektanggal=mysql_fetch_array($querytanggal);
	$mapelx=mysql_fetch_array(mysql_query("SELECT * FROM mapel WHERE id_mapel='$mapel'"));					
	$namamapel=	mysql_fetch_array(mysql_query("SELECT * FROM mata_pelajaran WHERE kode_mapel='$mapelx[nama]'"));
	$sqg=mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE id_pengawas='$mapelx[idguru]'"));
	if(date('m')>=7 AND date('m')<=12) {
		$ajaran = date('Y')."/".(date('Y')+1);
	}elseif(date('m')>=1 AND date('m')<=6) {
		$ajaran = (date('Y')-1)."/".date('Y');
	}
	//$sqg=mysql_fetch_array(mysql_query("SELECT mapel.*, pengawas.nama,pengawas.nip FROM mapel INNER JOIN pengawas ON mapel.idguru=pengawas.id_pengawas WHERE id_mapel='$id_mapel'"));
	$namakelas= mysql_fetch_array(mysql_query("SELECT * FROM kelas WHERE id_kelas='$id_kelas'"));
	$querysetting=mysql_query("SELECT * FROM setting WHERE id_setting='1'");
	$setting=mysql_fetch_array($querysetting);

	//======DAFTAR HADIR DICETAK BERDASARKAN PESERTA YANG MENGIKUTI MAPEL YANG ADA DI BANK SOAL========
	//$sqg=mysql_fetch_assoc(mysql_query("SELECT mapel.*, pengawas.nama,pengawas.nip FROM mapel INNER JOIN pengawas ON mapel.idguru=pengawas.id_pengawas WHERE id_mapel='$id_mapel'"));
	//$mapel=mysql_fetch_assoc(mysql_query("SELECT mapel.*, mata_pelajaran.nama_mapel FROM mapel INNER JOIN mata_pelajaran ON mapel.nama=mata_pelajaran.kode_mapel WHERE id_mapel='$id_mapel'"));
	if(!$sesi=='' and !$ruang=='' and !$kelas==''){
		if($mapelx['id_kelas']==''){	//semua jurusan
			$ckck=mysql_query("SELECT * FROM siswa WHERE id_kelas='$kelas' and ruang='$ruang' and sesi='$sesi' ");
		}else{		//jurusan tertentu ==> filter berdasarkan jurusan tersebut!!!
			$ckck=mysql_query("SELECT * FROM siswa WHERE sesi='$sesi' AND ruang='$ruang' AND id_kelas='$kelas' and idpk='$mapelx[idpk]'");
		}
	
	}elseif($sesi=='' and !$ruang=='' and !$kelas==''){
		if($mapelx['id_kelas']=='semua' or $mapelx['idpk']=='' ){
			$ckck=mysql_query("SELECT * FROM siswa WHERE  ruang='$ruang' and id_kelas='$kelas'");
		}else{
			$ckck=mysql_query("SELECT * FROM siswa WHERE  ruang='$ruang' and id_kelas='$kelas' and idpk='$mapelx[idpk]'");
		}
	}elseif($sesi=='' and $ruang=='' and !$kelas==''){
		if($mapelx['idpk']=='semua'){
			$ckck=mysql_query("SELECT * FROM siswa WHERE  id_kelas='$kelas'");	
		}else{
			$ckck=mysql_query("SELECT * FROM siswa WHERE  id_kelas='$kelas' and idpk='$mapelx[idpk]'");	
		}
	}elseif(!$sesi=='' and $ruang=='' and $kelas==''){
		if($mapelx['idpk']=='semua'){
			$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi'");	
		}else{
			$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and idpk='$mapelx[idpk]'");	
		}
	}elseif(!$sesi=='' and !$ruang=='' and $kelas==''){
		if($mapelx['idpk']=='semua'){
			$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and ruang='$ruang'");
		}else{
			$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and ruang='$ruang' and idpk='$mapelx[idpk]'");
		}
	}elseif($sesi=='' and !$ruang=='' and $kelas==''){
		if($mapelx['idpk']=='semua'){
			$ckck=mysql_query("SELECT * FROM siswa WHERE   ruang='$ruang'");	
		}else{
			$ckck=mysql_query("SELECT * FROM siswa WHERE   ruang='$ruang' and idpk='$mapelx[idpk]'");
		}
	}else{
		if($mapelx['idpk']=='semua'){
			$ckck=mysql_query("SELECT * FROM siswa");	
		}else{
			$ckck=mysql_query("SELECT * FROM siswa where idpk='$mapelx[idpk]'");	
		}
	}
	$jumlahData = mysql_num_rows($ckck);
	$jumlahn = '32';
	$n = ceil($jumlahData/$jumlahn);
	$nomer = 1;

	$date=date_create($cektanggal['tgl_ujian']);
	
	for($i=1;$i<=$n;$i++){
		$mulai = $i-1;
		$batas = ($mulai*$jumlahn);
		$startawal = $batas;
		$batasakhir = $batas+$jumlahn;
		if ($i==$n){
			echo "
			<div class='page'>
				<table width='100%'>
					<tr>
						<td width='100'><img src='$homeurl/$setting[logo_instansi]' height='75'></td>
						<td>
							<CENTER>
								<strong class='f12'>
									LAPORAN NILAI <BR>
									$setting[nama_ujian]<BR>TAHUN PELAJARAN $ajaran
								</strong>
							</CENTER></td>
							<td width='100'><img src='$homeurl/$setting[logo]' height='75'></td>
					</tr>
				</table>
				
				<table class='detail'>
					<tr>
						<td>SEKOLAH/MADRASAH</td><td>:</td><td><span style='width:450px;'>&nbsp;$setting[sekolah]</span></td>
					</tr>
					<tr>
						<td>KELAS</td><td>:</td><td><span style='width:450px;'>&nbsp;$namakelas[nama]</span></td>
					</tr>
					<tr>
						<td>MATA PELAJARAN</td><td>:</td><td colspan='4'><span style='width:450px;'>&nbsp;$namamapel[nama_mapel]</span></td>
					</tr>
				</table>
				<hr/><br>
				<table class='it-grid it-cetak' width='100%'>
				<tr height=40px>
					<td width='4%' align=center>No</td>
					
					<td width='15%'  align='center'>No Peserta</td>
					<td align='center'>Nama</th>
					<td width='10%' align='center'>NILAI PG</td>
					<td width='10%' align='center'>NILAI ESSAY</td>
					<td width='10%' align='center'>JUMLAH</td>
				</tr>";
					if(!$sesi=='' and !$ruang=='' and !$kelas==''){
						if($mapelx['idpk']=='semua'){	//semua jurusan
							$ckck=mysql_query("SELECT * FROM siswa WHERE sesi='$sesi' and ruang='$ruang' and id_kelas='$kelas' limit $batas,$jumlahn");
						}else{		//jurusan tertentu ==> filter berdasarkan jurusan tersebut!!!
							$ckck=mysql_query("SELECT * FROM siswa WHERE sesi='$sesi' AND ruang='$ruang' AND id_kelas='$kelas' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					
					}elseif($sesi=='' and !$ruang=='' and !$kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  ruang='$ruang' and id_kelas='$kelas' limit $batas,$jumlahn");
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  ruang='$ruang' and id_kelas='$kelas' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					}elseif($sesi=='' and $ruang=='' and !$kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  id_kelas='$kelas' limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  id_kelas='$kelas' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");	
						}
					}elseif(!$sesi=='' and $ruang=='' and $kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");	
						}
					}elseif(!$sesi=='' and !$ruang=='' and $kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and ruang='$ruang' limit $batas,$jumlahn");
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and ruang='$ruang' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					}elseif($sesi=='' and !$ruang=='' and $kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE   ruang='$ruang' limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE   ruang='$ruang' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					}else{
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa where idpk='$mapelx[idpk]' limit $batas,$jumlahn");	
						}
					}
					
					while($f= mysql_fetch_array($ckck)){
						if ($nomer % 2 == 0) {
							echo "
							<tr>
							<td align='center'>$nomer.</td>
							
							<td align='center'>&nbsp;$f[no_peserta]</td>
							<td>$f[nama]</td>
							<td align='center'></td>
							<td align='center'></td>
							<td align='center'>&nbsp;</td>
						</tr>";
						} else {
							echo "
							<tr>
							<td align='center'>$nomer.</td>
							
							<td align='center'>&nbsp;$f[no_peserta]</td>
							<td>$f[nama]</td>
							<td align='center'></td>
							<td align='center'></td>
							<td align='center'>&nbsp;</td>
						</tr>";
						}
						$nomer++;
					}
					echo "
				</table>
				<table class='detail'>
					<tr>
						<td>RATA-RATA ESAY</td><td>:</td><td><span style='width:50px;'>&nbsp;</span></td>
					</tr>
					<tr>
						<td>RATA-RATA KESELURUHAN</td><td>:</td><td><span style='width:50px;'>&nbsp;</span></td>
					</tr>
				</table>
				<p><b>*NOTE :</b></p>
				<p>Isi <u>Jumlah</u> dengan hasil penjumlahan pg + essay</p>
				<p>Isi <u>Rata-rata essay</u> dari nilai essay</p>
				<p>Isi <u>Rata-rata Keseluruhan</u> dari total pg + essay</p>
				
				<br><br>
				<table border=0 width='100%'>
				<tr>
				<td width='5%'>Mengatahui</td>
				<td width='8%'>Mengetahui</td>
				<td width='0.1%'>Mengatahui</td>
				</tr>
				<tr>
				<td>$setting[kota], $tglsekarang</td>
				<td>$setting[kota], $tglsekarang</td>
				<td width='0%'>$setting[kota], $tglsekarang</td>
				</tr>
				<tr>
				<td>Kepala Sekolah,,</td>
				<td>Guru Mapel,</td>
				<td width='0%'>Proktor,</td>
				</tr>
				<tr>
				<td><br><br><br><br><br><strong>$setting[kepsek]</strong></td>
				<td><br><br><br><br><br><strong>$sqg[nama]</strong></td>
				<td width='2%'><br><br><br><br><br><strong>________________________ </strong></td>
				</tr>
				<tr>
				<td>NIP.$setting[nip]</td>
				<td>NIP. $sqg[nip] </td>
				<td width='0%'>NIP. </td>
				</tr>
				</table>
				
				<div class='footer'>
					<table width='100%' height='30'>
						<tr>
							<td width='25px' style='border:1px solid black'></td>
							<td width='5px'>&nbsp;</td>
							<td style='border:1px solid black;font-weight:bold;font-size:14px;text-align:center;'>$setting[nama_ujian] $setting[sekolah] </td>
							<td width='5px'>&nbsp;</td>
							<td width='25px' style='border:1px solid black'></td>
						</tr>
					</table>
				</div>
			</div>";
			break;
		}
		echo "
		<div class='page'>
			<table width='100%'>
					<tr>
						<td width='100'><img src='$homeurl/dist/img/jabar.png' height='75'></td>
						<td>
							<CENTER>
								<strong class='f12'>
									REKAPITULASI NILAI <BR>
									$setting[nama_ujian]<BR>TAHUN PELAJARAN $ajaran
								</strong>
							</CENTER></td>
							<td width='100'><img src='$homeurl/$setting[logo]' height='75'></td>
					</tr>
				</table>
				
				<table class='detail'>
					<tr>
						<td>SEKOLAH/MADRASAH</td><td>:</td><td><span style='width:450px;'>&nbsp;$setting[sekolah]</span></td>
					</tr>
					<tr>
						<td>KELAS</td><td>:</td><td><span style='width:450px;'>&nbsp;$namakelas[nama]</span></td>
					</tr>
					<tr>
						<td>MATA PELAJARAN</td><td>:</td><td colspan='4'><span style='width:450px;'>&nbsp;$namamapel[nama_mapel]</span></td>
					</tr>
				</table>
				<hr/><br>

			<table class='it-grid it-cetak' width='100%'>
				<tr height=40px>
					<td width='4%' align=center>No</td>
					
					<td width='15%'  align='center'>No Peserta</td>
					<td align='center'>Nama</th>
					<td width='10%' align='center'>NILAI PG</td>
					<td width='10%' align='center'>NILAI ESSAY</td>
					<td width='10%' align='center'>JUMLAH</td>
				</tr>";
				
				$s = $i-1;
				if(!$sesi=='' and !$ruang=='' and !$kelas==''){
						if($mapelx['idpk']=='semua'){	//semua jurusan
							$ckck=mysql_query("SELECT * FROM siswa WHERE sesi='$sesi' and ruang='$ruang' and id_kelas='$kelas' limit $batas,$jumlahn");
						}else{		//jurusan tertentu ==> filter berdasarkan jurusan tersebut!!!
							$ckck=mysql_query("SELECT * FROM siswa WHERE sesi='$sesi' AND ruang='$ruang' AND id_kelas='$kelas' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					
					}elseif($sesi=='' and !$ruang=='' and !$kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  ruang='$ruang' and id_kelas='$kelas' limit $batas,$jumlahn");
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  ruang='$ruang' and id_kelas='$kelas' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					}elseif($sesi=='' and $ruang=='' and !$kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  id_kelas='$kelas' limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  id_kelas='$kelas' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");	
						}
					}elseif(!$sesi=='' and $ruang=='' and $kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");	
						}
					}elseif(!$sesi=='' and !$ruang=='' and $kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and ruang='$ruang' limit $batas,$jumlahn");
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE  sesi='$sesi' and ruang='$ruang' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					}elseif($sesi=='' and !$ruang=='' and $kelas==''){
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa WHERE   ruang='$ruang' limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa WHERE   ruang='$ruang' and idpk='$mapelx[idpk]' limit $batas,$jumlahn");
						}
					}else{
						if($mapelx['idpk']=='semua'){
							$ckck=mysql_query("SELECT * FROM siswa limit $batas,$jumlahn");	
						}else{
							$ckck=mysql_query("SELECT * FROM siswa where idpk='$mapelx[idpk]' limit $batas,$jumlahn");	
						}
					}
				while($f= mysql_fetch_array($ckck)){
					if ($nomer % 2 == 0) {
						echo "
								<tr>
							<td align='center'>$nomer.</td>
							
							<td align='center'>&nbsp;$f[no_peserta]</td>
							<td>$f[nama]</td>
							<td align='center'></td>
							<td align='center'></td>
							<td align='center'>&nbsp;</td>
						</tr>";
					} else {
						echo "
								<tr>
							<td align='center'>$nomer.</td>
							
							<td align='center'>&nbsp;$f[no_peserta]</td>
							<td>$f[nama]</td>
							<td align='center'></td>
							<td align='center'></td>
							<td align='center'>&nbsp;</td>
						</tr>";
					}
					$nomer++;
				}
				echo "
			</table>

			<div class='footer'>
				<table width='100%' height='30'>
					<tr>
						<td width='25px' style='border:1px solid black'></td>
						<td width='5px'>&nbsp;</td>
						<td style='border:1px solid black;font-weight:bold;font-size:14px;text-align:center;'>$setting[nama_ujian]$setting[sekolah]</td>
						<td width='5px'>&nbsp;</td>
						<td width='25px' style='border:1px solid black'></td>
					</tr>
				</table>
			</div>
		</div>";
	}
?>