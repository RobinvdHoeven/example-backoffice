<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use DB;
use App\Mail\RentProduct;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class StatusController extends Controller
{
    public function pendingOrders()
    {

        $orders = Order::where('status', 1)->paginate(500);
        return view('pages.pendingorders', compact('orders'));
    }

    public function deny($id)
    {

        $order = Order::where('id', $id)->firstOrFail();
        $order->status = 0;
        $order->save();

        return redirect('/bestellingen');
    }

    public function accept($id)
    {

        $order = Order::where('id', $id)->firstOrFail();
        $order->status = 2;
        $order->save();

        $productsarray = DB::select(DB::raw('SELECT product_templates.name_nl, product_templates.name_fr, product_templates.name_en, product_templates.name_de, count(orders_products.product_id) as aantal, product_templates.price
FROM product_templates
INNER JOIN products ON product_templates.id = products.product_template_id
INNER JOIN orders_products ON products.id = orders_products.product_id
INNER JOIN orders ON orders_products.order_id = orders.id
where orders.finished_at is not NULL and orders.id = :id
group by product_templates.name_nl;'), ["id" => $order->id]);

        $totalprice = 0;
        foreach ($productsarray as $product) {
            $totalprice += ($product->price * $product->aantal);
        }

        $start = Carbon::parse($order->date_start);
        $end = Carbon::parse($order->date_end);
        $days = $start->diffInDays($end);

        $data = [
            'date_start' => $order->date_start->format('d-m-Y'),
            'date_end' => $order->date_end->format('d-m-Y'),
            'firstname' => $order->firstname,
            'lastname' => $order->lastname,
            'email' => $order->email,
            'phone' => $order->phone,
            'country' => $order->country,
            'zipcode' => $order->zipcode,
            'housenr' => $order->housenr,
            'street' => $order->street,
            'city' => $order->city,
            'comment' => $order->comment,
            'products' => $productsarray,
            'locale' => $order->locale,
            'days' => $days,
            'totalprice' => $totalprice,
        ];

        Mail::to($data['email'])->bcc('cowboysbcc@gmail.com')->send(new RentProduct($data));

        return redirect('/bestellingen');
    }
}
