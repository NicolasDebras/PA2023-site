class Chat {
	constructor(url) {
		this.url = url;
		this.client = null;
	}

	connect() {
		// Se connecte au serveur WebSocket et initialise le client STOMP
		this.client = Stomp.client(this.url);
		this.client.connect({}, this.onConnect.bind(this), this.onError.bind(this));
	}

	disconnect() {
		// Se déconnecte du serveur WebSocket
		if (this.client !== null) {
			this.client.disconnect();
		}
	}

	sendMessage(message) {
		// Envoie un message au serveur WebSocket
		if (this.client !== null) {
			this.client.send('/chat/send/', {}, JSON.stringify({'message': message}));
		}
	}

	onConnect() {
		// Se connecte avec succès au serveur WebSocket
		console.log('Connecté au serveur WebSocket');
		this.client.subscribe('/chat/messages/', this.onMessage.bind(this));
	}

	onError(error) {
		// Rencontre une erreur lors de la connexion au serveur WebSocket
		console.log('Erreur de connexion au serveur WebSocket : ' + error);
	}

	onMessage(message) {
		// Traite un message reçu du serveur WebSocket
		var data = JSON.parse(message.body);
		var messageContainer = $('#message-container');
		messageContainer.append('<p>' + data.message + '</p>');
		messageContainer.scrollTop(messageContainer.prop('scrollHeight'));
	}

	// Gère les interactions de chat côté client
	// $(document).ready(function() {
	// 	$('#send-button').click(function() {
	// 		var messageInput = $('#message-input');
	// 		var message = messageInput.val().trim();
	// 		if (message !== '') {
	// 			chat.sendMessage(message);
	// 			messageInput.val('');
	// 		}
	// 	});

	// 	$('#message-input').keypress(function(event) {
	// 		if (event.which === 13) {
	// 			event.preventDefault();
	// 			$('#send-button').click();
	// 		}
	// 	});
	// });
}