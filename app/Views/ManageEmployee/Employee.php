<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="p-4 sm:ml-60">
        <div class="mt-16">
            <nav class="mb-3 sm:mb-4" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 text-sm text-gray-500 justify-left sm:justify-start">
                    <li>
                        <a href="<?= base_url('Dashboard') ?>" class="hover:text-primary">Dashboard</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Manage Employee</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Employee Management</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Manage employees, roles, and approval requests</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('ManageEmployee/Approval') ?>"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-primary text-white hover:bg-secondary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Employee Approvals
                            <span class="ml-2 bg-white text-primary text-xs font-bold px-2 py-0.5 rounded-full"
                                id="pendingBadge">0</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Employees</p>
                            <p class="text-lg font-semibold text-gray-800" id="statTotalEmployees">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Active</p>
                            <p class="text-lg font-semibold text-gray-800" id="statActiveEmployees">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-shield text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Admins</p>
                            <p class="text-lg font-semibold text-gray-800" id="statAdminCount">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pending</p>
                            <p class="text-lg font-semibold text-gray-800" id="statPendingCount">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="mb-4 p-3 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" id="searchInput"
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary"
                            placeholder="Search by name, email, or username...">
                    </div>
                    <div class="flex gap-2">
                        <select id="roleFilter"
                            class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-1 focus:ring-primary focus:border-primary">
                            <option value="all">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employee</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Joined</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="employeeTableBody">
                            <!-- Dynamic content will be inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State (positioned outside table) -->
                <div id="emptyState" class="hidden p-8 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-gray-300"></i>
                    </div>
                    <h3 class="text-base font-medium text-gray-900 mb-1">No Employees Found</h3>
                    <p class="text-gray-500 text-sm">There are no employees to display.</p>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-3 border-t border-gray-200 flex items-center justify-between"
                    id="paginationSection">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span
                            class="font-medium">12</span> employees
                    </div>
                    <div class="flex gap-1">
                        <button
                            class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50"
                            disabled>&laquo; Prev</button>
                        <button class="px-3 py-1 text-sm bg-primary text-white rounded">1</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50">2</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50">3</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50">Next
                            &raquo;</button>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-3" id="mobileCardsContainer">
                <!-- Dynamic mobile cards will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Change Role Modal -->
    <div id="changeRoleModal"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="roleModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-lg max-h-full mx-auto mt-20">
            <div class="relative bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Change Employee Role</h3>
                    <button type="button" id="closeRoleModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-primary" id="roleModalInitials">JD</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900" id="roleModalName">John Doe</h4>
                            <p class="text-xs text-gray-500" id="roleModalEmail">johndoe@email.com</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="newRole" class="block mb-2 text-sm font-medium text-gray-900">Select New Role <span
                                class="text-red-500">*</span></label>
                        <select id="newRole"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                            <option value="">Select a role...</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200">
                    <button type="button" id="confirmRoleChange"
                        class="flex-1 inline-flex justify-center items-center text-white bg-primary hover:bg-secondary focus:ring-4 focus:ring-primary/30 font-medium rounded-lg text-sm px-5 py-2.5">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                    <button type="button" id="cancelRoleChange"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View/Edit Employee Modal -->
    <div id="viewEditModal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="viewEditModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-lg max-h-full mx-auto mt-20">
            <div class="relative bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Employee Details</h3>
                    <button type="button" id="closeViewEditModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-bold text-primary" id="viewInitials"></span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-semibold text-gray-900" id="viewName"></h4>
                            <p class="text-sm text-gray-500" id="viewEmail"></p>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-100 text-gray-700"
                            id="viewStatus">
                            Active
                        </span>
                    </div>
                    <div class="grid gap-4 grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Username</label>
                            <p class="text-sm font-medium text-gray-900" id="viewUsername"></p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Phone</label>
                            <p class="text-sm font-medium text-gray-900" id="viewPhone"></p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Role</label>
                            <p class="text-sm font-medium text-gray-900" id="viewRole"></p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Gender</label>
                            <p class="text-sm font-medium text-gray-900" id="viewGender"></p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Birthdate</label>
                            <p class="text-sm font-medium text-gray-900" id="viewBirthdate"></p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Joined</label>
                            <p class="text-sm font-medium text-gray-900" id="viewJoined"></p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200">
                    <button type="button"
                        class="btn-role flex-1 inline-flex justify-center items-center text-white bg-primary hover:bg-secondary focus:ring-4 focus:ring-primary/30 font-medium rounded-lg text-sm px-5 py-2.5">
                        <i class="fas fa-edit mr-2"></i> Change Employee Role
                    </button>
                    <button type="button" id="closeViewBtn"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.BASE_URL = '<?= base_url() ?>';

        // Current logged-in user info
        const currentUser = {
            id: '<?= $user_id ?>',
            employeeType: '<?= $employee_type ?>'
        };

        // Store all employees for filtering
        let allEmployees = [];
        let currentPage = 1;
        const itemsPerPage = 5;

        $(document).ready(function () {
            fetchEmployees();
            fetchPendingCount();

            // Blame -> jc ... fetch pending approval accounts
            function fetchPendingCount() {
                $.ajax({
                    url: `${BASE_URL}/Approval/GetPendingUsers`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            console.log('Pending users:', response.data);
                            const pendingCount = response.data.filter(u => (u.status || 'pending').toLowerCase() === 'pending').length;
                            $('#pendingBadge').text(pendingCount);
                            $('#statPendingCount').text(pendingCount);
                        } else {
                            $('#pendingBadge').text('0');
                            $('#statPendingCount').text('0');
                        }
                    },
                    error: function () {
                        $('#pendingBadge').text('0');
                        $('#statPendingCount').text('0');
                    }
                });
            }

            // Helper function to get initials from name
            function getInitials(firstname, lastname) {
                const first = firstname ? firstname.charAt(0).toUpperCase() : '';
                const last = lastname ? lastname.charAt(0).toUpperCase() : '';
                return first + last;
            }

            // Helper function to format date
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }

            // Helper function to capitalize role
            function capitalizeRole(role) {
                if (!role) return 'N/A';
                return role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
            }

            // Helper function to check if current user can delete target user
            function canDeleteUser(targetEmployeeType) {
                const targetType = targetEmployeeType ? targetEmployeeType.toLowerCase() : '';
                const currentType = currentUser.employeeType ? currentUser.employeeType.toLowerCase() : '';

                // Admin: cannot delete co-admin and owner, but can delete staff
                if (currentType === 'admin') {
                    return targetType === 'staff' || targetType === 'cashier';
                }

                // Owner: cannot delete co-owner, but can delete admin/staff
                if (currentType === 'owner') {
                    return targetType !== 'owner';
                }

                // Default: cannot delete anyone
                return false;
            }


            function deleteUser(userId, employeeName, btn) {
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.start(btn, '');
                }
                
                $.ajax({
                    url: `${BASE_URL}/ManageEmployee/DeleteUser`,
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        user_id: userId,
                        privilege_level: currentUser.employeeType
                    }),
                    success: function (response) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(btn);
                        }
                        if (response.success) {
                            Toast.success('Employee deleted successfully.');
                            console.log('Deleted user ID:', response.data);
                            fetchEmployees();
                            fetchPendingCount();
                        } else {
                            Toast.error(response.message || 'Failed to delete employee.');
                        }
                    },
                    error: function (xhr, status, error) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(btn);
                        }
                        console.error('Error deleting user:', xhr);
                        Toast.error('An error occurred while deleting the employee.');
                    }
                });
            }

            function changeUserRole(userId, newRole, btn) {
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.start(btn, 'Saving...');
                }
                
                $.ajax({
                    url: `${BASE_URL}ManageEmployee/ChangeUserRole`,
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        user_id: userId,
                        new_role: newRole,
                    }),
                    success: function (response) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(btn);
                        }
                        if (response.success) {
                            Toast.success(response.message || 'Employee role changed successfully.');
                            console.log(response.data);

                            // Close the change role modal
                            $('#changeRoleModal').removeClass('flex').addClass('hidden');
                            $('#newRole').val('');

                            // Refresh employee list and pending count
                            fetchEmployees();
                            fetchPendingCount();
                        } else {
                            Toast.error(response.message || 'Failed to change employee role.');
                            console.log(response.data);
                        }
                    },
                    error: function (xhr, status, error) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(btn);
                        }
                        console.error(xhr);
                        const errorMessage = xhr.responseJSON?.message || 'An error occurred while changing employee role.';
                        Toast.error(errorMessage);
                    }
                });
            }


            function fetchEmployees() {
                $.ajax({
                    url: `${BASE_URL}/ManageEmployee/GetEmployees`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Filter out the current logged-in user from the list
                            // Also hide owners from admin view
                            let users = response.data.filter(user => user.user_id != currentUser.id);

                            if (currentUser.employeeType.toLowerCase() === 'admin') {
                                users = users.filter(user => user.employee_type && user.employee_type.toLowerCase() !== 'owner');
                            }

                            // Store all employees for filtering
                            allEmployees = users;

                            // Update stats cards
                            updateStatsCards(users);

                            // Apply filters (which will call renderEmployees)
                            applyFilters();
                        } else {
                            Toast.error(response.message || 'Failed to load employees');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching employees:', error);
                        Toast.error('Failed to load employees. Please try again.');
                    }
                });
            }

            // Update stats cards with actual data
            function updateStatsCards(users) {
                const totalCount = users.length;
                const activeCount = users.length; // All fetched users are active
                const adminCount = users.filter(u => (u.employee_type || '').toLowerCase() === 'admin').length;
                
                // Update the stats cards using IDs
                $('#statTotalEmployees').text(totalCount);
                $('#statActiveEmployees').text(activeCount);
                $('#statAdminCount').text(adminCount);
            }

            // Render employees with pagination
            function renderEmployees(users) {
                // Calculate pagination
                const totalItems = users.length;
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
                const paginatedUsers = users.slice(startIndex, endIndex);

                if (users.length === 0) {
                    // Show empty state for both desktop and mobile
                    $('#emptyState').removeClass('hidden');
                    $('#employeeTableBody').empty();
                    $('#paginationSection').addClass('hidden');

                    // Show mobile empty state
                    $('#mobileCardsContainer').html(`
                        <div class="p-8 text-center bg-white rounded-lg shadow-sm border border-gray-100">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-base font-medium text-gray-900 mb-1">No Employees Found</h3>
                            <p class="text-gray-500 text-sm">There are no employees matching your criteria.</p>
                        </div>
                    `);
                } else {
                    // Hide empty state, show table body and pagination
                    $('#emptyState').addClass('hidden');
                    $('#paginationSection').removeClass('hidden');
                    $('#employeeTableBody').empty();

                    // Clear and populate mobile cards
                    let mobileCardsHTML = '';

                    paginatedUsers.forEach(function (user) {
                        const initials = getInitials(user.firstname, user.lastname);
                        const fullName = `${user.firstname} ${user.middlename || ''} ${user.lastname}`.trim();
                        const formattedDate = formatDate(user.created_at);
                        const role = capitalizeRole(user.employee_type);
                        const canDelete = canDeleteUser(user.employee_type);

                        // Desktop table row
                        const rowHTML = `
                            <tr class="hover:bg-gray-50" data-user-id="${user.user_id}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-primary">${initials}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">${fullName}</div>
                                            <div class="text-sm text-gray-500">@${user.username || 'N/A'}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${user.email || 'N/A'}</div>
                                    <div class="text-sm text-gray-500">${user.phone_number || 'N/A'}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        ${role}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formattedDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" class="text-blue-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-blue-800 btn-edit" title="View"
                                            data-user-id="${user.user_id}"
                                            data-firstname="${user.firstname || ''}"
                                            data-middlename="${user.middlename || ''}"
                                            data-lastname="${user.lastname || ''}"
                                            data-email="${user.email || ''}"
                                            data-username="${user.username || ''}"
                                            data-phone="${user.phone_number || ''}"
                                            data-role="${user.employee_type || ''}"
                                            data-gender="${user.gender || ''}"
                                            data-birthdate="${user.birthdate || ''}"
                                            data-joined="${user.created_at || ''}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        ${canDelete ? `
                                        <button type="button" class="text-red-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-red-800 btn-delete"
                                            data-user-id="${user.user_id}"
                                            data-name="${fullName}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        ` : ''}
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('#employeeTableBody').append(rowHTML);

                        // Mobile card
                        mobileCardsHTML += `
                            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-primary">${initials}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">${fullName}</h3>
                                            <p class="text-xs text-gray-500 truncate">${user.email || 'N/A'}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            ${role}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                                        <span><i class="fas fa-phone mr-1"></i>${user.phone_number || 'N/A'}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-gray-700">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>Active
                                        </span>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                                    <button type="button" class="btn-edit flex-1 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg py-2 hover:bg-blue-100 transition-colors"
                                        data-user-id="${user.user_id}"
                                        data-firstname="${user.firstname || ''}"
                                        data-middlename="${user.middlename || ''}"
                                        data-lastname="${user.lastname || ''}"
                                        data-email="${user.email || ''}"
                                        data-username="${user.username || ''}"
                                        data-phone="${user.phone_number || ''}"
                                        data-role="${user.employee_type || ''}"
                                        data-gender="${user.gender || ''}"
                                        data-birthdate="${user.birthdate || ''}"
                                        data-joined="${user.created_at || ''}">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </button>
                                    ${canDelete ? `
                                    <button type="button" class="btn-delete flex-1 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg py-2 hover:bg-red-100 transition-colors"
                                        data-user-id="${user.user_id}"
                                        data-name="${fullName}">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });

                    // Update mobile cards container
                    $('#mobileCardsContainer').html(mobileCardsHTML);

                    // Update pagination
                    renderPagination(totalItems, totalPages, startIndex, endIndex);

                    attachEventHandlers();
                }
            }

            // Render pagination controls
            function renderPagination(totalItems, totalPages, startIndex, endIndex) {
                let paginationHTML = `
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium">${startIndex + 1}</span> to <span class="font-medium">${endIndex}</span> of <span class="font-medium">${totalItems}</span> employees
                    </div>
                    <div class="flex gap-1" id="paginationButtons">
                `;

                // Previous button
                paginationHTML += `
                    <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50 btn-page-prev" ${currentPage === 1 ? 'disabled' : ''}>
                        &laquo; Prev
                    </button>
                `;

                // Page numbers
                const maxVisiblePages = 3;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    if (i === currentPage) {
                        paginationHTML += `<button class="px-3 py-1 text-sm bg-primary text-white rounded">${i}</button>`;
                    } else {
                        paginationHTML += `<button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50 btn-page" data-page="${i}">${i}</button>`;
                    }
                }

                // Next button
                paginationHTML += `
                    <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50 btn-page-next" ${currentPage === totalPages ? 'disabled' : ''}>
                        Next &raquo;
                    </button>
                `;

                paginationHTML += '</div>';

                $('#paginationSection').html(paginationHTML);

                // Attach pagination handlers
                $('.btn-page').off('click').on('click', function () {
                    currentPage = parseInt($(this).data('page'));
                    applyFilters();
                });

                $('.btn-page-prev').off('click').on('click', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        applyFilters();
                    }
                });

                $('.btn-page-next').off('click').on('click', function () {
                    if (currentPage < totalPages) {
                        currentPage++;
                        applyFilters();
                    }
                });
            }

            function attachEventHandlers() {
                // View/Edit button handler
                $('.btn-edit').off('click').on('click', function () {
                    const btn = $(this);
                    const firstname = btn.data('firstname');
                    const middlename = btn.data('middlename');
                    const lastname = btn.data('lastname');
                    const fullName = `${firstname} ${middlename || ''} ${lastname}`.trim();
                    const initials = getInitials(firstname, lastname);

                    $('#viewInitials').text(initials);
                    $('#viewName').text(fullName);
                    $('#viewEmail').text(btn.data('email') || 'N/A');
                    $('#viewUsername').text('@' + (btn.data('username') || 'N/A'));
                    $('#viewPhone').text(btn.data('phone') || 'N/A');
                    $('#viewRole').text(capitalizeRole(btn.data('role')));
                    $('#viewGender').text(btn.data('gender') || 'N/A');
                    $('#viewBirthdate').text(formatDate(btn.data('birthdate')));
                    $('#viewJoined').text(formatDate(btn.data('joined')));

                    // Store user_id and role for actions
                    $('#viewEditModal').data('user-id', btn.data('user-id'));
                    $('#viewEditModal').data('user-role', btn.data('role'));
                    $('#viewEditModal').data('user-name', fullName);

                    $('#viewEditModal').removeClass('hidden').addClass('flex');
                });

                // Delete button handler
                $('.btn-delete').off('click').on('click', function () {
                    const btn = $(this);
                    const userId = btn.data('user-id');
                    const employeeName = btn.data('name');
                    
                    // Prevent double click
                    if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                        return;
                    }

                    Confirm.delete(
                        `Are you sure you want to delete ${employeeName}? This action cannot be undone.`,
                        function () {
                            // User confirmed deletion
                            deleteUser(userId, employeeName, btn);
                        }
                    );
                });
            }
            // Search functionality (client-side filtering)
            $('#searchInput').on('keyup', function () {
                applyFilters();
            });

            // Filter functionality
            $('#roleFilter').on('change', function () {
                currentPage = 1; // Reset to first page on filter change
                applyFilters();
            });

            // Apply filters and re-render
            function applyFilters() {
                const searchTerm = $('#searchInput').val().toLowerCase().trim();
                const roleFilter = $('#roleFilter').val();

                let filteredUsers = allEmployees;

                // Filter by role
                if (roleFilter !== 'all') {
                    filteredUsers = filteredUsers.filter(user => {
                        const userRole = (user.employee_type || '').toLowerCase();
                        return userRole === roleFilter;
                    });
                }

                // Filter by search term
                if (searchTerm) {
                    filteredUsers = filteredUsers.filter(user => {
                        const fullName = `${user.firstname} ${user.middlename || ''} ${user.lastname}`.toLowerCase();
                        const email = (user.email || '').toLowerCase();
                        const username = (user.username || '').toLowerCase();
                        const phone = (user.phone_number || '').toLowerCase();
                        return fullName.includes(searchTerm) || email.includes(searchTerm) || 
                               username.includes(searchTerm) || phone.includes(searchTerm);
                    });
                }

                // Reset to page 1 if current page exceeds total pages
                const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
                if (currentPage > totalPages && totalPages > 0) {
                    currentPage = 1;
                }

                renderEmployees(filteredUsers);
            }

            // Change Role Modal handlers
            $('.btn-role').on('click', function () {
                const userId = $('#viewEditModal').data('user-id');
                const userName = $('#viewEditModal').data('user-name');
                const userRole = $('#viewEditModal').data('user-role');
                const userEmail = $('#viewEmail').text();
                const initials = $('#viewInitials').text();

                console.log('Opening change role modal for:', userName, 'Current role:', userRole);

                // Populate the change role modal
                $('#roleModalInitials').text(initials);
                $('#roleModalName').text(userName);
                $('#roleModalEmail').text(userEmail);

                // Store user data in the modal
                $('#changeRoleModal').data('user-id', userId);
                $('#changeRoleModal').data('current-role', userRole);

                // Populate role dropdown based on current user's privilege level
                const currentEmployeeType = currentUser.employeeType.toLowerCase();
                let roleOptions = '<option value="">Select a role...</option>';

                if (currentEmployeeType === 'owner') {
                    // Owner can assign all roles
                    roleOptions += '<option value="owner">Owner</option>';
                    roleOptions += '<option value="admin">Admin</option>';
                    roleOptions += '<option value="staff">Staff</option>';
                } else if (currentEmployeeType === 'admin') {
                    // Admin can only assign staff and admin roles
                    roleOptions += '<option value="admin">Admin</option>';
                    roleOptions += '<option value="staff">Staff</option>';
                }

                $('#newRole').html(roleOptions);

                // Hide view modal
                $('#viewEditModal').removeClass('flex').addClass('hidden');

                // Show change role modal
                $('#changeRoleModal').removeClass('hidden').addClass('flex');

                // Set focus to dropdown after animation
                setTimeout(() => {
                    $('#newRole').focus();
                }, 100);
            });

            $('#closeRoleModal, #cancelRoleChange').on('click', function () {
                $('#changeRoleModal').removeClass('flex').addClass('hidden');
                $('#newRole').val('');
            });

            $('#confirmRoleChange').on('click', function () {
                const btn = $(this);
                const newRole = $('#newRole').val();
                const userId = $('#changeRoleModal').data('user-id');
                const currentRole = $('#changeRoleModal').data('current-role');
                
                // Prevent double submission
                if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                    return;
                }

                if (!newRole) {
                    Toast.warning('Please select a role');
                    return;
                }

                if (newRole.toLowerCase() === currentRole.toLowerCase()) {
                    Toast.info('Employee already has this role');
                    return;
                }

                // Call the change user role function
                changeUserRole(userId, newRole, btn);
            });

            // View/Edit Modal close handlers
            $('#closeViewEditModal, #closeViewBtn').on('click', function () {
                $('#viewEditModal').removeClass('flex').addClass('hidden');
            });

            // Prevent modal content click from closing
            $('#changeRoleModal .relative.bg-white, #viewEditModal .relative.bg-white').on('click', function (e) {
                e.stopPropagation();
            });
        });
    </script>