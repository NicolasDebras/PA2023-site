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
                                    <li><a href="index.php">Accueil</a>
                                    </li>
									<li class="dropdown"><a href="about.php">A propos</a>
                                        <ul>
                                            <li><a href="about.php">A propos de nous</a></li>
                                            <li><a href="team.php">Notre équipe</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="partie.php">Partie</a>
                                    </li>
                                    <li><a href="gallery.php">Gallery</a></li>
									<li class="dropdown"><a>Compte</a>
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