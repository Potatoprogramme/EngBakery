<!-- Toast Container - Position this where you want toasts to appear -->
<div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3">

    <!-- Success Toast -->
    <div id="toast-success" class="flex items-center w-full max-w-sm p-4 bg-white rounded-lg shadow-lg border border-green-200" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <i class="fa-solid fa-check"></i>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ms-3 text-sm font-normal text-gray-700">Item moved successfully.</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Close</span>
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Danger Toast -->
    <div id="toast-danger" class="flex items-center w-full max-w-sm p-4 bg-white rounded-lg shadow-lg border border-red-200" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg">
            <i class="fa-solid fa-xmark"></i>
            <span class="sr-only">Error icon</span>
        </div>
        <div class="ms-3 text-sm font-normal text-gray-700">Item has been deleted.</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-danger" aria-label="Close">
            <span class="sr-only">Close</span>
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Warning Toast -->
    <div id="toast-warning" class="flex items-center w-full max-w-sm p-4 bg-white rounded-lg shadow-lg border border-amber-200" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-amber-500 bg-amber-100 rounded-lg">
            <i class="fa-solid fa-exclamation"></i>
            <span class="sr-only">Warning icon</span>
        </div>
        <div class="ms-3 text-sm font-normal text-gray-700">Improve password difficulty.</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-warning" aria-label="Close">
            <span class="sr-only">Close</span>
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Info Toast -->
    <div id="toast-info" class="flex items-center w-full max-w-sm p-4 bg-white rounded-lg shadow-lg border border-blue-200" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg">
            <i class="fa-solid fa-info"></i>
            <span class="sr-only">Info icon</span>
        </div>
        <div class="ms-3 text-sm font-normal text-gray-700">This is an informational message.</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-info" aria-label="Close">
            <span class="sr-only">Close</span>
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

</div>

<script>
    // Toast dismiss functionality
    document.querySelectorAll('[data-dismiss-target]').forEach(button => {
        button.addEventListener('click', function() {
            const targetSelector = this.getAttribute('data-dismiss-target');
            const targetElement = document.querySelector(targetSelector);
            if (targetElement) {
                targetElement.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => {
                    targetElement.remove();
                }, 300);
            }
        });
    });

    // Optional: Auto-dismiss after 5 seconds
    // document.querySelectorAll('#toast-container > div').forEach(toast => {
    //     setTimeout(() => {
    //         toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
    //         setTimeout(() => toast.remove(), 300);
    //     }, 5000);
    // });
</script>