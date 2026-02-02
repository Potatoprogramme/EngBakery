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
                    <li class="text-gray-700">Daily Sales Remittance</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Daily Sales Remittance
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales/History') ?>"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-history mr-2"></i>Sales History
                        </a>
                        <a href="<?= base_url('Sales/RemittanceHistory') ?>"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>Remittance History
                        </a>
                        <button type="button" id="btnPrintRemittance"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Remittance Slip Card -->
            <div id="remittanceSlip" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Remittance Header -->
                <div class="border-b-2 border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-center text-gray-800 mb-4">CASHIER'S REMITTANCE SLIP</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">NAME:</label>
                                <input type="text" id="cashierName"
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold rounded text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter cashier name">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">EMAIL:</label>
                                <input type="email" id="cashierEmail"
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold rounded text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter email address">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">DATE:</label>
                                <span id="remittanceDate" class="flex-1 px-2 py-1 text-sm font-semibold rounded text-gray-900 bg-gray-50 rounded"></span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">OUTLET:</label>
                                <input type="text" id="outletName"
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold rounded text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter outlet name" value="Deca Sentrio" readonly>
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">SHIFT:</label>
                                <div class="flex-1 flex items-center gap-1 sm:gap-2">
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] text-gray-400 mb-0.5">Start</span>
                                        <select id="shiftStart" class="w-full border border-gray-300 px-1 sm:px-2 py-1.5 text-xs sm:text-sm font-semibold text-gray-900 bg-white rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary" name="shift_start">
                                            <option value="00:00">12:00 AM</option>
                                            <option value="01:00">1:00 AM</option>
                                            <option value="02:00">2:00 AM</option>
                                            <option value="03:00">3:00 AM</option>
                                            <option value="04:00">4:00 AM</option>
                                            <option value="05:00">5:00 AM</option>
                                            <option value="06:00">6:00 AM</option>
                                            <option value="07:00" selected>7:00 AM</option>
                                            <option value="08:00">8:00 AM</option>
                                            <option value="09:00">9:00 AM</option>
                                            <option value="10:00">10:00 AM</option>
                                            <option value="11:00">11:00 AM</option>
                                            <option value="12:00">12:00 PM</option>
                                            <option value="13:00">1:00 PM</option>
                                            <option value="14:00">2:00 PM</option>
                                            <option value="15:00">3:00 PM</option>
                                            <option value="16:00">4:00 PM</option>
                                            <option value="17:00">5:00 PM</option>
                                            <option value="18:00">6:00 PM</option>
                                            <option value="19:00">7:00 PM</option>
                                            <option value="20:00">8:00 PM</option>
                                            <option value="21:00">9:00 PM</option>
                                            <option value="22:00">10:00 PM</option>
                                            <option value="23:00">11:00 PM</option>
                                        </select>
                                    </div>
                                    <span class="text-gray-400 font-bold text-sm mt-4">-</span>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] text-gray-400 mb-0.5">End</span>
                                        <select id="shiftEnd" class="w-full border border-gray-300 px-1 sm:px-2 py-1.5 text-xs sm:text-sm font-semibold text-gray-900 bg-white rounded focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                                            <option value="00:00">12:00 AM</option>
                                            <option value="01:00">1:00 AM</option>
                                            <option value="02:00">2:00 AM</option>
                                            <option value="03:00">3:00 AM</option>
                                            <option value="04:00">4:00 AM</option>
                                            <option value="05:00">5:00 AM</option>
                                            <option value="06:00">6:00 AM</option>
                                            <option value="07:00">7:00 AM</option>
                                            <option value="08:00">8:00 AM</option>
                                            <option value="09:00">9:00 AM</option>
                                            <option value="10:00">10:00 AM</option>
                                            <option value="11:00">11:00 AM</option>
                                            <option value="12:00">12:00 PM</option>
                                            <option value="13:00">1:00 PM</option>
                                            <option value="14:00">2:00 PM</option>
                                            <option value="15:00">3:00 PM</option>
                                            <option value="16:00" selected>4:00 PM</option>
                                            <option value="17:00">5:00 PM</option>
                                            <option value="18:00">6:00 PM</option>
                                            <option value="19:00">7:00 PM</option>
                                            <option value="20:00">8:00 PM</option>
                                            <option value="21:00">9:00 PM</option>
                                            <option value="22:00">10:00 PM</option>
                                            <option value="23:00">11:00 PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid: Cash Sales | Sales Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- LEFT COLUMN: Cash Sales / Change Fund -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 border-b border-gray-200 pb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>CASH SALES / CHANGE FUND
                        </h4>

                        <!-- Bills Breakdown -->
                        <div class="space-y-1.5">
                            <!-- 1000 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱1000</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill1000">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill1000" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill1000">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total1000">₱0.00</span>
                            </div>
                            <!-- 500 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱500</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill500">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill500" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill500">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total500">₱0.00</span>
                            </div>
                            <!-- 200 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱200</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill200">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill200" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill200">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total200">₱0.00</span>
                            </div>
                            <!-- 100 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱100</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill100">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill100" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill100">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total100">₱0.00</span>
                            </div>
                            <!-- 50 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱50</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill50">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill50" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill50">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total50">₱0.00</span>
                            </div>
                            <!-- 20 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱20</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill20">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill20" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill20">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total20">₱0.00</span>
                            </div>
                            <!-- 10 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱10</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill10">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill10" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill10">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total10">₱0.00</span>
                            </div>
                            <!-- 5 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱5</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill5">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill5" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill5">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total5">₱0.00</span>
                            </div>
                            <!-- 1 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱1</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill1">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill1" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill1">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total1">₱0.00</span>
                            </div>
                            <!-- 0.25 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱0.25</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill025">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill025" min="0" value="0"
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill025">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total025">₱0.00</span>
                            </div>
                        </div>

                        <!-- Amount Enclosed -->
                        <div class="mt-3 pt-3 border-t-2 border-green-300 bg-green-50 -mx-4 px-4 -mb-4 pb-3 rounded-b-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-700">AMOUNT ENCLOSED:</span>
                                <span class="text-xl font-bold text-green-600" id="amountEnclosed">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Sales Summary -->
                    <div class="space-y-3">
                        <!-- Payment Methods -->
                        <div class="p-3 sm:p-4 bg-white rounded-lg border border-gray-200 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-mobile-alt text-blue-600 text-base"></i>
                                    <span class="text-sm font-medium text-gray-700">Online Payment:</span>
                                </div>
                                <input type="number" id="totalOnlineRevenue" min="0" placeholder="0.00"
                                    class="w-full sm:w-40 lg:w-48 text-right border border-blue-300 rounded-lg px-2 py-1.5 text-base font-bold text-blue-600 bg-blue-50/50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-hand-holding-usd text-gray-600 text-base"></i>
                                    <span class="text-sm font-medium text-gray-700">CASH OUT:</span>
                                </div>
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <input type="number" id="cashOutAmount" min="0" placeholder="0.00"
                                        class="w-1/2 sm:w-28 lg:w-36 text-right border border-gray-300 rounded-lg px-2 py-1.5 text-base font-semibold focus:ring-2 focus:ring-primary focus:border-primary">
                                    <input type="text" id="cashOutReason" placeholder="Reason"
                                        class="w-1/2 sm:w-32 lg:w-40 border border-gray-300 rounded-lg px-2.5 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Sales by Category -->
                        <div class="p-3 bg-white rounded-lg border border-gray-200">
                            <h5 class="text-xs font-semibold text-gray-600 mb-2">SALES BY CATEGORY</h5>
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between p-2 bg-amber-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bread-slice text-amber-600 text-xs"></i>
                                        <span class="text-xs font-medium text-gray-700">BAKERY:</span>
                                    </div>
                                    <span class="text-sm font-bold text-amber-600" id="bakerySales">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-orange-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-mug-hot text-orange-600 text-xs"></i>
                                        <span class="text-xs font-medium text-gray-700">COFFEE/DRINKS:</span>
                                    </div>
                                    <span class="text-sm font-bold text-orange-600" id="coffeeSales">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-shopping-basket text-green-600 text-xs"></i>
                                        <span class="text-xs font-medium text-gray-700">GROCERY:</span>
                                    </div>
                                    <span class="text-sm font-bold text-green-600" id="grocerySales">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center p-2 bg-white rounded">
                                    <div class="text-2xl font-bold text-primary" id="totalOrders">0</div>
                                    <div class="text-xs text-gray-500">Total Orders</div>
                                </div>
                                <div class="text-center p-2 bg-white rounded">
                                    <div class="text-2xl font-bold text-amber-600" id="totalItemsSold">0</div>
                                    <div class="text-xs text-gray-500">Items Sold</div>
                                </div>
                            </div>
                        </div>

                        <!-- Totals Card -->
                        <div class="grid grid-cols-1 gap-2">
                            <div class="p-3 bg-primary/10 rounded-lg border border-primary/20">
                                <span class="text-xs font-medium text-gray-600 block">TOTAL SALES:</span>
                                <p class="text-lg font-bold text-primary" id="totalSales">₱0.00</p>
                            </div>
                        </div>

                        <!-- Overage/Shortage -->
                        <div class="p-3 bg-gray-100 rounded-lg border border-gray-200" id="varianceContainer">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-800">OVERAGE / SHORTAGE:</span>
                                <span class="text-xl font-bold" id="variance">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" id="btnResetForm"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                    <button type="button" id="btnSaveRemittance"
                        class="inline-flex items-center rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <i class="fas fa-save mr-2"></i>Save Remittance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Print Frame -->
    <iframe id="printFrame" name="printFrame" style="position:absolute;top:-9999px;left:-9999px;width:0;height:0;border:none;"></iframe>

    <style>
        /* Hide number input spinners */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>

    <script>
        window.BASE_URL = '<?= base_url() ?>';

        console.log('BASE_URL:', BASE_URL);
        // Bill denominations mapping
        const billDenominations = {
            'bill1000': 1000,
            'bill500': 500,
            'bill200': 200,
            'bill100': 100,
            'bill50': 50,
            'bill20': 20,
            'bill10': 10,
            'bill5': 5,
            'bill1': 1,
            'bill025': 0.25
        };

        $(document).ready(function() {
            initializeRemittance();
            console.log('Remittance Slip Initialized');
            loadTodaysSalesData();
            console.log('Loaded Today\'s Sales Data');
            loadUserInfo();
            console.log('Loaded User Info');
            bindBillInputEvents();
            console.log('Bound Bill Input Events');
            bindBillButtonEvents();
            console.log('Bound Bill Button Events');
            bindGCashInputEvent();
            console.log('Bound GCash Input Event');
        });

        function initializeRemittance() {
            // Set today's date
            const today = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            $('#remittanceDate').text(today.toLocaleDateString('en-US', options));
        }

        function loadUserInfo() {
            $.ajax({
                url: BASE_URL + 'User/GetCurrentUser',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const user = response.data;
                        $('#cashierName').val(user.name || '');
                        $('#cashierEmail').val(user.email || '');
                        console.log('Loaded user info:', user);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading user info:', error);
                }
            });
        }

        var allTransactions = [];

        function loadTodaysSalesData() {
            $.ajax({
                url: BASE_URL + 'Sales/GetTodaysSummary',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log('Sales data response:', response.data);

                        allTransactions = response.data.transaction_ids || [];
                        
                        // Extract sales data with default values
                        const breadSales = response.data.bread_sales || {};
                        const drinksSales = response.data.drinks_sales || {};
                        const doughSales = response.data.dough_sales || {};
                        const grocerySales = response.data.grocery_sales || {};

                        // Payment methods
                        const gcashSales = response.data.gcash_sales || {};
                        const mayaSales = response.data.maya_sales || {};
                        const creditCardSales = response.data.credit_card_sales || {};
                        const debitCardSales = response.data.debit_card_sales || {};

                        const total_orders = response.data.total_orders || 0;
                        const total_items_sold = response.data.total_items_sold || 0;

                        const breadRevenue = breadSales.total_revenue || 0;
                        const drinksRevenue = drinksSales.total_revenue || 0;
                        const doughRevenue = doughSales.total_revenue || 0;
                        const groceryRevenue = grocerySales.total_revenue || 0;


                        const gcashRevenue = gcashSales.total_revenue || 0;
                        const mayaRevenue = mayaSales.total_revenue || 0;
                        const creditCardRevenue = creditCardSales.total_revenue || 0;
                        const debitCardRevenue = debitCardSales.total_revenue || 0;

                        const totalOnlineRevenue = Number(gcashRevenue) + Number(mayaRevenue) + Number(creditCardRevenue) + Number(debitCardRevenue);
                        // Now use breadRevenue, drinksRevenue, etc. safely

                        // Update sales by category
                        $('#bakerySales').text(formatCurrency(Number(breadRevenue)));
                        $('#coffeeSales').text(formatCurrency(Number(drinksRevenue)));
                        $('#grocerySales').text(formatCurrency(Number(groceryRevenue)));

                        const totalSales = Number(breadRevenue) + Number(drinksRevenue) + Number(groceryRevenue);

                        // Only set value if there's actual online revenue, otherwise leave empty for placeholder
                        if (totalOnlineRevenue > 0) {
                            $('#totalOnlineRevenue').val(Number(totalOnlineRevenue).toFixed(2));
                        } else {
                            $('#totalOnlineRevenue').val('');
                        }

                        // Update total sales
                        $('#totalSales').text(formatCurrency(totalSales));

                        // Update statistics
                        $('#totalOrders').text(formatNumber(total_orders));
                        $('#totalItemsSold').text(formatNumber(total_items_sold));

                        // Calculate variance
                        calculateAllTotals();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading sales data:', error);
                }
            });
        }

        function bindBillInputEvents() {
            // Bind events to all bill inputs
            $('.bill-input').on('input', function() {
                updateBillTotal($(this).attr('id'));
            });

            // Also bind cash out amount
            $('#cashOutAmount').on('input', function() {
                calculateVariance();
            });
        }

        function bindBillButtonEvents() {
            // Plus buttons
            $('.bill-plus').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const currentVal = parseInt(input.val()) || 0;
                input.val(currentVal + 1);
                updateBillTotal(targetId);
            });

            // Minus buttons
            $('.bill-minus').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const currentVal = parseInt(input.val()) || 0;
                if (currentVal > 0) {
                    input.val(currentVal - 1);
                    updateBillTotal(targetId);
                }
            });
        }

        function updateBillTotal(inputId) {
            const denomination = billDenominations[inputId];
            const quantity = parseInt($('#' + inputId).val()) || 0;
            const total = quantity * denomination;

            // Update the corresponding total
            const totalId = '#total' + inputId.replace('bill', '');
            $(totalId).text(formatCurrency(total));

            // Recalculate all totals
            calculateAllTotals();
        }

        function bindGCashInputEvent() {
            $('#totalOnlineRevenue').on('input', function() {
                calculateVariance();
            });
        }

        function calculateAmountEnclosed() {
            let totalEnclosed = 0;

            // Sum all bill totals
            Object.keys(billDenominations).forEach(function(inputId) {
                const quantity = parseInt($('#' + inputId).val()) || 0;
                totalEnclosed += quantity * billDenominations[inputId];
            });

            $('#amountEnclosed').text(formatCurrency(totalEnclosed));
            return totalEnclosed;
        }

        // NOTE: Cash count variance UI was removed. Amount enclosed is still
        // calculated and used for overall remittance variance.

        function calculateAllTotals() {
            calculateAmountEnclosed();
            calculateVariance();
        }

        function calculateVariance() {
            const amountEnclosed = parseCurrency($('#amountEnclosed').text());
            const gcash = parseFloat($('#totalOnlineRevenue').val()) || 0;
            const cashOut = parseFloat($('#cashOutAmount').val()) || 0;
            const totalSales = parseCurrency($('#totalSales').text());

            const totalRemitted = amountEnclosed + gcash + cashOut;
            const variance = totalRemitted - totalSales;

            // Update variance styling
            const container = $('#varianceContainer');
            const varianceEl = $('#variance');

            container.removeClass('bg-red-100 bg-green-100 bg-gray-100');
            varianceEl.removeClass('text-red-600 text-green-600 text-primary');

            if (variance > 0) {
                container.addClass('bg-green-100');
                varianceEl.addClass('text-green-600');
                varianceEl.text('+ ' + formatCurrency(variance) + ' (Over)');
            } else if (variance < 0) {
                container.addClass('bg-red-100');
                varianceEl.addClass('text-red-600');
                varianceEl.text('- ' + formatCurrency(Math.abs(variance)) + ' (Short)');
            } else {
                container.addClass('bg-gray-100');
                varianceEl.addClass('text-primary');
                varianceEl.text('₱0.00 (Balanced)');
            }
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function parseCurrency(str) {
            // Be tolerant of malformed currency strings (including mojibake like â±)
            // Remove everything except digits, minus sign and decimal point.
            if (!str) return 0;
            const cleaned = String(str).replace(/[^0-9.-]+/g, '');
            return parseFloat(cleaned) || 0;
        }

        function formatNumber(value) {
            // Generic number formatter for counts (e.g., orders, items)
            // Keeps integers with thousand separators.
            const n = Number(value) || 0;
            return n.toLocaleString('en-US');
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        function getDenominationsBreakdown() {
            const breakdown = {};
            Object.keys(billDenominations).forEach(function(inputId) {
                const count = parseInt($('#' + inputId).val()) || 0;
                const denomination = billDenominations[inputId];
                if (count > 0) {
                    breakdown[inputId.replace('bill', '')] = {
                        quantity: count,
                        denomination: denomination
                    };
                }
            });
            return breakdown;
        }

        // Reset form
        $('#btnResetForm').on('click', function() {
            // Reset bill inputs
            $('.bill-input').val(0);
            $('.bill-total').text('₱0.00');

            // Reset other inputs
            $('#cashOutAmount').val(0);
            $('#cashOutReason').val('');
            $('#cashierName').val('');
            $('#cashierEmail').val('');
            $('#totalOnlineRevenue').val(0);
            $('#amountEnclosed').text('₱0.00');

            calculateAllTotals();
        });

        // Print functionality - Opens dedicated print layout
        // Print functionality - Uses hidden iframe
        $('#btnPrintRemittance').on('click', function() {
            // Collect denominations data
            const denomsHtml = {};
            Object.keys(billDenominations).forEach(function(inputId) {
                const count = parseInt($('#' + inputId).val()) || 0;
                const denomination = billDenominations[inputId];
                const total = count * denomination;
                const denomKey = inputId.replace('bill', '');
                denomsHtml[denomKey] = {
                    count: count > 0 ? count : '',
                    total: count > 0 ? formatCurrency(total) : ''
                };
            });

            // Build the print HTML
            const printHtml = `
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Cashier's Remittance Slip</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    @page { size: 4in 6in; margin: 0; }
                    @media print {
                        html, body { width: 4in; height: 6in; margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .slip-container { border: none; }
                    }
                    body { font-family: Arial, sans-serif; font-size: 9pt; line-height: 1.2; width: 4in; height: 6in; padding: 0.1in; background: #fff; }
                    .slip-container { width: 100%; height: 100%; border: 1.5px solid #000; }
                    .slip-header { text-align: center; font-weight: bold; font-size: 11pt; padding: 6px 0; border-bottom: 1px solid #000; }
                    
                    /* Info table for aligned columns */
                    .info-table { width: 100%; border-collapse: collapse; }
                    .info-table td { border: 1px solid #000; border-top: none; padding: 2px 4px; height: 18px; font-size: 9pt; }
                    .info-table .label-col { font-weight: bold; width: 15%; white-space: nowrap; }
                    .info-table .value-col { width: 35%; }
                    
                    .section-header { text-align: center; font-weight: bold; font-size: 9pt; padding: 4px 0; border-bottom: 1px solid #000; background: #f5f5f5; }
                    .bills-table { width: 100%; border-collapse: collapse; }
                    .bills-table th, .bills-table td { border: 1px solid #000; padding: 1px 4px; text-align: center; height: 16px; font-size: 8.5pt; }
                    .bills-table th { background: #f0f0f0; font-weight: bold; }
                    .bills-table .denom-col { width: 22%; text-align: left; font-weight: bold; }
                    .bills-table .count-col { width: 20%; }
                    .bills-table .equals-col { width: 8%; }
                    .bills-table .total-col { width: 25%; text-align: right; padding-right: 6px; }
                    
                    /* Summary table */
                    .summary-table { width: 100%; border-collapse: collapse; }
                    .summary-table td { border: 1px solid #000; padding: 2px 4px; height: 18px; font-size: 9pt; }
                    .summary-table .sum-label { font-weight: bold; width: 25%; }
                    .summary-table .sum-value { width: 25%; text-align: right; }
                    .summary-table .total-row td { background: #f0f0f0; }
                    .summary-table .variance-row td { font-weight: bold; }
                    
                    .total-row { background: #f0f0f0; }
                    .variance-row .summary-label, .variance-row .summary-value { font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="slip-container">
                    <div class="slip-header">CASHIER'S REMITTANCE SLIP</div>
                    
                    <!-- Info section with aligned columns -->
                    <table class="info-table">
                        <tr>
                            <td class="label-col">NAMES:</td>
                            <td class="value-col" colspan="3">${$('#cashierName').val() || ''}</td>
                        </tr>
                        <tr>
                            <td class="label-col">DATE:</td>
                            <td class="value-col">${$('#remittanceDate').text() || ''}</td>
                            <td class="label-col">OUTLET:</td>
                            <td class="value-col">${$('#outletName').val() || ''}</td>
                        </tr>
                        <tr>
                            <td class="label-col">SHIFT:</td>
                            <td class="value-col"> ${formatTime($('#shiftStart').val())}</td>
                            <td class="label-col">TO:</td>
                            <td class="value-col"> ${formatTime($('#shiftEnd').val())}</td>
                        </tr>
                    </table>
                    
                    <div class="section-header">CASH SALES/CHANGE FUND</div>
                    <table class="bills-table">
                        <thead><tr><th class="denom-col">BILLS/COINS</th><th class="count-col"></th><th class="equals-col">=</th><th class="total-col"></th></tr></thead>
                        <tbody>
                            <tr><td class="denom-col">1000x</td><td class="count-col">${denomsHtml['1000'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['1000'].total}</td></tr>
                            <tr><td class="denom-col">500x</td><td class="count-col">${denomsHtml['500'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['500'].total}</td></tr>
                            <tr><td class="denom-col">200x</td><td class="count-col">${denomsHtml['200'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['200'].total}</td></tr>
                            <tr><td class="denom-col">100x</td><td class="count-col">${denomsHtml['100'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['100'].total}</td></tr>
                            <tr><td class="denom-col">50x</td><td class="count-col">${denomsHtml['50'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['50'].total}</td></tr>
                            <tr><td class="denom-col">20x</td><td class="count-col">${denomsHtml['20'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['20'].total}</td></tr>
                            <tr><td class="denom-col">10x</td><td class="count-col">${denomsHtml['10'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['10'].total}</td></tr>
                            <tr><td class="denom-col">5x</td><td class="count-col">${denomsHtml['5'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['5'].total}</td></tr>
                            <tr><td class="denom-col">1x</td><td class="count-col">${denomsHtml['1'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['1'].total}</td></tr>
                            <tr><td class="denom-col">.25x</td><td class="count-col">${denomsHtml['025'].count}</td><td class="equals-col">=</td><td class="total-col">${denomsHtml['025'].total}</td></tr>
                        </tbody>
                    </table>
                    
                    <!-- Summary section with aligned columns -->
                    <table class="summary-table">
                        <tr class="total-row">
                            <td class="sum-label" colspan="2">TOTAL AMOUNT ENCLOSED:</td>
                            <td class="sum-value" colspan="2">${$('#amountEnclosed').text() || '₱0.00'}</td>
                        </tr>
                        <tr>
                            <td class="sum-label" colspan="2">ONLINE PAYMENT:</td>
                            <td class="sum-value" colspan="2">${formatCurrency(parseFloat($('#totalOnlineRevenue').val()) || 0)}</td>
                        </tr>
                        <tr>
                            <td class="sum-label">CASH OUT:</td>
                            <td class="sum-value">${formatCurrency(parseFloat($('#cashOutAmount').val()) || 0)}</td>
                            <td class="sum-value" colspan="2" style="text-align:left;font-size:8pt;">${$('#cashOutReason').val() || ''}</td>
                        </tr>
                        <tr>
                            <td class="sum-label">BAKERY:</td>
                            <td class="sum-value">${$('#bakerySales').text() || '₱0.00'}</td>
                            <td class="sum-label">GROCERY:</td>
                            <td class="sum-value">${$('#grocerySales').text() || '₱0.00'}</td>
                        </tr>
                        <tr>
                            <td class="sum-label" colspan="2">COFFEE:</td>
                            <td class="sum-value" colspan="2">${$('#coffeeSales').text() || '₱0.00'}</td>
                        </tr>
                        <tr class="total-row">
                            <td class="sum-label" colspan="2">TOTAL SALES:</td>
                            <td class="sum-value" colspan="2">${$('#totalSales').text() || '₱0.00'}</td>
                        </tr>
                        <tr class="variance-row">
                            <td class="sum-label" colspan="2">OVERAGE/SHORTAGE:</td>
                            <td class="sum-value" colspan="2">${$('#variance').text() || '₱0.00'}</td>
                        </tr>
                    </table>
                </div>
            </body>
            </html>`;

            // Write to hidden iframe and print
            const printFrame = document.getElementById('printFrame');
            const frameDoc = printFrame.contentWindow || printFrame.contentDocument.document || printFrame.contentDocument;
            
            frameDoc.document.open();
            frameDoc.document.write(printHtml);
            frameDoc.document.close();

            // Wait for content to load then print
            setTimeout(function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            }, 250);
        });

        // Save remittance
        $('#btnSaveRemittance').on('click', function() {
            // Calculate variance properly with sign
            const amountEnclosed = parseCurrency($('#amountEnclosed').text());
            const totalOnlineRevenue = parseFloat($('#totalOnlineRevenue').val()) || 0;
            const cashOutAmount = parseFloat($('#cashOutAmount').val()) || 0;
            const totalSales = parseCurrency($('#totalSales').text());
            const totalRemitted = amountEnclosed + totalOnlineRevenue + cashOutAmount;
            const variance = totalRemitted - totalSales;

            // Get shift times from select elements (add :00 for seconds)
            const shiftStart = $('#shiftStart').val() + ':00';
            const shiftEnd = $('#shiftEnd').val() + ':00';

            const remittanceData = {
                // remittance_details table
                // cashier_id: $('#cashierName').val(),
                cashier_id: 1, // Temporary hardcoded for testing
                outlet_name: $('#outletName').val(),
                date: (function() {
                    const d = new Date();
                    const p = n => String(n).padStart(2, '0');
                    return `${d.getFullYear()}-${p(d.getMonth() + 1)}-${p(d.getDate())} ${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
                })(),
                shift_start: shiftStart,
                shift_end: shiftEnd,
                amount_enclosed: amountEnclosed,
                total_online_revenue: totalOnlineRevenue,
                cash_out_amount: cashOutAmount,
                cash_out_reason: $('#cashOutReason').val(),
                bakery_sales: parseCurrency($('#bakerySales').text()),
                coffee_sales: parseCurrency($('#coffeeSales').text()),
                grocery_sales: parseCurrency($('#grocerySales').text()),
                total_sales: totalSales,
                variance: variance,
                //remittance_denomination table
                denominations: getDenominationsBreakdown(),
                // remittance_items table 
                transaction_ids: allTransactions 
            };

            console.log('Saving remittance data:', remittanceData);

            $.ajax({
                url: BASE_URL + 'Sales/SaveRemittance',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(remittanceData),
                success: function(response) {
                    if (response.success) {
                        showToast('success', 'Remittance saved successfully!');
                    } else {
                        showToast('danger', response.message || 'Failed to save remittance');
                    }
                },
                error: function(xhr, status, error) {
                    showToast('danger', 'Error saving remittance: ' + error);
                }
            });
        });
    </script>