<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $PortfolioCompanyID =$_REQUEST['PortfolioCompanyID'];
    $sql="UPDATE PortfolioCompany SET Deleted = 1, DeletedDate = NOW() WHERE PortfolioCompanyID = '$PortfolioCompanyID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    echo '<p style="color:#FF0000;">Portfolio Company Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../AuthViews/portfolio-company.php" );
?>