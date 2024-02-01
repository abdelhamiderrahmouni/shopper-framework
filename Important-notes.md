# Important Notes for developers

## 1. Storing images using Spatie Laravel Permission

please always use the following code to store images in the database

```php
    ->usingName(uniqid('collection-image-', true))
    ->usingFileName(uniqid('collection-image-', true) . '.' . pathinfo($this->fileUrl, PATHINFO_EXTENSION))            
```

like in the following example

```php
    $collection->addMediaFromUrl($request->fileUrl)
        ->usingName(uniqid('collection-image-', true))
        ->usingFileName(uniqid('collection-image-', true) . '.' . pathinfo($this->fileUrl, PATHINFO_EXTENSION))            
        ->toMediaCollection('collection-images');
```

## 2. Other notes
