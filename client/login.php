<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="relative  text-white overflow-hidden bg-[#FFC244]">

        <?php include './navbar.php' ?>
    </header>

    <div class="h-screen flex items-center justify-center w-full bg-yellow-50">
        <div class="bg-white  shadow-2xl rounded-xl px-8 py-6 max-w-md w-full transform transition-all duration-300 hover:scale-[1.01] animate-fade-in">
            <h1 class="text-3xl font-bold text-center mb-8 text-rose-700 ">Welcome Back to Maklty!</h1>
            <form action="#" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-rose-900  mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        class="shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200  placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-800  transition-all duration-300" 
                        placeholder="your@email.com" 
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-rose-900  mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        class="shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200  placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-800  transition-all duration-300" 
                        placeholder="Enter your password" 
                        required
                    >
                    <a href="https://tailwindflex.com/@nejaa-badr/forgot-password-form-2" 
                        class="inline-block mt-2 text-sm text-rose-700 hover:text-rose-600  transition-colors duration-300">
                        Forgot Password?
                    </a>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            class="h-4 w-4 rounded border-gray-300 text-rose-800 focus:rose-indigo-800 transition-colors duration-300" 
                            checked
                        >
                        <label for="remember" class="ml-2 block text-sm text-rose-900 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>
                    <a href="sign-in.php" 
                        class="text-sm text-rose-800 hover:text-rose-900 transition-colors duration-300">
                        Create Account
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-rose-700 hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-800 transform transition-all duration-300 hover:scale-[1.02] "
                    onclick="handleLogin(event)"
                >
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
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

    <script>
        // Toggle dark mode based on system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        function handleLogin(event) {
            event.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            // Basic validation
            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }

            if (!isValidEmail(email)) {
                alert('Please enter a valid email address');
                return;
            }

            // Here you would typically make an API call to your backend
            console.log('Login attempt:', { email, remember });
            alert('Login successful!');
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Add ripple effect to button
        document.querySelector('button[type="submit"]').addEventListener('mousedown', function(e) {
            const button = e.currentTarget;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size/2;
            const y = e.clientY - rect.top - size/2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                background: rgba(255,255,255,0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    </script>

    <style>
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>