<?php
$path="/home/admincbt/ujian2/admin/ujian2/$_POST[project]";
chdir($path);
function execPrint($command) {
	$result=array();
	exec($command, $result);
	foreach ($result as $line) {
		print($line . "\n");
	}
};

//exec("git pull https://github.com/tetsunero/Spin.git master");
//echo "<h3>asdfg</h3>";
print("<h3> Pull sukses" . execPrint("git pull https://github.com/tetsunero/ujian2.git master") . "</h3>");
?>