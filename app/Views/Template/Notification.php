<!-- Toast Container - Position this where you want toasts to appear -->
<div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3"></div>

<!-- Confirmation Modal -->
<div id="confirm-modal" tabindex="-1" class="hidden fixed inset-0 z-[9998] flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50" id="confirm-modal-backdrop"></div>
    <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-4 md:p-6 z-10">
        <button type="button" id="confirm-modal-close" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-9 h-9 ms-auto inline-flex justify-center items-center">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
            <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
            <div id="confirm-modal-icon" class="mx-auto mb-4 w-12 h-12">
                <!-- Icon will be inserted here -->
            </div>
            <h3 id="confirm-modal-message" class="mb-6 text-gray-600 text-lg">Are you sure?</h3>
            <div class="flex w-full flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:justify-center">
                <button id="confirm-modal-yes" type="button" class="w-full sm:w-auto text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                    Yes, I'm sure
                </button>
                <button id="confirm-modal-no" type="button" class="w-full sm:w-auto text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none border border-gray-300">
                    No, cancel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #toast-container > div {
        transition: opacity 0.6s ease, transform 0.6s ease;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .fade-out-right {
        opacity: 0 !important;
        transform: translateX(40px) !important;
    }
    
    /* Confirmation Modal Styles */
    #confirm-modal {
        display: none;
    }
    #confirm-modal.show {
        display: flex !important;
    }
    #confirm-modal .relative {
        animation: modalSlideIn 0.2s ease;
    }
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<script>
/**
 * Toast Notification System
 * Usage: showToast('success', 'Your message here');
 * Types: 'success', 'danger', 'warning', 'info'
 */
const Toast = {
    config: {
        success: {
            icon: 'fa-check',
            borderColor: 'border-green-200',
            iconBg: 'bg-green-100',
            iconColor: 'text-green-500'
        },
        danger: {
            icon: 'fa-xmark',
            borderColor: 'border-red-200',
            iconBg: 'bg-red-100',
            iconColor: 'text-red-500'
        },
        warning: {
            icon: 'fa-exclamation',
            borderColor: 'border-amber-200',
            iconBg: 'bg-amber-100',
            iconColor: 'text-amber-500'
        },
        info: {
            icon: 'fa-info',
            borderColor: 'border-blue-200',
            iconBg: 'bg-blue-100',
            iconColor: 'text-blue-500'
        }
    },

    show: function(type, message, duration = 5000) {
        const container = document.getElementById('toast-container');
        if (!container) {
            console.error('Toast container not found!');
            return;
        }

        const cfg = this.config[type] || this.config.info;
        const toastId = 'toast-' + Date.now();

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast-card flex items-center w-full max-w-sm p-4 bg-white rounded-lg shadow-lg border ${cfg.borderColor}`;
        toast.setAttribute('role', 'alert');

        toast.innerHTML = `
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 ${cfg.iconColor} ${cfg.iconBg} rounded-lg">
                <i class="fa-solid ${cfg.icon}"></i>
            </div>
            <div class="ms-3 text-sm font-normal text-gray-700">${message}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
                <span class="sr-only">Close</span>
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;

        // Add close button functionality
        toast.querySelector('button').addEventListener('click', () => this.dismiss(toast));

        container.appendChild(toast);

        // Auto dismiss after duration
        if (duration > 0) {
            setTimeout(() => this.dismiss(toast), duration);
        }

        return toastId;
    },

    dismiss: function(toast) {
        if (typeof toast === 'string') {
            toast = document.getElementById(toast);
        }
        if (toast) {
            toast.classList.add('fade-out-right');
            setTimeout(() => toast.remove(), 600);
        }
    },

    // Convenience methods
    success: function(message, duration) { return this.show('success', message, duration); },
    danger: function(message, duration) { return this.show('danger', message, duration); },
    error: function(message, duration) { return this.show('danger', message, duration); },
    warning: function(message, duration) { return this.show('warning', message, duration); },
    info: function(message, duration) { return this.show('info', message, duration); }
};

