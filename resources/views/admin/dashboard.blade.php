<x-app-layout>
    <div class="min-h-screen bg-gray-100">
        <!-- Admin Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
                        <p class="text-purple-100 mt-1">Panel kontrol administrator SentriPay</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-white text-sm">{{ now()->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-8">
            @livewire('admin.admin-dashboard')
        </div>
    </div>
</x-app-layout>
