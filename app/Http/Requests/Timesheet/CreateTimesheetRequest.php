<?php

namespace App\Http\Requests\Timesheet;

use Illuminate\Foundation\Http\FormRequest;

class CreateTimesheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trouble' => 'required|max:1000',
            'date' => 'required|date',
            'plan' => 'required',
        ];
    }
}
