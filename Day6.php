<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day6 extends AdventOfCode
{
	protected function prepareData(): array
	{
		$group = 0;
		$groups = [];
		foreach ($this->data as $row) {
			if (empty($row)) {
				$group++;
				continue;
			}

			if (!isset($groups[$group])) {
				$groups[$group] = [];
			}
			$groups[$group][] = str_split($row);
		}

		return $groups;
	}

	protected function executePart1(array $data): void
	{
		$totalAnswers = 0;
		foreach ($data as $group) {
			$allAnswers = array_unique(array_merge(...$group));
			$totalAnswers += count($allAnswers);
		}

		println(sprintf('∑ Sum of answered question counts: %d', $totalAnswers));
	}

	protected function executePart2(array $data): void
	{
		$totalAnswers = 0;
		foreach ($data as $group) {
			if (count($group) === 1) {
				$allAnswers = $group[0];
			} elseif (count($group) === 2) {
				$allAnswers = array_intersect($group[0], $group[1]);
			} else {
				$answers1 = array_shift($group);
				$answers2 = array_shift($group);
				$allAnswers = array_intersect($answers1, $answers2, ...$group);
			}
			$totalAnswers += count($allAnswers);
		}

		println(sprintf('∑ Sum of all answered question counts: %d', $totalAnswers));
	}
}

$challenge = new Day6('day6.txt');
$challenge->run();
