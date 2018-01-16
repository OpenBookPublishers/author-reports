<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

    @if (env('MATOMO_URL'))
    <!-- Matomo -->
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
            var u="{{ env('MATOMO_URL') }}";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'),
                s=d.getElementsByTagName('script')[0]
            g.type='text/javascript'; g.async=true; g.defer=true;
            g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    @endif

@yield('head')
</head>
<body>
    <div id="app">
        @yield('app')
    </div>
</body>
</html>
