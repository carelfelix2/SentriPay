<x-layouts.app>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="{{ route('seller.orders') }}" class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Pesanan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pesanan</h2>
                
                <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">No. Pesanan</p>
                        <p class="font-bold text-gray-900 mt-1">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Tanggal</p>
                        <p class="font-bold text-gray-900 mt-1">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Status</p>
                        <div class="mt-1">
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
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
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Metode Pesanan</p>
                        <p class="font-bold text-gray-900 mt-1">Online</p>
                    </div>
                </div>

                <!-- Pembeli Info -->
                <div class="pt-6">
                    <p class="text-xs text-gray-600 font-semibold uppercase mb-3">Informasi Pembeli</p>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-lg font-semibold text-orange-600 flex-shrink-0">
                            {{ substr($order->buyer->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $order->buyer->name }}</p>
                            <p class="text-sm text-gray-600">📞 {{ $order->buyer->phone ?? '-' }}</p>
                            <p class="text-sm text-gray-600">✉️ {{ $order->buyer->email }}</p>
                            <p class="text-sm text-gray-600">📍 {{ $order->buyer->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Section -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Produk</h2>
                
                <div class="flex gap-4">
                    @if($order->product && $order->product->image_path)
                        <img src="{{ asset('storage/' . $order->product->image_path) }}" 
                             alt="{{ $order->product->name }}" 
                             class="w-24 h-24 object-cover rounded-lg">
                    @else
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $order->product->name ?? 'Produk' }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($order->product->description ?? '', 150) }}</p>
                        
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga Satuan</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($order->unit_price ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Jumlah Pesanan</span>
                                <span class="font-semibold text-gray-900">{{ $order->quantity ?? 1 }} pcs</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="text-gray-600 font-semibold">Total Harga</span>
                                <span class="font-bold text-lg text-orange-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Status Pengiriman</h2>
                
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $order->payment_date ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-sm font-bold">
                                {{ $order->payment_date ? '✓' : '1' }}
                            </div>
                            <div class="w-1 h-8 bg-gray-300 my-1"></div>
                        </div>
                        <div class="pt-1">
                            <p class="font-semibold text-gray-900">Pembayaran Diterima</p>
                            <p class="text-sm text-gray-600">{{ $order->payment_date ? $order->payment_date->format('d M Y H:i') : 'Menunggu' }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $order->shipped_date ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-sm font-bold">
                                {{ $order->shipped_date ? '✓' : '2' }}
                            </div>
                            <div class="w-1 h-8 bg-gray-300 my-1"></div>
                        </div>
                        <div class="pt-1">
                            <p class="font-semibold text-gray-900">Barang Dikirim</p>
                            <p class="text-sm text-gray-600">{{ $order->shipped_date ? $order->shipped_date->format('d M Y H:i') : 'Menunggu' }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $order->delivered_date ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-sm font-bold">
                                {{ $order->delivered_date ? '✓' : '3' }}
                            </div>
                        </div>
                        <div class="pt-1">
                            <p class="font-semibold text-gray-900">Barang Diterima Pembeli</p>
                            <p class="text-sm text-gray-600">{{ $order->delivered_date ? $order->delivered_date->format('d M Y H:i') : 'Menunggu' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                
                <div class="space-y-3 mb-6 pb-6 border-b">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya Admin</span>
                        <span class="font-semibold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkir</span>
                        <span class="font-semibold text-gray-900">Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-900 font-semibold">Total Bayar Pembeli</span>
                    <span class="text-2xl font-bold text-orange-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>

                <!-- Status Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-blue-800 font-semibold mb-2">💡 INFORMASI</p>
                    @if($order->status === 'pending_payment')
                        <p class="text-xs text-blue-700">Menunggu pembeli menyelesaikan pembayaran.</p>
                    @elseif($order->status === 'payment_confirmed')
                        <p class="text-xs text-blue-700">Pembayaran sudah diterima. Segera kirim barang ke pembeli.</p>
                    @elseif($order->status === 'shipped')
                        <p class="text-xs text-blue-700">Barang sedang dalam perjalanan ke pembeli.</p>
                    @elseif($order->status === 'completed')
                        <p class="text-xs text-blue-700">Pesanan selesai. Dana sudah masuk ke akun Anda.</p>
                    @else
                        <p class="text-xs text-blue-700">Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                    @endif
                </div>

                <!-- Chat/Pengiriman Button -->
                @php
                    $chatGroup = $order->chatGroup;
                @endphp
                @if($order->status === 'payment_confirmed' || $order->status === 'shipped' || $order->status === 'delivered' || $order->status === 'completed')
                    @if($chatGroup)
                        <a href="{{ route('chat.show', $chatGroup) }}" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Lihat Chat Pengiriman
                        </a>
                    @else
                        <form action="{{ route('chat.start', $order) }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Buka Chat Pengiriman
                            </button>
                        </form>
                    @endif
                @endif

                <!-- Transaction Info -->
                @if($order->transactions->count() > 0)
                <div class="mt-6 pt-6 border-t">
                    <p class="text-xs text-gray-600 font-semibold uppercase mb-3">Riwayat Transaksi</p>
                    <div class="space-y-2">
                        @foreach($order->transactions as $transaction)
                        <div class="bg-gray-50 rounded p-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-gray-900">{{ $transaction->transaction_number }}</span>
                                <span class="text-xs font-semibold 
                                    @if($transaction->status === 'completed') text-green-600
                                    @elseif($transaction->status === 'pending') text-orange-600
                                    @else text-gray-600
                                    @endif
                                ">{{ ucfirst($transaction->status) }}</span>
                            </div>
                            <p class="text-xs text-gray-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
