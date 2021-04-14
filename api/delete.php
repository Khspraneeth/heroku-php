<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

    if(isset($_POST['location'])){

    //database connection      
    include_once $_SERVER['DOCUMENT_ROOT'] ."/api/config/Database.php";
    $database = new Database();
    $db = $database->getMYSQLI();

    //getting file location
    $location = $_POST['location'];
    $fileLocation = '../gallery/public/pictures/'.$location;
    
    $fileInfo = glob($fileLocation);

    if(!unlink($fileLocation)){
        echo json_encode(array('message'=> 'error occurred'));
    }
    else{
        $query = "DELETE  FROM images where location = '$location' ";

       if( $db->query($query)){
        echo json_encode(array('message'=> 'success'));
       }
       else{
        echo json_encode(array('message'=> 'error occurred'));
       }

    }
 }
}
else{
	echo json_encode(array("message"=>"please ensure the request method is POST"));
} 
// tesing the github.

?>