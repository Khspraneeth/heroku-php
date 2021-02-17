
<?php
    $servername='172.17.0.2';
    $username='root';
    $password='test';
    $database='gallery';
   
   $conn=mysqli_connect($servername,$username,$password,$database);
   $username = 'demoacc2';
   $query =   "SELECT location from images where username LIKE '$username'";
   $results = $conn->query($query);
   while( $row = $results->fetch_assoc())
   {
       echo $row['location'];
?>
  <img  src = <?php base64_decode($row['location']) ?> alt ="not loaded">
<?php
   }

?>