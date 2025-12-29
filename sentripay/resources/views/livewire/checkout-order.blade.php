<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <a href="{{ route('products') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">&larr; Kembali ke Produk</a>
            <h1 class="text-3xl font-bold text-gray-900">Checkout Pesanan</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Product Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-6" x-data="{ 
            quantity: @entangle('quantity'),
            totalAmount: @entangle('totalAmount'),
            maxStock: {{ $product->stock }}
        }">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Product Image -->
                <div>
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="md:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                    
                    <!-- Price -->
                    <div class="mb-6">
                        <p class="text-gray-500 text-sm">Harga Satuan</p>
                        <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    <!-- Stock -->
                    <div class="mb-6">
                        <p class="text-gray-500 text-sm mb-2">Stok Tersedia: <span class="font-semibold text-gray-900">{{ $product->stock }}</span></p>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($product->stock / 100) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Seller -->
                    <div class="mb-6 pb-6 border-b">
                        <p class="text-gray-500 text-sm">Penjual</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $product->seller->name }}</p>
                    </div>

                    <!-- Quantity Selector with Alpine.js -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">Jumlah Pembelian</label>
                        <div class="flex items-center gap-4">
                            <button 
                                @click="quantity > 1 && quantity--; $dispatch('updated-quantity')"
                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-bold">
                                −
                            </button>
                            <input 
                                type="number" 
                                x-model.number="quantity"
                                @input="quantity > 1 ? (quantity <= maxStock ? quantity : quantity = maxStock) : quantity = 1; $dispatch('updated-quantity')"
                                class="w-20 px-4 py-2 border border-gray-300 rounded-lg text-center" 
                                min="1" 
                                :max="maxStock"
                            >
                            <button 
                                @click="quantity < maxStock && quantity++; $dispatch('updated-quantity')"
                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-bold">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <p class="text-gray-600 text-sm mb-2">Total Harga:</p>
                        <p class="text-4xl font-bold text-blue-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Section -->
        @if(!$showConfirmation)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-600">Produk:</span>
                    <span class="font-semibold">{{ $product->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Harga Satuan:</span>
                    <span class="font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jumlah:</span>
                    <span class="font-semibold">{{ $quantity }} unit</span>
                </div>
                <div class="border-t pt-3 flex justify-between">
                    <span class="text-gray-900 font-bold">Total:</span>
                    <span class="text-xl font-bold text-blue-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <p class="text-sm text-blue-800">
                    <strong>ℹ️ Informasi Penting:</strong><br>
                    Anda akan ditgarahkan untuk melakukan transfer ke rekening SentriPay. Dana akan ditahan aman sampai Anda menerima barang dan mengkonfirmasi pesanan.
                </p>
            </div>

            <button 
                wire:click="confirmOrder"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                Lanjutkan ke Pembayaran
            </button>
        </div>
        @endif

        <!-- Confirmation Modal -->
        @if($showConfirmation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Konfirmasi Pesanan</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Produk:</span>
                        <span class="font-semibold">{{ $product->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah:</span>
                        <span class="font-semibold">{{ $quantity }} unit</span>
                    </div>
                    <div class="border-t pt-4 flex justify-between">
                        <span class="text-gray-900 font-bold">Total Pembayaran:</span>
                        <span class="text-xl font-bold text-blue-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg mb-6 text-sm text-yellow-800">
                    Pastikan semua data sudah benar sebelum melanjutkan
                </div>

                <div class="flex gap-4">
                    <button 
                        wire:click="$set('showConfirmation', false)"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-bold py-2 px-4 rounded-lg">
                        Kembali
                    </button>
                    <button 
                        wire:click="placeOrder"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