// Global shorthand function
function showToast(type, message, duration) {
    return Toast.show(type, message, duration);
}

/**
 * Confirmation Modal System
 * Usage: 
 *   Confirm.show('Are you sure?', () => { // on confirm }, () => { // on cancel });
 *   Confirm.delete('Are you sure you want to delete this item?', () => { deleteItem(); });
 */
const Confirm = {
    icons: {
        danger: `<svg class="w-12 h-12 text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>`,
        warning: `<svg class="w-12 h-12 text-amber-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>`,
        info: `<svg class="w-12 h-12 text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>`,
        question: `<svg class="w-12 h-12 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>`
    },

    buttonStyles: {
        danger: 'text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300',
        warning: 'text-white bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:ring-amber-300',
        info: 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300',
        primary: 'text-white bg-primary hover:bg-secondary focus:ring-4 focus:ring-primary/40'
    },

    onConfirm: null,
    onCancel: null,

    show: function(message, onConfirm, onCancel, options = {}) {
        const modal = document.getElementById('confirm-modal');
        const iconContainer = document.getElementById('confirm-modal-icon');
        const messageEl = document.getElementById('confirm-modal-message');
        const yesBtn = document.getElementById('confirm-modal-yes');
        const noBtn = document.getElementById('confirm-modal-no');

        // Set defaults
        const type = options.type || 'danger';
        const confirmText = options.confirmText || "Yes, I'm sure";
        const cancelText = options.cancelText || 'No, cancel';

        // Set content
        iconContainer.innerHTML = this.icons[type] || this.icons.question;
        messageEl.textContent = message;
        yesBtn.textContent = confirmText;
        noBtn.textContent = cancelText;

        // Set button style
        yesBtn.className = `${this.buttonStyles[type] || this.buttonStyles.danger} font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none`;

        // Store callbacks
        this.onConfirm = onConfirm;
        this.onCancel = onCancel;

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('show');

        return this;
    },

    hide: function() {
        const modal = document.getElementById('confirm-modal');
        modal.classList.add('hidden');
        modal.classList.remove('show');
        this.onConfirm = null;
        this.onCancel = null;
    },

    // Convenience method for delete confirmations
    delete: function(message, onConfirm, onCancel) {
        return this.show(message || 'Are you sure you want to delete this item?', onConfirm, onCancel, {
            type: 'danger',
            confirmText: "Yes, delete",
            cancelText: 'No, cancel'
        });
    },

    // Convenience method for warning confirmations
    warn: function(message, onConfirm, onCancel) {
        return this.show(message, onConfirm, onCancel, {
            type: 'warning',
            confirmText: "Yes, proceed",
            cancelText: 'No, cancel'
        });
    }
};

// Initialize confirmation modal event listeners
document.addEventListener('DOMContentLoaded', function() {
    const yesBtn = document.getElementById('confirm-modal-yes');
    const noBtn = document.getElementById('confirm-modal-no');
    const closeBtn = document.getElementById('confirm-modal-close');
    const backdrop = document.getElementById('confirm-modal-backdrop');

    if (yesBtn) {
        yesBtn.addEventListener('click', function() {
            if (typeof Confirm.onConfirm === 'function') {
                Confirm.onConfirm();
            }
            Confirm.hide();
        });
    }

    if (noBtn) {
        noBtn.addEventListener('click', function() {
            if (typeof Confirm.onCancel === 'function') {
                Confirm.onCancel();
            }
            Confirm.hide();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            if (typeof Confirm.onCancel === 'function') {
                Confirm.onCancel();
            }
            Confirm.hide();
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', function() {
            if (typeof Confirm.onCancel === 'function') {
                Confirm.onCancel();
            }
            Confirm.hide();
        });
    }
});
</script>