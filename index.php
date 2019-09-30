<?php
require_once 'OpenWeather.php';

$meteo =  new OpenWeather('60ea8053e2377090969762659f2d5029');
//var_dump($meteo->getForeCast('Paris'));
 $m = $meteo->getToDay('Paris');


echo "<ul><li><strong>{$m['city']}</strong></li></li><li>{$m['date']}   Ciel {$m['description']} {$m['temp']}°C</li></ul>";

echo "<hr/>";

$p = $meteo->getForeCast('Kiev');
var_dump($p);