<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day4 extends AdventOfCode
{
	private array $requiredFields = [
		'byr',
		'iyr',
		'eyr',
		'hgt',
		'hcl',
		'ecl',
		'pid',
	];

	protected function prepareData(): array
	{
		return $this->data;
	}

	protected function executePart1(array $data): void
	{
		$passports = $this->parsePassportData($data);
		$validPasswords = 0;
		foreach ($passports as $i => $passport) {
			$entries = [];
			foreach ($passport as $passportData) {
				[$identifier] = $passportData;
				$entries[] = $identifier;
			}

			$isValid = empty(array_diff($this->requiredFields, $entries));
			$this->debug(
				sprintf(
					'🎫 Passport %d:%s',
					$i + 1,
					$isValid ? "\033[1;32m valid \033[0m" : "\033[1;31m invalid \033[0m"
				)
			);
			if ($isValid) {
				$validPasswords++;
			}
		}
		println(sprintf('[1/2] Number of valid 🎫 passports: %d', $validPasswords));
	}

	protected function executePart2(array $data): void
	{
		$passports = $this->parsePassportData($data);
		$validPasswords = 0;
		foreach ($passports as $i => $passport) {
			$missingFields = [];
			$invalidFields = [];
			$this->debug(sprintf('🎫 Passport %d:', $i + 1));
			foreach ($this->getAllFields() as $field) {
				$found = false;
				foreach ($passport as $passportData) {
					[$identifier, $value] = $passportData;
					if ($identifier === $field) {
						$found = true;
						if (!$this->validateValue($identifier, $value)) {
							$invalidFields[] = $identifier;
						}
						break;
					}
				}
				if (!$found) {
					$this->debug(sprintf(' - %s: ‼️', $field));
					$missingFields[] = $field;
				}
			}

			$isValid = empty($missingFields) && empty($invalidFields);
			if ($isValid) {
				$validPasswords++;
			}
			$this->debug(sprintf(' - Result:%s', $isValid ? "\033[1;32m valid \033[0m" : "\033[1;31m invalid \033[0m"));
		}
		println();
		println(sprintf('[2/2] Number of valid 🎫 passports: %d', $validPasswords));
	}

	private function validateValue(string $identifier, $value): bool
	{
		switch ($identifier) {
			case 'byr':
				$result = $this->integerish($value) && $value >= 1920 && $value <= 2002;
				break;
			case 'iyr':
				$result = $this->integerish($value) && $value >= 2010 && $value <= 2020;
				break;
			case 'eyr':
				$result = $this->integerish($value) && $value >= 2020 && $value <= 2030;
				break;
			case 'hgt':
				$matches = [];
				$valid = preg_match('/^(\d+)(cm|in)$/', $value, $matches);
				if (!$valid) {
					$result = false;
				} else if ($matches[2] === 'cm') {
					$result = $matches[1] >= 150 && $matches[1] <= 193;
				} elseif ($matches[2] === 'in') {
					$result = $matches[1] >= 59 && $matches[1] <= 76;
				} else {
					$result = false;
				}
				break;
			case 'hcl':
				$result = preg_match('/^#[0-9a-f]{6}$/', $value);
				break;
			case 'ecl':
				$result = in_array($value, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
				break;
			case 'pid':
				$result = preg_match('/^\d{9}$/', $value);
				break;
			case 'cid':
				// all valid
				$result = true;
				break;
			default:
				println(sprintf('‼️ Unknown identifier: %s', $identifier));
				exit(1);
		}

		$this->debug(sprintf(' - %s: %s %s', $identifier, $value, $result ? '✅' : '❌'));

		return $result;
	}

	private function integerish($value): bool
	{
		return filter_var($value, FILTER_VALIDATE_INT);
	}

	private function parsePassportData($data): array
	{
		$i = 0;
		$passports = [];
		foreach ($data as $row) {
			if (empty($row)) {
				$i++;
				continue;
			}
			if (!isset($passports[$i])) {
				$passports[$i] = [];
			}

			$entries = array_map(
				static function ($entry) {
					return explode(':', $entry);
				},
				explode(' ', $row)
			);
			uasort(
				$entries,
				function ($a, $b) {
					$ia = array_search($a[0], $this->getRequiredFields(), true);
					$ib = array_search($b[0], $this->getRequiredFields(), true);

					return $ia - $ib;
				}
			);
			array_push($passports[$i], ...$entries);
		}

		return $passports;
	}

	private function getRequiredFields(): array
	{
		return $this->requiredFields;
	}

	private function getAllFields(): array
	{
		return $this->requiredFields + ['cid'];
	}
}

$challenge = new Day4('day4.txt');
$challenge->run();
