<?php

require_once __DIR__ . '/bootstrap.php';

/*
* Below are the routers which calls to respective function in the routers.php
* Below routes load order is a must for properly routing respective page/post/cpt.
*/

// homepage
Flight::route('/', 'homepage');




// routes blog articles
Flight::route('/news', 'newsIndex');
Flight::route('/news/@slug:[a-z0-9-]+', 'newsEntry');

// routes movie articles
Flight::route('/movies', 'moviesIndex');
Flight::route('/movies/@slug:[a-z0-9-]+', 'moviesEntry');

// level 1 page routing ( eg: domaon.com/level-1 )
Flight::route('/@pageSlug:[a-z0-9-]+', 'pages');
// level 2 page routing ( eg: domaon.com/level-1/level-2 )
Flight::route('/[a-z0-9-]+/@pageSlug:[a-z0-9-]+', 'pages');

Flight::start();
