
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Statistiques des Patients</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
<?php include "entete.php"; ?>

<div class="container mx-auto p-4">
<h1 class="text-4xl font-bold text-blue-400 mb-8 text-center">Statistiques des Patients</h1>

    <?php 
    // Connexion à la base de données
    $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
    if(!$con){
        echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
        exit();
    }

    // Exécution de la requête SQL pour récupérer les données des soins, des dépenses et des versements par mois
    $sql = "
    SELECT
        DATE_FORMAT(p.Date, '%Y-%m') AS Month,
        COUNT(DISTINCT p.Numero_Patient) AS TOTAL_PATIENTS,
        COUNT(CASE WHEN p.Age_Patient < 14 THEN 1 END) AS PATIENTS_MOINS_14,
        COUNT(CASE WHEN p.Age_Patient >= 14 THEN 1 END) AS PATIENTS_14_ET_PLUS,
        COUNT(CASE WHEN p.Types_de_Soins = 'OCE' THEN 1 END) AS SOINS_OCE,
        SUM(CASE WHEN p.Types_de_Soins = 'OCE' THEN p.Montant ELSE 0 END) AS MONTANT_OCE,
        COUNT(CASE WHEN p.Types_de_Soins = 'ENDO' THEN 1 END) AS SOINS_ENDO,
        SUM(CASE WHEN p.Types_de_Soins = 'ENDO' THEN p.Montant ELSE 0 END) AS MONTANT_ENDO,
        COUNT(CASE WHEN p.Types_de_Soins = 'CHIR' THEN 1 END) AS CHIRURGIE,
        SUM(CASE WHEN p.Types_de_Soins = 'CHIR' THEN p.Montant ELSE 0 END) AS MONTANT_CHIR,
        COUNT(CASE WHEN p.Types_de_Soins = 'PARO' THEN 1 END) AS SOINS_PARODONTALE,
        SUM(CASE WHEN p.Types_de_Soins = 'PARO' THEN p.Montant ELSE 0 END) AS MONTANT_PARO,
        COUNT(CASE WHEN p.Types_de_Soins = 'PC' THEN 1 END) AS PC,
        SUM(CASE WHEN p.Types_de_Soins = 'PC' THEN p.Montant ELSE 0 END) AS MONTANT_PC,
        COUNT(CASE WHEN p.Types_de_Soins = 'PA' THEN 1 END) AS PA,
        SUM(CASE WHEN p.Types_de_Soins = 'PA' THEN p.Montant ELSE 0 END) AS MONTANT_PA,
        COUNT(CASE WHEN p.Types_de_Soins = 'AUTRES' THEN 1 END) AS AUTRES,
        SUM(CASE WHEN p.Types_de_Soins = 'AUTRES' THEN p.Montant ELSE 0 END) AS MONTANT_AUTRES,
        SUM(p.Montant) AS TOTAL_MONTANT,
        COALESCE(v.TOTAL_VERSEMENTS, 0) AS TOTAL_VERSEMENTS,
        COALESCE(d.TOTAL_DEPENSES, 0) AS TOTAL_DEPENSES
    FROM patient p
    LEFT JOIN (
        SELECT
            DATE_FORMAT(Date_Versement, '%Y-%m') AS Monthvers,
            SUM(Montant_Versement) AS TOTAL_VERSEMENTS
        FROM versement
        GROUP BY Monthvers
    ) v ON DATE_FORMAT(p.Date, '%Y-%m') = v.Monthvers
    LEFT JOIN (
        SELECT
            DATE_FORMAT(Date_Depense, '%Y-%m') AS Months,
            SUM(Montant_Depense) AS TOTAL_DEPENSES
        FROM depense
        GROUP BY Months
    ) d ON DATE_FORMAT(p.Date, '%Y-%m') = d.Months
    GROUP BY Month
    ORDER BY Month DESC;
";



 $sql1 ="SELECT
DATE_FORMAT(Date_Depense, '%Y-%m') AS Month,
SUM(Montant_Depense) AS TOTAL_DEPENSES
FROM depense
GROUP BY Month
ORDER BY Month";


    $result = $con->query($sql);
    $result1 = $con->query($sql1);
    // Vérifier s'il y a des résultats
    if (($result->num_rows > 0) ||  ($result1->num_rows > 0)){
        while($row = $result->fetch_assoc()) {
            echo "<h3 class='text-2xl font-bold mt-6 mb-4 text-blue-600 text-center'>Mois de " . date("F Y", strtotime($row['Month'] . "-01")) . "</h3>";
            echo "<div class='mb-4 p-4 bg-white rounded shadow'>";
            echo "<p class='text-lg'><strong>Nombre total de patients : </strong>&nbsp;&nbsp;&nbsp;&nbsp;{$row['TOTAL_PATIENTS']}</p>";
            echo "<p class='text-lg'><strong>Patients de moins de 14 ans : </strong>&nbsp;&nbsp;&nbsp;&nbsp;{$row['PATIENTS_MOINS_14']}</p>";
            echo "<p class='text-lg'><strong>Patients de 14 ans et plus : </strong>&nbsp;&nbsp;&nbsp;&nbsp;{$row['PATIENTS_14_ET_PLUS']}</p>";
            
            echo "</div>";
            echo "<div class='overflow-x-auto'>";
            echo "<table class='table-auto w-full mb-6 border-collapse border border-gray-300'>";
            echo "<thead>
                    <tr class='bg-blue-200'>
                        <th class='border border-gray-300 px-4 py-2 text-center'>Type de Soin</th>
                        <th class='border border-gray-300 px-4 py-2 text-center'>Nombre</th>
                        <th class='border border-gray-300 px-4 py-2 text-center'>Montant</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            echo "<tr class='bg-gray-100'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>OCE</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['SOINS_OCE']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_OCE'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='bg-gray-50'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>ENDO</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['SOINS_ENDO']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_ENDO'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='bg-gray-100'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>CHIR</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['CHIRURGIE']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_CHIR'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='bg-gray-50'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>PARO</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['SOINS_PARODONTALE']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_PARO'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='bg-gray-100'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>PC</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['PC']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_PC'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='bg-gray-50'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>PA</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['PA']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_PA'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='bg-gray-100'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>AUTRES</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>{$row['AUTRES']}</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . number_format($row['MONTANT_AUTRES'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='font-bold' style='background-color: #ADD8E6;'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>MONTANT TOTAL</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center' colspan='2'>" . number_format($row['TOTAL_MONTANT'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='font-bold' style='background-color: #ADD8E6;'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>Total des Versements</td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center' colspan='2'>" . number_format($row['TOTAL_VERSEMENTS'], 0, ',', ' ') . "</td>";
            echo "</tr>";
            echo "<tr class='font-bold' style='background-color: #ADD8E6;'>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center'>Total des Dépenses </td>";
            echo "<td class='border border-gray-300 px-4 py-2 text-center' colspan='2'>". number_format($row['TOTAL_DEPENSES'], 0, ',', ' ') ."</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } 
    } else {
        echo "<div class='text-red-500'>Aucun résultat trouvé.</div>";
    }

    // Fermer la connexion
    $con->close();
    ?>
</div>
</body>
</html>
