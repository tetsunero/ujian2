<?php

require("../config/config.default.php");
require("../config/config.function.php");
	$kode=$_POST['kode'];
		$exec = mysql_query("DELETE FROM soal WHERE id_mapel in (".$kode.")");
	if($exec){
		echo 1;
	//jump('?pg=banksoal&ac=lihat&id='.$kode);	
	}
	else{
		echo 0;
	}


	?>
	