<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day3 extends AdventOfCode
{
	protected function prepareData(): array
	{
		return array_map(
			static function ($row) {
				return str_split($row);
			},
			$this->data
		);
	}

	protected function executePart1(array $map): void
	{
		println('🛷 Sliding through, part1:');
		println('--------------------------');
		$totalHit = $this->executeCases($map, [[3, 1]]);
		println(sprintf('Total 🎄 hit (multiplied): %d', $totalHit));
		println();
	}

	protected function executePart2(array $map): void
	{
		println('🛷 Sliding through, part2:');
		println('--------------------------');
		$cases = [
			[1, 1],
			[3, 1],
			[5, 1],
			[7, 1],
			[1, 2],
		];

		$totalHit = $this->executeCases($map, $cases);
		println(sprintf('Total 🎄 hit (multiplied): %d', $totalHit));
		println();
		println();
	}

	private function executeCases(array $map, array $cases): int
	{
		$totalCases = count($cases);

		$totalRows = count($map);
		$totalColumns = count($map[0]);
		$totalHit = 1;

		foreach ($cases as $caseNumber => $case) {

			$trees = 0;
			$row = 0;
			$column = 0;
			[$columnStep, $rowStep] = $case;

			do {
				$row += $rowStep;
				$column += $columnStep;
				if ($column >= $totalColumns) {
					$column -= $totalColumns;
				}
				if ('#' === $map[$row][$column]) {
					$trees++;
				}
			} while ($row < $totalRows - 1);

			println(sprintf('[%d/%d] 🎄 hit: %d', $caseNumber + 1, $totalCases, $trees));
			$totalHit *= $trees;
		}

		return $totalHit;
	}
}

$challenge = new Day3('day3.txt');
$challenge->run();
