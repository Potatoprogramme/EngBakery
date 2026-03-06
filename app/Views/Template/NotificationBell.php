<!-- ════════════════════════════════════════════════════════════ -->
<!-- NOTIFICATION BELL DROPDOWN (include in SideNav/Navbar)     -->
<!-- ════════════════════════════════════════════════════════════ -->

<!-- Bell Button -->
<div class="relative" id="notification-wrapper">
    <button id="notification-bell" type="button"
        class="relative p-2 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-200"
        aria-label="Notifications">
        <!-- Bell SVG -->
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        <!-- Unread Badge -->
        <span id="notification-badge"
            class="hidden absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 ring-2 ring-white">
            0
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div id="notification-dropdown"
        class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-[100] max-h-[480px] flex flex-col"
        style="min-width: 320px;">

        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-lg">
            <h3 class="text-sm font-semibold text-gray-800">
                <i class="fas fa-bell text-primary mr-1"></i> Notifications
            </h3>
            <button id="mark-all-read-btn"
                class="text-xs text-primary hover:text-secondary font-medium hover:underline transition-colors"
                title="Mark all as read">
                Mark all read
            </button>
        </div>

        <!-- Notification List -->
        <div id="notification-list" class="overflow-y-auto flex-1" style="max-height: 380px;">
            <!-- Loading Spinner -->
            <div id="notification-loading" class="flex items-center justify-center py-8">
                <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
            <!-- Empty state -->
            <div id="notification-empty" class="hidden text-center py-8 px-4">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <p class="text-sm text-gray-400">You're all caught up!</p>
                <p class="text-xs text-gray-300 mt-1">No new notifications</p>
            </div>
            <!-- Items injected here by JS -->
        </div>
    </div>
</div>

