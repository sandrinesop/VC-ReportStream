<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $UserDetailID =$_REQUEST['UserDetailID'];
    $sql="UPDATE UserDetail SET Deleted = 1, DeletedDate = NOW() WHERE UserDetailID = '$UserDetailID ' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    echo '<p style="color:#FF0000;">Contact Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../AuthViews/contacts.php" );
?>