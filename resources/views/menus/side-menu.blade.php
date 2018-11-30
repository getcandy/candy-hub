
        @foreach ($navItems as $navItem)
          @if($navItem instanceof \GetCandy\Hub\Services\Navigation\NavBreak)
            <hr>
          @else
            <li @if($navItem->getCurrent()) class="active" @endif>
              <a href="{{ $navItem->getUrl() }}" title="Go to {{ $navItem->getTitle() }}">
                {{ $navItem->getTitle() }}
              </a>

              @if (count($navItem->getSubItems()) > 0)
              <div class="drop-menu">
                <ul>
                @foreach ($navItem->getSubItems() as $subItem)
                  <li><a href="{{ $subItem->getUrl() }}" title="Go to {{ $subItem->getTitle() }}">{{ $subItem->getTitle() }}</a></li>
                @endforeach
                </ul>
              </div>
              @endif
            </li>
          @endif
        @endforeach