<?php
namespace jlandfried;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Drupal-specific implementation of Doctrine CacheProvider
 */
class DrupalCache extends CacheProvider
{
  const PREFIX = "DrupalGuzzleCache";
  protected $drupalCache;

  /**
   * @param $drupalCache
   *   An instance of something implementing DrupalCacheInterface.
   *   Unfortunately Drupal 7 doesn't use namespaces, so it can't be
   *   typehinted.
   */
  public function __construct($drupalCache) {
    $this->drupalCache = $drupalCache;
  }

  /**
   * {@inheritdoc}
   */
  protected function doFetch($id)
  {
    $cache = $this->drupalCache->get($this->prepareId($id));
    if (isset($cache->data)) {
      return $cache->data;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function doContains($id)
  {
    $cache = $this->drupalCache->get($this->prepareId($id));
    return (bool) $cache->data ? $cache->data : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function doSave($id, $data, $lifeTime = 0)
  {
    $this->drupalCache->set($this->prepareId($id), $data, $lifeTime);

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function doDelete($id)
  {
    $this->drupalCache->clear($this->prepareId($id));
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function doFlush()
  {
    $this->drupalCache->clear(self::PREFIX, TRUE);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function doGetStats()
  {
    return NULL;
  }

  /**
   * Return a cache id that is prefixed with defined string.
   *
   * @param $id
   * @return string
   */
  private function prepareId($id) {
    return  $this::PREFIX . md5($id);
  }
}
