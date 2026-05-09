<div>
    @section('title', 'Ubah ' . $obj->nama)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteShow() }}">{{ $obj->kode }}</a></li>
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
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Nama
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'nama'" placeholder="Masukkan nama" />
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-lg-3 col-form-label">
                                Status
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.select2
                                    :name="'status'"
                                    :options="\App\Utilities\SelectHelpers\System\SH_Status::common()"
                                    :placeholder="'- Pilih Status -'"
                                />
                            </div>
                        </div>
                    </div>

                    <x-admin::includes.pages.edit-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
