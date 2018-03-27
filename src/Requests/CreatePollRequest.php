<?php
namespace Hannoma\Larapolls\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CreatePollRequest extends FormRequest
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
            'topic'=>'required|max:255',
            'info' => 'max:255',
            'finishes_at' => 'nullable|date|after:today',
            'option1' => 'required|max:255',
            'option2' => 'required|max:255',
        ];
    }
}
