<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $FundID =$_REQUEST['FundID'];
    $sql=" DELETE FROM Fund where FundID = '$FundID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    echo '<p style="color:#FF0000;">Record Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../tabs/fund.php" );
?>