@extends('layouts.app')

@section('heading')
    <a href="{{ route('notifications.index') }}">
        <i class="fas fa-bell ml-2 mr-1 text-indigo-300"></i>
        Notifikasi
    </a>
@endsection

@section('content')
    <div class="flex flex-col md:flex-row h-[calc(100vh-120px)] bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Notification List -->
        <div class="w-full md:w-1/3 border-r border-gray-100 bg-white">
            <div class="p-4 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Notifikasi</h2>
                    <button id="markAllRead" class="text-xs text-indigo-600 hover:text-indigo-800">Tandai semua telah
                        dibaca</button>
                </div>
            </div>
            <div class="overflow-y-auto h-[calc(100%-70px)]">
                <div id="notificationList">
                    @include('notifications.partials.list', ['notifications' => $notifications])
                </div>
            </div>
        </div>

        <!-- Notification Detail -->
        <div class="w-full md:w-2/3 bg-gray-50" id="notificationDetail">
            @include('notifications.partials.detail', ['selected' => $selected])
        </div>
    </div>

    <style>
        .skeleton-loader {
            position: relative;
            overflow: hidden;
            background-color: #f3f4f6;
        }

        .skeleton-loader::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const notificationList = document.getElementById('notificationList');
            const notificationDetail = document.getElementById('notificationDetail');
            const markAllReadBtn = document.getElementById('markAllRead');
            const unreadCounter = document.getElementById('unreadCounter');

            // Handle notification click
            notificationList.addEventListener('click', function(e) {
                const notificationItem = e.target.closest('.notification-item');
                if (!notificationItem) return;

                e.preventDefault();
                const notificationId = notificationItem.dataset.id;

                // Highlight selected notification
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-indigo-50');
                });
                notificationItem.classList.add('bg-indigo-50');

                // Show skeleton loader for detail
                notificationDetail.innerHTML = `
                    <div class="p-6">
                        <div class="flex items-start mb-6">
                            <div class="w-12 h-12 rounded-full bg-gray-200 skeleton-loader"></div>
                            <div class="ml-4 flex-1">
                                <div class="h-6 bg-gray-200 rounded w-3/4 skeleton-loader mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/2 skeleton-loader"></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-4 bg-gray-200 rounded w-full skeleton-loader"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6 skeleton-loader"></div>
                            <div class="h-4 bg-gray-200 rounded w-4/6 skeleton-loader"></div>
                        </div>
                    </div>
                `;

                // Load notification detail
                fetch(`/notifications?id=${notificationId}&ajax=1`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        notificationDetail.innerHTML = data.detail;

                        // Mark as read if unread
                        if (notificationItem.querySelector('.unread-badge')) {
                            markAsRead(notificationId, notificationItem);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notificationDetail.innerHTML = `
                        <div class="h-full flex flex-col items-center justify-center text-red-500">
                            <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                            <p class="text-sm">Gagal memuat notifikasi</p>
                            <button onclick="window.location.reload()" class="mt-2 text-xs text-indigo-600 hover:text-indigo-800">
                                Coba lagi
                            </button>
                        </div>
                    `;
                    });
            });

            // Mark as read function
            function markAsRead(notificationId, notificationItem) {
                fetch(`/notifications/${notificationId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to mark as read');

                        // Remove unread badge
                        const unreadBadge = notificationItem.querySelector('.unread-badge');
                        if (unreadBadge) {
                            unreadBadge.remove();
                        }

                        // Update unread counter
                        updateUnreadCount();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            // Update unread count
            function updateUnreadCount() {
                fetch('/notifications/unread-count', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to get unread count');
                        return response.json();
                    })
                    .then(data => {
                        if (data.count > 0) {
                            if (unreadCounter) {
                                unreadCounter.textContent = data.count;
                            } else {
                                const counter = document.createElement('span');
                                counter.id = 'unreadCounter';
                                counter.className =
                                    'ml-2 bg-indigo-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full';
                                counter.textContent = data.count;
                                document.querySelector('.heading a').appendChild(counter);
                            }
                        } else if (unreadCounter) {
                            unreadCounter.remove();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            // Mark all as read
            markAllReadBtn.addEventListener('click', function() {
                if (!confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?')) {
                    return;
                }

                fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to mark all as read');

                        // Remove all unread badges
                        document.querySelectorAll('.unread-badge').forEach(badge => {
                            badge.remove();
                        });

                        // Update counter
                        updateUnreadCount();

                        // Show success message
                        alert('Semua notifikasi telah ditandai sebagai dibaca');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal menandai semua notifikasi sebagai dibaca');
                    });
            });
        });
    </script>
@endsection
