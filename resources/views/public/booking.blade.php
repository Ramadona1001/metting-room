@extends('layouts.public')

@section('title', 'حجز غرفة اجتماعات - ' . $room->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <!-- Room Info Header -->
        <div class="bg-indigo-600 text-white p-6">
            <h1 class="text-2xl font-bold">{{ $room->name }}</h1>
            <p class="text-indigo-200 mt-1">{{ $room->company->name }}</p>
            @if($room->department)
                <p class="text-indigo-200 text-sm">{{ $room->department->name }}</p>
            @endif
        </div>

        <div class="p-6">
            <!-- Room Details -->
            <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-gray-500 text-sm">السعة</p>
                    <p class="text-xl font-bold text-gray-800">{{ $room->capacity }} شخص</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-gray-500 text-sm">الحد الأقصى للحجز</p>
                    <p class="text-xl font-bold text-gray-800">{{ $room->max_booking_duration }} دقيقة</p>
                </div>
                @if($room->working_hours_start && $room->working_hours_end)
                    <div class="col-span-2 text-center p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 text-sm">ساعات العمل</p>
                        <p class="text-xl font-bold text-gray-800">{{ $room->working_hours_start }} - {{ $room->working_hours_end }}</p>
                    </div>
                @endif
            </div>

            <!-- Today's Bookings -->
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">حجوزات اليوم</h2>
                @if($todayBookings->count() > 0)
                    <div class="space-y-2">
                        @foreach($todayBookings as $booking)
                            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg border border-red-200">
                                <span class="text-red-800 font-medium">محجوز</span>
                                <span class="text-red-600">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-green-600 bg-green-50 p-3 rounded-lg">لا توجد حجوزات اليوم - الغرفة متاحة</p>
                @endif
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Booking Form -->
            <form action="{{ route('public.booking.store', $room->qr_token) }}" method="POST">
                @csrf

                <h2 class="text-lg font-bold text-gray-800 mb-4">حجز جديد</h2>

                <div class="mb-4">
                    <label for="employee_name" class="block text-gray-700 font-medium mb-2">الاسم</label>
                    <input type="text" name="employee_name" id="employee_name" value="{{ old('employee_name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           required>
                </div>

                <div class="mb-4">
                    <label for="employee_email" class="block text-gray-700 font-medium mb-2">البريد الإلكتروني</label>
                    <input type="email" name="employee_email" id="employee_email" value="{{ old('employee_email') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="start_time" class="block text-gray-700 font-medium mb-2">وقت البداية</label>
                        <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               required>
                    </div>
                    <div>
                        <label for="end_time" class="block text-gray-700 font-medium mb-2">وقت الانتهاء</label>
                        <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               required>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                    تأكيد الحجز
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Set min date to today and format for datetime-local input
    const now = new Date();
    const today = now.toISOString().slice(0, 10);
    
    document.getElementById('start_time').min = now.toISOString().slice(0, 16);
    document.getElementById('end_time').min = now.toISOString().slice(0, 16);

    // Auto-update end time min when start time changes
    document.getElementById('start_time').addEventListener('change', function() {
        document.getElementById('end_time').min = this.value;
    });
</script>
@endsection

