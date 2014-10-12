<?php
/**
 * Sample use of TimeBenchmark to benchmark several implementations of array_map
 */ 
 
require_once __DIR__ . '/../../vendor/autoload.php';
require_once 'implementations.php';
 
use Benchmark\TimeBenchmark;

/**
 * A trivial function to use in each array_map implementation
 */ 
$mappingFunction = function ($value) {
	return $value * $value;
};

/**
 * @return array - the data to use in the 'map' function 
 */ 
function createData() {
	$data = range(1,10000);
	shuffle($data);
	return $data;
}

$benchmark = new TimeBenchmark([
	function($begin, $end) use ($mappingFunction) {
		$data = createData();
		$begin();
		withArrayIterator($data, $mappingFunction);
		$end();
	}, 
	function($begin, $end) use ($mappingFunction) {
		$data = createData();
		$begin();
		withArrayWalk($data, $mappingFunction);
		$end();
	}, 
	function($begin, $end) use ($mappingFunction) {
		$data = createData();
		$begin();
		withParkourMap($data, $mappingFunction);
		$end();
	}, 
	function($begin, $end) use ($mappingFunction) {
		$data = createData();
		$begin();
		withNativeArrayMap($data, $mappingFunction);
		$end();
	},
	function($begin, $end) use ($mappingFunction) {
		$data = createData();
		$begin();
		withForeach($data, $mappingFunction);
		$end();
	},
	
], 50);

$benchmark->run();

$results = $benchmark->getRawResults();

return [
	'ArrayIterator' => $results[0],
	'array_walk' => $results[1],
	'Parkour::map' => $results[2],
	'array_map' => $results[3],
	'foreach' => $results[4],
];