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

function standard_deviation($values) {
	$mean = array_sum($values) / count($values);
	$variance = 0.0;
	foreach ($values as $value) {
		$variance += ($value - $mean) * ($value - $mean);
	}
	$variance /= count($values);
	return sqrt($variance);
}

/**
 * Outputs the results
 */ 
function report ($statement, $results) {
	echo "$statement\n=====================\n";
	printf("  min     : %.3f seconds\n", min($results));
	printf("  max     : %.3f seconds\n", max($results));
	printf("  average : %.3f seconds\n", (array_sum($results)/count($results)));
	printf("  median  : %.3f seconds\n", $results[round(count($results) / 2) - 1]);
	printf("  sd      : %.3f seconds\n", standard_deviation($results));
	echo "\n";
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


//MAIN
$benchmark = new TimeBenchmark([$withNativeArrayMap, $withForeach, $withArrayWalk, $withArrayIterator], 500);

echo "Mapping an array of 10000 elements 500 times : \n";

// result output, generator version
/*
$benchmark->runGenerator()->next();
report('native implementation', $benchmark->runGenerator()->current());

$benchmark->runGenerator()->next();
report('foreach implementation', $benchmark->runGenerator()->current());

$benchmark->runGenerator()->next();
report('array_walk implementation', $benchmark->runGenerator()->current());

$benchmark->runGenerator()->next();
report('ArrayIterator implementation', $benchmark->runGenerator()->current());
*/
// result output, standard version
$benchmark->run();
$results = $benchmark->getRawResults();
report('native implementation', $results[0]);
report('foreach implementation', $results[1]);
report('array_walk implementation', $results[2]);
report('ArrayIterator implementation', $results[3]);