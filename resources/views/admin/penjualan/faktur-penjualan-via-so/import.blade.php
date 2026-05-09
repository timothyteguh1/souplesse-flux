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
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                File
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.file
                                    :name="'file'"
                                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                    class="form-control"
                                />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Ekspedisi
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.select2
                                    :name="'ekspedisi_id'"
                                    :defer="false"
                                    :options="$this->optionsEkspedisiId"
                                    placeholder="- Pilih Ekspedisi -"
                                />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Gudang
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9" wire:key="gudang_id">
                                <x-admin::input.select2
                                    :name="'gudang_id'"
                                    :defer="false"
                                    :options="$this->optionsGudangId"
                                    placeholder="- Pilih Gudang -"
                                />
                            </div>
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-import-action />
                </div>
            </form>
        </div>
    </div>
</div>
