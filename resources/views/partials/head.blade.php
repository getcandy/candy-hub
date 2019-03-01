
    <link rel="shortcut icon" type="image/png" href="{{ url('candy-hub/images/favicon.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <!-- GetCandy -->
    <link href="{{ url('candy-hub/css/hub.css') }}" rel="stylesheet">

    @if(File::exists(public_path('css/candy-hub.css')))
        <link href="{{ url('css/candy-hub.css') }}" rel="stylesheet">
    @endif

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
    window.Laravel = {
        "csrfToken": "{{ csrf_token() }}"
    };
    window.hubPrefix = "{{ config('getcandy.hub_prefix', 'hub') }}";
    </script>

    {!! $head_html !!}