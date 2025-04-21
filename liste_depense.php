<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Liste des Dépenses</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-x: auto;
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
            min-width: 120px;
        }
        .month-row {
            background-color: rgba(59, 130, 246, 0.5);
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 1.6em;
        }
        .month-row td {
            font-family: 'Arial', sans-serif;
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
            background-color: #6B7280;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<?php include "entete.php"; ?>
<div class="container mt-4">
    <h1 class="text-3xl font-bold mb-6">LISTE DES DEPENSES</h1>
    <?php 
        // Connexion à la base de données
        $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
        if(!$con){
            echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
            exit();
        }
    ?>
    <?php
    // Exécution de la requête SQL
    $sql = "SELECT * FROM depense ORDER BY Date_Depense DESC";
    $result = $con->query($sql);

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        $current_month = null;
        $current_date = null;
        $total_mois = 0;

        // Parcourir les résultats
        while($row = $result->fetch_assoc()) {
            $Date_Depense = $row['Date_Depense'];
            $month = date('F Y', strtotime($Date_Depense)); // Format mois complet et année
            
            // Ajouter une ligne pour chaque nouveau mois
            if ($month != $current_month) {
                if ($current_month !== null) {
                    // Afficher le total du mois précédent
                    echo "<tr class='total-row'>
                            <td colspan='2'></td>
                            <td>Total:</td>
                            <td colspan='2'>" . number_format($total_mois, 0, ',', ' ') . "</td>
                          </tr>";
                    echo "</table>"; // Fermer le tableau précédent s'il existe
                }
                echo "<table border='1'>
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
                $total_mois = 0; // Réinitialiser le total pour le nouveau mois
            }
            
            // Ajouter une ligne pour chaque nouveau jour
            if ($Date_Depense != $current_date) {
                echo "<tr>
                        <td class='date-column' colspan='5' style='text-align: center; background-color: #ADD8E6; color: #ffffff;'><b>Date: $Date_Depense</b></td>
                      </tr>";
                $current_date = $Date_Depense;
            }

            // Afficher les données de chaque ligne
            echo "<tr>
                    <td>" . number_format($row['Montant_Depense'], 0, ',', ' ') . "</td>
                    <td>{$row['Date_Depense']}</td>
                    <td>{$row['Remarque_Depense']}</td>
                    <td class='action-column'><a href='../AMPIASANA/modifier_depense.php?Id_Depense={$row['Id_Depense']}'>MOD</a></td>
                    <td class='action-column'><a href='../AMPIASANA/supprimer_depense.php?Id_Depense={$row['Id_Depense']}'>DEL</a></td>
                  </tr>";
            $total_mois += $row['Montant_Depense'];
        }
        // Afficher le total du dernier mois
        echo "<tr class='total-row'>
                <td colspan='2'></td>
                <td>Total:</td>
                <td colspan='2'>" . number_format($total_mois, 0, ',', ' ') . "</td>
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