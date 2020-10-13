<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use DB;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Validator;

class TranslationController extends Controller
{

    public function index()
    {
        session(['key' => '']);
        $categories = Translation::select('category')->orderBy('category', 'asc')->distinct()->get();
        $translations = Translation::all();

        foreach ($translations as $languages) {
            $nl = ' NL ';
            $de = ' DE ';
            $fr = ' FR ';
            $en = ' EN ';

            if (!is_null($languages->text_nl)) {
                $nl = '';
            }
            if (!is_null($languages->text_de)) {
                $de = '';
            }
            if (!is_null($languages->text_fr)) {
                $fr = '';
            }
            if (!is_null($languages->text_en)) {
                $en = '';
            }

            $languagearray[] = [
                'id' => $languages->id,
                'defaulttext' => $languages->defaulttext,
                'text_nl' => $languages->text_nl,
                'text_de' => $languages->text_de,
                'text_fr' => $languages->text_fr,
                'text_en' => $languages->text_en,
                'created_at' => $languages->created_at,
                'updated_at' => $languages->updated_at,
                'nl' => $nl,
                'de' => $de,
                'fr' => $fr,
                'en' => $en,
            ];
        }


        return view('pages.translations', compact('categories', 'languagearray'));
    }

    public function show($id)
    {

        $translation = Translation::where('id', $id)->first();

        return view('pages.showtranslation', compact('translation'));
    }


    public function update(Request $request)
    {
        $data = $request->all();

        $translation = Translation::where('id', $data['id'])->first();

        if ($request->get('text_nl') == '') {
            $translation->text_nl = NULL;
        } else {
            $translation->text_nl = $data['text_nl'];
        }

        if ($request->get('text_de') == '') {
            $translation->text_de = NULL;
        } else {
            $translation->text_de = $data['text_de'];
        }

        if ($request->get('text_fr') == '') {
            $translation->text_fr = NULL;
        } else {
            $translation->text_fr = $data['text_fr'];
        }

        if ($request->get('text_en') == '') {
            $translation->text_en = NULL;
        } else {
            $translation->text_en = $data['text_en'];
        }

        $translation->save();

        return redirect('/vertalingen');
    }

    public function showCategorizedTranslations(Request $request)
    {

        $translations = Translation::where('category', $request->get('category'))->get();
        $categories = Translation::select('category')->distinct()->orderBy('category', 'asc')->get();

        $request->session()->put('key', $request->get('category'));
        session(['key' => $request->get('category')]);
        $value = $request->session()->get('key');

        foreach ($translations as $languages) {
            $nl = ' NL ';
            $de = ' DE ';
            $fr = ' FR ';
            $en = ' EN ';

            if (!is_null($languages->text_nl)) {
                $nl = '';
            }
            if (!is_null($languages->text_de)) {
                $de = '';
            }
            if (!is_null($languages->text_fr)) {
                $fr = '';
            }
            if (!is_null($languages->text_en)) {
                $en = '';
            }

            $languagearray[] = [
                'id' => $languages->id,
                'defaulttext' => $languages->defaulttext,
                'text_nl' => $languages->text_nl,
                'text_de' => $languages->text_de,
                'text_fr' => $languages->text_fr,
                'text_en' => $languages->text_en,
                'created_at' => $languages->created_at,
                'updated_at' => $languages->updated_at,
                'nl' => $nl,
                'de' => $de,
                'fr' => $fr,
                'en' => $en,
            ];
        }

        return view('pages.translations', compact('languagearray', 'translations', 'categories'));
    }

}
