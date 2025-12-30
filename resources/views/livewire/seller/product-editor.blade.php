<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('seller.products') }}" class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Kelola Produk
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $productId ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>
            <p class="text-gray-600 mt-2">Lengkapi semua informasi produk Anda untuk meningkatkan penjualan</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-8">
            <!-- INFORMASI DASAR -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                    <span class="text-2xl">📋</span>
                    <h2 class="text-xl font-bold text-gray-900">Informasi Dasar Produk</h2>
                </div>

                <div class="space-y-4">
                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="name" 
                            placeholder="Contoh: Kemeja Katun Premium Pria"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="category" 
                            placeholder="Contoh: Fashion Pria"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                        @error('category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Produk <span class="text-red-500">*</span></label>
                        <textarea 
                            wire:model="description" 
                            rows="6"
                            placeholder="Jelaskan detail produk Anda... bahan, ukuran, fitur, kualitas, dll"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        ></textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Stock & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                            <input 
                                type="number" 
                                wire:model="stock" 
                                min="0"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            />
                            @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Produk <span class="text-red-500">*</span></label>
                            <select 
                                wire:model="status"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            >
                                <option value="available">Tersedia</option>
                                <option value="sold_out">Habis</option>
                            </select>
                            @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Visibilitas <span class="text-red-500">*</span></label>
                            <select 
                                wire:model="archiveStatus"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            >
                                <option value="active">Aktif (Ditampilkan)</option>
                                <option value="archived">Arsip (Disembunyikan)</option>
                            </select>
                            @error('archiveStatus') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- HARGA & GROSIR -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                    <span class="text-2xl">💰</span>
                    <h2 class="text-xl font-bold text-gray-900">Harga & Diskon Grosir</h2>
                </div>

                <div class="space-y-4">
                    <!-- Harga Normal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Normal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-2 text-gray-500 font-medium">Rp</span>
                            <input 
                                type="number" 
                                wire:model="price" 
                                min="0"
                                placeholder="100000"
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            />
                        </div>
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Harga Grosir -->
                    <div class="border-t pt-4">
                        <h3 class="font-medium text-gray-900 mb-3">Harga Grosir (Opsional)</h3>
                        <p class="text-sm text-gray-600 mb-4">Berikan diskon untuk pembeli yang membeli dalam jumlah banyak</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Grosir</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2 text-gray-500 font-medium">Rp</span>
                                    <input 
                                        type="number" 
                                        wire:model="wholesalePrice" 
                                        min="0"
                                        placeholder="80000"
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    />
                                </div>
                                @error('wholesalePrice') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimal Pembelian (pcs)</label>
                                <input 
                                    type="number" 
                                    wire:model="wholesaleMinQty" 
                                    min="0"
                                    placeholder="10"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                />
                                @error('wholesaleMinQty') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if($wholesalePrice && $price)
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800">
                                    <strong>✓ Hemat:</strong> Rp {{ number_format($price - $wholesalePrice, 0, ',', '.') }} 
                                    ({{ round((($price - $wholesalePrice) / $price) * 100) }}%) untuk pembelian minimal {{ $wholesaleMinQty }} pcs
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MEDIA & GAMBAR -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                    <span class="text-2xl">📸</span>
                    <h2 class="text-xl font-bold text-gray-900">Media & Gambar Produk</h2>
                </div>

                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Upload hingga 5 gambar. Gambar pertama akan menjadi gambar utama.</p>

                    <!-- Upload Area -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Main Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-orange-400 transition cursor-pointer">
                                <input type="file" wire:model="mainImage" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v4a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32 0h-9.5m0 0L28 21m-9-9v20m0-20L9.5 20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">
                                        @if($mainImage)
                                            <span class="font-medium">{{ $mainImage->getClientOriginalName() }}</span>
                                        @else
                                            <span><strong>Klik atau drag</strong> gambar di sini</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Tambahan (Opsional)</label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-orange-400 transition cursor-pointer">
                                <input type="file" wire:model="additionalImages" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v4a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32 0h-9.5m0 0L28 21m-9-9v20m0-20L9.5 20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Tambah hingga 4 gambar lagi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        @if($mainImage)
                            <button type="button" wire:click="addMainImage" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition">
                                + Tambah Gambar Utama
                            </button>
                        @endif
                        @if(count($additionalImages) > 0)
                            <button type="button" wire:click="addAdditionalImage" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition">
                                + Tambah Gambar Tambahan
                            </button>
                        @endif
                    </div>

                    <!-- Existing Images -->
                    @if(count($existingImages) > 0)
                        <div class="border-t pt-4">
                            <h4 class="font-medium text-gray-900 mb-3">Gambar Yang Telah Diupload</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($existingImages as $index => $image)
                                    <div class="relative bg-white rounded-lg border overflow-hidden group">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Product image" class="w-full h-32 object-cover">
                                        
                                        <!-- Badge Utama -->
                                        @if($index === 0)
                                            <div class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded">Utama</div>
                                        @endif

                                        <!-- Actions -->
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                            @if($index > 0)
                                                <button type="button" wire:click="moveImageUp({{ $index }})" class="bg-white text-gray-900 p-2 rounded hover:bg-gray-100" title="Pindah Atas">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V15a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            @endif
                                            @if($index < count($existingImages) - 1)
                                                <button type="button" wire:click="moveImageDown({{ $index }})" class="bg-white text-gray-900 p-2 rounded hover:bg-gray-100" title="Pindah Bawah">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V5a1 1 0 012 0v9.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            @endif
                                            <button type="button" wire:click="removeImage({{ $index }})" class="bg-red-500 text-white p-2 rounded hover:bg-red-600" title="Hapus">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Video Produk -->
                <div class="border-t mt-6 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">📹 Video Produk (Opsional)</h3>
                    <p class="text-sm text-gray-600 mb-4">Unggah video demonstrasi produk untuk meningkatkan konversi</p>

                    @if(!$existingVideo)
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-orange-400 transition cursor-pointer mb-4">
                            <input type="file" wire:model="videoFile" accept="video/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    @if($videoFile)
                                        <span class="font-medium">{{ $videoFile->getClientOriginalName() }}</span>
                                    @else
                                        <span><strong>Klik atau drag</strong> video di sini</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($videoFile)
                            <button type="button" wire:click="addVideo" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition">
                                Upload Video
                            </button>
                        @endif
                    @else
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                <span class="text-gray-700 font-medium">Video sudah diupload</span>
                            </div>
                            <button type="button" wire:click="removeVideo" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition">
                                Hapus
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- LOGISTIK -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                    <span class="text-2xl">📦</span>
                    <h2 class="text-xl font-bold text-gray-900">Informasi Logistik</h2>
                </div>

                <p class="text-sm text-gray-600 mb-4">Sediakan informasi ini untuk perhitungan ongkir yang akurat</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                        <input 
                            type="number" 
                            wire:model="weight" 
                            step="0.1"
                            min="0"
                            placeholder="0.5"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                        @error('weight') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Panjang (cm)</label>
                        <input 
                            type="number" 
                            wire:model="length" 
                            step="0.1"
                            min="0"
                            placeholder="20"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                        @error('length') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lebar (cm)</label>
                        <input 
                            type="number" 
                            wire:model="width" 
                            step="0.1"
                            min="0"
                            placeholder="15"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                        @error('width') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tinggi (cm)</label>
                        <input 
                            type="number" 
                            wire:model="height" 
                            step="0.1"
                            min="0"
                            placeholder="10"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                        @error('height') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- JASA KIRIM -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                    <span class="text-2xl">🚚</span>
                    <h2 class="text-xl font-bold text-gray-900">Pengaturan Jasa Kirim</h2>
                </div>

                <p class="text-sm text-gray-600 mb-4">Pilih jasa kirim yang ingin Anda aktifkan untuk produk ini</p>

                <div class="space-y-3">
                    @foreach($availableCouriers as $courierId => $courierName)
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-orange-50 transition {{ in_array($courierId, $enabledCouriers) ? 'border-orange-300 bg-orange-50' : 'border-gray-300' }}">
                            <input 
                                type="checkbox" 
                                wire:click="toggleCourier('{{ $courierId }}')"
                                @checked(in_array($courierId, $enabledCouriers))
                                class="w-5 h-5 rounded border-gray-300 text-orange-500 focus:ring-orange-500"
                            />
                            <span class="ml-3 font-medium text-gray-900">{{ $courierName }}</span>
                            @if(in_array($courierId, $enabledCouriers))
                                <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded">✓ Aktif</span>
                            @endif
                        </label>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>💡 Tips:</strong> Aktifkan semua jasa kirim untuk memberikan lebih banyak pilihan kepada pembeli
                    </p>
                </div>
            </div>

            <!-- SPESIFIKASI -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b">
                    <span class="text-2xl">⚙️</span>
                    <h2 class="text-xl font-bold text-gray-900">Spesifikasi Produk</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merek</label>
                        <input 
                            type="text" 
                            wire:model="specifications.brand" 
                            placeholder="Contoh: Nike, Adidas, Local Brand, etc"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bahan</label>
                        <input 
                            type="text" 
                            wire:model="specifications.material" 
                            placeholder="Contoh: Katun 100%, Polyester, Kulit Asli, dll"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                        <input 
                            type="text" 
                            wire:model="specifications.color" 
                            placeholder="Contoh: Merah, Biru, Hitam, Multicolor"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                        <select 
                            wire:model="specifications.condition"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                            <option value="new">Baru</option>
                            <option value="used">Bekas</option>
                            <option value="refurbished">Refurbished</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-sm p-6 flex gap-3 justify-end sticky bottom-0">
                <a href="{{ route('seller.products') }}" class="px-6 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition">
                    {{ $productId ? '✓ Update Produk' : '✓ Simpan Produk' }}
                </button>
            </div>
        </form>
    </div>
</div>
