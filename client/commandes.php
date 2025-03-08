<?php
require '../config.php';

// Check if user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: login.php");
    exit();
}

$idClient = $_SESSION['client']['idClient'];

// Fetch all orders for the current client
$query = "SELECT c.*, COUNT(cp.idPlat) as totalItems, SUM(p.prix * cp.qte) as montantTotal 
          FROM commande c
          LEFT JOIN commande_plat cp ON c.idCmd = cp.idCmd
          LEFT JOIN plat p ON cp.idPlat = p.idPlat
          WHERE c.idCl = :idClient
          GROUP BY c.idCmd
          ORDER BY c.dateCmd DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':idClient', $idClient, PDO::PARAM_INT);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Order details modal handling
$order_details = [];
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    // Fetch details for specific order
    $details_query = "SELECT p.*, cp.qte, (p.prix * cp.qte) as sous_total 
                      FROM commande_plat cp
                      JOIN plat p ON cp.idPlat = p.idPlat
                      WHERE cp.idCmd = :order_id
                      ORDER BY p.categoriePlat";
    
    $details_stmt = $pdo->prepare($details_query);
    $details_stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
    $details_stmt->execute();
    $order_details = $details_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get order info
    $order_query = "SELECT * FROM commande WHERE idCmd = :order_id";
    $order_stmt = $pdo->prepare($order_query);
    $order_stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
    $order_stmt->execute();
    $order_info = $order_stmt->fetch(PDO::FETCH_ASSOC);
}

// Helper function to get status color classes
function getStatusColorClasses($status) {
    switch ($status) {
        case 'en attente':
            return 'bg-yellow-100 text-yellow-800';
        case 'en cours':
            return 'bg-blue-100 text-blue-800';
        case 'expédiée':
            return 'bg-purple-100 text-purple-800';
        case 'livrée':
            return 'bg-green-100 text-green-800';
        case 'annulée':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="bg-yellow-50">
    <header class="bg-[#FFC244]">
        <?php include './navbar.php' ?>
    </header>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-rose-700 mb-8">Mes Commandes</h1>
        
        <?php if (count($commandes) > 0): ?>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                N° Commande
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Articles
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($commandes as $commande): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        #<?= htmlspecialchars($commande['idCmd']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        <?= date('d/m/Y à H:i', strtotime($commande['dateCmd'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= getStatusColorClasses($commande['Statut']) ?>">
                                        <?= htmlspecialchars(ucfirst($commande['Statut'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $commande['totalItems'] ?> article(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= number_format($commande['montantTotal'], 2) ?> MAD
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="?order_id=<?= $commande['idCmd'] ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-md transition-colors duration-300 text-sm">
                                        Voir les détails
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="mt-4 text-xl font-medium text-gray-900">Vous n'avez pas encore de commandes</h2>
                <p class="mt-2 text-gray-600">Découvrez notre menu et passez votre première commande !</p>
                <a href="menu.php" class="mt-6 inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-md transition-colors duration-300">
                    Voir le menu
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Order Details Modal -->
    <?php if (!empty($order_details)): ?>
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="orderModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-rose-700">
                        Détails de la commande #<?= htmlspecialchars($_GET['order_id']) ?>
                    </h3>
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium <?= getStatusColorClasses($order_info['Statut']) ?>">
                            <?= htmlspecialchars(ucfirst($order_info['Statut'])) ?>
                        </span>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-4">
                    Commandé le <?= date('d/m/Y à H:i', strtotime($order_info['dateCmd'])) ?>
                </p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plat
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prix unitaire
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sous-total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $total = 0;
                            foreach ($order_details as $item): 
                                $total += $item['sous_total'];
                            ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nomPlat']) ?>">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($item['nomPlat']) ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?= htmlspecialchars($item['TypeCuisine']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php
                                            switch ($item['categoriePlat']) {
                                                case 'entrée':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'plat principal':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'dessert':
                                                    echo 'bg-purple-100 text-purple-800';
                                                    break;
                                            }
                                            ?>">
                                            <?= htmlspecialchars(ucfirst($item['categoriePlat'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= number_format($item['prix'], 2) ?> MAD
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $item['qte'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= number_format($item['sous_total'], 2) ?> MAD
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <!-- Total Row -->
                            <tr class="bg-gray-50">
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    <?= number_format($total, 2) ?> MAD
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 text-right">
                    <a href="commandes.php" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors duration-300">
                        Fermer
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>