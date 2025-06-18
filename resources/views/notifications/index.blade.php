@extends('layouts.app')

@section('title', 'SISFO Sarpras - Manajemen Notifikasi')

@section('heading')
    <a href="{{ route('notifications.index') }}">
        <i class="fas fa-bell ml-2 mr-1 text-indigo-300"></i>
        Notifikasi
    </a>
@endsection

@section('content')
    <div class="container mx-auto h-[calc(100vh-120px)]">
        <div class="flex flex-col lg:flex-row h-full">
            <div class="w-full lg:w-1/3 bg-white flex flex-col h-full">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900">Notifikasi</h2>
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="text-sm bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-1 rounded-full transition-all duration-200 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> Tandai semua dibaca
                            </button>
                        </form>
                    </div>

                    <!-- Tab Bar -->
                    <div class="flex mt-4">
                        <button data-type=""
                            class="tab-button px-4 py-2 font-medium text-sm rounded-t-lg transition-all duration-200 {{ !request('type') ? 'bg-indigo-100 text-indigo-700 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700' }}">
                            Semua
                        </button>
                        <button data-type="request_peminjaman"
                            class="tab-button px-4 py-2 font-medium text-sm rounded-t-lg transition-all duration-200 {{ request('type') == 'request_peminjaman' ? 'bg-indigo-100 text-indigo-700 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700' }}">
                            Peminjaman
                        </button>
                        <button data-type="request_pengembalian"
                            class="tab-button px-4 py-2 font-medium text-sm rounded-t-lg transition-all duration-200 {{ request('type') == 'request_pengembalian' ? 'bg-indigo-100 text-indigo-700 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700' }}">
                            Pengembalian
                        </button>
                    </div>

                    <!-- Search and Filter -->
                    <div class="mt-4 flex items-center gap-3">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input name="search" value="{{ request('search') }}" type="text" id="searchInput"
                                class="block w-full pl-10 pr-3 py-2 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white"
                                placeholder="Cari notifikasi...">
                        </div>
                        <div class="relative">
                            <select name="status" id="statusFilter"
                                class="appearance-none block w-full pl-3 pr-8 py-2 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm">
                                <option value="">Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca
                                </option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="flex-1 overflow-y-auto" id="notificationList">
                    <!-- Skeleton Loader for List -->
                    <div id="listSkeletonLoader" class="hidden">
                        @foreach (range(1, 5) as $i)
                            <div class="p-4 border-b border-gray-100">
                                <div class="absolute top-4 left-3 h-2.5 w-2.5 rounded-full bg-gray-200 animate-pulse"></div>
                                <div class="pl-5 pr-8">
                                    <div class="flex justify-between items-start">
                                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-3 animate-pulse"></div>
                                    </div>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                                        <div class="h-3 bg-gray-200 rounded w-20 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @include('notifications.partials._notification_list', [
                        'notifications' => $notifications,
                        'selected' => $selected,
                    ])
                </div>
            </div>

            <!-- Detail Panel -->
            <div class="w-full lg:w-2/3 bg-white overflow-hidden flex flex-col h-full {{ !$selected ? 'hidden' : '' }}"
                id="detailPanel">
                <!-- Skeleton Loader for Detail -->
                <div id="detailSkeletonLoader" class="hidden p-6">
                    <!-- Header Skeleton -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-full">
                            <div class="h-8 bg-gray-200 rounded w-3/4 mb-4 animate-pulse"></div>
                            <div class="flex items-center">
                                <div class="h-4 bg-gray-200 rounded w-24 animate-pulse"></div>
                                <div class="h-3 bg-gray-200 rounded w-20 ml-2 animate-pulse"></div>
                            </div>
                        </div>
                        <div class="h-6 bg-gray-200 rounded w-20 animate-pulse"></div>
                    </div>

                    <!-- Message Skeleton -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <div class="h-4 bg-gray-200 rounded w-full mb-3 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6 mb-3 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3 animate-pulse"></div>
                    </div>

                    <!-- Detail Section Skeleton -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <div class="h-6 bg-gray-200 rounded w-1/4 mb-4 animate-pulse"></div>
                        <div class="grid gap-3">
                            @foreach (range(1, 3) as $item)
                                <div class="flex items-start gap-4 p-3 rounded-lg bg-white border border-gray-200">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-md bg-gray-200 animate-pulse"></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2 animate-pulse"></div>
                                        <div class="flex flex-wrap gap-1.5">
                                            <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                                            <div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Button Skeleton -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="h-10 bg-gray-200 rounded-lg w-full animate-pulse"></div>
                    </div>
                </div>

                <!-- Detail Content -->
                <div id="detailContent" class="flex-1 flex flex-col">
                    @if ($selected)
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-gray-900">Detail Notifikasi</h2>
                            <button id="closeDetail" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                        <div class="flex-1 overflow-y-auto">
                            @include('notifications.partials._notification_detail', [
                                'selected' => $selected,
                            ])
                        </div>
                        <div class="p-4 border-t border-gray-200">
                            <a href="{{ $selected->notification_type === 'request_peminjaman' ? route('borrow-requests.show', $selected->borrowRequest) : route('return-requests.show', $selected->returnRequest) }}"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-external-link-alt mr-2"></i> Lihat Detail Lengkap
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Empty State Panel -->
            <div class="w-full lg:w-2/3 bg-white overflow-hidden flex flex-col h-full items-center justify-center {{ $selected ? 'hidden' : '' }}"
                id="emptyStatePanel">
                <div class="text-center p-6">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada notifikasi dipilih</h3>
                    <p class="text-gray-500">Pilih notifikasi dari daftar di sebelah kiri untuk melihat detail</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Skeleton animations */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Smooth transitions */
        #listSkeletonLoader,
        #detailSkeletonLoader {
            transition: opacity 0.3s ease;
        }

        #detailContent {
            transition: opacity 0.2s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tabButtons = document.querySelectorAll('.tab-button');
            const notificationList = document.getElementById('notificationList');
            const detailPanel = document.getElementById('detailPanel');
            const emptyStatePanel = document.getElementById('emptyStatePanel');
            const closeDetail = document.getElementById('closeDetail');
            let timer;

            // Function to update URL without reload
            function updateUrl(selectedId = null) {
                const params = new URLSearchParams({
                    search: searchInput.value,
                    status: statusFilter.value,
                    type: document.querySelector('.tab-button.active')?.dataset.type || ''
                });

                if (selectedId) {
                    params.set('selected', selectedId);
                } else {
                    params.delete('selected');
                }

                history.pushState(null, '', '{{ route('notifications.index') }}?' + params.toString());
            }

            // Function to set active notification
            function setActiveNotification(notificationId) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-500');
                    if (item.dataset.id === notificationId) {
                        item.classList.add('bg-indigo-50', 'border-l-4', 'border-indigo-500');
                    }
                });
            }

            // Function to update active tab
            function updateActiveTab(activeType) {
                tabButtons.forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.type === activeType);
                    btn.classList.toggle('bg-indigo-100', btn.dataset.type === activeType);
                    btn.classList.toggle('text-indigo-700', btn.dataset.type === activeType);
                    btn.classList.toggle('border-b-2', btn.dataset.type === activeType);
                    btn.classList.toggle('border-indigo-500', btn.dataset.type === activeType);
                });
            }

            // Function to show notification detail
            function showNotificationDetail(selectedId) {
                // Show detail skeleton and hide content
                document.getElementById('detailSkeletonLoader').classList.remove('hidden');
                document.getElementById('detailContent').classList.add('hidden');

                // Show detail panel
                detailPanel.classList.remove('hidden');
                emptyStatePanel.classList.add('hidden');
                updateUrl(selectedId);

                // Mark as read and load content
                fetch(`/notifications/${selectedId}/mark-as-read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    fetch(`{{ route('notifications.index') }}?selected=${selectedId}`)
                        .then(response => response.text())
                        .then(html => {
                            setTimeout(() => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newDetail = doc.getElementById('detailContent').innerHTML;

                                // Update content and hide skeleton
                                document.getElementById('detailContent').innerHTML = newDetail;
                                document.getElementById('detailSkeletonLoader').classList.add(
                                    'hidden');
                                document.getElementById('detailContent').classList.remove(
                                    'hidden');

                                // Update notification item in list
                                const notificationItem = document.querySelector(
                                    `.notification-item[data-id="${selectedId}"]`);
                                if (notificationItem) {
                                    notificationItem.querySelector('.unread-badge')?.remove();
                                    const title = notificationItem.querySelector('h3');
                                    if (title) {
                                        title.classList.remove('font-semibold',
                                        'text-gray-900');
                                        title.classList.add('text-gray-700');
                                    }
                                }
                            }, 300);
                        });
                });
            }

            // Function to fetch filtered notifications
            function fetchNotifications() {
                const search = searchInput.value;
                const status = statusFilter.value;
                const type = document.querySelector('.tab-button.active')?.dataset.type || '';

                // Show list skeleton and hide current items
                document.getElementById('listSkeletonLoader').classList.remove('hidden');
                document.querySelectorAll('.notification-item').forEach(item => item.style.display = 'none');

                let url = '{{ route('notifications.index') }}?';
                if (search) url += `search=${encodeURIComponent(search)}&`;
                if (status) url += `status=${encodeURIComponent(status)}&`;
                if (type) url += `type=${encodeURIComponent(type)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newList = doc.getElementById('notificationList').innerHTML;

                        // Hide skeleton and show new content
                        document.getElementById('listSkeletonLoader').classList.add('hidden');
                        notificationList.innerHTML = newList;

                        // Update UI
                        updateActiveTab(type);

                        // Keep any selected notification active
                        const urlParams = new URLSearchParams(window.location.search);
                        const selectedId = urlParams.get('selected');
                        if (selectedId) {
                            setActiveNotification(selectedId);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('listSkeletonLoader').classList.add('hidden');
                    });
            }

            // Initialize based on current URL
            function init() {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedId = urlParams.get('selected');
                const type = urlParams.get('type') || '';

                updateActiveTab(type);

                if (selectedId) {
                    setActiveNotification(selectedId);
                    showNotificationDetail(selectedId);
                }
            }

            // Event Listeners
            closeDetail?.addEventListener('click', function() {
                // Remove active class from all items when closing detail
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-500');
                });

                detailPanel.classList.add('hidden');
                emptyStatePanel.classList.remove('hidden');
                updateUrl();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !detailPanel.classList.contains('hidden')) {
                    // Remove active class from all items when closing detail
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-500');
                    });

                    detailPanel.classList.add('hidden');
                    emptyStatePanel.classList.remove('hidden');
                    updateUrl();
                }
            });

            document.addEventListener('click', function(e) {
                const notificationItem = e.target.closest('.notification-item');
                if (notificationItem) {
                    e.preventDefault();
                    const selectedId = notificationItem.dataset.id;
                    setActiveNotification(selectedId);
                    showNotificationDetail(selectedId);
                }
            });

            // Handle popstate (back/forward navigation)
            window.addEventListener('popstate', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedId = urlParams.get('selected');
                const type = urlParams.get('type') || '';

                updateActiveTab(type);

                if (selectedId) {
                    setActiveNotification(selectedId);
                    showNotificationDetail(selectedId);
                } else {
                    // Remove active class from all items when closing detail
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-500');
                    });

                    detailPanel.classList.add('hidden');
                    emptyStatePanel.classList.remove('hidden');
                }
            });

            // Tab button click handler
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-indigo-100', 'text-indigo-700',
                            'border-b-2', 'border-indigo-500');
                    });
                    this.classList.add('active', 'bg-indigo-100', 'text-indigo-700', 'border-b-2',
                        'border-indigo-500');
                    fetchNotifications();
                });
            });

            // Event listeners for real-time filtering
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(fetchNotifications, 500);
            });

            statusFilter.addEventListener('change', fetchNotifications);

            // Initialize
            init();
        });
    </script>
@endsection
