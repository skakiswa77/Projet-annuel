<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once 'config/database.php';
include_once 'models/user.php';
include_once 'controllers/usercontroller.php';


$database = new Database();
$db = $database->getConnection();


$userController = new UserController($db);


$requestMethod = $_SERVER["REQUEST_METHOD"];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';


switch($requestMethod) {
    case 'POST':
      
        $data = json_decode(file_get_contents("php://input"));
        
        if($endpoint === 'register') {
            echo json_encode($userController->register($data));
        } else if($endpoint === 'login') {
            echo json_encode($userController->login($data));
        } else {
            echo json_encode(["message" => "Invalid endpoint"]);
        }
        break;
    
    case 'GET':
       
        echo json_encode(["message" => "GET method not implemented yet"]);
        break;
    
    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}