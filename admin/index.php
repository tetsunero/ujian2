	<?php
	require("../config/config.default.php");
	require("../config/config.function.php");
	require("../config/functions.crud.php");
	require("../config/excel_reader.php");
	(isset($_SESSION['id_pengawas'])) ? $id_pengawas = $_SESSION['id_pengawas'] : $id_pengawas = 0;
	($id_pengawas==0) ? header('location:login.php'):null;
	$pengawas = mysql_fetch_array(mysql_query("SELECT * FROM pengawas  WHERE id_pengawas='$id_pengawas'"));
	
	(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
	(isset($_GET['ac'])) ? $ac = $_GET['ac'] : $ac = '';
	($pg=='banksoal' && $ac=='input' ) ? $sidebar = 'sidebar-collapse' : $sidebar = '';
	($pg=='nilai' && $ac=='lihat' ) ? $sidebar = 'sidebar-collapse' : $sidebar = '';
						$nilai = mysql_num_rows(mysql_query("SELECT * FROM nilai"));
							$soal = mysql_num_rows(mysql_query("SELECT * FROM mapel"));
							$siswa = mysql_num_rows(mysql_query("SELECT * FROM siswa"));
							$ruang = mysql_num_rows(mysql_query("SELECT * FROM ruang"));
							$kelas = mysql_num_rows(mysql_query("SELECT * FROM kelas"));
							$mapel = mysql_num_rows(mysql_query("SELECT * FROM mata_pelajaran"));
							$online = mysql_num_rows(mysql_query("SELECT * FROM login"));
							$token=mysql_fetch_array(mysql_query("select token from token"));
	echo "
		<!DOCTYPE html>
		<html>
			<head>
  				<meta charset='utf-8'>
 				<meta http-equiv='X-UA-Compatible' content='IE=edge'>
  				<title>Administrator | $setting[aplikasi]</title>
  				<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  				<link rel='shortcut icon' href='$homeurl/favicon.ico'/>
				<link rel='stylesheet' href='$homeurl/dist/bootstrap/css/bootstrap.min.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/fileinput/css/fileinput.min.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/font-awesome/css/font-awesome.css'/>
					<link rel='stylesheet' href='$homeurl/dist/fonts/jawa/jawa_font.css' />
				<link rel='stylesheet' href='$homeurl/plugins/select2/select2.min.css'/>
				<link rel='stylesheet' href='$homeurl/dist/css/AdminLTE.min.css'/>
				<link rel='stylesheet' href='$homeurl/dist/css/skins/_all-skins.min.css'/>
				<link rel='stylesheet' href='$homeurl/dist/css/skins/skin-blue.min.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/jQueryUI/jquery-ui.css'>
  				<link rel='stylesheet' href='$homeurl/plugins/iCheck/square/green.css'/>
  				<link rel='stylesheet' href='$homeurl/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'>
				<link rel='stylesheet' href='$homeurl/plugins/datatables/dataTables.bootstrap.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/datatables/extensions/Select/css/select.bootstrap.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/animate/animate.min.css'>
				<link rel='stylesheet' href='$homeurl/plugins/datetimepicker/jquery.datetimepicker.css'/>
				<link rel='stylesheet' href='$homeurl/plugins/notify/css/notify-flat.css'/>
  				<link rel='stylesheet' href='$homeurl/plugins/sweetalert2/dist/sweetalert2.min.css'>
				<script src='$homeurl/plugins/tinymce/tinymce.min.js'></script>
<!--<script>tinyMCE.init({
  selector: 'textarea',
  plugins: 'code',
  toolbar: 'undo redo | fontsizeselect fontselect | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
  content_css : '/dist/css/fonts/jawa/jawa_font.css',
  font_formats: 'Arial Black=arial black,avant garde;Huruf Jawa=huruf_jawa,cursive;Times New Roman=times new roman,times;',
  });
</script>-->				
				
<style>
@font-face {
			font-family: 'huruf_jawa';
			src: url('../dist/font/jawa/huruf_jawa.ttf');
		}
.sidebar-menu>li>a {
    padding: 5px 5px 5px 10px;
    display: block;
}
  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../dist/img/ball.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .5;
}

.info-box-number {
    display: block;
    font-weight: bold;
    font-size: 30px;
	color:red;
}

.scrollTop {position: fixed;right: 1%;bottom: 10px;visibility: hidden;transition: all 0.4s ease-in-out 0s;}
.kiri {position: fixed;right: 4%;bottom: 10px;visibility: hidden;transition: all 0.4s ease-in-out 0s;}

.scrollTop a {
  font-size: 18px;
  color: #fff;
}
    td.upper_line { border-top:solid 1px black; }
    table.fraction { text-align: center; vertical-align: middle;
    margin-top:0.5em; margin-bottom:0.5em; line-height: 2em; float:left;}
</style>

<style type='text/css' media='print'>
    .page
    {
     -webkit-transform: rotate(-90deg); 
     -moz-transform:rotate(-90deg);
     filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }	
