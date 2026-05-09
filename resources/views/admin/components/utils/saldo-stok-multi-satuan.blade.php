@props([
    'produk_id',
    'total',
    'is_tanda_kurung' => false,
    'as_string' => false,
])

<?php
$stoks = \App\Utilities\Functions\InventoryFunction::getStokMultiSatuan($produk_id, $total);
?>

{{-- format-ignore-start --}}
@if (count($stoks))
    @if ($is_tanda_kurung)
        (
    @endif

    @foreach ($stoks as $stok)
        @if (! $loop->first)
            &mdash;
        @endif

        {{ _number($stok['jumlah']) }}
        @if(!$as_string)
            <a href="{{ route('admin.master.satuan.show', $stok['satuan_id']) }}">
        @endif
        {{ $stok['satuan_nama'] }}
        @if(!$as_string)
            </a>
        @endif
    @endforeach

    @if ($is_tanda_kurung)
        )
    @endif
@endif
{{-- format-ignore-end --}}
