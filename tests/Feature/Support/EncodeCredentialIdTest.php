<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelPasskeys\Models\Passkey;

it('base64-encodes credential id when the connection driver is pgsql', function () {
    DB::shouldReceive('connection->getDriverName')->andReturn('pgsql');

    $raw = "\xFF\xFE";

    expect(Passkey::encodeCredentialId($raw))->toBe(base64_encode($raw));
});

it('uses mb_convert_encoding for non-pgsql drivers', function () {
    DB::shouldReceive('connection->getDriverName')->andReturn('mysql');

    $raw = "\xFF\xFE";

    expect(Passkey::encodeCredentialId($raw))->toBe(mb_convert_encoding($raw, 'UTF-8'));
});
