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
</header>
