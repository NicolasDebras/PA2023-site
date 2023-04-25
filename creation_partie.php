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
                        <li>Créer une partie</li>
                    </ul>
                    <h1>Créer une partie</h1>
                </div>
            </div>
        </div>
    </section>
    <!--End Page Banner-->
	<!--Créer une partie Section-->
	<section class="create-game-section">
		<div class="auto-container">
			<!--Title-->
			<div class="sec-title centered"><h2>Créer une partie</h2><span class="bottom-curve"></span></div>

			<div class="form-box">
				<div class="default-form create-game-form">
					<form method="post" action="create_game.php" id="create-game-form" enctype="multipart/form-data">
						<div class="row clearfix">
							<div class="col-md-12 col-sm-12 form-group">
								<input type="text" name="title" placeholder="Titre de la partie" required="">
							</div>

							<div class="col-md-12 col-sm-12 form-group">
								<label for="url_image">Ajouter une image :</label>
								<input type="file" name="url_image" id="url_image" accept="image/*" required>
							</div>

							<!-- Si nécessaire, ajoutez des champs pour d'autres informations, par exemple la date de début, le nombre de participants, etc. -->

							<div class="col-md-12 col-sm-12 form-group">
								<div class="text-center">
									<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Créer une partie</span></button>
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