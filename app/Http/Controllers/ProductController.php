<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTemplate;
use App\Models\ProductTemplateImages;
use App\Rules\CheckAnumRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class ProductController extends Controller
{
    public function createProductIndex()
    {
        return view('pages.addproduct');
    }

    public function createProduct(Request $request)
    {
        $this->rules = [
            'name_nl' => 'required',
            'name_de' => 'required',
            'name_fr' => 'required',
            'name_en' => 'required',
            'price' => 'required|numeric',
            'text_nl' => 'required',
            'text_de' => 'required',
            'text_fr' => 'required',
            'text_en' => 'required',
            'stock' => 'required|numeric',
            'croppedimage' => 'required',
        ];

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('id', $request->get('id'));
        }

        $filepath = $request->get('croppedimage');

        $product = new ProductTemplate();
        $product->name_nl = $request->get('name_nl');
        $product->name_de = $request->get('name_de');
        $product->name_fr = $request->get('name_fr');
        $product->name_en = $request->get('name_en');
        $product->price = $request->get('price');
        $product->text_nl = $request->get('text_nl');
        $product->text_de = $request->get('text_de');
        $product->text_fr = $request->get('text_fr');
        $product->text_en = $request->get('text_en');

        $product->slug_nl = preg_replace('/[[:space:]]+/', '-', $request->get('name_nl'));
        $product->slug_nl = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_nl));
        $product->slug_de = preg_replace('/[[:space:]]+/', '-', $request->get('name_de'));
        $product->slug_de = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_de));
        $product->slug_fr = preg_replace('/[[:space:]]+/', '-', $request->get('name_fr'));
        $product->slug_fr = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_fr));
        $product->slug_en = preg_replace('/[[:space:]]+/', '-', $request->get('name_en'));
        $product->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_en));
        $product->save();

        $productimage = new ProductTemplateImages();
        $productimage->product_template_id = $product->id;
        $productimage->image = $filepath;
        $productimage->save();

        $counter = 1;
        for ($x = 1; $x <= $request->get('stock'); $x++) {
            $stock = new Product();
            $stock->product_template_id = $product->id;
            $stock->name = $product->slug_nl . $counter++;
            $stock->save();
        }

        return redirect('/producten');
    }

    public function index()
    {

        $products = ProductTemplate::all();

        return view('pages.products', compact('products'));
    }

    public function showProduct($id)
    {

        $product = ProductTemplate::where('id', $id)->firstOrFail();
        $productimage = ProductTemplateImages::where('product_template_id', $id)->firstOrFail();

        return view('pages.product', compact('product', 'productimage'));
    }

    public function update(Request $request, $id)
    {
        $this->rules = [
            'name_nl' => 'required',
            'name_de' => 'required',
            'name_fr' => 'required',
            'name_en' => 'required',
            'price' => 'required|numeric',
            'text_nl' => 'required',
            'text_de' => 'required',
            'text_fr' => 'required',
            'text_en' => 'required',
        ];

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('id', $request->get('id'));
        }

        $product = ProductTemplate::where('id', $id)->firstOrFail();
        $product->name_nl = $request->get('name_nl');
        $product->name_de = $request->get('name_de');
        $product->name_fr = $request->get('name_fr');
        $product->name_en = $request->get('name_en');
        $product->price = preg_replace('/\,/', '.', $request->get('price'));
        $product->text_nl = $request->get('text_nl');
        $product->text_de = $request->get('text_de');
        $product->text_fr = $request->get('text_fr');
        $product->text_en = $request->get('text_en');

        $product->slug_nl = preg_replace('/[[:space:]]+/', '-', $request->get('name_nl'));
        $product->slug_nl = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_nl));
        $product->slug_de = preg_replace('/[[:space:]]+/', '-', $request->get('name_de'));
        $product->slug_de = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_de));
        $product->slug_fr = preg_replace('/[[:space:]]+/', '-', $request->get('name_fr'));
        $product->slug_fr = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_fr));
        $product->slug_en = preg_replace('/[[:space:]]+/', '-', $request->get('name_en'));
        $product->slug_en = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($product->slug_en));

        $product->save();

        if ($request->get('croppedimage') != null) {
            $productimage = ProductTemplateImages::where('product_template_id', $id)->first();
            $productimage->image = $request->get('croppedimage');
            $productimage->save();
        }

        return redirect('/producten');
    }

    public function delete($id)
    {

        ProductTemplate::where('id', $id)->delete();
        Product::where('product_template_id', $id)->delete();
        ProductTemplateImages::where('product_template_id', $id)->delete();

        return redirect('/producten');
    }

    public function getCroppedImage(Request $request)
    {

        $folderPath = public_path('images/');

        $image_parts = explode(";base64,", $request->get('imageData'));
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $filename = uniqid() . '.png';
        $file = $folderPath . $filename;
        file_put_contents($file, $image_base64);

        return response()->json(['imagepath' => $filename]);
    }

}
