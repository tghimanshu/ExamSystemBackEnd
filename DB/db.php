<?php
$con=mysqli_connect("localhost","root","","");
if($con)
{
    echo "Connection established";
}
else{
    mysqli_error($con);
}
?>