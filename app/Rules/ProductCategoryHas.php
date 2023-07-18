<?php

namespace App\Rules;

use App\Models\ProductCategory;
use Illuminate\Contracts\Validation\Rule;

class ProductCategoryHas implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private $flag)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $categoryId = request()->category_id;
        if (! $categoryId) {
            return false;
        }

        $category = ProductCategory::find($categoryId);
        if (! $category) {
            return false;
        }

        if (! ($category->flags & $category->{$this->flag})) {
            return true;
        }

        return isset($value) && ! empty($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Required';
    }
}
