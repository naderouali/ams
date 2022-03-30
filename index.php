<?php include 'config.php' ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Evaluation</title>
	<meta name="description" content="" />
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
	<script src="./dist/js/functions.js"></script>
</head>
<body>
	<section class="container mt-4">
		<div class="row">
			<table class="table col table-bordered table-hover">
				<thead class="thead-dark">
					<tr>
						<th><a class="nav-link" href="#" onclick="sortButton()">Nom Produit</a></th>
						<th><a class="nav-link" href="#" onclick="sortButton()">Date d'ajout</a></th>
						<th><a class="nav-link" href="#" onclick="sortButton()">Depuis <small>(en jours)</small></a></th>
						<th><a class="nav-link" href="#" onclick="sortButton()">Stock</a></th>
						<th><a class="nav-link" href="#" onclick="sortButton()">Chiffre d'affaire</a></th>
					</tr>
				</thead>
				<tbody id="elements" >
					<?php 
					$selectPrd=$bdd->prepare("SELECT * FROM produits");
					$selectPrd->execute(array());
					while ($prd = $selectPrd->fetch(PDO::FETCH_OBJ)){

						$dateOrigine = new DateTime($prd->dateAjout);
						$maintenant = new DateTime("now");
						$nbJour = date_diff($dateOrigine, $maintenant);

						$stockAff = 0;
						$ca = 0;

						$selectStock=$bdd->prepare("SELECT * FROM stock WHERE idPrd = ?");
						$selectStock->execute(array($prd->idPrd));
						while ($stock = $selectStock->fetch(PDO::FETCH_OBJ)){
							if($stock->type == "stock"){
								$stockAff = $stockAff + $stock->qt;
							}
							elseif ($stock->type == "vente") {
								$stockAff = $stockAff - $stock->qt;
								$ca = $ca + ($prd->prix * $stock->qt);
							}
						}

						?>
						<tr>
							<td><?= $prd->nom  ?></td>
							<td><?= date("d/m/Y", strtotime($prd->dateAjout))  ?></td>
							<td><?= $nbJour->format('%a jour(s)') ?></td>
							<td><?= $stockAff  ?></td>
							<td><?= $ca ?> €</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</section>
	
	<script src="dist/js/jquery.min.js"></script>
	<script src="dist/js/bootstrap.min.js"></script>
	
</body>
</html>