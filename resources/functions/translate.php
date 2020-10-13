<?php

use App\Models\Translation;
use Illuminate\Support\Facades\DB;

function __trans($value, $category)
{

    $existingtext = Translation::where('defaulttext', $value)->first();

    if (is_null($existingtext)) {
        $row = new Translation();
        $row->defaulttext = $value;
        $row->category = $category;
        $row->save();
        $returnvalue = $value;
    } else {
        if (is_null($existingtext->{'text_' . app()->getLocale()})) {
            $returnvalue = $value;
        } else {
            $returnvalue = $existingtext->{'text_' . app()->getLocale()};
        }
    }

    return $returnvalue;
}

?>
