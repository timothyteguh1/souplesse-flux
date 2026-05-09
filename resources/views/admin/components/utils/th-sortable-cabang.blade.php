@props([
    'label' => '',
    'field' => '',
    'sortField' => '',
    'sortAsc' => '',
    'align' => 'start',
    'style' => 'width: 180px',
])

@if (count(session()->get('cabang_ids')) > 1)
    <th
        style="cursor: pointer; {{ $style }}"
        wire:click="sort('{{ $field }}')"
        class="text-uppercase text-{{ $align }}"
        {{ $attributes }}
    >
        <span role="button" @if ($align == 'right') class="mr-2" @endif>
            {{ $label }}
        </span>
        <span class="float-end">
            @if ($sortField == $field)
                <i class="mdi mdi-menu-{{ $sortAsc ? 'up' : 'down' }}"></i>
            @else
                <i class="mdi mdi-menu-swap"></i>
            @endif
        </span>
    </th>
@endif
