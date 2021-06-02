<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" SELECT *  FROM Investor where InvestorID = '$InvestorID'"; 
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    $tempHeadquarter = $row['Headquarters'];
    $sql2 = " SELECT Country FROM Country WHERE CountryID = '$tempHeadquarter' ";
    $result2 = mysqli_query($conn, $sql2) or die($conn->error);
    $row2 = mysqli_fetch_assoc($result2);
    // echo 'Headquaters =>'.$row2['Country'];
    // $country = $row2['Country'];
    
    // Access the Currency table to get currency name
    $tempCurrency = $row['CurrencyID'];
    $sql3 = " SELECT Currency FROM Currency WHERE CurrencyID = '$tempCurrency'";
    $result3 = mysqli_query($conn, $sql3) or die($conn->error);
    $row3 = mysqli_fetch_assoc($result3);

    // Access the Currency table to get currency name
    $tempDescription = $row['DescriptionID'];
    $sql4 = " SELECT Description FROM Description WHERE DescriptionID = '$tempDescription'";
    $result4 = mysqli_query($conn, $sql4) or die($conn->error);
    $row4 = mysqli_fetch_assoc($result4);

    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $InvestorID     =$_REQUEST['InvestorID'];
        $ModifiedDate   =$_REQUEST['ModifiedDate'];
        $InvestorName   =$_REQUEST['InvestorName'];
        $Website        =$_REQUEST['Website'];
        $Description    =$_REQUEST['Description'];
        $ImpactTag      =$_REQUEST['ImpactTag'];
        $YearFounded    =$_REQUEST['YearFounded'];
        $Headquarters   =$_REQUEST['Headquarters']; 
        $Logo           =$_REQUEST['Logo'];
        

        // DescriptionID='".$Description."',
        $update="UPDATE Investor SET ModifiedDate='".$ModifiedDate."',InvestorName='".$InvestorName."', Website='".$Website."', ImpactTag='".$ImpactTag."', YearFounded='".$YearFounded."', Headquarters='".$Headquarters."', Logo='".$Logo."' where InvestorID='".$InvestorID."'";
        mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/investor.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= ../tabs/investor.php" );
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
                <input name="InvestorID" type="hidden" value="<?php echo $row['InvestorID'];?>" />
                <p><input class="form-control col" type="text" name="ModifiedDate" required value="<?php echo $row['ModifiedDate'];?>" /></p>
                <p><input class="form-control col" type="text" name="Currency" placeholder="Enter Currency"  value="<?php echo $row3['Currency'];?>" /></p>
                <p><input class="form-control col" type="text" name="InvestorName" placeholder="Enter InvestorName"  value="<?php echo $row['InvestorName'];?>" /></p>
                <p><input class="form-control col" type="text" name="Website" placeholder="Enter Website"  value="<?php echo $row['Website'];?>" /></p>
                <p><input class="form-control col" type="text" name="Description" placeholder="Enter Description"  value="<?php echo $row4['Description'];?>" /></p>
                <p><input class="form-control col" type="text" name="ImpactTag" placeholder="Enter ImpactTag"  value="<?php echo $row['ImpactTag'];?>" /></p>
                <p><input class="form-control col" type="text" name="YearFounded" placeholder="Enter YearFounded"  value="<?php echo $row['YearFounded'];?>" /></p>
                <p><input class="form-control col" type="text" name="Headquarters" placeholder="Enter Headquarters"  value="<?php echo $row2['Country'];?>" /></p>
                <!-- <?php echo $country;?> -->
                <p><input class="form-control col" type="text" name="Logo" placeholder="Enter Logo"  value="<?php echo $row['Logo'];?>" /></p>

                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>
