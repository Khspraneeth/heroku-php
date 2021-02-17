<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization,Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

include_once $_SERVER['DOCUMENT_ROOT'] ."/api/config/Database.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

   $database = new Database();
   $db = $database->getMYSQLI();
   $name = $_POST['username'];
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
   if($number_of_rows > 0)
   {
   	
   	while( $row = $rows->fetch_assoc())
   	{
         $item = array('id'=>$row['id'],'location'=>$row['location']
         ,'category' => $row['category']
         ,'title'=>$row['title']
         ,'description'=>$row['description']);
   		array_push($results['data'],$item);
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
?>