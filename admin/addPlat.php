<?php
require '../config.php';
$erreurs = [];
$nomPlat = $categoriePlat = $TypeCuisine = $prix = $image = '';
$edit_mode = false;
$plat_id = null;

// Check if we're in edit mode
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_mode = true;
    $plat_id = (int)$_GET['edit'];

    // Get dish data
    $sql_get_plat = "SELECT * FROM PLAT WHERE idPlat = :idPlat";
    $stmt_get_plat = $pdo->prepare($sql_get_plat);
    $stmt_get_plat->bindParam(':idPlat', $plat_id);
    $stmt_get_plat->execute();
    $plat_data = $stmt_get_plat->fetch(PDO::FETCH_ASSOC);

    if ($plat_data) {
        $nomPlat = $plat_data['nomPlat'];
        $categoriePlat = $plat_data['categoriePlat'];
        $TypeCuisine = $plat_data['TypeCuisine'];
        $prix = $plat_data['prix'];
        $image = $plat_data['image'];
    } else {
        // Dish not found, redirect to listing
        header("Location: plats.php");
        exit;
    }
}

// Function to check if a dish name already exists (excluding current dish in edit mode)
function plat_existe($nomPlat, $current_id = null)
{
    global $pdo;
    if ($current_id) {
        $stmt = $pdo->prepare("SELECT * FROM PLAT WHERE nomPlat = :nomPlat AND idPlat != :idPlat");
        $stmt->bindParam(':nomPlat', $nomPlat);
        $stmt->bindParam(':idPlat', $current_id);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM PLAT WHERE nomPlat = :nomPlat");
        $stmt->bindParam(':nomPlat', $nomPlat);
    }
    $stmt->execute();
    return $stmt->fetch();
}

