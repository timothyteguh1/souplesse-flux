<div>
    @section('title', 'Forgot Password')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card mt-4">
                <div class="card-body p-4">
                    <div class="text-center mt-2">
                        <h5 class="text-primary">Forgot Password?</h5>
                        <p class="text-muted">Reset your password here</p>

                        <lord-icon
                            src="https://cdn.lordicon.com/rhvddzym.json"
                            trigger="loop"
                            colors="primary:#0ab39c"
                            class="avatar-xl"
                        ></lord-icon>
                    </div>

                    <div class="alert border-0 alert-warning text-center mb-2 mx-2" role="alert">
                        Masukkan email anda dan instruksi akan dikirimkan kepada anda!
                    </div>
                    <div class="p-2">
                        <x-admin::includes.alert-messages-auth />

                        <form wire:submit="process">
                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <x-admin::input.email :name="'email'" placeholder="Masukkan email" required />
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-success w-100 btn-load" type="submit">
                                    <span class="d-flex align-items-center">
                                        <span class="flex-grow-1 me-2">Send Reset Link</span>
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
                        <!-- end form -->
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
