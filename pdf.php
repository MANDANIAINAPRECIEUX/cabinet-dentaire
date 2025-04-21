<?php
//require_once('tcpdf/tcpdf.php'); // Assurez-vous que le chemin est correct
require 'vendor/autoload.php';

// Création de l'objet TCPDF
$pdf = new TCPDF();
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
            <th>Total Versements</th>
            <th>Total Dépenses</th>
            <th>Reste en Caisse</th>
          </tr>';

$total_versements_gen = 0;
$total_depenses_gen = 0;
$total_recettes_gen = 0;
$total_reste_caisse_gen = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total_montant = number_format($row['TOTAL_MONTANT'], 0, ',', ' ');
    $total_versements = number_format($row['TOTAL_VERSEMENTS'], 0, ',', ' ');
    $total_depenses = number_format($row['TOTAL_DEPENSES'], 0, ',', ' ');
    $reste_en_caisse = $row['TOTAL_MONTANT'] - $row['TOTAL_VERSEMENTS'] - $row['TOTAL_DEPENSES'];
    $reste_en_caisse_format = number_format($reste_en_caisse, 0, ',', ' ');

    $total_versements_gen += $row['TOTAL_VERSEMENTS'];
    $total_depenses_gen += $row['TOTAL_DEPENSES'];
    $total_recettes_gen += $row['TOTAL_MONTANT'];
    $total_reste_caisse_gen += $reste_en_caisse;

    $html .= "<tr>
                <td>{$row['Month']}</td>
                <td>{$total_montant}</td>
                <td>{$total_versements}</td>
                <td>{$total_depenses}</td>
                <td>{$reste_en_caisse_format}</td>
              </tr>";
}

$html .= "<tr>
            <td><strong>Total</strong></td>
            <td>" . number_format($total_recettes_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_versements_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_depenses_gen, 0, ',', ' ') . "</td>
            <td>" . number_format($total_reste_caisse_gen, 0, ',', ' ') . "</td>
          </tr>";
$html .= '</table>';

// Imprimer le tableau HTML dans le PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Clôturer la connexion à la base de données
mysqli_close($con);

// Générer le PDF et l'afficher
$pdf->Output('rapport.pdf', 'I');
?>
