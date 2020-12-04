<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay01 extends PuzzleBase {

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 1;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    // Check for each entry if we can find a matching number for
    // an intended sum result of 2020.
    foreach ($this->input as $value) {
      $matching = 2020 - $value;
      if (in_array($matching, $this->input)) {
        $this->render($value . ' + ' . $matching . ' = ' . ($value + $matching) . '<br />');
        $this->render($value . ' * ' . $matching . ' = ' . ($value * $matching) . '<br />');
        break;
      }
    }

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    // Check for any three numbers that sums to 2020.
    // Same logic as before with an additional loop.
    foreach($this->input as $first_value) {
      foreach($this->input as $second_value) {
        if ($second_value != $first_value) {
          $matching = 2020 - ($first_value + $second_value);
          if (in_array($matching, $this->input)) {
            $this->render($first_value . ' + ' . $second_value . ' + ' . $matching . ' = ' . ($first_value + $second_value + $matching) . '<br />');
            $this->render($first_value . ' * ' . $second_value . ' * ' . $matching . ' = ' . ($first_value * $second_value * $matching) . '<br />');
            break 2;
          }
        }
      }
    }
  }

}