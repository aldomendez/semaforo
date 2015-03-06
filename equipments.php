<?php
/*
Esta es la parte de la aplicacion que se encargará de hacer la administracion
de las maquinas en la base de datos, 
*/

include "../inc/database.php";
require 'Slim/Slim.php';
$app = new Slim();

$app->get('/equipments', 'index' );
$app->get('/equipments/:id', 'retrieve' );
$app->put('/equipments/:id', 'put');
$app->delete('/equipments/:id', 'del');
$app->post('/equipments','insert');

function index()
{
    // updateMachinesMxOptix();
    $query = file_get_contents('sql/machines.sql');
    $DB = new MxApps();
    $DB->setQuery($query);
    $DB->exec();
    if ($DB->json() == "[]") {
        throw new Exception("No arrojo datos la base de datos", 1);
    } else {
        echo $DB->json();
    }   
}

function retrieve($id)
{
    // updateMachinesMxOptix();
    $query = file_get_contents('sql/machines.sql');
    $DB = new MxApps();
    $DB->setQuery($query . " where id = '" . $id. "'");
    $DB->exec();
    if ($DB->json() == "[]") {
        $response = array('error' => "No arrojo dator la base de datos" );
        echo json_encode($response);
    } else {
        echo json_encode($DB->results[0]);
    }   
}

function insert() {
    if( file_exists('count.txt') ){
        $lastId = file_get_contents('count.txt');
    } else {
        $lastId = 0;
    }
    $user = json_decode(file_get_contents("php://input"),true);
    $user['id']= $lastId + 1;
    // file_put_contents('count.txt', $lastId+1);
    file_put_contents("user." . ($lastId+1) . ".txt", json_encode($user));
    echo(json_encode($user));
}

function put ($id) {
    global $app;
    $filename = 'user.' . $id . ".txt";
    if( file_exists($filename) ){
        $body = $app->request()->getBody();
        $remote = json_decode($body ,true);
        $local = json_decode(file_get_contents($filename), true);

        foreach ($remote as $key => $value) {
            if ($value != '') {
                $local[$key] = $value;
            }
        }
        $response = json_encode($local);
        file_put_contents($filename, $response);
        echo $response;
    }

}

function del ($id) {
    $filename = 'user.' . $id . ".txt";
    // Eliminar el archivo deberia de ser facil
    // unlink($filename);
    $response = array('eror' => 'no podemos borrar del servidor' );
    echo json_encode($response);
}

function post() {
    echo 'This is a POST route';
}
/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This is responsible for executing
 * the Slim application using the settings and routes defined above.
 */
$app->run();
