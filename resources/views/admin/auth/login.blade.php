<div>
    @section('title', 'Sign In')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mt-4">
                <div class="card-body p-4">
                    <div class="p-2">
                        <x-admin::includes.alert-messages-auth />

                        <form wire:submit="process">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <x-admin::input.text :name="'username'" placeholder="Masukkan username" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password-input">Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <x-admin::input.password :name="'password'" placeholder="Masukkan password"
                                        required />
                                </div>
                            </div>

                            <div>
                                {{-- <div class="float-end">
                                    <a href="{{ route('admin.password.request') }}" class="text-muted">
                                        Forgot password?
                                    </a>
                                </div> --}}

                                <x-admin::input.checkbox :name="'remember'" :label="'Remember Me'" :value="$remember"
                                    :form-check-class="false" />
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary w-100 btn-load" type="submit">
                                    <span class="d-flex align-items-center">
                                        <span class="flex-grow-1 me-2">Sign In</span>
                                        <span class="spinner-border flex-shrink-0" role="status" wire:loading.delay
                                            wire:target="process"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>
    <!-- end row -->
</div>
