<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" UPDATE Investor SET Deleted = 1, DeletedDate = NOW() WHERE InvestorID = '$InvestorID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    echo '<p style="color:#FF0000;">Record Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../tabs/investor.php" );
?>