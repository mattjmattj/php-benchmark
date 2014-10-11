<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license THE BEERWARE LICENSE
 */ 

namespace Benchmark;

interface Benchmark {

	/**
	 * Launches the benchmark
	 * @throws Exception
	 */ 
	public function run ();
	
	/**
	 * Equivalent to run, but using a generator
	 */ 
	public function runGenerator ();

}