<div>
    <x-admin::includes.alert-messages />

    <form wire:submit="submit">
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">
                        Nama
                        <span class="text-danger">*</span>
                    </label>
                    <x-admin::input.text :name="'name'" placeholder="Masukkan nama" required />
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">
                        Username
                        <span class="text-danger">*</span>
                    </label>
                    <x-admin::input.text :name="'username'" placeholder="Masukkan username" required />
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <x-admin::input.email :name="'email'" placeholder="Masukkan email" />
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-12 border-top pt-3">
                <div class="hstack gap-2 justify-content-end">
                    <x-admin::buttons.app-update />
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </form>
</div>
