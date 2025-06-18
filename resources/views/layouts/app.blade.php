<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  x-data
  class="dark"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="color-scheme" content="light dark" />
    <title>{{ config('app.name', 'Reflekt') }}</title>
    <!-- Favicon & Icons -->
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="{{ asset('favicon-32x32.png') }}"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="96x96"
      href="{{ asset('favicon-96x96.png') }}"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="192x192"
      href="{{ asset('android-chrome-192x192.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="{{ asset('apple-touch-icon.png') }}"
    />
    <link
      rel="icon"
      href="{{ asset('favicon.ico') }}"
      type="image/x-icon"
    />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    <!-- Vite Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
  </head>

  <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      @include('layouts.navigation')

      <!-- Page Heading -->
      @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
          </div>
        </header>
      @endisset

      <!-- Page Content -->
      <main>
        {{ $slot }}
      </main>
    </div>
    @stack('scripts')
  </body>
</html>
