<style>
    .select2 {
        width: 100% !important;
    }

    .select2-selection--single {
        height: 100% !important;
    }

    /*.select2-selection__rendered {*/
    /*    word-wrap: break-word !important;*/
    /*    text-overflow: inherit !important;*/
    /*    white-space: normal !important;*/
    /*}*/

    .select2-selection__clear {
        margin-right: 2.5rem !important;
        height: 38px !important;
    }

    /* input saat focus */
    .select2-container .select2-selection--single:focus {
        border: var(--vz-border-width) solid var(--vz-primary-border-subtle);
    }

    .select2-invalid {
        border: 1px solid var(--vz-form-invalid-color);
        border-radius: 5px;
    }

    /* item yg terpilih ketika dropdown */
    .select2-container--default .select2-results__option--selected {
        background-color: #e9ebec;
    }

    /* item yg di hover saat dropdown */
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #405189;
        color: #fff;
    }

    .select2-selection--multiple {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    h3.category {
        font-family: 'Bebas Neue';
        font-size: 20px;
        font-weight: bold;
        color: #d1d1d1;
        letter-spacing: 10px;
        margin: 0;
        padding: 0;
    }

    p.info-box {
        padding: 1.2em 2em;
        border: 1px solid #e91e63;
        border-left: 10px solid #e91e63;
        border-radius: 5px;
        margin: 1.5em;
    }
</style>
