@extends('layouts.app')

@section('title', 'User Profile - Camellia Boutique99')

@section('content')
<div class="p-6 lg:p-10">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Profile Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100/60 p-6">
            @livewire('profile.update-profile-information-form')
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100/60 p-6">
            @livewire('profile.update-password-form')
        </div>

        <!-- Two Factor Authentication -->
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div class="bg-white rounded-2xl shadow-sm border border-amber-100/60 p-6">
                @livewire('profile.two-factor-authentication-form')
            </div>
        @endif

        <!-- Logout Other Browser Sessions -->
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100/60 p-6">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        <!-- Delete Account -->
        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <div class="bg-white rounded-2xl shadow-sm border border-red-100/60 p-6">
                @livewire('profile.delete-user-form')
            </div>
        @endif
    </div>
</div>
@endsection
