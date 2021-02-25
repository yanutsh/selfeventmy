<?php 
function showDate($time) { // Определяем количество и тип единицы измерения
  $time = time() - $time;
  if ($time < 60) {
    return 'меньше минуты назад';
  } elseif ($time < 3600) {
    return dimension((int)($time/60), 'i');
  } elseif ($time < 86400) {
    return dimension((int)($time/3600), 'G');
  } elseif ($time < 2592000) {
    return dimension((int)($time/86400), 'j');
  } elseif ($time < 31104000) {
    return dimension((int)($time/2592000), 'n');
  } elseif ($time >= 31104000) {
    return dimension((int)($time/31104000), 'Y');
  }
}

function dimension($time, $type) { // Определяем склонение единицы измерения
  $dimension = array(
    'n' => array('месяцев', 'месяц', 'месяца', 'месяц'),
    'j' => array('дней', 'день', 'дня'),
    'G' => array('часов', 'час', 'часа'),
    'i' => array('минут', 'минуту', 'минуты'),
    'Y' => array('лет', 'год', 'года')
  );
    if ($time >= 5 && $time <= 20)
        $n = 0;
    else if ($time == 1 || $time % 10 == 1)
        $n = 1;
    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
        $n = 2;
    else
        $n = 0;
    return $time.' '.$dimension[$type][$n]. ' назад';

}