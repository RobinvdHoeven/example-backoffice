<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Participant;

class ordersExportView implements FromView
{
    private $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('exports.orderlijst',
            [
                'orders' => $this->orders,
            ]);
    }
}
