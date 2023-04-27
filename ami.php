<?php
	// on inclu le fichier entete.php
    require_once('includes/entete.php');

	$auth_token = $_COOKIE['auth_token'];
	$user_id = $_COOKIE['user_id'];

	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/player/' . $user_id . '/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTPHEADER => array(
		'Authorization: Token ' . $auth_token
	  ),
	  CURLOPT_FOLLOWLOCATION => true,
	));

	$response = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ($http_status == 200) {
		$user_info = json_decode($response);
	} else {
		echo 'Unexpected HTTP status: ' . $http_status;
		$user_info = null;
	}

	curl_close($curl);
	
	$message = '';
	if (isset($_GET['message']) && $_GET['message'] == 'error') {
		$status = isset($_GET['status']) ? $_GET['status'] : '';
		if ($status == '404') {
			$message = "Utilisateur introuvable.";
		} else {
			$message = "Une erreur s'est produite (Code : " . $status . "). Veuillez réessayer.";
		}
	}

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
                        <li>Amis</li>
                    </ul>
                    <h1>Mes amis</h1>
                </div>
            </div>
        </div>
    </section>
    <!--End Page Banner-->

	<!--Page Content-->
	<section class="team-section team-page-section">
		<div class="auto-container">
			<div class="row clearfix">
				<?php if ($user_info !== null && !empty($user_info->friends)): ?>
					<?php foreach ($user_info->friends as $friend): ?>
						<div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style="margin:auto;" data-wow-delay="0ms" data-wow-duration="1500ms">
							<div class="inner-box">
								<figure class="image-box"><a href="#"><img src="<?php echo htmlspecialchars($friend->url_image); ?>" alt="" title=""></a></figure>
								<div class="lower-box">
									<h3><a href="#"><?php echo htmlspecialchars($friend->username); ?></a></h3>
									<div class="designation">ID: <?php echo htmlspecialchars($friend->player_id); ?></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p class="text-center">Aucun ami trouvé.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	
	<section class="contact-section">
		<div class="auto-container">
			<!--Title-->
			<div class="sec-title centered"><h2>Ajouter un ami</h2><span class="bottom-curve"></span></div>

			<div class="form-box">
				<div class="default-form contact-form">
					<form method="post" action="controllers/add_friend.php">
						<div class="row clearfix">
							<div class="col-md-12 col-sm-12 form-group">
								<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
								<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
								<input type="text" name="friend_username" placeholder="Pseudo de l'ami" required="">
							</div>

							<div class="col-md-12 col-sm-12 form-group">
								<div class="text-center">
									<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Ajouter cet ami</span></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section class="team-section team-page-section">
		<div class="auto-container">
			<div class="sec-title centered"><h2>Invitations</h2><span class="bottom-curve"></span></div>
			<div class="row clearfix">
				<?php if ($user_info !== null && !empty($user_info->invit)): ?>
					<?php foreach ($user_info->invit as $invitation): ?>
						<?php if ($invitation->player_id != $user_id): ?>
							<div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style="margin:auto;" data-wow-delay="0ms" data-wow-duration="1500ms">
								<div class="inner-box">
									<figure class="image-box"><a href="#"><img src="https://avatars.githubusercontent.com/u/72074285?v=4" alt="" title=""></a></figure>
									<div class="lower-box">
										<h3><a href="#"><?php echo htmlspecialchars($invitation->username); ?></a></h3>
										<div class="designation">ID: <?php echo htmlspecialchars($invitation->player_id); ?></div>
										<div class="text-center">
											<form method="post" action="controllers/accept_friend.php">
												<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
												<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
												<input type="hidden" name="request_id" value="<?php echo htmlspecialchars($invitation->asc_id); ?>">
												<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Accepter</span></button>
											</form>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else: ?>
					<p class="text-center">Aucune invitation trouvée.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

    </div>
</section>

<?php
	// on inclu le fichier footer.php
    require_once('includes/footer.php');
?>
