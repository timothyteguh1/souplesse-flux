<script>
    /**
     * Place any jQuery/helper plugins in here.
     */
    $(function() {
        Livewire.on('triggerDelete', (model) => {
            Swal.fire({
                html: '<div class="mt-3"><lord-icon src="{{ asset('assets/misc/lord-icon-delete.json') }}" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon><div class="mt-4 pt-2 fs-15 mx-5"><h4>Apakah anda yakin?</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin menghapus item ini?</p></div></div>',
                showCancelButton: true,
                buttonsStyling: false,
                showCloseButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2 mb-1',
                    cancelButton: 'btn btn-danger w-xs mb-1',
                },
            }).then((result) => {
                if (result.value) {
                    if (model.action) {
                        Livewire.dispatch(model.action, {
                            params: model,
                        });
                    } else {
                        Livewire.dispatch('delete', {
                            params: model,
                        });
                    }
                }
            });
        });

        Livewire.on('confirmation', ([model]) => {
            Swal.fire({
                html: '<div class="mt-4"><h4>' + model.message + '</h4></div>',
                showCancelButton: true,
                buttonsStyling: false,
                showCloseButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2 mb-1',
                    cancelButton: 'btn btn-danger w-xs mb-1',
                },
            }).then((result) => {
                if (result.value) {
                    console.log(model);
                    if (model.action) {
                        Livewire.dispatch(model.action, {
                            params: model,
                        });
                    }
                }
            });
        });

        Livewire.on('openNewTab', (link) => {
            window.open(link, '_blank');
        });

        window.addEventListener('page-to-top', (event) => {
            $('html,body').animate({
                    scrollTop: 0,
                },
                250,
            );
        });

        window.addEventListener('cetakPdf', (event) => {
            printJS({
                printable: event.detail.base64,
                type: 'pdf',
                base64: true,
            });
        });

        window.addEventListener('cetakRawHtml', (event) => {
            printJS({
                printable: event.detail.rawHtml,
                type: 'raw-html',
            });
        });
    });
</script>
