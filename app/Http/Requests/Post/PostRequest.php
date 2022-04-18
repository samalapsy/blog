<?php

namespace App\Http\Requests\Post;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PostRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:10'],
            'slug' => ['required', 'string', 'min:15', 'unique:posts,slug'],
            'description' => ['required', 'string', 'min:5'],
            'publication_date' => ['required', 'string', 'min:5','date_format:Y-m-d H:i:s','after_or_equal:'.now()],
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'publication_date' => Carbon::create($this->publication_date)->toDateTimeString(),
        ]);
    }

    public function messages()
    {
        return [
            'slug.unique' => 'Title is already existing, please choose a different and unique title.',
        ];
    }
}
