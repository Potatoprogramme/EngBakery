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
                        <img src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="E n' G Bakery"
                            class="mx-auto w-40 md:w-48 mb-4" />
                        <h2 class="text-2xl font-extrabold text-white">Welcome back!</h2>
                        <p class="mt-2 text-sm text-white">Sign in to manage orders and bakery settings.</p>
                    </div>
                </div>

                <!-- Right panel: form -->
                <div class="w-full md:w-1/2 p-6 sm:p-8">
                    <div class="max-w-md mx-auto">
                        <h1 class="text-center text-4xl font-bold text-primary mb-6 hidden md:block">E n' G Bakery</h1>
                        <a href="<?= base_url() ?>"
                            class="flex items-center mb-6 justify-center md:justify-start md:hidden">
                            <img class="w-36 md:w-40 mr-2"
                                src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="logo">
                        </a>
                        <h1 class="text-center md:text-left text-m sm:text-l md:text-xl font-bold text-gray-800 mb-4">
                            Login to your account</h1>

                        <form class="space-y-4" action="<?= base_url('Login') ?>" method="post" novalidate>
                            <div>
                                <label for="username"
                                    class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                                <input id="username" name="username" type="text" required
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#007B4C] focus:border-transparent"
                                    placeholder="Enter your username">
                            </div>

                            <div>
                                <label for="password"
                                    class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" required
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#007B4C] focus:border-transparent"
                                    placeholder="Enter your password">
                            </div>

                            <div class="flex items-right justify-end">
                                <a href="<?= base_url('password/forgot') ?>"
                                    class="text-sm font-medium text-[#005A36] hover:underline">Forgot password?</a>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-[#007B4C] hover:bg-[#005A36] text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#005A36]">Sign
                                in</button>

                            <p class="text-center text-sm text-gray-600">Don't have an account yet? <a
                                    href="<?= base_url('register') ?>"
                                    class="font-medium text-[#007B4C] hover:underline">Sign up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>