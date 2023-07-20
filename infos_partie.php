<?php
	date_default_timezone_set('Europe/Paris');

	// on inclu le fichier entete.php
    require_once('includes/entete.php');
    $auth_token = $_COOKIE['auth_token'];
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	if (!isset($_COOKIE['auth_token'])) {
		echo '<script type="text/javascript">
			   window.location.href = "index.php";
		  </script>';
	}


    $party_id = $_GET['party_id'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nicolasdebras.fr/api/oneparty/' . $party_id . '/',
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
	
    $user_id = $_COOKIE['user_id'];

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://nicolasdebras.fr/api/player/' . $user_id . '/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $auth_token
      ),
      CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        $user_data = json_decode($response);
        $username = $user_data->username;
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
        $username = null;
    }
	
    curl_close($curl);
	
	if (isset($username)) {
		echo '<script>console.log("username est défini : ", ' . json_encode($username) . ')</script>';
	} else {
		echo '<script>console.log("username n\'est pas défini.")</script>';
	}
	
	$user_id = $user_data->id;
    $username = $user_data->username;
	
	// Récupére les derniers messages
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nicolasdebras.fr/api/message/' . $party_id . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' . $auth_token
        ),
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        $messages = json_decode($response);
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
        $messages = null;
    }

    curl_close($curl);
	
	function relativeDate($date) {
		$utc = new DateTimeZone('UTC');
		$currentDate = new DateTime('', $utc);
		$interval = $date->diff($currentDate);

		if ($interval->y >= 1) {
			return $interval->format('Il y a %y ans');
		} else if ($interval->m >= 1) {
			return $interval->format('Il y a %m mois');
		} else if ($interval->d >= 1) {
			return $interval->format('Il y a %d jours');
		} else if ($interval->h >= 1) {
			return $interval->format('Il y a %h heures');
		} else if ($interval->i >= 1) {
			return $interval->format('Il y a %i minutes');
		} else {
			return 'Moins d\'une minute';
		}
	}
	
	$user_tag = null;
    foreach ($party_data->accepting_participants as $participant) {
        if ($participant->player->id == $user_id) {
            $user_tag = $participant->tag_player;
            break;
        }
    }
    echo '<script>var gameData = ' . json_encode($party_data) . ';</script>';
