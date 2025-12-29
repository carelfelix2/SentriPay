<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SentriPay') }}</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire -->
    @livewireStyles
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">SentriPay</a>
                <div class="hidden md:flex gap-6">
                    <a href="{{ route('products') }}" class="text-gray-600 hover:text-blue-600">Belanja</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                        @if(auth()->user()->role === 'seller')
                            <a href="{{ route('seller.products') }}" class="text-gray-600 hover:text-blue-600">Produk Saya</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-4" x-data="{ mobileMenuOpen: false }">
                @auth
                    <!-- User Menu with Alpine -->
                    <div class="relative" @click.outside="mobileMenuOpen = false">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="mobileMenuOpen"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profil Saya</a>
                            <a href="{{ route('orders') }}" class="block px-4 py-2 hover:bg-gray-100">Pesanan</a>
                            <a href="{{ route('wallet') }}" class="block px-4 py-2 hover:bg-gray-100">Dompet</a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold px-4 py-2">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if ($message = session('message'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" x-data x-show="true" x-transition:leave="transition ease-in duration-300" @click="$el.remove()" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">SentriPay</h3>
                    <p class="text-gray-400">Platform escrow terpercaya untuk jual beli online dengan aman</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white">Syarat Layanan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                        <li><a href="#" class="hover:text-white">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-white">Panduan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: info@sentripay.com</li>
                        <li>Telepon: +62 XXX XXX XXX</li>
                        <li>Alamat: Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 SentriPay. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
