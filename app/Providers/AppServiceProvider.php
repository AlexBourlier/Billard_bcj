<?php

namespace App\Providers;

use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\TelematHttpFetcher;
use App\Domain\LicenseImport\Projection\Contracts\TelematProjectionStrategy;
use App\Domain\LicenseImport\Projection\Strategies\TelematFullReplaceProjectionStrategy;
use App\Models\Contact;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TelematFetcherInterface::class,
            TelematHttpFetcher::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.footer', function ($view) {
            $contact = SiteSetting::first();
            $view->with('contact_footer', $contact);
        });

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Admin::booting(function () {
        //     Admin::js('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js');
        //     Admin::js('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js');
        //     Admin::css('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css');
        //
        //     Admin::script("
        //         $('.date').datepicker({
        //             format: 'yyyy-mm-dd',
        //             language: 'fr',
        //             autoclose: true,
        //             todayHighlight: true
        //         });
        //     ");
        // });
    }
}