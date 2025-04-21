<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé des Soins et Finances</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Résumé des Soins et Finances par Mois</h1>
    <?php
    $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
    if (!$con) {
        echo "<div style='color: red;'>Vous n'êtes pas connecté à la base de données.</div>";
        exit();
    }

    // Exécution de la requête SQL pour récupérer les données des soins, des dépenses et des versements par mois
    $sql = "
        SELECT
            DATE_FORMAT(p.Date, '%Y-%m') AS Month,
            YEAR(p.Date) AS Year,
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
        ORDER BY Month;
    ";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo "<div style='color: red;'>Erreur lors de l'exécution de la requête SQL : " . mysqli_error($con) . "</div>";
        exit();
    }

    $reste_caisse_par_annee = [];
    $recettes_par_annee = [];
    $versements_par_annee = [];
    $depenses_par_annee = [];

    // Variables pour les totaux généraux
    $total_versements_gen = 0;
    $total_depenses_gen = 0;
    $total_recettes_gen = 0;
    $total_reste_caisse_gen = 0;

    // Générer le tableau HTML
    echo "<table>";
    echo "<tr>
            <th>Mois</th>
            <th>Montant Total</th>
            <th>Total Versements</th>
            <th>Total Dépenses</th>
            <th>Reste en Caisse</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $total_montant = number_format($row['TOTAL_MONTANT'], 0, ',', ' ');
        $total_versements = number_format($row['TOTAL_VERSEMENTS'], 0, ',', ' ');
        $total_depenses = number_format($row['TOTAL_DEPENSES'], 0, ',', ' ');
        $reste_en_caisse = $row['TOTAL_MONTANT'] - $row['TOTAL_VERSEMENTS'] - $row['TOTAL_DEPENSES'];
        $reste_en_caisse_format = number_format($reste_en_caisse, 0, ',', ' ');

        // Ajouter au total de l'année
        if (!isset($reste_caisse_par_annee[$row['Year']])) {
            $reste_caisse_par_annee[$row['Year']] = 0;
            $recettes_par_annee[$row['Year']] = 0;
            $versements_par_annee[$row['Year']] = 0;
            $depenses_par_annee[$row['Year']] = 0;
        }
        $reste_caisse_par_annee[$row['Year']] += $reste_en_caisse;
        $recettes_par_annee[$row['Year']] += $row['TOTAL_MONTANT'];
        $versements_par_annee[$row['Year']] += $row['TOTAL_VERSEMENTS'];
        $depenses_par_annee[$row['Year']] += $row['TOTAL_DEPENSES'];

        // Ajouter aux totaux généraux
        $total_versements_gen += $row['TOTAL_VERSEMENTS'];
        $total_depenses_gen += $row['TOTAL_DEPENSES'];
        $total_recettes_gen += $row['TOTAL_MONTANT'];
        $total_reste_caisse_gen += $reste_en_caisse;

        echo "<tr>
                <td>{$row['Month']}</td>
                <td>{$total_montant}</td>
                <td>{$total_versements}</td>
                <td>{$total_depenses}</td>
                <td>{$reste_en_caisse_format}</td>
              </tr>";
    }

    echo "</table>";

    echo "<h2>Total des Restes en Caisse par Année</h2>";
    echo "<table>";
    echo "<tr><th>Année</th><th>Reste en Caisse</th><th>Total Annuel des Recettes</th><th>Total Annuel des Versements</th><th>Total Annuel des Dépenses</th></tr>";
    foreach ($reste_caisse_par_annee as $year => $total_reste) {
        $total_reste_format = number_format($total_reste, 0, ',', ' ');
        $total_recettes_format = number_format($recettes_par_annee[$year], 0, ',', ' ');
        $total_versements_format = number_format($versements_par_annee[$year], 0, ',', ' ');
        $total_depenses_format = number_format($depenses_par_annee[$year], 0, ',', ' ');
        echo "<tr><td>{$year}</td><td>{$total_reste_format}</td><td>{$total_recettes_format}</td><td>{$total_versements_format}</td><td>{$total_depenses_format}</td></tr>";
    }
    echo "<tr class='total-row'><td>Total</td><td>" . number_format($total_reste_caisse_gen, 0, ',', ' ') . "</td><td>" . number_format($total_recettes_gen, 0, ',', ' ') . "</td><td>" . number_format($total_versements_gen, 0, ',', ' ') . "</td><td>" . number_format($total_depenses_gen, 0, ',', ' ') . "</td></tr>";
    echo "</table>";

    mysqli_close($con);
    ?>
</body>
</html>
