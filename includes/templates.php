<?php

header('Content-Type:text/html; charset=UTF-8');
ini_set('default_charset', 'UTF-8');
$page = '';
function commonHead() { 
?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<meta http-equiv="x-ua-compatible" content="ie=edge">
			<title><?php echo SITE_TITLE; ?></title>
			<!-- Font Awesome -->
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
			<!-- Bootstrap core CSS -->
			<link href="<?php echo STYLE_PATH; ?>bootstrap.min.css" rel="stylesheet">
			<link href="<?php echo STYLE_PATH; ?>bootstrap-datepicker.css" rel="stylesheet">
			
			<!-- Material Design Bootstrap -->
			<link href="<?php echo STYLE_PATH; ?>mdb.min.css" rel="stylesheet">
			<!-- Your custom styles (optional) -->
			<link href="<?php echo STYLE_PATH; ?>style.css" rel="stylesheet">
		</head>
<?php } 
	function top_header(){
	?>
		<body>
			<!--Main Navigation-->
			<header>

				<!-- Navbar -->
				<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
					<div class="container">            

						<!-- Collapse -->
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
							aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>

						<div class="collapse navbar-collapse" id="navbarSupportedContent">
							
							<!-- Logo -->
							<a class="navbar-brand waves-effect" href="https://mdbootstrap.com/docs/jquery/" target="_blank">
							<strong class="blue-text">MDB</strong>
							</a>
							 <!-- Logo -->

							<!-- Menu -->
							<ul class="navbar-nav">
								<li>
									<a href="#" title="">Home</a>
								</li>
								<li>
									<a href="#" title="">Magazines</a>
								</li>
								<li>
									<a href="#" title="">Events</a>
								</li>
								<li>
									<a href="#" title="">Songs</a>
								</li>
								<li>
									<a href="#" title="">Bible reference</a>
								</li>
								<li>
									<a href="#" title="">Tracts</a>
								</li>
								<li>
									<a href="#" title="">Index</a>
								</li>
								<li >
									<a  href="#" title="">Contact us</a>
								</li>
							</ul>
							<!-- Menu -->

							<!--Language-->
							<div class="lang">
								<select class="browser-default custom-select">
									<option selected>English</option>
									<option value="1">Us English</option>                            
								</select>
							</div>
							
							<!--Language-->
						</div>
					</div>
				</nav>
				<!-- Navbar -->

			</header>	
	<?php
	}

	function commonFooter() { ?>
		<!--Footer-->
			<footer class="page-footer text-center  wow fadeIn">
					<ul class="footer-nav">
						<li>
							<a href="#" title="">Home</a>
						</li>
						<li>
							<a href="#" title="">Magazines</a>
						</li>
						<li>
							<a href="#" title="">Events</a>
						</li>
						<li>
							<a href="#" title="">Songs</a>
						</li>
						<li>
							<a href="#" title="">Bible reference</a>
						</li>
						<li>
							<a href="#" title="">Tracts</a>
						</li>
						<li>
							<a href="#" title="">Index</a>
						</li>
						<li >
							<a  href="#" title="">Contact us</a>
						</li>
					</ul>
					<span>Millennial Bible truth Movement @ <?php echo date('Y'); ?></span>
			</footer>
			<!--/.Footer-->			
		</body>
		<!-- SCRIPTS -->
		<!-- JQuery -->
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>jquery-3.3.1.min.js"></script>
		<!-- Bootstrap tooltips -->
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>popper.min.js"></script>
		<!-- Bootstrap core JavaScript -->
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>bootstrap.min.js"></script>
		<!-- MDB core JavaScript -->
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>mdb.min.js"></script>
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>jquery-infiniteScroll.js"></script>
		<script type="text/javascript" src="<?php echo SCRIPT_PATH; ?>customScripts.js"></script>
		<!-- Initializations -->
		<script type="text/javascript">
			// Animations initialization
			new WOW().init();
		</script>
		</html>
	
	<?php /* don't delete it */
	if ($_SERVER['REMOTE_ADDR'] == 'localhost') {
		global $GLOBAL_REQUESTS_QUERIES;
		if(isset($_GET['echo']) && $_GET['echo']!='') {
			echo "<pre>";  print_r($GLOBAL_REQUESTS_QUERIES);  echo "</pre>";
		}
	}} 

/*********
  * Function Name: Notification Message
  * Purpose: To display notifications like (Insert/update/Delete/Status change)
  * Paramters :
  *			Need to Notification Session code
  * Output : Returns notification mgs block in table format.
  ********/
function displayNotification($prefix = ''){
global $notification_msg_class;
global $notification_msg;
	if(isset($_SESSION['notification_msg_code'])	&&	$_SESSION['notification_msg_code']!=''){ 
		$msgCode	=	$_SESSION['notification_msg_code'];
		if( isset($notification_msg_class[$msgCode])	&&	isset($notification_msg[$msgCode]) ){ ?>
			<div class="<?php  echo $notification_msg_class[$msgCode];  ?> w50"><span style="display:block;"><i class="fa fa-lg"></i>&nbsp;&nbsp;<?php echo $prefix.'&nbsp;'.$notification_msg[$msgCode];  ?></span></div>
<?php 	}
		unset($_SESSION['notification_msg_code']);
	}
}
?>