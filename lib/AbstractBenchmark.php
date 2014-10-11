<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license THE BEERWARE LICENSE
 */ 

namespace Benchmark;

abstract class AbstractBenchmark implements Benchmark {

	use StatsTrait {
		distribution as private;
	}
	
	/**
	 * @var array
	 */ 
	protected $results;

	protected function initResults () {
		$this->results = [];
	}
	
	/**
	 * @return array - the actual results, untreated
	 */ 
	public function getRawResults () {
		return $this->results;
	}
	
	public function getResultsDistribution ($precision = 0, $min = null, $max = null) {
		$distributions = [];
		foreach ($this->results as $k => $result) {
			$distributions[$k] = $this->distribution($result, $precision, $min, $max);
		}
		return $distributions;
	}
}

