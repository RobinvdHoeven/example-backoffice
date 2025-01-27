<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckAnumRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match('/^[-_áâæàåãäçéêèëíîìïñóôòøõöúûûüýÿÁÂÀÅÃÄÇÉÊÈËÍÎÌÏÑÓÔÒØÕÖÚÛÛÜÝŸA-Za-z0-9.,&]+$/m', str_replace(array(' ', '.', ',', '-', "'"), '', $value))) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.anum');
    }
}
