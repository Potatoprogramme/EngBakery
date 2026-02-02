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
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Employee Approvals
                            <span class="ml-2 bg-white text-primary text-xs font-bold px-2 py-0.5 rounded-full" id="pendingBadge">3</span>
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
                            <p class="text-lg font-semibold text-gray-800">12</p>
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
                            <p class="text-lg font-semibold text-gray-800">10</p>
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
                            <p class="text-lg font-semibold text-gray-800">2</p>
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
                            <p class="text-lg font-semibold text-gray-800">3</p>
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
                            <option value="cashier">Cashier</option>
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="employeeTableBody">
                            <!-- Static Employee Data -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-primary">JD</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">John Doe</div>
                                            <div class="text-sm text-gray-500">@johndoe</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">johndoe@email.com</div>
                                    <div class="text-sm text-gray-500">09123456789</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        Admin
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 15, 2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="btn-edit text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-delete text-red-600 hover:text-red-900 p-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-primary">MS</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Maria Santos</div>
                                            <div class="text-sm text-gray-500">@mariasantos</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">maria.santos@email.com</div>
                                    <div class="text-sm text-gray-500">09234567890</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        Cashier
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Feb 20, 2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="btn-edit text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-delete text-red-600 hover:text-red-900 p-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-primary">PR</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Pedro Reyes</div>
                                            <div class="text-sm text-gray-500">@pedroreyes</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">pedro.reyes@email.com</div>
                                    <div class="text-sm text-gray-500">09345678901</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        Staff
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mar 10, 2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="btn-edit text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-delete text-red-600 hover:text-red-900 p-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-primary">AG</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Ana Garcia</div>
                                            <div class="text-sm text-gray-500">@anagarcia</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">ana.garcia@email.com</div>
                                    <div class="text-sm text-gray-500">09456789012</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        Cashier
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Apr 5, 2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="btn-edit text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-delete text-red-600 hover:text-red-900 p-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-primary">RC</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Roberto Cruz</div>
                                            <div class="text-sm text-gray-500">@robertocruz</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">roberto.cruz@email.com</div>
                                    <div class="text-sm text-gray-500">09567890123</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        Owner
                                    </span>
                                </td>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 1, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="btn-edit text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-delete text-red-600 hover:text-red-900 p-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-3 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">12</span> employees
                    </div>
                    <div class="flex gap-1">
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50" disabled>&laquo; Prev</button>
                        <button class="px-3 py-1 text-sm bg-primary text-white rounded">1</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50">2</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50">3</button>
                        <button class="px-3 py-1 text-sm border border-gray-200 rounded hover:bg-gray-50">Next &raquo;</button>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-3">
                <!-- Employee Card 1 -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-primary">JD</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">John Doe</h3>
                                <p class="text-xs text-gray-500 truncate">johndoe@email.com</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                Admin
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                            <span><i class="fas fa-phone mr-1"></i>09123456789</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-gray-700">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>Active
                            </span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <button type="button" class="btn-edit flex-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                        <button type="button" class="btn-delete flex-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>

                <!-- Employee Card 2 -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-primary">MS</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">Maria Santos</h3>
                                <p class="text-xs text-gray-500 truncate">maria.santos@email.com</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-gray-700">
                                Cashier
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                            <span><i class="fas fa-phone mr-1"></i>09234567890</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                <span class="w-1.5 h-1.5 bg-gree-500 rounded-full mr-1"></span>Active
                            </span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <button type="button" class="btn-edit flex-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                        <button type="button" class="btn-delete flex-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>

                <!-- Employee Card 3 -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-primary">PR</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">Pedro Reyes</h3>
                                <p class="text-xs text-gray-500 truncate">pedro.reyes@email.com</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                Staff
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                            <span><i class="fas fa-phone mr-1"></i>09345678901</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1"></span>Active
                            </span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <button type="button" class="btn-edit flex-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                        <button type="button" class="btn-delete flex-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Role Modal -->
    <div id="changeRoleModal" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="roleModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-lg max-h-full mx-auto mt-20">
            <div class="relative bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Change Employee Role</h3>
                    <button type="button" id="closeRoleModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                        <label for="newRole" class="block mb-2 text-sm font-medium text-gray-900">Select New Role <span class="text-red-500">*</span></label>
                        <select id="newRole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                            <option value="">Select a role...</option>
                            <option value="admin">Admin</option>
                            <option value="cashier">Cashier</option>
                            <option value="staff">Staff</option>
                            <option value="owner">Owner</option>
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
    <div id="viewEditModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="viewEditModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-lg max-h-full mx-auto mt-20">
            <div class="relative bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Employee Details</h3>
                    <button type="button" id="closeViewEditModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-bold text-primary" id="viewInitials">JD</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-semibold text-gray-900" id="viewName">John Doe</h4>
                            <p class="text-sm text-gray-500" id="viewEmail">johndoe@email.com</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-100 text-gray-700" id="viewStatus">
                            Active
                        </span>
                    </div>
                    <div class="grid gap-4 grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Username</label>
                            <p class="text-sm font-medium text-gray-900" id="viewUsername">@johndoe</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Phone</label>
                            <p class="text-sm font-medium text-gray-900" id="viewPhone">09123456789</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Role</label>
                            <p class="text-sm font-medium text-gray-900" id="viewRole">Admin</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Gender</label>
                            <p class="text-sm font-medium text-gray-900" id="viewGender">Male</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Birthdate</label>
                            <p class="text-sm font-medium text-gray-900" id="viewBirthdate">January 15, 1995</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Joined</label>
                            <p class="text-sm font-medium text-gray-900" id="viewJoined">January 15, 2025</p>
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

        $(document).ready(function () {
            // Search functionality (client-side for static data)
            $('#searchInput').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                // Add search logic here when backend is implemented
                console.log('Searching for:', searchTerm);
            });

            // Filter functionality
            $('#roleFilter, #statusFilter').on('change', function() {
                const role = $('#roleFilter').val();
                const status = $('#statusFilter').val();
                // Add filter logic here when backend is implemented
                console.log('Filtering by role:', role, 'status:', status);
            });

            // Change Role Modal handlers
            $('.btn-role').on('click', function() {
                $('#changeRoleModal').removeClass('hidden').addClass('flex');
            });

            $('#closeRoleModal, #cancelRoleChange, #roleModalBackdrop').on('click', function() {
                $('#changeRoleModal').removeClass('flex').addClass('hidden');
            });

            $('#confirmRoleChange').on('click', function() {
                const newRole = $('#newRole').val();
                if (!newRole) {
                    Toast.warning('Please select a role');
                    return;
                }
                // Add role change logic here when backend is implemented
                console.log('Changing role to:', newRole);
                $('#changeRoleModal').removeClass('flex').addClass('hidden');
            });

            // Delete Employee
            $('.btn-delete').on('click', function() {
                const employeeName = $(this).closest('tr, .bg-white').find('.text-sm.font-medium.text-gray-900, .text-sm.font-semibold.text-gray-900').first().text();
                
                Confirm.delete(`Are you sure you want to delete ${employeeName}? This action cannot be undone.`, function() {
                    // Add delete logic here when backend is implemented
                    console.log('Deleting employee:', employeeName);
                    Toast.success('Employee deleted successfully.');
                });
            });

            // View/Edit Modal handlers
            $('.btn-edit').on('click', function() {
                $('#viewEditModal').removeClass('hidden').addClass('flex');
            });

            $('#closeViewEditModal, #closeViewBtn, #viewEditModalBackdrop').on('click', function() {
                $('#viewEditModal').removeClass('flex').addClass('hidden');
            });

            // Prevent modal content click from closing
            $('#changeRoleModal .relative.bg-white, #viewEditModal .relative.bg-white').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
