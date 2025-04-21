<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versement</title>
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
   
        $Id_Versement = $_GET['Id_Versement']; 
        // Requête pour afficher les infos d'un patient
        $req = mysqli_query($con , "SELECT * FROM versement WHERE Id_Versement = $Id_Versement");
        $row = mysqli_fetch_assoc($req);
       
    // Vérifier si le formulaire a été soumis
    if(isset($_POST['button'])) {
        // Extraction des informations envoyées dans des variables par la méthode POST 
        extract($_POST);
        // Vérifier que tous les champs ont été remplis
        if(isset($Montant_Versement) && isset($Date_Versement) && isset($Remarque_Versement) ){
            // Requête de modification
            $req = mysqli_query($con, "UPDATE Versement SET Montant_Versement = '$Montant_Versement', Date_Versement = '$Date_Versement', Remarque_Versement = '$Remarque_Versement' WHERE Id_Versement = $Id_Versement");
            if($req){
                // Si la requête a été effectuée avec succès, on fait une redirection
                header("location:liste_Versement.php");
            } else {
                $message = "valeur non modifié";
            }
        } else {
            $message = "Veuillez remplir tous les champs!";
        }
    }
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold  mb-6">Versement</h1>
    <?php if (isset($message)) echo $message; ?>
    <div class="flex">
        <div class="w-1/3">
            <img src="https://via.placeholder.com/300" alt="Expenses illustration" class="rounded shadow-md">
        </div>
        <div class="w-2/3 ml-4">
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="Montant_Versement" class="block text-gray-700">MONTANT</label>
                    <input type="number" id="Montant_Versement" value="<?= $row['Montant_Versement']; ?>" name="Montant_Versement" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label for="Date_Versement" class="block text-gray-700">DATE</label>
                    <input type="date" id="Date_Versement" value="<?= $row['Date_Versement']; ?>" name="Date_Versement" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label for="Remarque_Versement" class="block text-gray-700">REMARQUE</label>
                    <input type="text" id="Remarque_Versement" value="<?= $row['Remarque_Versement']; ?>" name="Remarque_Versement" class="border rounded w-full py-2 px-3">
                </div>
                <button type="submit" name="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Valider</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