if (isset($_POST["btnSubmit"])) {
    $nomPlat = trim($_POST["nomPlat"]);
    $categoriePlat = trim($_POST["categoriePlat"]);
    $TypeCuisine = trim($_POST["TypeCuisine"]);
    $prix = trim($_POST["prix"]);
    $image = trim($_POST["image"]);

    // Check if dish name exists (excluding current dish in edit mode)
    $plat_is_exist = plat_existe($nomPlat, $edit_mode ? $plat_id : null);

    if (!empty($nomPlat) && !empty($categoriePlat) && !empty($TypeCuisine) && !empty($prix) && !empty($image) && empty($plat_is_exist)) {
        if ($edit_mode) {
            // Update existing dish
            $sql_update_plat = "UPDATE PLAT SET nomPlat = :nomPlat, categoriePlat = :categoriePlat, 
                               TypeCuisine = :TypeCuisine, prix = :prix, image = :image 
                               WHERE idPlat = :idPlat";
            $stmt_update_plat = $pdo->prepare($sql_update_plat);

            $stmt_update_plat->bindParam(':nomPlat', $nomPlat);
            $stmt_update_plat->bindParam(':categoriePlat', $categoriePlat);
            $stmt_update_plat->bindParam(':TypeCuisine', $TypeCuisine);
            $stmt_update_plat->bindParam(':prix', $prix);
            $stmt_update_plat->bindParam(':image', $image);
            $stmt_update_plat->bindParam(':idPlat', $plat_id);

            $stmt_update_plat->execute();
        } else {
            // Insert new dish
            $sql_insert_plat = "INSERT INTO PLAT (nomPlat, categoriePlat, TypeCuisine, prix, image) 
                              VALUES(:nomPlat, :categoriePlat, :TypeCuisine, :prix, :image)";
            $stmt_insert_plat = $pdo->prepare($sql_insert_plat);

            $stmt_insert_plat->bindParam(':nomPlat', $nomPlat);
            $stmt_insert_plat->bindParam(':categoriePlat', $categoriePlat);
            $stmt_insert_plat->bindParam(':TypeCuisine', $TypeCuisine);
            $stmt_insert_plat->bindParam(':prix', $prix);
            $stmt_insert_plat->bindParam(':image', $image);

            $stmt_insert_plat->execute();
        }

        // Redirect to dish listing page
        header("Location: plats.php");
        exit;
    } else {
        if (empty($nomPlat)) {
            $erreurs['nomPlat'] = "Missing dish name.";
        }
        if (empty($categoriePlat)) {
            $erreurs['categoriePlat'] = "Missing dish category.";
        }
        if (empty($TypeCuisine)) {
            $erreurs['TypeCuisine'] = "Missing cuisine type.";
        }
        if (empty($prix)) {
            $erreurs['prix'] = "Missing price.";
        }
        if (empty($image)) {
            $erreurs['image'] = "Missing image URL.";
        }
        if (!empty($plat_is_exist)) {
            $erreurs['nomPlat'] = "This dish name already exists.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_mode ? 'Modifier' : 'Ajouter' ?> un Plat</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>
    <div>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

        <div x-data="{ sidebarOpen: false, showPreview: <?= !empty($image) ? 'true' : 'false' ?> }" class="flex h-screen bg-gray-200">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

            <?php require "sidebar.php" ?>

            <div class="flex flex-col flex-1 overflow-hidden">
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-yellow-50">
                    <div class="container px-6 py-8 mx-auto">
                        <form action="logout.php" method="post" class="w-full flex justify-end mb-5">

                            <button name="logout" class="bg-rose-700 text-white py-2 px-3 rounded hover:bg-red-500">Logout</button>
                        </form>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-3xl font-medium text-gray-700">
                                <?= $edit_mode ? 'Modifier un Plat' : 'Ajouter un Plat' ?>
                            </h3>
                            <a href="plats.php" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-300">
                                Retour à la liste
                            </a>
                        </div>

                        <div class="flex flex-col mt-8">
                            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                                    <form method="POST" class="space-y-6 p-6">
                                        <div>
                                            <label for="nomPlat" class="block text-rose-700 font-semibold mb-2">Nom du Plat</label>
                                            <input
                                                type="text"
                                                name="nomPlat"
                                                value="<?= htmlspecialchars($nomPlat) ?>"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300"
                                                placeholder="Entrez le nom du plat">
                                            <p class="text-red-500 text-sm mt-2"><?= $erreurs['nomPlat'] ?? '' ?></p>
                                        </div>

                                        <div>
                                            <label for="categoriePlat" class="block text-rose-700 font-semibold mb-2">Catégorie</label>
                                            <select
                                                name="categoriePlat"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300">
                                                <option value="" <?= empty($categoriePlat) ? 'selected' : '' ?>>Sélectionnez une catégorie</option>
                                                <option value="plat principal" <?= $categoriePlat === 'plat principal' ? 'selected' : '' ?>>Plat principal</option>
                                                <option value="dessert" <?= $categoriePlat === 'dessert' ? 'selected' : '' ?>>Dessert</option>
                                                <option value="entrée" <?= $categoriePlat === 'entrée' ? 'selected' : '' ?>>Entrée</option>
                                            </select>
                                            <p class="text-red-500 text-sm mt-2"><?= $erreurs['categoriePlat'] ?? '' ?></p>
                                        </div>

                                        <div>
                                            <label for="TypeCuisine" class="block text-rose-700 font-semibold mb-2">Type de Cuisine</label>
                                            <select
                                                name="TypeCuisine"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300">
                                                <option value="" <?= empty($TypeCuisine) ? 'selected' : '' ?>>Sélectionnez un type de cuisine</option>
                                                <option value="Marocaine" <?= $TypeCuisine === 'Marocaine' ? 'selected' : '' ?>>Marocaine</option>
                                                <option value="Italienne" <?= $TypeCuisine === 'Italienne' ? 'selected' : '' ?>>Italienne</option>
                                                <option value="Chinoise" <?= $TypeCuisine === 'Chinoise' ? 'selected' : '' ?>>Chinoise</option>
                                                <option value="Espagnole" <?= $TypeCuisine === 'Espagnole' ? 'selected' : '' ?>>Espagnole</option>
                                                <option value="Francaise" <?= $TypeCuisine === 'Francaise' ? 'selected' : '' ?>>Française</option>
                                            </select>
                                            <p class="text-red-500 text-sm mt-2"><?= $erreurs['TypeCuisine'] ?? '' ?></p>
                                        </div>

                                        <div>
                                            <label for="prix" class="block text-rose-700 font-semibold mb-2">Prix (DH)</label>
                                            <input
                                                type="number"
                                                name="prix"
                                                value="<?= htmlspecialchars($prix) ?>"
                                                step="0.01"
                                                min="0"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300"
                                                placeholder="Entrez le prix">
                                            <p class="text-red-500 text-sm mt-2"><?= $erreurs['prix'] ?? '' ?></p>
                                        </div>

                                        <div>
                                            <label for="image" class="block text-rose-700 font-semibold mb-2">URL de l'Image</label>
                                            <input
                                                type="url"
                                                name="image"
                                                value="<?= htmlspecialchars($image) ?>"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300"
                                                placeholder="Entrez l'URL de l'image"
                                                @input="showPreview = $event.target.value ? true : false">
                                            <p class="text-red-500 text-sm mt-2"><?= $erreurs['image'] ?? '' ?></p>
                                        </div>

                                        <!-- Image Preview -->
                                        <div x-show="showPreview" class="mt-4">
                                            <p class="text-sm text-gray-600 mb-2">Aperçu de l'image:</p>
                                            <img
                                                x-bind:src="document.querySelector('input[name=image]').value"
                                                alt="Aperçu"
                                                class="max-w-xs rounded-lg border border-gray-200"
                                                onerror="this.onerror=null;this.src='placeholder.jpg';this.classList.add('border-red-500');">
                                        </div>

                                        <button
                                            name="btnSubmit"
                                            class="w-full bg-rose-800 text-white py-3 rounded-lg font-semibold hover:bg-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-800 focus:ring-offset-2 transition-all duration-300 transform hover:scale-[1.02]">
                                            <?= $edit_mode ? 'Modifier le Plat' : 'Ajouter le Plat' ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>