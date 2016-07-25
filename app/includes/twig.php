<?php

// twig templating engine integration
Twig_Autoloader::register();
$twig_loader = new Twig_Loader_Filesystem(E25_TEMPLATES_PATH);
$twig_config = array(
		'debug' => DEBUG_MODE,
    'cache' => E25_CACHE_PATH . 'template_cache'
);
$twig = new Twig_Environment($twig_loader, $twig_config);
$twig->addExtension(new Twig_Extension_Debug());

$fixAssets = new Twig_SimpleFilter('fixAssets', function ($string) {
    return str_replace(E25_API_ASSETS_URL, E25_APP_ASSETS_URL, $string);
});
$twig->addFilter($fixAssets);
$fixUrls = new Twig_SimpleFilter('fixUrls', function ($string) {
    return str_replace(E25_API_HOST, E25_APP_HOST, $string);
});
$twig->addFilter($fixUrls);
// an anonymous function
$assetsFunction = new Twig_SimpleFunction('assets', function ($string) {
    return E25_APP_HOST . 'assets/'. $string;
});
$twig->addFunction($assetsFunction);
Flight::set('twig', $twig);

// twmplate renderer function
Flight::map('render', function($template, $data){
	$twig = Flight::get('twig');
	$template = $twig->loadTemplate($template);
	print $template->render($data);
});
