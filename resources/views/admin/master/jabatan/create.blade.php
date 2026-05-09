<div>
    @section('title', $menuTitle)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $model::routeIndex() }}">{{ $menuTitle }}</a></li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form wire:submit="submitDefault">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="row mb-3"> --}}
                        {{-- <label class="col-lg-3 col-form-label">Kode</label> --}}
                        {{-- <div class="col-lg-9"> --}}
                        {{-- <x-admin::input.text :name="'kode'" placeholder="(OTOMATIS)" disabled /> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Nama Jabatan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'nama'" placeholder="Masukkan nama" />
                            </div>
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
