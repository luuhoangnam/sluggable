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
