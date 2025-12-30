<x-layouts.app>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pesanan Saya</h1>
        <p class="text-gray-600 mt-1">Kelola dan pantau semua pembelian Anda</p>
    </div>

    @if ($orders && count($orders) > 0)
        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6" x-data="{ status: 'all' }">
            <label class="text-sm font-medium text-gray-700">Filter Status:</label>
            <div class="flex gap-2 mt-3 flex-wrap">
                <button @click="status = 'all'" :class="status === 'all' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Semua
                </button>
                <button @click="status = 'pending_payment'" :class="status === 'pending_payment' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Menunggu Pembayaran
                </button>
                <button @click="status = 'payment_confirmed'" :class="status === 'payment_confirmed' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Menunggu Pengiriman
                </button>
                <button @click="status = 'completed'" :class="status === 'completed' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Selesai
                </button>
                <button @click="status = 'cancelled'" :class="status === 'cancelled' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-800'" class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Dibatalkan
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-3 items-center">
                                        @if($order->product && $order->product->image_path)
                                            <img src="{{ asset('storage/' . $order->product->image_path) }}" alt="{{ $order->product->name }}" class="w-12 h-12 object-cover rounded">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900 line-clamp-2">{{ $order->product->name ?? 'Produk' }}</span>
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
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('order.detail', $order->order_number) }}" class="text-orange-600 hover:text-orange-800 font-medium hover:underline">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Pesanan</h3>
            <p class="text-gray-600 mt-1">Mulai berbelanja dan temukan produk favorit Anda</p>
            <a href="{{ route('products') }}" class="inline-flex items-center mt-6 px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium">
                Belanja Sekarang
            </a>
        </div>
    @endif
</div>
</x-layouts.app>
