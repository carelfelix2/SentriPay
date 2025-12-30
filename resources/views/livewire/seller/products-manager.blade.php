<div class="min-h-screen bg-gray-50">
    <!-- Header + Stats -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Produk</h1>
                    <p class="text-sm text-gray-600">Kelola semua produk Anda dalam satu tempat</p>
                </div>
                <!-- Tombol Tambah Produk -->
                <a href="{{ route('seller.product.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Tambah Produk
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="bg-orange-50 border border-orange-100 rounded-lg p-4">
                    <p class="text-xs font-medium text-orange-700 mb-1">Total Pesanan</p>
                    <p class="text-2xl font-bold text-orange-800">{{ $totalOrders }}</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4">
                    <p class="text-xs font-medium text-emerald-700 mb-1">Selesai</p>
                    <p class="text-2xl font-bold text-emerald-800">{{ $completedOrders }}</p>
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
                    <p class="text-xs font-medium text-amber-700 mb-1">Menunggu Pembayaran</p>
                    <p class="text-2xl font-bold text-amber-800">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 font-medium">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Filters & Search Bar -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="flex gap-3 w-full md:w-2/3">
                    <input type="text" wire:model.debounce.400ms="search" placeholder="Cari produk..." class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500" />
                    <select wire:model="filterStatus" class="w-44 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Semua Status</option>
                        <option value="available">Tersedia</option>
                        <option value="sold_out">Habis</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Produk</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Harga</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Stok</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Status</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-12 h-12 object-cover rounded" alt="{{ $product->name }}">
                                            @else
                                                <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center text-gray-400">📦</div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500 line-clamp-1">{{ $product->category ?: 'Umum' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $product->stock }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $product->status === 'available' ? 'bg-emerald-100 text-emerald-700' : ($product->status === 'sold_out' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                            {{ ucfirst(str_replace('_',' ', $product->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right flex gap-2 justify-end">
                                        <a href="{{ route('seller.product.edit', $product->id) }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-medium transition">
                                            ✎ Edit
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada produk. Tambahkan produk pertama Anda.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
