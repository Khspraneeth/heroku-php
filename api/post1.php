<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-methods,Authorization,X-Requested-With');


include_once $_SERVER['DOCUMENT_ROOT'] ."/api/config/Database.php";
include_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
use \Firebase\JWT\JWT;

$mySiginingKey = "yahallogoneyouaregone";
$JWT = null;

$headers = apache_request_headers();
$JWT = $headers['Authorization'];

if($JWT){
    try{

      $decoded = JWT::decode($JWT, $mySiginingKey, array('HS256'));
      $database = new Database();
      $conn = $database->getMYSQLI();
      if(isset($_FILES['uploadFile'])){ 
                    
            $file = $_FILES['uploadFile'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            $fileExt = explode(".",$fileName);
            $fileActualExt = strtolower(end($fileExt));
            $fileFormats = array('jpg','jpeg','png');
            $image = base64_encode(file_get_contents($fileTmpName));
            if (in_array($fileActualExt,$fileFormats)){

                if($fileError === 0){
        
                    if($fileSize < 1000000){
        
                            $username = $_POST['username'];
                            $category = $_POST['category'];
                            $title = $_POST['title'];
                            $description = $_POST['description'];
                            $location = $image;
                            $query = "INSERT INTO images (username,location,category,description,title) VALUES (?,?,?,?,?)";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("sssss",$username,$location,$category,$description,$title);
                            $stmt->execute();
                            echo json_encode(array("message"=>"".$image));
             
                    }
                    else{
                        json_encode(array( "message" => "File size is too big!"));
                    }
        
                }
                else{
                    json_encode(array( "message" => "".$fileError));
                }
            }
            else{
                
                json_encode(array( "message" => "File Format not supported"));
            }
        
      }

    }
    catch (Exception $e){
        echo json_encode(array(
            "message" => "Session expired",
            "error" => $e->getMessage()
        ));
    }
}
else{
    echo "error Occured!!";
}
?>