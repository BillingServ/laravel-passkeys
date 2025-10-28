---
title: Installation & setup
weight: 4
---

Here's how you can install the package.

You can install the package via composer:

### Step 1: Require the package using composer

```bash
composer require spatie/laravel-passkeys
```

### Step 2: Add the package's interface and trait to your user model

You must let your user model (or any model you use to authenticate) implement the `HasPasskeys` interface and use the  `InteractsWithPasskeys` trait.

```php
namespace App\Models;

use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
// ...

class User extends Authenticatable implements HasPasskeys
{
    use HasFactory, Notifiable, InteractsWithPasskeys;

    // ... 
}
```

### Step 3: Optionally set the `AUTH_MODEL` in your `.env` file

You'll only need to do this if you're not using the default `User` model. If you're using a different model to authenticate, you must set the `AUTH_MODEL` in your `.env` file to the class name of the model that should be authenticated using passkeys.

```php
AUTH_MODEL=App\Models\User
```

### Step 4: publish and run the migrations

Generated passkeys are stored in the database. To create the `passkeys` you must publish and run migrations.

```bash
php artisan vendor:publish --tag="passkeys-migrations"
php artisan migrate
```

### Step 5: install the JavaScript dependencies

Under the hood, our package uses `simplewebauthn/browser` to generate and verify passkeys in your browser. You can install this dependencies via NPM (or Yarn)

```bash
npm install @simplewebauthn/browser
```

### Step 6: Import the JavaScript dependencies

In your entry JavaScript file, you must add this piece of code to initialize `simplewebauthn/browser`

```js
// probably resources/js/bootstrap.js or similar file

import {
    browserSupportsWebAuthn,
    startAuthentication,
    startRegistration,
} from '@simplewebauthn/browser'

window.browserSupportsWebAuthn = browserSupportsWebAuthn;
window.startAuthentication = startAuthentication;
window.startRegistration = startRegistration;
```

### Step 7: Re-build the JavaScript assets

To ensure that the code above is used, don't forget to rebuild your assets.

```bash
npm run build
```

### Step 8: Add the package provided routes

The package offers a couple of routes to help generating and authentication passkeys. You must add them to your application by adding the following line to your `routes/web.php` file:

```php
// routes/web.php
Route::passkeys();
```

### Step 9: Optionally publish the config file

Publishing the config file isn't required. You only need to do this to customize the package's behavior.

```bash
php artisan vendor:publish --tag="passkeys-config"
```

This is the content of the published config file:

```php
return [
    /*
     * After a successful authentication attempt using a passkey
     * we'll redirect to this URL.
     */
    'redirect_to_after_login' => '/dashboard',

    /*
     * These class are responsible for performing core tasks regarding passkeys.
     * You can customize them by creating a class that extends the default, and
     * by specifying your custom class name here.
     */
    'actions' => [
        'generate_passkey_register_options' => Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction::class,
        'store_passkey' => Spatie\LaravelPasskeys\Actions\StorePasskeyAction::class,
        'generate_passkey_authentication_options' => \Spatie\LaravelPasskeys\Actions\GeneratePasskeyAuthenticationOptionsAction::class,
        'find_passkey' => Spatie\LaravelPasskeys\Actions\FindPasskeyToAuthenticateAction::class,
        'configure_ceremony_step_manager_factory' => Spatie\LaravelPasskeys\Actions\ConfigureCeremonyStepManagerFactoryAction::class,
    ],

    /*
     * These properties will be used to generate the passkey.
     */
    'relying_party' => [
        'name' => config('app.name'),
        'id' => parse_url(config('app.url'), PHP_URL_HOST),
        'icon' => null,
    ],

    /*
     * The models used by the package.
     * 
     * You can override this by specifying your own models
     */
    'models' => [
        'passkey' => Spatie\LaravelPasskeys\Models\Passkey::class,
        'authenticatable' => env('AUTH_MODEL', App\Models\User::class),
    ],
];
```

## Next steps

With this setup out of the way, you can now using the components provided by the package to [generate passkeys](https://spatie.be/docs/laravel-passkeys/v1/basic-usage/generating-passkeys) and [authenticate using passkeys](https://spatie.be/docs/laravel-passkeys/v1/basic-usage/authenticating-using-passkeys).

### 8. Add the authentication component to the login view

```html
<x-authenticate-passkey />
```

### 9. Add the passkey management component to the profile view

```html
<livewire:passkeys />
```

### 10. (Optional) Publish the views for custom styling

```bash
php artisan vendor:publish --tag="passkeys-views"
```




