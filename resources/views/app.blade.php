<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <title>Total Processing</title>
     <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg">

    @if (Auth::check())
        <script>
            window.Laravel = {!!json_encode([
                'isLoggedin' => true,
                'user' => Auth::user()
            ])!!}
        </script>
    @else
        <script>
            window.Laravel = {!!json_encode([
                    'isLoggedin' => false
                ])!!}
        </script>
    @endif

    <div id="app">
    </div>

    @isset($decode)
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-header text-center"><h3>Summary</h3></div>
                        <div class="card-body">
                            <p><strong>Result code: </strong> {{ $decode['result']['code'] }} <p>
                            <p><strong>Description: </strong> {{ $decode['result']['description'] }} <p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
</body>
</html>