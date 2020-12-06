<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay06 extends PuzzleBase {

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 6;

    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function preprocess(bool $load_input = TRUE, string $input_delimiter = "\n") {
    // Load the input dataset and map it correctly.
    $this->input = $this->helper->getInput($this->day, $input_delimiter);

    // Separate each set of data in a group.
    foreach($this->input as $key => $value) {
      $this->input[$key] = explode("\n", $value);
    }

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

    $total = 0;
    foreach($this->input as $group) {

      // Walk through all answers
      $answers = [];
      foreach($group as $group_answers) {
        $letters = str_split($group_answers);

        // Gather all answers.
        foreach ($letters as $letter) {
          $answers[$letter] = TRUE;
        }
      }
      // Increase total with the current count of gathered answers
      $total += count($answers);
    }
    $this->render($total);

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $total = 0;
    foreach($this->input as $group) {
      // Walk through all answers
      $answers = [];
      foreach($group as $group_answers) {
        $letters = str_split($group_answers);

        // Count all answers.
        foreach ($letters as $letter) {
          $answers[$letter] = !empty($answers[$letter]) ? $answers[$letter] + 1 : 1;
        }
      }

      // Only increase total if the answers count equals the number of people
      // in the group, therefore if this answer is common.
      foreach ($answers as $answers_count) {
        if ($answers_count == count($group)) {
          $total++;
        }
      }
    }
    $this->render($total);
  }



}