<?php 
require_once('db/bdd.php');
require_once('header.php');
if (!$session_connecte AND $loginPage == false) {
	header('Location: login.php');
}

$req = $bdd->query('SELECT id_acteur, acteur, IF(CHAR_LENGTH(description) > 50, CONCAT(LEFT(description, 50), "..."), description) AS description, logo FROM acteur');

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
		
		<div class="container_presentation">
			<div class="texte_presentation">
			<h1>Présentation</h1>
			<p>Le site GBAF est une plateforme regroupant plusieurs agence bancaire. Grâce au site, vous serez en mesure de voir directement les avis concernant chaques acteurs partenaires. Un gain de temps précieux dans notre travail.</p>
			</div>
		</div>
		
			<div class="illustration">
			<img src="css/images/extranet.png" alt="illustration d'un extranet"/>
			</div>
		
		<hr>
		
		<div class="container_description">		
		<h2>Acteurs et partenaires</h2>
		<p>Indigentia appareat amantur ad et sensu quoddam et illa est parentes quale natos indigentia moribus animadverti mihi similis quod cum cum et evidentius cuius etiam sit quam in quantum facile.</p>
		</div>
		
		<div class="container_tableau">
			<?php while($donnees = $req->fetch()): ?>
			<div class="tableau">
				
				<div class="mini_logo"><img src="<?= $donnees['logo'] ?>"/></div>
					<div class="texte_position">
						<h3><?= $donnees['acteur'] ?></h3>
						<p><?= $donnees['description']  ?> </p>
						<div class="button"><a href="acteur.php?acteur_id=<?= $donnees['id_acteur']?>"><input class="forme_button" type="button" value="Lire la suite" ></a></div>
					</div>
			</div>
			<?php endwhile ?> 
		</div>
		<hr/>
		<?php include("footer.php"); ?>

    </body>
	
</html>