?>
<style>
svg *:not(rect) {
    pointer-events: none;
}
.player-element {
    user-select: none;
}
</style>


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
									<div class="designation">ID: <?php echo htmlspecialchars($participant->id); ?></div>
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
	
	<section class="contact-section">
		<div class="auto-container">
			<div class="sec-title centered"><h2>Participants en attente</h2><span class="bottom-curve"></span></div>
			<div class="row clearfix">
			<?php if ($party_data !== null && !empty($party_data->pending_participants)): ?>
				<?php foreach ($party_data->pending_participants as $participant): ?>
					<div class="team-block col-lg-3 col-md-6 col-sm-12 wow fadeInLeft" style="margin:auto;" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="default-form contact-form">
							<div class="inner-box">
								<figure class="image-box"><a href="#"><img src="<?php echo htmlspecialchars($participant->player->url_image); ?>" alt="" title=""></a></figure>
								<div class="lower-box">
									<h3><a href="#"><?php echo htmlspecialchars($participant->player->username); ?></a></h3>
									<div class="designation">ID: <?php echo htmlspecialchars($participant->id); ?></div>
									<?php if ($user_id == $party_data->Founder->id): ?>
										<div class="text-center">
											<form method="post" action="controllers/accept_request.php">
												<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
												<input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>">
												<input type="hidden" name="request_id" value="<?php echo htmlspecialchars($participant->id); ?>">
												<input type="hidden" name="party_id" value="<?php echo $party_id; ?>">
												<div class="col-md-12 col-sm-12 form-group">
													<button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Accepter</span></button>
												</div>
											</form>
										</div>
									<?php elseif ($user_id != $party_data->Founder->id): ?>
										<p class="text-danger">Seul le créateur de la partie peut accepter les demandes.</p>
									<?php endif; ?>
								</div>
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

	<!-- Chat Section -->
	<section class="contact-section">
		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="card">
						<div class="card-header bg-primary text-white">
							<h4 class="card-title mb-0">Chat en direct</h4>
						</div>
						<div id="message-container" class="card-body chat-body p-4" style="height: 300px; overflow-y: auto; border-bottom: 1px solid #ccc;">
							<?php if ($messages !== null): ?>
								<?php foreach ($messages as $message): ?>
									<div class="d-flex justify-content-<?php echo $message->sender->id == $user_id ? 'end' : 'start'; ?>"><?php echo $message->sender->id == $user_id ? 'Vous' : $message->sender->username;?></div>
									<div class="d-flex justify-content-<?php echo $message->sender->id == $user_id ? 'end' : 'start'; ?>">
										<?php 
										$date = new DateTime($message->timestamp, new DateTimeZone('UTC'));
										echo relativeDate($date);
										?>
									</div>
									<div class="d-flex justify-content-<?php echo $message->sender->id == $user_id ? 'end' : 'start'; ?> mb-3">
										<div class="msg_cotainer_send bg-primary text-white p-2 rounded">
											<?php echo htmlspecialchars($message->content); ?>
											<span class="msg_time_send"><?php echo date('d M Y H:i:s', strtotime($message->timestamp)); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<div class="card-footer">
							<div class="input-group">
								<input id="message-input" type="text" class="form-control" placeholder="Entrez votre message ici" required="">
								<div class="input-group-append">
									<button id="emoji-button" class="btn btn-secondary">😀</button>
									<div class="tooltip" role="tooltip">
										<emoji-picker></emoji-picker>
									</div>
									<button id="send-button" class="btn btn-primary">Envoyer</button>
								</div>
							</div>
						</div>
						<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
						<script type="module">
						  import * as Popper from 'https://cdn.jsdelivr.net/npm/@popperjs/core@^2/dist/esm/index.js'
						  import insertText from 'https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js'
						  const button = document.querySelector('#emoji-button')
						  const tooltip = document.querySelector('.tooltip')
						  Popper.createPopper(button, tooltip)

						  document.querySelector('#emoji-button').onclick = () => {
							tooltip.classList.toggle('shown').offsetWidth;
						  }
						  document.querySelector('emoji-picker').addEventListener('emoji-click', e => {
                            insertText(document.querySelector('#message-input'), e.detail.unicode);
                          })
						</script>
					</div>
				</div>
			</div>
		</div>
	</section>

    <?php if ($user_id == $party_data->Founder->id): ?>
    <!-- Game Section -->
    <section class="video-section">
        <div class="image-layer" style="background-image: url(images/background/video-bg.jpg);"></div>
    
        <div class="auto-container">
            <div class="content-box wow zoomInStable" data-wow-delay="0ms" data-wow-duration="2500ms">
                <div class="link-box">
                    <a href="#myContent" class="lightbox-image"><span class="fa fa-cog fa-fw"></span></a>
                </div>
                <h2>Configurer le jeu !</h2>
            </div>
        </div>
    
        <div id="myContent" style="display: none; background-color: black;">
        <section class="contact-section">
            <div class="auto-container">
                <div class="sec-title centered"><h2>Configuration</h2><span class="bottom-curve"></span></div>
    
                <div class="form-box">
                    <div class="default-form contact-form">
                        <form method="post" action="controllers/CreateGame.php" id="signup-form" enctype="multipart/form-data" onsubmit="return validateForm();">
                            <div class="row clearfix">
                                <p>Gestion des arguments (laisser vide si pas d'arguments) :</p>
                                <div class="col-md-12 col-sm-12 form-group">
                                    <input type="text" class="argument-name" name="arguments[name][]" placeholder="Nom (ex : boat)">
                                </div>
                                
                                <div class="col-md-12 col-sm-12 form-group">
                                    <input type="text" class="argument-type" name="arguments[type][]" placeholder="Type (ex : int)">
                                </div>
                                
                                <div class="col-md-12 col-sm-12 form-group">
                                    <input type="text" class="argument-value" name="arguments[value][]" placeholder="Valeur (ex : 5)">
                                </div>
                                
                                <script>
                                    function validateForm() {
                                        var argumentNames = Array.from(document.getElementsByClassName('argument-name'));
                                        var argumentTypes = Array.from(document.getElementsByClassName('argument-type'));
                                        var argumentValues = Array.from(document.getElementsByClassName('argument-value'));
                                
                                        for(let i = 0; i < argumentNames.length; i++) {
                                            let name = argumentNames[i].value;
                                            let type = argumentTypes[i].value;
                                            let value = argumentValues[i].value;
                                
                                            if ((name == '' && type == '' && value == '') ||
                                                (name != '' && type != '' && value != '')) {
                                                continue;
                                            } else {
                                                alert('Veuillez soit remplir tous les champs d\'arguments, soit les laisser tous vides !');
                                                return false;
                                            }
                                        }
                                        
                                        return true;
                                    }
                                </script>
                                
                                <p>Paramètres :</p>
                                <div class="col-md-12 col-sm-12 form-group">
                                    <input type="text" name="langage" value=".py" readonly>
                                </div>
    
                                <div class="col-md-12 col-sm-12 form-group">
                                    <select id="player-number" name="number" required>
                                    </select>
                                </div>
    
                                <div class="col-md-12 col-sm-12 form-group" id="player-selects"></div>
    
                                <script>
                                    var players = <?php echo json_encode($party_data->accepting_participants); ?>;
                                    var currentUserId = <?php echo json_encode($user_id); ?>;
                                
                                    const totalNumberOfParticipants = players.filter(function(player) {
                                        return player.player.id != currentUserId;
                                    }).length + 1;
                                
                                    const numberOfPlayersSelect = document.getElementById('player-number');
                                
                                    for (let i = 2; i <= totalNumberOfParticipants; i++) {
                                        const optionElement = document.createElement('option');
                                        optionElement.value = i;
                                        optionElement.text = i;
                                        numberOfPlayersSelect.appendChild(optionElement);
                                    }
                                
                                    function handlePlayerSelectCreation() {
                                        const numberOfPlayers = parseInt(numberOfPlayersSelect.value);
                                        if (isNaN(numberOfPlayers) || numberOfPlayers < 2) {
                                            alert("Veuillez entrer un nombre entier supérieur ou égal à 2");
                                            return;
                                        }
                                
                                        const playerSelectsContainer = document.getElementById('player-selects');
                                        playerSelectsContainer.innerHTML = '';
                                
                                        var filteredPlayers = players.filter(function(player) {
                                            return player.player.id != currentUserId;
                                        });
                                
                                        for (let i = 0; i < numberOfPlayers - 1; i++) {
                                            const selectElement = document.createElement('select');
                                            selectElement.name = 'participants[]';
                                            selectElement.required = true;
                                
                                            const defaultOptionElement = document.createElement('option');
                                            defaultOptionElement.value = '';
                                            defaultOptionElement.text = '-- Sélectionnez un joueur --';
                                            selectElement.appendChild(defaultOptionElement);
                                
                                            for (let j = 0; j < filteredPlayers.length; j++) {
                                                const optionElement = document.createElement('option');
                                                optionElement.value = filteredPlayers[j].id;
                                                optionElement.text = filteredPlayers[j].player.username;
                                                selectElement.appendChild(optionElement);
                                            }
                                
                                            selectElement.addEventListener('change', function() {
                                                updatePlayerSelects();
                                            });
                                
                                            playerSelectsContainer.appendChild(selectElement);
                                        }
                                
                                        function updatePlayerSelects() {
                                            const selects = playerSelectsContainer.querySelectorAll('select');
                                            const selectedPlayerIds = Array.from(selects).map(select => select.value);
                                
                                            selects.forEach((select) => {
                                                const currentSelectedValue = select.value;
                                                select.innerHTML = '';
                                
                                                filteredPlayers.forEach(player => {
                                                    if (!selectedPlayerIds.includes(player.player.id.toString()) || player.player.id.toString() === currentSelectedValue) {
                                                        const optionElement = document.createElement('option');
                                                        optionElement.value = player.id;
                                                        optionElement.text = player.player.username;
                                                        select.appendChild(optionElement);
                                                    }
                                                });
                                
                                                select.value = currentSelectedValue;
                                            });
                                        }
                                    }

                                    numberOfPlayersSelect.addEventListener('change', handlePlayerSelectCreation);
                                    handlePlayerSelectCreation();
                                
                                </script>
    
                                <div class="col-md-12 col-sm-12 form-group">
                                    <label for="game">Importer le fichier :</label>
                                    <input type="file" name="game" id="game" required>
                                </div>
    
                                <div class="col-md-12 col-sm-12 form-group">
                                    <input type="hidden" name="party_id" value="<?php echo $party_data->id; ?>">
                                </div>
    
                                <div class="col-md-12 col-sm-12 form-group">
                                    <input type="hidden" name="creator_id" value="<?php echo $party_data->accepting_participants[0]->id; ?>">
                                </div>
    
                                <div class="col-md-12 col-sm-12 form-group">
                                    <div class="text-center">
                                        <button class="theme-btn btn-style-one" type="submit" name="submit-form"><span class="btn-title">Envoyer</span></button>
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
    <?php endif; ?>
    
    <?php if ($party_data->started == true && $user_tag != null): ?>
    <section class="contact-section">
        <div class="top-pattern-layer"></div>
        <div class="bottom-pattern-layer"></div>
    
        <div class="auto-container">
            <div class="sec-title centered"><h2>Le jeu</h2><span class="bottom-curve"></span></div>
        <?php if ($user_id == $party_data->Founder->id): ?>
		<div class="form-box">
    		<div class="default-form contact-form">
				<div class="row clearfix">
        			<div class="col-md-12 col-sm-12 form-group">
        				<div class="text-center">
        					<button class="theme-btn btn-style-one" id="init-button" type="submit" name="submit-form"><span class="btn-title">Initialiser le jeu</span></button>
        				</div>
        			</div>
        		</div>
        	</div>

        </div><br><br><br>
        <?php endif; ?>
        <center><div id="game-container">
    
        </div></center>
        </div>
    </section>
    
    <script>
        var ws_scheme = window.location.protocol == "https:" ? "wss" : "ws";
        var partyId = <?php echo $party_id; ?>;
        var gameSocket = new WebSocket(ws_scheme + '://nicolasdebras.fr/ws/game/' + partyId + '/');
        var userId = <?php echo $user_id; ?>;
        var username = <?php echo json_encode($username); ?>;
        var maxPlayers = <?php echo $party_data->max_player; ?>;
        var gameContainer = document.getElementById('game-container');
    
        gameSocket.onopen = function(e) {
            console.log("Connexion avec le serveur de jeu ouverte");
        };
    
        var actions = [
            {
                'type': 'CLICK',
                'player': <?php echo $user_tag; ?>,
                'x': 1,
                'y': 2,
                'buttons': ['RIGHT'],
                'confirm': true
            },
        ];
        var actionIndex = 0;
    
        gameSocket.onmessage = function(e) {
            var data = JSON.parse(e.data);
            //console.log("Message reçu du serveur de jeu :", data);
            
            if ('errors' in data) {
                handleErrors(data.errors);
            }
            else {
                updateGame(data);
            }
        };
        
        function handleErrors(errors) {
            errors.forEach(function(error) {
                switch (error.type) {
                    case 'BAD_FORMAT':
                        alert("Erreur de format de données. Merci de vérifier");
                        break;
                    case 'MISSING_ARGUMENT':
                        alert("Il manque l'argument : " + error.arg +". Merci de vérifier");
                        break;
                    case 'INCORRECT_VALUE':
                        alert("Valeur mauvaise pour l'argument : " + error.arg + ". Valeur donnée : " + error.value);
                        break;
                    case 'UNEXPECTED_ARGUMENT':
                        alert("Argument faux : " + error.argname + ". Valeur donnée : " + error.value);
                        break;
                    case 'UNEXPECTED_ACTION':
                        alert("Action inattendue par le joueur : " + error.player + ". Action donnée : " + JSON.stringify(error.action));
                        break;
                    case 'MISSING_ACTION':
                        alert("Il manque l'action du joueur : " + error.player + ". Action demandée à l'autre joueur  : " + JSON.stringify(error.requested_action));
                        break;
                    case 'WRONG_ACTION':
                        alert("Erreur sur l'action du joueur : " + error.player + ". Eerreur : " + error.subtype + ". Action donnée : " + JSON.stringify(error.action) + ". Action demandée au joueur : " + JSON.stringify(error.requested_action));
                        break;
                    default:
                        alert("Erreur inconnue :" + JSON.stringify(error));
                }
            });
        }
    
        gameSocket.onclose = function(e) {
            console.error('Connexion avec le serveur de jeu fermée inopinément');
        };
    
        document.querySelector('#init-button').onclick = function(e) {
            
            // Préparation de l'objet init
            let initObject = {
                'players': maxPlayers
            };
            
            if (gameData.fk_game_argument && gameData.fk_game_argument.length > 0) {
                gameData.fk_game_argument.forEach(argument => {
                    initObject[argument.name] = argument.value;
                });
            }
        
            gameSocket.send(JSON.stringify({
                'init': initObject
            }));
        };
    
    function updateGame(data) {
        while (gameContainer.firstChild) {
            gameContainer.removeChild(gameContainer.firstChild);
        }
    
        data.displays.forEach(function(display, index) {
            if (display.player == <?php echo $user_tag; ?>) {
                var playerElement = document.createElement('p');
                playerElement.className = 'player-element';
                playerElement.textContent = "Player " + (index + 1);
            
                var boardElement = createGameBoard(display);
                addClickZones(boardElement, data.requested_actions);
                playerElement.appendChild(boardElement);
            
                gameContainer.appendChild(playerElement);
            }
        });
    
        var gameStateElement = document.createElement('div');
        gameStateElement.textContent = "Game Over: " + data.game_state.game_over;
        gameContainer.appendChild(gameStateElement);
    
        var scoresElement = document.createElement('div');
        scoresElement.textContent = "Scores: " + data.game_state.scores.join(', ');
        gameContainer.appendChild(scoresElement);
    }
    
    function createGameBoard(display) {
        const gameBoard = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    
        gameBoard.setAttribute("width", display.width);
        gameBoard.setAttribute("height", display.height);
    
        display.content.forEach(item => {
            if (item.tag === "line") {
                const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
    
                line.setAttribute("x1", item.x1);
                line.setAttribute("y1", item.y1);
                line.setAttribute("x2", item.x2);
                line.setAttribute("y2", item.y2);
    
                gameBoard.appendChild(line);
            } else if (item.tag === "style") {
                const style = document.createElement("style");
                style.textContent = item.content;
                gameBoard.appendChild(style);
            } else if (item.tag === "circle") {
                const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    
                circle.setAttribute("cx", item.cx);
                circle.setAttribute("cy", item.cy);
                circle.setAttribute("r", item.r);
                circle.setAttribute("fill", item.fill);
    
                gameBoard.appendChild(circle);
            } else if (item.tag === "rect") {
                const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
    
                rect.setAttribute("x", item.x);
                rect.setAttribute("y", item.y);
                rect.setAttribute("width", item.width);
                rect.setAttribute("height", item.height);
                rect.setAttribute("fill", item.fill);
    
                gameBoard.appendChild(rect);
            } else if (item.tag === "text") {
                const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
    
                text.setAttribute("x", item.x);
                text.setAttribute("y", item.y);
                text.setAttribute("fill", item.fill);
                text.textContent = item.content;
    
                gameBoard.appendChild(text);
            }
        });
    
        return gameBoard;
    }

    
    function addClickZones(gameBoard, requestedActions) {
        console.log(requestedActions);
        requestedActions.forEach(action => {
            if (action.type === "CLICK") {
                action.zones.forEach(zone => {
                    const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
    
                    rect.setAttribute("x", zone.x);
                    rect.setAttribute("y", zone.y);
                    rect.setAttribute("width", zone.width);
                    rect.setAttribute("height", zone.height);
                    rect.setAttribute("fill", "transparent");
    
                    rect.addEventListener("click", () => {
                        console.log(`Clicked on zone at (${zone.x}, ${zone.y})`);
    
                        var clickAction = {
                            'type': 'CLICK',
                            'player': <?php echo $user_tag; ?>,
                            'x': zone.x,
                            'y': zone.y,
                            'buttons': ['RIGHT'],
                            'confirm': true
                        };
    
                        gameSocket.send(JSON.stringify({
                            'actions': [clickAction]
                        }));
                    });
    
                    gameBoard.appendChild(rect);
                });
            }
        });
    }
    
    </script>
    
    <?php endif; ?>

	<script>
		function hashCode(str) {
			var hash = 0;
			for (var i = 0; i < str.length; i++) {
				hash = str.charCodeAt(i) + ((hash << 5) - hash);
			}
			return hash;
		}
		
		function timeSince(date) {
		  var seconds = Math.floor((new Date() - date) / 1000);
		  var interval = Math.floor(seconds / 31536000);
		  if (interval > 1) {
			return "Il y a" + interval + " ans";
		  }
		  interval = Math.floor(seconds / 2592000);
		  if (interval > 1) {
			return "Il y a" + interval + " mois";
		  }
		  interval = Math.floor(seconds / 86400);
		  if (interval > 1) {
			return "Il y a" + interval + " jours";
		  }
		  interval = Math.floor(seconds / 3600);
		  if (interval > 1) {
			return "Il y a" + interval + " heures";
		  }
		  interval = Math.floor(seconds / 60);
		  if (interval > 1) {
			return "Il y a" + interval + " minutes";
		  }
		  return "Moins d'une minute";
		}

		var ws_scheme = window.location.protocol == "https:" ? "wss" : "ws";
		var partyId = <?php echo $party_id; ?>;
		var chatSocket = new WebSocket(ws_scheme + '://nicolasdebras.fr/ws/chat/' + partyId + '/');
		console.log(chatSocket);
		var senderId = <?php echo $user_id; ?>;
		var username = <?php echo json_encode($username); ?>;

		chatSocket.onopen = function(e) {
			console.log("Connection avec le chat ouverte");
		};

		chatSocket.onmessage = function(e) {
		  var data = JSON.parse(e.data);
		  var message = data['message'];
		  var sender = data['username'];
		  var senderIdInMessage = data['sender_id'];
		  var timestamp = new Date(data['timestamp']);

		  var messageElement = document.createElement('div');

		  if(senderIdInMessage == senderId) {
			messageElement.classList.add("message-sent");
			messageElement.innerHTML = `<div class="d-flex justify-content-end">Vous</div>
										<div data-timestamp="${timestamp.getTime()}" class="timestamp d-flex justify-content-end">${timeSince(timestamp)}</div>
										<div class="d-flex justify-content-end mb-3">
											<div class="msg_cotainer_send bg-primary text-white p-2 rounded">
												${message}
											</div>
										</div>`;
		  } else {
			messageElement.classList.add("message-received");
			messageElement.innerHTML = `<div class="d-flex justify-content-start">${sender}</div>
										<div data-timestamp="${timestamp.getTime()}" class="timestamp d-flex justify-content-start">${timeSince(timestamp)}</div>
										<div class="d-flex justify-content-start mb-3">
											<div class="msg_cotainer_send bg-primary text-white p-2 rounded">
												${message}
											</div>
										</div>`;
		  }

		  document.querySelector('#message-container').appendChild(messageElement);
		};
		
		chatSocket.onerror = function(error) {
          console.error("Erreur sur le WebSocket de chat:", error);
        };


		setInterval(function() {
		  var timestampElements = document.querySelectorAll('.timestamp');

		  timestampElements.forEach(function(element) {
			var timestamp = new Date(parseInt(element.getAttribute('data-timestamp')));
			element.textContent = timeSince(timestamp);
		  });
		}, 5000);

		chatSocket.onclose = function(e) {
			console.error('Chat socket closed unexpectedly');
		};

		document.querySelector('#send-button').onclick = function(e) {
			var messageInputDom = document.querySelector('#message-input');
			var message = messageInputDom.value;
			chatSocket.send(JSON.stringify({
				'type': 'chat_message',
				'message': message,
				'sender_id': senderId,
				'username': username
			}));

			messageInputDom.value = '';
		};
	</script>
	
<?php
	// on inclu le fichier footer.php
    require_once('includes/footer.php');
?>
