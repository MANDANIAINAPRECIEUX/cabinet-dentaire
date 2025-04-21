<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATIENT</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add custom styling for the blurred background */
        body {
            background-image: url('/AMPIASANA/dentiste.jpg');
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(8px);
        }
        .form-container {
            background: rgba(255, 255, 255, 0.5); /* Slight transparency */
            backdrop-filter: blur(10px); /* Blur effect on the form container */
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); /* Soft shadow */
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 50px;
            max-width: 500px;
            width: 100%;
        }
    </style>
    <!-- <script>
        function validateForm() {
            const age = document.getElementById("Age_Patient").value;
            const montant = document.getElementById("Montant").value;
            const rap = document.getElementById("RAP").value;

            if (age <= 0 || montant <= 0 || rap <= 0) {
                alert("Les valeurs pour l'âge, le montant et le RAP doivent être des nombres positifs.");
                return false;
            }
            return true;
        }
    </script> -->
</head>
<body>
<div><?php include "entete.php"; ?></div>
<?php 
    $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
    if(!$con){
        echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
        exit();
    }
?>
<?php
    if(isset($_POST['button'])){
        extract($_POST);
        if(isset($Nom_Patient) && isset($Age_Patient) && isset($Sexe_Patient) && isset($diagnostique) && isset($Types_de_Soins) && isset($Montant) && isset($RAP) && isset($REMARQUE) && isset($Date)){
            $stmt = $con->prepare("INSERT INTO patient (Nom_Patient, Age_Patient, Sexe_Patient, diagnostique, Types_de_Soins, Montant, RAP, REMARQUE, Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $Nom_Patient, $Age_Patient, $Sexe_Patient, $diagnostique, $Types_de_Soins, $Montant, $RAP, $REMARQUE, $Date);
            if($stmt->execute()){
                $message = "<div class='text-green-500'>Patient ajouté avec succès.</div>";
                header("location:entree.php");
            } else {
                $message = "<div class='text-red-500'>Erreur lors de l'ajout du patient.</div>";
            }
            $stmt->close();
        } else {
            $message = "<div class='text-red-500'>Veuillez remplir tous les champs.</div>";
        }
    }
?>

<div class="form-container">
    <h1 class="text-3xl font-bold text-center mb-6">PATIENT</h1>
    <?php if (isset($message)) echo $message; ?>
    <form action="" method="POST" onsubmit="return validateForm()">
        <div class="mb-4">
            <label for="Date" class="block text-gray-700 font-medium">Date</label>
            <input type="date" id="Date" name="Date" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="Nom_Patient" class="block text-gray-700 font-medium">Nom du Patient</label>
            <input type="text" id="Nom_Patient" name="Nom_Patient" required class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="Age_Patient" class="block text-gray-700 font-medium">Age du Patient</label>
            <input type="number" id="Age_Patient" name="Age_Patient" min="1" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="Sexe_Patient" class="block text-gray-700 font-medium">Genre du Patient</label>
            <select id="Sexe_Patient" name="Sexe_Patient" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="M">Masculin</option>
                <option value="F">Feminin</option>
                <option value="Autre">Autre</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="diagnostique" class="block text-gray-700 font-medium">Diagnostique</label>
            <input type="text" id="diagnostique" name="diagnostique" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="Types_de_Soins" class="block text-gray-700 font-medium">Soins</label>
            <select name="Types_de_Soins" id="Types_de_Soins" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="OCE">SOINS </option>
                <option value="ENDO">ENDODENTIE</option>
                <option value="CHIR">CHIRURGIE</option>
                <option value="PARO">PARODONTOLOGIE</option>
                <option value="PC">PROTHESE COMPLETE</option>
                <option value="PA">PROTHESE ADJOINTE</option>
                <option value="AUTRES">AUTRES</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="Montant" class="block text-gray-700 font-medium">Montant en MGA</label>
            <input type="number" id="Montant" name="Montant" min="1" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="RAP" class="block text-gray-700 font-medium">RAP</label>
            <input type="number" id="RAP" name="RAP" min="1" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label for="REMARQUE" class="block text-gray-700 font-medium">Remarque</label>
            <input type="text" id="REMARQUE" name="REMARQUE" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" name="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">Valider</button>
    </form>
</div>

</body>
</html>
