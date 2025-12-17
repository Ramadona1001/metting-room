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
                        <p class="text-xl font-bold text-gray-800">
                            من :  {{ \Carbon\Carbon::parse($room->working_hours_start)->format('h:i A') }} -  إلى : {{ \Carbon\Carbon::parse($room->working_hours_end)->format('h:i A') }}
                        </p>
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
                                <span class="text-red-600">
                                    {{ $booking->start_time->format('h:i A') }} - {{ $booking->end_time->format('h:i A') }}
                                </span>
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
                    <label for="employee_name" class="block text-gray-700 font-medium mb-2">الاسم <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_name" id="employee_name" value="{{ old('employee_name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="أدخل اسمك الكامل"
                           required>
                </div>

                <div class="mb-4">
                    <label for="employee_email" class="block text-gray-700 font-medium mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="employee_email" id="employee_email" value="{{ old('employee_email') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="example@company.com"
                           required>
                </div>

                <div class="mb-4">
                    <label for="company_id" class="block text-gray-700 font-medium mb-2">الشركة <span class="text-red-500">*</span></label>
                    <select name="company_id" id="company_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required>
                        <option value="">-- اختر الشركة --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="department_id" class="block text-gray-700 font-medium mb-2">القسم <span class="text-red-500">*</span></label>
                    <select name="department_id" id="department_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required disabled>
                        <option value="">-- اختر الشركة أولاً --</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="booking_date" class="block text-gray-700 font-medium mb-2">تاريخ الحجز <span class="text-red-500">*</span></label>
                    <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           min="{{ date('Y-m-d') }}"
                           max="{{ date('Y-m-d') }}"
                           required>
                    <p class="text-gray-500 text-sm mt-1">* الحجز متاح لنفس اليوم فقط</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_time" class="block text-gray-700 font-medium mb-2">وقت البداية <span class="text-red-500">*</span></label>
                        <select name="start_time" id="start_time" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                            <option value="">-- اختر الوقت --</option>
                        </select>
                    </div>
                    <div>
                        <label for="end_time" class="block text-gray-700 font-medium mb-2">وقت الانتهاء <span class="text-red-500">*</span></label>
                        <select name="end_time" id="end_time" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                            <option value="">-- اختر وقت البداية أولاً --</option>
                        </select>
                    </div>
                </div>

                <!-- Available Time Slots Info -->
                <div id="time-slots-info" class="mb-6 p-4 bg-blue-50 rounded-lg hidden">
                    <h3 class="font-medium text-blue-800 mb-2">الأوقات المتاحة:</h3>
                    <div id="available-slots" class="text-blue-600 text-sm"></div>
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
    // Room configuration
    const workingHoursStart = '{{ $room->working_hours_start ?? "08:00" }}';
    const workingHoursEnd = '{{ $room->working_hours_end ?? "18:00" }}';
    const maxDuration = {{ $room->max_booking_duration ?? 120 }};
    const bookedSlots = @json($todayBookings->map(function($b) {
        return [
            'start' => $b->start_time->format('H:i'),
            'end' => $b->end_time->format('H:i')
        ];
    }));

    // Convert 24h to 12h format
    function formatTime12h(time24) {
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }

    // Check if time slot is booked
    function isTimeBooked(time) {
        const timeMinutes = timeToMinutes(time);
        for (const slot of bookedSlots) {
            const startMinutes = timeToMinutes(slot.start);
            const endMinutes = timeToMinutes(slot.end);
            if (timeMinutes >= startMinutes && timeMinutes < endMinutes) {
                return true;
            }
        }
        return false;
    }

    // Check if time range overlaps with booked slots
    function hasOverlap(startTime, endTime) {
        const startMinutes = timeToMinutes(startTime);
        const endMinutes = timeToMinutes(endTime);
        for (const slot of bookedSlots) {
            const bookedStart = timeToMinutes(slot.start);
            const bookedEnd = timeToMinutes(slot.end);
            if (startMinutes < bookedEnd && endMinutes > bookedStart) {
                return true;
            }
        }
        return false;
    }

    // Convert time string to minutes
    function timeToMinutes(time) {
        const [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    }

    // Convert minutes to time string
    function minutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
    }

    // Generate available start times
    function generateStartTimes() {
        const startSelect = document.getElementById('start_time');
        startSelect.innerHTML = '<option value="">-- اختر الوقت --</option>';
        
        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        const workStart = timeToMinutes(workingHoursStart);
        const workEnd = timeToMinutes(workingHoursEnd);
        
        // Start from current time (rounded up to next 30 min) or working hours start
        let startFrom = Math.max(workStart, Math.ceil(currentMinutes / 30) * 30);
        
        const availableSlots = [];
        
        for (let time = startFrom; time < workEnd - 30; time += 30) {
            const timeStr = minutesToTime(time);
            if (!isTimeBooked(timeStr)) {
                availableSlots.push(timeStr);
                const option = document.createElement('option');
                option.value = timeStr;
                option.textContent = formatTime12h(timeStr);
                startSelect.appendChild(option);
            }
        }
        
        // Show available slots info
        updateAvailableSlotsInfo();
    }

    // Generate available end times based on start time
    function generateEndTimes(startTime) {
        const endSelect = document.getElementById('end_time');
        endSelect.innerHTML = '<option value="">-- اختر الوقت --</option>';
        
        if (!startTime) return;
        
        const startMinutes = timeToMinutes(startTime);
        const workEnd = timeToMinutes(workingHoursEnd);
        const maxEndMinutes = Math.min(startMinutes + maxDuration, workEnd);
        
        for (let time = startMinutes + 30; time <= maxEndMinutes; time += 30) {
            const timeStr = minutesToTime(time);
            // Check if this end time would create an overlap
            if (!hasOverlap(startTime, timeStr)) {
                const option = document.createElement('option');
                option.value = timeStr;
                const duration = time - startMinutes;
                option.textContent = `${formatTime12h(timeStr)} (${duration} دقيقة)`;
                endSelect.appendChild(option);
            } else {
                // Stop adding options if we hit a booked slot
                break;
            }
        }
    }

    // Update available slots info display
    function updateAvailableSlotsInfo() {
        const infoDiv = document.getElementById('time-slots-info');
        const slotsDiv = document.getElementById('available-slots');
        
        if (bookedSlots.length === 0) {
            infoDiv.classList.add('hidden');
            return;
        }
        
        infoDiv.classList.remove('hidden');
        
        // Calculate free slots
        const workStart = timeToMinutes(workingHoursStart);
        const workEnd = timeToMinutes(workingHoursEnd);
        const freeSlots = [];
        
        let currentStart = workStart;
        const sortedBookings = [...bookedSlots].sort((a, b) => timeToMinutes(a.start) - timeToMinutes(b.start));
        
        for (const booking of sortedBookings) {
            const bookingStart = timeToMinutes(booking.start);
            const bookingEnd = timeToMinutes(booking.end);
            
            if (currentStart < bookingStart) {
                freeSlots.push({
                    start: minutesToTime(currentStart),
                    end: minutesToTime(bookingStart)
                });
            }
            currentStart = Math.max(currentStart, bookingEnd);
        }
        
        if (currentStart < workEnd) {
            freeSlots.push({
                start: minutesToTime(currentStart),
                end: minutesToTime(workEnd)
            });
        }
        
        // Filter to show only future slots
        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        
        const futureFreeSlots = freeSlots.filter(slot => timeToMinutes(slot.end) > currentMinutes);
        
        if (futureFreeSlots.length === 0) {
            slotsDiv.innerHTML = '<span class="text-red-600">لا توجد أوقات متاحة اليوم</span>';
        } else {
            slotsDiv.innerHTML = futureFreeSlots.map(slot => 
                `<span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded mr-2 mb-1">
                    ${formatTime12h(slot.start)} - ${formatTime12h(slot.end)}
                </span>`
            ).join('');
        }
    }

    // Event listeners
    document.getElementById('start_time').addEventListener('change', function() {
        generateEndTimes(this.value);
    });

    document.getElementById('booking_date').addEventListener('change', function() {
        // For now, only today is allowed
        generateStartTimes();
    });

    // Company change - load departments
    document.getElementById('company_id').addEventListener('change', function() {
        const companyId = this.value;
        const deptSelect = document.getElementById('department_id');
        
        if (!companyId) {
            deptSelect.innerHTML = '<option value="">-- اختر الشركة أولاً --</option>';
            deptSelect.disabled = true;
            return;
        }
        
        deptSelect.innerHTML = '<option value="">جاري التحميل...</option>';
        deptSelect.disabled = true;
        
        fetch(`/api/departments/${companyId}`)
            .then(response => response.json())
            .then(departments => {
                deptSelect.innerHTML = '<option value="">-- اختر القسم --</option>';
                departments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    deptSelect.appendChild(option);
                });
                deptSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                deptSelect.innerHTML = '<option value="">خطأ في تحميل الأقسام</option>';
            });
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        generateStartTimes();
        
        // If old company_id exists, trigger change to load departments
        const companySelect = document.getElementById('company_id');
        if (companySelect.value) {
            companySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
