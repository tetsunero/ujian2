<?php 
$files = glob("/home/admincbt/ujian2/admin/ujian2/admin/*.*");
foreach($files as $file){
$file_to_go = str_replace("/home/admincbt/ujian2/admin/ujian2/admin/","/home/admincbt/ujian2/admin/",$file);
copy($file, $file_to_go);
}	   		
?>
