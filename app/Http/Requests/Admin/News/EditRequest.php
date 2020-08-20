<?php


namespace App\Http\Requests\Admin\News;


use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'short_content' => 'required|string|max:65535',
            'content' => 'required|string|max:65535',
            'image' => 'nullable|file|image'
        ];
    }
}