<script>
(function () {
    const BASE_URL = '<?= base_url() ?>';
    const bell = document.getElementById('notification-bell');
    const dropdown = document.getElementById('notification-dropdown');
    const badge = document.getElementById('notification-badge');
    const list = document.getElementById('notification-list');
    const loading = document.getElementById('notification-loading');
    const empty = document.getElementById('notification-empty');
    const markAllBtn = document.getElementById('mark-all-read-btn');

    let isOpen = false;
    let pollInterval = null;

    // ── Icon & color map by type/level ──
    const iconMap = {
        low_stock:          { icon: 'fa-boxes-stacked',      color: 'text-white', bg: 'bg-orange-500' },
        missed_remittance:  { icon: 'fa-receipt',            color: 'text-white', bg: 'bg-red-500' },
        distribution:       { icon: 'fa-truck',              color: 'text-white', bg: 'bg-blue-500' },
        approval:           { icon: 'fa-user-clock',         color: 'text-white', bg: 'bg-purple-500' },
        system:             { icon: 'fa-circle-info',        color: 'text-white', bg: 'bg-gray-500' },
        order:              { icon: 'fa-receipt',            color: 'text-white', bg: 'bg-red-600' },
        inventory:          { icon: 'fa-boxes-stacked',      color: 'text-white', bg: 'bg-teal-500' },
        product:            { icon: 'fa-bread-slice',        color: 'text-white', bg: 'bg-amber-500' },
        raw_material:       { icon: 'fa-cubes',              color: 'text-white', bg: 'bg-lime-600' },
        remittance:         { icon: 'fa-money-bill-wave',    color: 'text-white', bg: 'bg-green-500' },
        user_approval:      { icon: 'fa-user-check',         color: 'text-white', bg: 'bg-indigo-500' },
    };
    const levelBorder = {
        critical: 'border-l-red-500',
        warning:  'border-l-yellow-500',
        info:     'border-l-blue-400',
    };

    // ── Toggle dropdown ──
    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen = !isOpen;
        if (isOpen) {
            dropdown.classList.remove('hidden');
            fetchNotifications();
        } else {
            dropdown.classList.add('hidden');
        }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (isOpen && !document.getElementById('notification-wrapper').contains(e.target)) {
            isOpen = false;
            dropdown.classList.add('hidden');
        }
    });

    // ── Fetch notifications ──
    function fetchNotifications() {
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        // Remove old items
        list.querySelectorAll('.notif-item').forEach(el => el.remove());

        fetch(`${BASE_URL}/Notifications/GetNotifications`)
            .then(r => r.json())
            .then(res => {
                loading.classList.add('hidden');
                if (res.status !== 'success' || !res.data || res.data.length === 0) {
                    empty.classList.remove('hidden');
                    return;
                }
                res.data.forEach(n => list.insertAdjacentHTML('beforeend', renderItem(n)));
            })
            .catch(() => {
                loading.classList.add('hidden');
                empty.classList.remove('hidden');
            });
    }

    // ── Render a single notification item ──
    function renderItem(n) {
        const cfg = iconMap[n.type] || iconMap.system;
        const border = levelBorder[n.level] || levelBorder.info;
        const unread = parseInt(n.is_read_by_user) === 0;
        const readClass = unread ? 'bg-green-50/40' : 'bg-white';

        return `
        <div class="notif-item border-b border-gray-100 last:border-0 cursor-pointer hover:bg-gray-50 transition-colors duration-150 border-l-4 ${border} ${readClass}"
             data-id="${n.notification_id}" data-url="${n.action_url || ''}"
             onclick="window._notifClick(this)">
            <div class="flex items-start gap-3 px-4 py-3">
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-9 h-9 rounded-full ${cfg.bg} flex items-center justify-center shadow-sm">
                        <i class="fas ${cfg.icon} ${cfg.color} text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 ${unread ? '' : 'font-normal text-gray-600'} leading-tight">${escapeHtml(n.title)}</p>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">${escapeHtml(n.message)}</p>
                    <p class="text-[11px] text-gray-400 mt-1.5">
                        <i class="far fa-clock mr-0.5"></i> ${n.time_ago}
                    </p>
                </div>
                ${unread ? '<span class="flex-shrink-0 w-2 h-2 rounded-full bg-primary mt-2"></span>' : ''}
            </div>
        </div>`;
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text || '';
        return d.innerHTML;
    }

    // ── Click handler: mark read + navigate ──
    window._notifClick = function (el) {
        const id = el.dataset.id;
        const url = el.dataset.url;

        // Mark as read
        fetch(`${BASE_URL}/Notifications/MarkAsRead?id=${id}`)
            .then(() => {
                el.classList.remove('bg-green-50/40');
                el.classList.add('bg-white');
                el.querySelector('.bg-primary')?.remove();
                fetchUnreadCount();
            });

        // Navigate if URL exists
        if (url) {
            setTimeout(() => { window.location.href = url; }, 200);
        }
    };

    // ── Mark all as read ──
    markAllBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fetch(`${BASE_URL}/Notifications/MarkAllAsRead`)
            .then(r => r.json())
            .then(() => {
                // Update UI
                list.querySelectorAll('.notif-item').forEach(el => {
                    el.classList.remove('bg-green-50/40');
                    el.classList.add('bg-white');
                    el.querySelector('.bg-primary')?.remove();
                });
                fetchUnreadCount();
            });
    });

    // ── Poll unread count ──
    function fetchUnreadCount() {
        fetch(`${BASE_URL}/Notifications/UnreadCount`)
            .then(r => r.json())
            .then(res => {
                if (res.status !== 'success') return;
                const count = parseInt(res.count);
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            })
            .catch(() => {});
    }

    // ── Trigger generation + initial count on page load ──
    function init() {
        // Trigger generation (deduplicated server-side, safe to call every load)
        fetch(`${BASE_URL}/Notifications/Generate`).catch(() => {});

        // Get initial count
        setTimeout(fetchUnreadCount, 500);

        // Poll every 30 seconds for near-real-time updates
        pollInterval = setInterval(fetchUnreadCount, 30000);
    }

    // ── Global helper: refresh badge after an action (other JS can call this) ──
    window.refreshNotifications = function () {
        fetch(`${BASE_URL}/Notifications/Generate`).catch(() => {});
        setTimeout(fetchUnreadCount, 800);
    };

    // Start after DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
