@extends ('layouts/app')

@section('content')

    <div class="statscontainer">
        <div class="row">
            <div class="stats">
                <span class="statstitle">Aantal bezoekers</span>
                <div class="count">{{number_format($stats['users'], 0, 0, '.')}}</div>
            </div>
            <div class="stats">
                <span class="statstitle">Aantal bezoekers vandaag</span>
                <div class="count">{{number_format($stats['newusers'], 0, 0, '.')}}</div>
            </div>
            <div class="stats">
                <span class="statstitle">Paginaweergaven</span>
                <div class="count">{{number_format($stats['pageviews'], 0, 0, '.')}}</div>
            </div>
            <div class="stats">
                <span class="statstitle">Bouncepercentage</span>
                <div class="count">{{round($stats['bounce'], 1). '%'}}</div>
            </div>
            <div class="stats">
                <span class="statstitle">Aantal orders</span>
                <div class="count">{{number_format($step1, 0, 0, '.')}}</div>
            </div>
            <div class="stats">
                <span class="statstitle">Realtime gebruikers</span>
                <div class="count">{{number_format($realtimeusers, 0, 0, '.')}}</div>
            </div>
        </div>
        <br><br>
        @if(count($orders) > 0)
            <div class="chart">
                <canvas id="line-chart"></canvas>
            </div>
            <script>
                new Chart(document.getElementById("line-chart"), {
                    type: 'line',
                    data: {
                        labels:
                            [
                                @foreach($alldays as $days)
                                    "{{$days}}",
                                @endforeach
                            ],

                        datasets: [{
                            data: [
                                @foreach($valuearray as $data)
                                {{$data}},
                                @endforeach
                            ],
                            label: "Orders geplaatst per dag",
                            borderColor: "#8e5ea2",
                            fill: false
                        }
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Orders geplaatst'
                        },

                    }
                });
            </script>

            <br>
            <div class="participants">
                <h1>Financieel overzicht:</h1>
                <div class="participant" style="overflow-x: scroll">
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Aantal</th>
                            <th>Dagen</th>
                            <th>Prijs per dag</th>
                            <th>Totaal</th>
                        </tr>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name_nl }}</td>
                                <td>{{ $product->aantal }}</td>
                                <td>{{ $product->days }}</td>
                                <td>{{ $product->price }} CHF</td>
                                <td>{{ ($product->price * $product->days) }} CHF</td>
                            </tr>
                        @endforeach
                        <?php $total = 0;

                        foreach ($products as $product) {
                            $total += ($product->price * $product->days);
                        }
                        ?>
                        @if($total == 0)
                            <tr>
                                <td>Er zijn nog geen orders geplaatst.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Totaal</b></td>
                                <td><b>{{ $total }} CHF</b></td>
                            </tr>
                        @endif
                    </table>
                    <br>
                </div>
            </div>
        @endif
    </div>
@endsection
