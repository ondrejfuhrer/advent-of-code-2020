<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day10 extends AdventOfCode
{
	protected function prepareData(): array
	{
		return array_map('intval', $this->data);
	}

	protected function executePart1(array $data): void
	{
		$sortedData = $data;
		sort($sortedData);
		$highestJoltage = array_pop($sortedData);
		$sortedData[] = $highestJoltage;
		println(sprintf('⚡️ Highest adapter joltage: %d', $highestJoltage));
		println(sprintf('⚡️ Highest device joltage: %d', $highestJoltage + 3));

		$currentJoltage = 0;
		$usedAdapters = [];
		$this->calculateUsedAdapters($currentJoltage, $sortedData, $usedAdapters);
		$previous = 0;
		$differencesOfOne = 0;
		$differencesOfThree = 1;
		foreach (array_reverse($usedAdapters) as $adapter) {
			if ($adapter - $previous === 1) {
				$differencesOfOne++;
			} else {
				if ($adapter - $previous === 3) {
					$differencesOfThree++;
				}
			}
			$previous = $adapter;
		}

		println(sprintf('⚡️ Differences of 1: %d', $differencesOfOne));
		println(sprintf('⚡️ Differences of 3: %d', $differencesOfThree));
	}

	private function calculateUsedAdapters(int $currentJoltage, array $adapters, array &$result = []): bool
	{
		if (empty($adapters)) {
			return true;
		}
		$possibleAdapters = $this->findPossibleAdapters($currentJoltage, $adapters);
		foreach ($possibleAdapters as $possibleAdapter) {
			$nextUsableAdapters = array_diff($adapters, [$possibleAdapter]);
			if ($this->calculateUsedAdapters($possibleAdapter, $nextUsableAdapters, $result)) {
				$result[] = $possibleAdapter;

				return true;
			}
		}

		return false;
	}

	private function findPossibleAdapters(int $currentJoltage, array $adapters): array
	{
		$possibleAdapters = [];
		foreach ($adapters as $i => $adapter) {
			if ($adapter > $currentJoltage && $adapter <= $currentJoltage + 3) {
				$possibleAdapters[] = $adapter;
			}
		}

		return $possibleAdapters;
	}

	protected function executePart2(array $data): void
	{
		$sortedData = $data;
		sort($sortedData);
		$currentJoltage = 0;
		$usedAdapters = [];
		$this->calculateUsedAdapters($currentJoltage, $sortedData, $usedAdapters);
		$usedAdapters = array_reverse($usedAdapters);

		$paths = 1;
		foreach ($usedAdapters as $current => $adapter) {
			for ($i = $current; $i < count($usedAdapters); $i++) {
				if (isset($usedAdapters[$i + 2])) {
					if ($usedAdapters[$i + 2] - $adapter <= 3) {
						$paths++;
					}}
				}
				if (isset($usedAdapters[$i + 3]) {

				}
			}
		}
	}
}

$challenge = new Day10('day10.txt');
$challenge->run();
