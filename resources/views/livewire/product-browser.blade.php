<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-gray-900">Jelajahi Produk</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filter with Alpine.js -->
            <div class="lg:col-span-1" x-data="{ 
                open: false,
                search: @entangle('search'),
                category: @entangle('category'),
                sortBy: @entangle('sortBy')
            }">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <button @click="open = !open" class="lg:hidden w-full mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg">
                        <span x-text="open ? 'Tutup Filter' : 'Buka Filter'"></span>
                    </button>

                    <div :class="{ 'hidden': !open, 'block': open }" class="lg:block">
                        <!-- Search -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                            <input 
                                type="text" 
                                x-model="search"
                                placeholder="Nama produk..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select 
                                x-model="category"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                            <select 
                                x-model="sortBy"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="newest">Terbaru</option>
                                <option value="price_low">Harga Terendah</option>
                                <option value="price_high">Harga Tertinggi</option>
                                <option value="popular">Paling Populer</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden group">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Stock Badge -->
                            <div class="absolute top-2 right-2">
                                <span class="inline-block px-3 py-1 text-xs rounded-full font-semibold
                                    {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $product->description }}</p>
                            
                            <!-- Rating -->
                            @php
                                $avgRating = $product->averageRating();
                                $totalReviews = $product->totalReviews();
                            @endphp
                            @if($totalReviews > 0)
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($avgRating))
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
                                <span class="text-sm text-gray-600">{{ number_format($avgRating, 1) }} ({{ $totalReviews }})</span>
                            </div>
                            @endif
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">Terjual: {{ $product->sold }}</span>
                            </div>

                            <!-- Seller Info -->
                            <div class="mb-4 pb-4 border-b">
                                <p class="text-xs text-gray-600">Penjual: <span class="font-semibold">{{ $product->seller->name }}</span></p>
                            </div>

                            <!-- Action Button -->
                            @if(auth()->check() && $product->stock > 0)
                                <a href="{{ route('checkout', $product->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                                    Beli Sekarang
                                </a>
                            @elseif(!auth()->check())
                                <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                                    Masuk untuk Membeli
                                </a>
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                    Produk Habis
                                </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Produk tidak ditemukan</h3>
                        <p class="mt-2 text-gray-500">Coba ubah filter pencarian Anda</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
