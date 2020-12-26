<?php
session_start();
require_once('db/bdd.php');	

$succes = '';
$error = '';
$error_user = '';

if (!empty($_POST['inscription'])){
	$nom = htmlspecialchars($_POST['nom']);
	$prenom = htmlspecialchars($_POST['prenom']);
	$username= htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$question = htmlspecialchars($_POST['question']);
	$reponse = htmlspecialchars($_POST['reponse']);
	$pass_hash = password_hash($password, PASSWORD_DEFAULT);
	$user_exist = $bdd->query("SELECT username FROM account WHERE username = '' "); 
	$user_exist = $user_exist->fetch();

	    if (isset($nom) && $prenom && $username && $question && $reponse && $password)  {
	    	if (strlen($password) <= 8) {
	    		$error = 'le mot de passe doit contenir 8 caractères<br/>';
	    	}
	    	elseif ($user_exist = true) {
	    		$error_user = 'Le pseudo est déjà utilisé';
	    	}
	    	else{
	    		$succes = 'Inscription prise en compte';
				$req = $bdd->prepare('INSERT INTO account(nom, prenom, username, password, question, reponse) VALUES(?, ?, ?, ?, ?, ?)');
				$req->execute(array($nom, $prenom, $username, $pass_hash, $question, $reponse));
			}
	    }	
		else{
			$error = 'Veuillez renseigner l\'ensemble des champs.';
	}
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Extranet</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/style.css" />
    </head>
	
    <body>
		<?php include("header.php"); ?>
		<hr/>
			
			<form action="register.php" method="POST" class="register">

				<p><?= $succes ?> <?= $error ?> <?= $error_user ?></p>

				<p><label>Nom: </label><input type="text" value="<?php if(isset($nom)){ echo $nom; }?>" name="nom" /></p>
				<p><label>Prenom: </label><input type="text" value="<?php if(isset($prenom)){ echo $prenom; }?>" name="prenom" /></p>
				<p><label>Pseudo: </label><input type="text" value="<?php if(isset($username)){ echo $username; }?>" name="username" /></p>
				<p><label>Mot de passe: </label><input type="password" name="password" /></p>
				<p><label>Question secrète: </label><input type="text" value="<?php if(isset($question)){ echo $question; }?>" name="question" /></p>
				<p><label>Réponse : </label><input type="text" value="<?php if(isset($reponse)){ echo $reponse; }?>" name="reponse" /></p>
				<input type="submit" value="S'inscrire" name="inscription" >

			</form>

		<hr/>
		<?php include("footer.php"); ?>

    </body>
	
</html>