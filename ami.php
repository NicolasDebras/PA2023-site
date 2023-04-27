<?php
	require_once('entete.php');

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
								<figure class="image-box"><a href="#"><img src="https://avatars.githubusercontent.com/u/72074285?v=4" alt="" title=""></a></figure>
								<div class="lower-box">
									<h3><a href="#"><?php echo htmlspecialchars($friend->username); ?></a></h3>
									<div class="designation">ID: <?php echo htmlspecialchars($friend->id); ?></div>
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
					<form method="post" action="add_friend.php">
						<div class="row clearfix">
							<div class="col-md-12 col-sm-12 form-group">
								<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
								<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
								<input type="text" name="friend_id" placeholder="ID de l'ami" required="">
							</div>

							<div class="col-md-12 col-sm-12 form-group">
								<div class="text-center">
									<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Ajouter un ami</span></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section class="contact-section">
		<div class="auto-container">
			<!--Title-->
			<div class="sec-title centered"><h2>Accepter une demande d'ami</h2><span class="bottom-curve"></span></div>

			<div class="form-box">
				<div class="default-form contact-form">
					<form method="post" action="accept_friend.php">
						<div class="row clearfix">
							<div class="col-md-12 col-sm-12 form-group">
								<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
								<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
								<input type="text" name="request_id" placeholder="ID de la demande" required="">
							</div>

							<div class="col-md-12 col-sm-12 form-group">
								<div class="text-center">
									<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Accepter la demande</span></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

    </div>
</section>

<?php
    require_once('footer.php');
?>
