<div>
    <x-admin::includes.alert-messages />

    <form wire:submit="submit">
        <div class="row g-2">
            <div class="col-lg-4">
                <div>
                    <label class="form-label">
                        Old Password
                        <span class="text-danger">*</span>
                    </label>
                    <x-admin::input.password
                        :name="'current_password'"
                        placeholder="Masukkan password saat ini"
                        required
                    />
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-4">
                <div>
                    <label class="form-label">
                        New Password
                        <span class="text-danger">*</span>
                    </label>
                    <x-admin::input.password :name="'password'" placeholder="Masukkan password baru" required />
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-4">
                <div>
                    <label class="form-label">
                        Confirm Password
                        <span class="text-danger">*</span>
                    </label>
                    <x-admin::input.password :name="'password_confirmation'" placeholder="Confirm password" required />
                </div>
            </div>
            <!--end col-->
            <div class="col-lg-12 border-top mt-3 pt-3">
                <div class="hstack gap-2 justify-content-end">
                    <button class="btn btn-primary btn-load" type="submit">
                        <span class="d-flex align-items-center">
                            <span class="flex-grow-1">Change Password</span>
                            <span
                                class="spinner-border flex-shrink-0 ms-2"
                                role="status"
                                wire:loading.delay
                                wire:target="submit"
                            ></span>
                        </span>
                    </button>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </form>
</div>
