<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATIENT</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<?php 
    // Connexion à la base de données
    $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
    if(!$con){
        echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
        exit();
    }
?>
<?php
    // Vérification si Numero_Patient est défini dans l'URL
   
        $Numero_Patient = $_GET['Numero_Patient']; 
        // Requête pour afficher les infos d'un patient
        $req = mysqli_query($con , "SELECT * FROM patient WHERE Numero_Patient = $Numero_Patient");
        $row = mysqli_fetch_assoc($req);
       
    // Vérifier si le formulaire a été soumis
    if(isset($_POST['button'])) {
        // Extraction des informations envoyées dans des variables par la méthode POST 
        extract($_POST);
        // Vérifier que tous les champs ont été remplis
        if(isset($Nom_Patient) && isset($Age_Patient) && isset($Sexe_Patient) && isset($diagnostique) && isset($Types_de_Soins) && isset($Montant) && isset($RAP) && isset($REMARQUE) && isset($Date)){
            // Requête de modification
            $req = mysqli_query($con, "UPDATE patient SET Nom_Patient = '$Nom_Patient', Age_Patient = '$Age_Patient', Sexe_Patient = '$Sexe_Patient', diagnostique = '$diagnostique', Types_de_Soins = '$Types_de_Soins', Montant = '$Montant', RAP = '$RAP', REMARQUE = '$REMARQUE', Date = '$Date' WHERE Numero_Patient = $Numero_Patient");
            if($req){
                // Si la requête a été effectuée avec succès, on fait une redirection
                header("location:liste_jour.php");
            } else {
                $message = "Patient non modifié";
            }
        } else {
            $message = "Veuillez remplir tous les champs!";
        }
    }
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold underline mb-6">PATIENT</h1>
    <?php if (isset($message)) echo $message; ?>
    <form action="" method="POST">
        <div class="mb-4">
            <label for="Date" class="block text-gray-700">DATE</label>
            <input type="date" id="Date" name="Date" value="<?= $row['Date']; ?>" class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="Numero_Patient" class="block text-gray-700">NUMERO PATIENT</label>
            <input type="text" id="Numero_Patient" name="Numero_Patient" value="<?= $row['Numero_Patient']; ?>" readonly class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="Nom_Patient" class="block text-gray-700">NOM DU PATIENT</label>
            <input type="text" id="Nom_Patient" name="Nom_Patient" value="<?= $row['Nom_Patient']; ?>" required class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="Age_Patient" class="block text-gray-700">AGE DU PATIENT</label>
            <input type="number" id="Age_Patient" name="Age_Patient" value="<?= $row['Age_Patient']; ?>" class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="Sexe_Patient" class="block text-gray-700">GENRE DU PATIENT</label>
            <select id="Sexe_Patient" name="Sexe_Patient" class="border rounded w-full py-2 px-3">
                <option value="M" <?php if($row['Sexe_Patient'] === 'M') echo 'selected'; ?>>M</option>
                <option value="F" <?php if($row['Sexe_Patient'] === 'F') echo 'selected'; ?>>F</option>
                <option value="Autre" <?php if($row['Sexe_Patient'] === 'Autre') echo 'selected'; ?>>Autre</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="diagnostique" class="block text-gray-700">DIAGNOSTIQUE</label>
            <input type="text" id="diagnostique" name="diagnostique" value="<?= $row['diagnostique']; ?>" class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="Types_de_Soins" class="block text-gray-700">SOINS</label>
            <select name="Types_de_Soins" id="Types_de_Soins" class="border rounded w-full py-2 px-3">
                <option value="OCE" <?php if($row['Types_de_Soins'] === 'OCE') echo 'selected'; ?>>OCE</option>
                <option value="ENDO" <?php if($row['Types_de_Soins'] === 'ENDO') echo 'selected'; ?>>ENDO</option>
                <option value="CHIR" <?php if($row['Types_de_Soins'] === 'CHIR') echo 'selected'; ?>>CHIR</option>
                <option value="PARO" <?php if($row['Types_de_Soins'] === 'PARO') echo 'selected'; ?>>PARO</option>
                <option value="PC" <?php if($row['Types_de_Soins'] === 'PC') echo 'selected'; ?>>PC</option>
                <option value="PA" <?php if($row['Types_de_Soins'] === 'PA') echo 'selected'; ?>>PA</option>
                <option value="AUTRES" <?php if($row['Types_de_Soins'] === 'AUTRES') echo 'selected'; ?>>AUTRES</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="Montant" class="block text-gray-700">MONTANT</label>
            <input type="number" id="Montant" name="Montant" value="<?= $row['Montant']; ?>" class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="RAP" class="block text-gray-700">RAP</label>
            <input type="text" id="RAP" name="RAP" value="<?= $row['RAP']; ?>" class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="REMARQUE" class="block text-gray-700">REMARQUE</label>
            <input type="text" id="REMARQUE" name="REMARQUE" value="<?= $row['REMARQUE']; ?>" class="border rounded w-full py-2 px-3">
        </div>
        <button type="submit" name="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Valider</button>
    </form>
</div>
</body>
</html>
