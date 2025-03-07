<?php
require "../config.php";

$error = "";
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Check if the username exists
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        if ($password === $admin['password']) {                       
            $_SESSION["admin"] = $admin;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Username or password Invalid!";
        }
    } else {
        $error = "Username or password Invalid!";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    
    <div class="h-screen flex items-center justify-center w-full bg-yellow-50">
        <div class="bg-white shadow-2xl rounded-xl px-8 py-6 max-w-md w-full transform transition-all duration-300 hover:scale-[1.01] animate-fade-in">
            <h1 class="text-3xl font-bold text-center mb-8 text-rose-700">Admin Login</h1>
            <form method="post" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-rose-900 mb-2">
                        Username
                    </label>
                    <input type="text" name="username" id="username" class="shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300" placeholder="Username" required>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-rose-900 mb-2">
                        Password
                    </label>
                    <input type="password" name="password" id="password" class="shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300" placeholder="Password" required>
                    <span class="text-red-500"><?php echo $error; ?></span>
                </div>
                
                <button type="submit" name="login" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-rose-700 hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-800 transform transition-all duration-300 hover:scale-[1.02]">
                    Login
                </button>
            </form>
        </div>
    </div>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes ripple { to { transform: scale(4); opacity: 0; } }
    </style>
</body>
</html>