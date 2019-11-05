<?php

require("../config/config.default.php");
require("../config/config.function.php");
	$kode=$_POST['kode'];
	
		$exec = mysql_query("DELETE FROM berita  WHERE id_berita in (".$kode.")");
	
	if($exec){
		echo 1;
	}
	else{
		echo 0;
	}


	?>
	