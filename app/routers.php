<?php

Flight::map('getGlobals', function(){
    if (!Flight::has('globalData')){
      $data['menus']['main_menu']   = Flight::getMenuById(8);
      $data['menus']['footer_menu'] = Flight::getMenuById(10);
      Flight::set('globalData', $data);
    }
    return Flight::get('globalData');
});

function homepage(){
  try {
    $data = Flight::getDataById('pages', '497');
    $data['globals'] = Flight::getGlobals();
    Flight::render('front-page.html', $data);
  } catch (Exception $e) {
    Flight::notFound($e->getMessage());
  }
}


function newsIndex()
{
  try {
    $args = [
      'per_page' => 100,
      'order' => 'asc'
    ];
    $data = Flight::getDataBySlug('pages', 'news');
    $data['globals'] = Flight::getGlobals();
    $data['entries'] = Flight::getData('news', $args, 'cache-id-for-news');
    Flight::render('news/index.html', $data);
  } catch (Exception $e) {
    Flight::notFound($e->getMessage());
  }
}
function newsEntry($slug){
    try {
			$data = Flight::getDataBySlug('news', $slug);
      $data['globals'] = Flight::getGlobals();
			Flight::render('news/entry.html', $data);
    } catch (Exception $e) {
			Flight::notFound($e->getMessage());
    }
}


function moviesIndex()
{
  try {
    $args = [
      'per_page' => 2,
      'order' => 'asc'
    ];
    $data = Flight::getDataBySlug('pages', 'movies');
    $data['entries'] = Flight::getData('movies', $args, 'cache-id-for-movie');
    $data['globals'] = Flight::getGlobals();
    Flight::render('movies/index.html', $data);
  } catch (Exception $e) {
    Flight::notFound($e->getMessage());
  }
}
function moviesEntry($slug){
    try {
			$data = Flight::getDataBySlug('movies', $slug);
      $data['globals'] = Flight::getGlobals();
			Flight::render('movies/entry.html', $data);
    } catch (Exception $e) {
			Flight::notFound($e->getMessage());
    }
}

function pages($slug){
    try {
      $data = Flight::getDataBySlug('pages', $slug);
      $data['globals'] = Flight::getGlobals();
			$pageTemplate = 'page-' . $slug . '.html';
			$template = file_exists(E25_TEMPLATES_PATH . $pageTemplate) ? $pageTemplate : 'page.html' ;
			Flight::render($template, $data);
    } catch (Exception $e) {
			Flight::notFound($e->getMessage());
    }
}

Flight::map('notFound', function($message){
  $data['globals'] = Flight::getGlobals();
	Flight::render('404.html', array('messeage' => $message));
});
