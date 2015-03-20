# drupal-7-cache-provider

An implementation of Doctrine/CacheProvider that utilizes a DrupalCacheInterface object for a cache backend.

In a Drupal 7 installation it's recommended that this gets used in conjunction with the
[composer_manager](http://drupal.org/project/composer_manager) module for modules using the
[guzzlehttp/cache-subscriber](https://packagist.org/packages/guzzlehttp/cache-subscriber) package.

## Usage

In practice, using the provider looks something like this in a Drupal 7 installation.

Using the provider would look something like this:

```php
$client = new \GuzzleHttp\Client();

// Get the class used by Drupal for the standard cache.
$cache = variable_get('cache_class_cache', 'DrupalDatabaseCache');
$drupal_cache = new $cache('cache');

// Create a new doctrine cache object, passing in the drupal cache object as a dependency.
$doctrine_cache = new \jlandfried\DrupalCache($drupal_cache);

$storage = array('storage' => new \GuzzleHttp\Subscriber\Cache\CacheStorage($doctrine_cache, null, 3600));

\GuzzleHttp\Subscriber\Cache\CacheSubscriber::attach($client, $storage);

// Do stuff with your guzzle client...
```
