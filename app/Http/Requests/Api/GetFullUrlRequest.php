<?php

namespace App\Http\Requests\Api;

use App\Http\Traits\ValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class GetFullUrlRequest extends FormRequest
{
    use ValidationResponseTrait;
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
            'short_url' => 'required|exists:url_requests,shorten_url',
        ];
    }
}
