<x-layouts.app>
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Chat Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $chatGroup->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Status: <span class="font-semibold {{ $chatGroup->status === 'open' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $chatGroup->status === 'open' ? 'Terbuka' : 'Ditutup' }}
                    </span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Order: <span class="font-mono font-semibold">{{ $order->order_number }}</span></p>
                <p class="text-sm text-gray-600 mt-1">Status Order: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></p>
            </div>
        </div>

        <!-- Participants -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-xs text-gray-600">Penjual</p>
                <p class="font-semibold text-gray-900">{{ $chatGroup->seller->name }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-xs text-gray-600">Pembeli</p>
                <p class="font-semibold text-gray-900">{{ $chatGroup->buyer->name }}</p>
            </div>
            @if($chatGroup->admin)
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-xs text-gray-600">Admin</p>
                <p class="font-semibold text-gray-900">{{ $chatGroup->admin->name }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Messages Area -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6" style="height: 500px; overflow-y: auto;">
        <div class="space-y-4">
            @forelse($messages as $msg)
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
                <div class="text-center py-12">
                    <p class="text-gray-500">Belum ada pesan. Mulai percakapan di bawah.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Message Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('chat.send', $chatGroup) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                <textarea 
                    id="message"
                    name="message"
                    rows="4"
                    placeholder="Ketik pesan Anda di sini..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent resize-none"
                    required
                ></textarea>
                @error('message')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Kirim Pesan
            </button>
        </form>
    </div>

    <!-- Buyer Confirmation Section -->
    @if(Auth::id() === $chatGroup->buyer_id && !$chatGroup->buyer_confirmed && $chatGroup->status === 'open')
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-yellow-800">
                    Konfirmasi Penerimaan Akun
                </h3>
                <p class="mt-2 text-sm text-yellow-700">
                    Jika Anda telah menerima detail akun/produk dari penjual dan semuanya sesuai, silakan klik tombol di bawah untuk mengkonfirmasi.
                </p>
                <form action="{{ route('chat.confirm', $chatGroup) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                        ✓ Akun Sudah Diterima
                    </button>
                </form>
            </div>
        </div>
    </div>
    @elseif($chatGroup->buyer_confirmed)
    <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-green-800">
                    Transaksi Selesai
                </h3>
                <p class="mt-2 text-sm text-green-700">
                    Pembeli telah mengkonfirmasi penerimaan akun. Chat ditutup dan status order diubah menjadi "Barang Diterima".
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('orders') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
            ← Kembali ke Pesanan
        </a>
    </div>
</div>
</x-layouts.app>
