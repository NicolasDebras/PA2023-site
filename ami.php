<?php
	// on inclu le fichier entete.php
    require_once('entete.php');
?>
    <!--Page Title-->
    <section class="page-banner" style="background-image:url(images/heading.jpg);">
        <div class="top-pattern-layer"></div>

        <div class="banner-inner">
            <div class="auto-container">
                <div class="inner-container clearfix">
                    <ul class="bread-crumb clearfix">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="index.html">Compte</a></li>
                        <li>Ami</li>
                    </ul>
                    <h1>Mes amis</h1>
                </div>
            </div>
        </div>
    </section>
    <!--End Page Banner-->

    <!--Team Section-->
    <section class="team-section team-page-section">

        <div class="auto-container">

            <div class="row clearfix">
                <!--Team Block-->
                <div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style= "margin:auto;" data-wow-delay="0ms" data-wow-duration="1500ms">
                    <div class="inner-box">
                        <figure class="image-box"><a href="#"><img src="https://avatars.githubusercontent.com/u/72074285?v=4" alt="" title=""></a></figure>
                        <div class="lower-box">
                            <h3><a href="#">Bastien LEUWERS</a></h3>
                            <div class="designation">Développeur</div>
                            <div class="social-links">
                                <ul class="default-social-links clearfix">
                                    <li><a href="https://github.com/CapDRAKE"><span class="fab fa-github"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Team Block-->
                <div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style= "margin:auto;" data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="inner-box">
                        <figure class="image-box"><a href="#"><img src="https://avatars.githubusercontent.com/u/47598797?v=4" alt="" title=""></a></figure>
                        <div class="lower-box">
                            <h3><a href="#">Nicolas DEBRAS</a></h3>
                            <div class="designation">Developeur</div>
                            <div class="social-links">
                                <ul class="default-social-links clearfix">
                                    <li><a href="https://github.com/NicolasDebras"><span class="fab fa-github"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Team Block-->
                <div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style= "margin:auto;" data-wow-delay="400ms" data-wow-duration="1500ms">
                    <div class="inner-box">
                        <figure class="image-box"><a href="#"><img src="https://avatars.githubusercontent.com/u/79420105?v=4" alt="" title=""></a></figure>
                        <div class="lower-box">
                            <h3><a href="#">Noura BOUAISSA</a></h3>
                            <div class="designation">Developeur</div>
                            <div class="social-links">
                                <ul class="default-social-links clearfix">
                                    <li><a href="https://github.com/nourabouaissa"><span class="fab fa-github"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
				

            </div>
            
        </div>
        
    </section>
	
	<section class="contact-section">
        <div class="auto-container">
            <!--Title-->
            <div class="sec-title centered"><h2>Ajouter un ami</h2><span class="bottom-curve"></span></div>

            <div class="form-box">
                <div class="default-form contact-form">
                    <form method="post" action="back.php" id="contact-form">
                        <div class="row clearfix">                                    
                            <div class="col-md-12 col-sm-12 form-group">
                                <input type="text" name="username" placeholder="username" required="">
                            </div>

                            <div class="col-md-12 col-sm-12 form-group">
                                <div class="text-center">
                                    <button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Ajouter</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php
	// on inclu le fichier footer.php
    require_once('footer.php');
?>