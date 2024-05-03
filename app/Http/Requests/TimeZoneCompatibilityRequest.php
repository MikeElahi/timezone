<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeZoneCompatibilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'time' => 'required|date_format:H:i',
            'timezone' => 'required|string|timezone',
            'destination_timezones' => 'required|array',
            'destination_timezones.*' => 'string|timezone',
            'working_hours_start' => 'date_format:H:i',
            'working_hours_end' => 'date_format:H:i',
        ];
    }
}
