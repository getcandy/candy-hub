<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GetCandy</title>

    @include('partials.head')

  </head>
  <body>
    <div class="wrap" id="app">
      <div class="sidebar">

        @include('partials.top-menu')

        <div class="side-purple-overlay"></div>

        @yield('side_menu')

        <hr>
        <nav class="side-nav">
          <ul>
              <li><a href="{{ route('logout') }}">Logout</a></li>
          </ul>
        </nav>

      </div>

      <div class="content-panel">

        <!-- Section Header -->
        <div class="section header">
          @yield('header_title')
          <div class="actions">
            @yield('header_actions')
          </div>
        </div>
        <!-- END Section Header -->

        <!-- Products -->
        <div class="section">
          @yield('content')
        </div>

      </div>
    </div>
    <div class="main-purple-overlay"></div>

    @include('partials.scripts')

  </body>
</html>