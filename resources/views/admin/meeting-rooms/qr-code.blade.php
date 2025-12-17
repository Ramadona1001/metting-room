@extends('layouts.admin')

@section('title', 'رمز QR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h1 class="h3 mb-0">رمز QR: {{ $meetingRoom->name }}</h1>
    <a href="{{ route('admin.meeting-rooms.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" id="qr-card">
            <div class="card-body p-5 text-center">
                <!-- Print Header -->
                <div class="print-header mb-4">
                    <h2 class="fw-bold mb-1">{{ $meetingRoom->name }}</h2>
                    <p class="text-muted mb-0 fs-5">{{ $meetingRoom->company->name }}</p>
                    @if($meetingRoom->department)
                        <p class="text-muted mb-0">{{ $meetingRoom->department->name }}</p>
                    @endif
                </div>

                <!-- QR Code -->
                <div class="qr-container bg-white p-4 d-inline-block border rounded mb-4">
                    {!! $qrCode !!}
                </div>

                <!-- Room Info for Print -->
                <div class="room-info mb-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">السعة</small>
                                <strong>{{ $meetingRoom->capacity }} شخص</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">مدة الحجز القصوى</small>
                                <strong>{{ $meetingRoom->max_booking_duration }} دقيقة</strong>
                            </div>
                        </div>
                    </div>
                    @if($meetingRoom->working_hours_start && $meetingRoom->working_hours_end)
                        <div class="border rounded p-2 mt-2">
                            <small class="text-muted d-block">ساعات العمل</small>
                            <strong>{{ $meetingRoom->working_hours_start }} - {{ $meetingRoom->working_hours_end }}</strong>
                        </div>
                    @endif
                </div>

                <!-- Scan Instructions -->
                <div class="scan-instructions border-top pt-3">
                    <p class="mb-1"><i class="bi bi-phone d-print-none"></i> امسح الرمز لحجز الغرفة</p>
                    <small class="text-muted">Scan QR Code to book this room</small>
                </div>

                <!-- URL (hidden on screen, shown on print) -->
                <div class="url-print mt-3">
                    <small class="text-muted" style="font-size: 10px; word-break: break-all;">{{ $bookingUrl }}</small>
                </div>

                <!-- Action Buttons (hidden on print) -->
                <div class="mt-4 d-print-none">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> طباعة
                    </button>
                    <form action="{{ route('admin.meeting-rooms.regenerate-qr', $meetingRoom) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('هل أنت متأكد؟ سيتم إبطال الرمز القديم.')">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-repeat"></i> تجديد الرمز
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Screen styles */
    .url-print {
        display: none;
    }
    
    /* Print styles */
    @media print {
        /* Hide everything except QR card */
        body * {
            visibility: hidden;
        }
        
        #qr-card, #qr-card * {
            visibility: visible;
        }
        
        #qr-card {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            box-shadow: none !important;
            border: 2px solid #000 !important;
        }
        
        .d-print-none {
            display: none !important;
        }
        
        .url-print {
            display: block;
        }
        
        .qr-container {
            border: none !important;
        }
        
        .qr-container svg {
            width: 200px !important;
            height: 200px !important;
        }
        
        .print-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .print-header p {
            font-size: 16px;
        }
        
        .room-info {
            font-size: 14px;
        }
        
        .scan-instructions {
            font-size: 14px;
        }
        
        .scan-instructions p {
            font-weight: bold;
        }
        
        /* Page settings */
        @page {
            size: A5 portrait;
            margin: 10mm;
        }
    }
</style>
@endsection
