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
                    <li class="text-gray-700">Employee Approval</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Employee Approval</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Review and approve registration requests</p>
                    </div>
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                        <span id="pendingCount">0</span>&nbsp;Pending
                    </span>
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
                            placeholder="Search by name or email...">
                    </div>
                    <div class="flex">
                        <select id="statusFilter"
                            class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-1 focus:ring-primary focus:border-primary">
                            <option value="all">All Status</option>
                            <option value="pending" selected>Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Approval Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="approvalCardsContainer">

            </div>

            <!-- Empty State (hidden by default) -->
            <div id="emptyState" class="hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-check text-2xl text-gray-300"></i>
                    </div>
                    <h3 class="text-base font-medium text-gray-900 mb-1">No Pending Approvals</h3>
                    <p class="text-gray-500 text-sm">All registration requests have been processed.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- View Details Modal -->
    <div id="viewDetailsModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="viewModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-lg max-h-full mx-auto mt-20">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Employee Details</h3>
                    <button type="button" id="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <!-- Employee Info Header -->
                    <div class="flex items-center gap-4 mb-5">
                        <div
                            class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-primary" id="modalInitials">JD</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base font-semibold text-gray-900" id="modalName">John Doe</h4>
                            <p class="text-sm text-gray-500" id="modalEmail">johndoe@email.com</p>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200"
                            id="modalStatus">
                            Pending
                        </span>
                    </div>
                    <!-- Details Grid -->
                    <div class="grid gap-4 grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Phone</label>
                            <p class="text-sm font-medium text-gray-900" id="modalPhone">09123456789</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Role</label>
                            <p class="text-sm font-medium text-gray-900" id="modalRole">Cashier</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Outlet</label>
                            <p class="text-sm font-medium text-gray-900" id="modalOutlet">Deca Sentrio</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Shift</label>
                            <p class="text-sm font-medium text-gray-900" id="modalShift">Morning</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Registration
                                Date</label>
                            <p class="text-sm font-medium text-gray-900" id="modalDate">January 30, 2026</p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200">
                    <button type="button" id="modalApprove"
                        class="flex-1 inline-flex justify-center items-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve
                    </button>
                    <button type="button" id="modalReject"
                        class="flex-1 inline-flex justify-center items-center text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reject
                    </button>
                    <button type="button" id="modalClose"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Reason Modal -->
    <div id="rejectReasonModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="rejectModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-md max-h-full mx-auto mt-20">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Reject Registration</h3>
                    <button type="button" id="closeRejectModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <div class="grid gap-4">
                        <div>
                            <label for="rejectReasonSelect" class="block mb-2 text-sm font-medium text-gray-900">Reason
                                <span class="text-red-500">*</span></label>
                            <select id="rejectReasonSelect"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                                <option value="">Select a reason...</option>
                                <option value="incomplete">Incomplete documents</option>
                                <option value="invalid">Invalid information</option>
                                <option value="duplicate">Duplicate registration</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="rejectReasonText" class="block mb-2 text-sm font-medium text-gray-900">Notes
                                <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <textarea id="rejectReasonText" rows="3"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary"
                                placeholder="Additional details..."></textarea>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200">
                    <button type="button" id="confirmReject"
                        class="flex-1 inline-flex justify-center items-center text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Confirm Reject
                    </button>
                    <button type="button" id="cancelReject"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.BASE_URL = '<?= base_url() ?>';

        $(document).ready(function () {
            // Store all users for filtering
            let allUsers = [];

            fetchPendingUsers();

            // Search functionality
            $('#searchInput').on('keyup', function () {
                applyFilters();
            });

            // Filter functionality
            $('#statusFilter').on('change', function () {
                applyFilters();
            });

            // Apply search and filter
            function applyFilters() {
                const searchTerm = $('#searchInput').val().toLowerCase().trim();
                const statusFilter = $('#statusFilter').val();

                let filteredUsers = allUsers;

                // Filter by status
                if (statusFilter !== 'all') {
                    filteredUsers = filteredUsers.filter(user => {
                        const userStatus = (user.status || 'pending').toLowerCase();
                        return userStatus === statusFilter;
                    });
                }

                // Filter by search term
                if (searchTerm) {
                    filteredUsers = filteredUsers.filter(user => {
                        const fullName = `${user.firstname} ${user.middlename || ''} ${user.lastname}`.toLowerCase();
                        const email = (user.email || '').toLowerCase();
                        const username = (user.username || '').toLowerCase();
                        return fullName.includes(searchTerm) || email.includes(searchTerm) || username.includes(searchTerm);
                    });
                }

                renderUsers(filteredUsers);
            }

            // Render users to the grid
            function renderUsers(users) {
                if (users.length === 0) {
                    $('#approvalCardsContainer').hide();
                    $('#emptyState').removeClass('hidden').addClass('block');
                    // Update pending count based on filter
                    const pendingOnly = allUsers.filter(u => (u.status || 'pending').toLowerCase() === 'pending');
                    $('#pendingCount').text(pendingOnly.length);
                } else {
                    $('#approvalCardsContainer').show().empty();
                    $('#emptyState').removeClass('block').addClass('hidden');
                    // Update pending count based on filter
                    const pendingOnly = allUsers.filter(u => (u.status || 'pending').toLowerCase() === 'pending');
                    $('#pendingCount').text(pendingOnly.length);

                    users.forEach(function (user) {
                        const initials = getInitials(user.firstname, user.lastname);
                        const fullName = `${user.firstname} ${user.middlename || ''} ${user.lastname}`.trim();
                        const formattedDate = formatDate(user.created_at);
                        const status = (user.status || 'pending').toLowerCase();

                        // Determine status badge style
                        let statusBadge = '';
                        let showActionButtons = false;
                        if (status === 'pending') {
                            statusBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">Pending</span>';
                            showActionButtons = true;
                        } else if (status === 'approved') {
                            statusBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Approved</span>';
                        } else if (status === 'rejected') {
                            statusBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">Rejected</span>';
                        }

                        const cardHTML = `
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow" data-user-id="${user.user_id}">
                            <div class="p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-11 h-11 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-semibold text-gray-600">${initials}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">${fullName}</h3>
                                        <p class="text-xs text-gray-500 truncate">${user.email}</p>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">${user.employee_type || 'Staff'}</span>
                                </div>
                                <div class="flex mt-3 pt-3 border-t border-gray-100 items-center justify-between mb-3">
                                    ${statusBadge}
                                    <span class="text-xs text-gray-400">${formattedDate}</span>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                                    <span><i class="fas fa-phone mr-1"></i>${user.phone_number || 'N/A'}</span>
                                    <span><i class="fas fa-user mr-1"></i>${user.username}</span>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                                ${showActionButtons ? `
                                <button type="button" class="btn-approve flex-1 text-sm font-medium text-white bg-green-600 rounded-lg py-2 hover:bg-green-700 transition-colors">
                                    Approve
                                </button>
                                <button type="button" class="btn-reject flex-1 text-sm font-medium text-white bg-red-500 rounded-lg py-2 hover:bg-red-600 transition-colors">
                                    Reject
                                </button>
                                ` : ''}
                                <button type="button" class="btn-view ${showActionButtons ? 'px-3' : 'flex-1'} py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye${showActionButtons ? '' : ' mr-1'}"></i>${showActionButtons ? '' : ' View Details'}
                                </button>
                            </div>
                        </div>
                    `;
                        $('#approvalCardsContainer').append(cardHTML);
                    });

                    attachCardEventHandlers();
                }
            }

            // Fetch all users (including approved/rejected)
            function fetchPendingUsers() {
                $.ajax({
                    url: window.BASE_URL + 'Approval/GetPendingUsers',
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            allUsers = response.data;

                            // Apply initial filter (pending by default)
                            applyFilters();
                        } else {
                            showToast('error', response.message || 'Failed to load users');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        showToast('error', 'Error loading users');
                    }
                });
            }

            // Helper functions
            function getInitials(firstname, lastname) {
                const first = firstname ? firstname.charAt(0).toUpperCase() : '';
                const last = lastname ? lastname.charAt(0).toUpperCase() : '';
                return first + last || 'NA';
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            }

            // Approve user
            function approveUser(userId) {
                $.ajax({
                    url: window.BASE_URL + 'Approval/ApproveUser',
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ user_id: userId, }),
                    success: function (response) {
                        if (response.success) {
                            showToast('success', 'User approved successfully.');
                            fetchPendingUsers();
                        } else {
                            showToast('error', response.message || 'Failed to approve user.');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        showToast('error', response.message || 'Error approving user.');
                    }
                });
            }

            // Reject user
            function rejectUser(userId) {
                $.ajax({
                    url: window.BASE_URL + 'Approval/RejectUser',
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ user_id: userId }),
                    success: function (response) {
                        if (response.success) {
                            showToast('success', 'User rejected successfully.');
                            fetchPendingUsers();
                        } else {
                            showToast('error', response.message || 'Failed to reject user.');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        showToast('error', 'Error rejecting user.');
                    }
                });
            }

            // View employee details
            function viewEmpDetails(userId) {
                $.ajax({
                    url: window.BASE_URL + 'Approval/ViewEmpDetails',
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ user_id: userId }),
                    success: function (response) {
                        if (response.success) {
                            const user = response.data;
                            const initials = getInitials(user.firstname, user.lastname);
                            const fullName = `${user.firstname} ${user.middlename || ''} ${user.lastname}`.trim();
                            const formattedDate = formatDate(user.created_at);

                            $('#modalInitials').text(initials);
                            $('#modalName').text(fullName);
                            $('#modalEmail').text(user.email);
                            $('#modalPhone').text(user.phone_number || 'N/A');
                            $('#modalRole').text(user.employee_type || 'Staff');
                            $('#modalDate').text(formattedDate);

                            $('#viewDetailsModal').data('current-user-id', user.user_id);
                            $('#viewDetailsModal').removeClass('hidden').addClass('flex');
                        } else {
                            showToast('error', response.message || 'Failed to load user details.');
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        showToast('error', 'Data failed to load.');
                    }
                });
            }

            // Attach event handlers to card buttons
            function attachCardEventHandlers() {
                $('.btn-view').off('click').on('click', function () {
                    const userId = $(this).closest('[data-user-id]').data('user-id');
                    viewEmpDetails(userId);
                });

                $('.btn-approve').off('click').on('click', function () {
                    const userId = $(this).closest('[data-user-id]').data('user-id');
                    approveUser(userId);
                });

                $('.btn-reject').off('click').on('click', function () {
                    const userId = $(this).closest('[data-user-id]').data('user-id');
                    $('#rejectReasonModal').data('current-user-id', userId);
                    $('#rejectReasonModal').removeClass('hidden').addClass('flex');
                });
            }

            // Modal approve button
            $('#modalApprove').on('click', function () {
                const userId = $('#viewDetailsModal').data('current-user-id');
                if (userId) {
                    approveUser(userId);
                    $('#viewDetailsModal').removeClass('flex').addClass('hidden');
                }
            });

            // Modal reject button
            $('#modalReject').on('click', function () {
                const userId = $('#viewDetailsModal').data('current-user-id');
                $('#viewDetailsModal').removeClass('flex').addClass('hidden');
                $('#rejectReasonModal').data('current-user-id', userId);
                $('#rejectReasonModal').removeClass('hidden').addClass('flex');
            });

            // Confirm reject
            $('#confirmReject').on('click', function () {
                const reason = $('#rejectReasonSelect').val();
                const userId = $('#rejectReasonModal').data('current-user-id');

                if (!reason) {
                    showToast('warning', 'Please select a rejection reason.');
                    return;
                }

                rejectUser(userId);
                $('#rejectReasonModal').removeClass('flex').addClass('hidden');
                $('#rejectReasonSelect').val('');
                $('#rejectReasonText').val('');
            });

            // Close modals
            $('#closeModal, #modalClose, #viewModalBackdrop').on('click', function () {
                $('#viewDetailsModal').removeClass('flex').addClass('hidden');
            });

            $('#closeRejectModal, #cancelReject, #rejectModalBackdrop').on('click', function () {
                $('#rejectReasonModal').removeClass('flex').addClass('hidden');
            });

            // Prevent modal content click from closing
            $('#viewDetailsModal .relative.bg-white, #rejectReasonModal .relative.bg-white').on('click', function (e) {
                e.stopPropagation();
            });
        });
    </script>