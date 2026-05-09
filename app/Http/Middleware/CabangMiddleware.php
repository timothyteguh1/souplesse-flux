<?php

namespace App\Http\Middleware;

use App\Models\Master\Cabang;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CabangMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $cabang = session()->get('cabang_id');

        if (!$cabang) {
            $segments = explode('|', Cookie::get(Auth::getRecallerName()) ?: '');
            if (count($segments) < 4) {
                Auth::logout();
                session()->flush();
                session()->invalidate();
                session()->regenerateToken();

                return to_route(_get_homepage_route());
            }

            // setting session cabang
            $cabangs = explode(",", $segments[3]);
            $cabangs = collect($cabangs);
            $cabang_id = $cabangs->first();
            $cabang_ids = $cabangs->slice(1)->all();

            $cabang = Cabang::find($cabang_id);

            session()->put('cabang_id', $cabang->id);
            session()->put('cabang_kode', $cabang->kode);
            session()->put('cabang_nama', $cabang->nama);

            session()->put('cabang_ids', $cabang_ids);
        }

        return $next($request);
    }
}
