<?php
	// on inclu le fichier entete.php
    require_once('includes/entete.php');
?>

<!--Page Title-->
<section class="page-banner" style="background-image:url(images/heading.jpg);">
    <div class="top-pattern-layer"></div>

    <div class="banner-inner">
        <div class="auto-container">
            <div class="inner-container clearfix">
                <ul class="bread-crumb clearfix">
                    <li><a href="index.php">Home</a></li>
                    <li>Inscription</li>
                </ul>
                <h1>Inscription</h1>
            </div>
        </div>
    </div>
</section>
<!--End Page Banner-->

<section class="contact-section">
    <div class="auto-container">
        <!--Title-->
        <div class="sec-title centered"><h2>S'inscrire</h2><span class="bottom-curve"></span></div>

        <div class="form-box">
            <div class="default-form contact-form">
                <form method="post" action="controllers/register.php" id="signup-form" enctype="multipart/form-data">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 form-group">
                            <input type="text" name="username" placeholder="Nom d'utilisateur" required="">
                        </div>

                        <div class="col-md-12 col-sm-12 form-group">
                            <input type="email" name="email" placeholder="Adresse e-mail" required="">
                        </div>
						
						<div class="col-md-12 col-sm-12 form-group">
                            <input type="password" name="password" placeholder="Mot de passe" required="">
                        </div>

                        <div class="col-md-12 col-sm-12 form-group">
                            <input type="text" name="first_name" placeholder="Prénom" required="">
                        </div>

                        <div class="col-md-12 col-sm-12 form-group">
                            <input type="text" name="last_name" placeholder="Nom de famille" required="">
                        </div>
						
						<div class="col-md-12 col-sm-12 form-group">
								<label for="url_image">Ajouter une photo de profil :</label>
								<input type="file" name="url_image" id="url_image" accept="image/*" required>
						</div>

                        <div class="col-md-12 col-sm-12 form-group">
                            <div class="text-center">
                                <button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">S'inscrire</span></button>
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
    require_once('includes/footer.php');
?>
