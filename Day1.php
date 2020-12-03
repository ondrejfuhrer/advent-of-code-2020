<?php
require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;

class Day1 extends AdventOfCode
{
	private const TARGET_NUMBER = 2020;

	protected function prepareData(): array
	{
		return array_map('intval', $this->data);
	}

	protected function executePart1(array $numbers): void
	{
		$count = count($numbers);
		foreach ($numbers as $i => $iValue) {
			$current = $iValue;
			if ($current === 0) {
				continue;
			}
			for ($j = $i; $j < $count; $j++) {
				$next = $numbers[$j];
				if ($next === 0) {
					continue;
				}
				if ($current + $next === self::TARGET_NUMBER) {
					echo sprintf("2 numbers, that give %d together are: %d, %d\n", self::TARGET_NUMBER, $current, $next);
					echo sprintf("Multiplying those numbers give: %d\n\n", $current * $next);
				}
			}
		}
	}

	protected function executePart2(array $numbers): void
	{
		$count = count($numbers);
		foreach ($numbers as $i => $iValue) {
			$current = $iValue;
			if ($current === 0) {
				continue;
			}
			for ($j = $i; $j < $count; $j++) {
				$next = $numbers[$j];
				if ($next === 0) {
					continue;
				}
				for ($k = $j; $k < $count; $k++) {
					$third = $numbers[$k];
					if ($third === 0) {
						continue;
					}
					if ($current + $next + $third === self::TARGET_NUMBER) {
						echo sprintf("3 numbers, that give %d together are: %d, %d, %d\n", self::TARGET_NUMBER, $current, $next, $third);
						echo sprintf("Multiplying those numbers give: %d\n\n", $current * $next * $third);
					}
				}
			}
		}
	}
}

$challenge = new Day1('day1.txt');
$challenge->run();
