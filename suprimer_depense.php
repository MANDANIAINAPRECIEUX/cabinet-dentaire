<?php 
    // Connexion à la base de données
    $con = mysqli_connect("localhost", "root", "manda", "ampiasana");
    if(!$con){
        echo "<div class='text-red-500'>Vous n'êtes pas connecté à la base de données.</div>";
        exit();
    }
?>

<?php
    // Vérifier si Numero_Patient est défini dans l'URL
    if(isset($_GET['Id_Depense'])) {
        // Récupération du Numero_Patient depuis l'URL
        $Id_Depense = $_GET['Id_Depense'];

        // Requête préparée pour la suppression
        $stmt = mysqli_prepare($con, "DELETE FROM depense WHERE Id_Depense = ?");
        if ($stmt === false) {
            echo "<div class='text-red-500'>Erreur de préparation de la requête: " . htmlspecialchars(mysqli_error($con)) . "</div>";
            exit();
        }

        // Liaison des paramètres
        mysqli_stmt_bind_param($stmt, "i", $Id_Depense);

        // Exécution de la requête préparée
        if (mysqli_stmt_execute($stmt)) {
            // Fermeture de la requête et redirection vers la page "liste.php"
            mysqli_stmt_close($stmt);
            header("location:liste_depense.php");
            exit();
        } else {
            echo "<div class='text-red-500'>Erreur lors de la suppression de l'enregistrement: " . htmlspecialchars(mysqli_stmt_error($stmt)) . "</div>";
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "<div class='text-red-500'>Numéro de patient non spécifié.</div>";
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($con);
?>
