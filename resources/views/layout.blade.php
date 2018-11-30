<!DOCTYPE html>
<html lang="{{ $app->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="channel" content="@channel()">

    <title>@if(isset($title)){{ $title }} -@endif GetCandy</title>
    @routes
    @include('hub::partials.head')

  </head>
  <body>
    <div class="wrap" id="app">
      <div class="sidebar">

        @include('hub::partials.top-menu')

        <div class="side-purple-overlay"></div>

                <div class="user-info">
          <small>Hello,</small>
          <strong>
            @if(Auth::user()->firstname)
              {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
            @else
              {{ Auth::user()->email }}
            @endif
          </strong>
        </div>

        <hr>

        @yield('side_menu')

        <hr>

        <nav class="side-nav">
          <ul>
              <li @if(request()->segment(2) == 'dashboard') class="active" @endif><a href="{{ route('hub.index') }}" >Dashboard</a></li>
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
      <candy-notification></candy-notification>
    </div>
    <div class="main-purple-overlay"></div>
    @include('hub::partials.scripts')
    @yield('scripts')
  </body>
</html>