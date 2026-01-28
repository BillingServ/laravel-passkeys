<?php

namespace Spatie\LaravelPasskeys\Http\Controllers;

use Spatie\LaravelPasskeys\Actions\GeneratePasskeyAuthenticationOptionsAction;
use Spatie\LaravelPasskeys\Support\Config;

class GeneratePasskeyAuthenticationOptionsController
{
    public function __invoke()
    {
        $action = Config::getAction('generate_passkey_authentication_options', GeneratePasskeyAuthenticationOptionsAction::class);

        // The action handles session storage
        return $action->execute();
    }
}
