<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class participantsExportView implements FromView
{
    private $deliveryaddress;

    public function __construct($deliveryaddress)
    {
        $this->deliveryaddress = $deliveryaddress;
    }

    public function view(): View
    {
        return view('exports.deelnemerlijst',
            [
                'deliveryaddress' => $this->deliveryaddress,
            ]);
    }
}
