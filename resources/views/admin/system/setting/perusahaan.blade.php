<div>
    @section('title', $menuTitle)

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-admin::includes.alert-messages />
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                {{ $menuTitle }}
                            </h6>
                        </div>
                        <form wire:submit="submit">
                            <div class="card-body p-4">
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
                                                    <x-admin::input.textarea
                                                        :name="'alamat'"
                                                        placeholder="Masukkan alamat"
                                                    />
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
                                                    <x-admin::input.email
                                                        :name="'email'"
                                                        placeholder="Masukkan email"
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
                                            <x-admin::input.filepond
                                                :name="'logo'"
                                                :uploaded-files="$uploaded_logo"
                                                :uploaded-files-name="'uploaded_logo'"
                                                allowImagePreview
                                                imagePreviewMaxHeight="200"
                                                allowFileTypeValidation
                                                acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg', 'image/gif']"
                                                allowFileSizeValidation
                                                maxFileSize="10mb"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <x-admin::buttons.app-update />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
