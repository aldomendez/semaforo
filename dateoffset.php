<?php
/*
Esta es la parte de la aplicacion que se encargará de hacer la administracion
de las maquinas en la base de datos, 
*/

include "../inc/database.php";
require 'Slim/Slim.php';
$app = new Slim();

$app->get('/', 'dateoffset');


function dateoffset()
{
    echo date("YmdHis");
}

$app->run();
