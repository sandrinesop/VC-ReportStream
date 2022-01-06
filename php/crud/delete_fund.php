<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $FundID =$_REQUEST['FundID'];
    $sql=" UPDATE Fund SET Deleted = 1, DeletedDate = NOW() WHERE FundID = '$FundID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    echo '<p style="color:#FF0000;">Fund Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../AuthViews/fund.php" );
?>