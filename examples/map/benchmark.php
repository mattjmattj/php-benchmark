<?php
/**
 * Sample use of TimeBenchmark to benchmark several implementations of array_map
 */ 
 
require_once __DIR__ . '/../../vendor/autoload.php';
 
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

/**
 * This Closure uses the native array_map
 */ 
$withNativeArrayMap = function (\Closure $begin, \Closure $end) use ($mappingFunction) {
	$data = createData();
	$begin();
	
	$res = array_map($mappingFunction, $data);
	
	$end();
};

/**
 * This Closure uses a foreach structure to loop-and-map the array 
 */ 
$withForeach = function (\Closure $begin, \Closure $end) use ($mappingFunction) {
	$data = createData();
	$begin();
	
	$res = [];
	foreach ($data as $key => $value) {
		$res[$key] = $mappingFunction($value);
	}
	
	$end();
};

/**
 * This Closure uses array_walk to loop-and-map the array 
 */ 
$withArrayWalk = function (\Closure $begin, \Closure $end) use ($mappingFunction) {
	$data = createData();
	$begin();
	
	$res = [];
	array_walk($data, function($value,$key) use ($res, $mappingFunction) {
		$res[$key] = $mappingFunction($value);
	});
	
	$end();
};

/**
 * This Closure uses ArrayIterator
 */ 
$withArrayIterator = function (\Closure $begin, \Closure $end) use ($mappingFunction) {
	$data = createData();
	$begin();
	
	$obj = new ArrayObject($data);
	$iterator = $obj->getIterator();
	$res = [];
	while($iterator->valid()) {
		$res[$iterator->key()] = $mappingFunction($iterator->current());
		$iterator->next();
	}
	
	$end();
};

$benchmark = new TimeBenchmark([
	$withNativeArrayMap, 
	$withForeach, 
	$withArrayWalk, 
	$withArrayIterator
], 50);

$benchmark->run();

$distributions = $benchmark->getResultsDistribution(3, 0, 0.03);

return [
	'array_map' => $distributions[0],
	'foreach' => $distributions[1],
	'array_walk' => $distributions[2],
	'ArrayIterator' => $distributions[3],
];