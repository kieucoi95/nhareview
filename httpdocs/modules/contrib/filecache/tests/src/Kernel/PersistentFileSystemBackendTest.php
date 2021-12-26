<?php

declare(strict_types = 1);

namespace Drupal\Tests\filecache\Kernel;

use Drupal\Core\Cache\Cache;
use Drupal\filecache\Cache\FileSystemBackend;

/**
 * Tests the FileSystemBackend cache backend using the persistent option.
 *
 * @group filecache
 */
class PersistentFileSystemBackendTest extends FileSystemBackendTest {

  /**
   * {@inheritdoc}
   */
  protected function createCacheBackend($bin) {
    // Set the FileSystemBackend as the default cache backend.
    $this->setSetting('cache', ['default' => 'cache.backend.file_system']);

    $base_path = $this->config('system.file')->get('default_scheme') . '://filecache';
    $settings = [
      'directory' => [
        'default' => $base_path,
        'bins' => [
          'foo' => $base_path . '/foo',
          'bar' => $base_path . '/bar',
        ],
      ],
      'strategy' => [
        'default' => FileSystemBackend::PERSIST,
        'bins' => [
          'foo' => FileSystemBackend::PERSIST,
          'bar' => FileSystemBackend::PERSIST,
        ],
      ],
    ];
    $this->setSetting('filecache', $settings);

    return $this->container->get('cache.backend.file_system')->get($bin);
  }

  /**
   * {@inheritdoc}
   */
  public function testDeleteAll() {
    $backend_a = $this->getCacheBackend();
    $backend_b = $this->getCacheBackend('bootstrap');

    // Set both expiring and permanent keys.
    $backend_a->set('test1', 1, Cache::PERMANENT);
    $backend_a->set('test2', 3, time() + 1000);
    $backend_b->set('test3', 4, Cache::PERMANENT);

    $backend_a->deleteAll();

    $this->assertEquals(1, $backend_a->get('test1')->data, 'First key has been persisted.');
    $this->assertEquals(3, $backend_a->get('test2')->data, 'Second key has been persisted.');
    $this->assertEquals(4, $backend_b->get('test3')->data, 'Item in other bin is preserved.');
  }

  /**
   * {@inheritdoc}
   */
  public function testInvalidateAll() {
    $backend_a = $this->getCacheBackend();
    $backend_b = $this->getCacheBackend('bootstrap');

    // Set both expiring and permanent keys.
    $backend_a->set('test1', 1, Cache::PERMANENT);
    $backend_a->set('test2', 3, time() + 1000);
    $backend_b->set('test3', 4, Cache::PERMANENT);

    $backend_a->invalidateAll();

    $this->assertEquals(1, $backend_a->get('test1')->data, 'First key has been persisted.');
    $this->assertEquals(3, $backend_a->get('test2')->data, 'Second key has been persisted.');
    $this->assertEquals(4, $backend_b->get('test3')->data, 'Item in other bin is preserved.');
    $this->assertEquals(1, $backend_a->get('test1', TRUE)->data, 'First key has not been deleted.');
    $this->assertEquals(3, $backend_a->get('test2', TRUE)->data, 'Second key has not been deleted.');
  }

}
