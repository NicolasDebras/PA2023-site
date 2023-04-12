<!DOCTYPE html>
<html lang="fr">
<head>
<?php
//Pour le cookie
include 'back.php';
?>

<meta charset="utf-8">
<title>Projet Annuel 2023</title>
<!-- Stylesheets -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<!-- Responsive File -->
<link href="css/responsive.css" rel="stylesheet">



<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
<link rel="icon" href="images/favicon.png" type="image/x-icon">

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
                        <div class="top-text">Bienvenue sur notre site</div>
                    </div>
   
                </div>
            </div>

            <!-- Header Upper -->
            <div class="header-upper">
                <div class="inner-container clearfix">
                    <!--Logo-->
                    <div class="logo-box">
                        <div class="logo"><a href="index.html" title="Sintix - Digital Video Gaming and Consol HTML Template"><img src="images/logo.png" alt="Sintix - Digital Video Gaming and Consol HTML Template" title="Sintix - Digital Video Gaming and Consol HTML Template"></a></div>
                    </div>

                    <!--Nav Box-->
                    <div class="nav-outer clearfix">
                        <!--Mobile Navigation Toggler-->
                        <div class="mobile-nav-toggler"><span class="icon flaticon-menu-2"></span></div>

                        <!-- Main Menu -->
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li><a href="index.php">Home</a>
                                    </li>
									<li class="dropdown"><a href="about.html">A propos</a>
                                        <ul>
                                            <li><a href="about.html">A propos de nous</a></li>
                                            <li><a href="team.html">Notre équipe</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown"><a href="#">Jeux</a>
                                        <ul>
                                            <li><a href="#">Tous les jeux</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="gallery.html">Gallery</a></li>
                                    <li class="dropdown"><a>Compte</a>
									    <ul>
                                            <li><a href="compte.php">Connexion</a></li>
                                            <li><a href="inscription.php">Inscription</a></li>
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
                    <a href="index.html" title=""><img src="images/sticky-logo.png" alt="" title=""></a>
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
                <div class="nav-logo"><a href="index.html"><img src="images/logo.png" alt="" title=""></a></div>
                <div class="menu-outer"></div>
            </nav>
        </div><!-- End Mobile Menu -->
    </header>
    <!-- End Main Header -->