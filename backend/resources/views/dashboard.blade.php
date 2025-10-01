@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Dashboard</h1>
        <p class="text-xl text-gray">Welcome to your RayoChat admin panel</p>
    </div>

    <div class="card">
        <div class="text-center">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">User Information</h2>
                <p class="text-gray mb-4">Here are your account details</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Role</label>
                        <p class="text-lg font-semibold text-orange">{{ $roleName }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Member Since</label>
                        <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Last Login</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y H:i') : 'First login' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Session Status</label>
                        <p class="text-lg font-semibold text-green-600">Active & Persistent</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 justify-center">
                <button class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Manage Settings
                </button>
                <button class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Reports
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="card text-center">
            <div class="text-orange text-3xl font-bold mb-2">1</div>
            <h3 class="font-semibold text-gray-900 mb-2">Active Users</h3>
            <p class="text-gray text-sm">Currently online</p>
        </div>
        <div class="card text-center">
            <div class="text-orange text-3xl font-bold mb-2">0</div>
            <h3 class="font-semibold text-gray-900 mb-2">Messages Sent</h3>
            <p class="text-gray text-sm">This month</p>
        </div>
        <div class="card text-center">
            <div class="text-orange text-3xl font-bold mb-2">100%</div>
            <h3 class="font-semibold text-gray-900 mb-2">Uptime</h3>
            <p class="text-gray text-sm">System status</p>
        </div>
    </div>
</div>

<style>
    .grid {
        display: grid;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    
    .gap-4 {
        gap: 1rem;
    }
    
    .gap-6 {
        gap: 1.5rem;
    }
    
    .text-left {
        text-align: left;
    }
    
    .text-sm {
        font-size: 0.875rem;
    }
    
    .text-lg {
        font-size: 1.125rem;
    }
    
    .text-xl {
        font-size: 1.25rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .text-4xl {
        font-size: 2.25rem;
    }
    
    .font-medium {
        font-weight: 500;
    }
    
    .font-semibold {
        font-weight: 600;
    }
    
    .font-bold {
        font-weight: 700;
    }
    
    .text-gray-500 {
        color: #6b7280;
    }
    
    .text-gray-900 {
        color: #111827;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb;
    }
    
    .rounded-lg {
        border-radius: 0.5rem;
    }
    
    .p-6 {
        padding: 1.5rem;
    }
    
    .w-5 {
        width: 1.25rem;
    }
    
    .h-5 {
        height: 1.25rem;
    }
    
    .w-8 {
        width: 2rem;
    }
    
    .h-8 {
        height: 2rem;
    }
    
    .w-16 {
        width: 4rem;
    }
    
    .h-16 {
        height: 4rem;
    }
    
    .mr-2 {
        margin-right: 0.5rem;
    }
    
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    
    .mb-4 {
        margin-bottom: 1rem;
    }
    
    .mb-6 {
        margin-bottom: 1.5rem;
    }
    
    .mb-8 {
        margin-bottom: 2rem;
    }
    
    .mt-8 {
        margin-top: 2rem;
    }
    
    .inline-flex {
        display: inline-flex;
    }
    
    .items-center {
        align-items: center;
    }
    
    .justify-center {
        justify-content: center;
    }
    
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    .from-orange-400 {
        --tw-gradient-from: #fb923c;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(251, 146, 60, 0));
    }
    
    .to-orange-500 {
        --tw-gradient-to: #f97316;
    }
    
    .rounded-full {
        border-radius: 9999px;
    }
    
    .text-white {
        color: #ffffff;
    }
    
    .text-orange {
        color: #ff6b35;
    }
    
    .text-gray {
        color: #4a5568;
    }
    
    .hover\:underline:hover {
        text-decoration: underline;
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
</style>
@endsection
