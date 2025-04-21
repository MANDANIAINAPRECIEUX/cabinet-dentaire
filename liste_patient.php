<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Liste des Patients</title> 
    <meta name='viewport' content='width=device-width, initial-scale=1'>
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
        .mod, .sup {
            text-align: center;
        }
        .mod a {
            display: block;
            padding: 6px 12px;
            background-color: #B0C4DE;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .sup a {
            display: block;
            padding: 6px 12px;
            background-color: #191970; 
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 4px rgba(0.5,0.5,0.5,0.5); /* Ombre légère */
        }
        .sup a:hover {
            background-color: #c53030; /* Couleur rouge plus foncée au survol */
        }
        .date-column {
            min-width: 120px; /* Augmenter la largeur minimale de la colonne date */
        }
    </style>
</head>
<body>
<?php include "entete.php"; ?>
<?php 
    // Connexion à la base de données
    $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
    if(!$con){
        echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
        exit();
    }
?>
<div class="container mt-4 ">
    <h1 class="text-2xl font-bold mb-4">Liste des Patients</h1>

    <table>
        <thead>
            <tr>
                <th>Numéro Patient</th>
                <th>Nom Patient</th> 
                <th>Âge Patient</th>
                <th>Sexe Patient</th>
                <th>Diagnostic</th>
                <th>Types de Soins</th>
                <th>Montant</th> 
                <th>RAP</th>
                <th>Remarque</th>
                <th class="date-column">Date</th> <!-- Utilisation de la classe pour la colonne date -->
                <th colspan="2">Actions</th> <!-- Colspan pour fusionner les cellules des actions -->
            </tr>
        </thead>
        <tbody>
            <?php 
                // Requête pour afficher la liste des patients
                $req = mysqli_query($con , "SELECT * FROM patient ORDER BY Date ASC");
                if(mysqli_num_rows($req) == 0){
                    // S'il n'y a pas de patients dans la base de données
                    echo "<tr><td colspan='11'>Il n'y a pas encore de patients dans la base de données.</td></tr>";
                } else {
                    // Affichage de la liste des patients
                    while($row=mysqli_fetch_assoc($req)){
            ?>
                    <tr>
                        <td><?=$row['Numero_Patient']?></td>
                        <td><?=$row['Nom_Patient']?></td>
                        <td><?=$row['Age_Patient']?></td>
                        <td><?=$row['Sexe_Patient']?></td>
                        <td><?=$row['diagnostique']?></td>
                        <td><?=$row['Types_de_Soins']?></td>
                        <td><?=$row['Montant']?></td>
                        <td><?=$row['RAP']?></td>
                        <td><?=$row['REMARQUE']?></td>
                        <td><?=$row['Date']?></td>
                        <!-- Liens pour modifier et supprimer -->
                        <td class="mod"><a href="../AMPIASANA/modifier_patient.php?Numero_Patient=<?=$row['Numero_Patient']?>">MODIFIER</a></td>
                        <td class="sup"><a href="suprimer.php?Numero_Patient=<?=$row['Numero_Patient']?>">SUPPRIMER</a></td>
                    </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>

</div>
</body>
</html>
