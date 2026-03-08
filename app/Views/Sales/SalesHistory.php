<body class="bg-gray-50">
    <div class="p-4 sm:ml-60">
        <div class="mt-16">
            <nav class="mb-3 sm:mb-4" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 text-sm text-gray-500 justify-left sm:justify-start">
                    <li><a href="<?= base_url('Dashboard') ?>" class="hover:text-primary">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Transactions History</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Transactions History
                    </h2>
                    <!-- <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales') ?>"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Today's Remittance
                        </a>
                    </div> -->
                </div>
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateFrom"
                                class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">From:</label>
                            <input type="date" id="filterDateFrom"
                                class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateTo"
                                class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">To:</label>
                            <input type="date" id="filterDateTo"
                                class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <button id="btnApplyFilters" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                        <button id="btnResetFilters" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        </button>
                        <!-- Enable Export Button -->
                        <!-- <button type="button" id="btnExportCsv"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-file-csv mr-2"></i>Export
                        </button> -->
                        <!-- Disable Export Button -->
                        <button type="button" id="btnExportCsv" disabled
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-file-csv mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards - Row 1: Main Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
                <!-- Total Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-primary" id="summaryTotalSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-primary/10 rounded-full hidden sm:block">
                            <i class="fas fa-peso-sign text-primary text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                            <p class="text-lg sm:text-2xl font-bold text-blue-600" id="summaryTotalOrders">0</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-blue-100 rounded-full hidden sm:block">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Cash Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Cash Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600" id="summaryCashSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-green-100 rounded-full hidden sm:block">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- GCash Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">GCash Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-blue-500" id="summaryGcashSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-blue-50 rounded-full hidden sm:block">
                            <svg class="w-5 h-auto" viewBox="0 0 72 61" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M65.0342 30.6293C65.0461 36.0802 63.7663 41.4564 61.2995 46.3172C60.9014 47.0923 60.8077 47.9883 61.0369 48.8289C61.2661 49.6695 61.8017 50.394 62.5381 50.8597C62.9464 51.118 63.4042 51.2881 63.8821 51.3593C64.36 51.4305 64.8475 51.4012 65.3133 51.2732C65.7792 51.1452 66.2133 50.9213 66.5877 50.6159C66.9621 50.3105 67.2686 49.9303 67.4876 49.4996C70.4634 43.6551 72.01 37.1878 72.0001 30.6293C72.0103 24.0822 70.4693 17.6258 67.5034 11.7889C67.2845 11.3579 66.9781 10.9774 66.6036 10.6717C66.2291 10.366 65.7949 10.1418 65.3289 10.0137C64.8628 9.88548 64.375 9.85607 63.8969 9.9273C63.4188 9.99854 62.9608 10.1688 62.5523 10.4273C61.8165 10.8932 61.2815 11.6174 61.0524 12.4576C60.8232 13.2978 60.9164 14.1932 61.3137 14.9682C63.7713 19.8222 65.0462 25.1886 65.0342 30.6293Z" fill="#6FBAF7"/><path d="M53.7727 30.6292C53.7787 34.0085 53.0509 37.3489 51.6395 40.4194C51.2859 41.1877 51.2238 42.0584 51.4647 42.8692C51.7057 43.68 52.2332 44.3755 52.9491 44.8261C53.3693 45.0901 53.8411 45.2613 54.3329 45.3284C54.8246 45.3955 55.3251 45.357 55.8007 45.2152C56.2764 45.0735 56.7163 44.8319 57.0911 44.5066C57.466 44.1813 57.7671 43.7798 57.9743 43.3288C59.8047 39.3457 60.7484 35.0127 60.7402 30.6292C60.7484 26.2573 59.8097 21.9356 57.9885 17.9611C57.7818 17.5097 57.481 17.1076 57.1063 16.7818C56.7316 16.456 56.2916 16.2141 55.8158 16.0722C55.3399 15.9303 54.8393 15.8916 54.3474 15.9589C53.8554 16.0262 53.3835 16.1978 52.9633 16.4622C52.2478 16.9119 51.7202 17.6065 51.479 18.4164C51.2378 19.2263 51.2993 20.0963 51.6521 20.8642C53.0556 23.928 53.779 27.2593 53.7727 30.6292Z" fill="#6FBAF7"/><path d="M30.3662 54.0323C27.2911 54.0408 24.245 53.4392 21.404 52.2625C18.563 51.0857 15.9836 49.3571 13.8152 47.1768C11.635 45.0086 9.90655 42.4295 8.7298 39.5888C7.55306 36.7481 6.95143 33.7022 6.95973 30.6274C6.95122 27.5523 7.55277 24.5062 8.72952 21.6652C9.90627 18.8242 11.6349 16.2448 13.8152 14.0764C15.9836 11.896 18.563 10.1674 21.404 8.99069C24.245 7.81394 27.2911 7.21241 30.3662 7.22091C35.5409 7.19871 40.5738 8.91135 44.6609 12.0852C45.3296 12.6057 46.1651 12.8644 47.0109 12.813C47.8567 12.7616 48.6547 12.4036 49.2555 11.806C49.6048 11.4569 49.8756 11.0372 50.0498 10.5751C50.224 10.113 50.2975 9.61899 50.2656 9.12616C50.2336 8.63333 50.0968 8.153 49.8644 7.71725C49.632 7.28149 49.3093 6.90035 48.9178 6.59928C43.6111 2.48142 37.0831 0.250108 30.3662 0.258161C26.3773 0.247693 22.4259 1.02819 18.7405 2.55454C15.0552 4.08089 11.7091 6.32278 8.89567 9.15054C6.06663 11.9641 3.82372 15.3109 2.2968 18.9971C0.769876 22.6834 -0.0107431 26.6359 0.000111667 30.6258C-0.00185956 34.6147 0.782812 38.5648 2.30922 42.25C3.83563 45.9353 6.07382 49.2834 8.89567 52.1026C11.7097 54.932 15.057 57.1753 18.7437 58.7025C22.4305 60.2297 26.3835 61.0105 30.3741 60.9998C37.1111 61.0186 43.6603 58.78 48.9762 54.6413C49.3595 54.3422 49.6749 53.9652 49.9016 53.535C50.1282 53.1048 50.2608 52.6314 50.2906 52.1462C50.3204 51.6609 50.2468 51.1748 50.0746 50.7201C49.9024 50.2654 49.6356 49.8525 49.2917 49.5088L49.2176 49.4362C48.628 48.8463 47.8435 48.4915 47.0111 48.4384C46.1787 48.3852 45.3555 48.6373 44.6957 49.1474C40.5955 52.3243 35.5531 54.0433 30.3662 54.0323Z" fill="#007CFF"/><path d="M48.5683 28.1512C47.911 27.4937 47.0194 27.1242 46.0896 27.1241L33.8949 27.1382C32.965 27.1387 32.0733 27.5084 31.4158 28.1661C30.7584 28.8238 30.3891 29.7157 30.3891 30.6457C30.3891 31.576 30.7586 32.4683 31.4163 33.1264C32.074 33.7844 32.9661 34.1542 33.8965 34.1546H33.9186L42.0694 34.1436C41.2881 36.7293 39.6721 38.9833 37.4741 40.5533C35.276 42.1233 32.6198 42.9209 29.9205 42.8215C23.3301 42.5801 18.0871 37.0546 18.177 30.4611C18.221 27.2541 19.5258 24.1933 21.8091 21.941C24.0925 19.6886 27.1708 18.4258 30.3781 18.4257C32.6747 18.4198 34.9258 19.0666 36.8691 20.2907C37.5449 20.7117 38.3437 20.8908 39.1345 20.7985C39.9254 20.7062 40.6615 20.348 41.2222 19.7826C41.5927 19.4131 41.8753 18.965 42.0493 18.4715C42.2233 17.978 42.2841 17.4517 42.2273 16.9315C42.1705 16.4113 41.9975 15.9105 41.7211 15.4662C41.4447 15.0219 41.072 14.6454 40.6305 14.3645C36.4604 11.7233 31.3721 10.7671 26.2174 11.84C22.6048 12.6048 19.2943 14.4083 16.6926 17.0288C14.0908 19.6493 12.3112 22.9727 11.5724 26.5907C9.04795 38.9432 18.4626 49.8426 30.3781 49.8426C35.473 49.8368 40.3577 47.8102 43.9604 44.2075C47.5631 40.6048 49.5896 35.7201 49.5955 30.6252C49.5935 29.6972 49.2243 28.8077 48.5683 28.1512Z" fill="#002CB8"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards - Row 2: Category Breakdown -->
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4">
                <!-- Bakery Sales Card -->
                <!-- <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Bakery Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-amber-600" id="summaryBakerySales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-amber-100 rounded-full hidden sm:block">
                            <i class="fas fa-bread-slice text-amber-600 text-xl"></i>
                        </div>
                    </div> -->
            </div>

            <!-- Coffee/Drinks Sales Card -->
            <!-- <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Coffee/Drinks</p>
                            <p class="text-lg sm:text-2xl font-bold text-orange-600" id="summaryCoffeeSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-orange-100 rounded-full hidden sm:block">
                            <i class="fas fa-mug-hot text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div> -->

            <!-- Grocery Sales Card -->
            <!-- <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Grocery Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-emerald-600" id="summaryGrocerySales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-emerald-100 rounded-full hidden sm:block">
                            <i class="fas fa-shopping-basket text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                </div> -->
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
            <table id="salesHistoryTable" class="min-w-full text-sm text-left text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Order #</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Date</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Time</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Product</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Quantity</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Total</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="salesHistoryTableBody">
                    <!-- Data will be populated by JS -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3 mb-20" id="salesHistoryCards">
            <!-- Cards will be populated by JS -->
        </div>
    </div>
    </div>

    <!-- Sales Details Modal -->
    <div id="salesDetailsModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div
            class="relative w-full max-w-2xl mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Sticky Header -->
            <div class="sticky top-0 bg-white z-10 p-6 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Sales Details</h3>
                        <p class="text-sm text-gray-500" id="detailDate">-</p>
                    </div>
                    <button type="button" id="btnCloseDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="space-y-4">
                    <!-- Cashier Info -->
                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm text-gray-500">Cashier:</span>
                            <p class="font-semibold text-gray-800" id="detailCashier">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Outlet:</span>
                            <p class="font-semibold text-gray-800" id="detailOutlet">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Order ID:</span>
                            <p class="font-semibold text-gray-800" id="detailOrderCount">0</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Order Type:</span>
                            <p class="font-semibold text-gray-800" id="detailOrderType">-</p>
                        </div>
                    </div>

                    <!-- Distributed Note (shown only for distributed orders) -->
                    <div id="detailDistributedNoteContainer"
                        class="hidden p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-truck text-blue-500 mt-0.5"></i>
                            <div>
                                <span class="text-sm font-medium text-blue-700">Delivery Note:</span>
                                <p class="text-sm text-blue-800 mt-1" id="detailDistributedNote">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Breakdown -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Order Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-shopping-bag text-blue-500 mr-2"></i>Products:</span>
                                <span class="font-semibold text-sm" id="detailBakery">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-boxes text-orange-500 mr-2"></i>Total
                                    Quantity:</span>
                                <span class="font-semibold" id="detailCoffee">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-list text-green-500 mr-2"></i>Total
                                    Items:</span>
                                <span class="font-semibold" id="detailGrocery">0</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Total Sales:</span>
                                <span class="font-bold text-primary text-lg" id="detailTotalSales">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Distribution (Category Breakdown) -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Order Distribution</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600"><i
                                        class="fas fa-bread-slice text-amber-500 mr-2"></i>Bakery:</span>
                                <span class="font-semibold text-gray-800" id="detailBakeryAmount">₱0.00</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-100">
                                <span class="text-gray-600"><i
                                        class="fas fa-mug-hot text-orange-500 mr-2"></i>Coffee/Drinks:</span>
                                <span class="font-semibold text-gray-800" id="detailCoffeeAmount">₱0.00</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-100">
                                <span class="text-gray-600"><i
                                        class="fas fa-shopping-basket text-green-500 mr-2"></i>Grocery:</span>
                                <span class="font-semibold text-gray-800" id="detailGroceryAmount">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Payment Methods</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-money-bill text-green-500 mr-2"></i>Cash:</span>
                                <span class="font-semibold" id="detailCash">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 flex items-center"><img src="<?= base_url('assets/pictures/gcash.svg') ?>" class="inline w-4 h-4 mr-2 flex-shrink-0" alt="GCash">GCash:</span>
                                <span class="font-semibold" id="detailGcash">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 flex items-center"><img src="<?= base_url('assets/pictures/food-panda.svg') ?>" class="inline w-4 h-4 mr-2 flex-shrink-0" alt="FoodPanda">Food Panda:</span>
                                <span class="font-semibold" id="detailFoodPanda">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Variance -->
                    <div class="p-4 rounded-lg" id="detailVarianceContainer">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <span class="font-bold text-gray-800">Overage/Shortage:</span>
                            <span class="font-bold text-xl" id="detailVariance">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Footer -->
            <div class="sticky bottom-0 bg-white z-10 px-6 pb-6 pt-4 border-t border-gray-200">
                <div class="flex gap-2">
                    <button type="button" id="btnPrintDetails"
                        class="flex-1 px-4 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                    <button type="button" id="btnCloseModal"
                        class="flex-1 px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
        let dataTable = null;
        let salesData = []; // Will be populated from API
        let todaysSales = null; // Today's sales before remittance

        $(document).ready(function () {
            initFilters();
            loadSalesHistory();
            getSummaryDetails();
            initDetailsModal();
        });

        /**
         * Load sales history from API
         */
        function loadSalesHistory() {
            console.log('Loading sales history...');
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();

            // Show loading state
            $('#salesHistoryTableBody').html('<tr><td colspan="10" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin text-4xl mb-3"></i><p>Loading sales history...</p></td></tr>');
            $('#salesHistoryCards').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-spinner fa-spin text-4xl mb-3"></i><p>Loading...</p></div>');

            // Fetch sales history from API
            $.ajax({
                url: BASE_URL + 'Sales/GetSalesHistory',
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Sales history loaded:', response.data);
                        renderSalesHistory(response.data);
                    } else {
                        showToast('error', response.message || 'Failed to load sales history');
                        salesData = [];
                        renderSalesHistory([]);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading sales history:', error);
                    showToast('error', 'Failed to load sales history');
                    salesData = [];
                    renderSalesHistory([]);
                }
            });
        }

        function getSummaryDetails() {
            console.log('Loading summary details...');
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();
            $.ajax({
                url: BASE_URL + 'Sales/GetSummaryDetails',
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Summary Details loaded:', response.data);
                        updateSummaryCards(response.data);
                    } else {
                        showToast('error', response.message || 'Failed to load summary details');
                        // Set all to 0
                        updateSummaryCards({
                            total_sales: 0,
                            total_orders: 0,
                            cash_sales: 0,
                            gcash_sales: 0,
                            bakery_sales: 0,
                            coffee_sales: 0,
                            grocery_sales: 0
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading summary details:', xhr);
                    showToast('error', 'Failed to load summary details');
                    // Set all to 0
                    updateSummaryCards({
                        total_sales: 0,
                        total_orders: 0,
                        cash_sales: 0,
                        gcash_sales: 0,
                        bakery_sales: 0,
                        coffee_sales: 0,
                        grocery_sales: 0
                    });
                }
            });
        }

        function getTransactionDetails(orderId) {
            console.log('Loading transaction details...');
            $.ajax({
                url: BASE_URL + 'Sales/GetTransactionDetails',
                type: 'POST',
                data: JSON.stringify({ order_id: orderId }),
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Transaction details loaded:', response.data);
                        openDetailsModal(response.data);
                    } else {
                        showToast('error', response.message || 'Failed to load transaction details');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading transaction details:', xhr);
                    showToast('error', 'Failed to load transaction details');
                }
            });
        }

        function initFilters() {
            // Set default date range (2 weeks ago to today)
            const today = new Date();
            const twoWeeksAgo = new Date(today);
            twoWeeksAgo.setDate(today.getDate() - 14);

            // Format today's date
            const todayYear = today.getFullYear();
            const todayMonth = String(today.getMonth() + 1).padStart(2, '0');
            const todayDay = String(today.getDate()).padStart(2, '0');
            const todayStr = `${todayYear}-${todayMonth}-${todayDay}`;

            // Format two weeks ago date
            const fromYear = twoWeeksAgo.getFullYear();
            const fromMonth = String(twoWeeksAgo.getMonth() + 1).padStart(2, '0');
            const fromDay = String(twoWeeksAgo.getDate()).padStart(2, '0');
            const twoWeeksAgoStr = `${fromYear}-${fromMonth}-${fromDay}`;

            $('#filterDateTo').val(todayStr);
            $('#filterDateFrom').val(twoWeeksAgoStr);

            $('#btnApplyFilters').on('click', function () {
                // Reload data from API with new date filters
                loadSalesHistory();
                getSummaryDetails();
                showToast('success', 'Filters applied');
            });

            $('#btnResetFilters').on('click', function () {
                const today = new Date();
                const twoWeeksAgo = new Date(today);
                twoWeeksAgo.setDate(today.getDate() - 14);

                const todayYear = today.getFullYear();
                const todayMonth = String(today.getMonth() + 1).padStart(2, '0');
                const todayDay = String(today.getDate()).padStart(2, '0');
                const todayStr = `${todayYear}-${todayMonth}-${todayDay}`;

                const fromYear = twoWeeksAgo.getFullYear();
                const fromMonth = String(twoWeeksAgo.getMonth() + 1).padStart(2, '0');
                const fromDay = String(twoWeeksAgo.getDate()).padStart(2, '0');
                const twoWeeksAgoStr = `${fromYear}-${fromMonth}-${fromDay}`;

                $('#filterDateTo').val(todayStr);
                $('#filterDateFrom').val(twoWeeksAgoStr);
                loadSalesHistory();
                getSummaryDetails();
                showToast('info', 'Filters reset');
            });

            $('#btnExportCsv').on('click', exportToCsv);
        }

        function renderSalesHistory(history) {
            // Render desktop table
            renderDesktopTable(history);

            // Render mobile cards
            renderMobileCards(history);
        }

        function renderDesktopTable(history) {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            if (!history || history.length === 0) {
                $('#salesHistoryTableBody').html('<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-receipt text-4xl mb-3"></i><p>No sales history found</p></td></tr>');
                return;
            }

            let html = '';
            history.forEach((sale, index) => {
                const date = new Date(sale.date_created);
                const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const timeStr = formatTime(sale.time_created);
                const orderNumber = `${sale.date_created}-${sale.order_id}`;

                html += `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${orderNumber}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">${dateStr}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">${timeStr}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">${sale.product_name || 'Unknown'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700">${sale.quantity_sold}</td>
                <td class="px-6 py-4 whitespace-nowrap text-primary font-bold">${formatCurrency(sale.total_sales || 0)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="button" class="btn-view-details text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary hover:bg-gray-200" data-index="${index}">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
            });

            $('#salesHistoryTableBody').html(html);

            dataTable = new simpleDatatables.DataTable("#salesHistoryTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Search sales...",
                    noRows: "No sales found",
                    info: "Showing {start} to {end} of {rows} records"
                }
            });

            // Re-bind click events after DataTable renders
            $('#salesHistoryTable').on('click', '.btn-view-details', function () {
                const index = $(this).data('index');
                const orderId = history[index].order_id;
                getTransactionDetails(orderId);
            });
        }

        function renderMobileCards(history) {
            if (!history || history.length === 0) {
                $('#salesHistoryCards').html(`
            <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                <i class="fas fa-receipt text-4xl mb-3"></i>
                <p>No sales history found</p>
            </div>
        `);
                return;
            }

            let html = '';
            history.forEach((sale, index) => {
                const date = new Date(sale.date_created);
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                const timeStr = formatTime(sale.time_created);
                const orderNumber = `${sale.date_created}-${sale.order_id}`;

                html += `
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                <!-- Card Header -->
                <div class="bg-primary/90 px-4 py-3 border-b border-gray-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-receipt text-white"></i>
                            <span class="font-bold text-white">Order #${orderNumber}</span>
                        </div>
                        <span class="text-xs text-gray-200">${dateStr}</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-200">
                        <i class="fas fa-clock text-xs"></i>
                        <span>${timeStr}</span>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-4">
                    <!-- Product Info -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Product</p>
                        <p class="font-semibold text-gray-900">${sale.product_name || 'Unknown'}</p>
                    </div>
                    
                    <!-- Quantity & Total -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-gray-600"><i class="fas fa-boxes text-blue-500 mr-1"></i>Quantity</span>
                            <span class="font-semibold text-gray-900">${sale.quantity_sold}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                            <span class="text-gray-600"><i class="fas fa-peso-sign text-green-500 mr-1"></i>Total</span>
                            <span class="font-semibold text-green-600">${formatCurrency(sale.total_sales)}</span>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div>
                            <p class="text-xs text-gray-500">Total Amount</p>
                            <p class="text-xl font-bold text-primary">${formatCurrency(sale.total_sales)}</p>
                        </div>
                        <button type="button" class="btn-view-details-mobile px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all" data-index="${index}">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                    </div>
                </div>
            </div>
        `;
            });

            $('#salesHistoryCards').html(html);

            // Bind click events for mobile cards
            $('.btn-view-details-mobile').on('click', function () {
                const index = $(this).data('index');
                const orderId = history[index].order_id;
                getTransactionDetails(orderId);
            });
        }

        function updateSummaryCards(data) {
            // Update main stats
            $('#summaryTotalSales').text(formatCurrency(data.total_sales || 0));
            $('#summaryTotalOrders').text(data.total_orders || 0);
            $('#summaryCashSales').text(formatCurrency(data.cash_sales || 0));
            $('#summaryGcashSales').text(formatCurrency(data.gcash_sales || 0));

            // Update category breakdown (if you uncomment those cards)
            $('#summaryBakerySales').text(formatCurrency(data.bakery_sales || 0));
            $('#summaryCoffeeSales').text(formatCurrency(data.coffee_sales || 0));
            $('#summaryGrocerySales').text(formatCurrency(data.grocery_sales || 0));
        }

        function initDetailsModal() {
            $('#btnCloseDetailsModal, #btnCloseModal').on('click', () => $('#salesDetailsModal').addClass('hidden'));

            $('#btnPrintDetails').on('click', function () {
                printOrderDetails();
            });
        }

        function printOrderDetails() {
            const orderNumber = $('#detailOrderCount').text();
            const orderDate = $('#detailDate').text();
            const cashier = $('#detailCashier').text();
            const outlet = $('#detailOutlet').text();
            const orderType = $('#detailOrderType').text().trim();
            const distributedNote = $('#detailDistributedNote').text();
            const isDistributed = !$('#detailDistributedNoteContainer').hasClass('hidden');

            const products = $('#detailBakery').text();
            const totalQuantity = $('#detailCoffee').text();
            const totalItems = $('#detailGrocery').text();
            const totalSales = $('#detailTotalSales').text();

            const bakeryAmount = $('#detailBakeryAmount').text();
            const coffeeAmount = $('#detailCoffeeAmount').text();
            const groceryAmount = $('#detailGroceryAmount').text();

            const cashAmount = $('#detailCash').text();
            const gcashAmount = $('#detailGcash').text();
            const foodPandaAmount = $('#detailFoodPanda').text();

            const printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Sales Details - ${orderNumber}</title>
                            <style>
                                @media print {
                                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                                    @page { margin: 10mm 15mm; size: A4 portrait; }
                                    .page-break { page-break-inside: avoid; }
                                }
                                * { margin: 0; padding: 0; box-sizing: border-box; }
                                html {
                                    font-size: 110%;
                                }
                                body { 
                                    font-family: 'Arial', sans-serif; 
                                    padding: 12px 18px;
                                    line-height: 1.4;
                                    color: #333;
                                    font-size: 1rem;
                                }
                                .header {
                                    text-align: center;
                                    margin-bottom: 14px;
                                    padding-bottom: 12px;
                                    border-bottom: 2px solid #333;
                                }
                                .header h1 {
                                    font-size: 1.5rem;
                                    margin-bottom: 3px;
                                    color: #1a1a1a;
                                }
                                .header p {
                                    color: #666;
                                    font-size: 0.85rem;
                                    line-height: 1.4;
                                }
                                .info-section {
                                    background: #f5f5f5;
                                    padding: 10px 14px;
                                    margin-bottom: 12px;
                                    border-radius: 4px;
                                }
                                .info-row {
                                    display: flex;
                                    justify-content: space-between;
                                    margin-bottom: 4px;
                                    font-size: 0.85rem;
                                }
                                .info-row:last-child {
                                    margin-bottom: 0;
                                }
                                .info-label {
                                    font-weight: bold;
                                    color: #555;
                                }
                                .info-value {
                                    color: #1a1a1a;
                                }
                                .section {
                                    margin-bottom: 12px;
                                }
                                .section-title {
                                    font-size: 1.05rem;
                                    font-weight: bold;
                                    color: #1a1a1a;
                                    margin-bottom: 8px;
                                    padding-bottom: 5px;
                                    border-bottom: 1.5px solid #ddd;
                                }
                                .detail-row {
                                    display: flex;
                                    justify-content: space-between;
                                    padding: 5px 0;
                                    border-bottom: 1px solid #eee;
                                    font-size: 0.85rem;
                                }
                                .detail-row:last-child {
                                    border-bottom: none;
                                }
                                .detail-label {
                                    color: #555;
                                }
                                .detail-value {
                                    font-weight: 600;
                                    color: #1a1a1a;
                                }
                                .products-value {
                                    font-weight: 600;
                                    color: #1a1a1a;
                                    max-width: 60%;
                                    text-align: right;
                                    word-wrap: break-word;
                                    overflow-wrap: break-word;
                                    line-height: 1.4;
                                }
                                .total-row {
                                    display: flex;
                                    justify-content: space-between;
                                    padding: 10px 0;
                                    margin-top: 10px;
                                    border-top: 2px solid #333;
                                    font-size: 1.2rem;
                                    font-weight: bold;
                                }
                                .footer {
                                    margin-top: 18px;
                                    padding-top: 12px;
                                    border-top: 1.5px solid #ddd;
                                    text-align: center;
                                    color: #666;
                                    font-size: 0.75rem;
                                }
                                .print-date {
                                    margin-top: 5px;
                                    font-style: italic;
                                }
                                .distributed-note {
                                    background: #e8f4fd;
                                    border: 1px solid #b3d9f2;
                                    padding: 8px 12px;
                                    margin-bottom: 12px;
                                    border-radius: 4px;
                                    font-size: 0.85rem;
                                }
                                .distributed-note .note-label {
                                    font-weight: bold;
                                    color: #2563eb;
                                    font-size: 0.85rem;
                                    margin-bottom: 3px;
                                }
                                .distributed-note .note-value {
                                    color: #1e40af;
                                    font-size: 0.85rem;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <h1>E n' G Bakery</h1>
                                <p>${outlet}</p>
                                <p>Sales Details Report</p>
                            </div>

                            <div class="info-section">
                                <div class="info-row">
                                    <span class="info-label">Order Number:</span>
                                    <span class="info-value">${orderNumber}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Date & Time:</span>
                                    <span class="info-value">${orderDate}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Cashier:</span>
                                    <span class="info-value">${cashier}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Order Type:</span>
                                    <span class="info-value">${orderType}</span>
                                </div>
                            </div>

                            ${isDistributed ? `
                            <div class="distributed-note">
                                <div class="note-label">🚚 Delivery Note:</div>
                                <div class="note-value">${distributedNote}</div>
                            </div>
                            ` : ''}

                            <div class="section">
                                <div class="section-title">Order Summary</div>
                                <div class="detail-row">
                                    <span class="detail-label">Products:</span>
                                    <span class="products-value">${products}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Total Quantity:</span>
                                    <span class="detail-value">${totalQuantity}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Total Items:</span>
                                    <span class="detail-value">${totalItems}</span>
                                </div>
                            </div>

                            <div class="section">
                                <div class="section-title">Order Distribution</div>
                                <div class="detail-row">
                                    <span class="detail-label">Bakery:</span>
                                    <span class="detail-value">${bakeryAmount}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Coffee/Drinks:</span>
                                    <span class="detail-value">${coffeeAmount}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Grocery:</span>
                                    <span class="detail-value">${groceryAmount}</span>
                                </div>
                            </div>

                            <div class="section">
                                <div class="section-title">Payment Methods</div>
                                <div class="detail-row">
                                    <span class="detail-label">Cash:</span>
                                    <span class="detail-value">${cashAmount}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">GCash:</span>
                                    <span class="detail-value">${gcashAmount}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Food Panda:</span>
                                    <span class="detail-value">${foodPandaAmount}</span>
                                </div>
                            </div>

                            <div class="total-row">
                                <span>TOTAL SALES:</span>
                                <span>${totalSales}</span>
                            </div>

                            <div class="footer">
                                <p>Thank you for your business!</p>
                                <p class="print-date">Printed on: ${new Date().toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}</p>
                            </div>

                            <script>
                                window.onload = function() {
                                    setTimeout(function() {
                                        window.print();
                                        setTimeout(function() {
                                            window.close();
                                        }, 100);
                                    }, 500);
                                };
                            <\/script>
                        </body>
                        </html>
                    `);
            printWindow.document.close();
        }

        function openDetailsModal(order) {
            if (!order) return;

            const date = new Date(order.date_created);
            const dateStr = date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            const timeStr = formatTime(order.time_created);
            const orderNumber = `${order.date_created}-${order.order_id}`;

            // Header Info
            $('#detailDate').text(`${dateStr} at ${timeStr}`);
            $('#detailCashier').text(order.cashier_name || '-');
            $('#detailOutlet').text('DECA SENTRIO');
            $('#detailOrderCount').text('Order #' + orderNumber);

            // Order Type with badge styling
            const orderType = order.order_type || 'walk-in';
            const orderTypeLabels = {
                'walk-in': '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700"><i class="fas fa-walking"></i> Walk-in</span>',
                'foodpanda': '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-700"><img src="' + BASE_URL + 'assets/pictures/food-panda.svg" class="w-3.5 h-3.5" alt="FoodPanda"> FoodPanda</span>',
                'distributed': '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><i class="fas fa-truck"></i> Distributed</span>'
            };
            $('#detailOrderType').html(orderTypeLabels[orderType] || `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">${orderType}</span>`);

            // Distributed Note
            if (orderType === 'distributed' && order.distributed_note) {
                $('#detailDistributedNote').text(order.distributed_note);
                $('#detailDistributedNoteContainer').removeClass('hidden');
            } else {
                $('#detailDistributedNoteContainer').addClass('hidden');
                $('#detailDistributedNote').text('-');
            }

            // Order Summary - Build product list from order_items
            const productNames = order.order_items ? order.order_items.map(item => item.product_name).join(', ') : 'Unknown';
            const totalQuantity = order.order_items ? order.order_items.reduce((sum, item) => sum + parseInt(item.amount || 0), 0) : 0;
            const totalItems = order.order_items ? order.order_items.length : 0;

            $('#detailBakery').text(productNames);
            $('#detailCoffee').text(totalQuantity);
            $('#detailGrocery').text(totalItems);
            $('#detailTotalSales').text(formatCurrency(order.total_payment_due || 0));

            // Order Distribution by Category
            $('#detailBakeryAmount').text(formatCurrency(order.bakery_sales || 0));
            $('#detailCoffeeAmount').text(formatCurrency(order.coffee_sales || 0));
            $('#detailGroceryAmount').text(formatCurrency(order.grocery_sales || 0));

            // Payment methods - based on payment_method field
            const cashTotal = order.payment_method === 'cash' ? (order.total_payment_due || 0) : 0;
            const gcashTotal = order.payment_method === 'gcash' ? (order.total_payment_due || 0) : 0;
            const pandaTotal = order.payment_method === 'panda' ? (order.total_payment_due || 0) : 0;

            $('#detailCash').text(formatCurrency(cashTotal));
            $('#detailGcash').text(formatCurrency(gcashTotal));
            $('#detailFoodPanda').text(formatCurrency(pandaTotal));

            // Hide variance section
            $('#detailVarianceContainer').hide();

            $('#salesDetailsModal').removeClass('hidden');
        }

        function exportToCsv() {
            if (!salesData || salesData.length === 0) {
                showToast('warning', 'No data to export');
                return;
            }

            const headers = ['Date', 'Shift', 'Cashier', 'Bakery Sales', 'Coffee Sales', 'GCash', 'Cash', 'Panda', 'Total Sales', 'Variance'];
            const rows = salesData.map(sale => [
                sale.date,
                (sale.shift_start || '') + ' - ' + (sale.shift_end || ''),
                sale.cashier_name || '',
                sale.bakery_sales || 0,
                sale.coffee_sales || 0,
                sale.gcash_total || 0,
                sale.cash_total || 0,
                sale.panda_total || 0,
                sale.total_sales || 0,
                sale.variance || 0
            ]);

            let csv = headers.join(',') + '\n';
            rows.forEach(row => {
                csv += row.map(cell => `"${cell}"`).join(',') + '\n';
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `sales_history_${$('#filterDateFrom').val()}_to_${$('#filterDateTo').val()}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            showToast('success', 'Sales history exported successfully');
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes || '00'} ${ampm}`;
        }
    </script>
</body>