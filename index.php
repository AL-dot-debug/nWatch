<?php 

// session 
session_start(); 


// includes 
include_once('functions.php');
include_once('data.processor.php');


// Locale definition 
if(!isset($_COOKIE['nW_locale'])):
	setcookie("nW_locale", 'fr-FR',  time() + (10 * 365 * 24 * 60 * 60) );
endif; 

$locale = ( isset($_COOKIE['nW_locale']) ) ? str_replace('-', '_', $_COOKIE['nW_locale'] ) : 'fr_FR';
setlocale(LC_ALL, $locale);


// "Security"
if(check_security() == 1):

	$warning = true; 

else : 
	
	if(!isset($_SESSION['user']) AND $_GET['page'] != 'login'){
		header('Location:/?page=login'); 
	}
	
endif;

if(!canIWriteHere()):
	$config_error = true; 
endif; 



// Logout 
if(isset($_GET['logout'])):
	session_destroy(); 
	header('Location:/');
	exit(); 
endif; 



?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<title>nWatch - NKN Node Monitoring</title>
		<link rel="stylesheet" href="core/css/bootstrap.min.css" />
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Francois+One&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/r-2.2.7/datatables.min.css"/>
		
		<link rel="stylesheet" href="style.css" />
		
		<link rel="apple-touch-icon" sizes="180x180" href="core/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="core/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="core/favicons/favicon-16x16.png">
		<link rel="manifest" href="core/favicons/site.webmanifest">
		<link rel="mask-icon" href="core/favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">
		
		
	</head>
	
	<body>
		
		<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
			
			<div class="container-fluid">
				
				<a class="navbar-brand" href="/">
					 <img src="core/img/nWatch.svg" alt="" width="30" height="30"> nWatch
				</a>
				
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						
						<li class="nav-item">
							<a class="<?php if(!isset($_GET['page']) OR $_GET['page'] == 'nodes') : echo 'nav-link active'; else : echo 'nav-link'; endif; ?>" aria-current="page" href="?page=nodes">Nodes</a>
						</li>
			
						<li class="nav-item">
							<a class="<?php echo ($_GET['page'] == 'wallets' ? 'nav-link active' : 'nav-link') ?>" href="?page=wallets">Wallets</a>
						</li>
						
						<li class="nav-item">
							<a class="<?php echo ($_GET['page'] == 'settings' ? 'nav-link active' : 'nav-link') ?>" href="?page=settings"><img src="/core/img/settings.svg" height="20" /></a>
						</li>
					
					</ul>
			
					
				</div>
				
			</div>
		</nav>
		
		<?php if(isset($warning)) : ?>
		
		<div class="alert alert-danger text-center" role="alert">
			Your nWatch instance is not secure ! <strong><a href="?page=settings">Please define a password</a></strong>
		</div>
		
		<?php endif; ?>
		
		<?php if(isset($config_error)) : ?>
		
		<div class="alert alert-warning text-center" role="alert">
			Your nWatch instance doesn't have the rights to write in <?= dirname(__FILE__) ?> ! <strong>Please set the correct rights</strong>
		</div>
		
		<?php endif; ?>
		
		
		<?php
		
		if (!isset($_GET['page'])) : 
			$page = 'pages/nodes.page.php';
		else : 
			
			$page = 'pages/'.$_GET['page'].'.page.php';
			
			if(!file_exists($page)) : 
				$page = 'pages/nodes.page.php';
			endif; 
			
		endif; 
		
		include_once($page); 
		
		?>
		
		
		<footer class="container-fluid">
			
			<div class="row copyrights">
				<div class="col-6 col-lg-2 order-2 order-lg-1">
					<p>© <?= date('Y') ?> AL - v2.2.5</p>
				</div>
				<div class="col-12 col-lg-8 order-1 order-lg-2 text-center">
					<p>Do you enjoy nWatch? Feed the dev! Donate to <code>NKNQUttrQxNcY6cT9EmaKBT6ijshV1UZt4x2</code> </p>
				</div>
				<div class="col-6 col-lg-2 order-3 order-lg-3 text-end">
					<p>Made with ♥️ in 🇫🇮</p>
				</div>
			</div>
			
		</footer>

		
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/r-2.2.7/datatables.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
		<script src="core/js/bootstrap.bundle.min.js"></script>
		
		<?php 
		
		if (!isset($_GET['page'])) :
			echo '<script src="core/js/nodes.js"></script>'; 
		else : 
			echo '<script src="core/js/'.$_GET['page'].'.js"></script>'; 
		endif; 
		
		
		?>
		
		
	</body>
</html>