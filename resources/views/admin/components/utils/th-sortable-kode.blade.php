@props([
    'label' => '',
    'field' => '',
    'sortField' => '',
    'sortAsc' => '',
    'align' => 'start',
    'style' => 'width: 160px',
])

<th
    style="cursor: pointer; {{ $style }}"
    wire:click="sort('{{ $field }}')"
    class="align-content-center text-uppercase text-{{ $align }}"
    {{ $attributes }}
>
    <span role="button" class="d-flex align-items-center justify-content-between">
        <span>{{ $label }}</span>
        <span class="ms-1">
            @if ($sortField == $field)
                <i class="mdi mdi-menu-{{ $sortAsc ? 'up' : 'down' }}"></i>
            @else
                <i class="mdi mdi-menu-swap"></i>
            @endif
        </span>
    </span>
</th>
