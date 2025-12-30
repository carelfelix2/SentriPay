<x-layouts.app>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Container 1: Judul, Media, Rating -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Media -->
            <div class="flex justify-center">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-[450px] h-[450px] object-cover rounded-lg">
                @else
                    <div class="w-[450px] h-[450px] bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <!-- Info -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                <div class="mt-2 text-sm text-gray-600">Toko: {{ $product->seller->name ?? 'Penjual' }}</div>
                <div class="mt-3 flex items-center text-sm">
                    @php
                        $avgRating = $product->averageRating();
                        $totalReviews = $product->totalReviews();
                    @endphp
                    <div class="flex items-center">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-700 font-medium">{{ number_format($avgRating, 1) }}</span>
                    <span class="mx-3 text-gray-300">•</span>
                    <span class="text-gray-600">{{ $totalReviews }} ulasan</span>
                    <span class="mx-3 text-gray-300">•</span>
                    <span class="text-gray-600">Terjual {{ $product->sold }}</span>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <span class="text-3xl font-bold text-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-sm text-gray-600">Stok: {{ $product->stock }}</span>
                </div>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('checkout', $product->id) }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-orange-600 text-white hover:bg-orange-700 font-semibold">Beli Sekarang</a>
                    <a href="{{ route('products') }}?seller={{ $product->seller->id ?? '' }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 font-semibold">Kunjungi Toko</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Container 2: Related products from same user -->
    @if($relatedBySeller->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Produk Terkait dari Penjual yang Sama</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($relatedBySeller as $p)
            <a href="{{ route('product.show', $p) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
                @if($p->image_path)
                    <img src="{{ asset('storage/' . $p->image_path) }}" alt="{{ $p->name }}" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="p-3">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $p->name }}</h3>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs font-bold text-gray-900">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-500">Terjual {{ $p->sold }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Container 3: Spesifikasi & Deskripsi -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Spesifikasi & Deskripsi Produk</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">Kategori</span><span class="font-semibold text-gray-900">{{ $product->category ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Harga</span><span class="font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Stok</span><span class="font-semibold text-gray-900">{{ $product->stock }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Terjual</span><span class="font-semibold text-gray-900">{{ $product->sold }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Penjual</span><span class="font-semibold text-gray-900">{{ $product->seller->name ?? '-' }}</span></div>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="prose prose-sm max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Container 4: Penilaian & Review -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Penilaian & Review</h2>
        
        @if($product->totalReviews() > 0)
            <!-- Rating Summary -->
            <x-product-rating :product="$product" class="mb-8" />
            
            <!-- Reviews List -->
            <div class="mt-8 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Ulasan Pembeli</h3>
                @php
                    $reviews = $product->reviews()->latest()->get();
                    // DEBUG
                    // echo "Total reviews: " . $reviews->count();
                @endphp
                @foreach($reviews as $review)
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-semibold">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $review->user->name }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex">
                                            {{-- Rating: {{ $review->rating }} stars --}}
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <svg class="w-4 h-4" viewBox="0 0 20 20">
                                                        <path fill="#FACC15" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" viewBox="0 0 20 20">
                                                        <path fill="#FEF08A" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-gray-700">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <p class="mt-4 text-gray-600">Belum ada ulasan untuk produk ini.</p>
                <p class="mt-1 text-sm text-gray-500">Jadilah yang pertama memberikan ulasan!</p>
            </div>
        @endif
    </div>

    <!-- Produk lain dari toko yang sama -->
    @if($moreFromShop->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Produk Lain dari Toko yang Sama</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($moreFromShop as $p)
            <a href="{{ route('product.show', $p) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
                @if($p->image_path)
                    <img src="{{ asset('storage/' . $p->image_path) }}" alt="{{ $p->name }}" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="p-3">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $p->name }}</h3>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs font-bold text-gray-900">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-500">Terjual {{ $p->sold }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related category products (other users) -->
    @if($relatedByCategory->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Produk Terkait dalam Kategori yang Sama</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($relatedByCategory as $p)
            <a href="{{ route('product.show', $p) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
                @if($p->image_path)
                    <img src="{{ asset('storage/' . $p->image_path) }}" alt="{{ $p->name }}" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="p-3">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $p->name }}</h3>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs font-bold text-gray-900">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-500">Terjual {{ $p->sold }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
</x-layouts.app>
