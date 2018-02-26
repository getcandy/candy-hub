<?php return array (
  'barryvdh/laravel-cors' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\Cors\\ServiceProvider',
    ),
  ),
  'barryvdh/laravel-dompdf' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\DomPDF\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
    ),
  ),
  'intervention/image' => 
  array (
    'providers' => 
    array (
      0 => 'Intervention\\Image\\ImageServiceProvider',
    ),
    'aliases' => 
    array (
      'Image' => 'Intervention\\Image\\Facades\\Image',
    ),
  ),
  'itsgoingd/clockwork' => 
  array (
    'providers' => 
    array (
      0 => 'Clockwork\\Support\\Laravel\\ClockworkServiceProvider',
    ),
    'aliases' => 
    array (
      'Clockwork' => 'Clockwork\\Support\\Laravel\\Facade',
    ),
  ),
  'laravel/passport' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Passport\\PassportServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'spatie/laravel-permission' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Permission\\PermissionServiceProvider',
    ),
  ),
  'tightenco/ziggy' => 
  array (
    'providers' => 
    array (
      0 => 'Tightenco\\Ziggy\\ZiggyServiceProvider',
    ),
  ),
  'vinkla/hashids' => 
  array (
    'providers' => 
    array (
      0 => 'Vinkla\\Hashids\\HashidsServiceProvider',
    ),
    'aliases' => 
    array (
      'Hashids' => 'Vinkla\\Hashids\\Facades\\Hashids',
    ),
  ),
  'vinkla/vimeo' => 
  array (
    'providers' => 
    array (
      0 => 'Vinkla\\Vimeo\\VimeoServiceProvider',
    ),
    'aliases' => 
    array (
      'Vimeo' => 'Vinkla\\Vimeo\\Facades\\Vimeo',
    ),
  ),
  'sentry/sentry-laravel' => 
  array (
    'providers' => 
    array (
      0 => 'Sentry\\SentryLaravel\\SentryLaravelServiceProvider',
    ),
    'aliases' => 
    array (
      'Sentry' => 'Sentry\\SentryLaravel\\SentryFacade',
    ),
  ),
);