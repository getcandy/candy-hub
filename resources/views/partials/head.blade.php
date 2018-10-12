
    <link rel="shortcut icon" type="image/png" href="/images/favicon.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

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