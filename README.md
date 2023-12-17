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

Import css path in `resources/css/app.css`.

```

@import url('/public/luminouslabs/css/ll_style.css');

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
## Change View File Return You New Theme

The path  located at `config/view.php`. Modify this file to your heart's content. 
This HTML code add after Account Setting menu.

```
'paths' => [
    resource_path('views/luminouslabs/installer'),
],

```


<a name=sidebar></a>
## Update You Build File 

If project have node module

```
nmp run build

```

<a name=sidebar></a>
## Update You Build File 

If you do not have Node modules installed in your project: 

```
nmp -i 
npm run build

```
