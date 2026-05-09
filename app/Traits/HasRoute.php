<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait HasRoute
{
    public static function routePrefix()
    {
        return (new self())->getRoutePrefix();
    }

    public static function routeIndex()
    {
        return (new self())->getRouteIndex();
    }

    public static function routeCreate()
    {
        return (new self())->getRouteCreate();
    }

    public static function routeShow($obj)
    {
        return $obj->getRouteShow();
    }

    public static function routeEdit($obj)
    {
        return $obj->getRouteEdit();
    }

    public static function routeImport()
    {
        return (new self())->getRouteImport();
    }

    // ------------------------------------------------------------------------

    public function getRoutePrefix()
    {
        $this->validatePrefix();

        return $this->route_prefix ?? null;
    }

    public function getRouteIndex()
    {
        if (optional(auth()->user())->can($this->getPermissionPrefix() . '.index')) {
            if (Route::has($this->getRoutePrefix() . '.index')) {
                return route($this->getRoutePrefix() . '.index');
            }
        }

        return '#';
    }

    public function getRouteCreate()
    {
        if (optional(auth()->user())->can($this->getPermissionPrefix() . '.create')) {
            if (Route::has($this->getRoutePrefix() . '.create')) {
                return route($this->getRoutePrefix() . '.create');
            }
        }

        return '#';
    }

    public function getRouteShow()
    {
        if (optional(auth()->user())->can($this->getPermissionPrefix() . '.show')) {
            if (Route::has($this->getRoutePrefix() . '.show')) {
                return route($this->getRoutePrefix() . '.show', $this->id);
            }
        }

        return '#';
    }

    public function getRouteEdit()
    {
        if (optional(auth()->user())->can($this->getPermissionPrefix() . '.edit')) {
            if (Route::has($this->getRoutePrefix() . '.edit')) {
                return route($this->getRoutePrefix() . '.edit', $this->id);
            }
        }

        return '#';
    }

    public function getRouteImport()
    {
        if (optional(auth()->user())->can($this->getPermissionPrefix() . '.create')) {
            if (Route::has($this->getRoutePrefix() . '.import')) {
                return route($this->getRoutePrefix() . '.import');
            }
        }

        return '#';
    }

    // ------------------------------------------------------------------------

    public static function permissionPrefix()
    {
        return (new self())->getPermissionPrefix();
    }

    public static function permissionIndex()
    {
        return (new self())->getPermissionIndex();
    }

    public static function permissionCreate()
    {
        return (new self())->getPermissionCreate();
    }

    public static function permissionShow()
    {
        return (new self())->getPermissionShow();
    }

    public static function permissionEdit()
    {
        return (new self())->getPermissionEdit();
    }

    public static function permissionDelete()
    {
        return (new self())->getPermissionDelete();
    }

    public static function permissionActivityLog()
    {
        return (new self())->getPermissionActivityLog();
    }

    // ------------------------------------------------------------------------

    public function getPermissionPrefix()
    {
        // $this->validatePrefix();
        return $this->permission_prefix ?? null;
    }
    public function resolvePermissionPrefix()
    {
        return $this->permission_prefix ?? null;
    }

    public function getPermissionIndex()
    {
        return $this->getPermissionPrefix() . '.index';
    }

    public function getPermissionCreate()
    {
        return $this->resolvePermissionPrefix() . '.create';
    }

    public function getPermissionShow()
    {
        return $this->resolvePermissionPrefix() . '.show';
    }

    public function getPermissionEdit()
    {
        return $this->resolvePermissionPrefix() . '.edit';
    }

    public function getPermissionDelete()
    {
        return $this->resolvePermissionPrefix() . '.delete';
    }

    public function getPermissionActivityLog()
    {
        return $this->resolvePermissionPrefix() . '.activity-log';
    }

    // ------------------------------------------------------------------------

    private function validatePrefix()
    {
        //        if (empty($this->route_prefix)) {
        //            throw new \Exception('$route_prefix must be defined in ' . __CLASS__);
        //        }
        //
        //        if (empty($this->permission_prefix)) {
        //            throw new \Exception('$permission_prefix must be defined in ' . __CLASS__);
        //        }
    }

    public function getFromCurrentRoute()
    {
        $route = Route::currentRouteName();
        return substr($route, 0, strrpos($route, "."));
    }
}
