<?php

$tiempoInicio = microtime(true);
$data = [];


for($i = 0; $i<=200000; $i++){
    array_push($data,['data' => 5]);
}


$tiempoFin = microtime(true);



$tiempoTotal = $tiempoFin - $tiempoInicio;


echo "El proceso tardó aproximadamente {$tiempoTotal} segundos. \n";