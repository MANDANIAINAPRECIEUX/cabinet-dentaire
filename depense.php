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
    // Vérification que le bouton a été cliqué
    if(isset($_POST['button'])){
        // Extraction des informations envoyées par la méthode POST
        extract($_POST);
        // Vérification que tous les champs ont été remplis
        if(isset($Montant_Depense) && isset($Date_Depense) && isset($Remarque_Depense)){
            // Requête d'ajout sécurisée avec des requêtes préparées
            $stmt = $con->prepare("INSERT INTO depense (Montant_Depense, Date_Depense, Remarque_Depense) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $Montant_Depense, $Date_Depense, $Remarque_Depense);
            if($stmt->execute()){
                // Si la requête a été effectuée avec succès, on fait une redirection
                $message = "<div class='text-green-500'>Dépense ajoutée avec succès.</div>";
                header("location:depense.php");
            } else {
                $message = "<div class='text-red-500'>Erreur lors de l'ajout de la dépense.</div>";
            }
            $stmt->close();
        } else {
            $message = "<div class='text-red-500'>Veuillez remplir tous les champs.</div>";
        }
    }
?>
<?php include "entete.php"; ?>
<div class="container mx-auto mt-4 p-4">
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
                    <input type="number" id="Montant_Depense" name="Montant_Depense" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label for="Date_Depense" class="block text-gray-700">DATE</label>
                    <input type="date" id="Date_Depense" name="Date_Depense" class="border rounded w-full py-2 px-3">
                </div>
                <div class="mb-4">
                    <label for="Remarque_Depense" class="block text-gray-700">REMARQUE</label>
                    <input type="text" id="Remarque_Depense" name="Remarque_Depense" class="border rounded w-full py-2 px-3">
                </div>
                <button type="submit" name="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Valider</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
