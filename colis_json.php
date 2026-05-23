<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Colis</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>

<?php
// Nom du fichier JSON
$fichier = "colis.json";

// Si le fichier n'existe pas, on le crée avec un tableau vide
if (!file_exists($fichier)) {
    file_put_contents($fichier, json_encode([]));
}

// Lire le fichier JSON et mettre le contenu dans le tableau $tabColis
$tabColis = json_decode(file_get_contents($fichier), true);

// Ajout d'un colis
if (isset($_POST['btnAjouter'])) {
    $numero     = $_POST['numero_suivi'];
    $client     = $_POST['client'];
    $telephone  = $_POST['telephone'];
    $description = $_POST['description'];
    $statut     = $_POST['statut'];

    // Créer un tableau associatif pour le nouveau colis
    $nouveauColis = [
        "numero_suivi" => $numero,
        "client"       => $client,
        "telephone"    => $telephone,
        "description"  => $description,
        "statut"       => $statut
    ];

    // Ajouter le nouveau colis dans le tableau
    $tabColis[] = $nouveauColis;

    // Reconvertir le tableau en JSON et réécrire dans le fichier
    file_put_contents($fichier, json_encode($tabColis, JSON_PRETTY_PRINT));

    // Rafraîchir la page
    header("Location: colis.php");
    exit;
}

// Recherche par numéro de suivi ou téléphone
$recherche = $_GET['recherche'] ?? "";

if ($recherche != "") {
    $tabFiltres = [];
    foreach ($tabColis as $colis) {
        // Vérifier si le numéro de suivi ou le téléphone contient la recherche
        if (str_contains($colis['numero_suivi'], $recherche) || str_contains($colis['telephone'], $recherche)) {
            $tabFiltres[] = $colis;
        }
    }
} else {
    // Pas de recherche : afficher tous les colis
    $tabFiltres = $tabColis;
}
?>

<body>
    <h1 class="text-center text-primary mt-3">Suivi de Colis</h1>

    <div class="container mt-4">
        <div class="row">

            <!-- Formulaire d'ajout -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">Ajouter un Colis</div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-2">
                                <label>Numéro de suivi</label>
                                <input type="text" class="form-control" name="numero_suivi" required>
                            </div>
                            <div class="mb-2">
                                <label>Nom du client</label>
                                <input type="text" class="form-control" name="client" required>
                            </div>
                            <div class="mb-2">
                                <label>Téléphone</label>
                                <input type="text" class="form-control" name="telephone" required>
                            </div>
                            <div class="mb-2">
                                <label>Description</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <div class="mb-2">
                                <label>Statut</label>
                                <select class="form-control" name="statut">
                                    <option value="En attente">En attente</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Livré">Livré</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" name="btnAjouter">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Liste des colis -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">Liste des Colis</div>
                    <div class="card-body">

                        <!-- Barre de recherche -->
                        <form method="get" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="recherche"
                                       placeholder="Rechercher par numéro ou téléphone..."
                                       value="<?= $recherche ?>">
                                <button class="btn btn-secondary" type="submit">Rechercher</button>
                            </div>
                        </form>

                        <!-- Tableau des colis -->
                        <table class="table table-bordered">
                            <tr>
                                <th>#</th>
                                <th>Numéro de suivi</th>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Description</th>
                                <th>Statut</th>
                            </tr>
                            <?php foreach ($tabFiltres as $key => $colis) { ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $colis['numero_suivi'] ?></td>
                                    <td><?= $colis['client'] ?></td>
                                    <td><?= $colis['telephone'] ?></td>
                                    <td><?= $colis['description'] ?></td>
                                    <td><?= $colis['statut'] ?></td>
                                </tr>
                            <?php } ?>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
