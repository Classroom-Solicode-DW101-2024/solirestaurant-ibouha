<?php
    include '../config.php';

    // Fetch menu items from the database
    $query = "SELECT * FROM plat";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        #slideshow {
      opacity: 1;
      transition: opacity 0.5s ease-in-out;
    }
    </style>

</head>

<body>
    <header class="relative  text-white overflow-hidden bg-[#FFC244] ">
        <?php include './navbar.php' ?>

        <div class="container mx-auto lg:px-12 px-5 py-24 md:py-32 relative z-10 lg:h-[90vh]">
            <div class="flex flex-col md:flex-row items-center justify-around">
                <div class="dish w-full md:w-2/5 md:pl-12 relative">
                    <img id="slideshow" class="w-full  transition-opacity duration-500"
                        src="../assets/images/Burgerbar_hamburgers-restaurant-best-burgers-kolksteeg-amsterdam_BURGER_03.png"
                        alt="Dish Image">
                </div>
                <div class="w-full md:w-1/2 mb-12 md:mb-0 relative">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight ">
                        Vos repas livrés
                        <br />
                        <span
                            class="bg-gradient-to-r from-rose-700 via-orange-400 to-indigo-400 inline-block text-transparent bg-clip-text">
                            jusqu’à chez vous </span>
                    </h1>

                    <p class="text-xl mb-5 text-black ">
                        Harnessing Research for developing Sustainable, Scalable, &
                        Impactful Solutions.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 ">
                        <button class="group relative w-full sm:w-auto px-6 py-3 min-w-[160px]">
                            <div class="absolute inset-0 bg-gradient-to-r from-rose-600 to-orange-600 rounded-lg"></div>
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-rose-600 to-orange-600 rounded-lg lg:blur-md blur-0 group-hover:opacity-60 transition-opacity duration-500">
                            </div>
                            <div class="relative flex items-center justify-center gap-2">
                                <span class="text-white font-medium">Get Started</span>
                                <svg class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </button>


                    </div>

                </div>

            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                    fill="#fef9c3" />
            </svg>
        </div>
    </header>
    <main class="w-full h-full bg-yellow-100 ">

        <section class="container mx-auto py-10">

            <h1 class="text-4xl font-bold text-center mb-8">Our Menu</h1>

            <div class="flex justify-between mb-6">
                <form method="GET" class="w-full flex items-center justify-center gap-4">
                    <input type="text" name="search" placeholder="Search..." class="px-6 py-3 border rounded w-1/3"
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <select name="filter" class="px-6 py-3 border rounded w-1/4">
                        <option value="">All</option>
                        <option value="Marocaine" <?= isset($_GET['filter']) && $_GET['filter'] == 'Marocaine' ? 'selected' : '' ?>>Marocaine</option>
                        <option value="Italienne" <?= isset($_GET['filter']) && $_GET['filter'] == 'Italienne' ? 'selected' : '' ?>>Italienne</option>
                        <option value="Asiatique" <?= isset($_GET['filter']) && $_GET['filter'] == 'Asiatique' ? 'selected' : '' ?>>Asiatique</option>
                    </select>
                    <button type="submit"
                        class=" text-white w-1/5   text-center font-semibold px-6 py-3 rounded-md  bg-rose-700 hover:bg-rose-500">Filter</button>
                </form>
            </div>
        </section>
        <section class="container mx-auto py-10">

            <div class="w-full h-full">
                <div class="w-full h-full flex flex-col justify-center items-center py-10">
                    <div class="flex gap-6 flex-wrap  justify-center items-center">
                        <?php
                        foreach ($plats as $plat):
                            if (!empty($_GET['search']) && stripos($plat['nomPlat'], $_GET['search']) === false) {
                                continue;
                            }
                            if (!empty($_GET['filter']) && $plat['type_plat'] !== $_GET['filter']) {
                                continue;
                            }
                            ?>
                            
                            <!-- 1 -->
                            <div class="flex flex-col gap-4 rounded-lg shadow-lg bg-white group" <?= $plat['type_plat'] ?>>
                                <!-- Card Image -->
                                <img class="w-[16rem] h-[12rem] sm:w-[18rem] sm:h-[14rem] object-center aspect-square rounded-t-lg"
                                    src="https://techakim.com/sam/tg/7268/li/imgs/pizza.jpg"
                                    alt="<?= htmlspecialchars($plat['nomPlat']) ?>" />

                                <div class="flex flex-col">
                                    <!--  -->
                                    <div class="flex items-center justify-between my-4">
                                        <!-- Rater -->
                                        <div
                                            class="relative w-full h-[4rem] flex items-center justify-end border-l-4 border-rose-600 rounded-tr-full rounded-br-full bg-rose-100 ">

                                            <div class="flex gap-1 items-center justify-end text-2xl">
                                                <p class=" font-bold pr-4">DH <?= htmlspecialchars($plat['prix']) ?></p>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Description -->

                                    <h2 class="pl-4 text-2xl font-semibold group-hover:text-rose-600 cursor-pointer">
                                        <?= htmlspecialchars($plat['nomPlat']) ?></h2>
                                    <p class="pl-4 text-gray-800 dark:text-gray-300  mb-4">
                                        <?= htmlspecialchars($plat['categoryPlat']) ?>
                                    </p>
                                    <button
                                        class="border border-rose-700 cursor-pointer hover:bg-rose-700 hover:text-white w-fit ml-4 mb-6 text-xl text-rose-700  font-bold py-2 px-4  rounded-full uppercase">Order
                                        now</button>
                                </div>
                            </div>
                        <?php endforeach; ?>



                    </div>
                </div>
            </div>

        </section>
    </main>


    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../assets/js/script.js"></script>
</body>

</html>