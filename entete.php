<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATIENT</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .submenu {
            display: none;
            position: absolute;
            background-color: #f3f4f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
            border-radius: 0.25rem;
            width: 200px;
            margin-top: 8px;
        }
        .submenu a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            background-color: #B0C4DE;
            transition: background-color 0.3s ease;
        }
        .submenu a:hover {
            background-color: #6B7280;
            color: white;
        }
        .main-menu-item {
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            display: block;
            width: 150px;
            text-align: center;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 255, 0.6); /* Ombre rouge */
        }
        .main-menu-item:hover {
            background-color: #3A8EBA;
        }
        .submenu-cascade {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background-color: #f3f4f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
            border-radius: 0.25rem;
            width: 200px;
            margin-top: 0;
        }
        .submenu-cascade a {
            background-color: #B0C4DE;
        }
        .submenu-cascade a:hover {
            background-color: #6B7280;
            color: white;
        }
        .submenu-item:hover .submenu-cascade {
            display: block;
        }
        .group:hover .submenu {
            display: block;
        }
    </style>
</head>
<body>

<!-- Header Section -->

<div class="bg-blue-200 flex justify-between items-center h-20">
    <!-- Logo Section -->
    <div class="flex items-center space-x-4">
        <span class="text-xl font-bold pl-5">Cabinet Dentaire 6, Rue Flayelle</span>
    </div>
    <!-- Navigation Section -->
    <nav class="flex space-x-8 relative">
        <div class="relative group">
            <a href="../AMPIASANA/statistique.php" class="main-menu-item">STATISTIQUES</a>
        </div>
        <div class="relative group">
            <a href="#" class="main-menu-item">PATIENT</a>
            <div id="submenu" class="submenu absolute left-0 mt-2">
                <div class="submenu-item relative group">
                    <a href="../AMPIASANA/entree.php">PATIENT</a>
                    <a href="../AMPIASANA/liste_patient.php">Liste</a>
                    <a href="liste_jour.php">liste journalier</a>
                </div>
            </div>
        </div>
        <div class="relative group">
            <a href="#" class="main-menu-item">DEPENSES</a>
            <div id="submenu" class="submenu absolute left-0 mt-2">
                <div class="submenu-item relative group">
                    <a href="../AMPIASANA/depense.php">dépenses</a>
                    <a href="../AMPIASANA/liste_depense.php">Liste des dépenses</a>
                </div>
            </div>
        </div>
        <div class="relative group pr-2">
            <a href="#" class="main-menu-item">VERSEMENTS</a>
            <div id="submenu" class="submenu absolute left-0 mt-2">
                <div class="submenu-item relative group">
                    <a href="../AMPIASANA/versement.php">versement</a>
                    <a href="../AMPIASANA/liste_versement.php">Liste des versements</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    // Toggle submenu on click (optional if you prefer click over hover)
    document.querySelectorAll('.group').forEach(function (menu) {
        menu.addEventListener('click', function () {
            var submenu = menu.querySelector('.submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        });
    });
</script>

</body>
</html>
