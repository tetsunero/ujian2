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
	$file = "NILAI_".$mapel['tgl_ujian']."_".$mapel['nama']."_".$kelas['nama'];
	$file = str_replace(" ","-",$file);
	$file = str_replace(":","",$file);
	header("Content-type: application/octet-stream");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Content-Disposition: attachment; filename=".$file.".xls");
	echo "
		<table border='0'>
		<tr>
		<td colspan='2'>
		Mata Pelajaran
		</td>
		<td style='vertical-align:middle; text-align:center;'>:</td>
		<td colspan='2'>$mapel[nama]</td>
		</tr>
		<tr>
		<td colspan='2'>
		Tanggal Ujian
		</td>
		<td style='vertical-align:middle; text-align:center;'>:</td>
		<td colspan='2'>
		".buat_tanggal('D, d M Y')."
		</td>
		</tr>
		<tr>
		<td colspan='2'>Jumlah Soal</td>
		<td style='vertical-align:middle; text-align:center;'>:</td>
		<td style='vertical-align:middle; text-align:left;' colspan='2'>$mapel[jml_soal]</td>
		</tr>
		<tr>
		<td colspan='2'>
		Kelas
		</td>
		<td style='vertical-align:middle; text-align:center;'>
		:</td>
		<td colspan='2'>$id_kelas</td>
		</tr>
		</table><br/>
		
		<table border='1'>
		<tr>
		 <td style='background:orange' colspan='4' align='center'>Kunci Jawaban</td>";
				for($num=1;$num<=$mapel['jml_soal'];$num++) {
					$soal = fetch('soal',array('id_mapel'=>$id_mapel,'nomor'=>$num));
					echo "<td style='background:yellow;' align='center'>$soal[jawaban] </td>";
				}
				echo "
		 </tr>
		 </table><br/>
		<table border='1'>
		
			<tr>
				<td style='vertical-align:middle; text-align:center; background:silver;' rowspan='2'>NO.</td>
				<td style='vertical-align:middle; text-align:center; background:silver;' rowspan='2' width='500px'>NIS</td>
				<td style='vertical-align:middle; text-align:center; background:silver;' rowspan='2'>NO.PESERTA</td>
				<td style='vertical-align:middle; text-align:center; background:silver;'rowspan='2'>NAMA PESERTA</td>
				<td style='background:silver' colspan='".($mapel['jml_soal']+4)."' align='center'><b>RESPON JAWABAN PESERTA TES</b></td>
				<td style='vertical-align:middle; text-align:center; background:silver;' rowspan='2'>NILAI/SKOR</td>
			</tr>
			<tr>";
				for($num=1;$num<=$mapel['jml_soal'];$num++) {
					$soal = fetch('soal',array('id_mapel'=>$id_mapel,'nomor'=>$num));
					echo "<td style='background:orange;'><b>NO.$num</b></td>";
				}
				echo "
				<td style='vertical-align:middle; text-align:center; background:#DAA520;'>BENAR</td>
				<td style='vertical-align:middle; text-align:center; background:#DAA520;'>SALAH</td>
				<td style='vertical-align:middle; text-align:center; background:#DAA520;'>NILAI PG</td>
				<td style='vertical-align:middle; text-align:center; background:#DAA520;'>NILAI ESSAI</td>
			</tr>";
			if($id_kelas=='semua'){
						$siswaQ = mysql_query("SELECT * FROM siswa ORDER BY id_kelas ASC");
					}else{
						$siswaQ = mysql_query("SELECT * FROM siswa WHERE id_kelas='$id_kelas' ORDER BY nama ASC");	
					}
					while($siswa = mysql_fetch_array($siswaQ)) {
				$no++;
				$benar = $salah = 0;
				$skor = $lama = '-';
				$nilai = fetch('nilai',array('id_mapel'=>$id_mapel,'id_siswa'=>$siswa['id_siswa']));
				if($nilai['ujian_mulai']<>'' AND $nilai['ujian_selesai']<>'') {
					$selisih = strtotime($nilai['ujian_selesai'])-strtotime($nilai['ujian_mulai']);
					$jam = round((($selisih%604800)%86400)/3600);
					$mnt = round((($selisih%604800)%3600)/60);
					$dtk = round((($selisih%604800)%60));
					$lama = '';
					($jam<>0) ? $lama .= "$jam jam ":null;
					($mnt<>0) ? $lama .= "$mnt menit ":null;
					($dtk<>0) ? $lama .= "$dtk detik ":null;
				}
				echo "
					<tr>
						<td style='vertical-align:middle; text-align:center;'>$no</td>
						<td>$siswa[nis]</td>
						<td>$siswa[no_peserta]</td>
						<td>$siswa[nama]</td>
						
						";
						for($num=1;$num<=$mapel['jml_soal'];$num++) {
							$soal = fetch('soal',array('id_mapel'=>$id_mapel,'nomor'=>$num));
							$jawaban = fetch('hasil_jawaban',array('id_siswa'=>$siswa['id_siswa'],'id_mapel'=>$id_mapel,'id_soal'=>$soal['id_soal']));
							if($jawaban) {
								if($jawaban['jawaban']==$soal['jawaban']) {
									echo "<td style='background:#00FF00;' align='center'>$jawaban[jawaban]</td>";
								} else {
									echo "<td style='background:#FF0000;' align='center'>$jawaban[jawaban]</td>";
								}
							} else {
								echo "<td>-</td>";
							}
						}
						echo "
						<td style='vertical-align:middle; text-align:center;'>$nilai[jml_benar]</td>
						<td style='vertical-align:middle; text-align:center;'>$nilai[jml_salah]</td>
						<td style='vertical-align:middle; text-align:center;' class='str'>$nilai[skor]</td>
						<td style='vertical-align:middle; text-align:center;' class='str'>$nilai[nilai_esai]</td>
						<td style='vertical-align:middle; text-align:center;' class='str'>$nilai[total]</td>
					</tr>
				";
			}
			echo "
		</table>
	";
?>