<body class="bg-gray-50"></body>
    <!-- Main Content -->
    <div class="p-3 sm:p-4 sm:ml-60">
        <div class="mt-14">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-cog mr-2 text-primary"></i>Account Settings
                </h1>
                <p class="text-gray-600 mt-1">Manage your account information</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Settings Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 bg-primary text-white">
                            <h2 class="font-semibold">Settings</h2>
                        </div>
                        <nav class="p-2">
                            <button id="profileTab"
                                class="settings-tab w-full text-left px-4 py-3 rounded-lg mb-1 flex items-center gap-3 bg-gray-100 text-primary font-medium">
                                <i class="fas fa-user"></i>
                                <span>Profile Information</span>
                            </button>
                            <button id="passwordTab"
                                class="settings-tab w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-lock"></i>
                                <span>Change Password</span>
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Profile Information Section -->
                <div id="profileSection" class="lg:col-span-2 settings-content">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 bg-primary text-white">
                            <h2 class="font-semibold">Profile Information</h2>
                        </div>
                        <form id="profileForm" class="p-6">
                            <!-- Personal Information -->
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">
                                    Personal Information
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="relative z-0 w-full group">
                                        <input type="text" name="firstname" id="firstname"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="firstname"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full group">
                                        <input type="text" name="middlename" id="middlename"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " />
                                        <label for="middlename"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Middle Name
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full group">
                                        <input type="text" name="lastname" id="lastname"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="lastname"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="relative z-0 w-full group">
                                        <input type="date" name="birthdate" id="birthdate"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            required />
                                        <label for="birthdate"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Birthdate <span class="text-red-500">*</span>
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full group">
                                        <select name="gender" id="gender"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <label for="gender"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Gender <span class="text-red-500">*</span>
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full group">
                                        <input type="tel" name="phone_number" id="phone_number"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="phone_number"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Phone Number <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">
                                    Account Information
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative z-0 w-full group">
                                        <input type="text" name="username" id="username"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="username"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Username <span class="text-red-500">*</span>
                                        </label>
                                    </div>

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
                            </div>

                            <!-- Read-only Information -->
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b pb-2">
                                    Account Details (Read-Only)
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">User ID</label>
                                        <p class="text-sm font-medium text-gray-900" id="user_id_display">-</p>
                                    </div>

                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Employee Type</label>
                                        <p class="text-sm font-medium text-gray-900" id="employee_type_display">-</p>
                                    </div>

                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Account Status</label>
                                        <p class="text-sm font-medium text-gray-900" id="approved_display">-</p>
                                    </div>

                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Account Created</label>
                                        <p class="text-sm font-medium text-gray-900" id="created_at_display">-</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end gap-3">
                                <button type="button" id="cancelBtn"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition-colors font-medium">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Section -->
                <div id="passwordSection" class="lg:col-span-2 settings-content hidden">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 bg-primary text-white">
                            <h2 class="font-semibold">Change Password</h2>
                        </div>
                        <form id="passwordForm" class="p-6">
                            <div class="mb-6">
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                Password must be at least 6 characters long
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <div class="relative z-0 w-full group">
                                        <input type="password" name="current_password" id="current_password"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="current_password"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Current Password <span class="text-red-500">*</span>
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full group">
                                        <input type="password" name="new_password" id="new_password"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="new_password"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            New Password <span class="text-red-500">*</span>
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full group">
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
                                            placeholder=" " required />
                                        <label for="confirm_password"
                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Confirm New Password <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end gap-3">
                                <button type="button" id="cancelPasswordBtn"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition-colors font-medium">
                                    <i class="fas fa-key mr-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = '<?= base_url() ?>';
        let originalProfileData = {};

        $(document).ready(function() {
            loadUserData();
            initializeEventHandlers();
        });

        // Tab switching
        function initializeEventHandlers() {
            $('.settings-tab').on('click', function() {
                const tabId = $(this).attr('id');
                
                // Update active tab styling
                $('.settings-tab').removeClass('bg-gray-100 text-primary font-medium').addClass('text-gray-700');
                $(this).addClass('bg-gray-100 text-primary font-medium').removeClass('text-gray-700');

                // Show/hide sections
                if (tabId === 'profileTab') {
                    $('#profileSection').removeClass('hidden');
                    $('#passwordSection').addClass('hidden');
                } else if (tabId === 'passwordTab') {
                    $('#profileSection').addClass('hidden');
                    $('#passwordSection').removeClass('hidden');
                }
            });

            // Cancel buttons
            $('#cancelBtn').on('click', function() {
                loadUserData(); // Reset form
            });

            $('#cancelPasswordBtn').on('click', function() {
                $('#passwordForm')[0].reset();
            });

            // Profile form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                updateProfile();
            });

            // Password form submission
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                updatePassword();
            });
        }

        // Load user data via AJAX
        function loadUserData() {
            $.ajax({
                url: BASE_URL + 'User/GetUserData',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const user = response.data;
                        originalProfileData = { ...user };
                        
                        // Populate editable fields
                        $('#firstname').val(user.firstname || '');
                        $('#middlename').val(user.middlename || '');
                        $('#lastname').val(user.lastname || '');
                        $('#birthdate').val(user.birthdate || '');
                        $('#gender').val(user.gender || '');
                        $('#phone_number').val(user.phone_number || '');
                        $('#username').val(user.username || '');
                        $('#email').val(user.email || '');

                        // Populate read-only fields
                        $('#user_id_display').text(user.user_id || '-');
                        $('#employee_type_display').text(capitalizeRole(user.employee_type) || '-');
                        $('#approved_display').text(user.approved == 1 ? 'Approved' : 'Pending');
                        $('#created_at_display').text(formatDate(user.created_at) || '-');
                    } else {
                        showAlert('error', response.message || 'Failed to load user data');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading user data:', error);
                    showAlert('error', 'Failed to load user data. Please refresh the page.');
                }
            });
        }

        // Update profile
        function updateProfile() {
            const formData = {
                firstname: $('#firstname').val().trim(),
                middlename: $('#middlename').val().trim(),
                lastname: $('#lastname').val().trim(),
                birthdate: $('#birthdate').val(),
                gender: $('#gender').val(),
                phone_number: $('#phone_number').val().trim(),
                username: $('#username').val().trim(),
                email: $('#email').val().trim()
            };

            // Basic validation
            if (!formData.firstname || !formData.lastname || !formData.birthdate || 
                !formData.gender || !formData.phone_number || !formData.username || !formData.email) {
                showAlert('error', 'Please fill in all required fields');
                return;
            }

            $.ajax({
                url: BASE_URL + 'User/UpdateProfile',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Profile updated successfully');
                        loadUserData(); // Reload data
                    } else {
                        showAlert('error', response.message || 'Failed to update profile');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating profile:', error);
                    const errorMsg = xhr.responseJSON?.message || 'Failed to update profile. Please try again.';
                    showAlert('error', errorMsg);
                }
            });
        }

        // Update password
        function updatePassword() {
            const currentPassword = $('#current_password').val();
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();

            // Validation
            if (!currentPassword || !newPassword || !confirmPassword) {
                showAlert('error', 'Please fill in all password fields');
                return;
            }

            if (newPassword.length < 6) {
                showAlert('error', 'New password must be at least 6 characters long');
                return;
            }

            if (newPassword !== confirmPassword) {
                showAlert('error', 'New passwords do not match');
                return;
            }

            $.ajax({
                url: BASE_URL + 'User/ChangePassword',
                type: 'POST',
                data: {
                    current_password: currentPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Password updated successfully');
                        $('#passwordForm')[0].reset();
                    } else {
                        showAlert('error', response.message || 'Failed to update password');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating password:', error);
                    const errorMsg = xhr.responseJSON?.message || 'Failed to update password. Please try again.';
                    showAlert('error', errorMsg);
                }
            });
        }

        // Helper functions
        function capitalizeRole(role) {
            if (!role) return '';
            return role.charAt(0).toUpperCase() + role.slice(1);
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const alertHtml = `
                <div class="${alertClass} border px-4 py-3 rounded relative mb-4 alert-message" role="alert">
                    <div class="flex items-center">
                        <i class="fas ${iconClass} mr-2"></i>
                        <span class="block sm:inline">${message}</span>
                    </div>
                </div>
            `;

            // Remove existing alerts
            $('.alert-message').remove();

            // Add new alert to the active section
            if ($('#profileSection').is(':visible')) {
                $('#profileForm').prepend(alertHtml);
            } else {
                $('#passwordForm').prepend(alertHtml);
            }

            // Auto-remove after 5 seconds
            setTimeout(function() {
                $('.alert-message').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
    </script>
</body>
