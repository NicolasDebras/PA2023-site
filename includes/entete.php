<!DOCTYPE html>
<html lang="fr">
<head>
<?php
//Pour le cookie
include 'controllers/back.php';
?>

<meta charset="utf-8">
<title>Projet Annuel 2023</title>
<!-- Stylesheets -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<!-- Responsive File -->
<link href="css/responsive.css" rel="stylesheet">



<link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
<link rel="icon" href="images/logo.png" type="image/x-icon">

<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<script src="js/chat.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.tooltip:not(.shown) {
	display: none;
}

.tooltip.shown {
	opacity: 1;
}
</style>


</head>

<body>

<div class="page-wrapper">
    <!-- Preloader -->
    <div class="preloader"><div class="icon"></div></div>

    <!-- Main Header -->
    <header class="main-header header-style-one">
        <div class="header-container">
            <!--Header Background Shape-->
            <div class="bg-shape-box"><div class="bg-shape"></div></div>

            <!-- Header Top -->
            <div class="header-top">
				<div class="inner clearfix">
                    <div class="top-left">
                        <div class="top-text">Bienvenue sur Masseney !</div>
                    </div>
   
                </div>
            </div>

            <!-- Header Upper -->
            <div class="header-upper">
                <div class="inner-container clearfix">
                    <!--Logo-->
                    <div class="logo-box">
						<div class="logo"><a href="index.php"><img src="images/logo.png" width="130" height="130"></a></div>

                    </div>

                    <!--Nav Box-->
                    <div class="nav-outer clearfix">
                        <!--Mobile Navigation Toggler-->
                        <div class="mobile-nav-toggler"><span class="icon flaticon-menu-2"></span></div>

                        <!-- Main Menu -->
						<nav class="main-menu navbar-expand-md navbar-light">
							<div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
								<ul class="navigation clearfix">
									<li><a href="index.php">Accueil</a></li>
									<li class="dropdown"><a >A propos</a>
										<ul>
											<li><a>A propos de nous</a></li>
											<li><a href="team.php">Notre équipe</a></li>
										</ul>
									</li>
									<li><a href="partie.php">Partie</a></li>
									<?php
									// Si l'utilisateur est connecté
									if (isset($_COOKIE['auth_token']) && !empty($_COOKIE['auth_token'])) {
										$username = 'Compte';
										$profile_picture = 'default_picture.jpg'; // replace this with default picture URL
										if (isset($_COOKIE['username']) && !empty($_COOKIE['username'])) {
											$username = $_COOKIE['username'];
											$profile_picture = $_COOKIE['url_image'];
										} else {
											$user_id = $_COOKIE['user_id'];
											$auth_token = $_COOKIE['auth_token'];

											$curl = curl_init();

											curl_setopt_array($curl, array(
												CURLOPT_URL => 'https://nicolasdebras.fr/api/player/' . $user_id . '/',
												CURLOPT_RETURNTRANSFER => true,
												CURLOPT_HTTPHEADER => array(
													'Authorization: Token ' . $auth_token
												),
												CURLOPT_FOLLOWLOCATION => true,
											));

											$response = curl_exec($curl);
											$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

											if ($http_status == 200) {
												$user_data = json_decode($response);
												$username = $user_data->username;
												$profile_picture = $user_data->url_image; 
												setcookie('username', $username, time() + 86400 * 30, '/');
												setcookie('url_image', $profile_picture, time() + 86400 * 30, '/'); // 86400 = 1 day
											}

											curl_close($curl);
										}
										echo '<li class="dropdown"><a><img src="'.$profile_picture.'" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 5px;">' . $username . '</a>';
									} else {
										echo '<li class="dropdown"><a>Compte</a>';
									}
									?>
									<ul>
										<?php
										// Si l'utilisateur est connecté
										if (isset($_COOKIE['auth_token']) && !empty($_COOKIE['auth_token'])) {
										?>
											<li><a href="gestion.php">Gestion du compte</a></li>
											<li><a href="creation_partie.php">Créer une partie</a></li>
											<li><a href="parties_user.php">Mes parties</a></li>
											<li><a href="ami.php">Ami</a></li>
											<li><a href="controllers/deconnexion.php">Déconnexion</a></li>
										<?php
										} else {
										?>
											<li><a href="compte.php">Connexion</a></li>
											<li><a href="inscription.php">Inscription</a></li>
										<?php
										}
										?>
									</ul>
								</li>
							</ul>
						</div>
						</nav>




                        <!-- Main Menu End-->
                        
                    </div>
                </div>
            </div>
            <!--End Header Upper-->
        </div><!--End Header Container-->

        <!-- Sticky Header  -->
        <div class="sticky-header">
            <div class="auto-container clearfix">
                <!--Logo-->
                <div class="logo pull-left">
                    <a href="index.php" title=""><img src="images/logo.png" width="50" height="50"></a>
                </div>
                <!--Right Col-->
                <div class="pull-right">
                    <!-- Main Menu -->
                    <nav class="main-menu clearfix">
                    </nav><!-- Main Menu End-->
                </div>
            </div>
        </div><!-- End Sticky Menu -->

        <!-- Mobile Menu  -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><span class="icon flaticon-cancel"></span></div>
            
            <nav class="menu-box">
                <div class="nav-logo"><a href="index.php"><img src="images/logo.png" width="50" height="50"></a></div>
                <div class="menu-outer"></div>
            </nav>
        </div><!-- End Mobile Menu -->
		<style>
			.alert {
				margin-top: 180px;
				z-index: 999;
			}
		</style>

    </header>
    <!-- End Main Header -->