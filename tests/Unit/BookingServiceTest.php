<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Company;
use App\Models\MeetingRoom;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $bookingService;
    private MeetingRoom $room;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookingService = new BookingService();

        $company = Company::create([
            'name' => 'Test Company',
            'status' => 'active',
        ]);

        $this->room = MeetingRoom::create([
            'company_id' => $company->id,
            'name' => 'Test Room',
            'capacity' => 10,
            'status' => 'active',
            'max_booking_duration' => 120,
        ]);
    }

    public function test_detects_overlapping_bookings(): void
    {
        // Create existing booking from 10:00 to 11:00
        Booking::create([
            'meeting_room_id' => $this->room->id,
            'employee_name' => 'Test User',
            'employee_email' => 'test@test.com',
            'start_time' => Carbon::today()->setHour(10),
            'end_time' => Carbon::today()->setHour(11),
            'status' => 'confirmed',
        ]);

        // Test overlap: 10:30 to 11:30 (overlaps with existing)
        $hasConflict = $this->bookingService->hasConflict(
            $this->room->id,
            Carbon::today()->setHour(10)->setMinute(30),
            Carbon::today()->setHour(11)->setMinute(30)
        );

        $this->assertTrue($hasConflict);
    }

    public function test_allows_non_overlapping_bookings(): void
    {
        // Create existing booking from 10:00 to 11:00
        Booking::create([
            'meeting_room_id' => $this->room->id,
            'employee_name' => 'Test User',
            'employee_email' => 'test@test.com',
            'start_time' => Carbon::today()->setHour(10),
            'end_time' => Carbon::today()->setHour(11),
            'status' => 'confirmed',
        ]);

        // Test non-overlap: 11:00 to 12:00 (after existing)
        $hasConflict = $this->bookingService->hasConflict(
            $this->room->id,
            Carbon::today()->setHour(11),
            Carbon::today()->setHour(12)
        );

        $this->assertFalse($hasConflict);
    }

    public function test_detects_booking_inside_existing(): void
    {
        // Create existing booking from 10:00 to 12:00
        Booking::create([
            'meeting_room_id' => $this->room->id,
            'employee_name' => 'Test User',
            'employee_email' => 'test@test.com',
            'start_time' => Carbon::today()->setHour(10),
            'end_time' => Carbon::today()->setHour(12),
            'status' => 'confirmed',
        ]);

        // Test booking inside existing: 10:30 to 11:30
        $hasConflict = $this->bookingService->hasConflict(
            $this->room->id,
            Carbon::today()->setHour(10)->setMinute(30),
            Carbon::today()->setHour(11)->setMinute(30)
        );

        $this->assertTrue($hasConflict);
    }

    public function test_ignores_cancelled_bookings(): void
    {
        // Create cancelled booking from 10:00 to 11:00
        Booking::create([
            'meeting_room_id' => $this->room->id,
            'employee_name' => 'Test User',
            'employee_email' => 'test@test.com',
            'start_time' => Carbon::today()->setHour(10),
            'end_time' => Carbon::today()->setHour(11),
            'status' => 'cancelled',
        ]);

        // Should not conflict with cancelled booking
        $hasConflict = $this->bookingService->hasConflict(
            $this->room->id,
            Carbon::today()->setHour(10),
            Carbon::today()->setHour(11)
        );

        $this->assertFalse($hasConflict);
    }

    public function test_rejects_booking_for_inactive_room(): void
    {
        $this->room->update(['status' => 'inactive']);

        $result = $this->bookingService->createBooking($this->room, [
            'employee_name' => 'Test User',
            'employee_email' => 'test@test.com',
            'start_time' => Carbon::now()->addHour()->format('Y-m-d H:i'),
            'end_time' => Carbon::now()->addHours(2)->format('Y-m-d H:i'),
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('غرفة الاجتماعات غير متاحة حالياً', $result['message']);
    }

    public function test_rejects_booking_exceeding_max_duration(): void
    {
        $this->room->update(['max_booking_duration' => 60]);

        $result = $this->bookingService->createBooking($this->room, [
            'employee_name' => 'Test User',
            'employee_email' => 'test@test.com',
            'start_time' => Carbon::now()->addHour()->format('Y-m-d H:i'),
            'end_time' => Carbon::now()->addHours(3)->format('Y-m-d H:i'),
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContains('مدة الحجز يجب ألا تتجاوز', $result['message']);
    }

    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(
            str_contains($haystack, $needle),
            "Failed asserting that '$haystack' contains '$needle'"
        );
    }
}

