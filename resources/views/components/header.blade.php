<!-- Header with Profile Dropdown -->
<header class="h-16 border-b flex items-center justify-between px-6 bg-background sticky top-0 z-30">
    <div>
        <h1 class="text-sm font-medium text-muted-foreground">
            <a href="{{ route('dashboard') }}" class="hover:text-primary">
                <i class="fas fa-home mr-1"></i> Dashboard
            </a>
            > @yield('heading') @yield('subheading')
        </h1>
    </div>

    <div class="flex items-center gap-6">
        <!-- Live DateTime Display -->
        <div class="hidden md:flex items-center gap-2 text-sm text-muted-foreground" x-data="{
            now: new Date(),
            init() {
                setInterval(() => {
                    this.now = new Date();
                }, 1000);
            }
        }"
            x-init="init()">
            <i class="fas fa-clock"></i>
            <span
                x-text="now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
            <span
                x-text="now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
        </div>

        @php
            $unreadNotifications = App\Models\CustomNotification::where('receiver_id', 1)
                ->where('is_read', operator: 0)
                ->take(5)
                ->get();
            $unreadCount = $unreadNotifications->count();
        @endphp

        <!-- Notification Dropdown -->
        <div class="relative" x-data="{ notifOpen: false }">
            <button @click="notifOpen = !notifOpen" class="relative focus:outline-none">
                <i class="fas fa-bell text-lg text-muted-foreground"></i>
                @if ($unreadCount > 0)
                {{-- ketika jumlah notifikasi diatas 5 buat menjadi 5+ --}}
                    @if ($unreadCount > 5)
                        <span
                            class="absolute -top-1 -right-2 bg-red-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                            5+
                        </span>
                    @else
                        <span
                            class="absolute -top-1 -right-2 bg-red-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                @endif
            </button>

            <!-- Dropdown -->
            <div x-show="notifOpen" @click.away="notifOpen = false" x-transition
                class="absolute right-0 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                <div class="p-4 border-b font-semibold text-sm text-muted-foreground">Notifikasi Terbaru</div>
                <ul class="max-h-64 overflow-y-auto divide-y">
                    @forelse ($unreadNotifications as $notification)
                        <li>
                            <a href=""
                                class="block px-4 py-3 hover:bg-gray-100 text-sm text-gray-700">
                                <div class="font-medium">{{ $notification->title ?? 'Notifikasi' }}</div>
                                <div class="text-xs text-muted-foreground">
                                    {{ $notification->created_at->diffForHumans() }}</div>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-3 text-sm text-muted-foreground">Tidak ada notifikasi baru.</li>
                    @endforelse
                </ul>
                <div class="px-4 py-2 text-center">
                    <a href=""
                        class="text-blue-600 text-sm font-medium hover:underline">Lihat Semua</a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fas fa-user-tie text-sm"></i>
                </div>
                <span class="hidden md:inline text-sm font-medium">{{ Auth::user()->username }}</span>
                <i
                    :class="open ? 'fas fa-chevron-up text-muted-foreground' : 'fas fa-chevron-down text-muted-foreground'"></i>
            </button>

            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-popover shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                <div class="py-1">
                    <div class="px-4 py-2 border-b">
                        <p class="text-sm font-medium">{{ Auth::user()->username }}</p>
                        <p class="text-xs text-muted-foreground">{{ Auth::user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="px-1 py-1">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 text-sm px-3 py-2 rounded-md font-medium transition-colors
                                    text-destructive hover:bg-destructive/10">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
