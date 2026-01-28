<?php

namespace Spatie\LaravelPasskeys\Actions;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\LaravelPasskeys\Support\Config;
use Spatie\LaravelPasskeys\Support\Serializer;
use Webauthn\PublicKeyCredentialRequestOptions;

class GeneratePasskeyAuthenticationOptionsAction
{
    public function execute(): string
    {
        $options = new PublicKeyCredentialRequestOptions(
            challenge: Str::random(),
            rpId: Config::getRelyingPartyId(),
            allowCredentials: [],
        );

        $options = Serializer::make()->toJson($options);

        // Use put() instead of flash() so options persist across the WebAuthn flow
        // flash() only survives one request, but WebAuthn requires two (GET options, POST authenticate)
        Session::put('passkey-authentication-options', $options);

        return $options;
    }
}
