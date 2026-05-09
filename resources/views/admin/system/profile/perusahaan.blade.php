<div>
    <x-admin::includes.alert-messages />

    <form wire:submit="submit">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Nama
                                <span class="text-danger">*</span>
                            </label>
                            <x-admin::input.text :name="'nama'" placeholder="Masukkan nama" />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Alamat
                                <span class="text-danger">*</span>
                            </label>
                            <x-admin::input.textarea :name="'alamat'" placeholder="Masukkan alamat" />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Kota
                                <span class="text-danger">*</span>
                            </label>
                            <x-admin::input.text :name="'kota'" placeholder="Masukkan kota" />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Telp
                                <span class="text-danger">*</span>
                            </label>
                            <x-admin::input.text :name="'telp'" placeholder="Masukkan telp" />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Email
                                <span class="text-danger">*</span>
                            </label>
                            <x-admin::input.email :name="'email'" placeholder="Masukkan email" />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Plan
                                <span class="text-danger">*</span>
                            </label>
                            <x-admin::input.select2id
                                :id="'plan_id'"
                                :name="'plan_id'"
                                :defer="false"
                                :options="\App\Utilities\SelectHelpers\System\SH_Plan::active()"
                                placeholder="- Pilih Plan -"
                            />
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>

            <div class="col-lg-6 col-12">
                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <x-admin::input.file
                        :name="'logo'"
                        class="form-control"
                        placeholder="Masukkan logo"
                        accept="image/*"
                    />
                    <small class="text-muted">* Upload jika ingin mengganti logo</small>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <x-admin::buttons.app-update />
        </div>
    </form>
</div>
