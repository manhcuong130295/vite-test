<!doctype html>
<html lang="en">

<head>
  <meta name="zalo-platform-site-verification" content="F_AV6CU22J8Nee8JtBKXCY23XWNCzLHQCJaq" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', "TOMOGPT")</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logo.png') }}" />
  @include('layouts.partials.style')
  @yield('links-css')
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
        @include('layouts.partials.sidebar.menu')
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      @include('layouts.partials.header')
      <!--  Header End -->
      <div class="container-fluid">
        <div class="container-fluid">
            @yield('content')
        </div>
      </div>
    </div>
  </div>
  @include('layouts.partials.footer')
  @yield('links-script')
  @yield('script')
  @stack('scripts')
</body>
</html>
