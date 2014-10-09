<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license THE BEERWARE LICENSE
 */ 
 
use Benchmark\TimeBenchmark;

class TimeBenchmarkTest extends \PHPUnit_Framework_TestCase {

	public function testRun() {
		
		$fn1_count = 0;
		$fn1 = function($begin, $end) use (&$fn1_count) {
			$begin();
			$fn1_count++;
			$end();
		};
		
		$fn2_count = 0;
		$fn2 = function($begin, $end) use (&$fn2_count) {
			$begin();
			$fn2_count++;
			$end();
		};
		
		$benchmark = new TimeBenchmark([$fn1,$fn2], 30);
		$benchmark->run();
		$results = $benchmark->getRawResults();
		
		$this->assertEquals(2, count($results));
		$this->assertEquals(30, count($results[0]));
		$this->assertEquals(30, count($results[1]));
		$this->assertEquals(30, $fn1_count);
		$this->assertEquals(30, $fn2_count);
		
		foreach ($results as $fnResults) {
			foreach ($fnResults as $result) {
				//each result is a time in seconds
				$this->assertTrue(is_double($result));
			}
		}
	}
	
	/**
	 * @expectedException Benchmark\Exception
	 */
	public function testRunShouldThrowWhenBeginCallbacksAreNotCalled() {
		
		$fn = function($begin, $end) {
			$end();
		};

		$benchmark = new TimeBenchmark([$fn]);
		$benchmark->run();
	}
	
	/**
	 * @expectedException Benchmark\Exception
	 */
	public function testRunShouldThrowWhenEndCallbacksAreNotCalled() {
		
		$fn = function($begin, $end) {
			$begin();
		};

		$benchmark = new TimeBenchmark([$fn]);
		$benchmark->run();
	}
	
	public function testRunIsAGenerator() {
		
		$fn1_count = 0;
		$fn1 = function($begin, $end) use (&$fn1_count) {
			$begin();
			$fn1_count++;
			$end();
		};
		
		$fn2_count = 0;
		$fn2 = function($begin, $end) use (&$fn2_count) {
			$begin();
			$fn2_count++;
			$end();
		};
		
		$benchmark = new TimeBenchmark([$fn1,$fn2], 30);
		foreach($benchmark->runGenerator() as $results) {
			$this->assertEquals(30, count($results));
		}
		
	}
}
