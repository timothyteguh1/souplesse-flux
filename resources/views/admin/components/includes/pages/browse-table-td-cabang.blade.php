@props([
    'obj',
])

@if (count(session()->get('cabang_ids')) > 1)
    <td><a href="{{ $obj->cabang->getRouteShow() }}">{{ $obj->cabang->nama }}</a></td>
@endif
