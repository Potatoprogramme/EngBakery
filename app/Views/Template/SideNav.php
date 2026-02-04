<?php 
    $current = strtolower(service('uri')->getSegment(1) ?? ''); 
    $currentSegment2 = strtolower(service('uri')->getSegment(2) ?? '');
?>
<!-- Navbar -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="<?= base_url() ?>" class="flex items-center justify-center ms-4 md:me-24">
                        <img src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" class="h-6 me-2" alt="En'G Bakery Logo" />
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-primary">E n' G Bakery</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center gap-2 sm:gap-3 ms-3">
                        <!-- Profile Dropdown -->
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full" src="<?= base_url('assets/pictures/En\'G Bakery Logo.png') ?>" alt="user photo">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900" role="none">
                                    E n'G Bakery - <?= ucfirst(session('employee_type')) ?>
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate" role="none">
                                    <?= session('email') ?>
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="<?= base_url('User/Profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Account Settings</a>
                                </li>
                                <li>
                                    <a href="<?= base_url('Logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen pt-[70px] sm:pt-[60px] transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col">
            <ul class="space-y-2 font-medium flex-1">
                <li class="pt-2 mt-2 border-t border-gray-100">
                    <a href="<?= base_url('Dashboard') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'dashboard') ? 'bg-primary' : '' ?>">
                        <svg class="w-6 h-6 <?= ($current === 'dashboard') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'dashboard') ? 'text-white' : '' ?>">Dashboard</span>
                    </a>
                </li>
                <li class="pt-2 mt-2 border-t border-gray-100">
                    <a href="<?= base_url('RawMaterials') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'rawmaterials') ? 'bg-primary' : '' ?>">
                        <svg class="w-6 h-6 <?= ($current === 'rawmaterials') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'rawmaterials') ? 'text-white' : '' ?>">Raw Material</span>
                    </a>
                </li>
                <li class="pt-2 mt-2 border-t border-gray-100">
                    <a href="<?= base_url('Products') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'products') ? 'bg-primary' : '' ?>">
                        <svg class="w-6 h-6 <?= ($current === 'products') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'products') ? 'text-white' : '' ?>">Product</span>
                    </a>
                </li>
                <li class="pt-2 mt-2 border-t border-gray-100">
                    <a href="<?= base_url('Inventory') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'inventory') ? 'bg-primary' : '' ?>">
                        <svg class="w-6 h-6 <?= ($current === 'inventory') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'inventory') ? 'text-white' : '' ?>">Inventory</span>
                    </a>
                </li>
                <li class="pt-2 mt-2 border-t border-gray-100">
                    <a href="<?= base_url('Order') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'order') ? 'bg-primary' : '' ?>">
                        <svg class="w-6 h-6 <?= ($current === 'order') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'order') ? 'text-white' : '' ?>">Order</span>
                    </a>
                </li>
                <li class="pt-2 mt-2 border-t border-gray-100">
                    <button type="button" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= (strpos($current, 'sales') !== false) ? 'bg-primary' : '' ?>" aria-controls="dropdown-sales" data-collapse-toggle="dropdown-sales">
                        <svg class="w-6 h-6 <?= (strpos($current, 'sales') !== false) ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="flex-1 ms-3 text-left whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= (strpos($current, 'sales') !== false) ? 'text-white' : '' ?>">Sales</span>
                        <svg class="w-4 h-4 <?= (strpos($current, 'sales') !== false) ? 'text-white' : 'text-gray-900' ?> group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-sales" class="<?= (strpos($current, 'sales') !== false) ? '' : 'hidden' ?> py-2 space-y-1">
                        <li>
                            <a href="<?= base_url('Sales/History') ?>" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group transition-colors duration-300 <?= ($current === 'sales' && $currentSegment2 === 'history') ? 'bg-gray-200 font-semibold' : '' ?>">
                                <i class="fas fa-history mr-2 <?= ($current === 'sales' && $currentSegment2 === 'history') ? 'text-primary' : 'text-gray-500' ?>"></i>
                                <span class="text-sm">Sales History</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('Sales') ?>" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group transition-colors duration-300 <?= ($current === 'sales' && $currentSegment2 === '') ? 'bg-gray-200 font-semibold' : '' ?>">
                                <i class="fas fa-receipt mr-2 <?= ($current === 'sales' && $currentSegment2 === '') ? 'text-primary' : 'text-gray-500' ?>"></i>
                                <span class="text-sm">Daily Remittance</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('Sales/RemittanceHistory') ?>" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group transition-colors duration-300 <?= ($current === 'sales' && $currentSegment2 === 'remittancehistory') ? 'bg-gray-200 font-semibold' : '' ?>">
                                <i class="fas fa-file-invoice-dollar mr-2 <?= ($current === 'sales' && $currentSegment2 === 'remittancehistory') ? 'text-primary' : 'text-gray-500' ?>"></i>
                                <span class="text-sm">Remittance History</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="py-2 my-2 border-y border-gray-100">
                    <a href="<?= base_url('DeliveryLog') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'deliverylog') ? 'bg-primary' : '' ?>">
                        <svg class="w-6 h-6 <?= ($current === 'deliverylog') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'deliverylog') ? 'text-white' : '' ?>">Delivery Log</span>
                    </a>
                </li>
            </ul>
            
            <?php if (session('employee_type') === 'admin' || session('employee_type') === 'owner'): ?>
            <!-- Manage Employee at bottom -->
            <div class="pt-2 mt-auto border-t border-gray-100">
                <a href="<?= base_url('ManageEmployee') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-secondary group transition-colors duration-300 <?= ($current === 'manageemployee') ? 'bg-primary' : '' ?>">
                    <svg class="w-6 h-6 <?= ($current === 'manageemployee') ? 'text-white' : 'text-gray-900' ?> transition duration-300 group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-gray-200 transition-colors duration-300 <?= ($current === 'manageemployee') ? 'text-white' : '' ?>">Manage Employee</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </aside>