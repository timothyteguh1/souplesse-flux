@props(['title' => ''])
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{ $title }}</title>
        <style>
            html {
                height: 0;
            }

            body {
                font-size: 12px;
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
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

            .page {
                page-break-after: always;
                page-break-inside: avoid;
            }

            .page-break {
                page-break-after: always;
            }

            table {
                width: 100%;
            }

            table th {
                text-align: left;
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

            .break-all {
                word-break: break-all;
            }

            .separator {
                margin-top: 25px;
            }

            .separator2 {
                margin-top: 50px;
            }

            .logo img {
                width: 150px;
                height: 150px;
            }

            .yellow {
                background-color: #f7f6a7 !important;
            }

            .blue {
                background-color: #bfedf7 !important;
            }

            .grey {
                background-color: #aeaeae !important;
            }

            .grey-light {
                background-color: #dedede !important;
            }

            .green {
                background-color: #99ffcc !important;
            }

            .light {
                background-color: #e6ffe6 !important;
            }

            .dark {
                background-color: #98e698 !important;
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

            #header {
                position: fixed;
            }

            .center {
                text-align: center;
            }

            .text-center {
                text-align: center;
            }

            .text-uppercase {
                text-transform: uppercase;
            }

            #footer {
                position: fixed;
                left: 0;
                bottom: 0;
                right: 0;
                height: 150px;
                background-color: lightblue;
            }

            #footer .page:after {
                content: counter(page, upper-roman);
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

            .ps-20 {
                padding-left: 20px;
            }

            .ps-30 {
                padding-left: 30px;
            }

            .ps-40 {
                padding-left: 40px;
            }

            .ps-50 {
                padding-left: 50px;
            }

            .pb-10 {
                padding-bottom: 10px;
            }

            .pb-20 {
                padding-bottom: 20px;
            }

            .pb-30 {
                padding-bottom: 30px;
            }

            .pb-40 {
                padding-bottom: 40px;
            }

            .pb-50 {
                padding-bottom: 50px;
            }

            .left {
                float: left;
            }

            .right {
                float: right;
            }

            .clear {
                clear: both;
            }

            .date {
                text-align: right;
            }

            .text-right {
                text-align: right;
            }

            .text-end {
                text-align: right;
            }

            .text-left {
                text-align: left;
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

            hr {
                border: none;
                height: 1px;
                /* Set the hr color */
                color: #000;
                /* old IE */
                background-color: #333;
                /* Modern Browsers */
            }
        </style>

        @stack('after-styles')
    </head>

    <body {{ $attributes }}>
        {{ $slot }}

        @stack('after-scripts')
    </body>
</html>
