<?php
require 'vendor/autoload.php'; // Inclut l'autoloader de Composer

// Création de l'objet TCPDF
$pdf = new \TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Ajouter un titre
$pdf->Cell(0, 10, 'Résumé des Soins et Finances par Mois', 0, 1, 'C');

// Connexion à la base de données
$con = mysqli_connect("localhost", "root", "manda", "ampiasana");
if (!$con) {
    $pdf->Cell(0, 10, 'Erreur de connexion à la base de données.', 0, 1, 'C');
    $pdf->Output('rapport.pdf', 'I');
    exit();
}

// Exécution de la requête SQL
$sql = "
    SELECT
        DATE_FORMAT(p.Date, '%Y-%m') AS Month,
        YEAR(p.Date) AS Year,
        COUNT(DISTINCT p.Numero_Patient) AS TOTAL_PATIENTS,
        COUNT(CASE WHEN p.Age_Patient < 14 THEN 1 END) AS PATIENTS_MOINS_14,
        COUNT(CASE WHEN p.Age_Patient >= 14 THEN 1 END) AS PATIENTS_14_ET_PLUS,
        COUNT(CASE WHEN p.Types_de_Soins IN ('OCE', 'ENDO', 'CHIR', 'PARO', 'AUTRES') THEN 1 END) AS SOINS_SON,
        SUM(CASE WHEN p.Types_de_Soins IN ('OCE', 'ENDO', 'CHIR', 'PARO', 'AUTRES') THEN p.Montant ELSE 0 END) AS MONTANT_SON,
        COUNT(CASE WHEN p.Types_de_Soins IN ('PA', 'PC') THEN 1 END) AS SOINS_PROTHESES,
        SUM(CASE WHEN p.Types_de_Soins IN ('PA', 'PC') THEN p.Montant ELSE 0 END) AS MONTANT_PROTHESES,
        SUM(p.Montant) AS TOTAL_MONTANT,
        -- Calcul des parts en pourcentage
        ROUND(
            (SUM(CASE WHEN p.Types_de_Soins IN ('OCE', 'ENDO', 'CHIR', 'PARO', 'AUTRES') THEN p.Montant ELSE 0 END) *35) / 100, 2
        ) AS PART_SOINS,
        ROUND(
            (SUM(CASE WHEN p.Types_de_Soins IN ('PA', 'PC') THEN p.Montant ELSE 0 END) *55) / 100, 2
        ) AS PART_PROTHESES
    FROM patient p
    GROUP BY Month
    ORDER BY Month;
";

$result = mysqli_query($con, $sql);

if (!$result) {
    $pdf->Cell(0, 10, 'Erreur lors de l\'exécution de la requête SQL.', 0, 1, 'C');
    $pdf->Output('rapport.pdf', 'I');
    exit();
}

// Création du tableau HTML pour le PDF
$html = '<h2>Résumé des Soins et Finances par Mois</h2>';
$html .= '<table border="1" cellpadding="5">';
$html .= '<tr>
            <th>Mois</th>
            <th>Montant Total</th>
            <th>Montant Soins</th>
            <th>Part Soins</th>
            <th>Montant Soins Prothèses</th>
            <th>Part Prothèses</th>
          </tr>';

$total_recettes_gen = 0;
$total_montant_son_gen = 0;
$total_montant_protheses_gen = 0;
$total_part_soins_gen = 0;
$total_part_protheses_gen = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total_montant = number_format($row['TOTAL_MONTANT'], 0, ',', ' ');
    $montant_son = number_format($row['MONTANT_SON'], 0, ',', ' ');
    $part_soin = number_format($row['PART_SOINS'], 0, ',', ' ');
    $montant_protheses = number_format($row['MONTANT_PROTHESES'], 0, ',', ' ');
    $part_protheses = number_format($row['PART_PROTHESES'], 0, ',', ' ');

    $total_recettes_gen += $row['TOTAL_MONTANT'];
    $total_montant_son_gen += $row['MONTANT_SON'];
    $total_montant_protheses_gen += $row['MONTANT_PROTHESES'];
    $total_part_soins_gen += $row['PART_SOINS'];
    $total_part_protheses_gen += $row['PART_PROTHESES'];

    $html .= "<tr>
                <td>{$row['Month']}</td>
                <td>{$total_montant}</td>
                <td>{$montant_son}</td>
                <td>{$part_soin}</td>
                <td>{$montant_protheses}</td>
                <td>{$part_protheses}</td>
              </tr>";
}

$html .= "<tr>
            <td><strong>Total</strong></td>
            <td>" . number_format($total_recettes_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_montant_son_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_part_soins_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_montant_protheses_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_part_protheses_gen, 0, ',', ' ') . "</td>
          </tr>";
$html .= '</table>';

// Imprimer le tableau HTML dans le PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Clôturer la connexion à la base de données
mysqli_close($con);

// Générer le PDF et l'afficher
$pdf->Output('rapport.pdf', 'I');
?>
