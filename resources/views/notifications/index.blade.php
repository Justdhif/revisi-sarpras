@extends('layouts.app')

@section('title', 'SISFO Sarpras - Notifikasi')

@section('heading')
    <a href="{{ route('notifications.index') }}">
        <i class="fas fa-bell ml-2 mr-1 text-indigo-300"></i>
        Notifikasi
    </a>
@endsection

@section('content')
    <div x-data="notificationApp()" x-init="init()" class="flex h-screen">
        @include('notifications._list')
        @include('notifications._detail')
    </div>

    <script>
        function notificationApp() {
            return {
                notifications: [],
                selected: null,
                loading: false,
                loadingList: false,
                search: '',
                status: '',
                unreadCount: 0,

                init() {
                    this.fetchNotifications();
                    this.fetchUnreadCount();
                    setInterval(() => {
                        if (!this.search && !this.status) {
                            this.fetchNotifications(false);
                        }
                    }, 15000);
                },

                fetchUnreadCount() {
                    fetch('/notifications/unread-count', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.unreadCount = data.count;
                        })
                        .catch(error => {
                            console.error('Error fetching unread count:', error);
                        });
                },

                fetchNotifications(showLoading = false) {
                    if (showLoading) this.loadingList = true;

                    fetch(`/notifications?search=${this.search}&status=${this.status}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.notifications = data;
                            this.loadingList = false;
                        })
                        .catch(() => {
                            this.loadingList = false;
                        });
                },

                selectNotification(id) {
                    this.loading = true;
                    this.selected = null;

                    fetch(`/notifications/${id}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.selected = data;
                            this.loading = false;
                            this.fetchNotifications();
                            this.fetchUnreadCount();
                        })
                        .catch(() => {
                            this.loading = false;
                        });
                },

                markAllAsRead() {
                    fetch(`/notifications/mark-all`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(() => {
                        this.fetchNotifications();
                        this.unreadCount = 0;
                    });
                }
            };
        }
    </script>
@endsection
