<div>
    @section('title', 'Reset Password')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mt-4">
                <div class="card-body p-4">
                    <div class="text-center mt-2">
                        <h5 class="text-primary">Create new password</h5>
                        <p class="text-muted">Your new password must be different from previous used password.</p>
                    </div>

                    <div class="p-2">
                        <x-admin::includes.alert-messages-auth />

                        <form wire:submit="process">
                            <div class="mb-3">
                                <label class="form-label" for="password-input">Password</label>
                                <div class="position-relative auth-pass-inputgroup">
                                    <x-admin::input.password
                                        :name="'password'"
                                        placeholder="Masukkan password"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="confirm-password-input">Confirm Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <x-admin::input.password
                                        :name="'password_confirmation'"
                                        placeholder="Confirm password"
                                        required
                                    />
                                </div>
                            </div>

                            <x-admin::input.checkbox :name="'remember'" :label="'Remember Me'" :value="$remember" />

                            <div class="mt-4">
                                <button class="btn btn-success w-100 btn-load" type="submit">
                                    <span class="d-flex align-items-center">
                                        <span class="flex-grow-1 me-2">Reset Password</span>
                                        <span
                                            class="spinner-border flex-shrink-0"
                                            role="status"
                                            wire:loading.delay
                                            wire:target="process"
                                        ></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="mt-4 text-center">
                <p class="mb-0">
                    Wait, I remember my password...
                    <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline">
                        Click here
                    </a>
                </p>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
