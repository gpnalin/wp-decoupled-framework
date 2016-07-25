<?php

// Register API Client
$apiClient = new GuzzleHttp\Client(['base_url' => E25_API_HOST . 'wp-json/']);
Flight::set('apiClient', $apiClient);

// get data from the api
Flight::map('getDataBySlug', function($resourceType, $slug){

	$cachePool = Flight::get('cachePool');
	// Get a cache item.
	$cacheItem = $cachePool->getItem($resourceType.'-'.$slug);
	// Attempt to get the data
	$data = $cacheItem->get();
	// Check to see if the data was a miss.
	if($cacheItem->isMiss())
	{
		// Let other processes know that this one is rebuilding the data.
		$cacheItem->lock();
		// Run intensive code
		$apiClient = Flight::get('apiClient');
		$response = $apiClient->get('wp/v2/' . $resourceType, [
				'query' => ['filter[name]' => $slug]
		]);
		$jsonData = $response->getBody();
		$dataObj = json_decode($jsonData, true);

		if (empty($dataObj[0]))
			throw new Exception('No data found for the request');

		$data = Flight::formatData($dataObj[0]);

		// Store the expensive to generate data
		$cachePool->save($cacheItem->set($data));
	}
	return $data;
});



// get data from the api
Flight::map('getData', function($resourceType, $args, $cacheId){

	$cachePool = Flight::get('cachePool');
	// Get a cache item.
	$cacheItem = $cachePool->getItem($resourceType.'-'.$cacheId);
	// Attempt to get the data
	$data = $cacheItem->get();
	// Check to see if the data was a miss.
	if($cacheItem->isMiss())
	{
		// Let other processes know that this one is rebuilding the data.
		$cacheItem->lock();

		// Run intensive code
		$apiClient = Flight::get('apiClient');
		$response = $apiClient->get('wp/v2/' . $resourceType, array(
			'query' => $args
		));
		$jsonData = $response->getBody();
		$dataObj = json_decode($jsonData, true);

		if (empty($dataObj))
			throw new Exception('No data found for the request');

		$data = $dataObj;

		// Store the expensive to generate data
		$cachePool->save($cacheItem->set($data));
	}
	return $data;
});

// get data from the api
Flight::map('getDataById', function($resourceType, $id){

	$cachePool = Flight::get('cachePool');
	// Get a cache item.
	$cacheItem = $cachePool->getItem($resourceType.'-'.$id);
	// Attempt to get the data
	$data = $cacheItem->get();
	// Check to see if the data was a miss.
	if($cacheItem->isMiss())
	{
		// Let other processes know that this one is rebuilding the data.
		$cacheItem->lock();
		// Run intensive code
		$apiClient = Flight::get('apiClient');
		$response = $apiClient->get('wp/v2/'.$resourceType.'/'.$id );
		$jsonData = $response->getBody();
		$dataObj = json_decode($jsonData, true);

		if (empty($dataObj))
			throw new Exception('No data found for the request');

		$data = Flight::formatData($dataObj);

		// Store the expensive to generate data
		$cachePool->save($cacheItem->set($data));
	}
	return $data;
});


// get data from the api
Flight::map('getMenuById', function($id){

	$cachePool = Flight::get('cachePool');
	// Get a cache item.
	$cacheItem = $cachePool->getItem('menu-'.$id);
	// Attempt to get the data
	$data = $cacheItem->get();
	// Check to see if the data was a miss.
	if($cacheItem->isMiss())
	{
		// Let other processes know that this one is rebuilding the data.
		$cacheItem->lock();
		// Run intensive code
		$apiClient = Flight::get('apiClient');
		$response = $apiClient->get('wp-api-menus/v2/menus/'.$id );
		$jsonData = $response->getBody();
		$dataObj = json_decode($jsonData, true);

		if (empty($dataObj))
			throw new Exception('No data found for the request');

		$data = $dataObj;

		// Store the expensive to generate data
		$cachePool->save($cacheItem->set($data));
	}
	return $data;
});

// Format returned page/post data as we needed.
Flight::map('formatData', function( $data = array() ){
		$formatedData['id'] 		 = $data['id'];
		$formatedData['is_active'] 		 = $data['id'];
		$formatedData['slug']	 	 = $data['slug'];
		$formatedData['type'] 	 = $data['type'];

		if (array_key_exists('title',$data))
			$formatedData['title'] 			= $data['title']['rendered'];

		if (array_key_exists('content',$data))
			$formatedData['content'] 		= $data['content']['rendered'];

		if (array_key_exists('excerpt',$data))
			$formatedData['excerpt'] 		= $data['excerpt']['rendered'];

		if (array_key_exists('acf',$data))
			$formatedData['acf'] 		 		= $data['acf'];

		if (array_key_exists('categories',$data))
			$formatedData['categories']	=	$data['categories'];

		return $formatedData;
});
