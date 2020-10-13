<?php

namespace App\Http\Controllers;

use App\Exports\participantsExportView;
use App\Models\Order;
use App\Models\ProductTemplate;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Analytics;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{

    public function index()
    {
        $orderby = request('orderby', 'created_at');
        $type = request('type', 'desc');
        $orders = Order::whereNotNull('created_at')->orderBy($orderby, $type)->take(15)->get();

        $analytics = DB::TABLE('analytics')->get();

        foreach ($analytics as $data) {
            $stats[$data->key] = $data->value;
        }

        try {
            $realtimeusers = Analytics::getAnalyticsService()->data_realtime->get('ga:' . env('ANALYTICS_VIEW_ID'), 'rt:activeVisitors')->totalsForAllResults['rt:activeVisitors'];
            if (empty($realtimeusers)) {
                throw new \Exception('error');
            }
        } catch (\Exception $e) {
            $realtimeusers = 0;
        }

        $step1 = DB::TABLE('orders')->whereNotNull('finished_at')->count();

        $startdate = DB::select('select cast(created_at as date) from orders order by created_at asc limit 1;');
        $enddate = DB::select('select cast(created_at as date) from orders order by created_at desc limit 1');

        if (count($startdate) > 0 && count($enddate) > 0) {
            $start = Carbon::parse(current($startdate[0]));
            $end = Carbon::parse(current($enddate[0]));
        } else {
            $start = $end = Carbon::now();
        }


        $period = CarbonPeriod::create($start, $end);
        $dates = $period->toArray();

        foreach ($dates as $date) {
            $alldays[] = $date->format('Y-m-d');
        }

        $graphdatastarted = DB::SELECT('select count(id) as count, cast(created_at as date) as `date` from orders where created_at is null group by cast(created_at as date)');
        $graphdatafinished = DB::SELECT('select count(id) as count, cast(created_at as date) as `date` from orders where created_at is not null group by cast(created_at as date)');

        $startedarray = [];
        foreach ($graphdatastarted as $new) {
            $startedarray[$new->date] = $new->count;
        }

        $valuestartedarray = [];
        foreach ($alldays as $data) {
            if (array_key_exists($data, $startedarray)) {
                $valuestartedarray[] = $startedarray[$data];
            } else {
                $valuestartedarray[] = 0;
            }
        }

        foreach ($graphdatafinished as $new) {
            $finishedarray[$new->date] = $new->count;
        }

        $valuearray = array();
        if (isset($finishedarray) && count($finishedarray) > 0) {


            foreach ($alldays as $data) {
                if (array_key_exists($data, $finishedarray)) {
                    $valuearray[] = $finishedarray[$data];
                } else {
                    $valuearray[] = 0;
                }
            }
        }


        $products = DB::select(DB::raw('SELECT product_templates.name_nl, count(orders_products.product_id) as aantal, product_templates.price
FROM product_templates
INNER JOIN products ON product_templates.id = products.product_template_id
INNER JOIN orders_products ON products.id = orders_products.product_id
INNER JOIN orders ON orders_products.order_id = orders.id 
where orders.finished_at is not NULL
group by product_templates.name_nl order by aantal desc;'));


        $countdays = DB::select(DB::raw('SELECT product_templates.name_nl, orders.date_start, orders.date_end
FROM product_templates
INNER JOIN products ON product_templates.id = products.product_template_id
INNER JOIN orders_products ON products.id = orders_products.product_id
INNER JOIN orders ON orders_products.order_id = orders.id 
where orders.finished_at is not NULL'));

        $listofproducts = ProductTemplate::select('name_nl')->get();
        foreach ($listofproducts as $product) {
            foreach ($countdays as $day) {
                if ($product->name_nl == $day->name_nl) {
                    $start = Carbon::parse($day->date_start);
                    $end = Carbon::parse($day->date_end);
                    $days = $start->diffInDays($end);
                    $daysarray[] = [
                        'product' => $day->name_nl,
                        'days' => $days,
                    ];
                }
            }
        }

        foreach ($products as $product) {
            $product->days = 0;
        }

        foreach ($products as $product) {
            foreach ($daysarray as $newdaysarray) {
                if ($product->name_nl == $newdaysarray['product']) {
                    $product->days += $newdaysarray['days'];
                }
            }
        }

        return view('pages.dashboard', compact('orders', 'type', 'step1', 'alldays', 'graphdatastarted', 'graphdatafinished', 'valuearray', 'valuestartedarray', 'stats', 'products', 'realtimeusers'));
    }

}
