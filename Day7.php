<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day7 extends AdventOfCode
{
	protected function prepareData(): array
	{
		$rules = [];
		foreach ($this->data as $rule) {
			$matches = [];
			if (preg_match(
				'/^([\w ]+) bags contain (\d+) ([\w ]+) bag[s]?(, (\d+) ([\w ]+) bag[s]?)?(, (\d+) ([\w ]+) bag[s]?)?(, (\d+) ([\w ]+) bag[s]?)?\.$/',
				$rule,
				$matches
			)) {
				$rules[$matches[1]] = [];
				$i = 2;
				do {
					$rules[$matches[1]][] = ['count' => $matches[$i], 'colour' => trim($matches[$i + 1])];
					$i += 3;
				} while (isset($matches[$i]));
			} elseif (preg_match('/^([\w ]+) bags contain no other bags\.$/', $rule, $matches)) {
				$rules[$matches[1]] = null;
			}
		}

		return $rules;
	}

	protected function executePart1(array $data): void
	{
		$myColour = 'shiny gold';
		$colours[$myColour] = true;
		foreach ($data as $colour => $containment) {
			if ($this->findBag($myColour, $data, $colour, $colours)) {
				$colours[$colour] = true;
			}
		}
		$filteredCache = array_filter($colours);
		$result = count($filteredCache) - 1;
		println(sprintf("🧳 My %s bag can be contained by %d colours.", $myColour, $result));
	}

	protected function executePart2(array $data): void
	{
		$myColour = 'shiny gold';
		$containment = 0;
		foreach ($data[$myColour] as $bags) {
			$containment += $bags['count'] + ($bags['count'] * $this->countBagsInside($bags['colour'], $data));
		}
		println(sprintf("🧳 My %s bag must contain %d other bags.", $myColour, $containment));
	}

	private function countBagsInside(string $colour, array $data): int
	{
		if (null === $data[$colour]) {
			return 0;
		}
		$count = 0;
		foreach ($data[$colour] as $bags) {
			$count += $bags['count'] + ($bags['count'] * $this->countBagsInside($bags['colour'], $data));
		}

		return $count;
	}

	private function findBag(string $colour, array $rules, string $currentColour, array &$colours): bool
	{
		if (isset($colours[$currentColour])) {
			return $colours[$currentColour];
		}

		if (null === $rules[$currentColour]) {
			$colours[$currentColour] = false;

			return false;
		}
		foreach ($rules[$currentColour] as $rule) {
			$result = $this->findBag($colour, $rules, $rule['colour'], $colours);
			if ($result) {
				return true;
			}
		}
		$colours[$currentColour] = false;

		return false;
	}
}

$challenge = new Day7('day7.txt');
$challenge->run();
