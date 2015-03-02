# Getting Started

Provide an _elegant way_ to interact with slug feature for your eloquent models.

**Note**: The package is only support Laravel 5

# Installation

**Step 1**: Install package
```bash
composer require namest/sluggable
```

**Step 2**: Register service provider alias in your `config/app.php`
```php
return [
    ...
    'providers' => [
        ...
        'Namest\Sluggable\SluggableServiceProvider',
    ],
    ...
];
```

**Step 3**: Publish package resources, include: configs, migrations. Open your terminal and type:
```bash
php artisan vendor:publish --provider="Namest\Sluggable\SluggableServiceProvider"
```

**Step 4**: Migrate the migration that have been published
```bash
php artisan migrate
```

**Step 5**: Use some traits to make awesome things
```php
class User extends Model
{
    use \Namest\Sluggable\HasSlug;
    
    // ...
}
```

**Step 6**: Read API below and start _happy_

# API

```php
$post->slug = 'the-new-post';
$post->save(); // Save post & slug;

$slug = $post->slug; // Get slug string
```

```php
Slug::isValid($name); // Check a name is valid for slug: unique & sluged
Slug::regenerate($name); // Regenerate a slug if its invalid
Slug::regenerate($name, true); // Regenerate a slug without check its valid or not
```

# Reserve

Config slug reservation in `config/slug.php` file.
