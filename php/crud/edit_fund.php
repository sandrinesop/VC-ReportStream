<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $FundID =$_REQUEST['FundID'];
    $sql=" SELECT *  FROM Fund where FundID = '$FundID'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    $tempCurrency = $row['CurrencyID'];
    $sql3 = " Select Currency from Currency where CurrencyID = '$tempCurrency' ";
    $result3 = mysqli_query($conn, $sql3) or die($conn->error);
    $row3 = mysqli_fetch_assoc($result3);

    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $FundName                   = $_REQUEST['FundName'];
        $Currency                   = $_REQUEST['Currency'];
        $CommittedCapitalOfFund     = $_REQUEST['CommittedCapitalOfFund'];
        $CommittedCapital           = $_REQUEST['CommittedCapital'];
        $MinimumInvestment          = $_REQUEST['MinimumInvestment'];
        $MaximumInvestment          = $_REQUEST['MaximumInvestment'];


        $update="update Fund set ModifiedDate='uuid()',FundName='".$FundName."', CurrencyID='".$Currency."', CommittedCapitalOfFund='".$CommittedCapitalOfFund."', CommittedCapital='".$CommittedCapital."', MinimumInvestment='".$MinimumInvestment."', MaximumInvestment='".$MaximumInvestment."'";

        mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/portfolio-company.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= ../tabs//portfolio-company.php" );
    }else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>Update Record </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/main.css">
    </head>
    <body>
    <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../index.php"><img style=" width: 80px;" class="home-ico" src="../../resources/DCA_Icon.png" alt="Digital collective africa logo"> VC Reportstream  </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" >Digital Collective Africa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../WebInterface.php">New Deal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main  class="my-5 ">
            <form name="form" method="post" action="" class="form container">
                <input type="hidden" name="new" value="1" />
                <input name="PortfolioCompanyID" type="hidden" value="<?php echo $row['FundID'];?>" />
                <p><input class="form-control col" type="text" name="FundName" placeholder="Enter FundName"  value="<?php echo $row['FundName'];?>" /></p>
                <p><input class="form-control col" type="text" name="Currency" placeholder="Enter Currency"  value="<?php echo $row3['Currency'];?>" /></p>
                <p><input class="form-control col" type="text" name="CommittedCapitalOfFund" placeholder="Enter CommittedCapitalOfFund"  value="<?php echo $row['CommittedCapitalOfFund'];?>" /></p>
                <p><input class="form-control col" type="text" name="CommittedCapital" placeholder="Enter CommittedCapital" value="<?php echo $row['CommittedCapital'];?>"/></p>
                <p><input class="form-control col" type="text" name="MinimumInvestment" placeholder="Enter MinimumInvestment"  value="<?php echo $row['MinimumInvestment'];?>" /></p>
                <p><input class="form-control col" type="text" name="MaximumInvestment" placeholder="Enter MaximumInvestment"  value="<?php echo $row['MaximumInvestment'];?>" /></p>
                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>