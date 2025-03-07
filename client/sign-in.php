<?php
require '../config.php';
$erreurs = [];
$lname = $fname = $phone = '';


if (isset($_POST["btnSubmit"])) {
    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);    
    $phone = trim($_POST["phone"]);
    $tel_is_exist = tel_existe($phone);

    if (!empty($fname) && !empty($lname)  && !empty($phone) && empty($tel_is_exist)) {
        $sql_insert_client = "INSERT INTO CLIENT VALUES(:idClient, :nomCl, :prenomCl,:telCl)";
        $stmt_insert_client = $pdo->prepare($sql_insert_client);
        $idvalue = getLastIdClient() + 1;

        $stmt_insert_client->bindParam(':idClient', $idvalue);
        $stmt_insert_client->bindParam(':nomCl', $fname);       
        $stmt_insert_client->bindParam(':prenomCl', $lname);
        $stmt_insert_client->bindParam(':telCl', $phone);

        $stmt_insert_client->execute();
        header("Location:login.php");
    } else {
        if (empty($fname)) {
            $erreurs['fname'] = " Missing file first name .";
        }
        if (empty($lname)) {
            $erreurs['lname'] = "Missing file last name.";
        }

        if (empty($phone)) {
            $erreurs['phone'] = "Missing file phone number.";
        }
        if (!empty($tel_is_exist)) {
            $erreurs['phone'] = "this phone number already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-[#FFC244]">
        <?php include './navbar.php' ?>
    </header>
    <div class="bg-yellow-50 flex items-center justify-center min-h-screen p-4">

        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full animate-fade-in">
            <h2 class="text-2xl font-bold text-center text-rose-700 mb-8">Create an Account</h2>
            <p class="text-xl text-center text-green-600 mb-8"></p>
            <form  method="POST" class="space-y-6" >
                <div>
                    <label for="fname" class="block text-rose-700 font-semibold mb-2">First Name</label>
                    <input
                        type="text"
                        name="fname"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300"
                        placeholder="Enter your First name"
                        >
                    <p class="text-red-500 text-sm mt-2 " ><?= $erreurs['fname'] ?? '' ?> </p>
                </div>
                <div>
                    <label for="lname" class="block text-rose-700 font-semibold mb-2">Last Name</label>
                    <input
                        type="text"
                        name="lname"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300"
                        placeholder="Enter your last name"
                        >
                    <p class="text-red-500 text-sm mt-2 "><?= $erreurs['lname'] ?? '' ?></p>
                </div>

                <div>
                    <label for="phone" class="block text-rose-700 font-semibold mb-2">Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-800 transition-all duration-300"
                        placeholder="Enter your phone number"
                        >
                    <p class="text-red-500 text-sm mt-2 "><?= $erreurs['phone'] ?? '' ?> </p>
                </div>

                <button
                    name="btnSubmit"
                    class="w-full bg-rose-800 text-white py-3 rounded-lg font-semibold hover:bg-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-800 focus:ring-offset-2 transition-all duration-300 transform hover:scale-[1.02]">
                    Register
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Already have an account?
                <a href="login.php" class="text-rose-800 font-semibold hover:text-rose-900 transition-colors duration-300">Sign In</a>
            </p>
        </div>
    </div>


    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

</body>

</html>