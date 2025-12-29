<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Jual Beli Online dengan Aman</h1>
            <p class="text-xl mb-8 text-blue-100">SentriPay adalah platform escrow terpercaya yang melindungi Anda dari penipuan</p>
            <div class="flex gap-4 justify-center">
                @auth
                    <a href="{{ route('products') }}" class="bg-white text-blue-600 font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                        Belanja Sekarang
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-blue-700 text-white font-bold px-8 py-3 rounded-lg hover:bg-blue-800 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="bg-blue-700 text-white font-bold px-8 py-3 rounded-lg hover:bg-blue-800 transition">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">Kenapa Memilih SentriPay?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-lg shadow p-8 text-center hover:shadow-lg transition">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aman & Terpercaya</h3>
                    <p class="text-gray-600">Dana pembeli ditahan oleh sistem hingga barang diterima dan dikonfirmasi</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-lg shadow p-8 text-center hover:shadow-lg transition">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Cepat & Mudah</h3>
                    <p class="text-gray-600">Proses checkout yang sederhana hanya dalam beberapa klik</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-lg shadow p-8 text-center hover:shadow-lg transition">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Perlindungan Pembeli</h3>
                    <p class="text-gray-600">Jika ada masalah, kami ada sistem dispute untuk menyelesaikannya</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">Bagaimana Cara Kerjanya?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold mx-auto mb-4">1</div>
                    <h3 class="text-center font-bold text-gray-900 mb-2">Pilih Produk</h3>
                    <p class="text-center text-gray-600 text-sm">Cari dan pilih produk yang Anda inginkan dari berbagai penjual</p>
                </div>

                <!-- Arrow -->
                <div class="hidden md:flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold mx-auto mb-4">2</div>
                    <h3 class="text-center font-bold text-gray-900 mb-2">Bayar ke SentriPay</h3>
                    <p class="text-center text-gray-600 text-sm">Transfer uang ke rekening resmi SentriPay, bukan langsung ke penjual</p>
                </div>

                <!-- Arrow -->
                <div class="hidden md:flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold mx-auto mb-4">3</div>
                    <h3 class="text-center font-bold text-gray-900 mb-2">Terima Barang</h3>
                    <p class="text-center text-gray-600 text-sm">Penjual mengirim barang. Cek dan konfirmasi penerimaan di aplikasi</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <div class="bg-green-100 text-green-800 inline-block px-6 py-3 rounded-lg font-semibold">
                    ✓ Dana masuk ke penjual setelah Anda konfirmasi
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Siap Berbelanja dengan Aman?</h2>
            <p class="text-lg mb-8 text-indigo-100">Bergabunglah dengan ribuan pembeli dan penjual yang sudah mempercayai SentriPay</p>
            @auth
                <a href="{{ route('products') }}" class="bg-white text-indigo-600 font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition inline-block">
                    Mulai Berbelanja
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition inline-block">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-900">Pertanyaan Umum</h2>
            
            <div class="space-y-4">
                <details class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition" x-data="{ open: false }">
                    <summary class="font-bold text-gray-900 flex justify-between items-center" @click="open = !open">
                        Apakah SentriPay aman?
                        <span x-show="!open">+</span>
                        <span x-show="open">−</span>
                    </summary>
                    <p class="text-gray-600 mt-4">Ya, SentriPay sangat aman. Dana Anda ditahan oleh sistem kami sampai Anda menerima dan mengkonfirmasi barang. Kami juga memiliki sistem dispute untuk menyelesaikan masalah.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition" x-data="{ open: false }">
                    <summary class="font-bold text-gray-900 flex justify-between items-center" @click="open = !open">
                        Berapa biaya admin SentriPay?
                        <span x-show="!open">+</span>
                        <span x-show="open">−</span>
                    </summary>
                    <p class="text-gray-600 mt-4">SentriPay tidak memungut biaya dari pembeli. Penjual membayar komisi minimal sebesar 2% dari setiap transaksi.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition" x-data="{ open: false }">
                    <summary class="font-bold text-gray-900 flex justify-between items-center" @click="open = !open">
                        Berapa lama dana sampai ke penjual?
                        <span x-show="!open">+</span>
                        <span x-show="open">−</span>
                    </summary>
                    <p class="text-gray-600 mt-4">Setelah Anda mengkonfirmasi penerimaan barang, dana akan dilepas ke saldo penjual dalam sistem SentriPay dalam 1-2 jam. Withdrawal ke rekening bank biasanya 1-3 hari kerja.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-6 cursor-pointer hover:shadow-lg transition" x-data="{ open: false }">
                    <summary class="font-bold text-gray-900 flex justify-between items-center" @click="open = !open">
                        Bagaimana jika barang tidak sampai?
                        <span x-show="!open">+</span>
                        <span x-show="open">−</span>
                    </summary>
                    <p class="text-gray-600 mt-4">Anda bisa mengajukan komplain/dispute di aplikasi. Admin SentriPay akan meninjau bukti yang Anda berikan dan membuat keputusan berdasarkan aturan yang berlaku.</p>
                </details>
            </div>
        </div>
    </div>
</x-app-layout>
