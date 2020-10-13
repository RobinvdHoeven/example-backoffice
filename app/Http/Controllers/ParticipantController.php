<?php

namespace App\Http\Controllers;

use App\Exports\participantsExportView;
use App\Exports\ordersExportView;
use App\Models\Country;
use App\Models\DeliveryAddress;
use App\Models\ProductTemplate;
use App\Models\ProductTemplateImages;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Rules\CheckAnumRule;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use DB;
use App\Models\Invoice;
use Carbon\CarbonPeriod;
use Faker\Provider\Image;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends Controller
{

    private $options;

    public function __construct()
    {
        $this->options = config('cowboys.options');
    }

    public function confirmedOrders(Request $request)
    {
        $orderby = request('orderby', 'created_at');
        $type = request('type', 'desc');
        $day = Carbon::now();
        $orders = Order::whereNotNull('finished_at')->where('status', '2')->orderBy($orderby, $type)->paginate(500);


        return view('pages.confirmed', compact('orders', 'type', 'day'));
    }

    public function deniedOrders(Request $request)
    {
        $orderby = request('orderby', 'created_at');
        $type = request('type', 'desc');
        $day = Carbon::now();
        $orders = Order::whereNotNull('finished_at')->where('status', '0')->orderBy($orderby, $type)->paginate(500);


        return view('pages.denied', compact('orders', 'type', 'day'));
    }

    public function downloaddata(Request $request)
    {
        $orderby = request('orderby', 'finished_at');
        $type = request('type', 'desc');
        $orders = Order::whereNotNull('finished_at')->orderBy($orderby, $type)->paginate(500);


        return view('exports.deelnemerlijst', compact('orders', 'type'));
    }

    public function searchorders(Request $request)
    {
        $request->flash();

        $searchString = hash('sha256', trim(strtolower(preg_replace('/\s+/', '', $request->get('search')))));
        $unhashedSearchString = $request->get('search');

        try {
            $new = Carbon::parse($unhashedSearchString);
        } catch (\Exception $e) {
            $new = null;
        }

        $orderby = request('orderby', 'created_at');
        $type = request('type', 'desc');
        $orders = Order::whereNotNull('created_at')
            ->where(function ($query) use ($new, $searchString) {
                $query->where('searchstring', 'LIKE', '%' . $searchString . '%')->where('status', 2);
            })->orWhere(function ($query) use ($unhashedSearchString) {
                $query->where('id', $unhashedSearchString)->where('status', 2);
            })
            ->orderBy($orderby, $type)->paginate(500);

        return view('pages.confirmed', compact('orders', 'type'));
    }

    public function searchdeniedorders(Request $request)
    {
        $request->flash();

        $searchString = hash('sha256', trim(strtolower(preg_replace('/\s+/', '', $request->get('search')))));
        $unhashedSearchString = $request->get('search');

        try {
            $new = Carbon::parse($unhashedSearchString);
        } catch (\Exception $e) {
            $new = null;
        }
//        $new = $new->format('Y-m-d');
        $orderby = request('orderby', 'created_at');
        $type = request('type', 'desc');
        $orders = Order::whereNotNull('created_at')
            ->where(function ($query) use ($new, $searchString) {
                $query->where('searchstring', 'LIKE', '%' . $searchString . '%')->where('status', 0);
            })->orWhere(function ($query) use ($unhashedSearchString) {
                $query->where('id', $unhashedSearchString)->where('status', 0);
            })
            ->orderBy($orderby, $type)->paginate(500);
//        dd($orders);

//
//            })->orderBy($orderby, $type)->paginate(100);
//            ->orWhere('delivery_date', $new)->orWhere('finished_at', 'LIKE', $new . '%')->orderBy($orderby, $type)->paginate(100);
        return view('pages.denied', compact('orders', 'type'));
    }

    public function show($id)
    {
        $order = Order::where('id', $id)->firstOrFail();

        $startdate = Carbon::parse($order->date_start);
        $enddate = Carbon::parse($order->date_end);
        $days = $startdate->diffInDays($enddate);

        $products = DB::select(DB::raw('select 
	product_templates.name_nl,
	count(products.product_template_id ) as amount, 
	product_templates.price
from 
	products 
	inner join product_templates on product_templates.id = products.product_template_id
where
	products.id in 
		(select 
			product_id 
		from 
			orders_products 
		where 
			order_id = :id) 
group by 
	products.product_template_id;'), ['id' => $id]);

        $countries = Country::all();
        return view('pages.deelnemer', compact('order', 'products', 'days', 'countries'));
    }

    public function update(Request $request, $id)
    {

        $this->rules = [
            'firstname' => ['required', 'max:40', new CheckAnumRule()],
            'lastname' => ['required', 'max:40', new CheckAnumRule()],
            'phone' => ['required'],
            'email' => ['required', 'email', 'max:100'],
            'street' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'housenr' => 'required',
            'country' => 'required',
        ];

        $validator = Validator::make($request->all(), $this->rules);

        // Return with post data if validation is false
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('id', $request->get('id'));
        }

        $order = Order::where('id', $id)->firstOrFail();
        $order->firstname = $request->input('firstname');
        $order->lastname = $request->input('lastname');
        $order->phone = $request->input('phone');
        $order->email = $request->input('email');
        $order->comment = $request->input('comment');
        $order->zipcode = $request->input('zipcode');
        $order->street = $request->input('street');
        $order->city = $request->input('city');
        $order->housenr = $request->input('housenr');
        $order->country = $request->input('country');
        $order->save();

        return redirect('/bestellingen');
    }

    public function exportview()
    {
        $day = request()->get('date');
        $day = Carbon::parse($day);
        $excelname = 'orders' . $day . '.xlsx';
        $deliveryaddress = DeliveryAddress::WhereRAW('delivery_date = ? and order_id in (select id from orders where finished_at is not null)', $day->toDateString())->get();
        return Excel::download(new participantsExportView($deliveryaddress), $excelname);
    }

    public function exportsearch(Request $request)
    {
        $request->flash();

        $searchString = ($request->get('search'));
        $excelname = 'orders' . $searchString . '.xlsx';
        $orderby = request('orderby', 'finished_at');
        $type = request('type', 'desc');
        $deliveryaddress = DeliveryAddress::WhereRAW('delivery_date = ? and order_id in (select id from orders where finished_at is not null)', $searchString)->get();
        return Excel::download(new participantsExportView($deliveryaddress), $excelname);
    }

    public function exportvieworders()
    {
        $day = Carbon::now()->toDateString();
        $excelname = 'allorders.xlsx';
        $orders = Order::whereNotNull('finished_at')->where('status', '2')->orderBy('finished_at', 'desc')->get();
        return Excel::download(new ordersExportView($orders), $excelname);
    }

    public function exportdeniedorders()
    {
        $day = Carbon::now()->toDateString();
        $excelname = 'allorders.xlsx';
        $orders = Order::whereNotNull('finished_at')->where('status', 0)->orderBy('finished_at', 'desc')->get();
        return Excel::download(new ordersExportView($orders), $excelname);
    }

    public function exportsearchorders(Request $request)
    {
        $request->flash();
        $searchString = ($request->get('search'));
        $hashSearchString = '';
        $hashSearchString = hash('sha256', trim(strtolower(preg_replace('/\s+/', '', $searchString))));

//        $unhashedSearchString = $request->get('search');
//        $new = Carbon::parse($unhashedSearchString)->format('Y-m-d');
        $orderby = request('orderby', 'created_at');
        $type = request('type', 'desc');
        $orders = Order::where('searchstring', 'LIKE', '%' . $hashSearchString . '%')->orderBy($orderby, $type)->get();
        return Excel::download(new ordersExportView($orders), 'ordersfiltered.xlsx');
    }

    public function ordersSpecific()
    {

        $today = Carbon::now()->format('Y-m-d');
        $orders = Order::where('date_start', $today)->whereNotNull('finished_at')->get();

        return view('pages.bestellingenperdag', compact('orders'));
    }

    public function ordersSpecificSearch(Request $request)
    {

        $day = $request->get('date_start');

        $orders = Order::where('date_start', $day)->whereNotNull('finished_at')->get();

        return view('pages.bestellingenperdag', compact('orders'));
    }

}
