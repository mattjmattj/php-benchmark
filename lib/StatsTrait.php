<?php
/**
 * @author Matthias Jouan <matthias.jouan@gmail.com>
 * @license THE BEERWARE LICENSE
 */

namespace Benchmark;
 
trait StatsTrait {

	public function distribution (array $results, $precision = 0, $min = null, $max = null) {
		$increment = pow(10,-$precision);
		
		$min = ($min !== null) ? $min : round(min($results), $precision);
		$max = ($max !== null) ? $max : round(max($results), $precision) + $increment;
		for ($k=$min; $k<$max; $k+=$increment) {
			$distribution["$k"] = 0;
		}

		foreach ($results as $result) {
			$distribution[''.round($result,$precision)]++;
		}
		
		return $distribution;
	}
}
