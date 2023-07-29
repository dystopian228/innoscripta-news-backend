<?php

namespace App\Http\Requests\Preferences;

use App\Entities\ArticleDefinition;
use App\Entities\SourceDefinition;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePreferencesRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sources' => ['required', 'array'],
            'sources.*' => ['required', 'string', 'distinct', Rule::exists(SourceDefinition::TABLE_NAME, SourceDefinition::SYMBOL)],
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'string', 'distinct', Rule::exists(ArticleDefinition::TABLE_NAME, ArticleDefinition::CATEGORY)]
        ];
    }
}
