<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license THE BEERWARE LICENSE
 */ 
 
use Benchmark\StatsTrait;

class StatsTraitTest extends \PHPUnit_Framework_TestCase {

	private $stats;

	public function setup() {
		$this->stats = new \Stats();
	}

	public function testDistribution() {
		
		$data = [ 0, 0, 0, 1, 2, 2, 4, 5, 5, 5, 6 ];
		$expected = [ 3, 1, 2, 0, 1, 3, 1];
		$this->assertEquals($expected, $this->stats->distribution($data));
		
		$data = [ 2, 2, 4, 5, 5, 5, 6 ];
		$expected = [ 2 => 2, 0, 1, 3, 1];
		$this->assertEquals($expected, $this->stats->distribution($data));
		
		$data = [ 0.19, 0.21, 0.4, 0.5, 0.52, 0.53, 0.6109 ];
		$expected = [ 
			'0.2' => 2, 
			'0.3' => 0,
			'0.4' => 1,
			'0.5' => 3,
			'0.6' => 1
		];
		$this->assertEquals($expected, $this->stats->distribution($data,1));
		
		
		$data = [ 2, 2, 4, 5, 5, 5, 6 ];
		$expected = [ 0, 0, 2, 0, 1, 3, 1];
		$this->assertEquals($expected, $this->stats->distribution($data,0,0));
	}
}

class Stats {
	use StatsTrait;
}
