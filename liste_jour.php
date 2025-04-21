<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Liste des Patients</title> 
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
<?php include "recherche.php"; ?>
<div class="container mt-4 ">
    <?php 
        // Connexion à la base de données
        $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
        if(!$con){
            echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
            exit();
        }

        // Exécution de la requête SQL
        $sql = "SELECT * FROM patient ORDER BY Date DESC";
        $result = $con->query($sql);

        // Vérifier s'il y a des résultats
        if ($result->num_rows > 0) {
            $current_month = null;
            $current_date = null;
            $monthly_total = 0;

            // Parcourir les résultats
            while($row = $result->fetch_assoc()) {
                $date = $row['Date'];
                $month = date('F Y', strtotime($date)); // Format mois complet et année
                
                // Ajouter une ligne pour chaque nouveau mois
                if ($month != $current_month) {
                    if ($current_month !== null) {
                        echo "<tr>
                                <td colspan='11' style='text-align: center; background-color: #e0e0e0;'><b>Total Mensuel: $monthly_total</b></td>
                              </tr>";
                        echo "</table>"; // Fermer le tableau précédent s'il existe
                        $monthly_total = 0; // Réinitialiser le total mensuel pour le nouveau mois
                    }
                    echo "<table border='1'>
                            <tr class='month-row'>
                                <td colspan='11'><b>Mois: $month</b></td>
                            </tr>";
                    $current_month = $month;
                    $current_date = null; // Réinitialiser la date actuelle pour le nouveau mois
                }
                
                // Ajouter une ligne pour chaque nouveau jour
                if ($date != $current_date) {
                    if ($current_date !== null) {
                        echo "</table>"; // Fermer le tableau précédent s'il existe
                    }

                    // Calculer le montant total pour la date actuelle
                    $total_sql = "SELECT SUM(Montant) as total FROM patient WHERE Date = '$date'";
                    $total_result = $con->query($total_sql);
                    $total_row = $total_result->fetch_assoc();
                    $total_amount = $total_row['total'];

                    echo "<table border='1'>
                            <tr>
                                <th>Date</th>
                                <th>Nom Patient</th>
                                <th class='age-column'>Age</th>
                                <th>Sexe</th>
                                <th>Diagnostic</th>
                                <th>Types de Soins</th>
                                <th>Montant</th>
                                <th class='rap-column'>RAP</th>
                                <th>Remarque</th>
                                <th class='action-column' colspan='2'>ACTION</th>
                            </tr>";
                    echo "<tr>
                            <td class='date-column' colspan='11' style='text-align: right; background-color: #ADD8E6; color: #ffffff;'><b>Date: $date</b> - Montant Total: $total_amount</td>
                          </tr>";
                    $current_date = $date;
                }

                // Ajouter au total mensuel
                $monthly_total += $row['Montant'];

                // Afficher les données de chaque ligne
                echo "<tr>
                        <td>$date</td>
                        <td>{$row['Nom_Patient']}</td>
                        <td>{$row['Age_Patient']}</td>
                        <td>{$row['Sexe_Patient']}</td>
                        <td>{$row['diagnostique']}</td>
                        <td>{$row['Types_de_Soins']}</td>
                        <td>{$row['Montant']}</td>
                        <td>{$row['RAP']}</td>
                        <td>{$row['REMARQUE']}</td>
                        <td class='action-column'><a href='../AMPIASANA/modifier_patient.php?Numero_Patient={$row['Numero_Patient']}'>MOD</a></td>
                        <td class='action-column'><a href='suprimer.php?Numero_Patient={$row['Numero_Patient']}'>DEL</a></td>
                      </tr>";
            }
            // Afficher le total mensuel pour le dernier mois
            echo "<tr>
                    <td colspan='11' style='text-align: center; background-color: #e0e0e0;'><b>Total   Mensuel : $monthly_total</b></td>
                  </tr>";
            echo "</table>";
        } else {
            echo "Aucun résultat trouvé.";
        }

        // Fermer la connexion
        $con->close();
    ?>
</div>


</body>
</html>
