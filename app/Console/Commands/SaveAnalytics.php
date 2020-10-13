<?php

namespace App\Console\Commands;

use App\Models\AnalyticsData;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Analytics\Period;
use Analytics;
use DB;

class SaveAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saves analytics data to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        $startofday = Carbon::now()->startOfDay();
        $period = Period::create($startDate, $endDate);
        $period2 = Period::create($startofday, $endDate);
//        $this->info('hallo');

        $pageviews = Analytics::fetchMostVisitedPages($period);
        $sum = 0;
        foreach ($pageviews as $data) {
            $sum += $data['pageViews'];
        }
        $bounce = Analytics::performQuery($period, 'ga:bounceRate');
        $users = Analytics::performQuery($period, 'ga:users');
        $newusers = Analytics::performQuery($period2, 'ga:users');

        $data = [
            'bounce' => round(current($bounce->totalsForAllResults), 5),
            'users' => (int)current($users->totalsForAllResults),
            'newusers' => (int)current($newusers->totalsForAllResults),
            'pageviews' => $sum,
        ];

        foreach ($data as $key => $value) {
            $analytics = new AnalyticsData();
            $analytics->key = $key;
            $analytics->value = $value;
            $analytics->save();
        }
        DB::DELETE('delete from analytics WHERE created_at < date_sub(now(), interval 5 minute)');
    }
}