</style>
			</head>
			<body class='hold-transition skin-green sidebar-mini fixed $sidebar'>
			<div id='pesan'></div>
			<div class='loader'></div>
				<div class='wrapper'>
					<header class=' main-header'>
						<a href='?' class='logo'>
							<span class='animated bounce logo-mini'><image src='$homeurl/$setting[logo_header]' height='30px'></span>
							<span class='animated bounce logo-lg'><image src='$homeurl/$setting[logo_header]' height='40px'></span>
						</a>
						<nav class='navbar navbar-static-top' role='navigation'>
							<a href='#' class='sidebar-toggle' data-toggle='offcanvas' role='button'>
								<span class='sr-only'>Toggle navigation</span>
							</a>
							<div class='navbar-custom-menu'>
								<ul class='nav navbar-nav'>
									<li class='dropdown user user-menu'>
										<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
											<img src='$homeurl/dist/img/avatar-6.png' class='user-image' alt='+'>
											<span class='hidden-xs'>$pengawas[nama]  &nbsp; <i class='fa fa-caret-down'></i></span>
										</a>
										<ul class='dropdown-menu'>
											<li class='user-header'>";
												if($pengawas['level']=='admin'){
												echo "<img src='$homeurl/dist/img/avatar-6.png' class='img-circle' alt='User Image'>";
												}elseif($pengawas['level']=='guru'){
													if($pengawas['foto']<>''){
														echo "<img src='$homeurl/foto/fotoguru/$pengawas[foto]' class='img-circle' alt='User Image'>";
													}else{
														echo "<img src='$homeurl/dist/img/avatar-6.png' class='img-circle' alt='User Image'>";
													}
												
												}
												echo "<p>
													$pengawas[nama]
													<small>NIP. $pengawas[nip]</small>
												</p>
											</li>
											<li class='user-footer'>
												<div class='pull-left'>";
												if($pengawas['level']=='admin'){
												echo "
													<a href='?pg=pengaturan' class='btn btn-sm btn-default btn-flat'><i class='fa fa-gear'></i> Pengaturan</a>
													";
												}elseif($pengawas['level']=='guru'){
												echo "
													<a href='?pg=editguru' class='btn btn-sm btn-default btn-flat'><i class='fa fa-gear'></i> Edit Profil</a>
													";
												}
												echo "
												</div>
												<div class='pull-right'>
													<a href='logout.php' class='btn btn-sm btn-danger'><i class='fa fa-sign-out'></i> Keluar</a>
												</div>
											</li>
										</ul>
									</li>
									<!-- Control Sidebar Toggle Button -->
          <li>
            <a href='#' data-toggle='control-sidebar' data-slide='true'><i class='fa fa-gears'></i></a>
          </li>
								</ul>
							</div>
						</nav>
					</header>
					
					<!-- Control Sidebar -->
  <aside class='control-sidebar control-sidebar-dark'>
    <!-- Create the tabs -->
    <ul class='nav nav-tabs nav-justified control-sidebar-tabs'>
      <li><a href='#control-sidebar-home-tab' data-toggle='tab'><i class='fa fa-home'></i></a></li>
      <li><a href='#control-sidebar-settings-tab' data-toggle='tab'><i class='fa fa-gears'></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class='tab-content'>
      <!-- Home tab content -->
      <div class='tab-pane' id='control-sidebar-home-tab'>
        <h3 class='control-sidebar-heading'>Recent Activity</h3>
        <ul class='control-sidebar-menu'>
          <li>
            <a href='javascript:void(0)'>
              <i class='menu-icon fa fa-birthday-cake bg-red'></i>

              <div class='menu-info'>
                <h4 class='control-sidebar-subheading'>Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href='javascript:void(0)'>
              <i class='menu-icon fa fa-user bg-yellow'></i>

              <div class='menu-info'>
                <h4 class='control-sidebar-subheading'>Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href='javascript:void(0)'>
              <i class='menu-icon fa fa-envelope-o bg-light-blue'></i>

              <div class='menu-info'>
                <h4 class='control-sidebar-subheading'>Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href='javascript:void(0)'>
              <i class='menu-icon fa fa-file-code-o bg-green'></i>

              <div class='menu-info'>
                <h4 class='control-sidebar-subheading'>Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class='control-sidebar-heading'>Tasks Progress</h3>
        <ul class='control-sidebar-menu'>
          <li>
            <a href='javascript:void(0)'>
              <h4 class='control-sidebar-subheading'>
                Custom Template Design
                <span class='label label-danger pull-right'>70%</span>
              </h4>

              <div class='progress progress-xxs'>
                <div class='progress-bar progress-bar-danger' style='width: 70%'></div>
              </div>
            </a>
          </li>
          <li>
            <a href='javascript:void(0)'>
              <h4 class='control-sidebar-subheading'>
                Update Resume
                <span class='label label-success pull-right'>95%</span>
              </h4>

              <div class='progress progress-xxs'>
                <div class='progress-bar progress-bar-success' style='width: 95%'></div>
              </div>
            </a>
          </li>
          <li>
            <a href='javascript:void(0)'>
              <h4 class='control-sidebar-subheading'>
                Laravel Integration
                <span class='label label-warning pull-right'>50%</span>
              </h4>

              <div class='progress progress-xxs'>
                <div class='progress-bar progress-bar-warning' style='width: 50%'></div>
              </div>
            </a>
          </li>
          <li>
            <a href='javascript:void(0)'>
              <h4 class='control-sidebar-subheading'>
                Back End Framework
                <span class='label label-primary pull-right'>68%</span>
              </h4>

              <div class='progress progress-xxs'>
                <div class='progress-bar progress-bar-primary' style='width: 68%'></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class='tab-pane' id='control-sidebar-stats-tab'>Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class='tab-pane' id='control-sidebar-settings-tab'>
        <form method='post'>
          <h3 class='control-sidebar-heading'>General Settings</h3>

          <div class='form-group'>
            <label class='control-sidebar-subheading'>
              Report panel usage
              <input type='checkbox' class='pull-right' checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class='form-group'>
            <label class='control-sidebar-subheading'>
              Allow mail redirect
              <input type='checkbox' class='pull-right' checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class='form-group'>
            <label class='control-sidebar-subheading'>
              Expose author name in posts
              <input type='checkbox' class='pull-right' checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class='control-sidebar-heading'>Chat Settings</h3>

          <div class='form-group'>
            <label class='control-sidebar-subheading'>
              Show me as online
              <input type='checkbox' class='pull-right' checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class='form-group'>
            <label class='control-sidebar-subheading'>
              Turn off notifications
              <input type='checkbox' class='pull-right'>
            </label>
          </div>
          <!-- /.form-group -->

          <div class='form-group'>
            <label class='control-sidebar-subheading'>
              Delete chat history
              <a href='javascript:void(0)' class='text-red pull-right'><i class='fa fa-trash-o'></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
					
					<aside class='main-sidebar'>
						<section class='sidebar'>
							<div class='user-panel'>
								<div class='pull-left image' >";
								if($pengawas['level']=='admin') {
								echo"	<img src='$homeurl/dist/img/avatar-6.png' class='img-circle'  style='border:2px solid yellow; max-width:60px' alt='+'>";
								}elseif($pengawas['level']=='guru') {
									if($pengawas['foto']<>''){
										echo"	<img src='$homeurl/foto/fotoguru/$pengawas[foto]' class='img-circle'  style='border:2px solid yellow; max-width:60px' alt='+'>";
									}else{
										echo"	<img src='$homeurl/dist/img/avatar-6.png' class='img-circle'  style='border:2px solid yellow; max-width:60px' alt='+'>";	
									}
								}echo"
								</div>
								<div class='pull-left info' style='left:65px'>
									<p>$pengawas[nama]</p>
									<a href='#'><i class='fa fa-circle text-green'></i> $pengawas[level]</a>
								</div>
							</div>
							<ul class=' sidebar-menu tree data-widget='tree' >
								 <!-- <li class='header'>MAIN MENU </li> -->
								<li><a href='?'><img src='../dist/img/svg/home.svg' width='30'> <span>Dashboard</span></a></li>
								";
								if($pengawas['level']=='admin') {
									echo "
									
									<li class=' treeview'>
										<a href='#'>
										<img src='../dist/img/svg/genealogy.svg' width='30'>
										<span>Data Master</span><span class='pull-right-container'> <i class='glyphicon glyphicon-plus pull-right'></i> </span>
            
										</a>
										 <ul class='treeview-menu'>
										<li><a href='?pg=importmaster'><i class='fa fa-upload'></i> <span>Import Data Master</span><span class='pull-right-container'><small class='label pull-right bg-green'>new</small></span></a></li>
										<li><a href='?pg=matapelajaran'><i class='fa  fa-circle-o text-red'></i> <span> Data Mata Pelajaran</span></a></li>";
										if($setting['jenjang']=='SMK'){
										echo"<li><a href='?pg=pk'><i class='fa  fa-circle-o text-red'></i> <span> Data Jurusan</span></a></li>";
										}
										echo "
										<li><a href='?pg=kelas'><i class='fa  fa-circle-o text-red'></i> <span> Data Kelas</span></a></li>
										<li><a href='?pg=ruang'><i class='fa  fa-circle-o text-red'></i> <span> Data Ruangan</span></a></li>
										<li><a href='?pg=level'><i class='fa  fa-circle-o text-red'></i> <span> Data Level</span></a></li>
										<li><a href='?pg=sesi'><i class='fa  fa-circle-o text-red'></i> <span> Data Sesi</span></a></li>
										</ul>
										</li>
										<li class='treeview'><a href='?pg=siswa'><img src='../dist/img/svg/manager.svg' width='30'> <span>Peserta Ujian</span></a></li>
										
										<li><a href='?pg=banksoal'><img src='../dist/img/svg/briefcase.svg' width='30'> <span> Bank Soal</span></a></li>
										<li><a href='?pg=jadwal'><img src='../dist/img/svg/planner.svg' width='30'> <span> Jadwal Ujian</span></a></li>
										<li class='treeview'>
										<a href='#'><img src='../dist/img/svg/survey.svg' width='30'><span> UBK</span><span class='pull-right-container'> <i class='glyphicon glyphicon-plus pull-right'></i> </span></a>
										<ul class='treeview-menu'>
										
                                        
										<li><a href='?pg=status'><i class='fa  fa-circle-o text-red'></i> <span> Status Peserta</span></a></li>
										<li><a href='?pg=reset'><i class='fa  fa-circle-o text-red'></i> <span> Reset Login</span></a></li> 										
										<li><a href='?pg=token'><i class='fa  fa-circle-o text-red'></i> <span> Rilis Token</span></a></li>
										<li><a href='?pg=susulan'><i class='fa  fa-circle-o text-red'></i> <span> Belum Ujian</span></a></li>
										</ul>
										</li>
										<li class='treeview'>
										<a href='#'><img src='../dist/img/svg/print.svg' width='30'><span> Cetak </span><span class='pull-right-container'> <i class='glyphicon glyphicon-plus pull-right'></i> </span></a>
										<ul class='treeview-menu'>
										<li><a href='?pg=absen'><i class='fa  fa-circle-o text-red'></i> <span> Daftar Hadir</span></a></li> 
										<li><a href='?pg=kartu'><i class='fa  fa-circle-o text-red'></i> <span> Cetak Kartu</span></a></li>
										<li><a href='?pg=berita'><i class='fa  fa-circle-o text-red'></i> <span> Berita Acara</span></a></li>
										<li><a href='?pg=laporan'><i class='fa  fa-circle-o text-red'></i> <span>Format Laporan Nilai</span></a></li>
										</ul>
										</li>
								<!--	<li class='treeview'><a href='?pg=editnilai'><img src='../dist/img/svg/bullish.svg' width='30'> Perbaikan Nilai</span></a></li> -->
										<li class='treeview'>
											<a href='#'>
												<img src='../dist/img/svg/statistics.svg' width='30'>
													<span> Nilai </span>
													<span class='pull-right-container'> <i class='glyphicon glyphicon-plus pull-right'></i> </span>
											</a>
											<ul class='treeview-menu'>
												<li><a href='?pg=nilai'><i class='fa  fa-circle-o text-blue'></i><span> Analisis Jawaban</span></a></li>
												<li><a href='?pg=anso'><i class='fa  fa-circle-o text-blue'></i><span> Analisa Soal</span></a></li>
												<li><a href='?pg=rekapnilai'><i class='fa  fa-circle-o text-blue'></i><span> Rekapitulasi Nilai</span></a></li>
											</ul>
										</li>
										
										
								<!--	<li class='treeview'><a href='?pg=importword'><i class='fa  fa-bullhorn'></i> <span> Import Word</span></a></li> -->
										<li class='treeview'><a href='?pg=pengumuman'><img src='../dist/img/svg/advertising.svg' width='30'> <span> Pengumuman</span></a></li>
										<li class='treeview'>
										<a href='#'><img src='../dist/img/svg/conference_call.svg' width='30'> <span>Manajemen User</span><span class='pull-right-container'> <i class='glyphicon glyphicon-plus pull-right'></i> </span></a>
										 <ul class='treeview-menu'>
										<li><a href='?pg=pengawas'><i class='fa  fa-circle-o text-red'></i> <span>Data Administrator</span></a></li>
										<li><a href='?pg=guru'><i class='fa  fa-circle-o text-red'></i> <span>Data Guru</span></a></li>
										<li><a href='?pg=walikelas'><i class='fa  fa-circle-o text-red'></i> <span>Data Wali Kelas</span></a></li>
										</ul>
										</li>
								<!--	<li><a href='?pg=dataserver'><i class='fa  fa-desktop'></i> <span>Server Lokal</span></a></li> -->
										<li class='treeview'><a href='?pg=filemanager'><img src='../dist/img/svg/folder.svg' width='30'> <span> File manager</span></a></li>
										<li class='treeview'><a href='?pg=pengaturan'><img src='../dist/img/svg/services.svg' width='30'> <span>Pengaturan</span></a></li>																						
										<li class='treeview' ><a href='#' id='tentang'><img src='../dist/img/svg/about.svg' width='30'> <span>Tentang</span></a></li>														
											
										
											
									";
								}
								if($pengawas['level']=='guru') {
									echo "
										<li class='treeview'><a href='?pg=siswa'><i class='fa  fa-users'></i> <span>Peserta Ujian</span></a></li>
										 <li ><a href='?pg=editguru'><i class='fa  fa-user'></i> <span>Profil Saya</span></a></li>
                                        <li ><a href='?pg=banksoal'><i class='fa  fa-file-text'></i> <span>Bank Soal</span></a></li>
										<li><a href='?pg=jadwal'><i class='fa  fa-calendar '></i> <span> Jadwal Ujian</span></a></li>
										<li class='treeview'>
										<a href='#'><i class='fa  fa-desktop'></i><span> UBK</span><span class='pull-right-container'> <i class='glyphicon glyphicon-plus pull-right'></i> </span></a>
										<ul class='treeview-menu'>
										<li><a href='?pg=status'><i class='fa  fa-circle-o text-red'></i> <span> Status Peserta</span></a></li>
										<li><a href='?pg=reset'><i class='fa  fa-circle-o text-red'></i> <span> Reset Login</span></a></li> 
										
										</ul>
										</li>
										<li><a href='?pg=nilai'><i class='fa  fa-tags'></i> <span>Hasil Nilai</span></a></li>
										
										
									";
								}
								if($setting['jenjang']=='SMK'){$jenjang='SMK/SMA/MA';}elseif($setting['jenjang']=='SMP'){$jenjang='SMP/MTS';}else{$jenjang='SD/MI';}
								echo "
								<li class='header text-center' id='end-sidebar'>
									<div class='pull-center'>
									<a href='logout.php' class='btn btn-sm btn-success btn-flat'><i class='fa fa-sign-out'></i> Keluar</a>
									</div>
								</li>
								<li class='header text-center' id='end-sidebar'><b>TIM IT $setting[sekolah]<br/>OPMI Kota Batu 2019</b></li>
																				
							</ul><!-- /.sidebar-menu -->
						</section>
					</aside>
					
					<div class='content-wrapper' style='background-image: url(backgroun.jpg);background-size: cover;'>
					<section class='content-header'>
								<h1><span class='hidden-xs'>$setting[aplikasi] </span>
								<small class='label bg-blue'><strong>V 1.0 r5</strong>
								</small>
								</h1><div style='float:right; margin-top:-37px'>								
								<button class='btn  btn-flat pull-right bg-purple' ><i class='fa fa-calendar'></i> ".buat_tanggal('D, d M Y')."</button>
								<button class='btn  btn-flat pull-right btn-danger' ><span id='waktu'>$waktu </span></button>
								
								</div>
								<div class='breadcrumb'>
								
								
								
											</div>
								</section>
						<section class='content'>";
						if($pg=='') {
							
						
							$testongoing = mysql_num_rows(mysql_query("SELECT * FROM nilai WHERE ujian_mulai!='' AND ujian_selesai=''"));
							$testdone = mysql_num_rows(mysql_query("SELECT * FROM nilai WHERE ujian_mulai!='' AND ujian_selesai!=''"));
							
                            if($siswa<>0) {
                                $testongoing_per = (1000/$siswa)*$testongoing;
                                $testongoing_per = number_format($testongoing_per,2,'.','');
                                $testongoing_per = str_replace('.00','',$testongoing_per);
                                $testdone_per = (1000/$siswa)*$testdone;
                                $testdone_per = number_format($testdone_per,2,'.','');
                                $testdone_per = str_replace('.00','',$testdone_per);
                            } else {
                                $testongoing_per = $testdone_per = 0;
                            }
							if($pengawas['level']=='admin') {
							echo "
								
									<div class=' row'>
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=reset'>
												<div class='small-box bg-blue'>
													<div class='inner'>
														<h3>$online</h3>
														<p>ONLINE</p>
													</div>
													<div class='icon'>
														<i class='fa fa-user-circle'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
									
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=status'>
												<div class='small-box bg-maroon'>
													<div class='inner'>
														<h3>$ujian<br/></h3>
														<p>STATUS PESERTA</p>
													</div>
													<div class='icon'>
														<i class='fa fa-ticket'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
									
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=nilai'>
												<div class='small-box bg-yellow'>
													<div class='inner'>
														<h3>$nilai</h3>
														<p>NILAI</p>
													</div>
													<div class='icon'>
														<i class='fa fa-pencil-square-o'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
										
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=banksoal'>
												<div class='small-box bg-green'>
													<div class='inner'>
														<h3>$soal</h3>
														<p>SOAL</p>
													</div>
													<div class='icon'>
														<i class='fa fa-file-text'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
										
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=siswa'>
												<div class='small-box bg-red'>
													<div class='inner'>
														<h3>$siswa</h3>
														<p>SISWA</p>
													</div>
													<div class='icon'>
														<i class='fa fa-users'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
										
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=kelas'>
												<div class='small-box bg-blue'>
													<div class='inner'>
														<h3>$kelas</h3>
														<p>KELAS</p>
													</div>
													<div class='icon'>
														<i class='fa fa-building-o'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
										
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=matapelajaran'>
												<div class='small-box bg-aqua'>
													<div class='inner'>
														<h3>$mapel</h3>
														<p>MATA PELAJARAN</p>
													</div>
													<div class='icon'>
														<i class='fa fa-book'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
										
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=ruang'>
												<div class='small-box bg-teal'>
													<div class='inner'>
														<h3>$ruang</h3>
														<p>RUANGAN</p>
													</div>
													<div class='icon'>
														<i class='fa fa-university'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>																			
										
										<div class='animated swing col-lg-3 col-xs-6'>
										  <!-- small box -->
											<a href='?pg=token'>
												<div class='small-box bg-yellow'>
													<div class='inner'>
														<h3>$token[token]</h3>
														<p>TOKEN</p>
														</div>
													<div class='icon'>
														<i class='fa fa-barcode'></i>
													</div>
													<div class='small-box-footer'></div>
												</div>
											</a>
										</div>
										
									</div>	
									
								<div class='row'>
															
									<div class='animated flipInX col-md-12'>
										<div class='box direct-chat'>																					
											<div class='box-header with-border' >
												<h3 class='box-title'><i class='fa fa-bullhorn'></i> Pengumuman</h3>													
												<div class='box-tools pull-right'>
													<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
													<a href='?pg=$pg&ac=clearpengumuman' class='btn bg-maroon btn-flat' title='Bersihkan Pengumuman'><i class='fa fa-trash-o'></i> Bersihkan</a>
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>											
											<div id='pengumuman'>
													<p class='text-center'>
														<br/><i class='fa fa-spin fa-circle-o-notch'></i> Loading....
													</p>
													</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>			
									
									<div class='animated flipInX col-md-8'>
										<div class='box box-info direct-chat'>
											<div class='box-header with-border'>
												<h3 class='box-title'><i class='fa fa-history'></i> Penggunaan System</h3>												
											</div><!-- /.box-header -->
											<div class='box-body'>												
													<p class='text-center'>
														<iframe style='width: 100%;height: 470px;'src='$homeurl/sys.php'></iframe>
													</p>												
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									
									<div class='animated flipInX col-md-4'>
											<div class='box box-success direct-chat direct-chat-warning'>
												<div class='box-header with-border bg-green'>
													<h3 class='box-title'><i class='fa fa-history'></i> Log Aktifitas</h3>
													<div class='box-tools pull-right'>
														<a href='?pg=$pg&ac=clearlog' class='btn bg-maroon btn-flat' title='Bersihkan Pengumuman'><i class='fa fa-trash-o'></i> Bersihkan</a>
													</div>
												</div><!-- /.box-header -->
												<div class='box-body'>
													<div id='log-list'>
														<p class='text-center'>
															<br/><i class='fa fa-spin fa-circle-o-notch'></i> Loading....
														</p>
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>									
									
								</div>
							";
							if($ac=='clearlog') {
								mysql_query("TRUNCATE log");
								jump('?');
							}if($ac=='clearpengumuman') {
								mysql_query("TRUNCATE pengumuman");
								jump('?');
							}if($ac=='clearsiswa') {
								mysql_query("TRUNCATE siswa");
								jump('?');
							}
						}
						if($pengawas['level']=='guru') {
							echo "
								
								<div class='row'>	
								<div class='col-md-8'>
										<div class='box box-success direct-chat direct-chat-warning'>
											<div class='box-header with-border'>
												<h3 class='box-title'><i class='fa fa-bullhorn'></i> Pengumuman
												</h3>
												<div class='box-tools pull-right'>
													
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>
												<div id='pengumuman'>
													<p class='text-center'>
														<br/><i class='fa fa-spin fa-circle-o-notch'></i> Loading....
													</p>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									<div class='col-md-4'>
										<div class='box box-solid '>
											
											<div class='box-body'>
												<strong><i class='fa fa-building-o'></i> $setting[sekolah]</strong><br/>
												$setting[alamat]<br/><br/>
												<strong><i class='fa fa-phone'></i> Telepon</strong><br/>
												$setting[telp]<br/><br/>
												<strong><i class='fa fa-fax'></i> Fax</strong><br/>
												$setting[fax]<br/><br/>
												<strong><i class='fa fa-globe'></i> Website</strong><br/>
												$setting[web]<br/><br/>
												<strong><i class='fa fa-at'></i> E-mail</strong><br/>
												$setting[email]<br/>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
	
								</div>
							";
							
						}
						}
						elseif($pg=='dataserver') {
							include 'serverlokal.php';
						}
						elseif($pg=='filemanager') {
							if(isset($_POST['clearFile'])) {
									$info = info("Anda yakin akan menghapus semua isi soal inie?");
									$files = glob('../files/*'); // get directory contents
								foreach ($files as $file) { // iterate files      
								   // Check if file
								   if (is_file($file)) {
									  unlink($file); // delete file
								   }
								}													
							}	
								echo "
											<div class='box box-success direct-chat direct-chat-warning'>
												<div class='box-header with-border bg-green'>
													<h3 class='box-title'><i class='fa fa-history'></i> Berkas File Pendukung Soal</h3>
													<div class='box-tools pull-right'>
														<form action='' method='post'>
														<button type='submit' name='clearFile' class='btn btn-sm btn-danger' title='Kosongkan Berkas File Pendukung Soal'><i class='fa fa-trash-o'></i> Kosongkan</button>
														</form>
													</div>
												</div><!-- /.box-header -->												
							<iframe  width='100%' height='550' frameborder='0' src='ifm.php'>
							</iframe>
							
							";
						}
												
						// mata pelajaran
						elseif($pg=='matapelajaran') {
							cek_session_admin();
							$pesan='';
						if(isset($_POST['simpanmapel'])){
							$kode=str_replace(' ', '',$_POST['kodemapel']);
							$nama=addslashes($_POST['namamapel']);
							$cek=mysql_num_rows(mysql_query("select * from mata_pelajaran where kode_mapel='$kode'"));
							if($cek==0){
							$exec=mysql_query("INSERT INTO mata_pelajaran (kode_mapel,nama_mapel)value('$kode','$nama')");
							$pesan= "<div class='alert alert-success alert-dismissible'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<i class='icon fa fa-info'></i>
										Data Berhasil ditambahkan ..
										</div>";
							}else{
								$pesan= "<div class='alert alert-warning alert-dismissible'>
										<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
										<i class='icon fa fa-info'></i>
										Maaf Kode Mapel Sudah ada !
										</div>";
							}
						}
						if(isset($_POST['importmapel'])){
							$file = $_FILES['file']['name'];
                                $temp = $_FILES['file']['tmp_name'];
                                $ext = explode('.',$file);
                                $ext = end($ext);
                                if($ext<>'xls') {
                                    $info = info('Gunakan file Ms. Excel 93-2007 Workbook (.xls)','NO');
                                } else {
									
                                    $data = new Spreadsheet_Excel_Reader($temp);
                                    $hasildata = $data->rowcount($sheet_index=0);
                                    $sukses = $gagal = 0;
                                    for ($i=2; $i<=$hasildata; $i++) {
										
                                        $kode = addslashes($data->val($i,2));
                                        $nama = addslashes($data->val($i,3)); 
										$kode=str_replace(' ', '',$kode);
										$nama=addslashes($nama);
										$cek=mysql_num_rows(mysql_query("select * from mata_pelajaran where kode_mapel='$kode'"));
                                       if($kode<>'' and $nama<>''){
											if($cek==0){
											$exec = mysql_query("INSERT INTO mata_pelajaran (kode_mapel,nama_mapel) VALUES ('$kode','$nama')");                                        
											($exec) ? $sukses++ : $gagal++; 
											}
									   }else{
										$gagal++;   
									   }
                                    }
                                    $total = $hasildata-1;
                                    $info = info("Berhasil: $sukses | Gagal: $gagal | Dari: $total",'OK');
									
                                }
						}
						echo "
								<div class='row'><div class='col-md-12'>$pesan</div>
									<div class='col-md-12'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Mata Pelajaran</h3>
												<div class='box-tools pull-right btn-group'>
													<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahmapel'><i class='fa fa-check'></i> Tambah Mapel</button>
													<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#importmapel'><i class='fa fa-upload'></i> Import Mapel</button>
												</div>
									
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class='table-responsive'>
												<table id='tablemapel' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>Kode Mapel</th>
															<th>Mata Pelajaran</th>
															
														</tr>
													</thead>
													<tbody>";
													
													$mapelQ = mysql_query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
								
													while($mapel = mysql_fetch_array($mapelQ)) {
														$no++; 
														echo "
															<tr>
																<td>$no</td>
																<td>$mapel[kode_mapel]</td>
																<td>$mapel[nama_mapel]</td>
																
																
															</tr>";
													}
													echo "
													</tbody>
												</table>
												</div>
												
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
															<div class='modal fade' id='tambahmapel' style='display: none;'>
															<div class='modal-dialog'>
															<div class='modal-content'>
															<div class='modal-header bg-blue'>
															<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Tambah Mata Pelajaran</h3>
															</div>
															<div class='modal-body'>
															<form action='' method='post'>
															<div class='form-group'>
																<label>Kode Mapel</label>
																<input type='text' name='kodemapel' class='form-control'  required='true'/>
															</div>
															<div class='form-group'>
																<label>Nama Pelajaran</label>
																<input type='text' name='namamapel'  class='form-control' required='true'/>
															</div>
															<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='simpanmapel' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
															</div>
															</form>
															</div>
								
															</div>
															<!-- /.modal-content -->
															</div>
															<!-- /.modal-dialog -->
															</div>
															
															<div class='modal fade' id='importmapel' style='display: none;'>
															<div class='modal-dialog'>
															<div class='modal-content'>
															<div class='modal-header bg-blue'>
															<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Tambah Mata Pelajaran</h3>
															</div>
															<div class='modal-body'>
															<form action='' method='post' enctype='multipart/form-data'>
															<div class='form-group'>
																<label>Pilih File</label>
																<input type='file' name='file' class='form-control' required='true'/>
															</div>
															<p>
																Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Excel 97-2003 Workbook (.xls) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
															</p>
														
															<a href='importdatamapel.xls'><i class='fa fa-file-excel-o'></i> Download Format</a>
													   
															<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='importmapel' class='btn btn-sm btn-primary'><i class='fa fa-upload'></i> Simpan</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
															</div>
															</form>
															</div>
								
															</div>
															<!-- /.modal-content -->
															</div>
															<!-- /.modal-dialog -->
															</div>
															
															
								
						";
						}
						
						//Membuat token
						elseif($pg=='token') {
						
						
						if(isset($_POST['generate'])){
						function create_random($length)
						{
							$data = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
							$string = '';
							for($i = 0; $i < $length; $i++) {
							$pos = rand(0, strlen($data)-1);
							$string .= $data{$pos};
						}
						return $string;
						}
						$token=create_random(6);
						$now = date('Y-m-d H:i:s');
						$cek=mysql_num_rows(mysql_query("select * from token"));
						if($cek<>0){
						$query=mysql_fetch_array(mysql_query("select time from token"));
						$time=$query['time'];
						$tgl=buat_tanggal('H:i:s',$time);
						$exec=mysql_query("update token set token='$token', time='$now' where  id_token='1'");
						}else{
						$exec = mysql_query("INSERT INTO token (token,masa_berlaku) VALUES ('$token','00:15:00')");
						}
						}
						$token=mysql_fetch_array(mysql_query("select token from token"));
						echo "
						<div class='row'>
						<form action='' method='post'>
						<div class='col-md-6'>
						<div class='box box-primary'>
							<div class='box-header with-border'>
								<h3 class='box-title'> Generate</h3>
								<div class='box-tools pull-right'>
													
								</div>
							</div><!-- /.box-header -->
						<div class='box-body'>
											 
						<div class='col-xs-12'>
							
                            <div class='small-box bg-aqua'>
                                <div class='inner'>
									
                                    <h3><span name='token' id='isi_token'>$token[token]</span></h3>
                                    <p>Token Tes</p>
                                </div>
                                <div class='icon'>
                                    <i class='fa fa-barcode'></i>
                                </div>
                            </div>   
                           
							<button name='generate' class='btn btn-block btn-flat btn-danger'>Generate</button>
                        </div>
						</div><!-- /.box-body -->
						</div><!-- /.box -->
						</div>
						</form>
									<div class='col-md-6'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'> Data Token</h3>
												
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class='table-responsive'>
												<table  class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'></th>
															<th>Token</th>
															<th>Waktu Generate</th>
															<th>Masa Berlaku</th>
															
															
														</tr>
													</thead>
													<tbody>";
													$tokenku = mysql_query("SELECT * FROM token ");
													while($token = mysql_fetch_array($tokenku)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$token[token]</td>
																<td>$token[time]</td>
																<td>$token[masa_berlaku]</td>
																
																
															</tr>
															
														";
															
													}
													echo "
													</tbody>
												</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
										</div>				
										
						</div>";
						}
						elseif($pg=='pengumuman') {
							cek_session_admin();
						if(isset($_POST['simpanpengumuman'])){
												$exec = mysql_query("INSERT INTO pengumuman (judul,text,user,type) VALUES ('$_POST[judul]','$_POST[pengumuman]','$pengawas[id_pengawas]','$_POST[tipe]')");
													if(!$exec) {
														$info = info("Gagal menyimpan!","NO");
													} else {
														jump("?pg=$pg");
													}
												}
						echo "
								<div class='row'>
								<form action='' method='post'>
										<div class='col-md-6'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'> Tulis Pengumuman</h3>
												<div class='box-tools pull-right'>
													<button type='submit' name='simpanpengumuman' class='btn btn-sm btn-primary' ><i class='fa fa-pencil-square-o'></i> Simpan</button>
													<a href='?pg=$pg' class='btn btn-sm btn-danger' ><i class='fa fa-times'></i></a>
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>
												<div class='col-sm-12'>
												<div class='form-group'>
												<label >Judul </label>
												<input type='text' class='form-control' name='judul' placeholder='Judul' required>
												</div>
												</div>
												<div class='col-sm-12'>
												<div class='form-group'>
												<label >Jenis Pengumuman </label><br>
												<input type='radio' name='tipe' value='internal' checked> <span class='text-green'><b>guru</b></span> &nbsp; &nbsp;&nbsp;<input type='radio' name='tipe' value='eksternal'> <span class='text-blue'><b>siswa</b></span>
												</div>
												</div>
												<div class='col-sm-12'>
												<div class='form-group'>
												<label >Informasi Pengumuman </label>
												<textarea id='txtpengumuman' name='pengumuman' class='form-control'></textarea>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
										</div>
										</form>
										
										
								</div>
								<div class='col-md-6'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'> Pengumuman</h3>
												
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class='table-responsive'>
												<table id='example1' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'></th>
															<th>Pengumuman</th>
															
															<th>Untuk</th>
															
															<th width=60px></th>
														</tr>
													</thead>
													<tbody>";
													$pengumumanq = mysql_query("SELECT * FROM pengumuman ORDER BY date desc");
													while($pengumuman = mysql_fetch_array($pengumumanq)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$pengumuman[judul]</td>
																
																<td>";if($pengumuman['type']=='eksternal'){echo "<small class='label bg-blue'>siswa</label>";}else{echo "<small class='label bg-green'>guru</label>";}echo "</td>
																
																<td align='center'>
																<div class='btn-group'>
																	<!--<a href='?pg=$pg&ac=edit&id=$pengumuman[id_pengumuman]'> <button class='btn btn-xs btn-warning'><i class='fa fa-pencil-square-o'></i></button></a>-->
																	<a><button class='btn btn-danger btn-xs' data-toggle='modal' data-target='#hapus$pengumuman[id_pengumuman]'><i class='fa fa-trash-o'></i></button></a>
																</div>
																</td>
															</tr>
															
														";
													$info = info("Anda yakin akan menghapus pengumuman ini  ?");
													if(isset($_POST['hapus'])) {
													$exec = mysql_query("DELETE  FROM pengumuman WHERE id_pengumuman = '$_REQUEST[idu]'");
													(!$exec) ? info("Gagal menyimpan","NO") : jump("?pg=$pg");
	
													}	
													echo "
													<div class='modal fade' id='hapus$pengumuman[id_pengumuman]' style='display: none;'>
													<div class='modal-dialog'>
													<div class='modal-content'>
													<div class='modal-header bg-red'>
													<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Hapus Pengumuman</h3>
															</div>
													<div class='modal-body'>
													<form action='' method='post'>
													<input type='hidden' id='idu' name='idu' value='$pengumuman[id_pengumuman]'/>
													<div class='callout '>
															<h4>$info</h4>
													</div>
													<div class='modal-footer'>
													<div class='box-tools pull-right btn-group'>
																<button type='submit' name='hapus' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
													</div>	
													</div>
													</form>
													</div>
								
													</div>
														<!-- /.modal-content -->
													</div>
														<!-- /.modal-dialog -->
													</div>
														";
													}
													echo "
													</tbody>
												</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
										</div>
									<script>
									tinymce.init({
										selector: '#txtpengumuman',
										plugins: [
											'advlist autolink lists link image charmap print preview hr anchor pagebreak',
											'searchreplace wordcount visualblocks visualchars code fullscreen',
											'insertdatetime media nonbreaking save table contextmenu directionality',
											'emoticons template paste textcolor colorpicker textpattern imagetools uploadimage paste'
										],
										
										toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist  backcolor forecolor | emoticons code | imagetools link image paste ',
										fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
										paste_data_images: true,
										paste_as_text: true,
										images_upload_handler: function (blobInfo, success, failure) {
											success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
										},
										image_class_list: [
										{title: 'Responsive', value: 'img-responsive'}
										],									
										});
									</script>";
						}
						elseif($pg=='guru') {
						cek_session_admin();
						if(isset($_POST['clearguru'])) {
							mysql_query("TRUNCATE walikls");
							mysql_query("DELETE FROM pengawas where level='guru'");
							jump('?pg=guru');
						}
						echo "
								<div class='row'>
									<div class='col-md-8'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Manajemen Guru</h3>
												<div class='box-tools pull-right btn-group'>
													<a href='?pg=importguru' class='btn btn-sm btn-primary'><i class='fa fa-upload'></i> Import Guru</a>													
												<button data-toggle='modal' data-target='#clearguru' class='btn btn-danger btn-sm' title='Kosongkan Wali Kelas'><i class='fa fa-trash-o'></i> Kosongkan Guru</button>	
												</div>
														<script src='../plugins/jQuery/jquery-3.1.1.min.js'></script>
														<script src='../dist/bootstrap/js/bootstrap.min.js'></script>
														<script >$(document).ready(function () {
															$('#modal-default').modal('show');
														});</script>
														<form action='' method='post'>
																<div class='modal fade' id='clearguru' >
																  <div class='modal-dialog'>
																	<div class='modal-content'>
																	  <div class='modal-header'>
																		<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
																		  <span aria-hidden='true'>×</span></button>
																		<h4 class='modal-title'>Perhatian</h4>
																	  </div>
																	  <div class='modal-body'>
																		<p>Kosongkan semua data Guru?</p>
																	  </div>
																	  <div class='modal-footer'>
																		<button type='button' class='btn btn-default' data-dismiss='modal' aria-label='Close'> Batal</button>
																		<button type='submit' name='clearguru' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Oke</button>
																		
																	  </div>
																	</div>
																	<!-- /.modal-content -->
																  </div>
																  <!-- /.modal-dialog -->
																</div>	
													</form>
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class='table-responsive'>
												<table id='example1' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>NIP</th>
															<th>Nama</th>
															<th>Username</th>
															<th>Password</th>
															<th>Level</th>
															<th width=60px></th>
														</tr>
													</thead>
													<tbody>";
													$guruku = mysql_query("SELECT * FROM pengawas where level='guru'  ORDER BY nama ASC");
													while($pengawas = mysql_fetch_array($guruku)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$pengawas[nip]</td>
																<td>$pengawas[nama]</td>
																<td><small class='label bg-purple'>$pengawas[username]</small></td>
																<td><small class='label bg-blue'>$pengawas[password]</small></td>
																<td>$pengawas[level]</td>
																<td align='center'>
																<div class='btn-group'>
																	<a href='?pg=$pg&ac=edit&id=$pengawas[id_pengawas]'> <button class='btn btn-xs btn-warning'><i class='fa fa-pencil-square-o'></i></button></a>
																	<a href='?pg=$pg&ac=hapus&id=$pengawas[id_pengawas]'> <button class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></button></a>
																</div>
																</td>
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									<div class='col-md-4'>";
										if($ac=='') {
											if(isset($_POST['submit'])) {
												$nip = $_POST['nip'];
												$nama = $_POST['nama'];
												$nama = str_replace("'","&#39;",$nama);
												$username = $_POST['username'];
												$pass1 = $_POST['pass1'];
												$pass2 = $_POST['pass2'];
												
												$cekuser = mysql_num_rows(mysql_query("SELECT * FROM pengawas WHERE username='$username'"));
												if($cekuser>0) {
													$info = info("Username $username sudah ada!","NO");
												} else {
													if($pass1<>$pass2) {
														$info = info("Password tidak cocok!","NO");
													} else {
														$password = $pass1;
														$exec = mysql_query("INSERT INTO pengawas (nip,nama,username,password,level) VALUES ('$nip','$nama','$username','$password','guru')");
														(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
													}
												}
											}
											echo "
												<form action='' method='post'>
													<div class='box box-primary'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Tambah</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
															<div class='form-group'>
																<label>NIP</label>
																<input type='text' name='nip' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Nama</label>
																<input type='text' name='nama' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Username</label>
																<input type='text' name='username' class='form-control' required='true'/>
															</div>
															
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Password</label>
																		<input type='password' name='pass1' class='form-control' required='true'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Ulang Password</label>
																		<input type='password' name='pass2' class='form-control' required='true'/>
																	</div>
																</div>
															</div>
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}
										if($ac=='edit') {
											$id = $_GET['id'];
											$value = mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE id_pengawas='$id'"));
											if(isset($_POST['submit'])) {
												$nip = $_POST['nip'];
												$nama = $_POST['nama'];
												$nama = str_replace("'","&#39;",$nama);
												$username = $_POST['username'];
												$pass1 = $_POST['pass1'];
												$pass2 = $_POST['pass2'];
												
												if($pass1<>'' AND $pass2<>'') {
													if($pass1<>$pass2) {
														$info = info("Password tidak cocok!","NO");
													} else {
														$password =$pass1;
														$exec = mysql_query("UPDATE pengawas SET nip='$nip',nama='$nama',username='$username',password='$password',level='guru' WHERE id_pengawas='$id'");
													}
												} else {
													$exec = mysql_query("UPDATE pengawas SET nip='$nip',nama='$nama',username='$username',level='guru' WHERE id_pengawas='$id'");
												}
												(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
											}
											echo "
												<form action='' method='post'>
													<div class='box box-success'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Edit</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<a href='?pg=$pg' class='btn btn-sm btn-danger' title='Batal'><i class='fa fa-times'></i></a>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
															<div class='form-group'>
																<label>NIP</label>
																<input type='text' name='nip' value='$value[nip]' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Nama</label>
																<input type='text' name='nama' value='$value[nama]' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Username</label>
																<input type='text' name='username' value='$value[username]' class='form-control' required='true'/>
															</div>
															
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Password</label>
																		<input type='password' name='pass1' class='form-control'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Ulang Password</label>
																		<input type='password' name='pass2' class='form-control'/>
																	</div>
																</div>
															</div>
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}
										if($ac=='hapus') {
											$id = $_GET['id'];
											$info = info("Anda yakin akan menghapus pengawas ini?");
											if(isset($_POST['submit'])) {
												$exec = mysql_query("DELETE FROM pengawas WHERE id_pengawas='$id'");
												(!$exec) ? $info = info("Gagal menghapus!","NO") : jump("?pg=$pg");
											}
											echo "
												<form action='' method='post'>
													<div class='box box-danger'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Hapus</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
																<a href='?pg=$pg' class='btn btn-sm btn-default' title='Batal'><i class='fa fa-times'></i></a>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}
										echo "
									</div>
								</div>
							";
						}
						elseif($pg=='beritaacara') {
							if($pengawas['level']=='admin') {
							$idberita=$_GET['id'];
							$sqlx=mysql_query("select * from berita a left join mapel b ON a.id_mapel=b.id_mapel left join mata_pelajaran c ON b.nama=c.kode_mapel where a.id_berita='$idberita'");
							
							$ujian=mysql_fetch_array($sqlx);
							
							$hari=buat_tanggal('D',$ujian['tgl_ujian']);
							$tanggal=buat_tanggal('d',$ujian['tgl_ujian']);	
							$bulan=buat_tanggal('m',$ujian['tgl_ujian']);
							$tahun=buat_tanggal('Y',$ujian['tgl_ujian']);
							if(date('m')>=7 AND date('m')<=12) {
								$ajaran = date('Y')."/".(date('Y')+1);
							}
							elseif(date('m')>=1 AND date('m')<=6) {
								$ajaran = (date('Y')-1)."/".date('Y');
							}
						echo "
						<div class='row'>
						<div class='col-md-12' >
						<div class='box box-primary' >
						<div class='box-header'>
						<h3 class='box-title'>Preview Berita Acara</h3>
						<div class='box-tools pull-right btn-group'>
						<button  onclick=frames['printberita'].print() class='btn btn-sm btn-primary'><i class='fa fa-print'></i> Print</button>
						<iframe name='printberita' src='beritaacara.php?id=$idberita' style='border:none;width:1px;height:1px;'></iframe>
						</div>
						</div>
						<div class='box-body'  style='background:#c3c3c3;  height:1275px;'>
						<div class='table-responsive'>
						<div style='background:#fff; width:80%; margin:0 auto; padding:35px; height:90%;'>
						<table border='0' width='100%'>
						<tr>
						<td rowspan='4' width='140' align='center'><img src='$homeurl/$setting[logo_instansi]' width='80'></td>
						<td colspan='2'  align='center'><font size='+1'><b>BERITA ACARA PELAKSANAAN</b></font></td>
						<td rowspan='7' width='120' align='center'><img src='$homeurl/$setting[logo]' width='65'></td>
						</tr>
						 <tr>
						<td colspan='2' align='center'><font size='+1'><b>".strtoupper($setting['nama_ujian'])."</b></font></td>
						</tr>
						<tr>
						<td colspan='2' align='center'><font size='+1'><b>TAHUN PELAJARAN $ajaran</b></font></td>
						</tr>  
						</table>
						<br>
						<table border='0' width='95%' align='center' >
						<tr height='30'>
						<td height='30' colspan='4' style='text-align: justify;'>Pada hari ini <b> $hari </b>  tanggal <b>$tanggal</b> bulan <b>$bulan</b> tahun <b>$tahun</b>
						, di $setting[sekolah] telah diselenggarakan ".ucwords(strtolower($setting['nama_ujian']))." untuk Mata Pelajaran <b>$ujian[nama_mapel]</b> dari pukul <b>$ujian[mulai]</b> sampai dengan pukul <b>$ujian[selesai]</b></td>
						</tr>
						</table>
						<table border='0' width='95%' align='center'>
						<tr height='30'>
						<td height='30' width='5%'>1.</td>
						<td height='30' width='30%'>Kode Sekolah</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$setting[kode_sekolah]</td>
						</tr>
						<tr height='30'>
						<td height='30' width='10px'></td>
						<td height='30'>Sekolah/Madrasah</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$setting[sekolah]</td>  
						</tr>
						<tr height='30'>
						<td height='30' width='5%'>.</td>
						<td height='30' width='30%'>Sesi</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$ujian[sesi]</td>
						</tr>
						<tr height='30'>
						<td height='30' width='5%'>.</td>
						<td height='30' width='30%'>Ruang</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$ujian[ruang]</td>
						</tr>
						<tr height='30'>
						<td height='30' width='5%'></td>
						<td height='30' width='30%'>Jumlah Hadir (Ikut Ujian)</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$ujian[ikut]</td>
						</tr>
						<tr height='30'>
						<td height='30' width='10px'></td>
						<td height='30'>Jumlah Tidak Hadir</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$ujian[susulan]</td>  
						</tr>
						<tr height='30'>
						<td height='30' width='10px'></td>
						<td height='30'>Jumlah Peserta Seharusnya</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>$ujian[jumlahujian]</td>  
						</tr>
						<tr height='30'>
						<td height='30' width='10px'></td>
						<td height='30'>Nomer Peserta</td>
						<td height='30' width='60%' style='border-bottom:thin solid #000000'>";
						$dataArray = unserialize($ujian['no_susulan']);
							foreach ($dataArray as $key => $value) {
								echo "<small class='label label-success'>$value </small>&nbsp;";
							}
						echo "
						</td>  
						</tr>
						<tr height='30'>
						<td height='30' width='10px'></td></tr>    
						<tr height='30'>
						<td height='30' width='5%'>2.</td>
						<td colspan='2' height='30' width='30%'>Catatan selama ".ucwords(strtolower($setting['nama_ujian']))." </td>
						</tr>
						<tr height='120px'>
						<td height='30' width='5%'></td>
						<td colspan='2' style='border:solid 1px black'>&nbsp;&nbsp;$ujian[catatan]</td></tr>
   
						<tr height='30'>
						<td height='30' colspan='2' width='5%'>Yang membuat berita acara : </td></tr>
						</table>
						<table border='0' width='80%' style='margin-left:50px'>  
						<tr><td colspan='4' ></td>
						<td height='30' width='30%'>TTD</td>
						<tr><td width='10%'>1. </td><td width='20%'>Proktor</td><td width='30%' style='border-bottom:thin solid #000000'>$ujian[nama_proktor]</td>
						<td height='30' width='5%'></td><td height='30' width='35%'></td>
						</tr>
						<tr><td width='10%'>   </td><td width='20%'>NIP. </td><td width='30%' style='border-bottom:thin solid #000000'>$ujian[nip_proktor]</td>
						<td height='30' width='5%'></td><td height='30' width='35%' style='border-bottom:thin solid #000000'>  1. </td>
						</tr>
						<tr><td colspan='4' ></td>
						
						<tr><td width='10%'>2. </td><td width='20%'>Pengawas</td><td width='30%' style='border-bottom:thin solid #000000'>$ujian[nama_pengawas]</td>
						<td height='30' width='5%'></td><td height='30' width='35%'></td>
						</tr>
						<tr><td width='10%'>   </td><td width='20%'>NIP. </td><td width='30%' style='border-bottom:thin solid #000000'>$ujian[nip_pengawas]</td>
						<td height='30' width='5%'></td><td height='30' width='35%' style='border-bottom:thin solid #000000'>  2. </td>
						</tr>
						<tr><td colspan='4' ></td>
						
						<tr><td width='10%'>3. </td><td width='20%'>Kepala Sekolah</td><td width='30%' style='border-bottom:thin solid #000000'>$setting[kepsek]</td>
						<td height='30' width='5%'></td><td height='30' width='35%'></td>
						</tr>
						<tr><td width='10%'>   </td><td width='20%'>NIP. </td><td width='30%' style='border-bottom:thin solid #000000'>$setting[nip]</td>
						<td height='30' width='5%'></td><td height='30' width='35%' style='border-bottom:thin solid #000000'>  3. </td>
						</tr>
						</table><br><br><br><br><br>
						
						<table width='100%' height='30'>
						<tbody><tr>
						<td width='25px' style='border:1px solid black'></td>
						<td width='5px'>&nbsp;</td>
						<td style='border:1px solid black;font-weight:bold;font-size:14px;text-align:center;'>$setting[nama_ujian] $setting[sekolah]</td>
						<td width='5px'>&nbsp;</td>
						<td width='25px' style='border:1px solid black'></td>
						</tr>
						</tbody>
						</table>
						</div>
						</div>
						</div>
						</div>
						</div>
						</div>
						
						";
						}
						}// jadwal ujian
						elseif($pg=='jadwal') {
							
						if(isset($_POST['tambahjadwal'])) {
												
												$tgl_ujian = $_POST['tgl_ujian'];
												$tgl_selesai = $_POST['tgl_selesai'];
												$idmapel =$_POST['idmapel'];
												$mapelx=mysql_fetch_array(mysql_query("select * from mapel where id_mapel='$idmapel'"));
												$namamapel=$mapelx['nama'];
												$mapely=mysql_fetch_array(mysql_query("select * from mata_pelajaran where kode_mapel='$namamapel'"));
												$nama_mapel=$mapely['nama_mapel'];
												$jmlsoal=$mapelx['jml_soal'];
												$jml_esai=$mapelx['jml_esai'];
												$bobot_pg=$mapelx['bobot_pg'];
												$bobot_esai=$mapelx['bobot_esai'];
												$tampil_pg=$mapelx['tampil_pg'];
												$tampil_esai=$mapelx['tampil_esai'];
												$level=$mapelx['level'];
												$id_pk=$mapelx['idpk'];
												$wkt = explode(" ",$tgl_ujian);
												$wkt_ujian = $wkt[1];
												$lama_ujian = $_POST['lama_ujian'];
												$sesi = $_POST['sesi'];
												$idguru = $mapelx['idguru'];
												$kelas = $mapelx['kelas'];
												$acak = (isset($_POST['acak'])) ? 1 : 0;
												$token = (isset($_POST['token'])) ? 1 : 0;
												$hasil = (isset($_POST['hasil'])) ? 1 : 0;
												
												$cek = mysql_num_rows(mysql_query("SELECT * FROM ujian WHERE nama='$nama_mapel' AND sesi='$sesi' AND level='$level' AND kelas ='$kelas'"));
												if($cek>0) {
												echo "
												<div class='alert alert-danger alert-dismissible'>
															<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
															<h4><i class='icon fa fa-info'></i> Info</h4>
															Data jadwal tidak tersimpan karena data jadwal sudah ada
															</div>
												";
												}else{
												if($pengawas['level']=='admin') {
												$exec = mysql_query("INSERT INTO ujian (id_pk, id_mapel, nama,jml_soal,jml_esai,lama_ujian, tgl_ujian,tgl_selesai, waktu_ujian, level, sesi, acak, token,status,bobot_pg,bobot_esai,id_guru,tampil_pg,tampil_esai,hasil,kelas) VALUES ('$id_pk','$idmapel','$nama_mapel','$jmlsoal','$jml_esai','$lama_ujian','$tgl_ujian','$tgl_selesai','$wkt_ujian','$level','$sesi','$acak','$token','1','$bobot_pg','$bobot_esai','$idguru','$tampil_pg','$tampil_esai','$hasil','$kelas')");
												
												}else{
												$exec = mysql_query("INSERT INTO ujian (id_pk, id_mapel, nama,jml_soal,jml_esai,lama_ujian, tgl_ujian, tgl_selesai, waktu_ujian, level, sesi, acak, token,status,bobot_pg,bobot_esai,id_guru,tampil_pg,tampil_esai,hasil,kelas) VALUES ('$id_pk','$idmapel','$nama_mapel','$jmlsoal','$jml_esai','$lama_ujian','$tgl_ujian','$tgl_selesai','$wkt_ujian','$level','$sesi','$acak','$token','1','$bobot_pg','$bobot_esai','$id_pengawas','$tampil_pg','$tampil_esai','$hasil','$kelas')");
												}
												echo "
												<div class='alert alert-success alert-dismissible'>
															<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
															<h4><i class='icon fa fa-info'></i> Info</h4>
															Data jadwal ujian berhasil disimpan,,,
															</div>
												";
												}
												
											}
											
						echo "		<div class='modal fade' id='tambahjadwal' style='display: none;'>
										<div class='modal-dialog'>
											<div class='modal-content'>
											
												<div class='modal-header bg-blue'>
												<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
													<h3 class='modal-title'>Tambah Jadwal Ujian</h3>													
												</div>
												<div class='modal-body'>
												<div class='alert alert-danger '>
													<i class='icon fa fa-info'></i> Sebelum ujian jangan lupa kosongkan Pengacak soal dan opsi
												</div>
												<form action='' method='post'>
														
															<div class='form-group'>
																<label>Nama Bank Soal</label>
																<select name='idmapel' class='form-control' required='true'>";
																if($pengawas['level']=='admin') {
																$namamapelx = mysql_query("SELECT * FROM mapel where status='1' order by nama ASC");
																}else{
																$namamapelx = mysql_query("SELECT * FROM mapel where status='1' and idguru='$id_pengawas' order by nama ASC");	
																}
																while($namamapel = mysql_fetch_array($namamapelx)) {
																	$dataArray = unserialize($namamapel['kelas']);
																	
																	echo "<option value='$namamapel[id_mapel]'>$namamapel[nama] - $namamapel[level] - ";
																	
																	foreach ($dataArray as $key => $value) {
																		echo "$value ";
																	}
																	echo "
																	</option>";
																}
																echo"
															</select>
															</div>
															
															<div class='form-group'>
															<div class='row'>
															<div class='col-md-6'>
																<label>Tanggal Mulai Ujian</label>
																<input type='text' name='tgl_ujian'   class='tgl form-control' autocomplete='off' required='true'/>
															</div>
															
															
															<div class='col-md-6'>
																<label>Tanggal Waktu Expired</label>
																<input type='text' name='tgl_selesai'   class='tgl form-control' autocomplete='off' required='true'/>
															</div>
															</div>                                                     
															</div>
															
															
															<div class='form-group'>
															<label>Sesi</label>
															<select name='sesi' class='form-control' required='true'>";
																$sesix = mysql_query("SELECT * from sesi");	
																
																while($sesi = mysql_fetch_array($sesix)) {
																
																	echo "<option value='$sesi[kode_sesi]'>$sesi[kode_sesi]</option>";
																}
																echo"
															</select>
															</div>
															
															
															<div class='form-group'>
																<label>Lama ujian (menit)</label>
																<input  type='number' name='lama_ujian'   class='form-control' required='true'/>
																
															</div>
															
															
															<div class='form-group'>
																<label></label><br>
																	<label>
																		<input type='checkbox' class='icheckbox_square-green' name='acak' value='1' $acak/> Acak Soal
																	</label>";
																if($pengawas['level']=='admin') {
																echo "
																	<label>
																		<input type='checkbox' class='icheckbox_square-green' name='token' value='1' $token/> Token Soal
																	</label> ";
																}
																echo "
																	<label>
																		<input type='checkbox' class='icheckbox_square-green' name='hasil' value='1' $hasil/> Hasil Tampil
																	</label>
															</div>
															
															<div class='modal-footer'>
															<button name='tambahjadwal' class='btn btn-sm btn-primary' ><i class='fa fa-check'></i> Simpan Semua</button>
															</div>
															</form>
												</div>
											</div>
														<!-- /.modal-content -->
										</div>
														<!-- /.modal-dialog -->
									</div>
									<div class='row'>
										<div class='col-md-12'>
										
													<div class='box'>
														<div class='box-header with-border '>
															<h3 class='box-title'><i class='fa fa-clock-o'></i> Jadwal Ujian | </h3>
															<small class='label bg-blue'> Sebelum ujian dimulai, silahkan reset pengacak soal </small>
															<div class='box-tools pull-right btn-group'>";
															if($ac=='clearacak') {
																	mysql_query("TRUNCATE pengacak");
																	mysql_query("TRUNCATE pengacakopsi");
																	jump('?pg=jadwal');
																}
															if($pengawas['level']=='admin') {
																
															echo "																
																<a href='?pg=$pg&ac=clearacak' class='btn btn-success btn-sm' title='Reset Pengacak Soal'><i class='glyphicon glyphicon-refresh'></i> Reset Pengacak Soal</a>
																<a id='btnhapusjadwal' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-trash'></i> Kosongkan Jadwal</a>
																";
															} echo "
																<button  class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahjadwal'><i class='glyphicon glyphicon-plus'></i> Tambah Jadwal</button>
															</div>
														</div><!-- /.box-header -->
										<div class='box-body'>
															
										
										<div class=''>
											
												<div id='tablereset' class='table-responsive'>
													<table class='table table-bordered table-striped '>
													<thead>
														<tr><th width='5px'><input type='checkbox' id='ceksemua'  ></th>
															<th width='5px'>#</th>
															<th>Mata Pelajaran</th>
															<th>Level/Jur/Kelas</th>
															<th>Durasi</th>
															<th >Tgl Waktu Ujian</th>
															
															<th>Sesi</th>
															<th>Acak/Token/Hasil</th>
															
															<th>Status</th>
															<th width='90px'></th>
														</tr>
													</thead>
													<tbody>";
												if(isset($_POST['update'])) {
												$idujian=$_POST['idu'];
												$sesi=$_POST['sesi'];
												$nama = $_POST['namamapel'];
												$nama = str_replace("'","&#39;",$nama);
												$tglujian = $_POST['tgl_ujian'];
												$tglselesai = $_POST['tgl_selesai'];
												$lama = $_POST['lama_ujian'];
												$waktu = explode(" ",$tglujian);
												$waktu=$waktu[1];
												$status= $_POST['status'];
												$exec = mysql_query("UPDATE ujian SET sesi='$sesi',nama='$nama',tgl_ujian='$tglujian',tgl_selesai='$tglselesai',waktu_ujian='$waktu',lama_ujian='$lama',status='$status' WHERE id_ujian='$idujian'");
												
												(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
												}
												
												if($pengawas['level']=='admin') {
													$mapelQ = mysql_query("SELECT * FROM ujian ORDER BY tgl_ujian ASC, waktu_ujian ASC");
												}else{
													$mapelQ = mysql_query("SELECT * FROM ujian where id_guru='$id_pengawas' ORDER BY tgl_ujian ASC, waktu_ujian ASC");
												}
													while($mapel = mysql_fetch_array($mapelQ)) {
														$tgl = explode(" ",$mapel['tgl_ujian']);
												$tgl=$tgl[0];
														$no++;
														echo "
															<tr><td><input type='checkbox' name='cekpilih[]' class='cekpilih' id='cekpilih-$no' value='$mapel[id_ujian]' ></td>
																<td>$no</td>
																<td>
																"; if($mapel['id_pk']=='0'){$jur='Semua';}else{$jur=$mapel['id_pk'];} 
																echo "<b><small class='label bg-purple'>$mapel[nama]</small></b> 
																</td>
																									
																														
																<td>
																<small class='label label-primary'>$mapel[level]</small>
																<small class='label label-primary'>$jur</small> "; 
																$dataArray = unserialize($mapel['kelas']);
																foreach ($dataArray as $key => $value) {
																	echo "<small class='label label-success'>$value </small>&nbsp;";
																}
																echo "</td>
																<td><small class='label label-warning'>$mapel[tampil_pg] Soal / $mapel[lama_ujian] m</small></td>
																<td><small class='label bg-purple'><i class='fa fa-clock-o'></i> $mapel[tgl_ujian]</small> <small class='label bg-purple'>$mapel[tgl_selesai]</small></td>
																
																<td align='center'><small class='label bg-green'>$mapel[sesi]</small></td>
																<td>";
																if($mapel['acak']==1){
																	echo "<label class='label label-success'>Ya</label> ";
																}elseif($mapel['acak']==0){
																	echo "<label class='label label-danger'>Tidak</label> ";
																}
																if($mapel['token']==1){
																	echo "<label class='label label-success'>Ya</label> ";
																}elseif($mapel['token']==0){
																	echo "<label class='label label-danger'>Tidak</label> ";
																}
																if($mapel['hasil']==1){
																	echo "<label class='label label-success'>Ya</label> ";
																}elseif($mapel['hasil']==0){
																	echo "<label class='label label-danger'>Tidak</label> ";
																}
																
																echo "
																
																</td>
																
																<td align='center'>";
																if($mapel['status']==1){
																	echo "<label class='label label-success'>Aktif</label>";
																}elseif($mapel['status']==0){
																	echo "<label class='label label-danger'>Tidak Aktif</label>";
																}
																echo "
																
																</td>
																<td align='center'>
																	<div class='btn-grou'>
						
																			<a class='btn btn-warning btn-xs' data-toggle='modal' data-target='#edit$mapel[id_ujian]'><i class='fa fa-pencil-square-o'></i></a>
																			
																	</div>
																</td>
															</tr>
															<div class='modal fade' id='edit$mapel[id_ujian]' style='display: none;'>
															<div class='modal-dialog'>
															<div class='modal-content'>
															<div class='modal-header bg-blue'>
															<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Edit Jadwal Ujian</h3>
															</div>
															<div class='modal-body'>
															<div class='alert alert-danger '>
																<i class='icon fa fa-info'></i> Sebelum ujian jangan lupa kosongkan Pengacak soal dan opsi di pengaturan
															</div>
															<form action='' method='post'>
															<div class='form-group'>
																<label>Nama Ujian</label>
																<input type='text' name='namamapel' value='$mapel[nama]'  class='form-control' readonly/>
															</div>
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-6'>
																	<label>Tanggal Ujian</label>
																	<input  name='tgl_ujian' value='$mapel[tgl_ujian]' autocomplete='off' class='tgl form-control' required='true'/>
																</div>
																<div class='col-md-6'>
																	<label>Tanggal Selesai</label>
																	<input  name='tgl_selesai' value='$mapel[tgl_selesai]' autocomplete='off' class='tgl form-control' required='true'/>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-6'>
																<label>Lama Ujian</label>
																<input type='number' name='lama_ujian' value='$mapel[lama_ujian]'  class='form-control' required='true'/>
																</div>
																<div class='col-md-6'>
																<label>Sesi</label>
																<input type='number' name='sesi' value='$mapel[sesi]'  class='form-control' required='true'/>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<label>Status</label>
																<select  name='status'   class='form-control'>
																<option value='1'>Aktif</option>
																<option value='0'>Tidak Aktif</option>
																</select>
															</div>
															<input type='hidden' id='idm' name='idu' value='$mapel[id_ujian]'/>
															<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='update' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Update</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
															</div>
															</form>
															</div>
								
															</div>
															<!-- /.modal-content -->
															</div>
															<!-- /.modal-dialog -->
															</div>
															
															";
													}
													echo "
													</tbody>
												</table>
												</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>";
							if($ac=='kosongkan') {
								mysql_query("TRUNCATE ujian");
								jump('?pg=jadwal');
							}
						}
						elseif($pg=='berita') {
							
											
						echo "		
									<div class='row'>
										<div class='col-md-12'>
										
													<div class='box box-solid'>
														<div class='box-header with-border '>
															<h3 class='box-title'><i class='fa fa-file'></i> Berita Acara</h3>
															<div class='box-tools pull-right btn-group'>";
															if($pengawas['level']=='admin') {
															echo "
																<button id='buatberita' class='btn btn-sm btn-primary'><i class='fa fa-refresh'></i> Generate</button><span>  
																<button id='hapusberita' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i> Delete</button>
																";
															} echo "
															</div>
														</div><!-- /.box-header -->
										
										<div class='box-body'>														
											<div class=''>
												<div id='tablereset' class='table-responsive'>
													<table class='table table-bordered table-striped  table-hover'>
													<thead>
														<tr>
															<th width='5px'><input type='checkbox' id='ceksemua'  ></th>
															<th width='5px'>No</th>
															<th>Mata Pelajaran</th>
															<th>Level/Jur/Kelas</th>
															<th>Sesi</th>
															<th>Ruang</th>
															<th>Hadir</th>
															<th>Tidak Hadir</th>
															<th>Mulai</th>
															<th>Selesai</th>
															<th >Pengawas</th>
															<th width='50px'>Edit|Print</th>
														</tr>
													</thead>
													<tbody>";
												
												
													$beritaQ = mysql_query("SELECT * FROM berita where id_berita");
												
													while($berita= mysql_fetch_array($beritaQ)) {
														
														$mapel=mysql_fetch_array(mysql_query("select * from mapel a left join mata_pelajaran b ON a.nama=b.kode_mapel where a.id_mapel='$berita[id_mapel]'"));
														$no++;
														echo "
															<tr>
																<td><input type='checkbox' name='cekpilih[]' class='cekpilih' id='cekpilih-$no' value='$berita[id_berita]' ></td>
																<td>$no</td>
																<td>
																<b><small class='label bg-purple'>$mapel[nama_mapel]</small></b> 
																</td><td>
																<small class='label label-primary'>$mapel[level]</small>
																<small class='label label-primary'>$mapel[idpk]</small> "; 
																$dataArray = unserialize($mapel['kelas']);
																foreach ($dataArray as $key => $value) {
																	echo "<small class='label label-success'>$value </small>&nbsp;";
																}
																echo "</td>
																<td align='center'><b><small class='label bg-purple'>$berita[sesi]</small></b> </td>
																<td align='center'><small class='label bg-green'>$berita[ruang]</small></td>
																<td align='center'>$berita[ikut]</td>
																<td align='center'>$berita[susulan]</td>
																<td align='center'>$berita[mulai]</td>
																<td align='center'>$berita[selesai]</td>
																<td>$berita[nama_pengawas]</td>
																
																<td align='center'>
																	<div class='btn-group'>
																		<a class='btn btn-primary btn-xs' data-toggle='modal' data-target='#print$berita[id_berita]'><i class='glyphicon glyphicon-print'></i></a>		
																	</div>
																</td>
															</tr>";
															if(isset($_POST['print'])) {
																$idberita=$_POST['idu'];
																$tglujian=$_POST['tgl_ujian'];
																$hadir=$_POST['hadir'];
																$tidakhadir=$_POST['tidakhadir'];
																$jumlahsemua=$_POST['jumlahujian'];
																$jumlahsemua=$hadir+$tidakhadir;
																$mulai = $_POST['mulai'];
																$selesai=$_POST['selesai'];
																$pengawas=$_POST['nama_pengawas'];
																$nippengawas=$_POST['nip_pengawas'];
																$proktor=$_POST['nama_proktor'];
																$nipproktor=$_POST['nip_proktor'];
																$catatan=$_POST['catatan'];
																$nosusulan=serialize($_POST['nosusulan']);
																$exec = mysql_query("UPDATE berita SET ikut='$hadir',susulan='$tidakhadir',jumlahujian='$jumlahsemua',mulai='$mulai',selesai='$selesai',nama_pengawas='$pengawas',nip_pengawas='$nippengawas',
																						nama_proktor='$proktor',nip_proktor='$nipproktor',catatan='$catatan',tgl_ujian='$tglujian',no_susulan='$nosusulan' WHERE id_berita='$idberita'");
																(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=beritaacara&id=$idberita");
															}
															echo "
															<div class='modal fade' id='print$berita[id_berita]' style='display: none;'>
															<div class='modal-dialog'>
															<div class='modal-content'>
															<div class='modal-header bg-blue'>
															<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Print Berita Acara</h3>
															</div>
															<div class='modal-body'>
															<form action='' method='post'>
															<div class='col-md-4'>
															<div class='form-group'>
																<label>Nama Ujian</label>
																<input type='text' name='namamapel' value='$mapel[nama]'  class='form-control' disabled/>
															</div>
															</div>
															<div class='col-md-4'>
															<div class='form-group'>
																<label>Sesi</label>
																<input type='text' name='sesi' value='$berita[sesi]'  class='form-control' disabled/>
															</div>
															</div>
															<div class='col-md-4'>
															<div class='form-group'>
																<label>Ruang</label>
																<input type='text' name='ruang' value='$berita[ruang]'  class='form-control' disabled/>
															</div>
															</div>
															<div class='col-md-4'>
															<div class='form-group'>
																<label>Tanggal Ujian</label>
																<input  name='tgl_ujian' value='$berita[tgl_ujian]'  class='datepicker form-control' autocomplete=off/>
															</div>
															</div>
															
															<div class='col-md-2'>
															<div class='form-group'>
																<label>Mulai</label>
																<input id='waktumulai' type='text' name='mulai'   value='$berita[mulai]' class='timer form-control' autocomplete=off/>
															</div>
															</div>
															<div class='col-md-2'>
															<div class='form-group'>
																<label>Selesai</label>
																<input id='waktumulai' type='text' name='selesai'   value='$berita[selesai]' class='timer form-control' autocomplete=off/>
															</div>
															</div>
															<div class='col-md-2'>
															<div class='form-group'>
																<label>Hadir</label>
																<input type='number' name='hadir' value='$berita[ikut]'  class='form-control' required='true'/>
															</div>
															</div>
															<div class='col-md-2'>
															<div class='form-group'>
																<label>Absen</label>
																<input type='number' name='tidakhadir' value='$berita[susulan]'  class='form-control' required='true'/>
															</div>
															</div>
															<div class='col-md-12'>
															<div class='form-group'>
																<label>Siswa Tidak Hadir</label><br>
																<select name='nosusulan[]' class='form-control select2' multiple='multiple' style='width:100%'>
																
																";
																$lev = mysql_query("SELECT * FROM siswa where ruang='$berita[ruang]' and sesi='$berita[sesi]' order by nama ASC");
																while($siswa = mysql_fetch_array($lev)) {
																	echo "<option value='$siswa[no_peserta]'>$siswa[no_peserta] $siswa[nama]</option>";
																}
																echo"
																</select>
															</div>
															</div>
															
															<div class='col-md-6'>
															<div class='form-group'>
																<label>Nama Proktor</label>
																<input type='text' name='nama_proktor' value='$berita[nama_proktor]'  class='form-control' required='true'/>
															</div>
															</div>
															<div class='col-md-6'>
															<div class='form-group'>
																<label>NIP Proktor</label>
																<input type='text' name='nip_proktor' value='$berita[nip_proktor]'  class='form-control' required='true'/>
															</div>
															</div>
															<div class='col-md-6'>
															<div class='form-group'>
																<label>Nama Pengawas</label>
																<input type='text' name='nama_pengawas' value='$berita[nama_pengawas]'  class='form-control' required='true'/>
															</div>
															</div>
															<div class='col-md-6'>
															<div class='form-group'>
																<label>NIP Pengawas</label>
																<input type='text' name='jumlahsemua' value='$berita[jumlahujian]'  class='form-control' required='true'/>
															</div>
															</div>
															<div class='col-md-12'>
															<div class='form-group'>
																<label>Catatan</label>
																<textarea type='text' name='catatan'  class='form-control' required='true'>$berita[catatan]</textarea>
															</div>
															</div>
															<input type='hidden' id='idm' name='idu' value='$berita[id_berita]'/>
															<input type='hidden' name='idu' value='$berita[id_berita]'/>
															<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='print' class='btn btn-sm btn-success'><i class='fa fa-print'></i> Print</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
															</div>
															</form>
															</div>
								
															</div>
															<!-- /.modal-content -->
															</div>
															<!-- /.modal-dialog -->
															</div>";
															
													}
													echo "
													
													</tbody>
												</table>
												</div>
													
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>";
							
						}
						elseif($pg=='nilai') {
							include 'nilai.php';
							
						}
						elseif($pg=='susulan') {
							
											
						echo "		
									<div class='row'>
										<div class='col-md-12'>
										
													<div class='box box-solid'>
														<div class='box-header with-border '>
															<h3 class='box-title'><i class='fa fa-file'></i> Daftar Siswa Susulan</h3>
															<div class='box-tools pull-right btn-group'>
															</div>
														</div><!-- /.box-header -->
										<div class='box-body'>
															
										
										<div class=''>
											
												<div id='tableberita' class='table-responsive'>
													<table class='table table-bordered table-striped  table-hover'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>No Peserta</th>
															<th>Nama Siswa</th>
															<th>Mata Ujian</th>
														
														</tr>
													</thead>
													<tbody>";
												
												
													$beritaQ = mysql_query("SELECT * FROM berita");
													
													while($berita= mysql_fetch_array($beritaQ)) {
														$mapel=mysql_fetch_array(mysql_query("select * from mapel a left join mata_pelajaran b ON a.nama=b.kode_mapel where a.id_mapel='$berita[id_mapel]'"));
														$dataArray=unserialize($berita['no_susulan']);
														foreach ($dataArray as $key => $value) {
																	
														$siswaQ=mysql_query("select * from siswa where no_peserta='$value'");
															while($siswa= mysql_fetch_array($siswaQ)) {
																$cek=mysql_num_rows(mysql_query("select * from nilai where id_mapel='$berita[id_mapel]' and id_siswa='$siswa[id_siswa]'"));
																if($cek==0){
																$no++;
																echo "
																	<tr>
																	
																		<td>$no</td>
																		<td>$siswa[no_peserta]</td>
																		<td>$siswa[nama]</td>
																		<td>$mapel[nama_mapel]</td>
																	</tr>
																		";
																}
															}
														}
															
													}
													echo "
													
													</tbody>
												</table>
												</div>
													
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>";
							
						}
						elseif($pg=='status') {
							if($ac=='') {
								
								
								echo "
									<div class='row'>
										<div class='col-md-12'>
										<div class='alert alert-warning alert-dismissible'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Status peserta akan muncul saat ujian berlangsung ..
													</div>
											<div class='box box-primary'>
												<div class='box-header with-border'>
													<h3 class='box-title'>Status Peserta </h3>
													<div class='box-tools pull-right btn-group'>
														
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
														<tbody id='divstatus'>
														</tbody>
													</table>
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>
									</div>
								";
							}
							
						}
	
						elseif($pg=='kartu') {
							if($ac=='') {
								echo "
									<div class='row'>
										<div class='col-md-3'></div>
										<div class='col-md-6'>
											<div class='box box-primary'>
												<div class='box-header with-border'>
													<h3 class='box-title'>Kartu Peserta Ujian</h3>
													<div class='box-tools pull-right btn-group'>
														<button class='btn btn-sm btn-primary' onclick=frames['frameresult'].print()><i class='fa fa-print'></i> Print</button>
														<a href='?pg=siswa' class='btn btn-sm btn-danger' title='Batal'><i class='fa fa-times'></i></a>
													</div>
												</div><!-- /.box-header -->
												<div class='box-body'>
													$info
													<div class='form-group'>
														<label>Header Kartu</label>
														<textarea  id='headerkartu' class='form-control' onchange='kirim_form();' rows='3'>$setting[header_kartu]</textarea>
													</div>
													<div class='form-group'>
														
														<label>Kelas</label>
														<div class='row'>
															<div class='col-xs-4'>";
																$total = mysql_num_rows(mysql_query("SELECT * FROM kelas"));
																$limit = number_format($total/3,0,'','');
																$limit2 = number_format($limit*2,0,'','');
																$sql_kelas = mysql_query("SELECT * FROM kelas ORDER BY nama ASC LIMIT 0,$limit");
																while($kelas = mysql_fetch_array($sql_kelas)) {
																	echo "
																		<div class='radio'>
																			<label><input type='radio' name='idk' value='$kelas[id_kelas]' onclick=printkartu('$kelas[0]') /> $kelas[nama]</label>
																		</div>
																	";
																}
																echo "
															</div>
															<div class='col-xs-4'>";
																$sql_kelas = mysql_query("SELECT * FROM kelas ORDER BY nama ASC LIMIT $limit,$limit");
																while($kelas = mysql_fetch_array($sql_kelas)) {
																	echo "
																		<div class='radio'>
																			<label><input type='radio' name='idk' value='$kelas[id_kelas]' onclick=printkartu('$kelas[0]') /> $kelas[nama]</label>
																		</div>
																	";
																}
																echo "
															</div>
															<div class='col-xs-4'>";
																$sql_kelas = mysql_query("SELECT * FROM kelas ORDER BY nama ASC LIMIT $limit2,$total");
																while($kelas = mysql_fetch_array($sql_kelas)) {
																	echo "
																		<div class='radio'>
																			<label><input type='radio' name='idk' value='$kelas[id_kelas]' onclick=printkartu('$kelas[0]') /> $kelas[nama]</label>
																		</div>
																	";
																}
																echo "
															</div>
														</div>
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>
									</div>
									<iframe id='loadframe' name='frameresult' src='kartu.php' style='border:none;width:1px;height:1px;'></iframe>
								";
							}
						}
						//cetaklaporan
							elseif($pg=='laporan') {
							if($ac=='') {
								echo "
									<div class='row'>
										
										<div class='col-md-3'></div>
										<div class='col-md-6'>
										
											<div class='box box-primary'>
												<div class='box-header with-border'>
													<h3 class='box-title'>FORMAT LAPORAN NILAI</h3>
													<div class='box-tools pull-right btn-group'>
														<button id='btnlaporan' class='btn btn-sm btn-primary' onclick=frames['frameresult'].print()><i class='fa fa-print'></i> Print</button>
													</div>
												</div><!-- /.box-header -->
												<div class='box-body'>
													$info
													<div class='form-group'>
														
															<div class='form-group'>
															<label>Pilih Mapel</label>
															<select id='mapel' class='select2 form-control' onchange=printlaporan(); >";
																
																$sql_mapel = mysql_query("SELECT * FROM ujian group by nama");

																while($mapel = mysql_fetch_array($sql_mapel)) {
																	echo "<option value='$mapel[id_mapel]'>$mapel[nama]</option>";
																}
																echo "
															</select>
															</div>	
															<div class='form-group'>
															<label>Pilih Sesi</label>
															
															
															<select id='sesi' class='form-control select2 ' onchange=printlaporan();>";
																
																$sql_sesi = mysql_query("SELECT * FROM siswa GROUP BY sesi ");

																while($sesi = mysql_fetch_array($sql_sesi)) {
																	echo "<option value='$sesi[sesi]'>sesi&nbsp;$sesi[sesi]</option>";
																}
																echo "
															</select>
															</div>	
															
															<div class='form-group'>
															<label>Pilih Ruang</label>
															
															
															<select id='ruang' class='form-control select2 ' onchange=printlaporan();>";
																
																$sql_sesi = mysql_query("SELECT * FROM ruang ");

																while($ruang = mysql_fetch_array($sql_sesi)) {
																	echo "<option value='$ruang[kode_ruang]'>$ruang[kode_ruang]</option>";
																}
																echo "
															</select>
															</div>
															
															<div class='form-group'>
															<label>Pilih Kelas</label>
															
															
															<select id='kelas' class='form-control select2 ' onchange=printlaporan();>";
																
																$sql_sesi = mysql_query("SELECT * FROM kelas ");
																echo "<option value=''>pilih Kelas</option>";
																while($kelas = mysql_fetch_array($sql_sesi)) {
																	echo "<option value='$kelas[id_kelas]'>$kelas[nama]</option>";
																}
																echo "
															</select>
															</div>	
															
															
														
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>
									</div>
									<iframe id='loadlaporan' name='frameresult' src='laporan.php' style='border:none;width:0px;height:0px;'></iframe>";
								
								
							}
						}
						elseif($pg=='absen') {
							if($ac=='') {
								echo "
									<div class='row'>
										
										<div class='col-md-3'></div>
										<div class='col-md-6'>
										
											<div class='box box-primary'>
												<div class='box-header with-border'>
													<h3 class='box-title'>Daftar Hadir Peserta</h3>
													<div class='box-tools pull-right btn-group'>
														<button id='btnabsen' class='btn btn-sm btn-primary' onclick=frames['frameresult'].print()><i class='fa fa-print'></i> Print</button>
													</div>
												</div><!-- /.box-header -->
												<div class='box-body'>
													$info
													<div class='form-group'>
														
															<div class='form-group'>
															<label>Pilih Mapel</label>
															<select id='mapel' class='select2 form-control' onchange=printabsen(); >";
																
																$sql_mapel = mysql_query("SELECT * FROM ujian group by nama");
																echo "<option value=''>pilih mapel</option>";
																while($mapel = mysql_fetch_array($sql_mapel)) {
																	echo "<option value='$mapel[id_mapel]'>$mapel[nama]</option>";
																}
																echo "
															</select>
															</div>	
															<div class='form-group'>
															<label>Pilih Sesi</label>
															
															
															<select id='sesi' class='form-control select2 ' onchange=printabsen();>";
																
																$sql_sesi = mysql_query("SELECT * FROM siswa GROUP BY sesi ");
																echo "<option value=''>pilih sesi</option>";
																while($sesi = mysql_fetch_array($sql_sesi)) {
																	echo "<option value='$sesi[sesi]'>sesi&nbsp;$sesi[sesi]</option>";
																}
																echo "
															</select>
															</div>	
															
															<div class='form-group'>
															<label>Pilih Ruang</label>
															
															
															<select id='ruang' class='form-control select2 ' onchange=printabsen();>";
																
																$sql_sesi = mysql_query("SELECT * FROM ruang ");
																echo "<option value=''>pilih Ruang</option>";
																while($ruang = mysql_fetch_array($sql_sesi)) {
																	echo "<option value='$ruang[kode_ruang]'>$ruang[kode_ruang]</option>";
																}
																echo "
															</select>
															</div>
															
															<div class='form-group'>
															<label>Pilih Kelas</label>
															
															
															<select id='kelas' class='form-control select2 ' onchange=printabsen();>";
																
																$sql_sesi = mysql_query("SELECT * FROM kelas ");
																echo "<option value=''>pilih Kelas</option>";
																while($kelas = mysql_fetch_array($sql_sesi)) {
																	echo "<option value='$kelas[id_kelas]'>$kelas[nama]</option>";
																}
																echo "
															</select>
															</div>	
															
															
														
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>
									</div>
									<iframe id='loadabsen' name='frameresult' src='absen.php' style='border:none;width:0px;height:0px;'></iframe>";
								
								
							}
						}
						//hapuswali
							elseif($pg=='hapuswali') {
								cek_session_admin();
									if($ac==='hapus') {
										$id = $_GET['id'];
										$info = info("Anda yakin akan menghapus wali kelas ini?");
										if(isset($_POST['submit'])) {
											$exec = mysql_query("DELETE FROM walikls WHERE idwali='$id'");
											(!$exec) ? $info = info("Gagal menghapus!","NO") : jump("?pg=walikelas");
						}
						echo "
								<form action='' method='post'>
								<div class='box box-danger'>
									<div class='box-header with-border'>
									<h3 class='box-title'>Hapus</h3>
									<div class='box-tools pull-right btn-group'>
									<button type='submit' name='submit' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
									<a href='?pg=walikelas' class='btn btn-sm btn-default' title='Batal'><i class='fa fa-times'></i></a>
								</div>
								</div><!-- /.box-header -->
								<div class='box-body'>
									$info
								</div><!-- /.box-body -->
								</div><!-- /.box -->
								</form>
								";
							}
						}
						elseif($pg=='siswa') {
							include 'master_siswa.php';
						}
						elseif($pg=='uplfotosiswa'){
							cek_session_admin();
											if(isset($_POST["uplod"]))   {  
											  $output = '';  
											  if($_FILES['zip_file']['name'] != '')  
											  {  
												   $file_name = $_FILES['zip_file']['name'];  
												   $array = explode(".", $file_name);  
												   $name = $array[0];  
												   $ext = $array[1];  
												   if($ext == 'zip')  
												   {  
														$path = '../foto/fotosiswa/';  
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
																  $allowed_ext = array('jpg','JPG');  
																  if(in_array($file_ext, $allowed_ext))  
																  {  
																	   
																	   $output .= '<div class="col-md-3"><div style="padding:16px; border:1px solid #CCC;"><img class="img img-responsive" style="height:150px;" src="../foto/fotosiswa/'.$file.'"   /></div></div>';  
																		
																  }       
															 }  
															 unlink($location);  
															 
															  
															$pesan="
															<div class='alert alert-success alert-dismissible'>
															<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
																<h4><i class='icon fa fa-check'></i> Info</h4>
																Upload File zip berhasil 
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
											if(isset($_POST['hapussemuafoto'])){
											$files = glob('../foto/fotosiswa/*'); // Ambil semua file yang ada dalam folder

												foreach($files as $file){ // Lakukan perulangan dari file yang kita ambil

												  if(is_file($file)) // Cek apakah file tersebut benar-benar ada

													unlink($file); // Jika ada, hapus file tersebut

												}	
											}
											echo "
												
													<div class='box box-danger'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Upload Foto Peserta Ujian</h3>
															<div class='box-tools pull-right btn-group'>
																
																<a href='?pg=$pg' class='btn btn-sm btn-danger' title='Batal'><i class='fa fa-times'></i></a>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
														<div class='alert alert-danger alert-dismissible'>
															<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
															<h4><i class='icon fa fa-info'></i> Info</h4>
															Upload gambar dalam berkas zip,,, Penamaan gambar sesuai dengan no peserta siswa ujian
															</div>
															<form action='' method='post' enctype='multipart/form-data'>
															<div class='col-md-6'>
															<input class='form-control' type='file' name='zip_file'  accept='.zip' />
															</div>
															<div class='col-md-6'>
															<button class='btn btn-danger' name='uplod' type='submit' >Upload Foto</button>
															</div>
															</form>
															
														</div><!-- /.box-body -->
													</div><!-- /.box -->
													<div class='box box-success'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Daftar Foto Peserta</h3>
															<div class='box-tools pull-right btn-group'>
															<form action='' method='post'>
																<button class='btn btn-sm btn-danger' name='hapussemuafoto'>hapus semua foto</button>
																</form>
																
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>";
														$folder = "../foto/fotosiswa/"; //Sesuaikan Folder nya
														if(!($buka_folder = opendir($folder))) die ("eRorr... Tidak bisa membuka Folder");

														$file_array = array(); 
														while($baca_folder = readdir($buka_folder))
														{
														$file_array[] = $baca_folder;
														}

														$jumlah_array = count($file_array);
														for($i=2; $i<$jumlah_array; $i++)
														{
														$nama_file = $file_array;
														$nomor = $i - 1;
														echo "
														<div class='col-md-1'>
														<img class='img-logo' src='$folder$nama_file[$i]' style='width:65px'/><br><br>
														</div>";
														
														}

														closedir($buka_folder);
														echo"	
														</div><!-- /.box-body -->
													</div><!-- /.box -->
											";
										}
						elseif($pg=='importmaster') {
                           cek_session_admin();
						   if($setting['jenjang']=='SMK'){
							   $format='importdatamaster.xls';
						   }else{
								$format='importdatamaster2.xls';
						   }
							echo "
								<div class='row'>
									
									<div class='col-md-12'>
                                        
                                            <div class='box box-primary'>
                                                <div class='box-header with-border '>
                                                    <h3 class='box-title'>Import Data Master</h3>
                                                    <div class='box-tools pull-right btn-group'>
                                                        <a href='$format' class='btn btn-sm btn-primary'><i class='fa fa-file-excel-o'></i> Download Format</a>
														<a href='?pg=siswa' class='btn btn-sm btn-primary' title='Batal'><i class='fa fa-times'></i></a>
                                                    </div>
                                                </div><!-- /.box-header -->
                                                <div class='box-body'>
                                                    $info
												<div class='box box-solid'>
												<div class='box-body'>
                                                    <div class='form-group'>
														<div class='row'>
														<div class='col-md-4'>
														<form id='formsiswa' enctype='multipart/form-data'>
															<label>Pilih File</label>
															
															<input type='file' name='file' accept='.xls' class='form-control' required='true'/>
															
														</div>
														<div class='col-md-4'>
														<label>&nbsp;</label><br>
															<button type='submit' name='submit' class='btn btn-primary'><i class='fa fa-check'></i> Import Data</button>
														</div>
														</form>
														</div>
                                                    </div>
													<p>Menu ini berfungsi untuk import data Master</p>
													<p><b>*Import Data Siswa, Jurusan, Kelas, Ruangan,Sesi dan Level</b>
                                                    <p>
                                                        Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Excel 97-2003 Workbook (.xls) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
                                                    </p>
													<div id='progressbox'></div>
													<div id='hasilimport'></div>
												</div>
												</div>
                                                </div><!-- /.box-body -->
                                                <div class='box-footer'>
                                                    
                                                </div>
                                            </div><!-- /.box -->
                                        
                                    </div>
                                </div>
                            ";
                        }
						elseif($pg=='importword') {
							cek_session_admin();
							if (isset($_POST['tambah'])){
								function xml_attribute($object, $attribute)
								{
										if(isset($object[$attribute]))
										return (string) $object[$attribute];
								}
							  $ekstensi_diperbolehkan	= array('docx');
							  $dir_file = '../word/';
							  $filename = basename($_FILES['file']['name']);
							  $x = explode('.', $filename);
									$ekstensi = strtolower(end($x));
							  $filenamee = date("YmdHis").'-'.basename($_FILES['file']['name']);
							  $uploadfile = $dir_file . $filenamee;
							  $nip=$_SESSION['id'];
							  
							  if ($filename != ''){ 
								if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
								if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
									      
									$info = pathinfo($filenamee);
									$new_name=$info['filename'] . '.Zip'; 
									$new_name_path=$dir_file . $new_name;
									rename($dir_file.$filenamee,$new_name_path);
									$zip = new ZipArchive;
									if ($zip->open($new_name_path)) {
										  $zip->extractTo($dir_file);
										  $zip->close();
										  
											$word_xml=$dir_file."word/document.xml";
											$word_xml_relational=$dir_file."word/_rels/document.xml.rels";
											$content=file_get_contents($word_xml);
											$content = htmlentities(strip_tags($content,"<a:blip>"));
											$xml=simplexml_load_file($word_xml_relational);

											$supported_image = array(
												'gif',
												'jpg',
												'jpeg',
												'png'
											);

											$relation_image=array();
											foreach($xml as $key => $qjd){
											 $ext = strtolower(pathinfo($qjd['Target'], PATHINFO_EXTENSION));
												if (in_array($ext, $supported_image)) {
													$id=xml_attribute($qjd, 'Id');
													$target=xml_attribute($qjd, 'Target');
													$relation_image[$id]=$target;  
												} 
											}
											$word_folder=$dir_file."word";
											$prop_folder=$dir_file."docProps";
											$relat_folder=$dir_file."_rels";
											$content_folder=$dir_file."[Content_Types].xml";

											$rand_inc_number=1;
											foreach($relation_image as $key => $value){
												$rplc_str='&lt;a:blip r:embed=&quot;'.$key.'&quot; cstate=&quot;print&quot;/&gt;';
												$rplc_str2='&lt;a:blip r:embed=&quot;'.$key.'&quot;&gt;&lt;/a:blip&gt;';
												$rplc_str3='&lt;a:blip r:embed=&quot;'.$key.'&quot;/&gt;';
												 $ext_img = strtolower(pathinfo($value, PATHINFO_EXTENSION));
												$imagenew_name=time().$rand_inc_number.".".$ext_img;
												$old_path=$word_folder."/media/".$value;
												$new_path=$dir_file."../files".$imagenew_name;

												rename($old_path,$new_path);
												
												$rand_inc_number++;
											}
									}
									
								}else{
								  echo "<script>window.alert('Gagal Tambahkan Berkas.');</script>";
											  
								}
								}else{
										echo "<script>window.alert('EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN');</script>";
									}
							  }
							}
                           
							echo "
								<div class='row'>
									
									<div class='col-md-12'>
                                        
                                            <div class='box box-primary'>
                                                <div class='box-header with-border '>
                                                    <h3 class='box-title'>Import Data Master</h3>
                                                    <div class='box-tools pull-right btn-group'>
                                                        <a href='importdatamaster.xls' class='btn btn-sm btn-primary'><i class='fa fa-file-excel-o'></i> Download Format</a>
														<a href='?pg=siswa' class='btn btn-sm btn-primary' title='Batal'><i class='fa fa-times'></i></a>
                                                    </div>
                                                </div><!-- /.box-header -->
                                                <div class='box-body'>
                                                    $info
												<div class='box box-solid'>
												<div class='box-body'>
                                                    <div class='form-group'>
														<div class='row'>
														<div class='col-md-4'>
														<form action='' method='post' enctype='multipart/form-data'>
															<label>Pilih File</label>
															
															<input type='file'  name='file' class='form-control' required='true'/>
															
														</div>
														<div class='col-md-4'>
														<label>&nbsp;</label><br>
															<button type='submit' name='tambah' class='btn btn-primary'><i class='fa fa-check'></i> Import Data</button>
														</div>
														</form>
														</div>
                                                    </div>
													<p>Menu ini berfungsi untuk import data Master</p>
													<p><b>*Import Data Siswa, Jurusan, Kelas, Ruangan,Sesi dan Level</b>
                                                    <p>
                                                        Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Excel 97-2003 Workbook (.xls) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
                                                    </p>
													
												</div>
												</div>
                                                </div><!-- /.box-body -->
                                                <div class='box-footer'>
                                                    
                                                </div>
                                            </div><!-- /.box -->
                                        
                                    </div>
                                </div>
                            ";
                        }
						elseif($pg=='importguru') {
							cek_session_admin();
                            if(isset($_POST['submit'])) {
                                $file = $_FILES['file']['name'];
                                $temp = $_FILES['file']['tmp_name'];
                                $ext = explode('.',$file);
                                $ext = end($ext);
                                if($ext<>'xls') {
                                    $info = info('Gunakan file Ms. Excel 93-2007 Workbook (.xls)','NO');
                                } else {
                                    $data = new Spreadsheet_Excel_Reader($temp);
                                    $hasildata = $data->rowcount($sheet_index=0);
                                    $sukses = $gagal = 0;
									$exec = mysql_query("delete from pengawas where level='guru'");
                                    for ($i=2; $i<=$hasildata; $i++) {
                                        
                                        $nip = $data->val($i,2); 
                                        $nama = $data->val($i,3);
                                        $nama = addslashes($nama);
										$username = $data->val($i,4);
										$username = str_replace("'","",$username);
                                        $password = $data->val($i,5);
										
                                       
										
                                        $exec = mysql_query("INSERT INTO pengawas (nip,nama,username,password,level) VALUES ('$nip','$nama','$username','$password','guru')");
                                        ($exec) ? $sukses++ : $gagal++; 
                                    }
                                    $total = $hasildata-1;
									
                                    $info = info("Berhasil: $sukses | Gagal: $gagal | Dari: $total",'OK');
                                }
                            }
							echo "
								<div class='row'>
									<div class='col-md-3'></div>
									<div class='col-md-6'>
                                        <form action='' method='post' enctype='multipart/form-data'>
                                            <div class='box box-primary'>
                                                <div class='box-header with-border'>
                                                    <h3 class='box-title'>Import Guru</h3>
                                                    <div class='box-tools pull-right btn-group'>
                                                        <button type='submit' name='submit' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Import</button>
														<a href='?pg=guru' class='btn btn-sm btn-default' title='Batal'><i class='fa fa-times'></i></a>
                                                    </div>
                                                </div><!-- /.box-header -->
                                                <div class='box-body'>
                                                    $info
                                                    <div class='form-group'>
                                                        <label>Pilih File</label>
                                                        <input type='file' name='file' class='form-control' required='true'/>
                                                    </div>
                                                    <p>
                                                        Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Excel 97-2003 Workbook (.xls) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
                                                    </p>
                                                </div><!-- /.box-body -->
                                                <div class='box-footer'>
                                                    <a href='importdataguru.xls'><i class='fa fa-file-excel-o'></i> Download Format</a>
                                                </div>
                                            </div><!-- /.box -->
                                        </form>
                                    </div>
                                </div>
                            ";
                        }
						elseif($pg=='pengawas') {
							cek_session_admin();
							echo "
								<div class='row'>
									<div class='col-md-8'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Manajemen User</h3>
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class='table-responsive'>
												<table id='example1' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>NIP</th>
															<th>Nama</th>
															<th>Username</th>
															<th>Level</th>
															<th width=60px></th>
														</tr>
													</thead>
													<tbody>";
													$pengawasQ = mysql_query("SELECT * FROM pengawas where level='admin' ORDER BY nama ASC");
													while($pengawas = mysql_fetch_array($pengawasQ)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$pengawas[nip]</td>
																<td>$pengawas[nama]</td>
																<td>$pengawas[username]</td>
																<td>$pengawas[level]</td>
																<td align='center'>
																<div class='btn-group'>
																	<a href='?pg=$pg&ac=edit&id=$pengawas[id_pengawas]'> <button class='btn btn-xs btn-warning'><i class='fa fa-pencil-square-o'></i></button></a>
																	<a href='?pg=$pg&ac=hapus&id=$pengawas[id_pengawas]'> <button class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></button></a>
																</div>
																</td>
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									<div class='col-md-4'>";
										if($ac=='') {
											if(isset($_POST['submit'])) {
												$nip = $_POST['nip'];
												$nama = $_POST['nama'];
												$nama = str_replace("'","&#39;",$nama);
												$username = $_POST['username'];
												$pass1 = $_POST['pass1'];
												$pass2 = $_POST['pass2'];
												
												$cekuser = mysql_num_rows(mysql_query("SELECT * FROM pengawas WHERE username='$username'"));
												if($cekuser>0) {
													$info = info("Username $username sudah ada!","NO");
												} else {
													if($pass1<>$pass2) {
														$info = info("Password tidak cocok!","NO");
													} else {
														$password = password_hash($pass1,PASSWORD_BCRYPT);
														$exec = mysql_query("INSERT INTO pengawas (nip,nama,username,password,level) VALUES ('$nip','$nama','$username','$password','admin')");
														(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
													}
												}
											}
											echo "
												<form action='' method='post'>
													<div class='box box-primary'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Tambah</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
															<div class='form-group'>
																<label>NIP</label>
																<input type='text' name='nip' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Nama</label>
																<input type='text' name='nama' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Username</label>
																<input type='text' name='username' class='form-control' required='true'/>
															</div>
															
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Password</label>
																		<input type='password' name='pass1' class='form-control' required='true'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Ulang Password</label>
																		<input type='password' name='pass2' class='form-control' required='true'/>
																	</div>
																</div>
															</div>
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}
										if($ac=='edit') {
											$id = $_GET['id'];
											$value = mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE id_pengawas='$id'"));
											if(isset($_POST['submit'])) {
												$nip = $_POST['nip'];
												$nama = $_POST['nama'];
												$nama = str_replace("'","&#39;",$nama);
												$username = $_POST['username'];
												$pass1 = $_POST['pass1'];
												$pass2 = $_POST['pass2'];
												
												if($pass1<>'' AND $pass2<>'') {
													if($pass1<>$pass2) {
														$info = info("Password tidak cocok!","NO");
													} else {
														$password = password_hash($pass1,PASSWORD_BCRYPT);
														$exec = mysql_query("UPDATE pengawas SET nip='$nip',nama='$nama',username='$username',password='$password',level='admin' WHERE id_pengawas='$id'");
														
													}
												} else {
													$exec = mysql_query("UPDATE pengawas SET nip='$nip',nama='$nama',username='$username',level='admin' WHERE id_pengawas='$id'");
												}
												(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
											}
											echo "
												<form action='' method='post'>
													<div class='box box-success'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Edit</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<a href='?pg=$pg' class='btn btn-sm btn-danger' title='Batal'><i class='fa fa-times'></i></a>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
															<div class='form-group'>
																<label>NIP</label>
																<input type='text' name='nip' value='$value[nip]' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Nama</label>
																<input type='text' name='nama' value='$value[nama]' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Username</label>
																<input type='text' name='username' value='$value[username]' class='form-control' required='true'/>
															</div>
															
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Password</label>
																		<input type='password' name='pass1' class='form-control'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Ulang Password</label>
																		<input type='password' name='pass2' class='form-control'/>
																	</div>
																</div>
															</div>
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}
										if($ac=='hapus') {
											$id = $_GET['id'];
											$info = info("Anda yakin akan menghapus pengawas ini?");
											if(isset($_POST['submit'])) {
												$exec = mysql_query("DELETE FROM pengawas WHERE id_pengawas='$id'");
												(!$exec) ? $info = info("Gagal menghapus!","NO") : jump("?pg=$pg");
											}
											echo "
												<form action='' method='post'>
													<div class='box box-danger'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Hapus</h3>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
																<a href='?pg=$pg' class='btn btn-sm btn-default' title='Batal'><i class='fa fa-times'></i></a>
															</div>
														</div><!-- /.box-header -->
														<div class='box-body'>
															$info
														</div><!-- /.box-body -->
													</div><!-- /.box -->
												</form>
											";
										}
										echo "
									</div>
								</div>
							";
						} // modul jurusan
						
						elseif($pg=='pk') {
							if($setting['jenjang']=='SMK'){
							cek_session_admin();
							if(isset($_POST['tambahmapel'])) {
												$idpk = str_replace(' ', '',$_POST['idpk']);
												$nama = $_POST['nama'];
												$cek = mysql_num_rows(mysql_query("SELECT * FROM pk WHERE id_pk='$idpk'"));
												if($cek>0) {
													$info = info("Jurusan dengan kode $idpk sudah ada!","NO");
												} else {
													$exec = mysql_query("INSERT INTO pk (id_pk,program_keahlian) VALUES ('$idpk','$nama')");
													if(!$exec) {
														$info = info("Gagal menyimpan!","NO");
													} else {
														jump("?pg=$pg");
													}
												}
											}
											$info='';
							echo "
								<div class='row'>
									<div class='col-md-12'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Jurusan</h3>
												<div class='box-tools pull-right'>
												<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahmapel'><i class='fa fa-check'></i> Tambah Jurusan</button>
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>
											$info
												<table id='tablejurusan' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>Kode Jurusan</th>
															<th>Nama Jurusan</th>
															
														</tr>
													</thead>
													<tbody>";
													$adminQ = mysql_query("SELECT * FROM pk ORDER BY id_pk ASC");
													while($adm = mysql_fetch_array($adminQ)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$adm[id_pk]</td>
																<td>$adm[program_keahlian]</td>
																
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									
									<div class='modal fade' id='tambahmapel' style='display: none;'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header bg-blue'>
												<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
													<h3 class='modal-title'>Tambah Jurusan</h3>
												</div>
												<div class='modal-body'>
													<form action='' method='post'>
														<div class='form-group'>
															<label>Kode Jurusan</label>
															<input type='text' name='idpk' class='form-control'  required='true'/>
														</div>
														<div class='form-group'>
															<label>Nama Jurusan</label>
															<input type='text' name='nama'  class='form-control' required='true'/>
														</div>
													<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='tambahmapel' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
													</div>
													</form>
												</div>
											</div>					
										</div>											
									</div>
									
									
								</div>
							";
							}
						}
						elseif($pg=='ruang') {
							cek_session_admin();
							include 'master_ruang.php';
							
						}
						elseif($pg=='level') {
							cek_session_admin();
							if(isset($_POST['submit'])) {
												$level = str_replace(' ', '',$_POST['level']);
												$ket = $_POST['keterangan'];
											
												$cek = mysql_num_rows(mysql_query("SELECT * FROM level WHERE kode_level='$level'"));
												if($cek>0) {
													$info = info("Level atau tingkat $level sudah ada!","NO");
												} else {
													$exec = mysql_query("INSERT INTO level (kode_level,keterangan) VALUES ('$level','$ket')");
													if(!$exec) {
														$info = info("Gagal menyimpan!","NO");
													} else {
														jump("?pg=$pg");
													}
												}
											}
							echo "
								<div class='row'>
									<div class='col-md-12'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Level atau Tingkat</h3>
												<div class='box-tools pull-right'>
												<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahlevel'><i class='fa fa-check'></i> Tambah Level</button>
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>
												<table id='tablelevel' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															
															<th >Kode Level</th>
															<th >Nama Level</th>
															
														</tr>
													</thead>
													<tbody>";
													$adminQ = mysql_query("SELECT * FROM level ");
													while($adm = mysql_fetch_array($adminQ)) {
														$no++;
														
														echo "
															<tr>
																<td>$no</td>
																
																<td>$adm[kode_level]</td>
																<td>$adm[keterangan]</td>
																																									
																
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									
									<div class='modal fade' id='tambahlevel' style='display: none;'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header bg-blue'>
												<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
													<h3 class='modal-title'>Tambah Level</h3>
												</div>
												<div class='modal-body'>
													<form action='' method='post'>
														<div class='form-group'>
															<label>Kode Level</label>
															<input type='text' name='level' class='form-control'  required='true'/>
														</div>
														<div class='form-group'>
															<label>Nama Level</label>
															<input type='text' name='keterangan'  class='form-control' required='true'/>
														</div>
													<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
													</div>
													</form>
												</div>
											</div>					
										</div>											
									</div>
									
								</div>
							";
						}
						elseif($pg=='sesi') {
							cek_session_admin();
							if(isset($_POST['submit'])) {
												$sesi = str_replace(' ', '',$_POST['sesi']);
												$nama = $_POST['nama'];
											
												$cek = mysql_num_rows(mysql_query("SELECT * FROM sesi WHERE kode_sesi='$sesi'"));
												if($cek>0) {
													$info = info("Kelompok Test atau Sesi $sesi sudah ada!","NO");
												} else {
													$exec = mysql_query("INSERT INTO sesi (kode_sesi,nama_sesi) VALUES ('$sesi','$nama')");
													if(!$exec) {
														$info = info("Gagal menyimpan!","NO");
													} else {
														jump("?pg=$pg");
													}
												}
											}
							echo "
								<div class='row'>
									<div class='col-md-12'>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Sesi atau Kelompok Test</h3>
												<div class='box-tools pull-right'>
												<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahsesi'><i class='fa fa-check'></i> Tambah Kelompok</button>
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>
												<table id='tablesesi' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															
															<th >Kode Sesi</th>
															<th >Nama Sesi</th>
															
														</tr>
													</thead>
													<tbody>";
													$adminQ = mysql_query("SELECT * FROM sesi ");
													while($adm = mysql_fetch_array($adminQ)) {
														$no++;
														
														echo "
															<tr>
																<td>$no</td>
																
																<td>$adm[kode_sesi]</td>
																<td>$adm[nama_sesi]</td>
																																									
																
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									
									<div class='modal fade' id='tambahsesi' style='display: none;'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header bg-blue'>
												<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
													<h3 class='modal-title'>Tambah Sesi</h3>
												</div>
												<div class='modal-body'>
													<form action='' method='post'>
														<div class='form-group'>
															<label>Kode Sesi</label>
															<input type='text' name='sesi' class='form-control'  required='true'/>
														</div>
														<div class='form-group'>
															<label>Nama Sesi</label>
															<input type='text' name='nama'  class='form-control' required='true'/>
														</div>
													<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
													</div>
													</form>
												</div>
											</div>					
										</div>											
									</div>
									
								</div>
							";
						}
						elseif($pg=='kelas') {
							cek_session_admin();
							if(isset($_POST['submit'])) {
												$idkelas = str_replace(' ', '',$_POST['idkelas']);
												$nama = $_POST['nama'];
												$level = $_POST['level'];
												$cek = mysql_num_rows(mysql_query("SELECT * FROM kelas WHERE id_kelas='$idkelas'"));
												if($cek>0) {
													$info = info("Kelas dengan kode $idkelas sudah ada!","NO");
												} else {
													$exec = mysql_query("INSERT INTO kelas (id_kelas,level,nama) VALUES ('$idkelas','$level','$nama')");
													if(!$exec) {
														$info = info("Gagal menyimpan!","NO");
													} else {
														jump("?pg=$pg");
													}
												}
											}
							echo "
								<div class='row'>
									<div class='col-md-12'>
									<div class='alert alert-warning '>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Level Kelas harus sama dengan Kode Level di data master
													</div>
										<div class='box box-primary'>
											<div class='box-header with-border'>
												<h3 class='box-title'>Kelas</h3>
												<div class='box-tools pull-right'>
												<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahkelas'><i class='fa fa-check'></i> Tambah Kelas</button>
												</div>
											</div><!-- /.box-header -->
											<div class='box-body'>
												<table id='tablekelas' class='table table-bordered table-striped'>
													<thead>
														<tr>
															<th width='5px'>#</th>
															<th>Kode Kelas</th>
															<th>Level Kelas</th>
															<th>Nama Kelas</th>
															
														</tr>
													</thead>
													<tbody>";
													$adminQ = mysql_query("SELECT * FROM kelas ORDER BY nama ASC");
													while($adm = mysql_fetch_array($adminQ)) {
														$no++;
														echo "
															<tr>
																<td>$no</td>
																<td>$adm[id_kelas]</td>
																<td>$adm[level]</td>
																<td>$adm[nama]</td>
																
															</tr>
														";
													}
													echo "
													</tbody>
												</table>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
									<div class='modal fade' id='tambahkelas' style='display: none;'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header bg-blue'>
												<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
													<h3 class='modal-title'>Tambah Kelas</h3>
												</div>
												<div class='modal-body'>
													<form action='' method='post'>
														<div class='form-group'>
															<label>Kode Kelas</label>
															<input type='text' name='idkelas' class='form-control'  required='true'/>
														</div>
														<div class='form-group'>
															<label>Level</label>
																<select name='level' class='form-control' required='true'>
																<option value=''></option>";
																$levelQ = mysql_query("SELECT * FROM level ");
																while($level = mysql_fetch_array($levelQ)) {
																	
																	echo "<option value='$level[kode_level]' >$level[kode_level]</option>";
																}
																echo"
															</select>
														</div>
														<div class='form-group'>
															<label>Nama Kelas</label>
															<input type='text' name='nama'  class='form-control' required='true'/>
														</div>
													<div class='modal-footer'>
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-check'></i> Simpan</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
															</div>
													</div>
													</form>
												</div>
											</div>					
										</div>											
									</div>
									
									
								</div>
							";
						}
						elseif($pg=='banksoal') {
							if($ac==''){
											$pesan='';
											$value = mysql_fetch_array(mysql_query("SELECT * FROM mapel WHERE id_mapel='$id'"));
											$tgl_ujian = explode(' ',$value['tgl_ujian']);
											if(isset($_POST['editbanksoal'])) {
												$id = $_POST['idm'];
												$nama = $_POST['nama'];
												$nama = str_replace("'","&#39;",$nama);
												$idpk = $_POST['id_pk'];
												$jml_soal = $_POST['jml_soal'];
												$jml_esai = $_POST['jml_esai'];
												$bobot_pg = $_POST['bobot_pg'];
												$bobot_esai = $_POST['bobot_esai'];
												$tampil_pg = $_POST['tampil_pg'];
												$tampil_esai = $_POST['tampil_esai'];
												$level =$_POST['level'];
												$status =$_POST['status'];
												$guru=$_POST['guru'];
												$kelas=serialize($_POST['kelas']);
												if($pengawas['level']=='admin'){
												$exec = mysql_query("UPDATE mapel SET idpk='$idpk',nama='$nama',level='$level',jml_soal='$jml_soal',jml_esai='$jml_esai',status='$status',idguru='$guru',bobot_pg='$bobot_pg',bobot_esai='$bobot_esai',tampil_pg='$tampil_pg',tampil_esai='$tampil_esai',kelas='$kelas' WHERE id_mapel='$id'");
						
												(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
												}elseif($pengawas['level']=='guru'){
												$exec = mysql_query("UPDATE mapel SET idpk='$idpk',nama='$nama',level='$level',jml_soal='$jml_soal',jml_esai='$jml_esai',status='$status',bobot_pg='$bobot_pg',bobot_esai='$bobot_esai',tampil_pg='$tampil_pg',tampil_esai='$tampil_esai',kelas='$kelas' WHERE id_mapel='$id'");
												
												(!$exec) ? $info = info("Gagal menyimpan!","NO") : jump("?pg=$pg");
												}
											}
											if(isset($_POST['tambahbanksoal'])) {
												$nama = $_POST['nama'];
												$nama = str_replace("'","&#39;",$nama);
												$id_pk = $_POST['id_pk'];
												$jml_esai = $_POST['jml_esai'];
												$jml_soal = $_POST['jml_soal'];
												$bobot_pg = $_POST['bobot_pg'];
												$bobot_esai = $_POST['bobot_esai'];
												$tampil_pg = $_POST['tampil_pg'];
												$tampil_esai = $_POST['tampil_esai'];
												$level =$_POST['level'];
												$status =$_POST['status'];
												$kelas=serialize($_POST['kelas']);
												
												$cek = mysql_num_rows(mysql_query("SELECT * FROM mapel WHERE nama='$nama' and level='$level' and kelas ='$kelas'"));
												if($pengawas['level']=='admin'){
												$guru=$_POST['guru'];
												if($cek>0) {
													$pesan= "<div class='alert alert-warning alert-dismissible'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Maaf Kode Mapel - Level - Kelas Soal Sudah ada !
													</div>";
												} else {
													$exec = mysql_query("INSERT INTO mapel (idpk, nama, jml_soal,jml_esai,level,status,idguru,bobot_pg,bobot_esai,tampil_pg,tampil_esai,kelas) VALUES ('$id_pk','$nama','$jml_soal','$jml_esai','$level','$status','$guru','$bobot_pg','$bobot_esai','$tampil_pg','$tampil_esai','$kelas')");
													$pesan= "<div class='alert alert-success alert-dismissible'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Data Berhasil ditambahkan ..
													</div>";
												}
												}elseif($pengawas['level']=='guru'){
												if($cek>0) {
													$pesan= "<div class='alert alert-warning alert-dismissible'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Maaf Kode Mapel - Level - Kelas Sudah ada !
													</div>";
												} else {
													$exec = mysql_query("INSERT INTO mapel (idpk, nama, jml_soal,jml_esai,level,status,idguru,bobot_pg,bobot_esai,tampil_pg,tampil_esai,kelas) VALUES ('$id_pk','$nama','$jml_soal','$jml_esai','$level','$status','$id_pengawas','$bobot_pg','$bobot_esai','$tampil_pg','$tampil_esai','$kelas')");
													$pesan= "<div class='alert alert-success alert-dismissible'>
													<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
													<i class='icon fa fa-info'></i>
													Data Berhasil ditambahkan ..
													</div>";
												}
												}
											}
							echo "
								<div class='row'>
									<div class='col-md-12'>$pesan
										<div class='box'>
											<div class='box-header with-border '>
												<h3 class='box-title'><i class='fa fa-briefcase'></i> Data Bank Soal</h3>
												<div class='box-tools pull-right btn-group'>
													<button id='btnhapusbank' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i> Hapus</button>
													<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#tambahbanksoal'><i class='glyphicon glyphicon-plus'></i> Tambah Bank Soal</button>
													
												</div>
									
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div id='tablereset' class='table-responsive'>
												<table id='example1' class='table table-bordered table-striped'>
													<thead>
														<tr><th width='5px'><input type='checkbox' id='ceksemua'  ></th>
															<th width='5px'>No</th>
															<th>Mata Pelajaran</th>
															
															<th>Soal PG</th>
															<th>Soal Esai</th>
															
															
															<th>Kelas</th>
															<th>Guru</th>
															<th >Status</th>
															<th></th>
														</tr>
													</thead>
													<tbody>";
													if($pengawas['level']=='admin'){
													$mapelQ = mysql_query("SELECT * FROM mapel ORDER BY date ASC");
													}elseif($pengawas['level']=='guru'){
													$mapelQ = mysql_query("SELECT * FROM mapel where idguru='$pengawas[id_pengawas]' ORDER BY date ASC");
													}
													
													while($mapel = mysql_fetch_array($mapelQ)) {
														$cek=mysql_num_rows(mysql_query("select * from soal where id_mapel='$mapel[id_mapel]'"));
														//parsing array
														
														$no++;
														echo "
															<tr><td><input type='checkbox' name='cekpilih[]' class='cekpilih' id='cekpilih-$no' value='$mapel[id_mapel]' ></td>
																<td>$no</td>
																<td>
																"; if($mapel['idpk']=='semua'){$jur='Semua';}else{$jur=$mapel['idpk'];} echo "
																<b><small class='label bg-purple'>$mapel[nama]</small></b> 
																<small class='label label-primary'>$mapel[level]</small>
																<small class='label label-primary'>$jur</small>
																</td>";
																
														
														echo"
																
																
																<td><small class='label label-warning'>$mapel[tampil_pg]/$mapel[jml_soal]</small> <small class='label label-danger'>$mapel[bobot_pg] %</small></td>
																<td><small class='label label-warning'>$mapel[tampil_esai]/$mapel[jml_esai]</small> <small class='label label-danger'>$mapel[bobot_esai] %</small></td>
																
																
																<td>"; 
																$dataArray = unserialize($mapel['kelas']);
																foreach ($dataArray as $key => $value) {
																	echo "<small class='label label-success'>$value </small>&nbsp;";
																}
																echo "</td>
																";
																if($cek<>0){
																if($mapel['status']=='0'){
																	$status='<label class="label label-danger">non aktif</label>';
																}else{
																	$status='<label class="label label-success">  aktif  </label>';
																}
																}else{
																	$status='<label class="label label-warning">  Soal Kosong  </label>';
																}
																$guruku=mysql_fetch_array(mysql_query("select*from pengawas where id_pengawas = '$mapel[idguru]'"));
														echo"
																<td><small class='label label-primary'>$guruku[nama]</small></td>
																<td align='center'>$status</td>
																
																<td align='center'>
																	<div class='btn-group'>
																			
																			<a href='?pg=$pg&ac=lihat&id=$mapel[id_mapel]'><button class='btn btn-success btn-xs'><i class='fa fa-search'></i></button></a>
																			<a href='?pg=$pg&ac=importsoal&id=$mapel[id_mapel]'><button class='btn btn-info btn-xs'><i class='fa fa-upload'></i></button></a>
																			<a href='?pg=$pg&ac=duplikat&id=$mapel[id_mapel]'><button class='btn btn-danger btn-xs'><i class='fa fa-copy'></i></button></a>
																			<a ><button class='btn btn-warning btn-xs' data-toggle='modal' data-target='#editbanksoal$mapel[id_mapel]'><i class='fa fa-pencil-square-o'></i></button></a>
																			
																	</div>
																</td>
															</tr>
															
													<div class='modal fade' id='editbanksoal$mapel[id_mapel]' style='display: none;'>
													<div class='modal-dialog'>
													<div class='modal-content'>
													<div class='modal-header bg-blue'>
													<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Edit Bank Soal</h3>
													</div>
													<div class='modal-body'>
													<form action='' method='post'>	
													<input type='hidden' id='idm' name='idm' value='$mapel[id_mapel]'/>
															<div class='form-group'>
																<label>Mata Pelajaran</label>
																<select name='nama' class='form-control' required='true'>
																<option value=''></option>";
																$pkQ = mysql_query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
																while($pk = mysql_fetch_array($pkQ)) {
																	($pk['kode_mapel']==$mapel['nama']) ? $s='selected':$s='';
																	echo "<option value='$pk[kode_mapel]' $s>$pk[nama_mapel]</option>";
																}
																echo"
															</select>
															</div>";
															if($setting['jenjang']=='SMK'){
															echo "
															<div class='form-group'>
															<label>Program Keahlian</label>
															<select name='id_pk' class='form-control' required='true'>
																<option value='semua'>Semua</option>";
																$pkQ = mysql_query("SELECT * FROM pk ORDER BY program_keahlian ASC");
																while($pk = mysql_fetch_array($pkQ)) {
																	($pk['id_pk']==$mapel['idpk']) ? $s='selected':$s='';
																	echo "<option value='$pk[id_pk]' $s>$pk[program_keahlian]</option>";
																}
																echo"
															</select>
															</div>";
															}
															echo "
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-6'>
																<label>Pilih Level</label>
																<select name='level' class='form-control' required='true'>
																<option value='semua'>---</option>
																";
																$lev = mysql_query("SELECT * FROM level");
																while($level = mysql_fetch_array($lev)) {
																	echo "<option value='$level[kode_level]'>$level[kode_level]</option>";
																}
																echo"
																</select>
																</div>
																<div class='col-md-6'>
																<label>Pilih Kelas</label><br>
																<select name='kelas[]' class='form-control select2' multiple='multiple' style='width:100%' required='true'>
																<option value='semua'>---</option>
																";
																$lev = mysql_query("SELECT * FROM kelas  ");
																while($kelas = mysql_fetch_array($lev)) {
																	echo "<option value='$kelas[id_kelas]'>$kelas[id_kelas]</option>";
																}
																echo"
																</select>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-4'>
																<label>Jumlah Soal PG</label>
																<input type='number' name='jml_soal' class='form-control' value='$mapel[jml_soal]' required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Bobot Soal PG %</label>
																<input type='number' name='bobot_pg' class='form-control' value='$mapel[bobot_pg]' required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Soal Tampil</label>
																<input type='number' name='tampil_pg' class='form-control' value='$mapel[tampil_pg]' required='true'/>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-4'>
																<label>Jumlah Soal Essai</label>
																<input type='number' name='jml_esai' class='form-control' value='$mapel[jml_esai]' required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Bobot Soal Essai %</label>
																<input type='number' name='bobot_esai' class='form-control' value='$mapel[bobot_esai]' required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Soal Tampil</label>
																<input type='number' name='tampil_esai' class='form-control' value='$mapel[tampil_esai]' required='true'/>
																</div>
																</div>
															</div>
															
																<div class='form-group'>
																<div class='row'>
															";
															if($pengawas['level']=='admin'){
															echo "
																<div class='col-md-6'>
																<label>Guru Pengampu</label>
																<select name='guru' class='form-control' required='true'>
																";
																$guruku = mysql_query("SELECT * FROM pengawas where level='guru' order by nama asc");
																while($guru = mysql_fetch_array($guruku)) {
																	($guru['id_pengawas']==$mapel['idguru']) ? $s='selected':$s='';
																	echo "<option value='$guru[id_pengawas]' $s>$guru[nama]</option>";
																}
																echo"
																</select>
															</div>";
															}
															echo "
															<div class='col-md-6'>
																<label>Status Soal</label>
																<select name='status' class='form-control' required='true'>
																
																	<option value='1'>Aktif</option>
																	<option value='0'>Non Aktif</option>
																</select>
															</div>	
															</div>
															</div>
													
													</div>
													<div class='modal-footer'>
													<button type='submit' name='editbanksoal' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
												
													</div>
													</form>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
													</div>
														";
													}
													echo "
													</tbody>
												</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
								</div>
								
						";
												
											echo "<div class='modal fade' id='tambahbanksoal' style='display: none;'>
													<div class='modal-dialog'>
													<div class='modal-content'>
													<div class='modal-header bg-blue'>
													<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Tambah Bank Soal</h3>
													</div>
													<div class='modal-body'>
													<form action='' method='post'>	
															<div class='form-group'>
																<label>Mata Pelajaran</label>
																<select name='nama' class='form-control' required='true'>
																<option value=''></option>";
																$pkQ = mysql_query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
																while($pk = mysql_fetch_array($pkQ)) {
																	echo "<option value='$pk[kode_mapel]'>$pk[nama_mapel]</option>";
																}
																echo"
															</select>
															</div>";
															if($setting['jenjang']=='SMK'){
															echo "
															<div class='form-group'>
															<label>Program Keahlian</label>
															<select name='id_pk' class='form-control' required='true'>
																<option value='semua'>Semua</option>";
																$pkQ = mysql_query("SELECT * FROM pk ORDER BY program_keahlian ASC");
																while($pk = mysql_fetch_array($pkQ)) {
																	echo "<option value='$pk[id_pk]'>$pk[program_keahlian]</option>";
																}
																echo"
															</select>
															</div>";
															}
															echo "
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-6'>
																<label>Level Soal</label>
																<select name='level' id='soallevel' class='form-control' required='true'>
																<option value=''></option>
																<!--<option value='semua'>Semua</option>-->
																";
																$lev = mysql_query("SELECT * FROM level");
																while($level = mysql_fetch_array($lev)) {
																	echo "<option value='$level[kode_level]'>$level[kode_level]</option>";
																}
																echo"
																</select>
																</div>
																<div class='col-md-6'>
															
																<label>Pilih Kelas</label><br>
																<select name='kelas[]' id='soalkelas' class='form-control select2' multiple='multiple' style='width:100%' required='true'>
																																
																</select>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-4'>
																<label>Jumlah Soal PG</label>
																<input type='number' id='soalpg' name='jml_soal' class='form-control'  required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Bobot Soal PG %</label>
																<input type='number' name='bobot_pg' class='form-control'  required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Soal Tampil</label>
																<input type='number' id='tampilpg'  name='tampil_pg' class='form-control'  required='true'/>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																<div class='col-md-4'>
																<label>Jumlah Soal Essai</label>
																<input type='number' id='soalesai' name='jml_esai' class='form-control'  required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Bobot Soal Essai %</label>
																<input type='number' name='bobot_esai' class='form-control' required='true'/>
																</div>
																<div class='col-md-4'>
																<label>Soal Tampil</label>
																<input type='number' id='tampilesai' name='tampil_esai' class='form-control'  required='true'/>
																</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
															";
															if($pengawas['level']=='admin'){
															echo "
																
																<div class='col-md-6'>
																<label>Guru Pengampu</label>
																<select name='guru' class='form-control' required='true'>
																";
																$guruku = mysql_query("SELECT * FROM pengawas where level='guru' order by nama asc");
																while($guru = mysql_fetch_array($guruku)) {
																	echo "<option value='$guru[id_pengawas]'>$guru[nama]</option>";
																}
																echo"
																</select>
															</div>";
															}
															echo "
															<div class='col-md-6'>
																<label>Status Soal</label>
																<select name='status' class='form-control' required='true'>
																
																	<option value='1'>Aktif</option>
																	<option value='0'>Non Aktif</option>
																</select>
																</div>
																</div>
															</div>	
													
											</div>
											<div class='modal-footer'>
												<button type='submit' name='tambahbanksoal' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
												
											</div>
											</form>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
											</div>
											";
						
						}
									
							elseif($ac=='input') {
							
								include 'inputsmk.php';
							
							}
							elseif($ac=='hapusbank') {
							
								$exec=mysql_query("delete from soal where id_mapel='$_GET[id]'");
								jump("?pg=$pg&ac=lihat&id=$_GET[id]");
							}
							elseif($ac=='lihat') {
								$id_mapel = $_GET['id'];
								
								if(isset($_REQUEST['tambah']))
								{ 
// MENGHAPUS SOAL KETIKA IMPORT WORD
$SilentKosongSoal = mysql_query("delete from soal where id_mapel='$_GET[id]'");
									$sip = $_SERVER['SERVER_NAME'];
									$smax = mysql_query("select max(qid) as maxi from savsoft_qbank");
									while ($hmax = mysql_fetch_array($smax)) {
									$jumsoal = $hmax['maxi']; }
									$smaop = mysql_query("select max(oid) as maxop from savsoft_options");
									while ($hmaop = mysql_fetch_array($smaop)) {
									$jumop = $hmaop['maxop']; }
									$b_op = $jumop/$jumsoal;
									
									$no=1;
									$sqlcek = mysql_query("select * from savsoft_qbank");
									while($r=mysql_fetch_array($sqlcek)){
									$s_soal = mysql_fetch_array(mysql_query("select * from savsoft_qbank where qid = '$no'"));
									$soal_tanya = $s_soal['question'];
									$l_soal = $s_soal['lid'];
									$c_id = $s_soal['cid'];
									$g_soal = $s_soal['description'];
									$g_soal = str_replace(" ","",$g_soal);
									
									$smin = mysql_query("select min(oid) as mini from savsoft_options where qid = '$no'");
									while ($hmin = mysql_fetch_array($smin)) {
									$min_op = $hmin['mini']; }
									
									$sqlopc = mysql_query("select * from savsoft_options where qid = '$no' and oid = '$min_op'");
									$ropc=mysql_fetch_array($sqlopc);
									$opj1 = $ropc['q_option'];
									$opj1 = str_replace("&ndash;","-",$opj1);
									$opjs1 = $ropc['score'];
									$fileA = $ropc['q_option_match'];
									$fileA = str_replace(" ","",$fileA);
									
									$dele = mysql_query("delete from savsoft_options where qid = '$no' and oid = '$min_op'");
									
									$smin = mysql_query("select min(oid) as mini from savsoft_options where qid = '$no'");
									while ($hmin = mysql_fetch_array($smin)) {
									$min_op = $hmin['mini']; }
									
									$sqlopc = mysql_query("select * from savsoft_options where qid = '$no' and oid = '$min_op'");
									$rubah = mysql_query("select * from savsoft_options where qid = '$no'");
									$ck_jum = mysql_num_rows($rubah);
									
									$ropc=mysql_fetch_array($sqlopc);
									$opj2 = $ropc['q_option'];
									$opj2 = str_replace("&ndash;","-",$opj2);
									$opjs2 = $ropc['score'];
									$fileB = $ropc['q_option_match'];
									$fileB = str_replace(" ","",$fileB);
									
									
									$dele = mysql_query("delete from savsoft_options where qid = '$no' and oid = '$min_op'");
									
									$smin = mysql_query("select min(oid) as mini from savsoft_options where qid = '$no'");
									while ($hmin = mysql_fetch_array($smin)) {
									$min_op = $hmin['mini']; }
									
									$sqlopc = mysql_query("select * from savsoft_options where qid = '$no' and oid = '$min_op'");
									$ropc=mysql_fetch_array($sqlopc);
									$opj3 = $ropc['q_option'];
									$opj3 = str_replace("&ndash;","-",$opj3);
									$opjs3 = $ropc['score'];
									$fileC = $ropc['q_option_match'];
									$fileC = str_replace(" ","",$fileC);
									
									
									$dele = mysql_query("delete from savsoft_options where qid = '$no' and oid = '$min_op'");
									
									$smin = mysql_query("select min(oid) as mini from savsoft_options where qid = '$no'");
									while ($hmin = mysql_fetch_array($smin)) {
									$min_op = $hmin['mini']; }
									
									$sqlopc = mysql_query("select * from savsoft_options where qid = '$no' and oid = '$min_op'");
									$ropc=mysql_fetch_array($sqlopc);
									$opj4 = $ropc['q_option'];
									$opj4 = str_replace("&ndash;","-",$opj4);
									$opjs4 = $ropc['score'];
									$fileD = $ropc['q_option_match'];
									$fileD = str_replace(" ","",$fileD);
									
									$dele = mysql_query("delete from savsoft_options where qid = '$no' and oid = '$min_op'");
									
									$smin = mysql_query("select min(oid) as mini from savsoft_options where qid = '$no'");
									while ($hmin = mysql_fetch_array($smin)) {
									$min_op = $hmin['mini']; }
									
									$sqlopc = mysql_query("select * from savsoft_options where qid = '$no' and oid = '$min_op'");
									$ropc=mysql_fetch_array($sqlopc);
									$opj5 = $ropc['q_option'];
									$opj5 = str_replace("&ndash;","-",$opj5);
									$opjs5 = $ropc['score'];
									$fileE = $ropc['q_option_match'];
									$fileE = str_replace(" ","",$fileE);
									
									
									$dele = mysql_query("delete from savsoft_options where qid = '$no' and oid = '$min_op'");
									
									if($opjs1==1){$kunci="A";}
									if($opjs2==1){$kunci="B";}
									if($opjs3==1){$kunci="C";}
									if($opjs4==1){$kunci="D";}
									if($opjs5==1){$kunci="E";}
									
									if($ck_jum!==0){
									$jns="1";
									}
									if($ck_jum==0){
									$jns="2";
									}
									
									$opj12 = str_replace("&amp;lt;","<",$opj1);
									$opj22 = str_replace("&amp;lt;","<",$opj2);
									$jwb32 = str_replace("&amp;lt;","<",$opj3);
									$jwb42 = str_replace("&amp;lt;","<",$opj4);
									$jwb52 = str_replace("&amp;lt;","<",$opj5);
									
									$soal_tanya2 = str_replace("&amp;lt;","<",$soal_tanya);
							
									$opj1 = str_replace("&amp;gt;",">",$opj12);
									$opj2 = str_replace("&amp;gt;",">",$opj22);
									$opj3 = str_replace("&amp;gt;",">",$jwb32);
									$opj4 = str_replace("&amp;gt;",">",$jwb42);
									$opj5 = str_replace("&amp;gt;",">",$jwb52);
									
									$soal_tanya = str_replace("&amp;gt;",">",$soal_tanya2);
									$exec = mysql_query("INSERT INTO soal (id_mapel,nomor,soal,pilA,pilB,pilC,pilD,pilE,jawaban,jenis,file,file1,fileA,fileB,fileC,fileD,fileE) VALUES ('$id_mapel','$no','$soal_tanya','$opj1','$opj2','$opj3','$opj4','$opj5','$kunci','$jns','$g_soal','$file2','$fileA','$fileB','$fileC','$fileD','$fileE')");
									
									$no++;
									}
									
									$hasil2 = mysql_query("TRUNCATE TABLE savsoft_qbank");
									$hasil2 = mysql_query("TRUNCATE TABLE savsoft_options");
// LOMPAT JIKA SUDAH MENGAMBIL DATA DARI SAVASOFT
jump("?pg=$pg&ac=$ac&id=$id_mapel");									
									}
								
								$namamapel=mysql_fetch_array(mysql_query("select * from mapel where id_mapel='$id_mapel'"));
							if($namamapel['jml_esai']==0){$hide='hidden';}else{$hide='';}
							echo "
								<div class='row'>
									<div class='col-md-12'>
										<div class='box'>
											<div class='box-header with-border '>
												<h3 class='box-title'>Daftar Soal $namamapel[nama]</h3>
												<div class='box-tools pull-right btn-group'>
												
													<!-- <a href='?pg=$pg&ac=input&id=$id_mapel&no=1&jenis=1' class='btn btn-sm btn-primary'><i class='fa fa-plus'></i><span class='hidden-xs'> Tambah</span> PG</a>
													<a href='?pg=$pg&ac=input&id=$id_mapel&no=1&jenis=2' class='btn btn-sm btn-primary $hide'><i class='fa fa-plus'></i><span class='hidden-xs'> Tambah</span> Essai</a> -->
													
													<a class='btn btn-sm btn-primary' href='?pg=$pg&ac=importsoal&id=$id_mapel' title='Upload soal $namamapel[nama] dari template soal'><i class='fa fa-upload'></i><span class='hidden-xs'> Upload</span></a>
													<a class='btn btn-sm btn-primary' href='soal_excel.php?m=$id_mapel' title='Download soal $namamapel[nama] kedalam format excell'><i class='fa fa-download'></i><span class='hidden-xs'> Download</span></a>
													<button class='btn btn-sm btn-primary' title='Cetak soal $namamapel[nama]' onclick=frames['frameresult'].print()><i class='fa fa-print'></i><span class='hidden-xs'> Print</span></button>
<!-- /.Kode Hapus Soal Per Mapel -->
													<a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#hapus$soal[id_mapel]' title='Hapus semua isi soal $namamapel[nama]'><i class='fa fa-trash'></i> Kosongkan</a>												
													<iframe name='frameresult' src='cetaksoal.php?id=$id_mapel' style='border:none;width:1px;height:1px;'></iframe>
													
												</div>
									
											</div><!-- /.box-header -->
											";
											//kode hapus & alert
											$info = info("Anda yakin akan menghapus semua isi soal $namamapel[nama] inie?");
													if(isset($_POST['hapus'])) {
													$exec = mysql_query("delete from soal where id_mapel='$_GET[id]'");
													(!$exec) ? info("Gagal menyimpan","NO") : jump("?pg=$pg&ac=lihat&id=$_GET[id]");
													}
													echo "
													<div class='modal fade' id='hapus$soal[id]' style='display: none;'>
													<div class='modal-dialog'>
													<div class='modal-content'>
													<div class='modal-header bg-red'>
													<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Hapus Soal</h3>
															</div>
													<div class='modal-body'>
													<form action='' method='post'>
													<input type='hidden' id='idu' name='idu' value='$soal[id_soal]'/>
													<div class='callout '>
															<h4>$info</h4>
													</div>
													<div class='modal-footer'>
													<div class='box-tools pull-right btn-group'>
																<button type='submit' name='hapus' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
													</div>	
													</div>
													</form>
													</div>
								
													</div>
														<!-- /.modal-content -->
													</div>
														<!-- /.modal-dialog -->
													</div>
									
											</div><!-- /.box-header -->
											<div class='box-body'>
											<div class='table-responsive'>										
<div class='kiri'>
    <button class='link1 btn btn-sm btn-success' title='Ke Soal Pg' type='button' >Pilgan</button>
	<button class='link2 btn btn-sm btn-success' title='Ke Soal Esai' type='button' >Esai</button>
</div>

											<b id='h1'>A. Soal Pilihan Ganda</b>
												<table  class='table table-bordered table-striped'>
													
													<tbody>";
													
													$soalq = mysql_query("SELECT * FROM soal where id_mapel='$id_mapel' and jenis='1' order by nomor ");
													
													while($soal = mysql_fetch_array($soalq)) {
														
														echo "
															<tr>
																<td style='width:30px'>$soal[nomor]</td>
																<td>";
																if($soal['file']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['file']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[file]' style='max-width:200px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[file]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																		
																}
																if($soal['file1']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['file1']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[file1]' style='max-width:200px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[file1]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																} 
																echo "
																$soal[soal]
																
																
																																<table width=100% border=0>
																<tr>									
																<td width=4px valign=top>A.</td>
																<td width=300px colspan=2 valign=top>"; 
																if($soal['pilA']<>''){ 
																echo "$soal[pilA]<br>";
																}
																if($soal['fileA']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['fileA']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[fileA]' style='max-width:100px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[fileA]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																} 
																echo "																
																</td>									
																</tr>
																
																<tr>
																<td width=4px valign=top>B.</td>
																<td width=300px colspan=2 valign=top>"; 
																if(! $soal['pilB']==""){ 
																echo "$soal[pilB]<br>";
																}
																if($soal['fileB']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['fileB']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[fileB]' style='max-width:100px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[fileB]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																} 
																echo "
																</td>
																</tr>
																
																<td width=4px valign=top>C.</td>
																<td width=300px colspan=2 valign=top>"; 
																if(! $soal['pilC']==""){ 
																echo "$soal[pilC]<br>";
																}
																if($soal['fileC']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['fileC']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[fileC]' style='max-width:100px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[fileC]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																} 
																echo "
																</td>
																</tr>";
																																
																if($setting['jenjang']<>'SD'){
																echo "
																<td width=4px valign=top>D.</td>
																<td width=300px colspan=2 valign=top>"; 
																if(! $soal['pilD']==""){ 
																echo "$soal[pilD]<br>";
																}
																if($soal['fileD']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['fileD']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[fileD]' style='max-width:100px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[fileD]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																} 
																echo "
																
																</td>
																</tr>";
																}
																
																if($setting['jenjang']=='SMK'){
																echo "
																<td width=4px valign=top>E.</td>
																<td width=300px colspan=2 valign=top>"; 
																if(! $soal['pilE']==""){ 
																echo "$soal[pilE]<br>";
																}
																if($soal['fileE']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['fileE']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[fileE]' style='max-width:100px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[fileE]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																} 
																echo "
																</td>
																<td width=30px valign=top>&nbsp;</td>";
																}
																echo "
																</tr>";
																
																echo "															
																<tr>
																<td width=300px colspan=2 valign=top>
																<b>Jawaban: $soal[jawaban]</b> 
																<img src='$homeurl/dist/img/benar.png' height='20px'>
																</td>
																</tr>												
																</table>
																		
																<td style='width:30px'>
																<a href='?pg=$pg&ac=input&id=$id_mapel&no=$soal[nomor]&jenis=1' class='btn btn-sm btn-primary'><i class='fa fa-pencil-square-o'></i></a>
																<br><br>
													<!-- MENGHAPUS PERNOMOR SOAL PG
																<a><button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#hapus$soal[nomor]'><i class='fa fa-trash'></i></button></a>-->
																</td>
																
													";
													//** MENGHAPUS PER NOMOR SOAL PG
													$info = info("Anda yakin akan menghapus soal nomor $soal[nomor]  ?");
													if(isset($_POST['hapus_perSoal'])) {
													$exec = mysql_query("DELETE  FROM soal WHERE id_soal = '$_REQUEST[idu]'");
													(!$exec) ? info("Gagal menyimpan","NO") : jump("?pg=$pg&ac=$ac&id=$id_mapel");
	
													}
													
													echo "
													<div class='modal fade' id='hapus$soal[nomor]' style='display: none;'>
													<div class='modal-dialog'>
													<div class='modal-content'>
													<div class='modal-header bg-red'>
													<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Hapus Soal</h3>
															</div>
													<div class='modal-body'>
													<form action='' method='post'>
													<input type='hidden' id='idu' name='idu' value='$soal[id_soal]'/>
													<div class='callout '>
															<h4>$info</h4>
													</div>
													<div class='modal-footer'>
													<div class='box-tools pull-right btn-group'>
																<button type='submit' name='hapus_perSoal' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
													</div>	
													</div>
													</form>
													</div>
								
													</div>
														<!-- /.modal-content -->
													</div>
														<!-- /.modal-dialog -->
													</div>
														
														";
													}
													echo "
													</tbody>
												</table>
												<b id='h2'>B. Soal Essai</b>
												<table  class='table table-bordered table-striped'>
													
													<tbody>";
													
													$soalq = mysql_query("SELECT * FROM soal where id_mapel='$id_mapel' and jenis='2' order by nomor ");
													
													while($soal = mysql_fetch_array($soalq)) {
														
														echo "
															<tr>
																<td style='width:30px'>$soal[nomor]</td>
																<td>";
																if($soal['file']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['file']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[file]' style='max-width:200px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[file]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																		
																}
																if($soal['file1']<>'') {
																		$audio = array('mp3','wav','ogg','MP3','WAV','OGG');
																		$image = array('jpg','jpeg','png','gif','bmp','JPG','JPEG','PNG','GIF','BMP');
																		$ext = explode(".",$soal['file']);
																		$ext = end($ext);
																		if(in_array($ext,$image)) {
																			echo "
																				
																				<img src='$homeurl/files/$soal[file]' style='max-width:200px;'/>
																			";
																		}
																		elseif(in_array($ext,$audio)) {
																			echo "
																				
																				<audio controls><source src='$homeurl/files/$soal[file]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
																			";
																		} else {
																			echo "<b class='btn-sm bg-orange'><i class='fa fa-warning'></i> Error File gambar/audio tidak didukung, Silahkan perbaiki template soal!</b><br/><br/>";
																		}
																		
																}
																echo "$soal[soal]";
																if(! $soal['pilA']=="")
																{ 
																echo "
																<table width=100% border=0>
																<tr>											
																
																<td width=4px valign=top>A.</td>
																<td width=300px colspan=2 valign=top> $soal[pilA]</td>
										
																<td width=30px valign=top>&nbsp;</td>
																<td width=4px valign=top>C.</td>
																<td width=300px colspan=2 valign=top> $soal[pilC] </td>
											
																<td width=30px valign=top>&nbsp;</td>
																<td width=4px valign=top>E.</td>
																<td width=300px colspan=2 valign=top> $soal[pilE] </td>
																<td width=30px valign=top>&nbsp;</td>
																</tr>
																<tr>
																
																<td width=4px valign=top>B.</td>
																<td width=300px colspan=2 valign=top>$soal[pilB]</td>			
																<td width=30px>&nbsp;</td>
																<td width=4px valign=top>D.</td>
																<td width=300px colspan=2 valign=top>$soal[pilD] </td>	
																</tr>
																</table>
																		";
																}
																echo "
																</td>
																<td style='width:30px'>
																<a href='?pg=$pg&ac=input&id=$id_mapel&no=$soal[nomor]&jenis=2' class='btn btn-sm btn-primary'><i class='fa fa-pencil-square-o'></i></a>
																<br><br>
													<!-- MENGHAPUS PER NOMOR SOAL ESAI
																<a><button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#hapus$soal[nomor]'><i class='fa fa-trash-o'></i></button></a>-->
																</td>
																</tr>
														";
													// MENGHAPUS PERNOMOR SOAL ESAI	
													$info = info("Anda yakin akan menghapus soal nomor $soal[nomor]  ?");
													if(isset($_POST['hapus_perEsai'])) {
													$exec = mysql_query("DELETE  FROM soal WHERE id_soal = '$_REQUEST[idu]'");
													(!$exec) ? info("Gagal menyimpan","NO") : jump("?pg=$pg&ac=$ac&id=$id_mapel");
	
													}	
													echo "
													<div class='modal fade' id='hapus$soal[nomor]' style='display: none;'>
													<div class='modal-dialog'>
													<div class='modal-content'>
													<div class='modal-header bg-red'>
													<button  class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
															<h3 class='modal-title'>Hapus Soal</h3>
															</div>
													<div class='modal-body'>
													<form action='' method='post'>
													<input type='hidden' id='idu' name='idu' value='$soal[id_soal]'/>
													<div class='callout callout-warning'>
															<h4>$info</h4>
													</div>
													<div class='modal-footer'>
													<div class='box-tools pull-right btn-group'>
																<button type='submit' name='hapus_perEsai' class='btn btn-sm btn-danger'><i class='fa fa-trash-o'></i> Hapus</button>
																<button type='button' class='btn btn-default btn-sm pull-left' data-dismiss='modal'>Close</button>
													</div>	
													</div>
													</form>
													</div>
								
													</div>
														<!-- /.modal-content -->
													</div>
														<!-- /.modal-dialog -->
													</div>
														";
													}
													echo "
													</tbody>
													</table>
												</div>
											</div><!-- /.box-body -->
										</div><!-- /.box -->
									</div>
								</div>
								
						";	
								
							}
							elseif($ac=='hapusfile') {
								$jenis=$_GET['jenis'];
								$id = $_GET['id'];
								$file = $_GET['file'];
								$soal = mysql_fetch_array(mysql_query("SELECT * FROM soal WHERE id_soal='$id'"));
								(file_exists("../files/".$soal[$file])) ? unlink("../files/".$soal[$file]):null;
								mysql_query("UPDATE soal SET $file='' WHERE id_soal='$id'");
								jump("?pg=$pg&ac=input&paket=$soal[paket]&id=$soal[id_mapel]&no=$soal[nomor]&jenis=$jenis");
							}
							
                            elseif($ac=='duplikat') {
							$id_mapel = $_GET['id'];
							$mapelQ = mysql_fetch_array(mysql_query("SELECT * FROM mapel where id_mapel='$id_mapel'"));
							$idguru= $mapelQ['idguru'];
							$nama_mapel= $mapelQ['nama'];
							$jlmsoal= $mapelQ['jml_soal'];
							$jlmesai= $mapelQ['jml_esai'];
							$tmplpg= $mapelQ['tampil_pg'];
							$tmplesai= $mapelQ['tampil_esai'];
							$bobotpg= $mapelQ['bobot_pg'];
							$bobotesai= $mapelQ['bobot_esai'];
							$level= $mapelQ['level'];
							$kelas= $mapelQ['kelas'];
							$date= $mapelQ['date'];
							$status= $mapelQ['status'];
							$execmapel = mysql_query("INSERT INTO mapel (idguru,nama,jml_soal,jml_esai,tampil_pg,tampil_esai,bobot_pg,bobot_esai,level,kelas,date,status,statusujian) VALUES ('$idguru','$nama_mapel','$jlmsoal','$jlmesai','$tmplpg','$tmplesai','$bobotpg','$bobotesai','$level','$kelas','$date','$status','0')");
							$mapmax = mysql_query("select max(id_mapel) as maxi from mapel");
							while ($hmapmax = mysql_fetch_array($mapmax)) {
							$maximap = $hmapmax['maxi']; }
							
							$soalmax = mysql_query("select max(nomor) as maxinom from soal where id_mapel='$id_mapel'");
							while ($hsoalmax = mysql_fetch_array($soalmax)) {
							$maxisoal = $hsoalmax['maxinom']; }
							$maxiisoal = $maxisoal+1;
							$minisoal=1;
							while ($minisoal<$maxiisoal) {
							$duplikatQ = mysql_fetch_array(mysql_query("SELECT * FROM soal where id_mapel='$id_mapel' and nomor='$minisoal'"));
							$no= $duplikatQ['nomor'];
							$soal= $duplikatQ['soal'];
							$pilA= $duplikatQ['pilA'];
							$pilB= $duplikatQ['pilB'];
							$pilC= $duplikatQ['pilC'];
							$pilD= $duplikatQ['pilD'];
							$pilE= $duplikatQ['pilE'];
							$jawaban= $duplikatQ['jawaban'];
							$jenis= $duplikatQ['jenis'];
							$file1= $duplikatQ['file'];
							$file2= $duplikatQ['file1'];
							$fileA= $duplikatQ['fileA'];
							$fileB= $duplikatQ['fileB'];
							$fileC= $duplikatQ['fileC'];
							$fileD= $duplikatQ['fileD'];
							$fileE= $duplikatQ['fileE'];
							$execsoal = mysql_query("INSERT INTO soal (id_mapel,nomor,soal,pilA,pilB,pilC,pilD,pilE,jawaban,jenis,file,file1,fileA,fileB, fileC,fileD,fileE) VALUES ('$maximap','$no','$soal','$pilA','$pilB','$pilC','$pilD','$pilE','$jawaban','$jenis','$file1','$file2','$fileA','$fileB','$fileC','$fileD','$fileE')"); 
							$minisoal++;
							}
							jump("?pg=banksoal");
							}

							elseif($ac=='importsoal') {
							$id_mapel = $_GET['id'];
							$mapelQ = mysql_query("SELECT * FROM mapel where id_mapel='$id_mapel'");
							$mapel = mysql_fetch_array($mapelQ);
							$cekmapel=mysql_num_rows($mapelQ);
                            if(isset($_POST['submit'])) {
                                $file = $_FILES['file']['name'];
                                $temp = $_FILES['file']['tmp_name'];
                                $ext = explode('.',$file);
                                $ext = end($ext);
                                if($ext<>'xls') {
                                    $info = info('Gunakan file Ms. Excel 93-2007 Workbook (.xls)','NO');
                                } else {
									
                                    $data = new Spreadsheet_Excel_Reader($temp);
                                    $hasildata = $data->rowcount($sheet_index=0);
                                    $sukses = $gagal = 0;
									$exec=mysql_query("delete from soal where id_mapel='$id_mapel' ");
                                    for ($i=2; $i<=$hasildata; $i++) {
										$no = $data->val($i,1);
                                        $soal = addslashes($data->val($i,2));
                                        $pilA = addslashes($data->val($i,3)); 
                                        $pilB = addslashes($data->val($i,4)); 
                                        $pilC = addslashes($data->val($i,5));
                                        $pilD = addslashes($data->val($i,6));
                                        $pilE = addslashes($data->val($i,7));
                                        $jawaban = $data->val($i,8);
										$jenis = $data->val($i,9);
										$file1 = $data->val($i,10);
										$file2 = $data->val($i,11);
										$fileA = $data->val($i,12);
										$fileB = $data->val($i,13);
										$fileC = $data->val($i,14);
										$fileD = $data->val($i,15);
										$fileE = $data->val($i,16);
                                        $id_mapel = $_POST['id_mapel'];
										
                                       if($soal<>'' and $jenis<>''){
										$exec = mysql_query("INSERT INTO soal (id_mapel,nomor,soal,pilA,pilB,pilC,pilD,pilE,jawaban,jenis,file,file1,fileA,fileB, fileC,fileD,fileE) VALUES ('$id_mapel','$no','$soal','$pilA','$pilB','$pilC','$pilD','$pilE','$jawaban','$jenis','$file1','$file2','$fileA','$fileB','$fileC','$fileD','$fileE')");                                        
										($exec) ? $sukses++ : $gagal++; 
									   }else{
										$gagal++;   
									   }
                                    }
                                    $total = $hasildata-1;
                                    $info = info("Import soal excell Berhasil: $sukses | Gagal: $gagal | Dari: $total",'OK');
									
                                }
                            }
							
							if(isset($_POST['importbee'])) {
                                $file = $_FILES['file']['name'];
                                $temp = $_FILES['file']['tmp_name'];
                                $ext = explode('.',$file);
                                $ext = end($ext);
                                if($ext<>'xls') {
                                    $info = info('Gunakan file Ms. Excel 93-2007 Workbook (.xls)','NO');
                                } else {
									
                                    $data = new Spreadsheet_Excel_Reader($temp);
                                    $hasildata = $data->rowcount($sheet_index=0);
                                    $sukses = $gagal = 0;
									$exec=mysql_query("delete from soal where id_mapel='$id_mapel' ");
                                    for ($i=3; $i<=$hasildata; $i++) {
										$no = $data->val($i,1);
                                        $soal = addslashes($data->val($i,5));
                                        $pilA = addslashes($data->val($i,6)); 
                                        $pilB = addslashes($data->val($i,8)); 
                                        $pilC = addslashes($data->val($i,10));
                                        $pilD = addslashes($data->val($i,12));
                                        $pilE = addslashes($data->val($i,14));
                                        $jawab = $data->val($i,19);
										if($jawab=='1'){
											$jawaban='A';
										}elseif($jawab=='2'){
											$jawaban='B';
										}elseif($jawab=='3'){
											$jawaban='C';
										}elseif($jawab=='4'){
											$jawaban='D';
										}elseif($jawab=='5'){
											$jawaban='E';
										}
										$jenis = $data->val($i,2);
										$file1 = $data->val($i,18);
										$file2 = $data->val($i,17);
										$fileA = $data->val($i,7);
										$fileB = $data->val($i,9);
										$fileC = $data->val($i,11);
										$fileD = $data->val($i,13);
										$fileE = $data->val($i,15);
                                        $id_mapel = $_POST['id_mapel'];
										
                                       if($jenis<>''){
										$exec = mysql_query("INSERT INTO soal (id_mapel,nomor,soal,pilA,pilB,pilC,pilD,pilE,jawaban,jenis,file,file1,fileA,fileB, fileC,fileD,fileE) VALUES ('$id_mapel','$no','$soal','$pilA','$pilB','$pilC','$pilD','$pilE','$jawaban','$jenis','$file1','$file2','$fileA','$fileB','$fileC','$fileD','$fileE')");                                        
										($exec) ? $sukses++ : $gagal++; 
									   }else{
										$gagal++;   
									   }
                                    }
                                    $total = $hasildata-1;
                                    $info = info("Berhasil: $sukses | Gagal: $gagal | Dari: $total",'OK');
									
                                }
                            }
							
							echo "		
								<div class='row'>
								<div class='col-md-12'>
								  <!-- Custom Tabs -->
								  <div class='nav-tabs-custom'>
									<ul class='nav nav-tabs'>
									  <li class='active'><a href='#tab_1' data-toggle='tab' aria-expanded='true'>Import Soal MS-Word</a></li>
									  <li class=''><a href='#tab_2' data-toggle='tab' aria-expanded='false'>Import Soal MS-Excel</a></li>
									  <li class='pull-right'><a href='?pg=$pg' class='btn btn-sg' title='Batal'><i class='fa fa-times'></i></a></li>
									</ul>
									<div class='tab-content'>
									<!-- TAB1 -->
									  <div class='tab-pane active' id='tab_1'>				
											<div class='box box-solid'>
											<form action='$homeurl/admin/pages/word_import/import/index.php/word_import' method='post' enctype='multipart/form-data'>
													<div class='box-body'>
															$info
															<div class='form-group'>
																<label>Mata Pelajaran</label>
																<input type='hidden' name='id_mapel' class='form-control' value='$mapel[id_mapel]'/>
																<input type='text' name='mapel' class='form-control' value='$mapel[nama]' disabled/>
																
															</div>
															<tr><td> <input type='hidden' name='id_bank_soal' value='$_REQUEST[id]'></td></tr>
															<tr><td> <input type='hidden' name='id_lokal' value='$homeurl'></td></tr>
															<tr><td> <input type='hidden' name='cid' value='1'></td></tr>
															<tr><td> <input type='hidden' name='lid' value='2'></td></tr>
															<tr><td> <input type='hidden' name='question_split' value='/Q:[0-9]+\)/'></td></tr>
															<tr><td><input type='hidden' name='description_split' value='/FileQ:/'></td></tr>
															<tr><td><input type='hidden' name='question_gambar' value='/Gambar:/'></td></tr>
															<tr><td><input type='hidden' name='question_video' value='/Video:/'></td></tr>
															<tr><td><input type='hidden' name='question_audio' value='/Audio:/'></td></tr>
															<tr><td><input type='hidden' name='option_split' value='/[A-Z]:\)/'></td></tr>
															<tr><td><input type='hidden' name='option_file' value='/FileO:/'></td></tr>
															<tr><td><input type='hidden' name='correct_split' value='/Kunci:/'></td></tr>
														   
														
														<div class='form-group'>
															<label>Pilih File</label>
															<input type='file' accept='.docx' name='word_file' class='form-control' required='true'/>
															
															
															
														</div>
														<p>
															Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Word (.docx) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
														</p>
														<button type='submit' name='submit' class='btn btn-primary pull-left'><i class='fa fa-check'></i> Import</button>	
													</div><!-- /.box-body -->
													</form>	
													<div class='box-footer'>														
														<a href='$homeurl/admin/pages/word_import/import/sample/sample.docx'>
															<button class='btn btn-sm btn-danger pull-right'><i class='fa fa-file-word-o'></i> Download Contoh Format Template MS-Word</button>
														</a>
													</div>
																					
											</div><!-- /.box -->
																					
									  </div>
									<!-- TAB2 -->
									  <div class='tab-pane' id='tab_2'>
											
												<div class='box box-solid'>
												<form action='' method='post' enctype='multipart/form-data'>
													<div class='box-body'>												
															$info
															<div class='form-group'>
																<label>Mata Pelajaran</label>
																<input type='hidden' name='id_mapel' class='form-control' value='$mapel[id_mapel]'/>
																<input type='text' name='mapel' class='form-control' value='$mapel[nama]' disabled/>															
															</div>                                                     
														<div class='form-group'>
															<label>Pilih File</label>
															<input type='file' accept='.xls' name='file' class='form-control' required='true'/>
														</div>
														<p>
															Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Excel 97-2003 Workbook (.xls) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
														</p>
														
													<button type='submit' name='submit' class='btn btn-primary pull-left'><i class='fa fa-check'></i> Import</button>
													</div><!-- /.box-body -->
													</form>
													<div class='box-footer'>
														<a href='importdatasoal.xls'>
															<button class='btn btn-sm btn-success pull-right'>									
																<i class='fa fa-file-excel-o'></i> Download Contoh Format Template MS-Excel																
															</button></a>														
													</div>													
													
												</div><!-- /.box -->
																					
										
										";
										include 'filesoal.php';
								echo"
									  </div>
									  <!-- TAB3 -->
									  <div class='tab-pane' id='tab_3'>
										
									  </div>
									  <!-- /.tab-pane -->
									</div>
									<!-- /.tab-content -->
								  </div>
								  <!-- nav-tabs-custom -->
								</div>												
																																												
									<!-- START IMPORT BEE <div class='col-md-6'>
                                        <form action='' method='post' enctype='multipart/form-data'>
                                            <div class='box box-solid'>
                                                <div class='box-header  bg-yellow with-border'>
                                                    <h3 class='box-title'>Import Soal Excel (Bee)</h3>
                                                    <div class='box-tools pull-right btn-group'>
                                                        <button type='submit' name='importbee' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Import</button>
														<a href='?pg=$pg' class='btn btn-sm btn-danger' title='Batal'><i class='fa fa-times'></i></a>
                                                    </div>
                                                </div>
                                                <div class='box-body'>
												
														$info
														<div class='form-group'>
															<label>Mata Pelajaran</label>
															<input type='hidden' name='id_mapel' class='form-control' value='$mapel[id_mapel]'/>
															<input type='text' name='mapel' class='form-control' value='$mapel[nama]' disabled/>
															
														</div>
                                                       
													
                                                    <div class='form-group'>
                                                        <label>Pilih File</label>
                                                        <input type='file' name='file' class='form-control' required='true'/>
                                                    </div>
                                                    <p>
                                                        Sebelum meng-import pastikan file yang akan anda import sudah dalam bentuk Ms. Excel 97-2003 Workbook (.xls) dan format penulisan harus sesuai dengan yang telah ditentukan. <br/>
                                                    </p>
                                                </div>
                                                
                                            </div>
                                        </form>
                                    </div> END BEE-->                                
                            ";
							
							echo '</div>';
							}

						} elseif($pg=='editnilai') {
						  include '_editdata.php';
						} elseif($pg=='filesoal') {
						
						} elseif($pg=='anso') {
						  include 'frm_anso.php';

						} elseif($pg=='rekapnilai') {
						  include 'frm_rekapwali.php';
						
						} elseif($pg=='walikelas') {
						  include 'walikelas.php';						
						}
						
						elseif($pg=='editguru') {
						if(isset($_POST['submit'])) {
								$username = $_POST['username'];
								
								$nip = $_POST['nip'];
								$nama = $_POST['nama'];
								$nama = str_replace("'","&#39;",$nama);
								$exec=mysql_query("update pengawas set username='$username', nama='$nama',nip='$nip',password='$_POST[password]' where id_pengawas='$id_pengawas'");
						}		
						if($ac==''){
							$guru=mysql_fetch_array(mysql_query("select * from pengawas where id_pengawas='$pengawas[id_pengawas]'"));
							echo "
								<div class='row'>
                                	<div class='col-md-3'>
                                	<div class='box box-primary'>
                                		<div class='box-body box-profile'>
                                	
                                        <img class='profile-user-img img-responsive img-circle' alt='User profile picture' src='$homeurl/dist/img/avatar-6.png'>
                                		
                                		
                                			<h3 class='profile-username text-center'>$guru[nama]</h3>
                                              
                                			  
                                             
                                		</div>
                                		</div>
                                	</div>
                                	
                                	<div class='col-md-9'>
                            		<div class='nav-tabs-custom'>
                                        <ul class='nav nav-tabs'>
                                          <li class='active'><a aria-expanded='true' href='#detail' data-toggle='tab'><i class='fa fa-user'></i> Detail Profile</a></li>
                            			<!--  <li><a aria-expanded='true' href='#alamat' data-toggle='tab'><i class='fa fa-sign-in'></i> <span class='hidden-xs'>Login History</span></a></li>
                            			  <li><a aria-expanded='true' href='#kesehatan' data-toggle='tab'><i class='fa fa-download'></i> <span class='hidden-xs'>Recent Download</span></a></li>
                            			  -->
                                        </ul>
                                        <div class='tab-content'>
                                          <div class='tab-pane active' id='detail'>
                            						<div class='row margin-bottom'>
													<form action='' method='post'>
                            							<div class='col-sm-12'>
														
                                                      <table class='table table-striped table-bordered'>
                                                      <tbody>
                                                        
                                                        <tr><th scope='row'>Nama Lengkap</th> <td><input class='form-control' name='nama' value='$guru[nama]'/></td></tr>
                                                        <tr><th scope='row'>Nip</th> <td><input class='form-control' name='nip' value='$guru[nip]'/></td></tr>
                                                        <tr><th scope='row'>Username</th> <td><input class='form-control' name='username' value='$guru[username]' /></td></tr>
                                                        <tr><th scope='row'>Password</th> <td><input class='form-control' name='password' value='$guru[password]'/></td></tr>
                                                        
                                                      </tbody>
                                                      </table>
														<button name='submit' class='btn btn-sm btn-primary pull-right'>Perbarui Data </button>
                                                       </div>
                            						   </form>
                            						</div>
                            				</div>
                            				 <div class='tab-pane' id='alamat'>
                            						<div class='row margin-bottom'>
                            						<div class='col-sm-12'>
                                                      <table class='table  table-striped no-margin'>
                                                      <tbody>
                            							
                                                      </tbody>
                                                      </table>
                                                    </div>
                            						</div>
                            				</div>
                            				 <div class='tab-pane' id='kesehatan'>
                            						<div class='row margin-bottom'>
                            						<div class='col-sm-12'>
                                                      <table class='table  table-striped no-margin' >
                                                      <tbody>
                            						
                                                        
                                                      </tbody>
                                                      </table>
                                                    </div>
                            						</div>
                            				</div>
                            				 
                                          
                                        </div>
                                        <!-- /.tab-content -->
                            		</div>
                                </div> <!--row-->";
						}
						
						}
						elseif($pg=='reset') {
							
							$info='';
						echo "
								<div class='row'>
									<div class='col-md-12'>
                                        
                                            <div class='box box-primary'>
                                                <div class='box-header with-border'>
                                                    <h3 class='box-title'>Reset Login Peserta</h3>
                                                    <div class='box-tools pull-right btn-group'>
                                                        <button id='btnresetlogin' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Reset Login</button>
														
                                                    </div>
                                                </div><!-- /.box-header -->
                                                <div class='box-body'>
													$info
													<div id='tablereset' class='table-responsive'>
														<table id='example1' class='table table-bordered table-striped'>
															<thead>
																<tr><th width='5px'><input type='checkbox' id='ceksemua'  ></th>
																	<th width='5px'>#</th>
																	<th>No Peserta</th>
																	<th>Nama Peserta</th>
																	<th>Tanggal Login</th>
																	
																</tr>
															</thead>
															<tbody>";
															$loginQ = mysql_query("SELECT * FROM login ORDER BY date DESC");
															while($login = mysql_fetch_array($loginQ)) {
																$siswa=mysql_fetch_array(mysql_query("select * from siswa where id_siswa='$login[id_siswa]'"));
																$no++;
																echo "
																	<tr><td><input type='checkbox' name='cekpilih[]' class='cekpilih' id='cekpilih-$no' value='$login[id_log]' ></td>
																		<td>$no</td>
																		<td>$siswa[no_peserta]</td>
																		<td>$siswa[nama]</td>
																		<td>$login[date]</td>
																		
																	</tr>
																";
															}
															echo "
															</tbody>
														</table>
													</div>
														
                                                </div><!-- /.box-body -->
                                            </div><!-- /.box -->
                                        
                                    </div>
								</div>";
						}
						elseif($pg=='pengaturan') {
							cek_session_admin();
							$info1 = $info2 = $info3 = $info4 = $pesane = '';
							if(isset($_POST['submit1'])) {
								$alamat = nl2br($_POST['alamat']);
								$header = nl2br($_POST['header']);
								$exec = mysql_query("UPDATE setting SET nama_ujian='$_POST[namaujian]',aplikasi='$_POST[aplikasi]',sekolah='$_POST[sekolah]',kode_sekolah='$_POST[kode]',jenjang='$_POST[jenjang]',kepsek='$_POST[kepsek]',nip='$_POST[nip]',alamat='$alamat',kecamatan='$_POST[kecamatan]',kota='$_POST[kota]',telp='$_POST[telp]',fax='$_POST[fax]',web='$_POST[web]',email='$_POST[email]',header='$header',ip_server='$_POST[ipserver]',waktu='$_POST[waktu]' WHERE id_setting='1'");
								if($exec) {
									$pesane= "<script src='../plugins/jQuery/jquery-3.1.1.min.js'></script>
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
							<h4 class='modal-title'>Perhatian</h4>
						  </div>
						  <div class='modal-body'>
							<p>Berhasil menyimpan pengaturan.</p>
						  </div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Oke</button>
							
						  </div>
						</div>
						<!-- /.modal-content -->
					  </div>
					  <!-- /.modal-dialog -->
					</div>";
									$info1 = info('Berhasil menyimpan pengaturan!','OK');
									if($_FILES['logo']['name']<>'') {
										$logo = $_FILES['logo']['name'];
										$temp = $_FILES['logo']['tmp_name'];
										$ext = explode('.',$logo);
										$ext = end($ext);
										$dest = 'dist/img/logo'.rand(1,100).'.'.$ext;
										$upload = move_uploaded_file($temp,'../'.$dest);
										if($upload) {
											$exec = mysql_query("UPDATE setting SET logo='$dest' WHERE id_setting='1'");
											$info1 = info('Berhasil menyimpan pengaturan!','OK');
											$pesane= "<div class='alert alert-success alert-dismissible'>
											<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
											<i class='icon fa fa-info'></i>
											Berhasil menyimpan pengaturan!
											</div>";
										} else {
											$info1 = info('Gagal menyimpan pengaturan!','NO');
										}
									}
									if($_FILES['logo_header']['name']<>'') {
										$logo = $_FILES['logo_header']['name'];
										$temp = $_FILES['logo_header']['tmp_name'];
										$dest = 'dist/img/logo_header.png';										
										$upload = move_uploaded_file($temp,'../'.$dest);
										if($upload) {
											$exec = mysql_query("UPDATE setting SET logo_header='$dest' WHERE id_setting='1'");
											$info1 = info('Berhasil menyimpan pengaturan!','OK');
										} else {
											$info1 = info('Gagal menyimpan pengaturan!','NO');
										}
									}
									if($_FILES['logo_stempel']['name']<>'') {
										$logo = $_FILES['logo_stempel']['name'];
										$temp = $_FILES['logo_stempel']['tmp_name'];
										$dest = 'dist/img/logo_stempel.png';
										$upload = move_uploaded_file($temp,'../'.$dest);
										if($upload) {
											$exec = mysql_query("UPDATE setting SET logo_stempel='$dest' WHERE id_setting='1'");
											$info1 = info('Berhasil menyimpan pengaturan!','OK');
										} else {
											$info1 = info('Gagal menyimpan pengaturan!','NO');
										}
									}
									if($_FILES['logo_instansi']['name']<>'') {
										$logo = $_FILES['logo_instansi']['name'];
										$temp = $_FILES['logo_instansi']['tmp_name'];
										$dest = 'dist/img/logo_instansi.png';
										$upload = move_uploaded_file($temp,'../'.$dest);
										if($upload) {
											$exec = mysql_query("UPDATE setting SET logo_instansi='$dest' WHERE id_setting='1'");
											$info1 = info('Berhasil menyimpan pengaturan!','OK');
										} else {
											$info1 = info('Gagal menyimpan pengaturan!','NO');
										}
									}
									if($_FILES['background_admin']['name']<>'') {
										$logo = $_FILES['background_admin']['name'];
										$temp = $_FILES['background_admin']['tmp_name'];
										$dest = 'dist/img/background_admin.png';
										$upload = move_uploaded_file($temp,'../'.$dest);
										if($upload) {
											$exec = mysql_query("UPDATE setting SET background_admin='$dest' WHERE id_setting='1'");
											$info1 = info('Berhasil menyimpan pengaturan!','OK');
										} else {
											$info1 = info('Gagal menyimpan pengaturan!','NO');
										}
									}
									if($_FILES['background_siswa']['name']<>'') {
										$logo = $_FILES['background_siswa']['name'];
										$temp = $_FILES['background_siswa']['tmp_name'];
										$dest = 'dist/img/background_siswa.png';
										$upload = move_uploaded_file($temp,'../'.$dest);
										if($upload) {
											$exec = mysql_query("UPDATE setting SET background_siswa='$dest' WHERE id_setting='1'");
											$info1 = info('Berhasil menyimpan pengaturan!','OK');
										} else {
											$info1 = info('Gagal menyimpan pengaturan!','NO');
										}
									}
								} else {
									$info1 = info('Gagal menyimpan pengaturan!','NO');
									$pesane= "<script src='../plugins/jQuery/jquery-3.1.1.min.js'></script>
			<script src='../dist/bootstrap/js/bootstrap.min.js'></script>
			<script >$(document).ready(function () {
				$('#modal-danger').modal('show');
			});</script>
					<div class='modal modal-danger fade' id='modal-danger' >
					  <div class='modal-dialog'>
						<div class='modal-content'>
						  <div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
							  <span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'>Perhatian</h4>
						  </div>
						  <div class='modal-body'>
							<p>Gagal menyimpan pengaturan.</p>
						  </div>
						  <div class='modal-footer'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Oke</button>
							
						  </div>
						</div>
						<!-- /.modal-content -->
					  </div>
					  <!-- /.modal-dialog -->
					</div>";
								}
							}
							
							if(isset($_POST['submit3'])) {								
								$password = $_POST['password'];
                                if(!password_verify($password,$pengawas['password'])) {									
                                    $info4 = "<div class='alert alert-success alert-dismissible'>
											<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
											<i class='icon fa fa-info'></i>
											Password Admin salah!
											</div>";									
                                } else {
                                    if(!empty($_POST['data'])) {
                                        $data = $_POST['data'];
                                        if($data<>'') {
                                            foreach($data as $table) {
                                                if($table<>'pengawas') {
                                                    mysql_query("TRUNCATE $table");
                                                } else {
                                                    mysql_query("DELETE FROM $table WHERE level!='admin'");
                                                }
                                            }
                                            $info4 = info('Data terpilih telah dikosongkan!','OK');
											
                                        }
                                    }
                                }
							}
							
							if(isset($_POST['sync'])) {
							$path="/home/admincbt/ujian2/admin/spon/$_POST[project]";
							chdir($path);
							function execPrint($command) {
								$result=array();
								exec($command, $result);
								foreach ($result as $line) {
									print("<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
											<i class='icon fa fa-info'></i>Database " . $line . "</div>");
								}
							};							
							//exec("git pull https://github.com/tetsunero/Spin.git master");
							//echo "<h3>asdfg</h3>";
							print("<h3>" . execPrint("git pull https://github.com/tetsunero/spon.git master") . "</h3>");
							}
							
							if(isset($_POST['sync2'])) {
							$files = glob("/home/admincbt/ujian2/admin/spon/files/*.*");
								   foreach($files as $file){
								   $file_to_go = str_replace("/home/admincbt/ujian2/admin/spon/files/","/home/admincbt/ujian2/files/",$file);
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
							<h4 class='modal-title'>Perhatian</h4>
						  </div>
						  <div class='modal-body'>
							<p>Data file berhasil di instal</p>
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
							}
							
							if(isset($_POST['sync3'])) {								
							$path="/home/admincbt/ujian2/admin/ujian2/$_POST[project]";
							chdir($path);
							function execPrint($command) {
								$result=array();
								exec($command, $result);
								foreach ($result as $line) {
									
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
							<h4 class='modal-title'>Perhatian! </h4>
						  </div>
						  <div class='modal-body'>
							<p><div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><i class='icon fa fa-info'></i>Software " . $line . "</div>Update Berhasil, silahkan tekan tombol Install update aplikasi</p>
						  </div>
						  <div class='modal-footer'>
							<button name='sync4' class='btn btn-primary' data-dismiss='modal'><i class='fa fa-check'></i> Oke</button>
							
						  </div>
						</div>
						<!-- /.modal-content -->
					  </div>
					  <!-- /.modal-dialog -->
					</div>
											
				";	
								}
							};							
							//exec("git pull https://github.com/tetsunero/Spin.git master");
							//echo "<h3>asdfg</h3>";
							print("<h3>" . execPrint("git pull https://github.com/tetsunero/ujian2.git master") . "</h3>");
						
							}

							if(isset($_POST['sync4'])) {
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
							}							
												
							
							$admin = mysql_fetch_array(mysql_query("SELECT * FROM pengawas WHERE level='admin' AND id_pengawas='1'"));
							$setting = mysql_fetch_array(mysql_query("SELECT * FROM setting WHERE id_setting='1'"));
							$setting['alamat'] = str_replace('<br />','',$setting['alamat']);
							$setting['header'] = str_replace('<br />','',$setting['header']);
 
							echo "
								<div class='row'>
								
								<div class='col-md-12 notif'>$pesane</div>
									<div class='col-md-12'>
										
											<div class='box box-primary'>
												<div class='box-header with-border'>
													<div class='box-body'>												
														<p>Klik Tombol dibawah ini untuk sinkronasi atau update aplikasi, jangan lupa install setelah berhasil download <label class='label label-success'>Jika menggunakan tombol di bawah ini, pastikan komputer Anda terhubung dengan internet</label></p>
													<form class='col-md-6' action='' method='post' enctype='multipart/form-data'>														
														<button name='sync' class='btn btn-primary'><i class='fa fa-download'></i> Sinkron Data</button>
														<button name='sync2' class='btn btn-primary' id='mymodal'><i class='fa fa-database'></i> Install Data</button>
													<!--<button name='sync3' class='btn btn-success'><i class='fa fa-download'></i> Update Aplikasi</button>
														<button name='sync4' class='btn btn-success' id='stall'><i class='fa fa-database'></i> Install Update Aplikasi</button>-->
													</form>	
													<div class='col-md-6'>														
														<button name='sync5' class='btn btn-success' id='sin'><i class='fa fa-download'></i> Update Aplikasi</button>
														<button name='sync4' class='btn btn-success' id='stall'><i class='fa fa-database'></i> Install Update Aplikasi</button>
													</div>
												</div><!-- /.box-body -->
											</div><!-- /.box -->
										</div>
										
											
									</div>
								</div><!-- end row update -->
								$info1
								$info4								
								<div class='row'>
									<div class='col-xs-12'>																						
										<div class='nav-tabs-custom'>
											<ul class='nav nav-tabs'>
												<li class='active'><a href='#tab_a' data-toggle='tab' aria-expanded='true'>Pengaturan Umum</a></li>
												<li class=''><a href='#tab_b' data-toggle='tab' aria-expanded='false'>Gambar</a></li>
												<li class=''><a href='#tab_c' data-toggle='tab' aria-expanded='false'>Kosongkan Data</a></li>															
												<li class=''><a href='#tab_d' data-toggle='tab' aria-expanded='false'>Backup/Restore</a></li>
												<li class='pull-right'><a href='?pg=$pg' class='btn btn-sg' title='Batal'><i class='fa fa-times'></i></a></li>
											</ul>
											<div class='tab-content'>
											<!-- TAB1 -->
											<div class='tab-pane active' id='tab_a'>	
												<form action='' method='post' enctype='multipart/form-data'>
																											
														<div class='box-body'>														
															<div class='form-group'>
																<label>Nama Aplikasi</label>
																<input type='text' name='aplikasi' value='$setting[aplikasi]' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Nama Ujian</label>
																<input type='text' name='namaujian' value='$setting[nama_ujian]' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Nama Sekolah</label>
																		<input type='text' name='sekolah' value='$setting[sekolah]' class='form-control' required='true'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Kode Sekolah</label>
																		<input type='text' name='kode' value='$setting[kode_sekolah]' class='form-control' required='true'/>
																	</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Alamat Server / Ip Server</label>
																		<input type='text' name='ipserver' value='$setting[ip_server]' class='form-control'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Waktu Server</label>
																			<select name='waktu' class='form-control' required='true'>
																			<option value='$setting[waktu]'>$setting[waktu]</option>
																			<option value='Asia/Jakarta'>Asia/Jakarta</option>
																			<option value='Asia/Makassar'>Asia/Makassar</option>
																			<option value='Asia/Jayapura'>Asia/Jayapura</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class='form-group' style='visibility:hidden;'>
																<label>Jenjang</label>
																	<select name='jenjang' class='form-control' required='true'>
																	<option value='SMP'>$setting[jenjang]</option>
																	<option value='SD'>SD/MI</option>
																	<option value='SMP'>SMP/MTS</option>
																	<option value='SMK'>SMK/SMA/MA</option>
																</select>
															</div> 													
															<div class='form-group'>
																<label>Kepala Sekolah</label>
																<input type='text' name='kepsek' value='$setting[kepsek]' class='form-control'/>
															</div>
															<div class='form-group'>
																<label>NIP Kepala Sekolah</label>
																<input type='text' name='nip' value='$setting[nip]' class='form-control'/>
															</div>
															<div class='form-group'>
																<label>Alamat</label>
																<textarea name='alamat' class='form-control' rows='3'>$setting[alamat]</textarea>
															</div>
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Kecamatan</label>
																		<input type='text' name='kecamatan' value='$setting[kecamatan]' class='form-control'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Kota/Kabupaten</label>
																		<input type='text' name='kota' value='$setting[kota]' class='form-control'/>
																	</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Telepon</label>
																		<input type='text' name='telp' value='$setting[telp]' class='form-control'/>
																	</div>
																	<div class='col-md-6'>
																		<label>Fax</label>
																		<input type='text' name='fax' value='$setting[fax]' class='form-control'/>
																	</div>
																</div>
															</div>
															<div class='form-group'>
																<div class='row'>
																	<div class='col-md-6'>
																		<label>Website</label>
																		<input type='text' name='web' value='$setting[web]' class='form-control'/>
																	</div>
																	<div class='col-md-6'>
																		<label>E-mail</label>
																		<input type='text' name='email' value='$setting[email]' class='form-control'/>
																	</div>
																</div>
															</div>
													<!-- LAPORAN -->
															<div class='form-group'>
																<label>Header Laporan</label>
																<textarea name='header' class='form-control' rows='3'>$setting[header]</textarea>
															</div>	
															<div class='box-tools pull-right btn-group'>
																<button type='submit' name='submit1' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
															</div>
														</div>																		
												</div><!-- /.box -->
												
											<!-- TAB1 -->
											<div class='tab-pane' id='tab_b'>	
													<div class='box-tools pull-right btn-group'>
														<button type='submit' name='submit1' class='btn btn-sm btn-primary'><i class='fa fa-check'></i> Simpan</button>
													</div>	
													<div class='row'>
													<div class='col-md-6'>
													   <div class='box box-solid'>
														  <div class='box-body'>
															 <div class='col-md-6'>																		
																<img class='img img-responsive' src='$homeurl/$setting[logo_header]' height='100'/>
															 </div>
															 <div class='col-md-12'>
																<label>Logo Header</label>
																<input type='file' name='logo_header' class='form-control'/>
															 </div>
														  </div>
														  <!-- /.box-body -->
													   </div>
													   <!-- /.box -->
													</div>
													<div class='col-md-6'>
													   <div class='box box-solid'>
														  <div class='box-body'>
															 <div class='col-md-6'>
																<img class='img img-responsive' src='$homeurl/$setting[logo]'height='100'/>
															 </div>
															 <div class='col-md-12'>
																<label>Logo Sekolah / Kanan</label>
																<input type='file' name='logo' class='form-control'/>
															 </div>
														  </div>
														  <!-- /.box-body -->
													   </div>
													   <!-- /.box -->
													</div>
													</div>
													<div class='row'>
													<div class='col-md-6'>
													   <div class='box box-solid'>
														  <div class='box-body'>
															 <div class='col-md-6'>
																<img class='img img-responsive' src='$homeurl/$setting[logo_instansi]'height='100'/>
															 </div>
															 <div class='col-md-12'>
																<label>Logo Instansi / Kiri</label>
																<input type='file' name='logo_instansi' class='form-control'/>
															 </div>
														  </div>
														  <!-- /.box-body -->
													   </div>
													   <!-- /.box -->
													</div>
													<div class='col-md-6'>
													   <div class='box box-solid'>
														  <div class='box-body'>
															 <div class='col-md-6'>
																<img class='img img-responsive' src='$homeurl/$setting[logo_stempel]'height='100'/>
															 </div>
															 <div class='col-md-12'>
																<label>Stempel & Tanda Tangan</label>
																<input type='file' name='logo_stempel' class='form-control'/>
															 </div>
														  </div>
														  <!-- /.box-body -->
													   </div>
													   <!-- /.box -->
													</div>
													</div>
													<div class='row'>
													<div class='col-md-6'>
													   <div class='box box-solid'>
														  <div class='box-body'>
															 <div class='col-md-6'>
																<img class='img img-responsive' src='$homeurl/$setting[background_admin]' height='100'/>
															 </div>
															 <div class='col-md-12'>
																<label>Background Admin</label>
																<input type='file' name='background_admin' class='form-control'/>
															 </div>
														  </div>
														  <!-- /.box-body -->
													   </div>
													   <!-- /.box -->
													</div>
													<div class='col-md-6'>
													   <div class='box box-solid'>
														  <div class='box-body'>
															 <div class='col-md-6'>
																<img class='img img-responsive' src='$homeurl/$setting[background_siswa]'height='100'/>
															 </div>
															 <div class='col-md-12'>
																<label>Background Siswa</label>
																<input type='file' name='background_siswa' class='form-control'/>
															 </div>
														  </div>
														  <!-- /.box-body -->
													   </div>
													   <!-- /.box -->
													</div>
													</div>												
													</form>																		
												</div><!-- /.box -->
												
												<!-- TAB1 -->
												<div class='tab-pane' id='tab_c'>	
													<form action='' method='post'>															
															<div class='box-body'>													
																<div class='form-group'>														
																	<div class='alert alert-warning'>
																		<i class='fa fa-warning'></i> <strong>Mohon di ingat!</strong> Data yang telah dikosongkan tidak dapat dikembalikan.</p>
																	</div>															
																	
																	<label>Pilih Data yang akan dikosongkan</label>														
																	<div class='row'>
																		<div class='col-md-5'>
																			<div class='checkbox'>
																			<small class='label bg-purple'>Pilih Data Hasil Nilai</small><br/>
																				<label><input type='checkbox' name='data[]' value='nilai'/> Data Nilai</label><br/>
																				 <label><input type='checkbox' name='data[]' value='hasil_jawaban'/> Data Jawaban</label><br/>
																				 <label><input type='checkbox' name='data[]' value='jawaban'/> Temp_Jawaban</label><br/>
																																		 
																				<small class='label label-danger'>Pilih Data Master</small><br/>
																				  <label><input type='checkbox' name='data[]' value='siswa'/> Data Siswa</label><br/>
																				 <label><input type='checkbox' name='data[]' value='kelas'/> Data Kelas</label><br/>
																				<label><input type='checkbox' name='data[]' value='mata_pelajaran'/> Data Mata Pelajaran</label><br/>
																				<label><input type='checkbox' name='data[]' value='pk'/> Data Jurusan</label><br/>
																				<label><input type='checkbox' name='data[]' value='level'/> Data Level</label><br/>
																				<label><input type='checkbox' name='data[]' value='ruang'/> Data Ruangan</label><br/>
																				<label><input type='checkbox' name='data[]' value='sesi'/> Data Sesi</label><br/>
																				
																			</div>
																		</div>
																		<div class='col-md-5'>
																			<div class='checkbox'>
																				<small class='label bg-green'>Pilih Data Bank Soal</small><br/>
																				<label><input type='checkbox' name='data[]' value='soal'/> Data Soal</label><br/>                                                              
																				<label><input type='checkbox' name='data[]' value='mapel'/> Data Bank Soal</label><br/>
																				
																				<small class='label bg-blue'>Pilih Data Pengacak Soal</small><br/>
																				<label><input type='checkbox' name='data[]' value='pengacak'/> Pengacak Soal</label><br/>                                                              
																				<label><input type='checkbox' name='data[]' value='pengacakopsi'/> Pengacak Opsi</label><br/>
																			</div>
																		</div>
																	</div>
																</div>
																<div class='form-group'>
																	<label>Password Admin</label>
																	<input type='password' name='password' class='form-control' required='true'/>
																</div>
																<div class='box-tools pull-right btn-group'>													
																	<button type='submit' name='submit3' class='btn btn-danger'><i class='fa fa-trash-o'></i> Kosongkan</button>														
																</div>
															</div><!-- /.box-body -->
														
													</form>																	
												</div><!-- /.box -->
												<!-- TAB1 -->
												<div class='tab-pane' id='tab_d'>	
													<div class='box box-danger'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Backup Data</h3>
															
														</div><!-- /.box-header -->
														<div class='box-body'>
															<p>Klik Tombol dibawah ini untuk membackup database </p>
																<button  id='btnbackup' class='btn btn-success'><i class='fa fa-database'></i> Backup Data</button>
															
															
														</div><!-- /.box-body -->
													</div><!-- /.box -->
													<div class='box box-success'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Restore Data</h3>
															
													</div><!-- /.box-header -->
														<div class='box-body'>
														<form method='post' action='' name='postform' enctype='multipart/form-data' >
															<p>Klik Tombol dibawah ini untuk merestore database </p>
															<div class='col-md-8'>
															<input class='form-control' name='datafile' type='file' accept='.sql'/>
															</div>
																<button name='restore' class='btn btn-primary' id='mymodal'><i class='fa fa-database'></i> Restore Data</button>															
														</form>  
														</div><!-- /.box-body -->
													</div><!-- /.box -->																	
												</div><!-- /.box -->
											</div>																						
									</div> <!--END TAB PENGATURAN -->
									
									
									</div>
								</div>
							";
							if(isset($_POST['restore'])){
								restore($_FILES['datafile']);
								
							}
							else
							{
								unset($_POST['restore']);
							}

						} else {
							echo "
								<div class='error-page'>
									<h2 class='headline text-yellow'> 404</h2>
									<div class='error-content'>
										<br/>
										<h3><i class='fa fa-warning text-yellow'></i> Upss! Halaman tidak ditemukan.</h3>
										<p>
											Halaman yang anda inginkan saat ini tidak tersedia.<br/>
											Silahkan kembali ke <a href='?'><strong>dashboard</strong></a> dan coba lagi.<br/>
											Hubungi petugas <strong><i>Developer</i></strong> jika ini adalah sebuah masalah.
										</p>
									</div><!-- /.error-content -->
								</div><!-- /.error-page -->
							";
						}
						echo "
						</section><!-- /.content -->
					</div><!-- /.content-wrapper -->
				</div><!-- ./wrapper -->
					<footer class='main-footer hidden-xs'>
					<button id='stop' class='scrollTop btn btn-sm btn-danger' title='Munggah notok' type='button' ><i class='fa fa-chevron-up'></i></button>
						<div class='container'>
							<div class='pull-left hidden-xs'>
								<strong>
									<span id='end-sidebar'>
										$setting[sekolah] support by $copyright OPMI Kota Batu V $setting[versi]
									</span>
								</strong>
							</div>
						
					</footer>
				<!-- REQUIRED JS SCRIPTS -->
				
				<script src='$homeurl/plugins/jQuery/jquery-3.1.1.min.js'></script>
				<script src='$homeurl/dist/bootstrap/js/bootstrap.min.js'></script>
				<script src='$homeurl/plugins/fastclick/fastclick.js'></script>
				<script src='$homeurl/dist/js/adminlte.min.js'></script>
				<script src='$homeurl/dist/js/app.min.js'></script>
				<script src='$homeurl/plugins/datetimepicker/build/jquery.datetimepicker.full.min.js'></script>
				
				<script src='$homeurl/plugins/slimScroll/jquery.slimscroll.min.js'></script>
				
				<script src='$homeurl/plugins/datatables/jquery.dataTables.min.js'></script>
				<script src='$homeurl/plugins/datatables/dataTables.bootstrap.min.js'></script>
				<script src='$homeurl/plugins/datatables/extensions/Select/js/dataTables.select.min.js'></script>
				<script src='$homeurl/plugins/datatables/extensions/Select/js/select.bootstrap.min.js'></script>
				<script src='$homeurl/plugins/iCheck/icheck.min.js'></script>
				<script src='$homeurl/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'></script>
				<script src='$homeurl/plugins/select2/select2.min.js'></script>
				<script src='$homeurl/plugins/tableedit/jquery.tabledit.js'></script>
				<script src='$homeurl/plugins/fileinput/js/fileinput.min.js'></script>
				<script src='$homeurl/plugins/notify/js/notify.js'></script>
				<script src='$homeurl/plugins/sweetalert2/dist/sweetalert2.min.js'></script>
				

				
				<script>
				
				$('.loader').fadeOut('slow');				
  					$(function () {    				
   					 $('#textarea').wysihtml5()					 					 					 
  					});
					var autoRefresh = setInterval(
						function () {
							$('#waktu').load('$homeurl/admin/_load.php?pg=waktu');
							
							$('#log-list').load('$homeurl/admin/_load.php?pg=log');
							
							$('#pengumuman').load('$homeurl/admin/_load.php?pg=pengumuman');
							
						}, 1000
					);
					var autoRefresh = setInterval(
						function () {
							$('#divstatus').load('$homeurl/admin/statuspeserta.php');	
						}, 1000
					);
					var autoRefresh = setInterval(
						function () {
							
							$('#isi_token').load('$homeurl/admin/_load.php?pg=token');
						}, 900000
					);
					
					
					$('.datepicker').datetimepicker({
                    timepicker:false,format:'Y-m-d'
					});
					$('.tgl').datetimepicker();
					$('.timer').datetimepicker({
                    datepicker:false,format:'H:i'
					});
					
					$(function () {
											//Add text editor
						
						$('#jenis').change(function(){
						if($('#jenis').val() == '2') {
						$('#jawabanpg').hide();
						$('input:radio[name=jawaban]').attr('disabled',true);
						}else{
						$('#jawabanpg').show();
						$('input:radio[name=jawaban]').attr('disabled',false);
						}
						});
						
					});
					function printkartu(idkelas,judul) {
						$('#loadframe').attr('src','kartu.php?id_kelas='+idkelas);
					}
					function printabsen() {
						var idsesi = $('#sesi option:selected').val();
						var idmapel = $('#mapel option:selected').val();
						var idruang = $('#ruang option:selected').val();
						var idkelas = $('#kelas option:selected').val();
						$('#loadabsen').attr('src','absen.php?id_sesi='+idsesi+'&id_ruang='+idruang+'&id_mapel='+idmapel+'&id_kelas='+idkelas);
					
					}
									
					function printlaporan() {
						var idsesi = $('#sesi option:selected').val();
						var idmapel = $('#mapel option:selected').val();
						var idruang = $('#ruang option:selected').val();
						var idkelas = $('#kelas option:selected').val();
						$('#loadlaporan').attr('src','laporan.php?id_sesi='+idsesi+'&id_ruang='+idruang+'&id_mapel='+idmapel+'&id_kelas='+idkelas);
						
						
					}
					function iCheckform() {
						
							$('input[type=checkbox].flat-check, input[type=radio].flat-check').iCheck({
								 checkboxClass: 'icheckbox_square-green',
									radioClass: 'iradio_square-green',
								increaseArea: '20%' // optional
							});
					}
					
					$(document).ready(function() {
					$('#example1').DataTable({
						select:true
					});
					$('#soalpg').keyup(function(){
						$('#tampilpg').val(this.value);
					});
					$('#soalesai').keyup(function(){
						$('#tampilesai').val(this.value);
					});
					$('#formsoal').submit(function(e) {
						
						 e.preventDefault();
						 var data = new FormData(this);
							$.ajax({
								type: 'POST',
								url: 'simpansoal.php',
								enctype: 'multipart/form-data',
								data: data,
								cache: false,
								contentType: false,
								processData: false,
								beforeSend: function() {
										swal({
											
											  text: 'Proses menyimpan data',
											  timer: 2000,
											  onOpen: () => {
												swal.showLoading()
											  }
										});
									},
								success: function(data) {
									
									swal({
										  position: 'top-end',
										  type: 'success',
										  title: 'Data Berhasil disimpan',
										  showConfirmButton: true
										 
										});
										
								}
							})
							return false;
					 });
					$('.input-id').fileinput({
						allowedFileExtensions: ['jpg', 'png', 'gif','mp3','ogg','wav'],
						showRemove: false,
						showUpload: false,
						showBrowse: false,
						browseOnZoneClick: true,
						
						maxFileSize: 5000,
						uploadUrl: 'upload.php' // your upload server url

						
					}).on('filebatchselected', function(event, files) {
						$('.input-id').fileinput('upload');
					});
									
							$('#ceksemua').change(function() {
								$(this).parents('#tablereset:eq(0)').
								find(':checkbox').attr('checked', this.checked);
							});
						
						$('.idkel').change(function(){
							var thisval = $(this).val();
							var txt_id=$(this).attr('id').replace('me', 'txt');
							var idm = $('#'+txt_id).val();							
							console.log(thisval+idm);
							$('.linknilai').attr('href','?pg=nilai&ac=lihat&idm='+idm+'&idk='+thisval);
						});
					$('.alert-dismissible').fadeTo(2000, 500).slideUp(500, function(){
					$('.alert-dismissible').alert('close');
					});
					$('.select2').select2();
					
					$('input:checkbox[name=masuksemua]').click(function() {
						if ($(this).is(':checked'))
						$('input:radio.absensi').attr('checked', 'checked');
						else
						$('input:radio.absensi').removeAttr('checked');
						});
					iCheckform()
					$('#btnbackup').click(function(){
						
						$('.notif').load('backup.php');	
						console.log('sukses');
			
					});
					
 
					});
				</script>
				<script>
					
						function kirim_form(){
							var homeurl;
							homeurl = '$homeurl';
							var jawab = $('#headerkartu').val();
							$.ajax({
								type:'POST',
								url:'simpanheader.php',
								data:'jawab='+jawab,
								success:function(response) {
									location.reload();
								}
							});
						}	
						
								
				</script>
				
				";?>
		<script>		
			$(function(){
				$("#btnresetlogin").click(function(){
					id_array=new Array()
					i=0;
					$("input.cekpilih:checked").each(function(){
						id_array[i]=$(this).val();
						i++;
					})

					$.ajax({
						url:'resetlogin.php',
						data:"kode="+id_array,
						type:"POST",
						success:function(respon)
						{
							if(respon==1)
							{
								$("input.cekpilih:checked").each(function(){
									$(this).parent().parent().remove('.cekpilih').animate({ opacity: "hide" }, "slow");
								})
							}
						}
					})
					return false;
				})
			})

			$(function(){
				$("#btnhapusbank").click(function(){
					id_array=new Array()
					i=0;
					$("input.cekpilih:checked").each(function(){
						id_array[i]=$(this).val();
						i++;
					})
					
				swal({
							  title: 'Bank Soal Terpilih '+i,
							  text: 'Apakah kamu yakin akan menghapus data bank soal yang sudah dipilih  ini ??',
							  type: 'warning',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Ya, Hapus!'
				}).then((result) => {
					if (result.value) {
					$.ajax({
						url:'hapusbanksoal.php',
						data:"kode="+id_array,
						type:"POST",
						success:function(respon)
						{
							if(respon==1)
							{
								$("input.cekpilih:checked").each(function(){
									$(this).parent().parent().remove('.cekpilih').animate({ opacity: "hide" }, "slow");
								})
							}
						}
					})
					}
				})
					return false;
				})
			})

				$(function(){
				$("#hapusberita").click(function(){
					id_array=new Array()
					i=0;
					$("input.cekpilih:checked").each(function(){
						id_array[i]=$(this).val();
						i++;
					})
					
				swal({
							  title: 'Berita Acara Terpilih '+i,
							  text: 'Apakah kamu yakin akan menghapus data berita acara yang sudah dipilih  ini ??',
							  type: 'warning',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Ya, Hapus!'
				}).then((result) => {
					if (result.value) {
					$.ajax({
						url:'hapusberita.php',
						data:"kode="+id_array,
						type:"POST",
						success:function(respon)
						{
							if(respon==1)
							{
								$("input.cekpilih:checked").each(function(){
									$(this).parent().parent().remove('.cekpilih').animate({ opacity: "hide" }, "slow");
								})
							}
						}
					})
					}
				})
					return false;
				})
			})
			
			$(function(){
				$("#buatberita").click(function(){
					
					
				swal({
							  title: 'Generate Berita Acara',
							  text: 'Pastikan pembuatan jadwal sudah fix ??',
							  type: 'warning',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Ya, Buat!'
				}).then((result) => {
					if (result.value) {
					$.ajax({
						url:'buatberita.php',
						type:"POST",
						beforeSend: function() {
							$('.loader').css('display','block');
						},
						success:function(respon)
						{
							$('.loader').css('display','none');
							location.reload();
						}
					})
					}
				})
					return false;
				})
			})
			$(function(){
				$("#btnhapusjadwal").click(function(){
					id_array=new Array()
					i=0;
					$("input.cekpilih:checked").each(function(){
						id_array[i]=$(this).val();
						i++;
					})

				swal({
							  title: 'Jadwal Terpilih '+i,
							  text: 'Apakah kamu yakin akan menghapus data jadwal yang sudah dipilih  ini ??',
							  type: 'warning',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Ya, Hapus!'
				}).then((result) => {
					if (result.value) {
					$.ajax({
						url:'hapusjadwal.php',
						data:"kode="+id_array,
						type:"POST",
						success:function(respon)
						{
							if(respon==1)
							{
								$("input.cekpilih:checked").each(function(){
									$(this).parent().parent().remove('.cekpilih').animate({ opacity: "hide" }, "slow");
								})
							}
						}
					})
					}
				})
					return false;
				})
			})
            $(document).ready(function() {
					var messages = $('#pesan').notify({
						type: 'messages',
						removeIcon: '<i class="icon icon-remove"></i>'
					});
					$('#formreset').submit(function(e) {
						
						 e.preventDefault();
							$.ajax({
								type: 'POST',
								url: $(this).attr('action'),
								data: $(this).serialize(),
								success: function(data) {
									
									if(data=="ok"){
										messages.show("Reset Login Peserta Berhasil", {
											type: 'success',
											title: 'Berhasil',
											icon: '<i class="icon icon-check-sign"></i>'
										});
									}
									if(data=="pilihdulu"){
										swal({
										  position: 'top-end',
										  type: 'success',
										  title: 'Data Berhasil disimpan',
										  showConfirmButton: true
										 
										});
									}
								}
							})
							return false;
					 });
					 
                var t = $('#tabelsiswa').DataTable( {
                    'ajax': 'datasiswa.php',
                    'order': [[ 1, 'asc' ]],
                    'columns': [
                        { 
                            'data': null,
                            'width': '10px',
                            'sClass': 'text-center'
                        },
                       
						{ 'data': 'no_peserta' },
						{ 'data': 'nama' },
						{ 'data': 'level' },
						{ 'data': 'id_kelas' },
						<?php if($setting['jenjang']=='SMK'){ ?>
						{ 'data': 'idpk' },
						<?php } ?>
						{ 'data': 'sesi' },
						{ 'data': 'ruang' },
						{ 'data': 'username' },
						{ 'data': 'password' },
						<?php if($pengawas['level']=='admin'){ ?>
						{
                            'data': 'id_siswa',
                            'width': '100px',
                            'sClass': 'text-center',
                            'orderable': false,
                            'mRender': function (data) {
                                return '<a class="btn btn-xs bg-yellow" href="?pg=siswa&ac=edit&id='+ data +'"><i class="fa fa-pencil-square-o"></i></a> | \n\
                                <a class="btn btn-xs bg-red" href="?pg=siswa&ac=hapussiswa&id='+ data +'" onclick="javascript:return confirm(\'Anda yakin akan menghapus data ini?\');"><i class="fa fa-trash"></i></a>';
                            }
                        }<?php } ?>
						
                    ]
                } );
				t.on( 'order.dt search.dt', function () {
                    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                } ).draw();
            } );
        </script>
				<script>
						$('#formsiswa').on('submit', function (e) {

						  e.preventDefault();

						  $.ajax({
							type: 'post',
							url: 'importsiswa.php',
							data: new FormData(this),
							processData: false,
							contentType: false,
							cache: false,
           
							beforeSend: function() {
								 $('#progressbox').html('<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div>');
								 $('.progress-bar').animate({width: "30%"}, 100);
							   },
								success:function(response) {
									setTimeout(function(){
										$('.progress-bar').css({width: "100%"});
										setTimeout(function(){
											$('#hasilimport').html(response);
											
										}, 100);
									}, 500);
									
								}
							  });

						});
				
				</script>
				
				<script>
				<?php if($pg=='pk'){ ?>
				$(document).ready(function() {
					$('#tablejurusan').Tabledit({
						url: 'example.php?pg=jurusan',
						restoreButton: false,
						columns: {
							identifier: [1, 'id'],
							editable: [[2, 'namajurusan']]
						}
					});
					
				});
				<?php } ?>
				<?php if($pg=='level'){ ?>
				$(document).ready(function() {
				$('#tablelevel').Tabledit({
						url: 'example.php?pg=level',
						restoreButton: false,
						columns: {
							identifier: [1, 'id'],
							editable: [[2, 'namalevel']]
						}
					});
				});
				<?php } ?>
				<?php if($pg=='kelas'){ ?>
				$(document).ready(function() {
				$('#tablekelas').Tabledit({
						url: 'example.php?pg=kelas',
						restoreButton: false,
						columns: {
							identifier: [1, 'id'],
							editable: [[2, 'level'],[3, 'namakelas']]
						}
					});
				});
				<?php } ?>
				<?php if($pg=='matapelajaran'){ ?>
				$(document).ready(function() {
				$('#tablemapel').Tabledit({
						url: 'example.php?pg=mapel',
						restoreButton: false,
						columns: {
							identifier: [1, 'id'],
							editable: [[2, 'namamapel']]
						}
					});
				});
				<?php } ?>
				<?php if($pg=='ruang'){ ?>
				$(document).ready(function() {
				$('#tableruang').Tabledit({
						url: 'example.php?pg=ruang',
						restoreButton: false,
						columns: {
							identifier: [1, 'id'],
							editable: [[2, 'namaruang']]
						}
					});
				});
				<?php } ?>
				<?php if($pg=='sesi'){ ?>
				$(document).ready(function() {
				$('#tablesesi').Tabledit({
						url: 'example.php?pg=sesi',
						restoreButton: false,
						columns: {
							identifier: [1, 'id'],
							editable: [[2, 'namasesi']]
						}
					});
				});
				<?php } ?>
				</script>
				<script>
				$(document).ready(function(){ // Ketika halaman sudah siap (sudah selesai di load)
				  
				  $("#soallevel").change(function(){ // Ketika user mengganti atau memilih data provinsi
					 // Sembunyikan dulu combobox kota nya
					var level=$(this).val();
					console.log(level);
					$.ajax({
					  type: "POST", // Method pengiriman data bisa dengan GET atau POST
					  url: "datakelas.php",// Isi dengan url/path file php yang dituju
					  data: "level="+level, // data yang akan dikirim ke file yang dituju
					  success: function(response){ // Ketika proses pengiriman berhasil
						
						$("#soalkelas").html(response);
					  }
					});
					});	
										
					$(document).on('click','.ambiljawaban',function(){
						
						var idmapel=$(this).data('id');
						console.log(idmapel);
						swal({
							  title: 'Anda yakin?',
							  text: 'Fungsi ini akan memindahkan data jawaban dari temp_jawaban ke hasil jawaban',
							  type: 'warning',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Ya, Ambil!'
							}).then((result) => {
							  if (result.value) {
								 $.ajax({
									type:'POST',
									url:'ambiljawaban.php',
									data:'id='+idmapel,
									beforeSend: function() {
										swal({
											
											  text: 'Proses memindahkan',
											  timer: 1000,
											  onOpen: () => {
												swal.showLoading()
											  }
										});
									},
									success:function(response) {
										$(this).attr('disabled','disabled');
										swal({
										  position: 'top-end',
										  type: 'success',
										  title: 'Data Berhasil diambil',
										  showConfirmButton: false,
										  timer: 1500
										});
										
									}
								});
								
							  }
							})
					
					});
				});
				</script>
<script>
$(document).ready(function () {
	$(document).on('click', '#sin', function(){
		$.ajax({
			type:'GET',
			url:'www.stackoverflow.com',			
			timeout: 15000,
			success:function(data) {
				swal({
				  title: 'Download update?',
				  text: 'Klik tombol dibawah ini untuk mendownload update aplikasi.',
				  type: 'question',
				  showCancelButton: true,
				  allowOutsideClick: false,
				  confirmButtonColor: '#DD6B55',
				  confirmButtonText: 'Oyi',
				  cancelButtonText: 'Tidak.'
				}).then((result) => {
					  if (result.value) {
						// handle Confirm button click		
											$.ajax({
												type:'POST',
												url:'sin.php',
												
												beforeSend: function() {
													swal({			
														allowOutsideClick: false,
														  text: 'Proses download update',
														  onOpen: () => {
															swal.showLoading()
														  }
													});
												},
												success:function(response) {
													$(this).attr('disabled','disabled');
													swal({
													  position: 'top-end',
													  type: 'success',
													  title: 'Aplikasi Berhasil diupdate, jangan lupa install update',
													  showConfirmButton: false,
													  timer: 2000
													});										
												},
												error: function (xhr, ajaxOptions, thrownError) {
													swal("Error deleting!", "Please try again", "error");
													}
											});			
					} else {
						// result.dismiss can be 'cancel', 'overlay', 'esc' or 'timer'
					}
				});					
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				if (XMLHttpRequest.readyState == 4) {
					// HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
					swal({
						  title: 'Perhatiane!',
						  text: 'VHD Server tidak terhubung dengan internet.',
						  type: 'warning',
						  showCancelButton: false,
						  confirmButtonColor: '#DD6B55',
						  confirmButtonText: 'Oyi',				  
						})
					
				}
				else if (XMLHttpRequest.readyState == 0) {
					// Network error (i.e. connection refused, access denied due to CORS, etc.)
					swal({
						  title: 'Perhatian!',
						  text: 'VHD Server tidak terhubung dengan internet.',
						  type: 'warning',
						  showCancelButton: false,
						  confirmButtonColor: '#DD6B55',
						  confirmButtonText: 'Oyi',				  
						})
				}
				else {
					// something weird is happening
				}
				
			}
		});
				
	});
});

$(document).ready(function () {
	$(document).on('click', '#stall', function(){
		swal({
		  title: 'Install update?',
		  text: 'Klik tombol dibawah ini untuk install update aplikasi.',
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#DD6B55',
		  confirmButtonText: 'Oyi',
		  cancelButtonText: 'Tidak.'
		}).then((result) => {
		  if (result.value) {
			// handle Confirm button click		
								$.ajax({
									type:'POST',
									url:'stall.php',
									
									beforeSend: function() {
										swal({
											allowOutsideClick: false,
											  text: 'Proses install update',
											  onOpen: () => {
												swal.showLoading()
											  }
										});
									},
									success:function(response) {
										$(this).attr('disabled','disabled');
										swal({
										  position: 'top-end',
										  type: 'success',
										  title: 'Aplikasi Berhasil diupdate',
										  showConfirmButton: false,
										  timer: 2000										  
										})										
									}
								});									
		} else {
			// result.dismiss can be 'cancel', 'overlay', 'esc' or 'timer'
		}
		});
	});
});

$(document).ready(function () {
	$(document).on('click', '#tentang', function(){
		swal({
		  title: 'Segera Hadir!',
		  text: 'Fitur ini dalam tahap penyusunan',
		  type: 'info',
		  showCancelButton: false,
		  confirmButtonColor: '#3085d6',
		  confirmButtonText: 'Oke',
		  cancelButtonText: 'Tidak.'
		  
		})
	});
});
</script>
<script> 
// BY KAREN GRIGORYAN

$(document).ready(function() {
  /******************************
      BOTTOM SCROLL TOP BUTTON
   ******************************/

  // declare variable
	var scrollTop = $(".scrollTop");
	var kiri = $(".kiri");
	
$(window).scroll(function() {
    // declare variable
    var topPos = $(this).scrollTop();
	var topPos2 = $(this).scrollTop();

    // if user scrolls down - show scroll to top button
    if (topPos > 100) {
		$(scrollTop).css("visibility", "visible");
    } else {
		$(scrollTop).css("visibility", "hidden");
    }
	if (topPos2 > 100) {
		$(kiri).css("visibility", "visible");
    } else {
		$(kiri).css("visibility", "hidden");
    }

  }); // scroll END

  //Click event to scroll to top
  $(scrollTop).click(function() {
    $('html, body').animate({
      scrollTop: 0
    }, 800);
    return false;

  }); // click() scroll top EMD

  /*************************************
    LEFT MENU SMOOTH SCROLL ANIMATION
   *************************************/
  // declare variable
  var h1 = $("#h1").position();
  var h2 = $("#h2").position();
  var h3 = $("#h3").position();
  
  $('.link1').click(function() {
    $('html, body').animate({
      scrollTop: h1.top
    }, 500);
    return false;

  }); // left menu link2 click() scroll END

  $('.link2').click(function() {
    $('html, body').animate({
      scrollTop: h2.top
    }, 500);
    return false;

  }); // left menu link2 click() scroll END

  $('.link3').click(function() {
    $('html, body').animate({
      scrollTop: h3.top
    }, 500);
    return false;

  }); // left menu link3 click() scroll END

}); // ready() END
</script> 				
			</body>
		</html>
