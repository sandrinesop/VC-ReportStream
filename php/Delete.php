<?php 
    include_once('./connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" DELETE FROM investor where InvestorID = '$InvestorID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die ( mysqli_error());
    header("Location: ../index.php"); 
?>