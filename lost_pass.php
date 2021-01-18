<?php
require('db/bdd.php');
$loginPage = true;
require ('header.php');

if ($session_connecte) {
	header('Location: index.php');
	exit();
}

$affiche_question = Null;
$succes = Null; 
$error = Null;
$error_question = Null;
$change_password = Null;


if(!empty($_GET['username'])){
	$req = $bdd->prepare('SELECT * FROM account WHERE username = :username');
	$req->execute(array('username'=> $_GET['username']));
	$resultat = $req->fetch();
	$_GET['username'] = htmlspecialchars($_GET['username']);

		if (!$resultat) {
			$error = 'Le pseudo n\'existe pas dans notre base';
		}
		else{
			if ($resultat == true) {
				$affiche_question = '<p><strong>Question:</strong> '.$resultat['question'].' </p>
										<form action="" method="POST">
											<p><label>Votre reponse: </label><input type="text" name="reponse"/><br>
											<input type="submit" name="lostpass_reponse"></p>
										</form>';
										
		}
	}
}

if (!empty($_POST['lostpass_reponse'])) {
	$req = $bdd->prepare('SELECT * FROM account WHERE reponse = :reponse');
	$req->execute(array('reponse'=> $_POST['reponse']));
	$resultat_reponse = $req->fetch();
	$_POST['reponse'] = htmlspecialchars($_POST['reponse']); 

	
		if ($resultat_reponse == true) {
			
			$succes = 'Bonne reponse, vous pouvez changer votre mot de passe';
			$change_password = '<form action="" method="POST">
									<p><label>Entrez votre nouveau mot de passe :</label><input type="password" name="password"><br>
									<input type="submit" name="newpass" /></p>
								</form>';
		}
		else{
			$error_question = 'la reponse ne correspond pas ';
	}
}

$succes_pass= Null;
$error_pass= Null;

if (!empty($_POST['newpass'])) {
	$req = $bdd->prepare('SELECT username, password, FROM account');
	$change_pass = $req->fetch();
	$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		if (strlen($_POST['password']) <= 8) {
					$error_pass = 'Mot de passe doit contenir 8 caractères';
				}
				else{
					$req = $bdd->prepare('UPDATE account SET password = :password WHERE username = :username'); 
					$req->execute(array(
						'password' => $password_hash,
						'username' => $_GET['username']));
					$succes_pass = 'Le mot de passe a bien été modifié';
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
		<div class="lostpass_pseudo">
			<form action="" method="GET">
				<p><label>Votre pseudo: </label><input type="text" name="username" value="<?php if(isset($_GET['username'])){ echo $_GET['username']; }?>" /><br>
					<input type="submit" ></p>
					<?= $error ?>
			</form>
		</div>
		<div class="lostpass_question">
			<?= $affiche_question ?>
			<?= $succes ?> <?= $error_question ?>
		</div>
		<div class="change_password">
			<?= $change_password ?>
			<?= $succes_pass ?> <?= $error_pass ?>
		</div>
	<hr>
	<?php include 'footer.php'; ?>
</body>
</html>