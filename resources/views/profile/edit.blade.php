<x-layouts.app>
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-2 text-gray-600">Manage your account information and preferences</p>
        </div>

        <!-- Profile Forms -->
        <div class="space-y-6">
            <!-- Update Profile Information -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Profile Information</h2>
                @include('profile.update-profile-information-form')
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h2>
                @include('profile.update-password-form')
            </div>

            <!-- Two Factor Authentication -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::twoFactorAuthentication()))
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Two Factor Authentication</h2>
                    @include('profile.two-factor-authentication-form')
                </div>
            @endif

            <!-- Browser Sessions -->
            @if (Laravel\Jetstream\Jetstream::hasSecurityFeatures())
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Browser Sessions</h2>
                    @include('profile.logout-other-browser-sessions-form')
                </div>
            @endif

            <!-- Delete Account -->
            <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-red-500">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Delete Account</h2>
                @include('profile.delete-user-form')
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
