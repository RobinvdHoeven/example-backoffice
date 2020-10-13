<!DOCTYPE html>
<html lang="en">
<head>
    <title>Backoffice - Robin Backoffice</title>
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link rel="stylesheet" href="/css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.js"></script>

    <script src="https://cdn.tiny.cloud/1/7qzgpca0jov43xn6nvriwypbwqk7gvbb7oeth2xikhpbhvu0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
</head>

<body>
<div class="maincontainer">
    <div class="nav">
        <div class="navtitle">
            <a href="/dashboard">Backoffice</a>
            <a href="#" class="toggle">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <div class="navmenu">
            @if(Auth::check())
                <ul class="navmenu">
                    <li><a href="/dashboard"><i class="fa fa-home"></i>Home / Dashboard<span
                                class="fa fa-chevron-right"></span></a></li>
                    <li><a href="/bestellingen/dagelijks"><i class="fa fa-shopping-cart"></i>Bestellingen per dag<span
                                class="fa fa-chevron-right"></span></a></li>
                    <li><a href="/bestellingen"><i class="fa fa-shopping-cart"></i>Bestellingen<span
                                class="fa fa-chevron-right"></span></a></li>
                    <li><a href="/producten"><i class="fa fa-tag" aria-hidden="true"></i>Producten<span
                                class="fa fa-chevron-right"></span></a></li>
                    <li><a href="/sliders"><i class="fa fa-picture-o" aria-hidden="true"></i>Sliders<span
                                class="fa fa-chevron-right"></span></a></li>
                    <li><a href="/vertalingen"><i class="fa fa-globe"></i>Vertalingen<span
                                class="fa fa-chevron-right"></span></a></li>
                    <li><a href="/gebruikers"><i class="fa fa-user"></i>Gebruikers beheren<span
                                class="fa fa-chevron-right"></span></a></li>
                </ul>
            @endif
        </div>

    </div>
    <div class="mainnavtop">
        <div class="navtop">
            <a href="#" class="toggle">
                <i class="fa fa-bars"></i>
            </a>
            <nav class="navbar-nav">
                <ul class="navbar-right">
                    <li class="nav-item">
                        @if (Auth::check())
                            <div class="dropdown">
                                <button class="dropbtn">{{Auth::user()->name}} <i class="fa fa-sort-desc"></i></button>
                                <div class="dropdown-content">
                                    <a href="{{ route('logout') }}">Uitloggen</a>
                                </div>
                            </div>
                        @else
                            <div class="dropdown">
                                <button class="dropbtn">Inloggen</button>
                            </div>
                        @endif
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="content" data-template="{{ $view_name ?? '' }}">
        @yield('content')
    </div>
</div>
<script>

    $(function () {
        var open = false;

        $(".toggle").click(function () {
            open = !open;
            if (open) {
                $(".nav").toggle();
                $(".mainnavtop").css({marginLeft: "0"});
                $(".content").css({marginLeft: "0"});
            } else {
                $(".nav").toggle();
                $(".mainnavtop").css({marginLeft: "230px"});
                $(".content").css({marginLeft: "230px"});
            }

        });
    });


</script>

</body>

</html>
