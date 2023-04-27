<?php
//Ce code m'a fait suer pour rien...
	// on inclu le fichier entete.php
    require_once('includes/entete.php');

    // Récupérer les cookies pour le token et l'id de l'utilisateur
    $user_id = $_COOKIE['user_id'];
    $auth_token = $_COOKIE['auth_token'];

	$curl = curl_init();
	
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/player/' . $user_id . '/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTPHEADER => array(
		'Authorization: Token ' . $auth_token
	  ),
	  CURLOPT_FOLLOWLOCATION => true,
	));

    // Exécuter la requête et récupérer les données
    $response = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ($http_status == 200) {
		$user_data = json_decode($response);
	} else {
		echo 'Unexpected HTTP status: ' . $http_status;
		$user_info = null;
	}
	
	curl_close($curl);
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
                        <li>Gestion</li>
                    </ul>
                    <h1>Gestion du compte</h1>
                </div>
            </div>
        </div>
    </section>
    <!--End Page Banner-->
	<!--About Section-->
    <section class="about-section">
        <div class="bottom-pattern-layer-dark"></div>

        <div class="about-content">
            <div class="auto-container">
                <div class="row clearfix">
                    <!--Text Column-->
                    <div class="text-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="sec-title"><h2>Informations de l'utilisateur</h2><span class="bottom-curve"></span></div>
                            <div class="text">
                                <p>ID: <?php echo htmlspecialchars($user_data->id); ?></p>
                                <p>Nom d'utilisateur: <?php echo htmlspecialchars($user_data->username); ?></p>
                                <p>Email: <?php echo htmlspecialchars($user_data->email); ?></p>
                                <p>Prénom: <?php echo htmlspecialchars($user_data->first_name); ?></p>
                                <p>Nom de famille: <?php echo htmlspecialchars($user_data->last_name); ?></p>
                                <p>Commentaire: <?php echo $user_data->commentaire ? 'Oui' : 'Non'; ?></p>
                            </div>
                        </div>
                    </div>
                    <!--Image Column-->
                    <div class="image-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="image-box"><img src="<?php echo htmlspecialchars($user_data->url_image); ?>" alt="" title=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php
	// on inclu le fichier footer.php
    require_once('includes/footer.php');
?>