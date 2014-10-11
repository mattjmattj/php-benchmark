<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license THE BEERWARE LICENSE
 */ 

namespace Benchmark;

/**
 * Utility class to benchmark execution time of several functions.
 * Ideally used when benchmarking several implementations of the function.
 */
class TimeBenchmark extends AbstractBenchmark {

	/**
	 * @var int - number of iterations to perform
	 */ 
	private $iterations;
	
	/**
	 * @var Closure[] - array of functions to benchmark
	 */
	private $functions;
	

	/**
	 * @param Closure[] - functions to call. Each Closure must accept a 'begin' and a 'end' callback, and call them around the code to benchmark
	 */ 
	public function __construct (array $functions, $iterations = 100) {
		$this->iterations = $iterations;
		$this->functions = $functions;
	}
	
	private function benchmarkFunction (\Closure $function) {
		$results = [];
		
		$nb = $this->iterations;
		while ($nb--) {
			$begin = null;
			$end = null;
			
			$function(
				function () use (&$begin) {
					$begin = microtime(true);
				},
				function () use (&$end) {
					$end = microtime(true);
				}
			);
			
			if (is_null($begin) ||is_null($end)) {
				throw new Exception("Callbacks not called!");
			}
			
			$results[] = $end-$begin;
		}
		
		return $results;
	}

	/**
	 * Launches the benchmark
	 * @throws Exception
	 */ 
	public function run () {
		$this->initResults();
		foreach ($this->functions as $function) {
			$result = $this->benchmarkFunction ($function);
			$this->results[] = $result;
		}
	}

	/**
	 * TimeBenchmark::run equivalent, but using a generator
	 */ 
	public function runGenerator () {
		$this->initResults();
		foreach ($this->functions as $function) {
			$result = $this->benchmarkFunction ($function);
			$this->results[] = $result;
			yield $result;
		}
	}
	
	
}