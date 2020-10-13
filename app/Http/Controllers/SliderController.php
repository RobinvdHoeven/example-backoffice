<?php

namespace App\Http\Controllers;

use App\Models\ProductTemplateImages;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class SliderController extends Controller
{
    public function index()
    {

        $sliders = Slider::all();

        return view('pages.sliders', compact('sliders'));
    }

    public function createSliderIndex()
    {

        return view('pages.addslider');
    }

    public function createSlider(Request $request)
    {
        $this->rules = [
            'title_nl' => 'required',
            'title_de' => 'required',
            'title_fr' => 'required',
            'title_en' => 'required',
            'text_nl' => 'required|max:50',
            'text_de' => 'required|max:50',
            'text_fr' => 'required|max:50',
            'text_en' => 'required|max:50',
            'link_nl' => 'required',
            'link_en' => 'required',
            'link_fr' => 'required',
            'link_de' => 'required',
            'croppedimage' => 'required',
        ];

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('id', $request->get('id'));
        }

        $filepath = Storage::disk('public')->put('', $request->image);

        $slider = new Slider();
        $slider->title_nl = $request->get('title_nl');
        $slider->title_de = $request->get('title_de');
        $slider->title_fr = $request->get('title_fr');
        $slider->title_en = $request->get('title_en');
        $slider->text_nl = $request->get('text_nl');
        $slider->text_de = $request->get('text_de');
        $slider->text_fr = $request->get('text_fr');
        $slider->text_en = $request->get('text_en');
        $slider->link_nl = $request->get('link_nl');
        $slider->link_en = $request->get('link_en');
        $slider->link_de = $request->get('link_de');
        $slider->link_fr = $request->get('link_fr');
        $slider->img = $request->get('croppedimage');
        $slider->save();

        return redirect('/sliders');
    }

    public function showSlider($id)
    {

        $slider = Slider::where('id', $id)->firstOrFail();

        return view('pages.slider', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $this->rules = [
            'title_nl' => 'required',
            'title_de' => 'required',
            'title_fr' => 'required',
            'title_en' => 'required',
            'text_nl' => 'required|max:60',
            'text_de' => 'required|max:60',
            'text_fr' => 'required|max:60',
            'text_en' => 'required|max:60',
            'link_nl' => 'required',
            'link_en' => 'required',
            'link_fr' => 'required',
            'link_de' => 'required',

        ];

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('id', $request->get('id'));
        }

        $slider = Slider::where('id', $id)->firstOrFail();
        $slider->title_nl = $request->get('title_nl');
        $slider->title_de = $request->get('title_de');
        $slider->title_fr = $request->get('title_fr');
        $slider->title_en = $request->get('title_en');
        $slider->text_nl = $request->get('text_nl');
        $slider->text_de = $request->get('text_de');
        $slider->text_fr = $request->get('text_fr');
        $slider->text_en = $request->get('text_en');
        $slider->link_nl = $request->get('link_nl');
        $slider->link_en = $request->get('link_en');
        $slider->link_de = $request->get('link_de');
        $slider->link_fr = $request->get('link_fr');
        if ($request->image != null) {
            $slider->img = $request->get('croppedimage');
        }
        $slider->save();

        return redirect('/sliders');
    }

    public function delete($id)
    {

        $slider = Slider::where('id', $id)->firstOrFail();
        $slider->delete();

        return redirect('/sliders');
    }
}
