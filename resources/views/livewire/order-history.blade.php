<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h1>
            <p class="text-sm text-gray-600">Kelola dan pantau semua pesanan dalam satu tempat</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="flex border-b">
                <button wire:click="$set('activeTab', 'buyer')" 
                    class="flex-1 py-4 px-6 text-center font-semibold transition {{ $activeTab === 'buyer' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-600 hover:text-gray-900' }}">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Pembelian Saya</span>
                        <span class="bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full text-xs font-bold">{{ $buyerStats['total'] }}</span>
                    </div>
                </button>
                <button wire:click="$set('activeTab', 'seller')" 
                    class="flex-1 py-4 px-6 text-center font-semibold transition {{ $activeTab === 'seller' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-600 hover:text-gray-900' }}">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Pesanan Masuk</span>
                        <span class="bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full text-xs font-bold">{{ $sellerStats['total'] }}</span>
                    </div>
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-gray-50">
                @if($activeTab === 'buyer')
                    <div class="bg-white border rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Pembelian</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $buyerStats['total'] }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-600 mb-1">Menunggu Pembayaran</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $buyerStats['pending'] }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-600 mb-1">Selesai</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $buyerStats['completed'] }}</p>
                    </div>
                @else
                    <div class="bg-white border rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Pesanan Masuk</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $sellerStats['total'] }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-600 mb-1">Menunggu Diproses</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $sellerStats['pending'] }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-600 mb-1">Selesai</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $sellerStats['completed'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" wire:model.debounce.400ms="search" 
                        placeholder="Cari nomor pesanan, produk, atau pembeli..." 
                        class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500" />
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button wire:click="$set('filterStatus', 'all')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filterStatus === 'all' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        Semua
                    </button>
                    <button wire:click="$set('filterStatus', 'pending_payment')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filterStatus === 'pending_payment' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        Menunggu Pembayaran
                    </button>
                    <button wire:click="$set('filterStatus', 'payment_confirmed')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filterStatus === 'payment_confirmed' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        {{ $activeTab === 'buyer' ? 'Menunggu Pengiriman' : 'Siap Kirim' }}
                    </button>
                    <button wire:click="$set('filterStatus', 'completed')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filterStatus === 'completed' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                        Selesai
                    </button>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        @if($activeTab === 'buyer')
            @if(count($buyerOrders) > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No. Pesanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($buyerOrders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $order->order_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-3 items-center">
                                                @if($order->product && $order->product->image_path)
                                                    <img src="{{ asset('storage/' . $order->product->image_path) }}" alt="{{ $order->product->name }}" class="w-12 h-12 object-cover rounded">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400">📦</div>
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900 line-clamp-1">{{ $order->product->name ?? 'Produk' }}</p>
                                                    <p class="text-xs text-gray-600">{{ $order->seller->name ?? 'Penjual' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            Rp {{ number_format($order->unit_price ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $order->quantity ?? 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'payment_confirmed') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'pending_payment') bg-orange-100 text-orange-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                @if($order->status === 'completed') Selesai
                                                @elseif($order->status === 'payment_confirmed') Menunggu Pengiriman
                                                @elseif($order->status === 'pending_payment') Menunggu Pembayaran
                                                @elseif($order->status === 'cancelled') Dibatalkan
                                                @else {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            <a href="{{ route('order.detail', $order->order_number) }}" class="text-orange-600 hover:text-orange-800 font-medium hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Pembelian</h3>
                    <p class="text-gray-600 mt-1">Mulai berbelanja dan temukan produk favorit Anda</p>
                    <a href="{{ route('products') }}" class="inline-flex items-center mt-6 px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium">
                        Belanja Sekarang
                    </a>
                </div>
            @endif
        @else
            @if(count($sellerOrders) > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No. Pesanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pembeli</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($sellerOrders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            {{ $order->order_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-sm font-semibold text-orange-600">
                                                    {{ substr($order->buyer->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $order->buyer->name }}</p>
                                                    <p class="text-xs text-gray-600">{{ $order->buyer->phone ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-3 items-center">
                                                @if($order->product && $order->product->image_path)
                                                    <img src="{{ asset('storage/' . $order->product->image_path) }}" alt="{{ $order->product->name }}" class="w-10 h-10 object-cover rounded">
                                                @else
                                                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center text-gray-400">📦</div>
                                                @endif
                                                <span class="font-medium text-gray-900 line-clamp-1">{{ $order->product->name ?? 'Produk' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $order->quantity }} pcs
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'payment_confirmed') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'pending_payment') bg-orange-100 text-orange-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                @if($order->status === 'completed') Selesai
                                                @elseif($order->status === 'shipped') Dikirim
                                                @elseif($order->status === 'payment_confirmed') Siap Kirim
                                                @elseif($order->status === 'pending_payment') Menunggu Pembayaran
                                                @elseif($order->status === 'cancelled') Dibatalkan
                                                @else {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            <a href="{{ route('seller.order.detail', $order->order_number) }}" class="text-orange-600 hover:text-orange-800 font-medium hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Belum ada pesanan masuk</h3>
                    <p class="mt-1 text-gray-600">Pesanan dari pembeli akan muncul di sini</p>
                </div>
            @endif
        @endif
    </div>
</div>
