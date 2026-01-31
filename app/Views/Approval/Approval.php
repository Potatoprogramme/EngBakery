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
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                        <span id="pendingCount">3</span> Pending
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
                        <select id="statusFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-1 focus:ring-primary focus:border-primary">
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
                
                <!-- Pending Card 1 -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                Pending
                            </span>
                            <span class="text-xs text-gray-400">Jan 30, 2026</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-gray-600">JD</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">John Doe</h3>
                                <p class="text-xs text-gray-500 truncate">johndoe@email.com</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Cashier</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-store mr-1"></i>Deca Sentrio</span>
                            <span><i class="fas fa-clock mr-1"></i>Morning Shift</span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <button type="button" class="btn-approve flex-1 text-sm font-medium text-white bg-green-600 rounded-lg py-2 hover:bg-green-700 transition-colors">
                            Approve
                        </button>
                        <button type="button" class="btn-reject flex-1 text-sm font-medium text-white bg-red-500 rounded-lg py-2 hover:bg-red-600 transition-colors">
                            Reject
                        </button>
                        <button type="button" class="btn-view px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Pending Card 2 -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                Pending
                            </span>
                            <span class="text-xs text-gray-400">Jan 29, 2026</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-orange-50 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-orange-600">MS</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">Maria Santos</h3>
                                <p class="text-xs text-gray-500 truncate">maria.santos@email.com</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Baker</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-store mr-1"></i>Deca Sentrio</span>
                            <span><i class="fas fa-clock mr-1"></i>Night Shift</span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <button type="button" class="btn-approve flex-1 text-sm font-medium text-white bg-green-600 rounded-lg py-2 hover:bg-green-700 transition-colors">
                            Approve
                        </button>
                        <button type="button" class="btn-reject flex-1 text-sm font-medium text-white bg-red-500 rounded-lg py-2 hover:bg-red-600 transition-colors">
                            Reject
                        </button>
                        <button type="button" class="btn-view px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Pending Card 3 -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                Pending
                            </span>
                            <span class="text-xs text-gray-400">Jan 28, 2026</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-green-50 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-green-600">RC</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">Roberto Cruz</h3>
                                <p class="text-xs text-gray-500 truncate">roberto.cruz@email.com</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Staff</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-store mr-1"></i>Deca Sentrio</span>
                            <span><i class="fas fa-clock mr-1"></i>Morning Shift</span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                        <button type="button" class="btn-approve flex-1 text-sm font-medium text-white bg-green-600 rounded-lg py-2 hover:bg-green-700 transition-colors">
                            Approve
                        </button>
                        <button type="button" class="btn-reject flex-1 text-sm font-medium text-white bg-red-500 rounded-lg py-2 hover:bg-red-600 transition-colors">
                            Reject
                        </button>
                        <button type="button" class="btn-view px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Approved Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                Approved
                            </span>
                            <span class="text-xs text-gray-400">Jan 25, 2026</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-blue-600">AL</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">Ana Lim</h3>
                                <p class="text-xs text-gray-500 truncate">ana.lim@email.com</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Cashier</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-store mr-1"></i>Deca Sentrio</span>
                            <span class="text-green-600"><i class="fas fa-check mr-1"></i>By Admin</span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                        <button type="button" class="btn-view w-full text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Rejected Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                Rejected
                            </span>
                            <span class="text-xs text-gray-400">Jan 20, 2026</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-red-500">PG</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">Pedro Garcia</h3>
                                <p class="text-xs text-gray-500 truncate">pedro.garcia@email.com</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Baker</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-red-500">
                            <i class="fas fa-info-circle mr-1"></i>Incomplete documents
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                        <button type="button" class="btn-view w-full text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg py-2 hover:bg-gray-50 transition-colors">
                            View Details
                        </button>
                    </div>
                </div>

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
    <div id="viewDetailsModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="viewModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-lg max-h-full mx-auto mt-20">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Employee Details</h3>
                    <button type="button" id="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <!-- Employee Info Header -->
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-primary" id="modalInitials">JD</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base font-semibold text-gray-900" id="modalName">John Doe</h4>
                            <p class="text-sm text-gray-500" id="modalEmail">johndoe@email.com</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200" id="modalStatus">
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
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Address</label>
                            <p class="text-sm font-medium text-gray-900" id="modalAddress">123 Sample Street, City</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 uppercase">Registration Date</label>
                            <p class="text-sm font-medium text-gray-900" id="modalDate">January 30, 2026</p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200">
                    <button type="button" id="modalApprove" class="flex-1 inline-flex justify-center items-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve
                    </button>
                    <button type="button" id="modalReject" class="flex-1 inline-flex justify-center items-center text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                    <button type="button" id="modalClose" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Reason Modal -->
    <div id="rejectReasonModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="fixed inset-0 bg-black/50 transition-opacity" id="rejectModalBackdrop"></div>
        <div class="relative p-4 w-full max-w-md max-h-full mx-auto mt-20">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Reject Registration</h3>
                    <button type="button" id="closeRejectModal" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <div class="grid gap-4">
                        <div>
                            <label for="rejectReasonSelect" class="block mb-2 text-sm font-medium text-gray-900">Reason <span class="text-red-500">*</span></label>
                            <select id="rejectReasonSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                                <option value="">Select a reason...</option>
                                <option value="incomplete">Incomplete documents</option>
                                <option value="invalid">Invalid information</option>
                                <option value="duplicate">Duplicate registration</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="rejectReasonText" class="block mb-2 text-sm font-medium text-gray-900">Notes <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <textarea id="rejectReasonText" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary" placeholder="Additional details..."></textarea>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200">
                    <button type="button" id="confirmReject" class="flex-1 inline-flex justify-center items-center text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Confirm Reject
                    </button>
                    <button type="button" id="cancelReject" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.BASE_URL = '<?= base_url() ?>';

        $(document).ready(function() {
            // View Details Modal
            $('.btn-view').on('click', function() {
                $('#viewDetailsModal').removeClass('hidden').addClass('flex');
            });

            $('#closeModal, #modalClose, #viewModalBackdrop').on('click', function() {
                $('#viewDetailsModal').removeClass('flex').addClass('hidden');
            });

            // Reject Modal
            $('.btn-reject, #modalReject').on('click', function() {
                $('#viewDetailsModal').removeClass('flex').addClass('hidden');
                $('#rejectReasonModal').removeClass('hidden').addClass('flex');
            });

            $('#closeRejectModal, #cancelReject, #rejectModalBackdrop').on('click', function() {
                $('#rejectReasonModal').removeClass('flex').addClass('hidden');
            });

            // Approve action (placeholder)
            $('.btn-approve, #modalApprove').on('click', function() {
                showToast('success', 'Employee approved successfully!');
                $('#viewDetailsModal').removeClass('flex').addClass('hidden');
            });

            // Confirm Reject action (placeholder)
            $('#confirmReject').on('click', function() {
                const reason = $('#rejectReasonSelect').val();
                if (!reason) {
                    showToast('warning', 'Please select a rejection reason.');
                    return;
                }
                showToast('info', 'Employee registration rejected.');
                $('#rejectReasonModal').removeClass('flex').addClass('hidden');
            });

            // Search functionality (placeholder)
            $('#searchInput').on('input', function() {
                const query = $(this).val().toLowerCase();
                // Add search logic here
            });

            // Filter functionality (placeholder)
            $('#statusFilter, #roleFilter').on('change', function() {
                // Add filter logic here
            });

            // Prevent modal content click from closing
            $('#viewDetailsModal .relative.bg-white, #rejectReasonModal .relative.bg-white').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
