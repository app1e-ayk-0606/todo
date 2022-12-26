<?php

namespace App\Http\Requests\todo;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CreateRequest extends FormRequest
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
            'title'    => 'required|max:50',
            'content'  => 'required|max:255',
            'start_at' => 'required|date_format:Y-m-d',
            'end_at'   => 'required|date_format:Y-m-d',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title'    => 'タイトル',
            'content'  => 'タスク内容',
            'start_at' => '開始日',
            'end_at'   => '終了日'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required'    => ':attributeは必須です',
            'max'         => ':attributeは:max文字以下で入力してください',
            'date_format' => ':attributeの日付形式が正しくありません',
        ];
    }
}
