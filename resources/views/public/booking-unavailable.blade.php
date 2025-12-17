@extends('layouts.public')

@section('title', 'غرفة غير متاحة')

@section('content')
<div class="max-w-md mx-auto text-center">
    <div class="bg-white rounded-lg shadow-xl p-8">
        <div class="text-red-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">غرفة غير متاحة</h1>
        <p class="text-gray-600">{{ $message }}</p>
    </div>
</div>
@endsection

