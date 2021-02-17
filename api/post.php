<?php


ini_set("display_errors",0);
ini_set("log_errors",1);
ini_set("error_log", dirname(__FILE__).'/logs/error_log.txt');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-methods,Authorization,X-Requested-With');


include_once './logger.php';
include_once $_SERVER['DOCUMENT_ROOT'] ."/api/config/Database.php";
$database = new Database();
$conn = $database->getMYSQLI();

if(isset($_FILES['uploadFile'])){

    try{

         
    $file = $_FILES['uploadFile'];

    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $fileExt = explode(".",$fileName);
    $fileActualExt = strtolower(end($fileExt));

    $fileFormats = array('jpg','jpeg','png');

    //check whether given file is image or not

    if (in_array($fileActualExt,$fileFormats)){

        if($fileError === 0){

            if($fileSize < 1000000){

                $filePath = uniqid('',true).".".$fileActualExt;
                $fileDestination = './uploads/'.$filePath;
                if(move_uploaded_file($fileTmpName,$fileDestination)){
                    $username = $_POST['username'];
                    $category = $_POST['category'];
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $location = 'http://localhost/api/uploads/'.$filePath;
                    $query = "INSERT INTO images (username,location,category,description,title) VALUES (?,?,?,?,?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssss",$username,$location,$category,$description,$title);
                    $stmt->execute();

                    echo json_encode(array("message"=>"success"));
    
                }
               
            }
            else{

                logError("file size is too big!",$_POST['username']);
                echo json_encode(array( "message" => "file size is too big! please compress it"));
            }

        }
        else{

           logError("excceded maximum fize size for php",$_POST['username']);
           echo  json_encode(array( "message" => "exceeded maximum size"));
        }



    }
    else{

        logError("file format not supported",$_POST['username']);
        echo json_encode(array("message"=>"file format is not supported"));
    }

    }
    catch(Exception $e){

        logError($e->getMessage(),$_POST['username']);
        echo json_encode(array("error" => $e->getMessage()));
    }

}
