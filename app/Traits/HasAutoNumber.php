<?php

namespace App\Traits;

use App\Models\CodeCounter;
use Illuminate\Support\Str;

trait HasAutoNumber
{
    abstract public function autoNumberPrefix(array $data = []);

    public static function bootHasAutoNumber()
    {
        static::creating(function ($model) {
            if (!$model->{$model->getAutoNumberColumn()}) {
                $cabang_id = $model->cabang_id;
                $prefix = $model->autoNumberPrefix();
                $lastCodeCounter = (new self())->getLastCounter($cabang_id, $prefix);
                $model->{$model->getAutoNumberColumn()} = (new self())->generateCode($lastCodeCounter);
            }
        });
    }

    public function getAutoNumberColumn()
    {
        return 'kode';
    }

    public function autoNumberLength()
    {
        if (empty($this->auto_number_length)) {
            return 3;
        }

        return $this->auto_number_length;
    }

    public function getAutoNumber(array $data = [], $update = false): string
    {
        $cabang_id = $this->cabang_id;
        $prefix = $this->autoNumberPrefix($data);
        $lastCodeCounter = $this->getLastCounter($cabang_id, $prefix);

        return $this->generateCode($lastCodeCounter, $update);
    }

    private function getLastCounter($cabang_id, $prefix)
    {
        $length = $this->autoNumberLength();

        $lastCodeCounter = CodeCounter::query()
            ->when($cabang_id, function ($query) use ($cabang_id) {
                return $query->where('cabang_id', $cabang_id);
            })
            ->where('model', get_class($this))
            ->where('prefix', $prefix)
            ->where('length', $length)
            ->first();

        if (!$lastCodeCounter) {
            // jika belum ada code counternya, cari kode max di tabel
            $numberLength = $length + strlen($prefix);
            $lastModelCounter = optional(
                $this->whereNotNull($this->getAutoNumberColumn())
                    ->when($cabang_id, function ($query) use ($cabang_id) {
                        return $query->where('cabang_id', $cabang_id);
                    })
                    ->where($this->getAutoNumberColumn(), 'LIKE', $prefix . '%')
                    ->whereRaw('LENGTH(?) = ?', [$this->getAutoNumberColumn(), $numberLength])
                    ->latest($this->getAutoNumberColumn())
                    ->first(),
            )->{$this->getAutoNumberColumn()};

            $max = 0;

            if ($lastModelCounter) {
                $max = Str::after($lastModelCounter, $prefix);
                $max = (int) $max;
            }

            $lastCodeCounter = CodeCounter::create([
                'cabang_id' => $cabang_id,
                'model' => get_class($this),
                'prefix' => $prefix,
                'length' => $length,
                'counter' => $max,
            ]);
        }

        return $lastCodeCounter;
    }

    private function generateCode(CodeCounter $codeCounter, $update = true)
    {
        do {
            $nextCounter = $codeCounter->counter + 1;

            $codeCounter->counter = $nextCounter;
            if ($update) {
                $codeCounter->save();
            }

            $code = $codeCounter->prefix . str_pad((string) $codeCounter->counter, $codeCounter->length, '0', STR_PAD_LEFT);

            $exists = $this->where($this->getAutoNumberColumn(), $code)
                ->when($codeCounter['cabang_id'], function ($query) use ($codeCounter) {
                    $query->where('cabang_id', $codeCounter['cabang_id']);
                })
                ->exists();
        } while ($exists);

        return $code;
    }
}
