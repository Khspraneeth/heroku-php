<?php

date_default_timezone_set('Asia/Kolkata');
ini_set("display_errors",0);
ini_set("log_errors",1);
ini_set("error_log", dirname(__FILE__).'/logs/error_log.txt');

include_once './logger.php';

//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

include_once $_SERVER['DOCUMENT_ROOT'] ."/api/config/Database.php";
include_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
use \Firebase\JWT\JWT;

$mySiginingKey = "yahallogoneyouaregone";
$JWT = null;

$headers = apache_request_headers();
if(isset($headers['Authorization']))
$JWT = $headers['Authorization'];

if($JWT){

 try{

      $decoded = JWT::decode($JWT, $mySiginingKey, array('HS256'));

      if($_SERVER['REQUEST_METHOD'] === 'POST'){

         $database = new Database();
         $db = $database->getMYSQLI();
         $name = $decoded->{'username'};
         if(isset($_POST['category']))
         {
          $category = $_POST['category'];
          if($category == "ALL")
          $query = "SELECT * FROM images where username = '$name' ";
          else
          $query = "SELECT * FROM images where username = '$name' and category = '$category'";
         }
         else
         $query = "SELECT * FROM images where username = '$name' ";
      
         $rows = $db->query($query);
         $results = array();
         $number_of_rows = mysqli_num_rows($rows);
         $results['data'] = array();
         $results['categories'] = array();

         // to get categories list
         $query1 = "SELECT category FROM images where username = '$name' ";
         $dbCategories = $db->query($query1);
         $number_of_category_rows = mysqli_num_rows($dbCategories);
        
         if($number_of_rows > 0 || $number_of_category_rows > 0)
         {
            $results['message'] = "success";
            while( $row = $rows->fetch_assoc())
            {
               $item = array('id'=>$row['id'],'location'=>$row['location']
               ,'category' => $row['category']
               ,'title'=>$row['title']
               ,'description'=>$row['description']);
               array_push($results['data'],$item);
            }

            while( $row = $dbCategories->fetch_assoc())
            {
               $item = array('category' => $row['category']);
               array_push($results['categories'],$item);
            }
            
            echo json_encode($results);
         
          }
         else
         {
            echo json_encode(array('message'=> 'no results found'));
         }
             
      }
      else{
         echo json_encode(array("message"=>"please ensure the request method is POST"));
      } 

   }catch (Exception $e){

  
      echo json_encode(array(
          "message" => "Session expired",
          "error" => $e->getMessage()
      ));
  }
}
else{

	echo json_encode(array("message"=> "Error in request."));
}

?>