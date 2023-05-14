<?php
	// on inclu le fichier entete.php
    require_once('includes/entete.php');
    $auth_token = $_COOKIE['auth_token'];
    $party_id = $_GET['party_id'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/party/' . $party_id . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' . $auth_token,
            'Content-Type: application/json'
        ),
    ));
	
    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        $party_data = json_decode($response);
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
        $party_data = null;
    }
    // Ferme cURL
    curl_close($curl);
	
	$joined = isset($_GET['joined']) ? $_GET['joined'] : null;
	$error = isset($_GET['error']) ? $_GET['error'] : null;

	if ($joined === 'true') {
		echo '<div class="alert alert-success" role="alert">Demande acceptée !</div>';
	} elseif ($joined === 'false') {
		echo '<div class="alert alert-danger" role="alert">Erreur ! Petit malin ! Tu ne peux pas accepter deux fois une demande!</div>';
	}
?>

    <section class="page-banner" style="background-image:url(images/heading.jpg);">
        <div class="top-pattern-layer"></div>

        <div class="banner-inner">
            <div class="auto-container">
                <div class="inner-container clearfix">
                    <ul class="bread-crumb clearfix">
                        <li><a href="index.html">Home</a></li>
                        <li><a>Partie</a></li>
                        <li>Infos</li>
                    </ul>
                    <h1>Informations de la partie</h1>
                </div>
            </div>
        </div>
    </section>
	
	<section class="contact-section">
		<div class="about-content">
			<div class="auto-container">
				<div class="row clearfix">
					<!--Text Column-->
					<div class="text-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
							<div class="sec-title"><h2>Informations de la partie</h2><span class="bottom-curve"></span></div>
							<div class="text">
								<p>ID: <?php echo htmlspecialchars($party_data->id); ?></p>
								<p>Titre: <?php echo htmlspecialchars($party_data->title); ?></p>
								<p>Créé par: <?php echo htmlspecialchars($party_data->Founder->username); ?></p>
								<p>Statut: <?php echo $party_data->started ? 'Commencée' : 'Non commencée'; ?></p>
								<p>Date de création: <?php echo date('F Y', strtotime($party_data->created_at)); ?></p>
							</div>
						</div>
					</div>
					<!--Image Column-->
					<div class="image-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
							<div class="image-box"><img src="<?php echo htmlspecialchars($party_data->url_image); ?>" alt="" title=""></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<section class="team-section team-page-section">
		<div class="auto-container">
			<div class="sec-title centered"><h2>Participants acceptés</h2><span class="bottom-curve"></span></div>
			<div class="row clearfix">
				<?php if ($party_data !== null && !empty($party_data->accepting_participants)): ?>
					<?php foreach ($party_data->accepting_participants as $participant): ?>
						<div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style="margin:auto;" data-wow-delay="0ms" data-wow-duration="1500ms">
							<div class="inner-box">
								<figure class="image-box"><a href="#"><img src="<?php echo htmlspecialchars($participant->player->url_image); ?>" alt="" title=""></a></figure>
								<div class="lower-box">
									<h3><a href="#"><?php echo htmlspecialchars($participant->player->username); ?></a></h3>
									<div class="designation">ID: <?php echo htmlspecialchars($participant->player->id); ?></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p class="text-center">Aucun participant accepté trouvé.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	
	<section class="team-section team-page-section">
		<div class="auto-container">
			<div class="sec-title centered"><h2>Participants en attente</h2><span class="bottom-curve"></span></div>
			<div class="row clearfix">
				<?php if ($party_data !== null && !empty($party_data->pending_participants)): ?>
					<?php foreach ($party_data->pending_participants as $participant): ?>
						<div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style="margin:auto;" data-wow-delay="0ms" data-wow-duration="1500ms">
							<div class="inner-box">
								<figure class="image-box"><a href="#"><img src="<?php echo htmlspecialchars($participant->player->url_image); ?>" alt="" title=""></a></figure>
								<div class="lower-box">
									<h3><a href="#"><?php echo htmlspecialchars($participant->player->username); ?></a></h3>
									<div class="designation">ID: <?php echo htmlspecialchars($participant->player->id); ?></div>
									<?php if ($user_id == $party_data->Founder->id): ?>
										<div class="text-center">
											<form method="post" action="controllers/accept_request.php">
												<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
												<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
												<input type="hidden" name="request_id" value="<?php echo htmlspecialchars($participant->id); ?>">
												<input type="hidden" name="party_id" value="<?php echo $party_id; ?>">
												<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Accepter</span></button>
											</form>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p class="text-center">Aucun participant en attente trouvé.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	
<?php
	// on inclu le fichier footer.php
    require_once('includes/footer.php');
?>