<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Liste des dépenses</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Ajout de styles personnalisés */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }
        .container {
            max-width: 1200px; /* Augmenter la largeur pour s'adapter à l'écran */
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-x: auto; /* Permet de faire défiler horizontalement si nécessaire */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .date-column {
            min-width: 120px; /* Augmenter la largeur minimale de la colonne date */
        }
        .month-row {
            background-color: rgba(59, 130, 246, 0.5); /* Gris foncé pour les lignes de mois */
            color: #ffffff; /* Texte blanc */
            font-weight: bold;
            text-align: center;
            font-size: 1.6em;
        }
        .month-row td {
            font-family: 'Arial', sans-serif; /* Police de caractères pour les mois */
        }
        .age-column {
            width: 80px; /* Largeur réduite pour la colonne Age Patient */
        }
        .rap-column {
            min-width: 100px; /* Augmenter la largeur minimale de la colonne RAP */
        }
        .action-column {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .action-column a {
            display: block;
            padding: 6px 12px;
            background-color: #B0C4DE;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .action-column a:hover {
            background-color: #6B7280; /* Couleur grise plus foncée au survol */
        }
    </style>
</head>
<body>
<?php include "entete.php"; ?>
<div class="container">
<h1 class="text-3xl font-bold  mb-6"> LISTE DES VERSEMENT</h1>
    <?php 
        // Connexion à la base de données
        $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
        if (!$con) {
            echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
            exit();
        }

        // Exécution de la requête SQL
        $sql = "SELECT *
                FROM versement
                ORDER BY Date_Versement DESC";
        $result = $con->query($sql);

        // Vérifier s'il y a des résultats
        if ($result->num_rows > 0) {
            $current_month = null;
            $current_date = null;
            $total_month = 0;

            // Parcourir les résultats
            while ($row = $result->fetch_assoc()) {
                $Date_Versement = $row['Date_Versement'];
                $month = date('F Y', strtotime($Date_Versement)); // Format mois complet et année

                // Ajouter une ligne pour chaque nouveau mois
                if ($month != $current_month) {
                    // Afficher le total du mois précédent s'il existe
                    if ($current_month !== null) {
                        echo "<tr class='total-row'>
                                <td colspan='2'><b>Total</b></td>
                                <td><b>$total_month</b></td>
                                <td colspan='2'></td>
                              </tr>";
                    }

                    // Commencer une nouvelle table pour le nouveau mois
                    echo "<table class='monthly-table'>
                            <tr class='month-row'>
                                <td colspan='5'><b>Mois: $month</b></td>
                            </tr>
                            <tr>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Intitulé</th>
                                <th class='action-column' colspan='2'>ACTION</th>
                            </tr>";
                    $current_month = $month;
                    $total_month = 0;
                }

                // Calculer le total du mois
                $total_month += $row['Montant_Versement'];

                // Afficher les données de chaque ligne
                echo "<tr>
                        <td>{$row['Montant_Versement']}</td>
                        <td>{$row['Date_Versement']}</td>
                        <td>{$row['Remarque_Versement']}</td>
                        <td class='action-column'><a href='../AMPIASANA/modifier_Versement.php?Id_Versement={$row['Id_Versement']}'>MOD</a></td>
                        <td class='action-column'><a href='../AMPIASANA/suprimer_Versement.php?Id_Versement={$row['Id_Versement']}'>DEL</a></td>
                      </tr>";
            }

            // Afficher le total du dernier mois
            echo "<tr class='total-row'>
                    <td colspan='2'><b>Total</b></td>
                    <td><b>$total_month</b></td>
                    <td colspan='2'></td>
                  </tr>";
            
            echo "</table>"; // Fermer la dernière table

        } else {
            echo "Aucun résultat trouvé.";
        }

        // Fermer la connexion à la base de données
        $con->close();
    ?>
</div> <!-- Fin de la balise div container -->




</body>
</html>