<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E n' G Bakery - Registration</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- JQUERY AND TAILWIND CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
    
    <section class="min-h-screen flex items-center bg-[#b2d7c9] py-8">
        <div class="w-full max-w-3xl mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6 sm:p-8">
                <!-- Logo and Header -->
                <div class="text-center mb-6">
                    <img src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="E n' G Bakery"
                        class="mx-auto w-20 sm:w-24 mb-4" />
                    <h1 class="text-xl sm:text-2xl font-bold text-primary">E n' G Bakery</h1>
                    <p class="text-sm text-gray-500 mt-1">Create your account</p>
                </div>

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

                <form action="" method="post" id="registrationForm">
                    <!-- Personal Information Section -->
                    <div class="mb-6">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">
                            Personal Information</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6">
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="text" name="first_name" id="first_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="first_name"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First
                                    Name <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="text" name="middle_name" id="middle_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " />
                                <label for="middle_name"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Middle
                                    Name</label>
                            </div>
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="text" name="last_name" id="last_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="last_name"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last
                                    Name <span class="text-red-500">*</span></label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6">
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="date" name="birthdate" id="birthdate"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    required />
                                <label for="birthdate"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-focus:scale-75 peer-focus:-translate-y-6">Date
                                    of Birth <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative z-0 w-full mb-5 group">
                                <select name="gender" id="gender"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    required>
                                    <option value="" disabled selected></option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <label for="gender"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-focus:scale-75 peer-focus:-translate-y-6">Gender
                                    <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="tel" name="phone" id="phone"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="phone"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone
                                    Number <span class="text-red-500">*</span></label>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Section -->
                    <div class="mb-6">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">
                            Account Information</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="text" name="username" id="username"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="username"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username
                                    <span class="text-red-500">*</span></label>
                            </div>

                            <div class="relative z-0 w-full mb-5 group">
                                <input type="email" name="email" id="email"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="email"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
                                    Address <span class="text-red-500">*</span></label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="password" name="password" id="password"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="password"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password
                                    <span class="text-red-500">*</span></label>
                            </div>

                            <div class="relative z-0 w-full mb-5 group">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                    placeholder=" " required />
                                <label for="confirm_password"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm
                                    Password <span class="text-red-500">*</span></label>
                            </div>
                        </div>

                        <!-- Hidden field for role -->
                        <input type="hidden" name="employee_type" value="staff">
                    </div>

                    <button type="button" id="createAccountBtn"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary hover:bg-secondary text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-secondary transition-colors">
                        Create Account
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">Already have an account? <a
                            href="<?= base_url('login') ?>" class="font-medium text-primary hover:underline">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </section>
</body>

</html>

<script>
    $(document).ready(function () {
        let isSubmitting = false;

        // Form submission request handler
        $('#createAccountBtn').on('click', function (e) {
            e.preventDefault();

            // Prevent double clicking
            if (isSubmitting) {
                return;
            }

            isSubmitting = true;
            const $btn = $(this);
            const originalText = $btn.html();

            // Show loading state
            $btn.prop('disabled', true)
                .html('<svg class="animate-spin h-5 w-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating Account...');

            $.ajax({
                type: 'POST',
                url: '<?= base_url('registration/submit') ?>',
                data: $('#registrationForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Toast.success(response.message);
                        setTimeout(function () {
                            window.location.href = '<?= base_url('login') ?>';
                        }, 2000);
                    } else {
                        // Registration failed, display error message
                        Toast.error(response.message);
                        // Reset button
                        $btn.prop('disabled', false).html(originalText);
                        isSubmitting = false;
                    }
                },
                error: function (xhr, status, error) {
                    Toast.error('Error creating account: ' + (xhr.responseJSON?.message || error));
                    console.log(xhr);
                    // Reset button
                    $btn.prop('disabled', false).html(originalText);
                    isSubmitting = false;
                }
            });
        });
    });
</script>