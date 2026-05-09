<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <style>
            body {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }

            table {
                width: 100%;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-row-group;
            }

            tr {
                page-break-inside: avoid;
            }

            table.bordered {
                border-collapse: collapse;
            }

            table.bordered th {
                border: 1px solid #777;
            }

            table.bordered td {
                border: 1px solid #777;
            }

            table.bordered th,
            table.bordered td {
                padding: 2px;
            }

            table.bordered th {
                border-right: 1px solid #777;
            }

            table.table-spaced tbody {
                margin: 8px 0;
            }

            .logo img {
                width: 150px;
                height: 150px;
            }

            .font-10 {
                font-size: 10px;
            }

            .font-11 {
                font-size: 11px;
            }

            .font-12 {
                font-size: 12px;
            }

            .font-13 {
                font-size: 13px;
            }

            .font-14 {
                font-size: 14px;
            }

            .font-16 {
                font-size: 16px;
            }

            .font-18 {
                font-size: 18px;
            }

            .font-20 {
                font-size: 20px;
            }

            .font-22 {
                font-size: 22px;
            }

            .font-24 {
                font-size: 24px;
            }

            .font-26 {
                font-size: 26px;
            }

            .font-28 {
                font-size: 28px;
            }

            .font-30 {
                font-size: 30px;
            }

            .font-32 {
                font-size: 32px;
            }

            .font-34 {
                font-size: 34px;
            }

            .font-36 {
                font-size: 36px;
            }

            .font-38 {
                font-size: 38px;
            }

            .font-40 {
                font-size: 40px;
            }

            .clear {
                clear: both;
            }

            .w-25 {
                width: 25px;
            }

            .w-100 {
                width: 100px;
            }

            .w-150 {
                width: 150px;
            }

            .w-300 {
                width: 300px;
            }

            .w-350 {
                width: 350px;
            }

            .w-auto {
                width: auto;
            }

            .mt-10 {
                margin-top: 10px;
            }

            .mt-20 {
                margin-top: 20px;
            }

            .mt-30 {
                margin-top: 30px;
            }

            .mt-40 {
                margin-top: 40px;
            }

            .mt-50 {
                margin-top: 50px;
            }

            .mb-10 {
                margin-bottom: 10px;
            }

            .mb-20 {
                margin-bottom: 20px;
            }

            .mb-30 {
                margin-bottom: 30px;
            }

            .mb-40 {
                margin-bottom: 40px;
            }

            .mb-50 {
                margin-bottom: 50px;
            }

            .ps-10 {
                padding-left: 10px;
            }

            .ps-15 {
                padding-left: 15px;
            }

            .ps-20 {
                padding-left: 20px;
            }

            .text-right {
                text-align: right;
            }

            .text-left {
                text-align: left;
            }

            .text-center {
                text-align: center;
            }

            .text-end {
                text-align: right;
            }

            .text-danger {
                color: rgba(240, 101, 72, 1) !important;
            }

            .bg-info-subtle {
                background-color: #dff0fa !important;
            }

            .fw-bold {
                font-weight: 700 !important;
            }

            td.one-third {
                width: 33%;
            }

            td.two-third {
                width: 66%;
            }

            table.valign-top > tbody > tr > td,
            table.valign-top > tbody > tr > th {
                vertical-align: top;
            }

            td.indent {
                padding-left: 25px !important;
            }

            th.indent {
                padding-left: 25px !important;
            }

            tr.border-btm td,
            tr.border-btm th {
                border-bottom: 1px solid #777;
            }

            tr.border-top td,
            tr.border-top th {
                border-top: 1px solid #777;
            }

            thead.bordered,
            tfoot.bordered,
            tr.bordered,
            tr.bordered th {
                border-bottom: 1px solid #777;
                border-top: 1px solid #777;
            }

            thead.bordered-top,
            tfoot.bordered-top,
            tr.bordered-top,
            tr.bordered-top th {
                border-top: 1px solid #000 !important;
            }

            thead.bordered-btm,
            tfoot.bordered-btm,
            tr.bordered-btm,
            tr.bordered-btm th {
                border-bottom: 1px solid #000 !important;
            }

            table.table-spaced tbody {
                margin: 8px 0;
            }

            table.border-collapse {
                border-collapse: collapse;
            }

            .underline {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        {{ $slot }}
    </body>
</html>
