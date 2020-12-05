<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day2 extends AdventOfCode
{
	protected function prepareData(): array
	{
		return array_map(
			static function (string $row) {
				$r = explode(':', $row);
				if (count($r) !== 2) {
					echo "Cannot parse row: $row";
					die();
				}

				return [
					'policy' => $r[0],
					'password' => trim($r[1]),
				];
			},
			$this->data
		);
	}

	protected function executePart1(array $data): void
	{
		$validPasswords = 0;
		$invalidPasswords = 0;

		foreach ($data as $row) {
			[$min, $max, $letter] = $this->parsePolicy($row['policy']);
			$occurrence = 0;

			for ($i = 0, $iMax = strlen($row['password']); $i < $iMax; $i++) {
				$currentLetter = $row['password'][$i];
				if ($currentLetter === $letter) {
					$occurrence++;
				}
			}

			$isValid = $occurrence >= $min && $occurrence <= $max;

			$this->printResultCheck($row, $isValid);
			$isValid ? $validPasswords++ : $invalidPasswords++;
		}

		echo "🔐 Policy 1\n";
		$this->printResult($validPasswords, $invalidPasswords, count($data));
		echo "\n\n";
	}

	protected function executePart2(array $data): void
	{
		$validPasswords = 0;
		$invalidPasswords = 0;

		foreach ($data as $row) {
			[$positionOne, $positionTwo, $letter] = $this->parsePolicy($row['policy']);

			$letterAtOne = $row['password'][$positionOne - 1];
			$letterAtTwo = $row['password'][$positionTwo - 1];

			$isValid = ($letter === $letterAtOne && $letter !== $letterAtTwo) ||
				($letter !== $letterAtOne && $letter === $letterAtTwo);

			$this->printResultCheck($row, $isValid);
			$isValid ? $validPasswords++ : $invalidPasswords++;
		}

		echo "🔐 Policy 2\n";
		$this->printResult($validPasswords, $invalidPasswords, count($data));
		echo "\n\n";
	}

	private function printResult(int $validPasswords, int $invalidPasswords, int $totalPasswords): void
	{
		echo "\n💯 Total passwords: ".$totalPasswords;
		echo "\n✅ Valid passwords: ".$validPasswords;
		echo "\n‼️ Invalid passwords: ".$invalidPasswords;
	}

	private function printResultCheck(array $row, bool $isValid): void
	{
		$this->debug(sprintf(
			"Password '%s' test against '%s': %s",
			$row['password'],
			$row['policy'],
			$isValid ? "true" : "false"
		));
	}

	private function parsePolicy(string $policy): array
	{
		$matches = [];
		$isMatching = preg_match('/(\d+)-(\d+) (\w+)/', $policy, $matches);
		if (!$isMatching) {
			println("Policy doesn't match: $policy");
			die();
		}

		return [$matches[1], $matches[2], $matches[3]];
	}
}

$challenge = new Day2('day2.txt');
$challenge->run();
