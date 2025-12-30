<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">&larr; Kembali ke Dashboard</a>
            <h1 class="text-3xl font-bold text-gray-900">Pembayaran Pesanan #{{ $order->order_number }}</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Payment Instructions - Left Side -->
            <div class="lg:col-span-2">
                @if($step == 1)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Instruksi Pembayaran</h2>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6">
                        <p class="text-blue-900"><strong>Langkah 1:</strong> Transfer uang ke rekening SentriPay di bawah ini</p>
                    </div>

                    <!-- Bank Account Details -->
                    <div class="bg-white border-2 border-gray-300 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Data Rekening SentriPay</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-600 text-sm">Bank</p>
                                <p class="text-lg font-semibold text-gray-900">Bank Mandiri</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Nomor Rekening</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-lg font-mono font-semibold text-gray-900">1234567890</p>
                                    <button onclick="navigator.clipboard.writeText('1234567890')" class="text-blue-600 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Atas Nama</p>
                                <p class="text-lg font-semibold text-gray-900">PT. SentriPay Indonesia</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Jumlah Transfer</p>
                                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Penting Notes -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 mb-6">
                        <h4 class="font-bold text-yellow-900 mb-2">⚠️ Penting:</h4>
                        <ul class="text-yellow-900 text-sm space-y-1 list-disc list-inside">
                            <li>Transfer tepat sesuai jumlah yang ditentukan</li>
                            <li>Catat nomor referensi transfer Anda</li>
                            <li>Siapkan bukti transfer (screenshot bank/ATM)</li>
                            <li>Transfer akan membuat pesanan Anda terkunci</li>
                        </ul>
                    </div>

                    <!-- Next Step -->
                    <button 
                        @click="$wire.set('step', 2)"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                        Sudah Transfer? Lanjutkan ke Langkah 2
                    </button>
                </div>
                @endif

                @if($step == 2)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Upload Bukti Pembayaran</h2>

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-700 font-semibold">⚠️ Error</p>
                            <p class="text-red-600 text-sm">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form 
                        wire:submit.prevent="submitPaymentProof" 
                        class="space-y-6">
                        <!-- Bank Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Bank Pengirim</label>
                            <select 
                                wire:model="bankName"
                                wire:loading.attr="disabled"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent @error('bankName') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                            >
                                <option value="">Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="Bank Mandiri">Bank Mandiri</option>
                                <option value="BNI">BNI</option>
                                <option value="CIMB Niaga">CIMB Niaga</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('bankName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nomor Rekening Pengirim</label>
                            <input 
                                type="text" 
                                wire:model="accountNumber"
                                wire:loading.attr="disabled"
                                placeholder="Nomor rekening Anda"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent @error('accountNumber') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                            >
                            @error('accountNumber') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Transfer Date -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Tanggal Transfer</label>
                            <input 
                                type="date" 
                                wire:model="transferDate"
                                wire:loading.attr="disabled"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent @error('transferDate') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                            >
                            @error('transferDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bank Proof Upload -->
                        <div x-data="{ 
                            fileName: '',
                            fileSize: '',
                            previewUrl: ''
                        }" wire:key="bank-proof-upload">
                            <label class="block text-gray-700 font-semibold mb-2">Bukti Transfer (Screenshot/Foto)</label>
                            
                            @if($bankProof)
                                <!-- File Preview -->
                                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-green-800">File berhasil dipilih</p>
                                            <p class="text-xs text-green-700">{{ $bankProof->getClientOriginalName() }} ({{ round($bankProof->getSize() / 1024, 2) }} KB)</p>
                                        </div>
                                        <button 
                                            type="button"
                                            wire:click="$set('bankProof', null)"
                                            class="text-green-600 hover:text-green-800 font-medium text-sm">
                                            Ganti
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Image Preview -->
                                <div class="mb-4">
                                    <img src="{{ $bankProof->temporaryUrl() }}" alt="Preview" class="max-h-64 rounded-lg border border-gray-200">
                                </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-600 transition cursor-pointer"
                                 @dragover="$el.classList.add('border-blue-600', 'bg-blue-50')"
                                 @dragleave="$el.classList.remove('border-blue-600', 'bg-blue-50')"
                                 @drop="$el.classList.remove('border-blue-600', 'bg-blue-50')"
                                 :class="@entangle('bankProof').defer ? 'hidden' : ''">
                                <input 
                                    type="file" 
                                    wire:model.live="bankProof"
                                    accept="image/*"
                                    class="hidden"
                                    id="bankProofInput"
                                    @change="fileName = $el.files[0]?.name; fileSize = $el.files[0]?.size"
                                >
                                <label for="bankProofInput" class="cursor-pointer block">
                                    @if($bankProof)
                                    @else
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-700 font-semibold">Drag dan drop atau klik untuk upload</p>
                                        <p class="text-gray-500 text-sm">Format: JPG, PNG, GIF (Maks: 2MB)</p>
                                    @endif
                                </label>
                            </div>
                            
                            @error('bankProof') 
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-red-700 text-sm font-semibold">⚠️ Error</p>
                                    <p class="text-red-600 text-sm">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                            <textarea 
                                wire:model="notes"
                                wire:loading.attr="disabled"
                                rows="3"
                                placeholder="Tambahkan catatan apapun..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                            ></textarea>
                        </div>

                        <!-- Confirmation -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="flex items-center">
                                <input type="checkbox" required wire:loading.attr="disabled" class="rounded border-gray-300 disabled:opacity-50">
                                <span class="ml-3 text-gray-700 text-sm">Saya menjamin bahwa bukti transfer di atas adalah asli dan sesuai</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition">
                            <span wire:loading.remove>Konfirmasi Pembayaran</span>
                            <span wire:loading>
                                <svg class="inline animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>

                    <button 
                        type="button"
                        wire:click="$set('step', 1)"
                        class="w-full mt-4 bg-gray-200 hover:bg-gray-300 text-gray-900 font-bold py-2 px-4 rounded-lg">
                        Kembali
                    </button>
                </div>
                @endif
            </div>

            <!-- Order Summary - Right Side -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                    <div class="space-y-4 pb-4 border-b">
                        <div>
                            <p class="text-gray-600 text-sm">Nomor Pesanan</p>
                            <p class="font-mono font-bold text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Produk</p>
                            <p class="font-semibold text-gray-900">{{ $order->product->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Penjual</p>
                            <p class="font-semibold text-gray-900">{{ $order->seller->name }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 py-4 border-b">
                        <div class="flex justify-between text-gray-600">
                            <span>Harga Satuan:</span>
                            <span>Rp {{ number_format($order->unit_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Jumlah:</span>
                            <span>{{ $order->quantity }} unit</span>
                        </div>
                    </div>

                    <div class="py-4">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-xs text-blue-800">
                            <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
