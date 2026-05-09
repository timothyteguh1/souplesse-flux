<div>
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="card-title mb-0 fs-14 text-white">Total Nilai Persediaan</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-4 mb-2 mb-lg-0">
                    <x-admin::input.select2 :id="'tahun_total_nilai_persediaan'" :name="'tahun'" :options="$this->optionsTahun" :defer="false"
                        :placeholder="'- Pilih Tahun -'" />
                </div>
            </div>
            <div style="height: 20rem" class="mt-3">
                <livewire:livewire-column-chart key="{{ $chart->reactiveKey() }}" :column-chart-model="$chart" />
            </div>
        </div>
    </div>
</div>
