<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=ampiasana;', 'root', 'manda');

$allusers = null;
if(isset($_GET['s']) && !empty($_GET['s'])){
    $recherche = htmlspecialchars($_GET['s']);
    $allusers = $bdd->query("SELECT * FROM patient WHERE Numero_Patient LIKE '%$recherche%' OR Nom_Patient LIKE '%$recherche%' ORDER BY Numero_Patient DESC");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rechercher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            background-color: #f3f4f6;
            color: #6B7280;
        }
        #recherche {
            margin: 20px auto;
            width: 50%;
            text-align: center;
        }
        #recherche input[type="search"] {
            padding: 10px;
            border: 2px solid #B0C4DE;
            border-radius: 5px;
            width: 70%;
        }
        #recherche input[type="submit"] {
            padding: 10px 20px;
            background-color: rgba(59, 130, 246, 0.5);
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            margin-left: 10px;
        }
        #table-result {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        #table-result, #table-header, #table-cell {
            border: 1px solid #B0C4DE;
        }
        #table-header {
            background-color: rgba(59, 130, 246, 0.5);
            color: white;
            padding: 10px;
        }
        #table-cell {
            padding: 10px;
            text-align: center;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div id="recherche">
        <form method="GET" id="">
            <input type="search" name="s" placeholder="Recherche d'un utilisateur">
            <input type="submit" value="Recherche">
        </form>
    </div>

    <?php if($allusers): ?>
        <table id="table-result">
            <tr>
                <th id="table-header">Nom_Patient</th>
                <th id="table-header">Age_Patient</th>
                <th id="table-header">Sexe_Patient</th>
                <th id="table-header">Diagnostique</th>
                <th id="table-header">Types_de_Soins</th>
                <th id="table-header">Montant</th>
                <th id="table-header">RAP</th>
                <th id="table-header">REMARQUE</th>
                <th id="table-header">Date</th>
            </tr>
            <section class="afficher_utilisateur">
                <?php
                if($allusers->rowCount() > 0){
                    while($row = $allusers->fetch()){
                        ?>
                        <tr>   
                            <td id="table-cell"><?=$row['Nom_Patient']?></td>
                            <td id="table-cell"><?=$row['Age_Patient']?></td>
                            <td id="table-cell"><?=$row['Sexe_Patient']?></td>
                            <td id="table-cell"><?=$row['diagnostique']?></td>
                            <td id="table-cell"><?=$row['Types_de_Soins']?></td>
                            <td id="table-cell"><?=$row['Montant']?></td>
                            <td id="table-cell"><?=$row['RAP']?></td>
                            <td id="table-cell"><?=$row['REMARQUE']?></td>
                            <td id="table-cell"><?=$row['Date']?></td>
                        </tr>
                    <?php   
                    }
                }else{
                    ?>
                    <tr>
                        <td id="table-cell" colspan="9">Aucun utilisateur trouvé</td>
                    </tr>
                    <?php
                }
                ?>
            </section>
        </table>
    <?php endif; ?>
</body>
</html>
