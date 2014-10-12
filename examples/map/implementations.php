<?php

require_once 'vendor/autoload.php';

use Parkour\Parkour;

function withNativeArrayMap (array $data, callable $mappingFunction) {
	return array_map($mappingFunction, $data);
}

function withParkourMap (array $data, callable $mappingFunction) {
	return Parkour::map($data, $mappingFunction);
}

function withArrayWalk (array $data, callable $mappingFunction) {
	$res = [];
	array_walk($data, function($value,$key) use (&$res, $mappingFunction) {
		$res[$key] = $mappingFunction($value);
	});
	return $res;
}

function withArrayIterator (array $data, callable $mappingFunction) {
	$res = [];
	$obj = new ArrayObject($data);
	$iterator = $obj->getIterator();
	while($iterator->valid()) {
		$res[$iterator->key()] = $mappingFunction($iterator->current());
		$iterator->next();
	}
	return $res;
}

function withForeach (array $data, callable $mappingFunction) {
	$res = [];
	foreach ($data as $k => $value) {
		$res[$k] = $mappingFunction($value);
	}
	return $res;
}