<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day9 extends AdventOfCode
{
	private array $invalidNumbers = [];

	protected function prepareData(): array
	{
		return array_map('intval', $this->data);
	}

	protected function executePart1(array $data): void
	{
		$this->invalidNumbers = $this->detectInvalidNumbers($data);
		foreach ($this->invalidNumbers as $currentNumber) {
			println(sprintf('🔢 Invalid number detected: %d', $currentNumber));
		}
	}

	protected function executePart2(array $data): void
	{
		[$invalidNumber] = $this->invalidNumbers;
		$dataCount = count($data);
		foreach ($data as $position => $startingNumber) {
			if ($startingNumber >= $invalidNumber) {
				continue;
			}
			$currentValue = $startingNumber;
			$currentRange = [$startingNumber];
			$this->debug(sprintf('Starting number: %d', $startingNumber));
			$this->debug(sprintf('Position: %d', $position));
			for ($i = $position + 1; $i < $dataCount; $i++) {
				$this->debug(sprintf('Adding %d', $data[$i]));
				$currentValue += $data[$i];
				$currentRange[] = $data[$i];
				if ($currentValue > $invalidNumber) {
					$this->debug(sprintf('Too big: %d > %d', $currentValue, $invalidNumber));
					break;
				}

				if ($currentValue === $invalidNumber) {
					println('✅ Range detected.');
					sort($currentRange);
					$min = $currentRange[0];
					$max = $currentRange[count($currentRange) - 1];
					println(sprintf('⬇️ Min value: %d', $min));
					println(sprintf('⬆️ Max value: %d', $max));
					println(sprintf('∑️ Sum: %d', $min + $max));
				}
			}
		}
	}

	private function detectInvalidNumbers(array $data): array
	{
		$result = [];
		$preambleLength = 25;
		$workData = $data;
		$preamble = array_slice($workData, 0, $preambleLength);
		$dataCount = count($workData);
		for ($i = $preambleLength; $i < $dataCount; $i++) {
			$currentNumber = $workData[$i];
			if (!$this->isValidNumber($preamble, $currentNumber)) {
				$result[] = $currentNumber;
			}
			$this->updatePreamble($preamble, $currentNumber);
		}

		return $result;
	}

	private function isValidNumber(array $preamble, int $number): bool
	{
		$preambleCount = count($preamble);
		foreach ($preamble as $index => $first) {
			for ($i = $index + 1; $i < $preambleCount; $i++) {
				$second = $preamble[$i];
				if ($first !== $second && $first + $second === $number) {
					return true;
				}
			}
		}

		return false;
	}

	private function updatePreamble(array &$preamble, int $number): void
	{
		array_shift($preamble);
		$preamble[] = $number;
	}
}

$challenge = new Day9('day9.txt');
$challenge->run();
