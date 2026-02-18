<header class="bg-black border-b border-gray-800 h-16 flex items-center justify-between px-6">
    <div class="flex items-center">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white focus:outline-none md:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>

        <!-- Search bar (Optional) -->
        <div class="hidden md:flex items-center ml-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text"
                    class="pl-10 pr-4 py-2 border border-gray-700 bg-dark-surface rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-lime-brand focus:border-lime-brand w-64"
                    placeholder="Search orders, clients...">
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Notifications (AlpineJS) -->
        <div x-data="{
            open: false,
            notifications: [],
            unreadCount: 0,
            init() {
                this.fetchNotifications();
                setInterval(() => this.fetchNotifications(), 30000);
            },
            fetchNotifications() {
                axios.get('{{ route('notifications.index') }}')
                    .then(response => {
                        this.notifications = response.data.notifications;
                        this.unreadCount = response.data.count;
                    });
            },
            markAsRead(id) {
                axios.post('/notifications/' + id + '/read').then(() => this.fetchNotifications());
            },
            markAllRead() {
                axios.post('{{ route('notifications.markAllRead') }}').then(() => this.fetchNotifications());
            }
        }" class="relative">
            <button @click="open = !open" class="relative p-1 text-gray-400 hover:text-white focus:outline-none">
                <span x-show="unreadCount > 0" x-text="unreadCount"
                    class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-black transform translate-x-1/2 -translate-y-1/2 bg-lime-brand rounded-full"></span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100 z-50"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                class="absolute right-0 mt-2 w-80 bg-dark-surface border border-gray-700 rounded-md shadow-lg py-1 z-50"
                style="display: none;">
                <div class="px-4 py-2 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <span class="text-sm font-semibold text-white">Notifications</span>
                    <button x-show="unreadCount > 0" @click="markAllRead"
                        class="text-xs text-lime-brand hover:text-lime-300">Mark all read</button>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="px-4 py-3 border-b border-gray-700 hover:bg-gray-800">
                            <p class="text-sm text-gray-200" x-text="notification.data.message"></p>
                            <div class="mt-1 flex justify-between">
                                <span class="text-xs text-gray-500"
                                    x-text="new Date(notification.created_at).toLocaleString()"></span>
                                <button @click="markAsRead(notification.id)" class="text-xs text-lime-brand">Mark
                                    read</button>
                            </div>
                        </div>
                    </template>
                    <div x-show="notifications.length === 0" class="px-4 py-4 text-center text-sm text-gray-500">No new
                        notifications.</div>
                </div>
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-lime-brand flex items-center justify-center text-black font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="text-sm font-medium text-white hidden md:block">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-dark-surface border border-gray-700 rounded-md shadow-lg py-1 z-50"
                style="display: none;">
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Sign
                        out</button>
                </form>
            </div>
        </div>
    </div>
</header>