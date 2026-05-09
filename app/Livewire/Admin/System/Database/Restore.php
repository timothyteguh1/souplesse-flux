<?php

namespace App\Livewire\Admin\System\Database;

use App\Jobs\RestoreDatabaseJob;
use App\Traits\HasKeywordSearch;
use App\Traits\Livewire\WithIndexForm;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Spatie\Backup\Helpers\Format;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

class Restore extends Component
{
    use HasKeywordSearch;
    use WithIndexForm;

    public $menuTitle = 'Restore Database';
    public $tanggal;
    public $disks;
    public $disk_id;
    protected $files;

    public function mount()
    {
        abort_if(Gate::none(['admin.system.database.restore']), Response::HTTP_FORBIDDEN);

        $this->tanggal = _get_default_date_range();
    }

    public function processRestore($disk, $path)
    {
        try {
            RestoreDatabaseJob::dispatch($disk, $path);
            session()->flash('flash_success', 'Proses restore database telah dilakukan.');
        } catch (Exception $exception) {
            $this->dispatch('page-to-top');
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    private function getQuery()
    {
        return;
    }

    private function getFiles()
    {
        $collections = collect();
        $this->disks = [];

        $tanggals = explode(' - ', $this->tanggal);
        $tanggal_awal = Carbon::parse(_datetime_format_db($tanggals[0]))->startOfDay();
        $tanggal_akhir = $tanggal_awal->clone()->endOfDay();
        if (count($tanggals) > 1) {
            $tanggal_akhir = Carbon::parse(_datetime_format_db($tanggals[1]))->endOfDay();
        }

        $backupDestinationStatuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitor_backups'));
        $backupDestinationStatuses->map(function (BackupDestinationStatus $backupDestinationStatus) use ($collections, $tanggal_awal, $tanggal_akhir) {
            $destination = $backupDestinationStatus->backupDestination();
            $this->disks[] = $destination->diskName();

            if ($this->disk_id && $this->disk_id != $destination->diskName()) {
                return;
            }

            $backups = $destination->backups();
            foreach ($backups as $backup) {
                $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();
                $date = $backup->date()->clone()->format('Y-m-d H:i:s');
                $start = $backup->date()->clone()->startOfDay();
                $end = $backup->date()->clone()->endOfDay();

                if ($tanggal_awal <= $start && $end <= $tanggal_akhir) {
                    $collections->push([
                        'disk' => $destination->diskName(),
                        'path' => $backup->path(),
                        'date' => $date,
                        'size' => Format::humanReadableSize($size),
                    ]);
                }
            }
        });

        return $collections;
    }

    public function render()
    {
        $this->data = $this->getFiles();

        return view('admin.system.database.restore', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
