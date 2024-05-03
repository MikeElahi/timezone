<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeZoneCompatibilityRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeZoneCompatibilityController extends Controller
{
    public function __invoke(TimeZoneCompatibilityRequest $request)
    {
        /** @var string $time */
        $time = $request->get('time');
        /** @var string $timezone */
        $timezone = $request->get('timezone');
        /** @var array<string> $destinationTimezones */
        $destinationTimezones = $request->get('destination_timezones');
        /** @var string|null $workingHoursStart */
        $workingHoursStart = $request->get('working_hours_start', '09:00');
        /** @var string|null $workingHoursEnd */
        $workingHoursEnd = $request->get('working_hours_end', '17:00');


        $currentTime = Carbon::createFromFormat('H:i', $time, $timezone);

        $destinationTimes = [
            $timezone => $currentTime->format('H:i'),
        ];
        $valid = true;
        foreach ($destinationTimezones as $destinationTimezone) {
            $destinationTime = $currentTime->copy()->setTimezone($destinationTimezone);
            $workingHoursStart = Carbon::createFromFormat('H:i', $workingHoursStart, $destinationTimezone);
            $workingHoursEnd = Carbon::createFromFormat('H:i', $workingHoursEnd, $destinationTimezone);
            $destinationTimes[$destinationTimezone] = $destinationTime->format('H:i');
            if ($destinationTime->lessThan($workingHoursStart) || $destinationTime->greaterThan($workingHoursEnd)) {
                $valid = false;
            }
        }


        return response()->json([
            'valid' => $valid,
            'timezones' => $destinationTimes,
        ]);
    }
}
