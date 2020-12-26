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
			
			<div class="container_acteur">
				<div class="image_acteur"><img src="css/images/formation_co.png" alt="image acteur formation&Co" /></div>
					<div class="texte_acteur">
						<h2>Nom de l'acteur</h2>
							<p><a href="#">Lien acteur</a></p>
							<p>Contenue textuel de l'acteur (présentation, ce qu'ils font, etc...)</p>
					</div> 
			</div>

			<div class="container_commentaire">
					<div class="nombre_commentaire">
						<p>X Commentaire</p>
					</div>
					<div class="new_commentaire"><input class="bouton_commentaire" type="button" value="Nouveau commentaire"></div>
					<div class="like&dislike">
						<div class="images_pouces">
							<img src="" alt="" />
							<img src="" alt="" />
						</div>
					</div>
				<div class="commentaire">
					<p>Prenom:<br/>Date:<br/>Commentaire:</p>
				</div>
			</div>

		<hr/>
		<?php include("footer.php"); ?>

    </body>
	
</html>