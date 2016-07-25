<?php
// Create Driver with default options
$driver = new Stash\Driver\FileSystem(array('path' => E25_CACHE_PATH . '.stash/'));
// Inject the driver into a new Pool object.
$cachePool = new Stash\Pool($driver);
Flight::set('cachePool', $cachePool);
