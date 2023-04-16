<?php
  $date = getdate();
  $data = [
    'month' => $date['month'],
    'year' => $date['year']
  ];
  if (empty($_GET['month']) || empty($_GET['year']) || is_int($_GET['year']) || strlen($_GET['year']) !== 4) {
    $href = $_SERVER['SCRIPT_NAME'] . '?' . http_build_query($data);
    header("Location: $href");
  }
  $months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];

  function get_calendar ($month, $year) {
    // get information about the month
    $date = getdate(strtotime("$month $year"));
    $week = ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'];
    // create an array for the calendar
    $cal[] = $week;
    $row = [];
    // add empty days to the first week
    while (count($row) + 1 < $date['wday'] && $date['wday'] !== 1) {
      $row[] = '';
    }
    if (count($row) === 0 && $date['wday'] === 0) {
      for ($y = 1; $y < 7; $y++) {
        $row[] = '';
      }
    }
    // add more weeks
    for ($i = 1; $i <= 31; $i++) {
      // data validation
      if (!checkdate($date['mon'], $i, $year)) {
        continue;
      }
      // add the week to the calendar
      if (count($row) < 7) {
        $row[] = $i;
      } else {
        $cal[] = $row;
        $row = [$i];
      }
    }
    // add empty days to the last week
    while (count($row) < 7) {
      $row[] = '';
    }
    // add the last week to the calendar
    $cal[] = $row;
    return $cal;
  }

  $cal = get_calendar ($_GET['month'], $_GET['year']);
?>

<!DOCTYPE html>
<html lang='eu'>
  <head>
    <meta charset='utf-8'>
    <title>Календарь</title>
    <link rel='stylesheet' href='css/style.css'>
  </head>
  <body>
    <div class='container'>
      <form class='form-choice' action='<?= $_SERVER['SCRIPT_NAME'] ?>' method='get'>
        <h1 class='title'>Choise the month</h1>
        <div class='box'>
          <div class='item'>
            <p class='text'>Month</p>
            <select class='select' name='month'>
              <?php foreach ($months as $month) : ?>
                <?php if ($month === $_GET['month']): ?>
                  <option value='<?= $month ?>' selected><?= $month ?></option>
                <?php else: ?>
                  <option value='<?= $month ?>'><?= $month ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class='item'>
            <p class='text'>Year</p>
            <input type='text' name='year' value='<?= $_GET['year'] ?>' class='input'>
          </div>
        </div>
        <button class='btn' type='submit'>Create</button>
      </form>
      <table class='calendar'>
        <?php foreach ($cal as $week): ?>
          <tr>
            <?php foreach ($week as $day): ?>
              <td><?= $day ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </body>
</html>
