<?php

namespace App\Http\Resources;

use App\Contracts\TimezoneInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimezoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        assert($this->resource instanceof TimezoneInterface);
        // offset is in seconds, convert to hours and minutes like +01:00
        return  [
            'name' => $this->resource->getName(),
            'offset' => $this->normalizeOffset($this->resource->getOffset()),
            'is_dst' => $this->resource->isDst(),
        ];
    }

    private function normalizeOffset(int $offset): string
    {
        $hours = floor($offset / 3600);
        $minutes = floor(($offset % 3600) / 60);
        $sign = $offset < 0 ? '-' : '+';
        return sprintf('%s%02d:%02d', $sign, abs($hours), abs($minutes));
    }
}
