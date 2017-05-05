# B3COD4x
B3 management system build on laravel with support for cod4x screenshots. Based on the idea of Echelon

Install library in a laravel installation via composer

```composer require Stormyy/b3cod4x```

Add the service provider in the app.php

```Stormyy\B3\B3AddonServiceProvider::class```

After that deploy assets by executing

```php artisan vendor:publish --provider="Stormyy\B3\B3AddonServiceProvider" --force```

Excecute migrations with 

```php artisan migrate```


The library uses the base template from laravel located in resources/views/layouts/app.php on the bottom of this layout (inside body) add the following: 

```
<script type="text/javascript">
    window.pusherinfo = {
        broadcaster: 'pusher',
        key: '',
        cluster: 'eu',
        encrypted: true,
        namespace: 'Stormyy\\B3\\Events'
    }
</script>
<script type="text/javascript" src="{{asset('vendor/stormyy/b3cod4x/js/b3app.js')}}"></script>
```

If you want to make use of notifcation/broadcast system see https://laravel.com/docs/5.4/broadcasting and add the pusher information above
