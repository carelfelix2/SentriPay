<x-layouts.app>
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Chat Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $chatGroup->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Status: <span class="font-semibold text-red-600">Ditutup</span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Order: <span class="font-mono font-semibold">{{ $chatGroup->order->order_number }}</span></p>
                <p class="text-sm text-gray-600 mt-1">Ditutup: <span class="font-semibold">{{ $chatGroup->closed_at->format('d M Y H:i') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Closed Message -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm6 0a1 1 0 11-2 0 1 1 0 012 0zm2 0a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-medium text-blue-900">
                    Chat Telah Ditutup
                </h3>
                <p class="mt-2 text-blue-700">
                    Transaksi ini telah selesai. Pembeli telah mengkonfirmasi penerimaan akun/produk pada {{ $chatGroup->closed_at->format('d M Y pukul H:i') }}.
                </p>
                <p class="mt-3 text-blue-700">
                    Status order telah diubah menjadi "Barang Diterima".
                </p>
            </div>
        </div>
    </div>

    <!-- Chat History -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Riwayat Chat</h2>
        <div class="space-y-4" style="max-height: 600px; overflow-y: auto;">
            @forelse($chatGroup->messages()->with('user')->get() as $msg)
                <div class="flex {{ $msg->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs md:max-w-md {{ $msg->user_id === Auth::id() ? 'bg-blue-100' : 'bg-gray-100' }} rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold {{ $msg->user_role === 'seller' ? 'text-orange-600' : ($msg->user_role === 'admin' ? 'text-red-600' : 'text-blue-600') }}">
                                {{ $msg->user_role === 'seller' ? '🏪 Penjual' : ($msg->user_role === 'admin' ? '👨‍💼 Admin' : '👤 Pembeli') }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $msg->user->name }}</span>
                        </div>
                        <p class="text-gray-900 text-sm">{{ $msg->message }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $msg->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Tidak ada pesan dalam chat ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Back Button -->
    <div>
        <a href="{{ route('orders') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
            ← Kembali ke Pesanan
        </a>
    </div>
</div>
</x-layouts.app>
