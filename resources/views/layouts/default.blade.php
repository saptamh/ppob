@include('includes.header')
@include('includes.sidebar')
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        @include('includes.topbar')
        @yield('content')
    </div>
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Rekakomindo.com 2000</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
</div>
<!-- Content Wrapper -->
@include('includes.footer')
