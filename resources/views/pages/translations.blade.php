@extends ('layouts/app')

@section('content')
    <script type="text/javascript">

        function get_id(clicked_id) {
            $('#' + clicked_id + '').dblclick(function () {
                $('#' + clicked_id + '').css('display', 'none');

                tinymce.init({
                    selector: '#' + clicked_id + 'hidden',
                    force_br_newlines: false,
                    force_p_newlines: false,
                    forced_root_block: '',
                    plugins: 'table autoresize',

                    setup: function (editor) {

                        editor.on('blur', function (e) {
                            tinymce.remove('#' + clicked_id + 'hidden');
                            $('#' + clicked_id + '').css('display', 'inline-block');
                            var id = clicked_id.replace(/\D/g, '');

                            var text_nl = $('#text_nl' + id + 'hidden').val();
                            var text_de = $('#text_de' + id + 'hidden').val();
                            var text_fr = $('#text_fr' + id + 'hidden').val();
                            var text_en = $('#text_en' + id + 'hidden').val();

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },

                                type: 'POST',

                                url: '{{route('translation.update')}}',

                                data: {text_nl: text_nl, text_de: text_de, text_fr: text_fr, text_en: text_en, id: id},

                                success: function (data) {
                                    location.reload(true);
                                }
                            });
                        });
                    }
                });
            });
        }

    </script>
    <p>
        Het labeltje geeft aan waar een vertaling bij hoort.
        <br><br>
        <b>Hoe pas ik een vertaling aan?</b> <br>
        Dubbelklik op het tekstvak onder de taal dat je wilt aanpassen, het tekstvak wordt nu groter.<br>
        Na het invullen van de gewenste vertaling klik je vervolgens 1x naast het tekstvak, je ziet nu het tekstvak weer kleiner worden. <br>
        (De vertaling slaat automatisch op als je uit het tekstvak hebt geklikt)<br>
        <br>
        Wanneer er een vertaling mist laat de website het labeltje zien op de plek waar de vertaling normaal zou staan.<br>
        <br>

    </p>

    <br>

    <div class="participants fullwidth">
        <div class="participant">
            <form action="{{ route('category') }}" method="post">
                @csrf
                <label for="category">Kies een categorie:</label><br>
                <select name="category" id="category" style="height: 32px">

                    @foreach($categories as $category)
                        <option value="{{$category->category}}" {{ session('key') == "$category->category" ? 'selected' : '' }}>{{ucfirst($category->category)}}</option>
                    @endforeach
                </select>
                <button style="float: none; font-size: 16px; padding: 5px;" type="submit"><i class="fa fa-search"></i></button>

            </form>

            <br><br>
            @if($languagearray > 0)
                @foreach($languagearray as $translation)
                    <div class="tr extra" style="width: 25%; display: inline-block;"><b>{{ $translation['defaulttext'] }}</b></div>
                    <br>Nederlands<br>
                    <textarea id="text_nl{{$translation['id']}}" name="text_nl{{$translation['id']}}" onClick="get_id(this.id)" rows=3" cols="30"
                              readonly>{!!  old('text_nl',  str_replace('<br />', '&#13;&#10;', strip_tags($translation['text_nl'], '<br>'))) !!}</textarea>
                    <textarea id="text_nl{{$translation['id']}}hidden" name="text_nl{{$translation['id']}}hidden" style="visibility: hidden;" rows=2" cols="30">{{ $translation['text_nl']}}</textarea>
                    <br>Duits<br>
                    <textarea id="text_de{{$translation['id']}}" name="text_de{{$translation['id']}}" onClick="get_id(this.id)" rows=3" cols="30"
                              readonly>{!! old('text_de', str_replace('<br />', '&#13;&#10;', strip_tags($translation['text_de'], '<br>'))) !!}</textarea>
                    <textarea id="text_de{{$translation['id']}}hidden" name="text_de{{$translation['id']}}hidden" style="visibility: hidden;" rows=2" cols="30">{{ $translation['text_de']}}</textarea>
                    <br>Frans<br>
                    <textarea id="text_fr{{$translation['id']}}" name="text_fr{{$translation['id']}}" onClick="get_id(this.id)" rows=3" cols="30"
                              readonly>{!! old('text_fr', str_replace('<br />', '&#13;&#10;', strip_tags($translation['text_fr'], '<br>'))) !!}</textarea>
                    <textarea id="text_fr{{$translation['id']}}hidden" name="text_fr{{$translation['id']}}hidden" style="visibility: hidden;" rows=2" cols="30">{{ $translation['text_fr']}}</textarea>
                    <br>Engels<br>
                    <textarea id="text_en{{$translation['id']}}" name="text_en{{$translation['id']}}" onClick="get_id(this.id)" rows=3" cols="30"
                              readonly>{!! old('text_en', str_replace('<br />', '&#13;&#10;', strip_tags($translation['text_en'], '<br>'))) !!}</textarea>
                    <textarea id="text_en{{$translation['id']}}hidden" name="text_en{{$translation['id']}}hidden" style="visibility: hidden;" rows=2" cols="30">{{ $translation['text_en']}}</textarea>
                    <br><br>
                @endforeach
            @else
                <h1>Geen vertalingen gevonden.</h1>
            @endif
        </div>
    </div>

    <style>
        textarea {
            resize: none;
            height: auto;
            width: auto;
        }

        p {
            font-size: 16px;
        }

        .translationcontainer {
            height: 50px;
        }

        @media screen and (max-width: 500px) {

            .extra {
                width: 100% !important;
            }
        }
    </style>
@endsection
