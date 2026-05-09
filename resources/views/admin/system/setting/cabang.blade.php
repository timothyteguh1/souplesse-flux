<div>
    <div class="p-3 pb-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-nowrap">
                <tbody>
                    @foreach ($cabangs as $cabang)
                        <tr>
                            <td class="text-center" style="width: 50px">
                                <x-admin::input.checkbox
                                    :name="'cabang_ids.' . $cabang->id"
                                    :value="$cabang->id"
                                    :defer="false"
                                    :form-check-class="false"
                                    :wire:key="'cabang_ids_' . $loop->index"
                                    :disabled="$cabang->id == $selected_cabang_id"
                                />
                            </td>
                            <td
                                wire:click="selectCabang('{{ $cabang->id }}')"
                                @class(['bg-light' => $cabang->id == $selected_cabang_id])
                                style="cursor: pointer"
                            >
                                {{ $cabang->nama }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border">
        <button class="btn btn-primary text-center w-100" wire:click="save">Update</button>
    </div>
</div>
