<!-- Header -->
@include('layouts.header')

<!-- Navbar -->
@include('layouts.navbar')
<!-- /.navbar -->

<!-- Main Sidebar Container -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  @yield('content')
</div>
<!-- /.content-wrapper -->

@include('layouts.footer')