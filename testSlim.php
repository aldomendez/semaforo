<?php
/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */
require 'Slim/Slim.php';

/**
 * Step 2: Instantiate the Slim application
 *
 * Here we instantiate the Slim application with its default settings.
 * However, we could also pass a key-value array of settings.
 * Refer to the online documentation for available settings.
 */
$app = new Slim();

// $response = $app->request();
/**
 * Step 3: Define the Slim application routes
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
 * The routes below work with PHP >= 5.3.
 */

//GET route
$app->get('/user', 'index' );

$app->get('/user/:id', 'retrieve' );

//PUT route
$app->put('/user/:id', 'put');

//DELETE route
$app->delete('/user/:id', 'del');

//POST route
$app->post('/user','insert');


function index(){
    echo "index";
}

function retrieve($id)
{
    echo file_get_contents('user.' . $id . ".txt");
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
