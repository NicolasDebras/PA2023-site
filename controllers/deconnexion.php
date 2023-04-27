<?php
	// Ici, on ne gère que la déconnexion
	// Supprime le cookie auth_token pour qu'on soit déconnecté zebi
	setcookie('auth_token', '', time() - 3600, '/');

	// Redirige vers la page d'accueil (index.php)
	header('Location: ../index.php');
	exit;
?>
