@props([
    'type',
    'name',
    'defer' => true,
    'showError' => 'true',
    'disabled' => false,
])

<div
    class="input-group"
    x-data="{
        selectedText: @entangle($type),
        setSelected(value) {
            this.selectedText = value
        },
    }"
>
    <button
        class="btn btn-success dropdown-toggle"
        type="button"
        tabindex="-1"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        x-text="selectedText"
    ></button>
    <ul class="dropdown-menu" style="">
        <li
            class="dropdown-item"
            x-on:click="setSelected('{{ \App\Utilities\Constants\Const_Umum::DISKON_TYPE_RP }}')"
        >
            Rp
        </li>
        <li
            class="dropdown-item"
            x-on:click="setSelected('{{ \App\Utilities\Constants\Const_Umum::DISKON_TYPE_PERCENT }}')"
        >
            %
        </li>
    </ul>

    <x-admin::input.number
        :name="$name"
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        :defer="$defer"
    />
</div>
