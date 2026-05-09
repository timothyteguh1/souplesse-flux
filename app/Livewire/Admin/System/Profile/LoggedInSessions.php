<?php

namespace App\Livewire\Admin\System\Profile;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LoggedInSessions extends Component
{
    public $lists;

    public function logoutAll()
    {
        try {
            $user = auth()->user();
            $user->remember_token = null;
            $user->save();

            DB::table('sessions')
                ->where('user_id', Auth::user()->id)
                ->delete();

            session()->flash('flash_success', 'Semua device telah logout.');
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function logout($id)
    {
        try {
            $user = auth()->user();
            $user->remember_token = null;
            $user->save();

            DB::table('sessions')
                ->where('user_id', Auth::user()->id)
                ->where('id', $id)
                ->delete();

            session()->flash('flash_success', 'Device telah logout.');
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        $this->lists = DB::table('sessions')
            ->where('user_id', Auth::user()->id)
            ->get();

        return view('admin.system.profile.logged-in-sessions')
            ->layout('admin.components.layouts.app');
    }
}
