<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay05 extends PuzzleBase {

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 5;

    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $seats = $this->gatherSeats();
    $this->render(array_key_last($seats));

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $seats = $this->gatherSeats();
    foreach($seats as $seat_id => $position) {
      // Check the ids ignoring the first row of seats.
      if ($seat_id > 7) {
        if ($seat_id != $last_id + 1) {
          // Return the previous seat id as our detection is trigger once
          // we passed the missing id.
          $this->render($seat_id - 1);
          break;
        }
      }
      $last_id = $seat_id;
    }

  }

  /**
   * Process any range to keep only half of it based on the given letter.
   *
   * @param array $range
   * @param string $letter
   *
   * @return array
   */
  private function processRange(array $range, string $letter) {
    switch ($letter) {
      // Keep the lower half of the range.
      case 'F' :
      case 'L' :
        $this->splitRange($range, 'max');
        break;

      // Keep the upper half of the range.
      case 'B' :
      case 'R' :
        $this->splitRange($range, 'min');
        break;
    }
    return $range;
  }

  /**
   * Split a range in half based on the current min and max values.
   *
   * @param array $range
   * @param string $key
   */
  private function splitRange(array &$range, string $key) {
    $full_range = $range['max'] - $range['min'];

    // Keep the upper or lower part of the range rounding the result
    // up or down accordingly.
    switch ($key) {
      case 'min' :
        $range[$key] = ceil($full_range / 2) + $range['min'];
        break;
      case 'max' :
        $range[$key] = floor($full_range / 2) + $range['min'];
        break;
    }
  }

  /**
   * Walk through the input data to calculate the seat id for each seat.
   *
   * @return array
   */
  private function gatherSeats() {
    $seats = [];

    foreach($this->input as $value) {

      // Process rows number.
      $rows_range = ['min' => 0, 'max' => 127];
      $cols_range = ['min' => 0, 'max' => 7];
      $value = str_split($value);

      foreach($value as $letter) {

        switch ($letter) {
          // Process rows.
          case 'F' :
          case 'B' :
            $rows_range = $this->processRange($rows_range, $letter);
            break;

          // Process cols.
          case 'L' :
          case 'R' :
            $cols_range = $this->processRange($cols_range, $letter);
            break;
        }
      }

      // Store the current seat data.
      $seat_id = $rows_range['min'] * 8 + $cols_range['min'];
      $seats[$seat_id] = [
        'row' => $rows_range['min'],
        'col' => $cols_range['min']
      ];
    }

    // Sort keys before the output.
    ksort($seats);

    return $seats;
  }

}