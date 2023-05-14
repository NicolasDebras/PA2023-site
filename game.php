<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Mon site avec un chat en temps réel</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/stomp.js/2.3.3/stomp.min.js"></script>
	<script src="{% static 'js/chat.js' %}"></script>
</head>
<body>

	<h1>Mon site avec un chat en temps réel</h1>

	<div id="chat">
		<div id="message-container"></div>
		<input type="text" id="message-input" placeholder="Entrez votre message ici">
		<button id="send-button">Envoyer</button>
	</div>

	<script>
		// Initialise le chat et se connecte au serveur WebSocket
		var chat = new Chat('ws://' + window.location.host + '/ws/chat/{{ room_name }}/');
		chat.connect();
	</script>

</body>
</html>
