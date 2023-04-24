<?php
	if (isset($_GET['error']) && $_GET['error'] == 1) {
		echo '<script>alert("Mot de passe ou nom d\'utilisateur incorrect.");</script>';
	}
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
                        <li><a>Compte</a></li>
                        <li>Connexion</li>
                    </ul>
                    <h1>Connexion</h1>
                </div>
            </div>
        </div>
    </section>
    <!--End Page Banner-->
	<!--Compte Section-->
    <section class="contact-section">
        <div class="auto-container">
            <!--Title-->
            <div class="sec-title centered"><h2>Connexion</h2><span class="bottom-curve"></span></div>

            <div class="form-box">
                <div class="default-form contact-form">
                    <form method="post" action="back.php" id="contact-form">
                        <div class="row clearfix">                                    
                            <div class="col-md-12 col-sm-12 form-group">
                                <input type="text" name="username" placeholder="username" required="">
                            </div>
                    
                            <div class="col-md-12 col-sm-12 form-group">
                                <input type="text" name="password" placeholder="Mot de passe" required="">
                            </div>

                            <div class="col-md-12 col-sm-12 form-group">
                                <div class="text-center">
                                    <button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Connexion</span></button>
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