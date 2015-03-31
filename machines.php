<?php
require '../Slim/Slim.php';

if ( $_SERVER['REMOTE_ADDR'] != '192.168.1.147'){
    $local = false;
    include "../inc/database.php";
} else {
    $local = true;
}

$app = new Slim();

/**
 * Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, and `Slim::delete`
 * is an anonymous function. If you are using PHP < 5.3, the
 * second argument should be any variable that returns `true` for
 * `is_callable()`. An example GET route for PHP < 5.3 is:
 *
 * $app = new Slim();
 * $app->get('/hello/:name', 'myFunction');
 * function myFunction($name) { echo "Hello, $name"; }
 *
 */
$app->get('/', 'semaforo' );
$app->get('/all', 'index' );
$app->get('/:id', 'retrieve' );
$app->put('/:id', 'update');
$app->delete('/:id', 'del');
$app->post('/','insert');


function semaforo(){
    global $local;
    $query = file_get_contents('sql/machines.semaforo.sql');
    if($local){
        $json = file_get_contents('filecache.txt');
    } else {
        $DB = new MxApps();
        $DB->setQuery($query);
        $DB->exec();
        $json = $DB->json();
    }
    if ($json == "[]") {
        throw new Exception("No arrojo datos la base de datos", 1);
    } else {
        file_put_contents('filecache.txt', $json);
        echo $json;
    }
}

function index(){
    global $local;
    $query = file_get_contents('sql/machines_test.sql');
    if($local){
        $json = file_get_contents('filecache.txt');
    } else {
        $DB = new MxApps();
        $DB->setQuery($query);
        $DB->exec();
        $json = $DB->json();
    }
    if ($json == "[]") {
        throw new Exception("No arrojo datos la base de datos", 1);
    } else {
        file_put_contents('filecache.txt', $json);
        echo $json;
    }
}

function retrieve($id)
{
    echo file_get_contents('user.' . $id . ".txt");
}

function insert() {

    global $app;
    $body = $app->request()->getBody();
    parse_str($body, $body);
    print_r( $body );    

    $MO = new MxApps();
    $infoQuery = file_get_contents('sql/insert.semaforo.sql');
    $MO->setQuery($infoQuery);
    $MO->bind_vars(':DB_ID',$body['DB_ID']);
    $MO->bind_vars(':NAME',$body['NAME']);
    $MO->bind_vars(':DESCRIPTION',$body['DESCRIPTION']);
    $MO->bind_vars(':AREA',$body['AREA']);
    $MO->bind_vars(':PROCESS',$body['PROCESS']);
    $MO->bind_vars(':DBCONNECTION',$body['DBCONNECTION']);
    $MO->bind_vars(':DBTABLE',$body['DBTABLE']);
    $MO->bind_vars(':DBMACHINE',$body['DBMACHINE']);
    $MO->bind_vars(':DBDEVICE',$body['DBDEVICE']);
    $MO->bind_vars(':DBDATE',$body['DBDATE']);
    $MO->bind_vars(':CICLETIME',$body['CICLETIME']);
    $MO->bind_vars(':BU',$body['BU']);
    echo $MO->query;
    $MO->exec();

}

function update ($id) {
    global $app;
    $body = $app->request()->getBody();
    parse_str($body, $body);
    print_r( $body );


    $MO = new MxApps();
    $infoQuery = file_get_contents('sql/update.semaforo.sql');
    $MO->setQuery($infoQuery);
    $MO->bind_vars(':DB_ID',$body['DB_ID']);
    $MO->bind_vars(':NAME',$body['NAME']);
    $MO->bind_vars(':DESCRIPTION',$body['DESCRIPTION']);
    $MO->bind_vars(':AREA',$body['AREA']);
    $MO->bind_vars(':PROCESS',$body['PROCESS']);
    $MO->bind_vars(':DBCONNECTION',$body['DBCONNECTION']);
    $MO->bind_vars(':DBTABLE',$body['DBTABLE']);
    $MO->bind_vars(':DBMACHINE',$body['DBMACHINE']);
    $MO->bind_vars(':DBDEVICE',$body['DBDEVICE']);
    $MO->bind_vars(':DBDATE',$body['DBDATE']);
    $MO->bind_vars(':CICLETIME',$body['CICLETIME']);
    $MO->bind_vars(':BU',$body['BU']);
    $MO->bind_vars(':ID',$body['ID']);
    echo $MO->query;
    $MO->exec();

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
