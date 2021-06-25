<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $PortfolioCompanyID =$_REQUEST['PortfolioCompanyID'];
    $sql=" SELECT *  FROM PortfolioCompany where PortfolioCompanyID = '$PortfolioCompanyID'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    $tempHeadquarter = $row['Headquarters'];
    $sql2 = " Select Country from Country where CountryID = '$tempHeadquarter' ";
    $result2 = mysqli_query($conn, $sql2) or die($conn->error);
    $row2 = mysqli_fetch_assoc($result2);
    // echo 'Headquaters =>'.$row2['Country'];
    // $country = $row2['Country'];

    $tempCurrency = $row['CurrencyID'];
    $sql3 = " Select Currency from Currency where CurrencyID = '$tempCurrency' ";
    $result3 = mysqli_query($conn, $sql3) or die($conn->error);
    $row3 = mysqli_fetch_assoc($result3);

    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $PortfolioCompanyName    = $_REQUEST['PortfolioCompanyName'];
        $Currency                = $_REQUEST['Currency'];
        $Website                 = $_REQUEST['Website'];
        $TotalInvestmentValue    = $_REQUEST['TotalInvestmentValue'];
        $Stake                   = $_REQUEST['Stake'];
        $Details                 = $_REQUEST['Details'];
        $YearFounded             = $_REQUEST['YearFounded'];
        $Headquarters            = $_REQUEST['Headquarters'];
        // $Logo                    = $_REQUEST['Logo'];


        $update="UPDATE PortfolioCompany SET ModifiedDate='uuid()',PortfolioCompanyName='".$PortfolioCompanyName."', CurrencyID = (SELECT C.CurrencyID FROM currency C WHERE C.Currency = '$Currency' ), Website='".$Website."', TotalInvestmentValue='".$TotalInvestmentValue."', Stake='".$Stake."', Details='".$Details."', YearFounded='".$YearFounded."', Headquarters=(select country.CountryID FROM country where country.Country = '$Headquarters') WHERE PortfolioCompanyID='".$PortfolioCompanyID."'";

        mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/portfolio-company.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= ../tabs/portfolio-company.php" );
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
                <input name="PortfolioCompanyID" type="hidden" value="<?php echo $row['PortfolioCompanyID'];?>" />
                <!-- <p><input class="form-control col" type="text" name="ModifiedDate" required value="<?php echo $row['ModifiedDate'];?>" /></p> -->
                <p><input class="form-control col" type="text" name="PortfolioCompanyName" placeholder="Enter PortfolioCompanyName"  value="<?php echo $row['PortfolioCompanyName'];?>" /></p>
                <p><input class="form-control col" type="text" name="Website" placeholder="Enter Website"  value="<?php echo $row['Website'];?>" /></p>
                <p><textarea class="form-control col" type="text" name="Details" placeholder="Enter Details"   > <?php echo $row['Details'];?></textarea></p>
                <p><input class="form-control col" type="text" name="Currency" placeholder="Enter Currency"  value="<?php echo $row3['Currency'];?>" /></p>
                <p><input class="form-control col" type="text" name="TotalInvestmentValue" placeholder="Enter TotalInvestmentValue"  value="<?php echo $row['TotalInvestmentValue'];?>" /></p>
                <p><input class="form-control col" type="text" name="Stake" placeholder="Enter Stake"  value="<?php echo $row['Stake'];?>" /></p>
                <p><input class="form-control col" type="text" name="YearFounded" placeholder="Enter YearFounded"  value="<?php echo $row['YearFounded'];?>" /></p>
                <p><input class="form-control col" type="text" name="Headquarters" placeholder="Enter Headquarters"  value="<?php echo $row2['Country'];?>" /></p>
                <!-- <?php echo $country;?> -->
                <!-- <p><input class="form-control col" type="file" name="Logo" placeholder="Enter Logo"  value="<?php echo $row['Logo'];?>" /></p> -->

                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>