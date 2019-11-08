<?php 
$pesan=''; 
if(isset($_POST["btn_zip"]))   {  
      $output = '';  
      if($_FILES['zip_file']['name'] != '')  
      {  
           $file_name = $_FILES['zip_file']['name'];  
           $array = explode(".", $file_name);  
           $name = $array[0];  
           $ext = $array[1];  
           if($ext == 'zip')  
           {  
                $path = '../files/';  
                $location = $path . $file_name;  
                if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $location))  
                {  
                     $zip = new ZipArchive;  
                     if($zip->open($location))  
                     {  
                          $zip->extractTo($path);  
                          $zip->close();  
                     }  
                     $files = scandir($path );  
                     //$name is extract folder from zip file  
                     foreach($files as $file)  
                     {  
                          $file_ext = end(explode(".", $file));  
                          $allowed_ext = array('jpg', 'png','jpeg','gif','mp3','wav');  
                          if(in_array($file_ext, $allowed_ext))  
                          {  
                               
                               $output .= '<div class="col-md-3"><div style="padding:16px; border:1px solid #CCC;"><img class="img img-responsive" style="" src="../files/'.$file.'"   /></div></div>';  
                               
                          }       
                     }  
                     unlink($location);  
                     $pesan ="<div class='alert alert-success alert-dismissible'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<h4><i class='icon fa fa-check'></i> Info</h4> Upload File zip berhasil
							</div>"; 
					  
					
                }  
           }else{
			   $pesan="
					<div class='alert alert-warning alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<h4><i class='icon fa fa-info'></i> Gagal Upload</h4>
						Mohon Upload file zip
					</div>";
		   }  
      }  
 } 
 ?>  
 
		<!-- <div class='col-md-6'> -->
		<form method="post" enctype="multipart/form-data" id="zipgam">
			<div class='box box-solid'>
				<div class='box-header with-border bg-green'>
				<h3 class='box-title'>Import File Gambar/Audio Template Excell</h3>
					<div class='box-tools pull-right btn-group'>
						
						<a href='?pg=banksoal' class='btn btn-sm btn-danger' title='Batal'><i class='fa fa-times'></i></a>
					</div>
				</div>
				<div class='box-body'>
				<?php echo $pesan; ?>
                          
				<div class='form-group'>                    
                     <input class='form-control' type="file" accept='.zip' name="zip_file" required='true'/>  
				</div>								
                     <!--<button type="submit" name="btn_zip" class="btn btn-info" >Upload File</button> --> 
				
         </form>  
                  
				 <p>
                    Silahkan upload file pendukung soal seperti gambar dan audio ke dalam arsip bertipe zip setelah itu upload kesini dan komputer akan mengekstraknya ke dalam folder files <br/>
                 </p>
				<button type='submit' name='btn_zip' class='btn  btn-primary' title='Import File'><i class='fa fa-check'></i> Import File</button>

					<div id='progressbox'></div>
					<div id='hasilimport'></div>
                <?php  
                if(isset($output))  
                {  
                     echo $output;  
                }  
                ?>  
				</div>
				<div class='alert alert-danger '>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<h4><i class='icon fa fa-info'></i> Info</h4>
						Untuk file gambar atau audio silahkan di compress jadi satu zip 
				</div> 
			</div>

	<!-- </div> -->
	
          
