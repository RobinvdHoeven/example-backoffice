<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Robin Backoffice</title>
    <style>
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;;
            font-size: 24px;
            width: 600px;
        }

        div {
            width: 100%;
        }

        tr {
            width: 33%;
        }

        th {
            text-align: left;
        }

        table.products {
            width: 75%;
        }

        img {
            width: 70%;
            margin: 0 auto;
        }

        @media screen and (max-width: 1024px) {
            div {
                width: 100%;
            }

        }

        @media screen and (max-width: 600px) {
            body {
                font-size: 14px;
                width: 100%;
            }

            div {
                width: 100%;
            }

            img {
                width: 100%;
            }

            table.products {
                width: 100%;
            }
        }
    </style>
</head>
<?php App::setLocale($data['locale']); ?>
<body>
<div style="background-color: #69BD98; text-align: center;">
    <img src="{{ $message->embed('img/logo-robin-backoffice.png')}}" alt="">
</div>
<br>
@if(is_null($data['comment']))
    <?php $data['comment'] = '-----' ?>
@endif

<?= str_replace(['<startdatum>', '<einddatum>', '<voornaam>', '<achternaam>', '<email>', '<telefoonnummer>', '<land>', '<postcode>', '<huisnummer>', '<straat>', '<plaats>', '<opmerking>', '<locale>', '<dagen>', '<totaalprijs>'], [$data['date_start'], $data['date_end'], $data['firstname'], $data['lastname'], $data['email'], $data['phone'], $data['country'], $data['zipcode'], $data['housenr'], $data['street'], $data['city'], $data['comment'], $data['locale'], $data['days'], $data['totalprice']], __trans('Emailtemplate-geaccepteerd', 'Emailtemplate')) ?>

{{--<table>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Startdatum', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['date_start'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Einddatum', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['date_end'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Dagen', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['days'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><br></td>--}}
{{--        <td><br></td>--}}
{{--        <td><br></td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Voornaam', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['firstname'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Achternaam', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['lastname'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('E-mailadres', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['email'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Telefoonnummer', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['phone'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Straat', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['street'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Huisnummer', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['housenr'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Postcode', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['zipcode'] }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><b><?= __trans('Plaats', 'formulier') ?>:</b></td>--}}
{{--        <td style="width: 30px"></td>--}}
{{--        <td> {{ $data['city'] }}</td>--}}
{{--    </tr>--}}
{{--</table>--}}
{{--<br>--}}
{{--<b><?= __trans('Opmerking', 'formulier') ?>:</b><br>--}}
{{--@if(is_null($data['comment']))--}}
{{--    -----<br><br>--}}
{{--@else--}}
{{--    {{ $data['comment'] }}<br><br>--}}
{{--@endif--}}
{{--<br>--}}
<br><br>
<table class="products">
    <tr>
        <th style="border-bottom: 1px solid lightgrey; border-top: 1px solid lightgrey; padding: 10px 0;"><?= __trans('Product', 'product') ?></th>
        <th style="border-bottom: 1px solid lightgrey; border-top: 1px solid lightgrey; padding: 10px 0;"><?= __trans('Aantal', 'product') ?></th>
        <th style="border-bottom: 1px solid lightgrey; border-top: 1px solid lightgrey; padding: 10px 0;"><?= __trans('Prijs', 'product') ?></th>
    </tr>
    @foreach($data['products'] as $product)
        @if($product->aantal > 0)
            <tr>
                @if($data['locale'] == 'nl')
                    <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ucfirst($product->name_nl)}}</td>
                @elseif($data['locale'] == 'fr')
                    <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ucfirst($product->name_fr)}}</td>
                @elseif($data['locale'] == 'de')
                    <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ucfirst($product->name_de)}}</td>
                @else
                    <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ucfirst($product->name_en)}}</td>
                @endif
                <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ $product->aantal }}</td>
                <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{$product->price }} CHF</td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"><b><?= __trans('Dagprijs', 'product') ?>:</b></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{$data['totalprice']}} CHF</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"><b><?= __trans('Totaal', 'product') ?>:</b></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{$data['totalprice'] * $data['days']}} CHF</td>
    </tr>
</table>
<br>
</body>
</html>
