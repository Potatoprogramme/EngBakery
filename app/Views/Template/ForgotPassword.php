<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E n' G Bakery - Forgot Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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

<body class="bg-[#b2d7c9] min-h-screen flex items-center justify-center py-8 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Logo and Header -->
            <div class="text-center p-8 pb-6">
                <img src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="E n' G Bakery"
                    class="mx-auto w-20 mb-4" />
                <h1 class="text-2xl font-bold text-primary">Password Reset</h1>
                <p class="text-sm text-gray-500 mt-1">Enter your email to receive a verification code</p>
            </div>

            <!-- Step 1: Request OTP -->
            <div id="step1" class="p-8 pt-0">
                <form id="requestOtpForm">
                    <div class="mb-6">
                        <div class="relative z-0 w-full group">
                            <input type="email" name="email" id="email"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                placeholder=" " required />
                            <label for="email"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white font-medium rounded-lg py-2.5 hover:bg-secondary transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Send Verification Code
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="<?= base_url('login') ?>" class="text-sm text-primary hover:text-secondary font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Login
                    </a>
                </div>
            </div>

            <!-- Step 2: Verify OTP -->
            <div id="step2" class="p-8 pt-0 hidden">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                A 6-digit verification code has been sent to <strong id="emailDisplay"></strong>
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                Code expires in <strong id="countdown">3:00</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <form id="verifyOtpForm">
                    <div class="mb-6">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="otp" id="otp" maxlength="6"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer text-center text-2xl tracking-widest font-mono"
                                placeholder=" " required pattern="[0-9]{6}" />
                            <label for="otp"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] left-1/2 -translate-x-1/2 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Enter 6-Digit Code <span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white font-medium rounded-lg py-2.5 hover:bg-secondary transition-colors">
                        <i class="fas fa-check mr-2"></i>Verify Code
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <button id="resendOtp" class="text-sm text-primary hover:text-secondary font-medium" disabled>
                        <i class="fas fa-redo mr-1"></i>Resend Code <span id="resendTimer"></span>
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <button id="backToStep1" class="text-sm text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-1"></i>Change Email
                    </button>
                </div>
            </div>

            <!-- Step 3: Reset Password -->
            <div id="step3" class="p-8 pt-0 hidden">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Verification successful! Please enter your new password.
                            </p>
                        </div>
                    </div>
                </div>

                <form id="resetPasswordForm">
                    <div class="mb-4">
                        <div class="relative z-0 w-full group">
                            <input type="password" name="new_password" id="new_password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                placeholder=" " required />
                            <label for="new_password"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                New Password <span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="relative z-0 w-full group">
                            <input type="password" name="confirm_password" id="confirm_password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                placeholder=" " required />
                            <label for="confirm_password"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>

                    <div class="text-xs text-gray-600 mb-4">
                        <i class="fas fa-info-circle mr-1"></i>Password must be at least 6 characters long
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white font-medium rounded-lg py-2.5 hover:bg-secondary transition-colors">
                        <i class="fas fa-key mr-2"></i>Reset Password
                    </button>
                </form>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer" class="px-8 pb-4"></div>
        </div>
    </div>

    <script>
        const BASE_URL = '<?= base_url() ?>';
        let countdownInterval;
        let resendCountdownInterval;
        let otpExpiryTime;

        $(document).ready(function() {
            initializeEventHandlers();
        });

        function initializeEventHandlers() {
            // Step 1: Request OTP
            $('#requestOtpForm').on('submit', function(e) {
                e.preventDefault();
                requestOTP();
            });

            // Step 2: Verify OTP
            $('#verifyOtpForm').on('submit', function(e) {
                e.preventDefault();
                verifyOTP();
            });

            // Step 3: Reset Password
            $('#resetPasswordForm').on('submit', function(e) {
                e.preventDefault();
                resetPassword();
            });

            // Resend OTP
            $('#resendOtp').on('click', function() {
                if (!$(this).prop('disabled')) {
                    requestOTP(true);
                }
            });

            // Back to Step 1
            $('#backToStep1').on('click', function() {
                clearInterval(countdownInterval);
                clearInterval(resendCountdownInterval);
                showStep(1);
            });

            // OTP input formatting
            $('#otp').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        function requestOTP(isResend = false) {
            const email = $('#email').val().trim();

            if (!email) {
                showAlert('error', 'Please enter your email address');
                return;
            }

            $.ajax({
                url: BASE_URL + 'PasswordReset/RequestOTP',
                type: 'POST',
                data: { email: email },
                dataType: 'json',
                beforeSend: function() {
                    if (isResend) {
                        $('#resendOtp').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Sending...');
                    } else {
                        $('#requestOtpForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Sending...');
                    }
                },
                success: function(response) {
                    if (response.success) {
                        $('#emailDisplay').text(email);
                        showStep(2);
                        startCountdown();
                        showAlert('success', isResend ? 'New code sent successfully' : 'Verification code sent to your email');
                    } else {
                        showAlert('error', response.message || 'Failed to send verification code');
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Failed to send verification code. Please try again.';
                    showAlert('error', errorMsg);
                },
                complete: function() {
                    $('#requestOtpForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i>Send Verification Code');
                    $('#resendOtp').prop('disabled', false).html('<i class="fas fa-redo mr-1"></i>Resend Code');
                }
            });
        }

        function verifyOTP() {
            const otp = $('#otp').val().trim();
            const email = $('#email').val().trim();

            if (!otp || otp.length !== 6) {
                showAlert('error', 'Please enter the 6-digit verification code');
                return;
            }

            $.ajax({
                url: BASE_URL + 'PasswordReset/VerifyOTP',
                type: 'POST',
                data: { 
                    email: email,
                    otp: otp 
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#verifyOtpForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...');
                },
                success: function(response) {
                    if (response.success) {
                        clearInterval(countdownInterval);
                        clearInterval(resendCountdownInterval);
                        showStep(3);
                    } else {
                        showAlert('error', response.message || 'Invalid verification code');
                        $('#otp').val('').focus();
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Verification failed. Please try again.';
                    showAlert('error', errorMsg);
                    $('#otp').val('').focus();
                },
                complete: function() {
                    $('#verifyOtpForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-check mr-2"></i>Verify Code');
                }
            });
        }

        function resetPassword() {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            const email = $('#email').val().trim();

            if (newPassword.length < 6) {
                showAlert('error', 'Password must be at least 6 characters long');
                return;
            }

            if (newPassword !== confirmPassword) {
                showAlert('error', 'Passwords do not match');
                return;
            }

            $.ajax({
                url: BASE_URL + 'PasswordReset/ResetPassword',
                type: 'POST',
                data: { 
                    email: email,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#resetPasswordForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Resetting...');
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Password reset successfully! Redirecting to login...');
                        setTimeout(function() {
                            window.location.href = BASE_URL + 'login';
                        }, 2000);
                    } else {
                        showAlert('error', response.message || 'Failed to reset password');
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Failed to reset password. Please try again.';
                    showAlert('error', errorMsg);
                },
                complete: function() {
                    $('#resetPasswordForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-key mr-2"></i>Reset Password');
                }
            });
        }

        function showStep(step) {
            $('#step1, #step2, #step3').addClass('hidden');
            $('#step' + step).removeClass('hidden');
            $('#alertContainer').empty();

            if (step === 1) {
                $('#email').focus();
            } else if (step === 2) {
                $('#otp').val('').focus();
            } else if (step === 3) {
                $('#new_password').focus();
            }
        }

        function startCountdown() {
            // Set expiry time to 3 minutes from now
            otpExpiryTime = Date.now() + (3 * 60 * 1000);
            
            // Disable resend button for 30 seconds
            let resendWait = 30;
            $('#resendOtp').prop('disabled', true);
            
            resendCountdownInterval = setInterval(function() {
                resendWait--;
                if (resendWait > 0) {
                    $('#resendTimer').text('(' + resendWait + 's)');
                } else {
                    clearInterval(resendCountdownInterval);
                    $('#resendOtp').prop('disabled', false);
                    $('#resendTimer').text('');
                }
            }, 1000);

            // Main countdown
            countdownInterval = setInterval(function() {
                const remaining = otpExpiryTime - Date.now();
                
                if (remaining <= 0) {
                    clearInterval(countdownInterval);
                    $('#countdown').text('0:00').addClass('text-red-600');
                    showAlert('error', 'Verification code has expired. Please request a new one.');
                    $('#verifyOtpForm button[type="submit"]').prop('disabled', true);
                } else {
                    const minutes = Math.floor(remaining / 60000);
                    const seconds = Math.floor((remaining % 60000) / 1000);
                    $('#countdown').text(minutes + ':' + (seconds < 10 ? '0' : '') + seconds);
                    
                    // Change color when less than 1 minute remains
                    if (remaining < 60000) {
                        $('#countdown').addClass('text-red-600');
                    }
                }
            }, 1000);
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const alertHtml = `
                <div class="${alertClass} border px-4 py-3 rounded relative alert-message" role="alert">
                    <div class="flex items-center">
                        <i class="fas ${iconClass} mr-2"></i>
                        <span class="block sm:inline">${message}</span>
                    </div>
                </div>
            `;

            $('#alertContainer').html(alertHtml);

            // Auto-remove after 5 seconds (except for success messages on password reset)
            if (!(type === 'success' && message.includes('Redirecting'))) {
                setTimeout(function() {
                    $('.alert-message').fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        }
    </script>
</body>

</html>
