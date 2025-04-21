<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEPENSE</title>
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
   
        $Id_Depense = $_GET['Id_Depense']; 
        // Requête pour afficher les infos d'un patient
        $req = mysqli_query($con , "SELECT * FROM depense WHERE Id_Depense = $Id_Depense");
        $row = mysqli_fetch_assoc($req);
       
    // Vérifier si le formulaire a été soumis
    //raha mety le izy
    if(isset($_POST['button'])) {
        // Extraction des informations envoyées dans des variables par la méthode POST 
        extract($_POST);
        // Vérifier que tous les champs ont été remplis
        if(isset($Montant_Depense) && isset($Date_Depense) && isset($Remarque_Depense) ){
            // Requête de modification
            $req = mysqli_query($con, "UPDATE depense SET Montant_Depense = '$Montant_Depense', Date_Depense = '$Date_Depense', Remarque_Depense = '$Remarque_Depense' WHERE Id_Depense = $Id_Depense");
            if($req){
                // Si la requête a été effectuée avec succès, on fait une redirection
                header("location:liste_depense.php");
            } else {
                $message = "valeur non modifié";
            }
        } else {
            $message = "Veuillez remplir tous les champs!";
        }
    }
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold  mb-6">DEPENSE</h1>
    <?php if (isset($message)) echo $message; ?>
    <div class="flex">
        <div class="w-1/3">
            <img src="https://via.placeholder.com/300" alt="Expenses illustration" class="rounded shadow-md">
        </div>
        <div class="w-2/3 ml-4">
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="Montant_Depense" class="block text-gray-700">MONTANT</label>
                    <input type="number" id="Montant_Depense" value="<?= $row['Montant_Depense']; ?>" name="Montant_Depense" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label for="Date_Depense" class="block text-gray-700">DATE</label>
                    <input type="date" id="Date_Depense" value="<?= $row['Date_Depense']; ?>" name="Date_Depense" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label for="Remarque_Depense" class="block text-gray-700">REMARQUE</label>
                    <input type="text" id="Remarque_Depense" value="<?= $row['Remarque_Depense']; ?>" name="Remarque_Depense" class="border rounded w-full py-2 px-3">
                </div>
                <button type="submit" name="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Valider</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
