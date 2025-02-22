<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="relative  text-white overflow-hidden bg-[#FFC244]">

        <?php include './navbar.php' ?>
    </header>
    <div class="bg-yellow-50 flex items-center justify-center min-h-screen p-4">

        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full animate-fade-in">
            <h2 class="text-2xl font-bold text-center text-indigo-800 mb-8">Create an Account</h2>
            <form id="registrationForm" class="space-y-6" novalidate>
                <div>
                    <label for="username" class="block text-indigo-900 font-semibold mb-2">Username</label>
                    <input
                        type="text"
                        id="username"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-800 transition-all duration-300"
                        placeholder="Enter your username"
                        required>
                    <p class="text-red-500 text-sm mt-2 hidden" id="usernameError">Username is required.</p>
                </div>

                <div>
                    <label for="email" class="block text-indigo-900 font-semibold mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-800 transition-all duration-300"
                        placeholder="Enter your email"
                        required>
                    <p class="text-red-500 text-sm mt-2 hidden" id="emailError">Please enter a valid email.</p>
                </div>

                <div>
                    <label for="password" class="block text-indigo-900 font-semibold mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-800 transition-all duration-300"
                        placeholder="Enter your password"
                        required>
                    <p class="text-red-500 text-sm mt-2 hidden" id="passwordError">Password is required.</p>
                </div>

                <div>
                    <label for="confirm-password" class="block text-indigo-900 font-semibold mb-2">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm-password"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-800 transition-all duration-300"
                        placeholder="Confirm your password"
                        required>
                    <p class="text-red-500 text-sm mt-2 hidden" id="confirmPasswordError">Passwords do not match.</p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-indigo-800 text-white py-3 rounded-lg font-semibold hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-800 focus:ring-offset-2 transition-all duration-300 transform hover:scale-[1.02]">
                    Register
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Already have an account?
                <a href="login.php" class="text-indigo-800 font-semibold hover:text-blue-900 transition-colors duration-300">Sign In</a>
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

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Clear previous error messages
            document.querySelectorAll('.text-red-500').forEach(function(element) {
                element.classList.add('hidden');
            });

            let isValid = true;

            // Validate username
            const username = document.getElementById('username').value.trim();
            if (!username) {
                document.getElementById('usernameError').classList.remove('hidden');
                isValid = false;
            }

            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!email || !emailPattern.test(email)) {
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            }

            // Validate password
            const password = document.getElementById('password').value;
            if (!password) {
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            }

            // Validate confirm password
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').classList.remove('hidden');
                isValid = false;
            }

            // Add visual feedback for invalid fields
            document.querySelectorAll('input').forEach(input => {
                if (!input.value) {
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (isValid) {
                // Submit the form if all validations pass
                alert('Form submitted successfully!');
                // Reset form and remove any error states
                document.getElementById('registrationForm').reset();
                document.querySelectorAll('input').forEach(input => {
                    input.classList.remove('border-red-500');
                });
            }
        });

        // Remove error state on input
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                const errorElement = document.getElementById(`${this.id}Error`);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>