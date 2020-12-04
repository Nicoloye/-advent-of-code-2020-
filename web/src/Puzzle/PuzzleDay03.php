<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay03 extends PuzzleBase {

  /**
   * Pattern used to explode the password policies string.
   *
   * @var string
   */
  private string $pattern;

  /**
   * Part 1 calculate results, this property is here
   * to avoid reprocessing the thing.
   *
   * @var int
   */
  private int $first_result;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 3;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    // Walk through the map counting trees on a slope movement 1 down, 3 right.
    $this->first_result = $this->countTrees();
    $this->render($this->first_result . '<br />');

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    // Walk through the map counting trees on all different slope movements.
    $slope11 = $this->countTrees(1);
    $slope51 = $this->countTrees(5);
    $slope71 = $this->countTrees(7);
    $slope12 = $this->countTrees(1, 2);
    $this->render('Slope col+1, row+1 : ' . $slope11 . '<br />');
    $this->render('Slope col+3, row+1 : ' . $this->first_result . '<br />');
    $this->render('Slope col+5, row+1 : ' . $slope51 . '<br />');
    $this->render('Slope col+7, row+1 : ' . $slope71 . '<br />');
    $this->render('Slope col+1, row+2 : ' . $slope12 . '<br />');
    $this->render($slope11 . ' * ' . $this->first_result . ' * ' . $slope51 . ' * ' . $slope71 . ' * ' . $slope12 . ' = ' . $slope11 * $this->first_result * $slope51 * $slope71 * $slope12);
  }

  /**
   * Shift position for each row of the map.
   *
   * @param int $position
   * @param int $row_length
   * @param int $offset
   *
   * @return int
   */
  private function nextPosition(int $position, int $row_length, int $offset) {
    $position = $position + $offset;
    if ($position >= $row_length) {
      $position -= $row_length;
    }
    return $position;
  }

  /**
   * Walk through the map with a specific slope defined.
   *
   * @param array $input
   * @param int $col_offset
   * @param int $row_offset
   *
   * @return int
   */
  private function countTrees(int $col_offset = 3, int $row_offset = 1) {
    $trees = 0;
    $position = 0;
    $row_length = strlen($this->input[0]);
    $current_row = 0;

    // Remove initial lines we don't need as the count start
    // after the first movement.
    for ($i = 0; $i < $row_offset; $i++) {
      array_shift($this->input);
    }

    foreach($this->input as $value) {
      // Skip rows according to the row offset.
      if ($row_offset > 1 && $current_row >= $row_offset - 1) {
        $current_row = 0;
        continue;
      }

      // Set col position.
      $position = $this->nextPosition($position, $row_length, $col_offset);

      if (substr($value, $position, 1) === '#') {
        $trees++;
      }

      // Increment the row counter if row offset is more than 1.
      if ($row_offset > 1) {
        $current_row++;
      }
    }
    return $trees;
  }

}