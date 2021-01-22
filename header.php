<?php
session_start();
require('db/bdd.php'); 

$session_connecte = Null;

if (isset($_SESSION['nom']) AND isset($_SESSION['prenom'])){
	$session_connecte = ''.$_SESSION['nom'].' '.$_SESSION['prenom'].'';
}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="css/images/favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<header>
		<div class="header"> 
			<a href="index.php"><img src="css/images/logo_gbaf.png" alt="Logo GBAF Groupement Banque-Assurance Français" class="logo"/></a>
			
		<div class="user_info">
			<a href="parametre.php" title="Gestion de compte" class="link"><?= $session_connecte ?></a>
			<a href="logout.php"><?= isset($session_connecte) ? '<img src="css/images/user_logout.png" alt="se déconnecter" title="Se déconnecter"/>' : ''; ?> </a>
		</div>
		</div>
	</header>
</body>
</html>