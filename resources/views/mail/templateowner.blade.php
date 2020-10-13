<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Robin Backoffice</title>
    <style>
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;;
            font-size: 24px;
            line-height: 24px;
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
                line-height: 14px;
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
<body>
<div style="background-color: #69BD98; text-align: center;">
    <img src="{{ $message->embed('img/logo-robin-backoffice.png')}}" alt="">
</div>

<h3>Order geplaatst op: {{ $data['timeoforder'] }}</h3>
<br>
<table>
    <tr>
        <td><b>Startdatum:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['date_start'] }}</td>
    </tr>
    <tr>
        <td><b>Einddatum:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['date_end'] }}</td>
    </tr>
    <tr>
        <td><b>Dagen:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['days'] }}</td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><b>Taal:</b></td>
        <td style="width: 30px"></td>
        @if($data['locale'] == 'nl')
            <td> Nederlands</td>
        @elseif($data['locale'] == 'en')
            <td> Engels</td>
        @elseif($data['locale'] == 'de')
            <td> Duits</td>
        @elseif($data['locale'] == 'fr')
            <td> Frans</td>
        @endif
    </tr>
    <tr>
        <td><b>Land:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['country'] }}</td>
    </tr>
    <tr>
        <td><b>Voornaam:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['firstname'] }}</td>
    </tr>
    <tr>
        <td><b>Achternaam:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['lastname'] }}</td>
    </tr>
    <tr>
        <td><b>E-mailadres:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['email'] }}</td>
    </tr>
    <tr>
        <td><b>Telefoonnummer:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['phone'] }}</td>
    </tr>
    <tr>
        <td><b>Straat:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['street'] }}</td>
    </tr>
    <tr>
        <td><b>Huisnummer:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['housenr'] }}</td>
    </tr>
    <tr>
        <td><b>Postcode:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['zipcode'] }}</td>
    </tr>
    <tr>
        <td><b>Plaats:</b></td>
        <td style="width: 30px"></td>
        <td> {{ $data['city'] }}</td>
    </tr>
</table>
<br>
<b>Opmerking:</b><br>
@if(is_null($data['comment']))
    -----<br><br>
@else
    {{ $data['comment'] }}<br><br>
@endif
<br>

<table class="products">
    <tr>
        <th style="border-bottom: 1px solid lightgrey; border-top: 1px solid lightgrey; padding: 10px 0;">Product</th>
        <th style="border-bottom: 1px solid lightgrey; border-top: 1px solid lightgrey; padding: 10px 0;">Aantal</th>
        <th style="border-bottom: 1px solid lightgrey; border-top: 1px solid lightgrey; padding: 10px 0;">Prijs</th>
    </tr>
    @foreach($data['products'] as $product)
        @if($product['amount'] > 0)
            <tr>
                <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ucfirst($product['nlname'])}}</td>
                <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{ $product['amount'] }}</td>
                <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{$product['price'] }} CHF</td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"><b>Dagprijs:</b></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{$data['totalprice']}} CHF</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;"><b>Totaal:</b></td>
        <td style="border-bottom: 1px solid lightgrey; padding: 20px 0;">{{$data['totalprice'] * $data['days']}} CHF</td>
    </tr>
</table>
<br>
</body>
</html>
