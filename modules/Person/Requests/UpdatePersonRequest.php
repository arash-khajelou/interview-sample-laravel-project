<?php

namespace Modules\Person\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "is_active" => "required|boolean",
            "first_name" => "required|string|max:50",
            "last_name" => "required|string|max:50",
            "social_id" => "required|string|min:10|max:10",
            "birth_date" => "required|date|date_format:Y-m-d",
            "mobile_number" => "required|string|min:11|max:15",
            "mobile_description" => "required|string|max:100",
            "email" => "required|email",
            "email_description" => "required|string|max:100",
        ];
    }
}
