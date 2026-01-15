<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E n' G Bakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#007B4C',
                        secondary: '#005A36',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body>
    <section class="min-h-screen flex items-center bg-[#b2d7c9]">
        <div class="w-full max-w-4xl mx-auto p-6">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden md:flex">
                <!-- Left panel: brand / illustration (hidden on small screens) -->
                <div class="hidden md:flex md:w-1/2 bg-cover bg-center items-center justify-center p-8 bg-primary">
                    <div class="text-center">
                        <img src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="E n' G Bakery" class="mx-auto w-40 md:w-48 mb-4" />
                        <h2 class="text-2xl font-extrabold text-white">Welcome back!</h2>
                        <p class="mt-2 text-sm text-white">Sign in to manage orders and bakery settings.</p>
                    </div>
                </div>

                <!-- Right panel: form -->
                <div class="w-full md:w-1/2 p-6 sm:p-8">
                    <div class="max-w-md mx-auto">
                        <h1 class="text-center text-4xl font-bold text-primary mb-6 hidden md:block">E n' G Bakery</h1>
                        <a href="<?= base_url() ?>" class="flex items-center mb-6 justify-center md:justify-start md:hidden">
                            <img class="w-36 md:w-40 mr-2" src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="logo">
                        </a>
                        <h1 class="text-center md:text-left text-m sm:text-l md:text-xl font-bold text-gray-800 mb-4">Login to your account</h1>

                        <!-- Flash Messages -->
                        <?php if (session()->has('error_message')): ?>
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <span class="block sm:inline"><?= session('error_message') ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->has('success_message')): ?>
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                <span class="block sm:inline"><?= session('success_message') ?></span>
                            </div>
                        <?php endif; ?>

                        <form class="space-y-4" action="<?= base_url('Login') ?>" method="post" novalidate>
                            <div>
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                                <input id="username" name="username" type="text" required class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#007B4C] focus:border-transparent" placeholder="Enter your username">
                            </div>

                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" required class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#007B4C] focus:border-transparent" placeholder="Enter your password">
                            </div>

                            <div class="flex items-right justify-end">
                                <a href="<?= base_url('password/forgot') ?>" class="text-sm font-medium text-[#005A36] hover:underline">Forgot password?</a>
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-[#007B4C] hover:bg-[#005A36] text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#005A36]">Sign in</button>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                                </div>
                            </div>

                            <a href="<?= base_url('Auth/Google') ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#007B4C]">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                    <image href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%234285F4' d='M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z'/%3E%3Cpath fill='%2334A853' d='M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z'/%3E%3Cpath fill='%23FBBC05' d='M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z'/%3E%3Cpath fill='%23EA4335' d='M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z'/%3E%3C/svg%3E" width="20" height="20" />
                                    < class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <g fill="#4285F4">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        </g>
                                        <g fill="#34A853">
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        </g>
                                        <g fill="#FBBC05">
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                        </g>
                                        <g fill="#EA4335">
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                        </g>
                                </svg>
                                Sign in with Google
                            </a>

                            <p class="text-center text-sm text-gray-600">Don't have an account yet? <a href="<?= base_url('register') ?>" class="font-medium text-[#007B4C] hover:underline">Sign up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>