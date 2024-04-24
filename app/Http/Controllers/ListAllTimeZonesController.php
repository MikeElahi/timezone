<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Entities\Timezone;
use App\Http\Resources\TimezoneResource;
use DateTimeZone;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListAllTimeZonesController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $now = Carbon::now();
        $timezones = collect(DateTimeZone::listIdentifiers())
            ->map(function (string $timezone) use ($now) {
                $timezone = new DateTimeZone($timezone);
                $name = $timezone->getName();
                $offset = $timezone->getOffset(new \DateTime());
                $isDst = $timezone->getTransitions($now->getTimestamp(), $now->getTimestamp())[0]['isdst'];
                return new Timezone($name, $offset, $isDst);
            });

        return TimezoneResource::collection($timezones);
    }
}
