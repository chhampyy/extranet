<?php
require('db/bdd.php');
require ('header.php');

if (!$session_connecte AND $loginPage == false) {
	header('Location: login.php');
}

$error = Null;
$succes = Null;
if (!empty($_POST['password'])) {
	$req = $bdd->prepare('SELECT username, password FROM account WHERE id_user = :id_user');
	$req->execute(array('id_user' => $_SESSION['id_user']));
	$resultat = $req->fetch();
	$old_pass = htmlspecialchars($_POST['old_pass']);
	$new_pass = htmlspecialchars($_POST['new_pass']);
	$password_hash = password_hash($new_pass, PASSWORD_DEFAULT);

	$password_correct = password_verify($old_pass, $resultat['password']);

		if ($resultat['password'] != $password_correct) {
			$error = "Ce n'est pas votre mot de passe";	
		}
		else{
			if (strlen($new_pass) <= 8) {
				$error = 'Mot de passe doit contenir 8 caractères';
			}
			else{
				$req = $bdd->prepare('UPDATE account SET password = :password WHERE id_user = :id_user'); 
				$req->execute(array(
					'password' => $password_hash,
					'id_user' => $_SESSION['id_user']));
				$succes = 'Le mot de passe a bien été modifié';
		}
	}
}

$error_user = Null;
$succes_user = Null;
$error_user_pseudo = Null;
if (!empty($_POST['username'])) {
	$req = $bdd->prepare('SELECT username, password FROM account WHERE id_user = :id_user');
	$req->execute(array('id_user' => $_SESSION['id_user']));
	$result = $req->fetch();
	$old_username = htmlspecialchars($_POST['old_username']);
	$new_username = htmlspecialchars($_POST['new_username']);
	$user_exist = $bdd->prepare('SELECT username FROM account WHERE username = ? '); 
	$user_exist ->execute(array($new_username));
	$user_exist = $user_exist->fetch();

		if ($result['username'] != $old_username) {
			$error_user = "Ce n'est pas votre pseudo";	
		}
		else{
			if ($user_exist != Null) {
				$error_user = 'Le pseudo est déjà utilisé';
			}
			elseif (empty($new_username)) {
				$error_user = 'Renseignez votre nouveau pseudo';
			}
			else{
				$req = $bdd->prepare('UPDATE account SET username = :username WHERE id_user = :id_user'); 
				$req->execute(array(
					'username' => $new_username,
					'id_user' => $_SESSION['id_user']));
				$succes_user = 'Le pseudo a bien été modifié';
		}
	}
}

?>



<!DOCTYPE html>
<html>
<head>
	<title>GBAF - Parametre de compte</title>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
	<hr>
		<form action="" method="POST">
			<fieldset>
				<legend>Modifier votre mot de passe</legend>
						<p><label>Mot de passe actuel</label><br/>
						<input type="password" name="old_pass"  /></p>
						<p><label>Nouveau mot de passe</label><br/>
						<input type="password" name="new_pass"  /></p>
						<input type="submit" name="password">
						<p><?= $error ?> <?= $succes ?></p>
			</fieldset>
		</form>
		<form action="" method="POST">
			<fieldset>
				<legend>Modifier votre pseudo</legend>
						<p><label>Pseudo actuel</label><br/>
						<input type="text" name="old_username"  /></p>
						<p><label>Nouveau pseudo</label><br/>
						<input type="text" name="new_username"  /></p>
						<input type="submit" name="username">
						<p><?= $error_user ?> <?= $succes_user ?></p>
			</fieldset>
		</form>
	<hr>
	<?php include 'footer.php'; ?>
</body>
</html>