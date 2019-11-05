<?php
    if(!isset($_COOKIE['cbtusbn'])){
        header("Location: login.php");
    }
    if (isset($_POST['submit2'])){
        $idmapely = $_POST['mapelbanksoaly'];
        $hasil = mysql_query("SELECT * FROM nilai WHERE id_mapel='$idmapely' ORDER BY id_siswa");
        // $jumlah = count(mysql_fetch_array($hasil));
        $no=1;
        while($hasiljawabanx=mysql_fetch_array($hasil)){
            mysql_query("DELETE FROM hasil_jawaban WHERE id_jawaban IN (SELECT * FROM (SELECT id_jawaban FROM hasil_jawaban WHERE id_mapel='$idmapely' AND id_siswa='$hasiljawabanx[id_siswa]' GROUP BY id_soal HAVING (COUNT(*) > 1)) AS A)");
            $no++;
        }
    }
    if (isset($_POST['submit1'])){
        $idmapelx = $_POST['mapelbanksoalx'];
        $jumlahsoal = $_POST['jml_soal'];
        $queryx = mysql_query("SELECT id_soal, id_mapel, jawaban, jenis FROM soal WHERE jenis='1' AND id_mapel='$idmapelx' LIMIT $jumlahsoal");
        $no=1;
        while($jawabansoal=mysql_fetch_array($queryx)){
            mysql_query("UPDATE hasil_jawaban SET jawaban = '$jawabansoal[jawaban]' WHERE id_mapel='$idmapelx' AND jenis='1' AND id_soal='$jawabansoal[id_soal]'");
            $no++;
        }
    }
    if (isset($_POST['submit'])) {
        $idmapel = $_POST['mapelbanksoal'];
        $querysiswa = mysql_query("SELECT * FROM nilai WHERE id_mapel='$idmapel' ORDER BY id_siswa");
        $no=1;
        while($siswa=mysql_fetch_array($querysiswa)){
            $where = array(
                'id_mapel' => $idmapel,
                'id_siswa' => $siswa['id_siswa']
            );
            $benar = $salah = 0;
            $mapel = fetch('mapel',array('id_mapel'=>$idmapel));
            $ceksoal = select('soal',array('id_mapel'=>$idmapel,'jenis'=>'1'));
            foreach($ceksoal as $getsoal){
                $jika = array(
                    'id_siswa' => $siswa['id_siswa'],
                    'id_mapel' => $idmapel,
                    'id_soal' => $getsoal['id_soal'],
                    'jenis' => '1'
                );
                $getjwb = fetch('hasil_jawaban',$jika);
                if($getjwb) {
                    ($getjwb['jawaban']==$getsoal['jawaban']) ? $benar++ : $salah++;
                } 
            }
            $jumsalah = $mapel['tampil_pg']-$benar;
            $bagi = $mapel['tampil_pg']/100;
            $bobot= $mapel['bobot_pg']/100;
            $skorx = ($benar/$bagi)*$bobot;
            $skor=number_format($skorx,2,'.','');
            $data = array(
                'jml_benar' => $benar,
                'jml_salah' => $jumsalah,
                'skor' => $skor
            );
            update('nilai',$data,$where);
            mysql_query("UPDATE nilai SET total = FORMAT((skor + nilai_esai),2) WHERE id_mapel='$idmapel' AND id_siswa='$siswa[id_siswa]'");
            $no++;
        }    
    }
    echo "
    <div class='panel panel-primary'>
        <div class='panel-heading'><i class='glyphicon glyphicon-refresh'></i>
            Generate Jawaban dan Proses Nilai
        </div>
        <div class='panel-body'>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='panel panel-info'>
                        <div class='panel-heading'>
                            Generate Jawaban
                        </div>
                        <div class='panel-body'>
                            <form action='' method='post'>
                                <label>
                                    Kode Mapel Bank Soal
                                </label>
                                <select name='mapelbanksoalx' class='form-control' required='true'>";
                                    $querymapel = mysql_query("SELECT mapel.id_mapel, mapel.nama, mata_pelajaran.nama_mapel FROM mapel INNER JOIN mata_pelajaran ON mapel.nama=mata_pelajaran.kode_mapel");
                                    while($mapelbanksoal = mysql_fetch_array($querymapel)) {
                                        echo "<option value='$mapelbanksoal[id_mapel]'>$mapelbanksoal[id_mapel] - $mapelbanksoal[nama] - $mapelbanksoal[nama_mapel]</option>";
                                    }
                                    echo"
                                </select>
                                <br>
                                <label>
                                    Jumlah jawaban benar yang Diinginkan
                                </label>
                                <input type='number' name='jml_soal' class='form-control' required='true' value='20'/>
                                <br>
                                <button type='submit' name='submit1' class='btn btn-info pull-right'><i class='glyphicon glyphicon-refresh'></i> GENERATE JAWABAN</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='panel panel-danger'>
                        <div class='panel-heading'>
                            Proses Nilai
                        </div>
                        <div class='panel-body'>
                            <form action='' method='post'>
                                <label>
                                    Kode Mapel Bank Soal
                                </label>
                                <select name='mapelbanksoal' class='form-control' required='true'>";
                                    $querymapel = mysql_query("SELECT mapel.id_mapel, mapel.nama, mata_pelajaran.nama_mapel FROM mapel INNER JOIN mata_pelajaran ON mapel.nama=mata_pelajaran.kode_mapel");
                                    while($mapelbanksoal = mysql_fetch_array($querymapel)) {
                                        echo "<option value='$mapelbanksoal[id_mapel]'>$mapelbanksoal[id_mapel] - $mapelbanksoal[nama] - $mapelbanksoal[nama_mapel]</option>";
                                    }
                                    echo"
                                </select>
                                <br>
                                <button type='submit' name='submit' class='btn btn-danger pull-right'><i class='glyphicon glyphicon-refresh'></i> PROSES DATA</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='panel panel-warning'>
                        <div class='panel-heading'>
                            Cleaning Data
                        </div>
                        <div class='panel-body'>
                            <form action='' method='post'>
                                <label>
                                    Kode Mapel Bank Soal
                                </label>
                                <select name='mapelbanksoaly' class='form-control' required='true'>";
                                    $querymapel = mysql_query("SELECT mapel.id_mapel, mapel.nama, mata_pelajaran.nama_mapel FROM mapel INNER JOIN mata_pelajaran ON mapel.nama=mata_pelajaran.kode_mapel");
                                    while($mapelbanksoal = mysql_fetch_array($querymapel)) {
                                        echo "<option value='$mapelbanksoal[id_mapel]'>$mapelbanksoal[id_mapel] - $mapelbanksoal[nama] - $mapelbanksoal[nama_mapel]</option>";
                                    }
                                    echo"
                                </select>
                                <br>
                                <button type='submit' name='submit2' class='btn btn-warning pull-right'><i class='glyphicon glyphicon-refresh'></i> CLEANING DATA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <div class='panel-footer'>
            <a href='?' class='btn btn-sm btn-primary' title='ke dashboard'><i class='glyphicon glyphicon-chevron-right'></i></a>
        </div>
    </div>"
?>