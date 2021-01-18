<?php
require_once('db/bdd.php');
require('header.php');

if (!$session_connecte AND $loginPage == false) {
	header('Location: login.php');
}

function curdate() {
    return date('d-m-Y');
}

$error = Null;
$succes = Null;

//Affichage des information de la page acteur selectionnée 
$req_acteur = $bdd->prepare('SELECT id_acteur, acteur, description, logo FROM acteur WHERE id_acteur = ? ');
$req_acteur->execute(array($_GET['acteur_id']));
$donnees = $req_acteur->fetch();

//Redirection sur index si aucun ID acteur dans URL
$id_existe = (int) $_GET['acteur_id'];
if(!empty($id_existe) AND $id_existe <= $donnees['id_acteur']) {
//Si ID de l'acteur est présent dans l'URL et qu'il est un numerique et inférieur ou égale au nombre d'acteur dans la base alors ont affiche

     if (!empty($_POST['new_commentaire'])) {
		$commentaire = htmlspecialchars($_POST['commentaire']);
	
		if (!empty($commentaire)) {
			$succes = 'merci pour votre commentaire';
			$rm_commentaire = $bdd->prepare('INSERT INTO post(id_user, id_acteur, date_add, post) VALUES(?, ?, curdate() , ?)');
			$rm_commentaire->bindValue(1, $_SESSION['id_user'], PDO::PARAM_INT);
			$rm_commentaire->bindValue(2, $donnees['id_acteur'], PDO::PARAM_INT);
			$rm_commentaire->bindValue(3, $commentaire, PDO::PARAM_STR);
			$rm_commentaire->execute();
		}
		else{
		$error = 'Renseignez un commentaire dans le champs ci dessus';
		}
	}

//Recupération du nombre de commentaire sur page acteur selectionné
$req_nb_commentaire = $bdd->prepare('SELECT count(*) AS TotalCommentaire FROM post WHERE id_acteur = :id_acteur');
$req_nb_commentaire->execute(array('id_acteur'=> $donnees['id_acteur']));
$nombre_commentaire = $req_nb_commentaire->fetch(); 

// Affichage des commentaires par boucle while
$req_commentaire = $bdd->prepare('SELECT account.prenom, id_acteur, DATE_FORMAT(date_add, \'%d/%m/%Y\') AS date_add_fr , post  FROM post LEFT JOIN account ON post.id_user = account.id_user WHERE id_acteur = :id_acteur');
$req_commentaire->execute(array('id_acteur'=> $donnees['id_acteur']));


if (!empty($_POST['likes']) || !empty($_POST['dislikes'])) {
    //selection du vote de l'utilisater si il y as
    $req_vote = $bdd->prepare('SELECT id_user, id_acteur, vote FROM vote WHERE id_user = :id_user AND id_acteur = :id_acteur');
    $req_vote->execute(array(
                'id_acteur' => $donnees['id_acteur'],
                'id_user' => $_SESSION['id_user'])); 
    $resultat_vote = $req_vote->fetch();

    if (!empty($resultat_vote)) {
         // il y a déjà un vote on change la valeur pour correspondre au nouveau vote
         $req_update_vote = $bdd->prepare('UPDATE vote SET vote = :vote WHERE id_user = :id_user AND id_acteur = :id_acteur'); 
                    $req_update_vote->execute(array(
                    'vote'=> (!empty($_POST['likes']) ? 2 : 1 ),
                    'id_acteur'=> $donnees['id_acteur'],
                    'id_user'=> $_SESSION['id_user']));
    }
    // sinon il n'y a pas de vote (première fois qu'il vote)
    else {
        	//on insert
        	$rm_vote = $bdd->prepare('INSERT INTO vote(id_user, id_acteur, vote) VALUES (?, ?, ?)');
          	$rm_vote->bindValue(1, $_SESSION['id_user'], PDO::PARAM_INT);
          	$rm_vote->bindValue(2, $donnees['id_acteur'], PDO::PARAM_INT);
          	$rm_vote->bindValue(3, (empty($_POST['likes']) ? 1 : 2 ), PDO::PARAM_INT);
          	$rm_vote->execute();
          }
}

//récupération du nombre de Like / Dislike à afficher dans les boutons
$req_nb_like = $bdd->prepare('SELECT count(vote) AS TotalAime FROM vote WHERE id_acteur = :id_acteur AND vote = 2');
$req_nb_like->execute(array('id_acteur'=> $donnees['id_acteur']));
$nombre_like = $req_nb_like->fetch(); //Affiche Nb like

$req_nb_dislike = $bdd->prepare('SELECT count(vote) AS TotalDislike FROM vote WHERE id_acteur = :id_acteur AND vote = 1');
$req_nb_dislike->execute(array('id_acteur'=> $donnees['id_acteur']));
$nombre_dislike = $req_nb_dislike->fetch(); //Affiche Nb like

}
else{
	header('Location: index.php'); 
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
		<hr/>
			<div class="container_acteur">
				<div class="image_acteur"><img src="<?= $donnees['logo'] ?>" /></div>
					<div class="texte_acteur">
						<h2><?= $donnees['acteur'] ?></h2>
							<p><a href="#">Lien acteur</a></p>
							<p><?= $donnees['description']?></p>
					</div> 
			</div>
			<div class="container_commentaire">
					<div class="nombre_commentaire">
						<p><?= $nombre_commentaire['TotalCommentaire'] ?> Commentaire<?= ($nombre_commentaire['TotalCommentaire'] > 1 ) ? 's' : ''; ?></p>
					</div>	
						<div>
							<form action="" method="POST" name="nouveau_commentaire_utilisateur">
								<div class="new_commentaire">
									<input class="bouton_commentaire" type="submit" value="Nouveau commentaire" name="new_commentaire" />
									<textarea name="commentaire" placeholder="Ajouter un commentaire..." class="textarea" ></textarea>
									<p><?= $succes ?> <?= $error ?></p>
								</div>
							</form>
								<div class="like_dislike">
									<div class="images_pouces">
										<form action="" method="POST" name="vote_user">
											<button type="submit" name="likes" value="2"><img src="css/images/like.png" alt="bouton like" /> <?= $nombre_like['TotalAime'] ?> </button>
											<button type="submit" name="dislikes" value="1"><img src="css/images/dislike.png" alt="bouton dislike" /> <?= $nombre_dislike['TotalDislike'] ?> </button>
										</form> 
									</div>
								</div>
								<?php while ($affiche = $req_commentaire->fetch()):?>
									<div class="commentaire">
										<p><strong>Prenom:</strong> <?= $affiche['prenom'] ?><br/>
										<strong>Date:</strong> <?= $affiche['date_add_fr'] ?> <br/>
										<strong>Commentaire:</strong> <?= $affiche['post'] ?></p>
									</div>
								<?php endwhile ?>
						</div>
			</div>
		<hr/>
		<?php include("footer.php"); ?>
    </body>
</html>