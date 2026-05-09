<x-admin::layouts.auth-base>
    @section('title', 'Coming Soon')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 pt-4 mb-4">
                    <div class="mb-sm-5 pb-sm-4 pb-5">
                        <img
                            src="{{ asset('assets/admin/images/comingsoon.png') }}"
                            alt=""
                            height="120"
                            class="move-animation"
                        />
                    </div>
                    <div class="mb-5">
                        <h1 class="display-2 coming-soon-text">Coming Soon</h1>
                    </div>
                    <div class="mt-n4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-success w-25">
                            <i class="mdi mdi-home me-1"></i>
                            Admin Area
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</x-admin::layouts.auth-base>
