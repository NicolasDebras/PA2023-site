<?php
	// on inclu le fichier entete.php
    require_once('includes/entete.php');

    // Récupère le numéro de page depuis l'URL, ou utilise la valeur par défaut 1
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Récupère les parties depuis l'API
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/party/?page=' . $current_page,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
	echo $response;
	print curl_error($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        $parties = json_decode($response);
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
        $parties = [];
    }

    // Ferme cURL
    curl_close($curl);
	
	$joined = isset($_GET['joined']) ? $_GET['joined'] : null;
	$error = isset($_GET['error']) ? $_GET['error'] : null;

	if ($joined === 'true') {
		echo '<div class="alert alert-success" role="alert">Vous avez rejoint la partie</div>';
	} elseif ($joined === 'false') {
		echo '<div class="alert alert-danger" role="alert">Erreur ! Petit malin ! On ne peut pas rentrer dans une partie si on y est déjà !</div>';
	}
?>
    <!--Page Title-->
    <section class="page-banner" style="background-image:url(images/heading.jpg);">
        <div class="top-pattern-layer"></div>

        <div class="banner-inner">
            <div class="auto-container">
                <div class="inner-container clearfix">
                    <ul class="bread-crumb clearfix">
                        <li><a href="index.php">Home</a></li>
                        <li>Parties</li>
                    </ul>
                    <h1>Parties</h1>
                </div>
            </div>
        </div>
    </section>
    <!--End Page Banner-->

	<!--Games Section-->
    <section class="games-section">
        <div class="top-pattern-layer"></div>
        <div class="bottom-pattern-layer"></div>

        <div class="auto-container">
            <!--Title-->
            <div class="sec-title centered"><h2>Les parties</h2><span class="bottom-curve"></span></div>

			<div class="row clearfix">
				<?php foreach ($parties->results as $party): ?>
					<div class="game-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="game-details.html"><img src="<?php echo htmlspecialchars($party->url_image); ?>" alt="" title=""></a></figure>
								<div class="link-box">
									<a href="controllers/join_party.php?party_id=<?php echo htmlspecialchars($party->id); ?>&user_id=<?php echo $_COOKIE['user_id']; ?>" class="link-btn">
										<span class="btn-title">Rejoindre la partie</span>
									</a>
								</div>
							</div>
							<div class="lower-content">
								<h3><a href="game-details.html"><?php echo htmlspecialchars($party->title); ?></a></h3>
								<div class="text">Créé par : <?php echo htmlspecialchars($party->Founder->username); ?></div>
								<div class="post-info">
									<ul class="clearfix">
										<li><a href="#"><span class="icon flaticon-calendar-2"></span><?php echo date('F Y', strtotime($party->created_at)); ?></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Navigation -->
			<div class="pagination-wrapper text-center">
				<div class="d-inline-block">
					<?php if ($current_page > 1): ?>
						<button type="button" role="presentation" class="navigation-btn owl-prev" onclick="location.href='?page=<?php echo $current_page - 1; ?>'">
							<span class="icon flaticon-triangle"></span> Précédent
						</button>
					<?php endif; ?>

					<?php if ($current_page < ceil($parties->count / 9)): ?>
						<button type="button" role="presentation" class="navigation-btn owl-next" onclick="location.href='?page=<?php echo $current_page + 1; ?>'">
							Suivant <span class="icon flaticon-next"></span>
						</button>
					<?php endif; ?>
				</div>
			</div>
        
    </section>

<?php
	// on inclu le fichier footer.php
    require_once('includes/footer.php');
?>