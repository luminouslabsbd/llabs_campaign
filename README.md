# Laravel Spark Pages

<a name=installing></a>
## Basic Installation

Add the package to your campaign installation:

```
composer require llabs/armando_campaign

```

Add the following to the `providers` array in `config/app.php`. This provider must be **registered as the last service provider** on the `providers` array:

```
Luminouslabs\Installer\Providers\LuminousLabsServiceProvider::class

```

Publish migrations:

```
php artisan vendor:publish --provider="Luminouslabs\Installer\Providers\LuminousLabsServiceProvider"

```

Run migrations:

```
php artisan migrate

```

<a name=sidebar></a>
## Editing & Update the Patner Defult Blade File

The template for the sidebar is located at `resources/ll_views/partner/layouts/default.blade.php`. Modify this file to your heart's content. 
This HTML code add after Account Setting menu.

```

<li>
    <a class="ll-sidebar-link flex items-center p-2 group" href="{{ route('luminouslabs::partner.campain.manage') }}"><x-ui.icon icon="user-circle" class="" /><span class="ml-2">Campaign</span></a>
</li>

```
