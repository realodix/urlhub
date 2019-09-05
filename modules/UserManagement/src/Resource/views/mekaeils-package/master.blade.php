@include('mekaeils-package.layouts.header')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('mekaeils-package.layouts.top-nav')
    <!-- partial -->

        <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
        @include('mekaeils-package.layouts.side-nav')

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
            @yield('breadcrumb')

            @include('mekaeils-package.layouts.alert')

            @yield('content')
        </div>
        <!-- partial:partials/_footer.html -->
        <footer class="footer" >
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-block">Laravel User Management Package By <a href="https://mekaeil.me/" target="_blank">Mekaeil Andisheh</a>.</span>
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Theme By <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap Dash</a>. All rights reserved.</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
            </div>
        </footer>
        <!-- partial -->
      </div>

    </div>
</div>

@include('mekaeils-package.layouts.footer')