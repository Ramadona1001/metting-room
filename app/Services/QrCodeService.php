<?php

namespace App\Services;

use App\Models\MeetingRoom;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateQrCode(MeetingRoom $room, int $size = 300): string
    {
        $bookingUrl = $this->getBookingUrl($room);

        return QrCode::format('svg')
            ->size($size)
            ->errorCorrection('H')
            ->generate($bookingUrl);
    }

    public function getBookingUrl(MeetingRoom $room): string
    {
        return config('app.frontend_url', config('app.url')) . '/book/' . $room->qr_token;
    }

    public function regenerateToken(MeetingRoom $room): string
    {
        $newToken = \Illuminate\Support\Str::uuid()->toString();
        $room->update(['qr_token' => $newToken]);

        return $newToken;
    }
}

