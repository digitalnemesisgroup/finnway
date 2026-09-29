<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') | FINNWAY 360 Payment Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="{{ request()->routeIs('hub.portal.login') ? 'bg-slate-900' : 'bg-slate-50' }} text-slate-800 font-sans min-h-screen antialiased" x-data="{ sidebarOpen: false }">

    @if(request()->routeIs('hub.portal.login'))
        <main class="min-h-screen w-full flex flex-col justify-center bg-slate-900">
            @yield('content')
        </main>
    @else
        <div class="flex h-screen overflow-hidden bg-slate-900">

            <!-- ── Mobile Backdrop Overlay ─────────────────────────────────────────── -->
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 lg:hidden">
            </div>

            <!-- ── Sidebar Navigation Container ───────────────────────────────────── -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed lg:static inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800/80 z-50 flex flex-col transition-transform duration-300 ease-in-out shrink-0">

                <!-- Sidebar Brand Header with Official logo.png -->
                <div class="h-20 px-5 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/60">
                    <a href="{{ route('hub.portal.dashboard') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('logo.png') }}" alt="FINNWAY 360" class="h-11 w-auto object-contain max-w-[180px] drop-shadow-md group-hover:scale-105 transition">
                    </a>
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <!-- Account / Merchant Status Pill -->
                @if(Auth::check() && (Auth::user()->isPaymentAdmin() || Auth::user()->isAdmin()))
                <div class="p-3 mx-4 my-3 rounded-xl bg-blue-950/80 border border-blue-800/60 flex items-center justify-between gap-2 shadow-inner">
                    <div class="truncate">
                        <span class="block text-xs font-black text-blue-300 truncate">Payment Admin Control</span>
                        <span class="text-[10px] text-blue-400/80 block truncate">{{ Auth::user()->name }}</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                </div>
                @elseif(isset($client))
                <div class="p-3 mx-4 my-3 rounded-xl bg-slate-800/70 border border-slate-700/60 flex items-center justify-between gap-2">
                    <div class="truncate">
                        <span class="block text-xs font-bold text-white truncate">{{ $client->name }}</span>
                        <span class="text-[10px] text-slate-400 block truncate">{{ $client->business_email }}</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $client->isLive() ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}" title="{{ strtoupper($client->approval_status) }}"></span>
                </div>
                @endif

                <!-- Navigation Menu Links -->
                <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">

                    @if(Auth::check() && (Auth::user()->isPaymentAdmin() || Auth::user()->isAdmin()))
                        <div class="px-2 pt-2 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-blue-400">
                            Admin Control
                        </div>

                        <!-- 1. Payment Clients & Applications -->
                        <a href="{{ route('admin.payment-clients.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.payment-clients*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="ri-building-4-line text-base text-blue-400"></i>
                            <span>Business Applications</span>
                        </a>

                        <!-- 2. Platform Analytics -->
                        <a href="{{ route('admin.payment-analytics.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.payment-analytics.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="ri-line-chart-line text-base text-indigo-400"></i>
                            <span>Platform Analytics</span>
                        </a>

                        <!-- 3. Financial Reports -->
                        <a href="{{ route('admin.payment-analytics.report') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.payment-analytics.report*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="ri-file-chart-line text-base text-emerald-400"></i>
                            <span>Financial Reports</span>
                        </a>

                        <div class="px-2 pt-4 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                            Merchant View
                        </div>
                    @endif

                    <!-- 1. Dashboard -->
                    <a href="{{ route('hub.portal.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('hub.portal.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="ri-dashboard-3-line text-base"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. Orders & Settlements -->
                    <a href="{{ route('hub.portal.orders') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('hub.portal.orders') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="ri-shopping-bag-3-line text-base"></i>
                        <span>Orders & Settlements</span>
                    </a>

                    <!-- 3. API Keys -->
                    <a href="{{ route('hub.portal.api-keys') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('hub.portal.api-keys') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="ri-key-2-line text-base"></i>
                        <span>API Keys</span>
                    </a>

                    <!-- 4. API Docs -->
                    <a href="{{ route('hub.portal.docs') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('hub.portal.docs') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="ri-book-open-line text-base"></i>
                        <span>API Integration Docs</span>
                    </a>

                    <!-- 5. Business Profile -->
                    <a href="{{ route('hub.portal.profile') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('hub.portal.profile') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="ri-user-settings-line text-base"></i>
                        <span>Business Profile</span>
                    </a>

                </nav>

                <!-- Sidebar Footer & Logout -->
                <div class="p-4 border-t border-slate-800/80 bg-slate-950/30 space-y-3">
                    <form action="{{ route('hub.portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white border border-rose-500/20 transition flex items-center justify-center gap-2">
                            <i class="ri-logout-box-r-line"></i> Logout Account
                        </button>
                    </form>
                    <p class="text-[10px] text-center text-slate-500">FINNWAY 360° Encrypted Engine</p>
                </div>
            </aside>

            <!-- ── Main App Content Area ───────────────────────────────────────────── -->
            <div class="flex-1 flex flex-col min-w-0 bg-slate-50 overflow-hidden">

                <!-- Top App Header -->
                <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-10">
                    <div class="flex items-center gap-3">
                        <!-- Mobile Hamburger Button -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                            <i class="ri-menu-2-line text-lg"></i>
                        </button>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('logo.png') }}" alt="FINNWAY 360" class="h-8 w-auto object-contain hidden sm:block">
                            <h2 class="text-base font-bold text-slate-800 truncate border-l border-slate-200 pl-3 hidden sm:block">
                                @hasSection('header_title') @yield('header_title') @else @yield('page_title', 'Payment Hub') @endif
                            </h2>
                            <h2 class="text-base font-bold text-slate-800 truncate sm:hidden">
                                @hasSection('header_title') @yield('header_title') @else @yield('page_title', 'Payment Hub') @endif
                            </h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @if(Auth::check() && (Auth::user()->isPaymentAdmin() || Auth::user()->isAdmin()))
                        <span class="px-3 py-1.5 rounded-xl bg-blue-100 text-blue-800 border border-blue-200 text-xs font-extrabold flex items-center gap-1.5">
                            <i class="ri-shield-user-fill text-blue-600"></i> Payment Admin
                        </span>
                        @elseif(isset($client))
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700">
                            <span class="w-2 h-2 rounded-full {{ $client->isLive() ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            <span>{{ $client->name }}</span>
                        </div>
                        @endif
                    </div>
                </header>

                <!-- Scrollable Body Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium px-4 py-3 rounded-xl shadow-sm flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2">
                                <i class="ri-checkbox-circle-fill text-emerald-500 text-base"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i class="ri-close-line"></i></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium px-4 py-3 rounded-xl shadow-sm flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2">
                                <i class="ri-error-warning-fill text-rose-500 text-base"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800"><i class="ri-close-line"></i></button>
                        </div>
                    @endif

                    @yield('content')
                </main>

            </div>

        </div>
    @endif

</body>
</html>
