<div class="fixed top-4 right-4 z-50 w-80 space-y-3">
    @if (session('success'))
        <div class="notification success animate-slideInRight">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-white">Sukses!</h3>
                    <p class="text-xs mt-1 text-white opacity-90">{{ session('success') }}</p>
                </div>
                <button class="notification-close ml-2"
                    onclick="this.parentElement.parentElement.style.opacity = '0'; setTimeout(() => this.parentElement.parentElement.remove(), 300)">
                    <svg class="h-5 w-5 text-white opacity-70 hover:opacity-100" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-white bg-opacity-30 w-full">
                <div class="h-full bg-white bg-opacity-80 notification-progress"
                    style="animation: progressBar 5s linear forwards;"></div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="notification error animate-slideInRight">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-white">Error!</h3>
                    <p class="text-xs mt-1 text-white opacity-90">{{ session('error') }}</p>
                </div>
                <button class="notification-close ml-2"
                    onclick="this.parentElement.parentElement.style.opacity = '0'; setTimeout(() => this.parentElement.parentElement.remove(), 300)">
                    <svg class="h-5 w-5 text-white opacity-70 hover:opacity-100" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-white bg-opacity-30 w-full">
                <div class="h-full bg-white bg-opacity-80 notification-progress"
                    style="animation: progressBar 5s linear forwards;"></div>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="notification warning animate-slideInRight">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-white">Peringatan!</h3>
                    <p class="text-xs mt-1 text-white opacity-90">{{ session('warning') }}</p>
                </div>
                <button class="notification-close ml-2"
                    onclick="this.parentElement.parentElement.style.opacity = '0'; setTimeout(() => this.parentElement.parentElement.remove(), 300)">
                    <svg class="h-5 w-5 text-white opacity-70 hover:opacity-100" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-white bg-opacity-30 w-full">
                <div class="h-full bg-white bg-opacity-80 notification-progress"
                    style="animation: progressBar 5s linear forwards;"></div>
            </div>
        </div>
    @endif
</div>
