<?php
require "../config.php";

// Set default date filters if not provided
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Fetch menu items from the database with date filter
$query = "SELECT c.*, cl.nomCl, cl.prenomCl 
          FROM commande c
          JOIN client cl ON c.idCl = cl.idClient";

// Add date filter conditions if dates are provided
$params = [];
if (!empty($startDate) && !empty($endDate)) {
    $query .= " WHERE DATE(c.dateCmd) BETWEEN :startDate AND :endDate";
    $params[':startDate'] = $startDate;
    $params[':endDate'] = $endDate;
} elseif (!empty($startDate)) {
    $query .= " WHERE DATE(c.dateCmd) >= :startDate";
    $params[':startDate'] = $startDate;
} elseif (!empty($endDate)) {
    $query .= " WHERE DATE(c.dateCmd) <= :endDate";
    $params[':endDate'] = $endDate;
}

$stmt = $pdo->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update commande status if form is submitted
if (isset($_POST['update_status'])) {
    $cmdId = $_POST['cmd_id'];
    $newStatus = $_POST['new_status'];

    $stmt = $pdo->prepare("UPDATE commande SET Statut = :newStatus WHERE idCmd = :cmdId");
    $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':cmdId', $cmdId, PDO::PARAM_STR);
    $stmt->execute();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

</head>

<body>
    <div>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

            <?php require "sidebar.php" ?>

            <div class="flex flex-col flex-1 overflow-hidden">
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-yellow-50">
                    <div class="container px-6 py-8 mx-auto">
                        <form action="logout.php" method="post" class="w-full flex justify-end">

                            <button name="logout" class="bg-rose-700 text-white py-2 px-3 rounded hover:bg-red-500">Logout</button>
                        </form>
                        <h3 class="text-3xl font-medium text-gray-700">Toutes les Commandes</h3>
                        <div class="container mx-auto p-6">
                            <!-- Date Filter Form -->
                        <div class="mt-6 bg-white p-4 rounded shadow">
                            <h4 class="text-lg font-medium text-gray-700 mb-3">Filtrer par Date</h4>
                            <form method="GET" class="flex flex-wrap items-end gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début:</label>
                                    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate) ?>"
                                        class="p-2 border border-gray-300 rounded">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin:</label>
                                    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>"
                                        class="p-2 border border-gray-300 rounded">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="p-2 bg-rose-600 hover:bg-rose-500 text-white rounded">
                                        Filtrer
                                    </button>
                                    <a href="commandes.php" class="p-2 bg-gray-500 hover:bg-gray-400 text-white rounded flex items-center">
                                        Réinitialiser
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="flex flex-col mt-8">
                            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="overflow-x-auto rounded-lg shadow">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <!-- Table Header -->
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    idCommande
                                                </th>
                                                <th scope="col" class="px-6 py-3">Date Commande</th>
                                                <th scope="col" class="px-6 py-3">Status</th>
                                                <th scope="col" class="px-6 py-3">Client</th>
                                            </tr>
                                        </thead>

                                        <!-- Table Body -->
                                        <tbody>
                                            <?php if (count($commandes) > 0) : ?>
                                                <?php foreach ($commandes as $commande) : ?>
                                                    <tr class="bg-white border-b ">
                                                        <td class="px-6 py-4">
                                                            <?php echo $commande['idCmd'] ?>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <?php echo $commande['dateCmd'] ?>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <form method="POST" class="flex items-center gap-3">
                                                                <input type="hidden" name="cmd_id" value="<?= htmlspecialchars($commande['idCmd']) ?>">
                                                                <select name="new_status" class="p-2 rounded 
                                                                            <?php
                                                                            // Add different background colors based on the current status
                                                                            switch ($commande['Statut']) {
                                                                                case 'en attente':
                                                                                    echo 'bg-yellow-200 text-yellow-500 ';
                                                                                    break;
                                                                                case 'en cours':
                                                                                    echo 'bg-blue-200 text-blue-500';
                                                                                    break;
                                                                                case 'expédiée':
                                                                                    echo 'bg-purple-200 text-purple-500';
                                                                                    break;
                                                                                case 'livrée':
                                                                                    echo 'bg-green-200 text-green-500';
                                                                                    break;
                                                                                case 'annulée':
                                                                                    echo 'bg-red-200 text-red-500';
                                                                                    break;
                                                                            }
                                                                            ?>">
                                                                    <option value="en attente" class="bg-yellow-200 text-yellow-500" <?= ($commande['Statut'] === 'en attente') ? 'selected' : '' ?>>En attente</option>
                                                                    <option value="en cours" class="bg-blue-200 text-blue-500" <?= ($commande['Statut'] === 'en cours') ? 'selected' : '' ?>>En cours</option>
                                                                    <option value="expédiée" class="bg-purple-200 text-purple-500" <?= ($commande['Statut'] === 'expédiée') ? 'selected' : '' ?>>Expédiée</option>
                                                                    <option value="livrée" class="bg-green-200 text-green-500" <?= ($commande['Statut'] === 'livrée') ? 'selected' : '' ?>>Livrée</option>
                                                                    <option value="annulée" class="bg-red-200 text-red-500" <?= ($commande['Statut'] === 'annulée') ? 'selected' : '' ?>>Annulée</option>
                                                                </select>
                                                                <button class=" w-8 h-8 text-white bg-rose-600 hover:bg-rose-400 rounded cursor-pointer" type="submit" name="update_status">✓</button>
                                                            </form>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <?php echo htmlspecialchars($commande['nomCl'] . ' ' . $commande['prenomCl']); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr class="bg-white border-b">
                                                    <td colspan="4" class="px-6 py-4 text-center">Aucune commande trouvée pour cette période</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="flex items-center justify-between p-4 bg-white ">
                                        <button class="px-3 py-1 bg-gray-200 rounded">Previous</button>
                                        <span class="text-sm text-gray-700 dark:text-gray-400">Page 1 of 10</span>
                                        <button class="px-3 py-1 bg-gray-200 rounded">Next</button>
                                    </div>
                                </div>
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