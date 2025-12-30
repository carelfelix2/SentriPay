<x-layouts.app>
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Digital Wallet</h1>
            <p class="mt-2 text-gray-600">Manage your balance and view transaction history</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Balance Card -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-8 text-white">
                <p class="text-sm font-medium opacity-90 uppercase tracking-wide">Available Balance</p>
                <h2 class="text-4xl font-bold mt-2">Rp 0</h2>
                <p class="text-sm mt-4 opacity-75">Last updated just now</p>
            </div>

            <!-- Top Up Card -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Up Balance</h3>
                <form class="space-y-4">
                    <div>
                        <input type="number" placeholder="Amount (Rp)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                        Top Up
                    </button>
                </form>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Stats</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-600">Total Spent</dt>
                        <dd class="text-2xl font-bold text-gray-900">Rp 0</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Transactions</dt>
                        <dd class="text-2xl font-bold text-gray-900">0</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Transaction History</h3>
            </div>

            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="mt-2 text-sm font-medium text-gray-900">No transactions yet</h4>
                <p class="mt-1 text-sm text-gray-500">Your transaction history will appear here.</p>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
