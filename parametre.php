<?php
require('db/bdd.php');
require ('header.php');



if (!empty($_POST['password'])) {
	$req = $bdd->prepare('SELECT username, password FROM account WHERE id_user = :id_user');
	$req->execute(array('id_user' => $_SESSION['id_user']));
	$resultat = $req->fetch();
	$old_pass = htmlspecialchars($_POST['old_pass']);
	$new_pass = htmlspecialchars($_POST['new_pass']);
	$password_hash = password_hash($new_pass, PASSWORD_DEFAULT);

	$password_correct = password_verify($old_pass, $resultat['password']);

		if ($resultat['password'] != $password_correct) {
			echo "Ce n'est pas votre mot de passe";	
		}
		else{
			if (strlen($new_pass) <= 8) {
				echo 'Mot de passe doit contenir 8 caractères';
			}
			else{
				$req = $bdd->prepare('UPDATE account SET password = :password WHERE id_user = :id_user'); 
				$req->execute(array(
					'password' => $password_hash,
					'id_user' => $_SESSION['id_user']));
				echo 'Le mot de passe a bien été modifié';
		}
	}
}


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
			echo "Ce n'est pas votre pseudo";	
		}
		else{
			if ($user_exist != Null) {
				echo 'Le pseudo est déjà utilisé';
			}
			elseif (empty($new_username)) {
				echo 'Renseigne un pseudo coco';
			}
			else{
				$req = $bdd->prepare('UPDATE account SET username = :username WHERE id_user = :id_user'); 
				$req->execute(array(
					'username' => $new_username,
					'id_user' => $_SESSION['id_user']));
				echo 'Le pseudo a bien été modifié';
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
						<input type="texte" name="old_pass" placeholder="Entrez votre mot de passe actuel" /></p>
						<p><label>Nouveau mot de passe</label><br/>
						<input type="password" name="new_pass" placeholder="Entrez votre nouveau mot de passe" /></p>
						<input type="submit" name="password">
			</fieldset>
		</form>
		<form action="" method="POST">
			<fieldset>
				<legend>Modifier votre pseudo</legend>
						<p><label>Pseudo actuel</label><br/>
						<input type="texte" name="old_username" placeholder="Entrez votre mot de passe actuel" /></p>
						<p><label>Nouveau pseudo</label><br/>
						<input type="password" name="new_username" placeholder="Entrez votre nouveau mot de passe" /></p>
						<input type="submit" name="username">
			</fieldset>
		</form>
	<hr>
	<?php include 'footer.php'; ?>
</body>
</html>