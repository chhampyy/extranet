<?php
require('db/bdd.php');
$loginPage = true;
require ('header.php');

if ($session_connecte) {
	header('Location: index.php');
	exit();
}

$error = Null;
 if (!empty($_POST['connexion'])) {

	$req = $bdd->prepare('SELECT id_user, prenom, nom, password FROM account WHERE username = :username');
	$req->execute(array(
	    'username' => $_POST['username']));
	$resultat = $req->fetch();


	if (!$resultat){
	    $error = 'Mauvais identifiant !';
	}
	else{
		$password_correct = password_verify($_POST['password'], $resultat['password']);
	    if ($password_correct) {
	        
			session_start();
		    $_SESSION['id_user'] = $resultat['id_user'];
		    $_SESSION['username'] = $_POST['username'];
		    $_SESSION['prenom'] = $resultat['prenom'];
		    $_SESSION['nom'] = $resultat['nom'];
		    header('Location: index.php');
		    
	    }
	    else {
	        $error = 'Mauvais identifiant !';
	    }
	}
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>GBAF - Extranet</title>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
	<hr>
	<div class="login">
		<form action="" method="POST">
			<p><label>Pseudo</label><input type="text" name="username" /></p>
			<p><label>Mot de passe</label><input type="password" name="password" /></p>
			<input type="submit"  value="Connexion" name="connexion" />
			<?= $error ?>
		</form>
	</div>
		<div class="lien_log">
			<p><strong><a href="register.php" class="link">S'inscrire</a> || <a href="lost_pass.php" class="link">Mot de passe oublié</a></strong></p>
		</div>
	<hr>
	<?php include 'footer.php'; ?>
</body>
</html>