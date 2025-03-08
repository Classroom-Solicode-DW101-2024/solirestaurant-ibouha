<?php
require '../config.php';

// Handle delete action
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $idPlat = (int)$_GET['delete'];
    $sql_delete = "DELETE FROM PLAT WHERE idPlat = :idPlat";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindParam(':idPlat', $idPlat);
    $stmt_delete->execute();

    // Redirect to refresh the page after deletion
    header("Location: plats.php");
    exit;
}

// Get all dishes
$sql_plats = "SELECT * FROM PLAT ORDER BY idPlat DESC";
$stmt_plats = $pdo->prepare($sql_plats);
$stmt_plats->execute();
$plats = $stmt_plats->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Plats</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>
    <div>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

        <div x-data="{ sidebarOpen: false, confirmDelete: false, deleteId: null }" class="flex h-screen bg-gray-200">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

            <?php require "sidebar.php" ?>

            <div class="flex flex-col flex-1 overflow-hidden">
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-yellow-50">

                    <div class="container px-6 py-8 mx-auto">
                        <form action="logout.php" method="post" class="w-full flex justify-end mb-5">

                            <button name="logout" class="bg-rose-700 text-white py-2 px-3 rounded hover:bg-red-500">Logout</button>
                        </form>

                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-3xl font-medium text-gray-700">Gestion des Plats</h3>
                            <a href="addPlat.php" class="px-4 py-2 bg-rose-800 text-white rounded-lg hover:bg-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300">
                                Ajouter un Plat
                            </a>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div x-show="confirmDelete" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
                                <h3 class="text-xl font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
                                <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer ce plat? Cette action est irréversible.</p>
                                <div class="flex justify-end space-x-4">
                                    <button @click="confirmDelete = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-300">
                                        Annuler
                                    </button>
                                    <a :href="'plats.php?delete=' + deleteId" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 transition-all duration-300">
                                        Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($plats)): ?>
                            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                                <p class="text-gray-700">Aucun plat n'a été ajouté. <a href="add_plat.php" class="text-rose-800 hover:underline">Ajouter un plat</a></p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($plats as $plat): ?>
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                        <div class="h-48 bg-gray-200 overflow-hidden">
                                            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nomPlat']) ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h4 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($plat['nomPlat']) ?></h4>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs"><?= htmlspecialchars($plat['categoriePlat']) ?></span>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs"><?= htmlspecialchars($plat['TypeCuisine']) ?></span>
                                            </div>
                                            <p class="text-gray-700 font-medium mb-4"><?= number_format($plat['prix'], 2) ?> DH</p>
                                            <div class="flex justify-between">
                                                <a href="addPlat.php?edit=<?= $plat['idPlat'] ?>" class="px-3 py-1.5 bg-yellow-500 text-white rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all duration-300">
                                                    Modifier
                                                </a>
                                                <button @click="confirmDelete = true; deleteId = <?= $plat['idPlat'] ?>" class="px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 transition-all duration-300">
                                                    Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>