<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay02 extends PuzzleBase {

  /**
   * Pattern used to explode the password policies string.
   *
   * @var string
   */
  private string $pattern;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE) {
    $this->day = 2;
    $this->pattern = '/[- :]/';
    parent::__construct($load_input);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    // Check the number of occurrences of the policy letter in the password.
    $valid = 0;
    foreach ($this->input as $value) {
      list($min, $max, $letter, $password) = array_values(array_filter(preg_split($this->pattern, $value)));

      $count = substr_count($password, $letter);
      if ($count >= $min && $count <= $max) {
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

    // Check the two positions of the policy in the password to find a single
    // occurrence of the letter at these positions.
    $valid = 0;
    foreach ($this->input as $value) {
      list($first_offset, $second_offset, $letter, $password) = array_values(array_filter(preg_split($this->pattern, $value)));

      $first_letter = substr($password, $first_offset - 1, 1);
      $second_letter = substr($password, $second_offset -1, 1);

      switch($letter) {
        case ($first_letter === $letter && $second_letter !== $letter):
        case ($first_letter !== $letter && $second_letter === $letter):
          $valid++;
          break;
      }
    }
    $this->render($valid);
  }

}