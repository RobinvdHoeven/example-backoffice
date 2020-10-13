@extends ('layouts/app')

@section('content')
    <h1>{{ $product->name_nl }}</h1>
    <div style="font-size: 18px;">
        <b>Productnaam: </b> De naam van een product.<br>
        <b>Producttekst: </b> Elk product heeft een eigen pagina, dit is de tekst dat op die pagina komt en het product beschrijft.<br>
        <b>Productfoto: </b> Dit is de foto van het product.

    </div>
    <br><br>
    <form method="post" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        <table class="table table-striped table-hover" style="width:100%">
            <tbody>
            <tr>
                <td class="{{ $errors->has('name_nl') ? 'error' : '' }}">Productnaam (Nederlands)</td>
                <td><input type="text" name="name_nl" value="{{ old('name_nl', $product->name_nl) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('name_de') ? 'error' : '' }}">Productnaam (Duits)</td>
                <td><input type="text" name="name_de" value="{{ old('name_de', $product->name_de) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('name_fr') ? 'error' : '' }}">Productnaam (Frans)</td>
                <td><input type="text" name="name_fr" value="{{ old('name_fr', $product->name_fr) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('name_en') ? 'error' : '' }}">Productnaam (Engels)</td>
                <td><input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}"></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('price') ? 'error' : '' }}">Prijs</td>
                <td><input type="text" name="price" value="{{ old('price', $product->price) }}"></td>
            </tr>

            <tr>
                <td class="{{ $errors->has('text_nl') ? 'error' : '' }}">Producttekst (Nederlands)</td>
                <td><textarea id="text_nl" name="text_nl" rows="4" cols="50">{{ old('text_nl', $product->text_nl) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_de') ? 'error' : '' }}">Producttekst (Duits)</td>
                <td><textarea id="text_de" name="text_de" rows="4" cols="50">{{ old('text_de', $product->text_de) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_fr') ? 'error' : '' }}">Producttekst (Frans)</td>
                <td><textarea id="text_fr" name="text_fr" rows="4" cols="50">{{ old('text_fr', $product->text_fr) }}</textarea></td>
            </tr>
            <tr>
                <td class="{{ $errors->has('text_en') ? 'error' : '' }}">Producttekst (Engels)</td>
                <td><textarea id="text_en" name="text_en" rows="4" cols="50">{{ old('text_en', $product->text_en) }}</textarea></td>
            </tr>
            </tbody>
        </table>
        <br><br><br>

        @if(!empty($productimage))
            <img src="{{ ('/images/') . $productimage->image }}" alt="pic" width="325px" height="225px"><br>
            <a id="change">Foto aanpassen</a><br><br>
        @else
            <label for="file">Foto van product:</label><br>
            <h3>Hoe werkt het?</h3>
            <ul>
                <li>1. Klik op bestand kiezen en kies de gewenste foto.</li>
                <li>2. De blauwe lijn geeft aan welk stuk van de foto je selecteert, sleep de box naar de gewenste positie.</li>
                <li>3. Klik vervolgens op bijsnijden, je ziet nu de bijgesneden foto.</li>
                <li>4. Als je blij bent met de bijgesneden foto klik op "Update"</li>
                <li><b>Tip:</b> Als je de foto wilt in of uitzoomen, gebruik je scrollwheel op je muis.</li>
            </ul>
            <br>
            <input type="hidden" name="croppedimage" id="croppedimage">
            <input type="file" id="image" name="image" accept="image/*">
            <input style="width: 100px" type="button" id="btnCrop" value="Bijsnijden"/>
            <input style="width: 100px" type="button" id="btnRestore" value="Reset"/>
            <br><br>
            <div id="cropcontainer">
                <canvas id="canvas">

                </canvas>
            </div>

            <div id="result"></div>
        @endif
        <div id="hiddendiv">
            <label for="file">Foto van product:</label><br>
            <h3>Hoe werkt het?</h3>
            <ul>
                <li>1. Klik op bestand kiezen en kies de gewenste foto.</li>
                <li>2. De blauwe lijn geeft aan welk stuk van de foto je selecteert, sleep de box naar de gewenste positie.</li>
                <li>3. Klik vervolgens op bijsnijden, je ziet nu de bijgesneden foto.</li>
                <li>4. Als je blij bent met de bijgesneden foto klik op "Update"</li>
                <li><b>Tip:</b> Als je de foto wilt in of uitzoomen, gebruik je scrollwheel op je muis.</li>
            </ul>
            <br>
            <input type="hidden" name="croppedimage" id="croppedimage">
            <input type="file" id="image" name="image" accept="image/*">
            <input style="width: 100px" type="button" id="btnCrop" value="Bijsnijden"/>
            <input style="width: 100px" type="button" id="btnRestore" value="Reset"/>
            <br><br>
            <div id="cropcontainer">
                <canvas id="canvas">

                </canvas>
            </div>

            <div id="result"></div>
        </div>
        <br>
        <button type="submit" value="Update" class="postbtn" id="postform">Update</button>
    </form>
    <br>
    <form method="post" action="{{ route('product.delete', $product->id) }}" enctype="multipart/form-data">
        @csrf

        <button class="postbtn" type="submit" onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">Verwijder product
        </button>
    </form>
    <br>
    <script>
        var canvas = $("#canvas"),
            context = canvas.get(0).getContext("2d"),
            $result = $('#result');

        $('#image').on('change', function () {
                $('#result').html = "";
                $('#image').value = "";
                if (this.files && this.files[0]) {
                    if (this.files[0].type.match(/^image\//)) {
                        var reader = new FileReader();
                        reader.onload = function (evt) {
                            var img = new Image();
                            img.onload = function () {
                                context.canvas.height = 450;
                                context.canvas.width = 670;
                                context.drawImage(img, 0, 0, 670, 450);
                                canvas.cropper('destroy');
                                var cropper = canvas.cropper;
                                $result.empty();

                                canvas.cropper({
                                    width: 670,
                                    height: 450,
                                    maxWidth: 670,
                                    maxHeight: 450,
                                    autoCropArea: 0,
                                    strict: false,
                                    guides: false,
                                    highlight: false,
                                    dragCrop: false,
                                    scale: false,
                                    cropBoxMovable: true,
                                    cropBoxResizable: false,
                                    // data: {
                                    //     width: 477,
                                    //     height: 337,
                                    // },
                                });

                                $('#btnCrop').click(function () {
                                    // Get a string base 64 data url
                                    var croppedImageDataURL = canvas.cropper("getCroppedCanvas", {width: 670, height: 450}).toDataURL("image/png", 1);
                                    $result.html($('<h1>Bijgesneden foto:</h1><img>').attr('src', croppedImageDataURL));


                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        type: 'POST',
                                        url: '{{ route('getCroppedImage')}}',
                                        data: {
                                            imageData: croppedImageDataURL,
                                        },
                                        success: function (data) {
                                            console.log(data.imagepath);
                                            $('#croppedimage').val(data.imagepath);
                                        }
                                    });
                                });


                                $('#btnRestore').click(function () {
                                    canvas.cropper('reset');
                                    $('#croppedimage').val('');
                                    $result.empty();
                                });
                            };
                            img.src = evt.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        alert("Invalid file type! Please select an image file.");
                    }
                } else {
                    alert('No file(s) selected.');
                }
            }
        );
    </script>
    <script>
        $('#change').click(function () {
            $('#hiddendiv').toggle();

        });
    </script>
    <style>
        img {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }

        #result > img {
            width: 375px;
            height: 225px;
        }

        #cropcontainer {
            height: 450px;
            width: 650px;
        }

        #canvas {
            height: 450px;
            width: 650px;
            background-color: #ffffff;
            cursor: default;
            border: 1px solid black;
        }

        #hiddendiv {
            display: none;
        }
    </style>
@endsection
