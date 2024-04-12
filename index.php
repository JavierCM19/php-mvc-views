<?php 

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

spl_autoload_register( function (string $class_name) {

    require "src/" . str_replace("\\", "/", $class_name) . ".php";

});

$router = new Framework\Router;

$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("/{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/products/show", ["controller" => "products", "action" => "show"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");

$params = $router->matchRoute($path);

if ($params === false) {
    throw new PageNotFoundException("No matching route for '$path'.");
}

if (!empty($params["id"])) {
    $id = $params["id"];
} 
else {
    $id = NULL;
}

$action = $params["action"];
$controller = "App\Controllers\\" . ucwords($params["controller"]);

$controller_object = new $controller;

$controller_object->$action($id);
?>