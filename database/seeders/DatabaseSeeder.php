<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Company;
use App\Models\Department;
use App\Models\MeetingRoom;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        Admin::create([
            'name' => 'مدير النظام',
            'email' => 'superadmin@meeting.com',
            'password' => Hash::make('21q1v2MmgUL2'),
        ]);
        
        Admin::create([
            'name' => 'مدير النظام',
            'email' => 'hr@hojuzat.com',
            'password' => Hash::make('21q1v2MmgUL2'),
        ]);
        
        Admin::create([
            'name' => 'مدير النظام',
            'email' => 'hr@bavaya.com',
            'password' => Hash::make('21q1v2MmgUL2'),
        ]);
        
        Admin::create([
            'name' => 'مدير النظام',
            'email' => 'hr@bevatel.com',
            'password' => Hash::make('21q1v2MmgUL2'),
        ]);
        
        Admin::create([
            'name' => 'مدير النظام',
            'email' => 'hr@ocoda.com',
            'password' => Hash::make('21q1v2MmgUL2'),
        ]);

        

        // Create Companies
        $companies = [
            ['name' => 'شركة أكودا', 'status' => 'active'],
            ['name' => 'شركة بيفاتيل', 'status' => 'active'],
            ['name' => 'شركة حجوزات العالم', 'status' => 'active'],
            ['name' => 'شركة ويلز', 'status' => 'active'],
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            // Create Departments
            $departments = ['الموارد البشرية', 'تقنية المعلومات', 'المالية', 'التسويق'];
            foreach ($departments as $deptName) {
                Department::create([
                    'company_id' => $company->id,
                    'name' => $deptName,
                ]);
            }

            // Create Meeting Rooms
            // for ($i = 1; $i <= 3; $i++) {
            //     $room = MeetingRoom::create([
            //         'company_id' => $company->id,
            //         'department_id' => $company->departments->random()->id,
            //         'name' => "قاعة الاجتماعات {$i}",
            //         'capacity' => rand(5, 20),
            //         'status' => $company->status === 'active' ? 'active' : 'inactive',
            //         'max_booking_duration' => 120,
            //         'working_hours_start' => '08:00',
            //         'working_hours_end' => '18:00',
            //     ]);

            //     // Create sample bookings for active rooms
            //     if ($room->status === 'active') {
            //         $startHour = rand(9, 14);
            //         Booking::create([
            //             'meeting_room_id' => $room->id,
            //             'employee_name' => 'أحمد محمد',
            //             'employee_email' => 'ahmed@example.com',
            //             'start_time' => Carbon::today()->setHour($startHour),
            //             'end_time' => Carbon::today()->setHour($startHour + 1),
            //             'status' => 'confirmed',
            //         ]);
            //     }
            // }
        }
    }
}
