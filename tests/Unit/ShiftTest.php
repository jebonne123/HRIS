<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftTest extends TestCase
{
    public function test_shift_can_calculate_scheduled_minutes()
    {
        $shift = Shift::create([
            'name' => 'Test Shift',
            'start_time_1' => '09:00:00',
            'end_time_1' => '12:00:00',
            'start_time_2' => '13:00:00',
            'end_time_2' => '17:00:00',
            'bell_enabled' => false,
            'allow_late_minutes' => 15,
            'allow_early_minutes' => 15,
            'rest_days' => 2,
            'enable_ot' => true,
            'ot_start_minutes' => 60,
            'ot_valid_minutes' => 30,
        ]);

        $scheduledMinutes = $shift->getTotalScheduledMinutes();
        
        // 3 hours (180 min) + 4 hours (240 min) = 420 minutes
        $this->assertEquals(420, $scheduledMinutes);
    }

    public function test_shift_can_detect_late_checkin()
    {
        $shift = Shift::create([
            'name' => 'Test Shift',
            'start_time_1' => '09:00:00',
            'end_time_1' => '17:00:00',
            'allow_late_minutes' => 15,
        ]);

        // Check-in at 9:20 (20 minutes late, but only 15 allowed)
        $lateCheckIn = '09:20:00';
        $this->assertTrue($shift->isLate($lateCheckIn));

        // Check-in at 9:10 (10 minutes late, within 15 allowed)
        $onTimeCheckIn = '09:10:00';
        $this->assertFalse($shift->isLate($onTimeCheckIn));
    }

    public function test_shift_can_calculate_overtime()
    {
        $shift = Shift::create([
            'name' => 'Test Shift',
            'start_time_1' => '09:00:00',
            'end_time_1' => '17:00:00',
            'enable_ot' => true,
            'ot_start_minutes' => 60,
            'ot_valid_minutes' => 30,
        ]);

        $scheduledMinutes = 480; // 8 hours
        
        // Worked 9 hours (540 min) - should get 30 min OT
        $workMinutes = 540;
        $otMinutes = $shift->calculateOvertime($workMinutes, $scheduledMinutes);
        $this->assertEquals(30, $otMinutes);

        // Worked 8.5 hours (510 min) - should get 0 min OT (less than 60 min threshold)
        $workMinutes = 510;
        $otMinutes = $shift->calculateOvertime($workMinutes, $scheduledMinutes);
        $this->assertEquals(0, $otMinutes);

        // Worked 10 hours (600 min) - should get 60 min OT (in 30-min blocks)
        $workMinutes = 600;
        $otMinutes = $shift->calculateOvertime($workMinutes, $scheduledMinutes);
        $this->assertEquals(60, $otMinutes);
    }

    public function test_shift_with_ot_disabled_returns_zero_overtime()
    {
        $shift = Shift::create([
            'name' => 'Test Shift',
            'start_time_1' => '09:00:00',
            'end_time_1' => '17:00:00',
            'enable_ot' => false,
            'ot_start_minutes' => 60,
            'ot_valid_minutes' => 30,
        ]);

        $scheduledMinutes = 480;
        $workMinutes = 540;
        
        $otMinutes = $shift->calculateOvertime($workMinutes, $scheduledMinutes);
        $this->assertEquals(0, $otMinutes);
    }
}




