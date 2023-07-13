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
        CURLOPT_URL => 'http://139.162.199.69/api/party/' . $party_id . '/',
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
      CURLOPT_URL => 'http://139.162.199.69/api/player/' . $user_id . '/',
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
        CURLOPT_URL => 'http://139.162.199.69/api/message/' . $party_id . '/',
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
									<div class="designation">ID: <?php echo htmlspecialchars($participant->player->id); ?></div>
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
									<button id="send-button" class="btn btn-primary">Envoyer</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	


	<svg id="morpion" width="300" height="300">
	</svg>

	<script>
	// Stockage du JSON dans une variable
	var data = {
    "displays": [
      {
        "width": "300",
        "height": "300",
        "content": [
          {
            "tag": "style",
            "content": "line{stroke:black;stroke-width:4;}"
          },
          {
            "tag": "line",
            "x1": "0",
            "y1": "100",
            "x2": "300",
            "y2": "100"
          },
          {
            "tag": "line",
            "x1": "100",
            "y1": "0",
            "x2": "100",
            "y2": "300"
          },
          {
            "tag": "line",
            "x1": "0",
            "y1": "200",
            "x2": "300",
            "y2": "200"
          },
          {
            "tag": "line",
            "x1": "200",
            "y1": "0",
            "x2": "200",
            "y2": "300"
          }
        ],
        "player": 1
      },
      {
        "width": "300",
        "height": "300",
        "content": [
          {
            "tag": "style",
            "content": "line{stroke:black;stroke-width:4;}"
          },
          {
            "tag": "line",
            "x1": "0",
            "y1": "100",
            "x2": "300",
            "y2": "100"
          },
          {
            "tag": "line",
            "x1": "100",
            "y1": "0",
            "x2": "100",
            "y2": "300"
          },
          {
            "tag": "line",
            "x1": "0",
            "y1": "200",
            "x2": "300",
            "y2": "200"
          },
          {
            "tag": "line",
            "x1": "200",
            "y1": "0",
            "x2": "200",
            "y2": "300"
          }
        ],
        "player": 2
      }
    ],
    "requested_actions": [
      {
        "type": "CLICK",
        "player": 1,
        "zones": [
          {
            "x": 0,
            "y": 0,
            "width": 100,
            "height": 100
          },
          {
            "x": 0,
            "y": 100,
            "width": 100,
            "height": 100
          },
          {
            "x": 0,
            "y": 200,
            "width": 100,
            "height": 100
          },
          {
            "x": 100,
            "y": 0,
            "width": 100,
            "height": 100
          },
          {
            "x": 100,
            "y": 100,
            "width": 100,
            "height": 100
          },
          {
            "x": 100,
            "y": 200,
            "width": 100,
            "height": 100
          },
          {
            "x": 200,
            "y": 0,
            "width": 100,
            "height": 100
          },
          {
            "x": 200,
            "y": 100,
            "width": 100,
            "height": 100
          },
          {
            "x": 200,
            "y": 200,
            "width": 100,
            "height": 100
          }
        ]
      }
    ],
    "game_state": {
      "scores": [
        0,
        0
      ],
      "game_over": false
    }
  };

	var svg = document.getElementById('morpion');
	var gameBoard = [
	  ["", "", ""],
	  ["", "", ""],
	  ["", "", ""]
	];

	data.displays.forEach(function(display, playerIndex) {
	  display.content.forEach(function(item) {
		if (item.tag == "line") {
		  var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
		  line.setAttribute('x1', item.x1);
		  line.setAttribute('y1', item.y1);
		  line.setAttribute('x2', item.x2);
		  line.setAttribute('y2', item.y2);
		  line.style.stroke = "black";
		  line.style.strokeWidth = "4";
		  svg.appendChild(line);
		}
	  });
	});

	data.requested_actions.forEach(function(action) {
	  action.zones.forEach(function(zone, index) {
		var rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
		rect.setAttribute('x', zone.x);
		rect.setAttribute('y', zone.y);
		rect.setAttribute('width', zone.width);
		rect.setAttribute('height', zone.height);
		rect.style.fill = "transparent";
		
		rect.addEventListener('click', function() {
			if (gameBoard[Math.floor(index/3)][index%3] != "") {
			  return;
			}
			gameBoard[Math.floor(index/3)][index%3] = action.player == 1 ? "X" : "O";
			
			var text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			text.setAttribute('x', zone.x + 50);
			text.setAttribute('y', zone.y + 70);
			text.textContent = action.player == 1 ? "X" : "O";
			text.style.fontFamily = "Verdana";
			text.style.fontSize = "35px";
			text.style.fill = "black";
			svg.appendChild(text);
			
			if (checkWin(gameBoard)) {
			  alert("Player " + action.player + " wins!");
			}
		  });
		svg.appendChild(rect);
	  });
	});

	function checkWin(board) {
	  for (var i = 0; i < 3; i++) {
		if (board[i][0] != "" && board[i][0] == board[i][1] && board[i][0] == board[i][2]) return true;
		if (board[0][i] != "" && board[0][i] == board[1][i] && board[0][i] == board[2][i]) return true;
	  }
	  if (board[0][0] != "" && board[0][0] == board[1][1] && board[0][0] == board[2][2]) return true;
	  if (board[0][2] != "" && board[0][2] == board[1][1] && board[0][2] == board[2][0]) return true;
	  
	  return false;
	}

	</script>



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
		var chatSocket = new WebSocket(ws_scheme + '://139.162.199.69/ws/chat/' + partyId + '/');
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
