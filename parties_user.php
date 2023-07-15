<?php
	// on inclu le fichier entete.php
    require_once('includes/entete.php');
    $auth_token = $_COOKIE['auth_token'];
    $user_id = $_COOKIE['user_id'];

    // Récupère le numéro de page depuis l'URL, ou utilise la valeur par défaut 1
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Récupère les parties depuis l'API
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nicolasdebras.fr/api/myparty/' . $user_id . '/?page=' . $current_page,
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
        $response_data = json_decode($response, false);
        $parties = $response_data->results;
        $total_count = $response_data->count;
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
        $parties = [];
        $total_count = 0;
    }
    // Ferme cURL
    curl_close($curl);
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
                    <h1>Mes parties</h1>
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
			<?php if (!empty($parties)): ?>
				<?php foreach ($parties as $party): ?>
					<div class="game-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="infos_partie.php?party_id=<?php echo htmlspecialchars($party->id); ?>"><img src="<?php echo htmlspecialchars($party->url_image); ?>" alt="" title=""></a></figure>
								<div class="link-box"><a href="infos_partie.php?party_id=<?php echo htmlspecialchars($party->id); ?>" class="link-btn"> <span class="btn-title">Accéder</span></a></div>
							</div>
							<div class="lower-content">
								<h3><a href="infos_partie.php?party_id=<?php echo htmlspecialchars($party->id); ?>"><?php echo htmlspecialchars($party->title); ?></a></h3>
								<div class="text">Créé par : <?php echo htmlspecialchars($party->Founder->username); ?></div>
								<div class="post-info">
									<ul class="clearfix">
										<li><a href="infos_partie.php?party_id=<?php echo htmlspecialchars($party->id); ?>"><span class="icon flaticon-calendar-2"></span><?php echo date('F Y', strtotime($party->created_at)); ?></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p class="text-center">Aucune partie trouvée.</p>
			<?php endif; ?>
		</div>


		<div class="pagination-wrapper text-center">
			<div class="d-inline-block">
				<?php if ($current_page > 1): ?>
					<button type="button" role="presentation" class="navigation-btn owl-prev" onclick="location.href='?page=<?php echo $current_page - 1; ?>'">
						<span class="icon flaticon-triangle"></span> Précédent
					</button>
				<?php endif; ?>

				<?php if ($current_page < ceil($total_count / 9)): ?>
					<button type="button" role="presentation" class="navigation-btn owl-next" onclick="location.href='?page=<?php echo $current_page + 1; ?>'">
						Suivant <span class="icon flaticon-next"></span>
					</button>
				<?php endif; ?>
			</div>
		</div>


    </div>
</section>

<?php
	// on inclu le fichier footer.php
    require_once('includes/footer.php');
?>