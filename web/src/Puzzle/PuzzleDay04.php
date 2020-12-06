<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay04 extends PuzzleBase {

  // Set intended keys on passports.
  private $intended_keys;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 4;

    // Set the intended keys on passports.
    $this->intended_keys = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid'];

    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function preprocess(bool $load_input = TRUE, string $input_delimiter = "\n") {
    // Load the input dataset and map it correctly.
    $this->input = $this->helper->getInput($this->day, $input_delimiter);

    $func = function($input) {
      $fields = array_values(array_filter(preg_split("/[ \\n]/", $input)));
      $output = [];
      foreach($fields as $field) {
        [$key, $value] = explode(':', $field);
        $output[$key] = $value;
      }
      return $output;
    };
    $this->input = array_map($func, $this->input);

    // Print day title.
    $this->render($this->helper->printDay($this->day));

    // Process the input.
    $this->processPart1();
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    // Counting valid passports with only required fields existence check.
    $valid = 0;
    foreach($this->input as $fields) {
      if($this->keysExists($this->intended_keys, $fields)) {
        $valid++;
      }
    }
    $this->render($valid);

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    // Counting valid passports with multiple validation criteria.
    $valid = 0;
    foreach($this->input as $fields) {
      // Check fields existence.
      $fields_exist = $this->keysExists($this->intended_keys, $fields);

      // Check date ranges.
      $byr_is_valid = $this->fieldIsInRange($fields, 'byr', 1920, 2002);
      $iyr_is_valid = $this->fieldIsInRange($fields, 'iyr', 2010, 2020);
      $eyr_is_valid = $this->fieldIsInRange($fields, 'eyr', 2020, 2030);

      // Check height ranges according to units.
      $hgt_is_valid = FALSE;
      if (!empty($fields['hgt'])) {
        preg_match('/([0-9]+)([cm|in]+)/', $fields['hgt'], $matches);
        if (count($matches)) {
          switch ($matches[2]) {
            case 'cm' :
              $hgt_is_valid = $this->fieldIsInRange($matches, 1, 150, 193);
              break;
            case 'in' :
              $hgt_is_valid = $this->fieldIsInRange($matches, 1, 59, 76);
              break;
          }
        }
      }

      // Check hair color format.
      $hcl_is_valid = FALSE;
      if (!empty($fields['hcl'])) {
        preg_match('/^#[0-9|a-f]{6}$/', $fields['hcl'], $matches);
        $hcl_is_valid = count($matches);
      }

      // Check eye color values.
      $intended_values = ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'];
      $ecl_is_valid = !empty($fields['ecl']) ? in_array($fields['ecl'], $intended_values) : FALSE;

      // Check passport id length.
      $pid_is_valid = !empty($fields['pid']) ? (strlen($fields['pid']) == 9) : FALSE;

      // Check all conditions.
      if ($fields_exist && $byr_is_valid && $iyr_is_valid && $eyr_is_valid && $hgt_is_valid && $hcl_is_valid && $ecl_is_valid && $pid_is_valid) {
        $valid++;
      }
    }
    $this->render($valid);
  }

  /**
   * Check if given keys exists in a given array.
   *
   * @param array $keys
   * @param array $fields
   *
   * @return bool
   */
  private function keysExists(array $keys, array $fields) {
    return !array_diff_key(array_flip($keys), $fields);
  }

  /**
   * Check whether the field value is defined in a specific range or not.
   *
   * @param array $fields
   * @param string $key
   * @param int $min
   * @param int $max
   *
   * @return bool
   */
  private function fieldIsInRange(array $fields, string $key, int $min, int $max) {
    return !empty($fields[$key]) ? ($fields[$key] >= $min && $fields[$key] <= $max) : FALSE;
  }

}