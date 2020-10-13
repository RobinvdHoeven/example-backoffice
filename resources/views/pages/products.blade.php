@extends ('layouts/app')

@section('content')
    <h1>Producten: ({{ count($products) }})</h1>
    <h3>Klik op een product om het product aan te passen.</h3>
    <div class="participants fullwidth">
        <div style="overflow-x:scroll;">
            <div class="participant"><br>
                <a style="cursor: pointer;" href="{{ route('product.new') }}"><i class="fa fa-plus"></i> Maak nieuw product</a>
                <br><br>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Naam</th>
                        <th>Prijs</th>
                    </tr>
                    @foreach($products as $product)
                        <tr style="cursor: pointer;" onclick="location.href='{{ route('product.show', ['id' => $product->id]) }}'">
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name_nl }}</td>
                            <td>{{ $product->price }}</td>
                        </tr>
                    @endforeach

                </table>
            </div>

        </div>
    </div>
@endsection
