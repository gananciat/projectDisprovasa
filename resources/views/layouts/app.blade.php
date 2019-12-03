<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <v-app id="app">
    <base-component logo="https://randomuser.me/api/portraits/men/78.jpg" sistema="{{ config('app.name', 'Laravel') }}"></base-component>

    <v-content>
      <v-container style="height: 100%; width: 100%;">
        @yield('content')
      </v-container>
    </v-content>

    <v-footer
      dark
      color="#43a047"
      
    >
      <v-col
        class="text-center white--text"
        cols="12"
      >
        2019 — <strong>{{ config('app.name', 'Laravel') }}</strong>
      </v-col>
    </v-footer>
    
  </v-app>
</body>
</html>
