<?php
include('create_connection.php');
echo"<br><h2>Delete Table</h2></br>";


$database='drop Student_details';
$sql1=mysqli_query($con,$database);

if(!$sql1) {
    echo ("Table not deleted".mysqli_query_error());
}
else
{
    echo "Table successfully deleted";
}

?>