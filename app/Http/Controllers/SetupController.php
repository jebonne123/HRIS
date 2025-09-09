<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetupRequest;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetupController extends Controller
{
    public function status()
    {
        return response()->json([
            'setup_completed' => Setting::isSetupCompleted(),
            'shifts_count' => Shift::count(),
            'departments_count' => Department::count(),
        ]);
    }

    public function complete(SetupRequest $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        // Update company settings
        Setting::set('company_name', $data['company_name']);
        Setting::set('company_address', $data['company_address'] ?? '');
        Setting::set('company_phone', $data['company_phone'] ?? '');
        Setting::set('company_email', $data['company_email'] ?? '');
        Setting::set('timezone', $data['timezone'] ?? 'UTC');

        // Create or update a default shift if data provided or no shifts exist yet
        if (Shift::count() === 0 || !empty($data['shift_name'])) {
            $shiftPayload = [
                'name' => $data['shift_name'] ?? 'Default Day Shift',
                'start_time_1' => $data['start_time_1'] ?? '08:00',
                'end_time_1' => $data['end_time_1'] ?? '12:00',
                'start_time_2' => $data['start_time_2'] ?? '13:00',
                'end_time_2' => $data['end_time_2'] ?? '17:00',
                'bell_enabled' => $data['bell_enabled'] ?? false,
                'allow_late_minutes' => $data['allow_late_minutes'] ?? 0,
                'allow_early_minutes' => $data['allow_early_minutes'] ?? 0,
                'rest_days' => $data['rest_days'] ?? 2,
                'enable_ot' => $data['enable_ot'] ?? true,
                'ot_start_minutes' => $data['ot_start_minutes'] ?? 60,
                'ot_valid_minutes' => $data['ot_valid_minutes'] ?? 30,
            ];

            // Ensure types for booleans/integers
            $shiftPayload['bell_enabled'] = (bool) $shiftPayload['bell_enabled'];
            $shiftPayload['enable_ot'] = (bool) $shiftPayload['enable_ot'];
            $shiftPayload['allow_late_minutes'] = (int) $shiftPayload['allow_late_minutes'];
            $shiftPayload['allow_early_minutes'] = (int) $shiftPayload['allow_early_minutes'];
            $shiftPayload['rest_days'] = (int) $shiftPayload['rest_days'];
            $shiftPayload['ot_start_minutes'] = (int) $shiftPayload['ot_start_minutes'];
            $shiftPayload['ot_valid_minutes'] = (int) $shiftPayload['ot_valid_minutes'];

            // Create the shift (first-run default)
            Shift::create($shiftPayload);
        }

        // Mark setup as completed
        Setting::markSetupCompleted();

        return response()->json([
            'message' => 'Setup completed successfully',
            'setup_completed' => true
        ]);
    }
}


