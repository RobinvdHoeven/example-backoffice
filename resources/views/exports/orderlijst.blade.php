<table>
    <tr>
        <th>ID</th>
        <th>Begindatum</th>
        <th>Einddatum</th>
        <th>Taal</th>
        <th>Land</th>
        <th>Naam</th>
        <th>Telefoonnummer</th>
        <th>E-mailadres</th>
        <th>Postcode</th>
        <th>Huisnummer</th>
        <th>Straat</th>
        <th>Woonplaats</th>
        <th>Opmerking</th>
        <th>Bedrag</th>
    </tr>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->date_start->format('d-m-Y') }}</td>
            <td>{{$order->date_end->format('d-m-Y') }}</td>
            <td>{{$order->locale }}</td>
            <td>{{$order->country }}</td>
            <td>{{$order->firstname}} {{$order->lastname}}</td>
            <td>{{$order->phone}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->zipcode}}</td>
            <td>{{$order->housenr}}</td>
            <td>{{$order->street}}</td>
            <td>{{$order->city}}</td>
            <td>{{$order->comment}}</td>
            <td>{{$order->total}}</td>
        </tr>
    @endforeach
</table>

