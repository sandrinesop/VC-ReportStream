<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $DealsID =$_REQUEST['DealsID'];
    $sql="UPDATE Deals SET ModifiedDate = NOW(), Deleted = 1, DeletedDate = NOW() WHERE DealsID = '$DealsID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    echo '<p style="color:#FF0000;">Deal Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../AuthViews/NewDeals.php" );
?>