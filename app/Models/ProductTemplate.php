<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    protected $table = 'product_templates';
    public $primaryKey = 'id';

    public function getLocalizedName()
    {

        if (is_null($this->{'name_' . app()->getLocale()})) {
            return $this->{'name_en'};
        }

        return $this->{'name_' . app()->getLocale()};
    }
}
