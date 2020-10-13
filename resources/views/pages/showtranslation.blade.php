@extends ('layouts/app')

@section('content')

    <div class="participants fullwidth">
        <div class="participant">

            <form method="post" action="{{ route('translation.updatespecific', ['id' => $translation->id]) }}" enctype="multipart/form-data">
                @csrf
                Label vertaling: <b>{{ $translation->defaulttext }}</b><br><br>
                <label for="text_nl">Vertaling NL</label><br>
                <textarea id="text_nl" name="text_nl">{{ old('text_nl', $translation->text_nl) }}</textarea>
                <br><br>

                <label for="text_de">Vertaling DE</label><br>
                <textarea id="text_de" name="text_de" rows="4" cols="50">{{ old('text_de', $translation->text_de) }}</textarea><br><br>

                <label for="text_fr">Vertaling FR</label><br>
                <textarea id="text_fr" name="text_fr" rows="4" cols="50">{{ old('text_fr', $translation->text_fr) }}</textarea><br><br>

                <label for="text_en">Vertaling EN</label><br>
                <textarea id="text_en" name="text_en" rows="4" cols="50">{{ old('text_en', $translation->text_en) }}</textarea><br><br>
                <br>
                <input type="submit" value="Update" class="postbtn" style="width: 200px">
            </form>
        </div>
    </div>
@endsection
