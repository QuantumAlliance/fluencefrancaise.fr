<?php

namespace App\Providers;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\Extra\SpoofCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\NoRFCWarningsValidation;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Concerns\FilterEmailValidation;

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
        $this->hardenEmailValidation();
    }

    /**
     * Reject CRLF sequences in the "email" validation rule.
     *
     * Backport of the upstream fix for GHSA-5vg9-5847-vvmq (patched in Laravel
     * 12.60.0). Laravel 10 is end-of-life and will not receive the patch, so the
     * rule is replaced here with the stock implementation plus a CRLF guard.
     * Remove this once the framework is upgraded to >= 12.61.1.
     */
    protected function hardenEmailValidation(): void
    {
        Validator::extend('email', function ($attribute, $value, $parameters, $validator) {
            if (! is_string($value) && ! (is_object($value) && method_exists($value, '__toString'))) {
                return false;
            }

            // The guard: no line breaks may reach the mail transport.
            if (preg_match('/[\r\n]/', (string) $value)) {
                return false;
            }

            $validations = collect($parameters)
                ->unique()
                ->map(fn ($validation) => match (true) {
                    $validation === 'strict' => new NoRFCWarningsValidation(),
                    $validation === 'dns' => new DNSCheckValidation(),
                    $validation === 'spoof' => new SpoofCheckValidation(),
                    $validation === 'filter' => new FilterEmailValidation(),
                    $validation === 'filter_unicode' => FilterEmailValidation::unicode(),
                    is_string($validation) && class_exists($validation) => Container::getInstance()->make($validation),
                    default => new RFCValidation(),
                })
                ->values()
                ->all() ?: [new RFCValidation];

            $emailValidator = Container::getInstance()->make(EmailValidator::class);

            return $emailValidator->isValid((string) $value, new MultipleValidationWithAnd($validations));
        });
    }
}
