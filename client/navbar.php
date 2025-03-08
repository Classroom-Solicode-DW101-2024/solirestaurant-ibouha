<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body>
<nav class="container mx-auto flex items-center justify-between py-6 px-4 md:px-8" x-data="{ open: false }">
    <div class=" font-extrabold text-3xl text-rose-700 cursor-pointer">
        <a href="./index.php">Maklty</a>
    </div>
    
    <button @click="open = !open" class="md:hidden text-rose-700 focus:outline-none">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <ul class="hidden md:flex items-center gap-x-8">
        <li><a href="index.php" class="block px-4 py-2 hover:text-rose-700">Home</a></li>
        <li><a href="#" class="block px-4 py-2">About</a></li>
        <li><a href="#" class="block px-4 py-2">Services</a></li>
        <?php if (isset($_SESSION['client'])): ?>
            <li><a href="commandes.php" class="block px-4 py-2">mes commandes</a></li>            
        <?php endif; ?>
        <li><a href="#" class="block px-4 py-2">Contact</a></li>
    </ul>
    
    <div class="hidden md:flex items-center gap-x-4">
        <?php if (isset($_SESSION['client'])): ?>
            <?php require "cart.php"?>
            <a href="#" class="text-rose-700 text-5xl w-11 h-11 p-2 flex justify-center items-center">
                <ion-icon name="person-circle-outline"></ion-icon>
            </a>
            <a href="./logout.php" class="text-white bg-rose-700 hover:bg-rose-500 px-4 py-2 rounded-md">Logout</a>
        <?php else: ?>
            <a href="./login.php" class="inline-flex items-center px-6 py-3 rounded-md text-white bg-rose-700 hover:bg-rose-500">
                Login
            </a>
        <?php endif; ?>
    </div>
</nav>

</body>

</html>
