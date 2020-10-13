<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Guid;
use App\Traits\Encryptable;

class Order extends Model
{
    use Guid, Encryptable;

    protected $encryptable = [
        'firstname',
        'lastname',
        'postcode',
        'email',
        'phone',
        'comment',
        'zipcode',
        'street',
        'city',
        'housenr',
    ];

    protected $fillable = [
        'locale',
        'referer_id',
        'device_id',

        'company_name',
        'firstname',
        'lastname',
        'postcode',
        'housenr',
        'street',
        'city',
        'email',

        'optin',

        'ip_address',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_start',
        'date_end',
    ];

    public function cleanAttribute($value)
    {
        return strtoupper(trim(preg_replace('/\s+/', '', str_replace(' ', '', $value))));
    }

    public function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = trim($this->hashValue(trim(strtolower($this->cleanAttribute($value)))));
    }

    public function hashValue($value)
    {
        return hash('sha256', $value);
    }

    public function devices()
    {
        return $this->hasOne('App\Models\Device', 'id', 'device_id');
    }

    public function addresses()
    {
        return $this->hasMany('\App\Models\DeliveryAddress');
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment');
    }

    public function invoice()
    {
        return $this->hasOne('App\Models\Invoice')->latest();
    }

    public function isPaid()
    {
        $is_paid = false;
        foreach (Payment::where('order_id', $this->id)->get() as $payment) {
            if (!is_null($payment->paid_at)) {
                $is_paid = true;
            }
        }


        return (bool)$is_paid;
    }

    public function mealCount(): int
    {
        $total = 0;
        foreach ($this->addresses as $address) {
            $total += $address->mealCount();
        }

        return (int)$total;
    }

    public function wineCount(): int
    {
        $total = 0;
        foreach ($this->addresses as $address) {
            $total += $address->wineCount();
        }

        return (int)$total;
    }

    public function getPriceVat(): float
    {
        $total = 0;
        foreach ($this->addresses as $address) {
            $total += $address->getPriceVat();
        }

        return $total;
    }


    public function getPrice(): float
    {
        $total = 0;
        foreach ($this->addresses as $address) {
            $total += $address->getPrice();
        }

        return $total;
    }

    public function getBillingAddressForMollie()
    {
        return [
            'organizationName' => ($this->is_company ? $this->company_name : null),
            'streetAndNumber' => ($this->street . ' ' . $this->housenr),
            'city' => ($this->city),
            'postalCode' => ($this->postcode),
            'country' => 'NL',
            'title' => null,
            'givenName' => $this->firstname,
            'familyName' => $this->lastname,
            'email' => $this->email,
        ];
    }


    public function getLines(): array
    {

        $count_meal_a = 0;
        $count_meal_b = 0;
        $count_meal_c = 0;
        $count_meal_d = 0;

        $lines = [];

        foreach ($this->addresses as $address) {

            if ($address->mealCount() > 0) {

                if ($address->option_a > 0) {
                    $count_meal_a += $address->option_a;
                }

                if ($address->option_b > 0) {
                    $count_meal_b += $address->option_b;
                }

                if ($address->option_c > 0) {
                    $count_meal_c += $address->option_c;
                }
            }

            if ($address->wineCount() > 0) {

                if ($address->option_d > 0) {
                    $count_meal_d += $address->option_d;
                }

            }
        }

        if ($count_meal_a > 0) {
            $lines[] = [
                'type' => 'surcharge',
                'name' => 'A - Asperge Ham maaltijd',
                'quantity' => $count_meal_a,
                'vatRate' => 9,
                'unitPrice' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->mealPrice()), 2, '.', ''),
                ],
                'totalAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->mealPrice() * $count_meal_a), 2, '.', ''),
                ],
                'vatAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format((($address->mealPrice() * $count_meal_a) / 109 * 9), 2, '.', ''),
                ]
            ];
        }


        if ($count_meal_b > 0) {
            $lines[] = [
                'type' => 'surcharge',
                'name' => 'B - Asperge Zalm maaltijd',
                'quantity' => $count_meal_b,
                'vatRate' => 9,
                'unitPrice' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->mealPrice()), 2, '.', ''),
                ],
                'totalAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->mealPrice() * $count_meal_b), 2, '.', ''),
                ],
                'vatAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format((($address->mealPrice() * $count_meal_b) / 109 * 9), 2, '.', ''),
                ]
            ];
        }


        if ($count_meal_c > 0) {
            $lines[] = [
                'type' => 'surcharge',
                'name' => 'C - Asperge Portobello maaltijd',
                'quantity' => $count_meal_c,
                'vatRate' => 9,
                'unitPrice' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->mealPrice()), 2, '.', ''),
                ],
                'totalAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->mealPrice() * $count_meal_c), 2, '.', ''),
                ],
                'vatAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format((($address->mealPrice() * $count_meal_c) / 109 * 9), 2, '.', ''),
                ]
            ];
        }


        if ($count_meal_d > 0) {
            $lines[] = [
                'type' => 'surcharge',
                'name' => 'D - Apostelhoeve Cuvée XII',
                'quantity' => $count_meal_d,
                'vatRate' => 21,
                'unitPrice' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->winePrice()), 2, '.', ''),
                ],
                'totalAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format(($address->winePrice() * $count_meal_d), 2, '.', ''),
                ],
                'vatAmount' => [
                    'currency' => 'EUR',
                    'value' => number_format((($address->winePrice() * $count_meal_d) / 121 * 21), 2, '.', ''),
                ]
            ];
        }


        return $lines;

    }


    public function sendConfirmationMail()
    {
        //
    }


}
