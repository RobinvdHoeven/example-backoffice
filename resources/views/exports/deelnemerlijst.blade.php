<table>
    <tr>
        <th>Voornaam</th>
        <th>Achternaam</th>
        <th>Telefoonnummer</th>
        <th>Postcode</th>
        <th>Plaats</th>
        <th>Straat</th>
        <th>Huisnr.</th>
        <th>Soort</th>
        <th>BBQ Box</th>
        <th>Picnic Geniet Box</th>
        <th>Borrel Box</th>
        <th>Sweet Geniet Box</th>
        <th>Saté Geniet Box</th>
        <th>Tekst</th>
    </tr>

    <?php
    $counter = [];
    ?>
    @foreach($deliveryaddress as $daddress)
        <?php
        foreach (['a', 'b', 'c', 'd', 'e'] as $letter) {
            if (!isset($counter['option_' . $letter])) {
                $counter['option_' . $letter] = 0;
            }

            if ($daddress->{'option_' . $letter} > 0) {
                $counter['option_' . $letter] += $daddress->{'option_' . $letter};
            }
        }
        ?>

        <tr>
            <td>{{$daddress->firstname}}</td>
            <td>{{$daddress->lastname}}</td>
            <td>{{$daddress->order->phone}}</td>
            <td>{{$daddress->postcode}}</td>
            <td>{{$daddress->city}}</td>
            <td>{{$daddress->street}}</td>
            <td>{{$daddress->housenr}}</td>
            @if($daddress->order->is_delivery == 1)
                <td>Bezorgen</td>
            @else
                <td>Afhalen</td>
            @endif
            <td>{{$daddress->option_a}}</td>
            <td>{{$daddress->option_b}}</td>
            <td>{{$daddress->option_c}}</td>
            <td>{{$daddress->option_d}}</td>
            <td>{{$daddress->option_e}}</td>
            <td>{{$daddress->text}}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="14">
            &nbsp;
        </td>
    </tr>

    <tr>
        <td colspan="7">&nbsp;</td>
        <td><strong>TOTAAL</strong></td>
        <td>{{ $counter['option_a'] }}</td>
        <td>{{ $counter['option_b'] }}</td>
        <td>{{ $counter['option_c'] }}</td>
        <td>{{ $counter['option_d'] }}</td>
        <td>{{ $counter['option_e'] }}</td>
        <td>&nbsp;</td>
    </tr>

</table>
