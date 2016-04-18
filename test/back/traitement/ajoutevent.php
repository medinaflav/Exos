	<?php
		// Variables vides pour les valeurs par défaut des champs
		$titre=""; $description=""; $dateDebut = date("d/m/Y", time()); $dateFin = date("d/m/Y", time());

		if(isset($_POST['envoi'])) {
			// Traitement de l'envoi de l'événement
			$titre = htmlspecialchars(addslashes($_POST['titre']));
			$description = nl2br(htmlspecialchars(addslashes($_POST['description'])));
			$dateDebut = htmlspecialchars($_POST['debut']);
			$dateFin = htmlspecialchars($_POST['fin']);

			$typeDate = "#^[0-3]?[0-9]/[01]?[0-9]/[0-9]{4}$#";

			if (preg_match($typeDate, $dateDebut) && preg_match($typeDate, $dateFin)) {
				$tabDateDeb = explode("/", $dateDebut);
				$timestampDebut = mktime(0, 0, 0, $tabDateDeb[1], $tabDateDeb[0], $tabDateDeb[2]);

				$tabDateFin = explode("/", $dateFin);
				$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);

				$timestampDiff = $timestampFin - $timestampDebut;
				$nbreJours = intval($timestampDiff / 86400)+1;

				if($nbreJours <= 0) $nbreJours = 1;


				if(!empty($titre) && !empty($description)) {
					// Traitement de l'enregistrement de l'événement
					$identifiantCommun = time();
					$timeDuJour = $timestampDebut;

					include("../sql_connect.php");

					$datess = $dateDebut . '' . $dateFin ;

					for($i=0 ; $i<$nbreJours ; $i++) {
						$req = "INSERT INTO calendrier VALUES ('', ".date('d', $timeDuJour).", ".date('m', $timeDuJour).", ".date('Y', $timeDuJour).", $identifiantCommun)";
						mysql_query($req) or die(mysql_error());

						$timeDuJour += 86400; // On augmente le timestamp d'un jour
					}

					$req = "INSERT INTO evenements VALUES ($identifiantCommun, '$titre', '$description' , '$dateDebut' , '$dateFin')";
					mysql_query($req) or die(mysql_error());

					mysql_close();
					echo '<ul><li>Evénement enregistré !</li></ul>';
					header('Location:/');
				} else {
					echo '<ul><li>Titre ou description de l\'événement non renseigné.</li></ul>';
				}
			}
			else
			{
				echo '<ul><li>Date de début ou de fin d\'événement non conforme (ex. 12/02/2008).</li></ul>';
			}
		}
	?>

    <!-- Formulaire d'envoi
				<h1>Ajouter un événement</h1>

    <form method="post" action="ajoutevent.php">
    	<table id="tabAjoutEvent">
        	<tr>
            	<td><label>Du : <input type="text" name="debut" value="<?php echo $dateDebut ?>" /></label></td>
                <td><label>Au : <input type="text" name="fin" value="<?php echo $dateFin; ?>" /></label></td>
            </tr>
       		<tr>
       			<td colspan="2"><br/>
                	<label for="titre">Titre de l'événement :</label><br/>
       				<input type="text" name="titre" id="titre" size="30" value="<?php echo $titre ?>" /><br/><br/>
                </td>
       		</tr>
            <tr>
            	<td colspan="2">
       				<label for="description">Description de l'événement :</label><br/>
       				<textarea rows="10" cols="50" id="description" name="description"><?php echo $description ?></textarea>
                </td>
            </tr>
            <tr>
            	<td colspan="2"><input type="submit" name="envoi" value="Envoyer"/></td>
            </tr>
       </table>
    </form>

    <p class="centre"><br/><a href="../index.php">Revenir à l'accueil</a></p>
</body>
</html> -->
