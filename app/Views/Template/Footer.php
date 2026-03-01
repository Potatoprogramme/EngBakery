

    <script>
        // Prevent form submission on Enter key for all input fields
        document.addEventListener('keydown', function(event) {
            // Check if the pressed key is Enter and the target is an input (not textarea or button)
            if (
                event.key === 'Enter' &&
                event.target.tagName === 'INPUT' &&
                event.target.type !== 'submit' &&
                event.target.type !== 'button'
            ) {
                event.preventDefault();
                return false;
            }
        });
    </script>

    <!-- Session Timeout Modal -->
    <div id="session-timeout-modal" tabindex="-1" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50" id="session-timeout-backdrop"></div>
        <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-4 md:p-6 z-10">
            <div class="p-4 md:p-5 text-center">
                <div class="mx-auto mb-4 w-12 h-12">
                    <svg class="w-12 h-12 text-amber-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-800">Are you still there?</h3>
                <p class="mb-6 text-gray-600">Your session will expire in <strong id="session-timeout-countdown" class="text-amber-600">0</strong> seconds due to inactivity.</p>
                <div class="flex w-full flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:justify-center">
                    <button id="session-timeout-stay" type="button"
                        class="w-full sm:w-auto text-white bg-primary hover:bg-secondary focus:ring-4 focus:ring-primary/40 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        <i class="fas fa-check mr-1"></i> Yes, stay logged in
                    </button>
                    <button id="session-timeout-logout" type="button"
                        class="w-full sm:w-auto text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none border border-gray-300">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log out
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
         Session Timeout – Idle Detection & AJAX Session Check
         ======================================================== -->
    <style>
        #session-timeout-modal { display: none; }
        #session-timeout-modal.show { display: flex !important; }
        #session-timeout-modal .relative { animation: modalSlideIn 0.2s ease; }
        @keyframes modalSlideIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
    (function () {
        // ---------- Configuration ----------
        const SESSION_LIFETIME  = <?= config('Session')->expiration ?>;
        const WARNING_BEFORE    = Math.min(60, Math.floor(SESSION_LIFETIME / 2));
        const POLL_INTERVAL     = Math.min(60, Math.floor(SESSION_LIFETIME / 3));

        const LOGIN_URL         = '<?= base_url("login") ?>';
        const CHECK_SESSION_URL = '<?= base_url("Auth/CheckSession") ?>';
        const LOGOUT_URL        = '<?= base_url("Logout") ?>';

        const modal       = document.getElementById('session-timeout-modal');
        const countdownEl = document.getElementById('session-timeout-countdown');
        const stayBtn     = document.getElementById('session-timeout-stay');
        const logoutBtn   = document.getElementById('session-timeout-logout');

        let idleSeconds  = 0;
        let warningShown = false;

        console.log('[Session Timeout] SESSION_LIFETIME=' + SESSION_LIFETIME + 's, WARNING_BEFORE=' + WARNING_BEFORE + 's, POLL_INTERVAL=' + POLL_INTERVAL + 's');

        // ---------- Reset idle counter on user activity ----------
        const activityEvents = ['mousemove', 'keydown', 'scroll', 'touchstart', 'click'];
        activityEvents.forEach(function (evt) {
            document.addEventListener(evt, function () {
                if (idleSeconds > 0) console.log('[Session Timeout] Activity detected — idle counter reset (was ' + idleSeconds + 's)');
                idleSeconds = 0;
                if (warningShown) hideModal();
            }, { passive: true });
        });

        // ---------- Tick every second ----------
        setInterval(function () {
            idleSeconds++;
            const remaining = SESSION_LIFETIME - idleSeconds;
            console.log('[Session Timeout] idle=' + idleSeconds + 's | remaining=' + remaining + 's | warning=' + warningShown);

            if (!warningShown && idleSeconds >= (SESSION_LIFETIME - WARNING_BEFORE)) {
                console.log('[Session Timeout] ⚠ Showing warning modal — ' + remaining + 's left');
                showModal(remaining);
            }

            if (warningShown) {
                if (countdownEl) countdownEl.textContent = remaining > 0 ? remaining : 0;
                if (remaining <= 0) {
                    console.log('[Session Timeout] ⛔ Time is up — redirecting to logout');
                    window.location.href = LOGOUT_URL;
                }
            }
        }, 1000);

        // ---------- Poll the server periodically ----------
        setInterval(function () {
            fetch(CHECK_SESSION_URL, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (res) {
                if (res.status === 401) window.location.href = LOGIN_URL;
            })
            .catch(function () { /* network error */ });
        }, POLL_INTERVAL * 1000);

        // ---------- Modal helpers ----------
        function showModal(remaining) {
            warningShown = true;
            if (countdownEl) countdownEl.textContent = remaining > 0 ? remaining : 0;
            modal.classList.remove('hidden');
            modal.classList.add('show');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal() {
            warningShown = false;
            modal.classList.add('hidden');
            modal.classList.remove('show');
            document.body.classList.remove('overflow-hidden');
        }

        // ---------- Button handlers ----------
        if (stayBtn) {
            stayBtn.addEventListener('click', function () {
                hideModal();
                idleSeconds = 0;
                fetch(CHECK_SESSION_URL, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
            });
        }

        if (logoutBtn) {
            logoutBtn.addEventListener('click', function () {
                window.location.href = LOGOUT_URL;
            });
        }

        // ---------- Global AJAX Session-Expired Interceptor (jQuery) ----------
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ajaxComplete(function (_event, jqXHR) {
                if (jqXHR.status === 401) {
                    try {
                        var body = JSON.parse(jqXHR.responseText);
                        if (body && body.session_expired) window.location.href = LOGIN_URL;
                    } catch (e) { /* not JSON */ }
                }
            });
        }

        // ---------- Global fetch() Interceptor ----------
        const _origFetch = window.fetch;
        window.fetch = function () {
            return _origFetch.apply(this, arguments).then(function (response) {
                if (response.status === 401) {
                    response.clone().json().then(function (body) {
                        if (body && body.session_expired) window.location.href = LOGIN_URL;
                    }).catch(function () { /* not JSON */ });
                }
                return response;
            });
        };
    })();
    </script>
</body>
</html>