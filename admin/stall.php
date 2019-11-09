<?php 
$files = glob("/home/admincbt/ujian2/admin/ujian2/admin/*.*");
foreach($files as $file){
$file_to_go = str_replace("/home/admincbt/ujian2/admin/ujian2/admin/","/home/admincbt/ujian2/admin/",$file);
copy($file, $file_to_go);
}
echo "
			<script src='../plugins/jQuery/jquery-3.1.1.min.js'></script>
			<script src='../dist/bootstrap/js/bootstrap.min.js'></script>
			<script >$(document).ready(function () {
				$('#modal-default').modal('show');
			});</script>
					<div class='modal fade' id='modal-default' >
					  <div class='modal-dialog'>
						<div class='modal-content'>
						  <div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
							  <span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'>Selamat</h4>
						  </div>
						  <div class='modal-body'>
							<p>Aplikasi berhasil diperbarui</p>
						  </div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Oke</button>
							
						  </div>
						</div>
						<!-- /.modal-content -->
					  </div>
					  <!-- /.modal-dialog -->
					</div>
											
";  							   
		
?>