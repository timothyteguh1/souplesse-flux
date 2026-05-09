<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Models\Master\Cabang;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use Lab404\Impersonate\Events\TakeImpersonation;
use Lab404\Impersonate\Events\LeaveImpersonation;
use Illuminate\Database\Eloquent\Factories\Factory;

use function Sentry\captureMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootLazyLoading();
        $this->bootBlade();
        $this->bootFactory();
        $this->bootAuth();
        $this->bootEvent();
        $this->bootMacro();
    }

    public function bootLazyLoading()
    {
        // Prevent lazy loading always.
        Model::preventLazyLoading(! $this->app->isProduction());

        // But in production, log the violation instead of throwing an exception.
        if ($this->app->isProduction()) {
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                $class = get_class($model);

                $message = "Attempted to lazy load [{$relation}] on model [{$class}].";
                captureMessage($message);
                info($message);
            });
        }
    }

    public function bootBlade()
    {
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');
    }

    public function bootFactory()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $reflection_class = new \ReflectionClass($modelName);
            $namespace = $reflection_class->getNamespaceName();

            $folder = '';
            if ($namespace != "App\Models") {
                $folder = Str::after($namespace, 'App\\Models\\') . '\\';
            }

            return 'Database\\Factories\\' .
                $folder .
                class_basename($modelName) .
                'Factory';
        });
    }

    public function bootAuth(): void
    {
        LogViewer::auth(function ($request) {
            return $request->user()->is_developer;
        });

        Gate::before(function ($user, $ability) {
            if ($ability == 'viewPulse') {
                return $user->is_developer;
            }

            if ($user->is_developer) {
                return true;
            }

            $superUserPermission = Permission::where('name', 'superuser')->first();
            if ($superUserPermission && $user->hasPermissionTo($superUserPermission)) {
                return true;
            }

            return false;
        });
    }

    public function bootEvent(): void
    {
        Event::listen(function (TakeImpersonation $event) {
            session()->put([
                'password_hash_web' => $event->impersonated->getAuthPassword(),
            ]);
        });

        Event::listen(function (LeaveImpersonation $event) {
            session()->put([
                'password_hash_web' => $event->impersonator->getAuthPassword(),
            ]);
        });
    }

    public function bootMacro(): void
    {
        Carbon::macro('inApplicationTimezone', function () {
            $timezone = config('app.timezone_display');

            return $this->timezone($timezone);
        });

        Carbon::macro('inUserTimezone', function () {
            $timezone = auth()->user()?->timezone;

            if (!$timezone) {
                $cabang_id = session()->get('cabang_id');
                $timezone = Cabang::find($cabang_id)?->timezone;
            }

            if (!$timezone) {
                $timezone = config('app.timezone_display');
            }

            return $this->timezone($timezone);
        });
    }
}
