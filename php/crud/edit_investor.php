<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" SELECT  
                Investor.InvestorID, Investor.Deleted, Investor.DeletedDate, Investor.InvestorName, GROUP_CONCAT(DISTINCT Investor.Website) AS Website, GROUP_CONCAT(DISTINCT FundName) AS FundName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, Note.Note, description.Description, currency.Currency, Investor.ImpactTag, Investor.YearFounded, GROUP_CONCAT(DISTINCT Country) AS Country, Investor.Logo 
            FROM 
                Investor
                -- Joining linking table so that we can access funds linked to investor
            LEFT JOIN 
                FundInvestor 
            ON 
                FundInvestor.InvestorID = Investor.InvestorID
            LEFT JOIN 
                Fund 
            ON 
                Fund.FundID = FundInvestor.FundID
                -- Joining linking table so that we can access Portfolio Companies linked to investor
            LEFT JOIN 
                InvestorPortfolioCompany 
            ON 
                InvestorPortfolioCompany.InvestorID = Investor.InvestorID
            LEFT JOIN 
                PortfolioCompany 
            ON 
                PortfolioCompany.PortfolioCompanyID = InvestorPortfolioCompany.PortfolioCompanyID
                -- Joining linking table so that we can access Notes linked to investor
            LEFT JOIN 
                InvestorNote 
            ON 
                InvestorNote.InvestorID = Investor.InvestorID
            LEFT JOIN 
                Note 
            ON 
                Note.NoteID = InvestorNote.NoteID
            LEFT JOIN 
                currency 
            ON 
                currency.CurrencyID=Investor.CurrencyID
                
            LEFT JOIN 
                description 
            ON 
                description.DescriptionID=Investor.DescriptionID 
            LEFT JOIN 
                country 
            ON 
                country.CountryID = Investor.Headquarters 
            WHERE 
                Investor.Deleted= 0 AND Investor.InvestorID = '$InvestorID'

            GROUP BY InvestorID, Deleted, DeletedDate, InvestorName, Description, Currency, ImpactTag, YearFounded, Logo
            ORDER BY InvestorName;
    "; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    /*   $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
         $tempHeadquarter = $row['Headquarters'];
         $sql2 = " SELECT Country FROM Country WHERE CountryID = '$tempHeadquarter' ";
         $result2 = mysqli_query($conn, $sql2) or die($conn->error);
         $row2 = mysqli_fetch_assoc($result2);
    
        Access the Currency table to get currency name
        $tempCurrency = $row['CurrencyID'];
        $sql3 = " SELECT Currency FROM Currency WHERE CurrencyID = '$tempCurrency'";
        $result3 = mysqli_query($conn, $sql3) or die($conn->error);
        $row3 = mysqli_fetch_assoc($result3);

        Access the Currency table to get currency name
        $tempDescription = $row['DescriptionID'];
        $sql4 = " SELECT Description FROM Description WHERE DescriptionID = '$tempDescription'";
        $result4 = mysqli_query($conn, $sql4) or die($conn->error);
        $row4 = mysqli_fetch_assoc($result4); 
    */
            
    // ACCESSING FUND TO POPULATE DROPDOWN FROM DATABASE
    $sql97 = "   SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC";
    $result97 = mysqli_query($conn, $sql97);

    // ACCESSING FUND TO POPULATE DROPDOWN FROM DATABASE
    $sql98 = "   SELECT DISTINCT 
                    FundName
                FROM 
                    Fund 
                WHERE 
                    FundName IS NOT NULL ORDER BY FundName ASC";
    $result98 = mysqli_query($conn, $sql98);

    // ACCESSING DESCRIPTION TO POPULATE DROPDOWN FROM DATABASE
    $sql99 = "   SELECT DISTINCT 
                    Description
                FROM 
                    Description 
                WHERE 
                    Description IS NOT NULL ORDER BY Description ASC";
    $result99 = mysqli_query($conn, $sql99);

    // ACCESSING CURRENCIES TO POPULATE DROPDOWN FROM DATABASE
    $sql100 = "   SELECT DISTINCT 
                    Currency
                FROM 
                    Currency 
                WHERE 
                    Currency IS NOT NULL ORDER BY Currency ASC";
    $result100 = mysqli_query($conn, $sql100);
    // ACCESSING COUNTRIES TO POPULATE DROPDOWN FROM DATABASE
    $sql101 = "   SELECT DISTINCT 
                    Country
                FROM 
                    Country 
                WHERE 
                    Country IS NOT NULL ORDER BY Country ASC";
    $result101 = mysqli_query($conn, $sql101);

    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $InvestorID             =$_REQUEST['InvestorID'];
        $Currency               =$_REQUEST['Currency'];
        $InvestorName           =$_REQUEST['InvestorName'];
        $FundName               =$_REQUEST['FundName'];
        $PortfolioCompanyName   =$_REQUEST['PortfolioCompanyName'];
        $Website                =$_REQUEST['Website'];
        $Description            =$_REQUEST['Description'];
        $ImpactTag              =$_REQUEST['ImpactTag'];
        $YearFounded            =$_REQUEST['YearFounded'];
        $Headquarters           =$_REQUEST['Headquarters']; 
        // $Logo           =$_REQUEST['Logo'];
        
        // ModifiedDate= DATE()
        // DescriptionID='".$Description."',

        $update="UPDATE Investor SET ModifiedDate = NOW(), CurrencyID = (select Currency.CurrencyID FROM Currency where Currency.Currency = '$Currency'), InvestorName='".$InvestorName."', Website='".$Website."', ImpactTag='".$ImpactTag."', YearFounded='".$YearFounded."', Headquarters=(select country.CountryID FROM country where country.Country = '$Headquarters') where InvestorID='".$InvestorID."'";
        mysqli_query($conn, $update) or die($conn->error);

        // LINK INVESTOR TO FUND 
        $sql4 = " INSERT INTO FundInvestor(FundInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, InvestorID)
        VALUES (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'),(select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'))";
        $query4 = mysqli_query($conn, $sql4);
        if($query4){
            //  DO NOTHING IF SUCCESSFULL
        } else {
            echo 'Oops! There was an error Updating link of Investor to Fund. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }
                  
        // LINK INVESTOR TO PORTFOLIO COMPANY 
        $sql4 = "  INSERT IGNORE INTO 
                        InvestorPortfolioCompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate,InvestorID, PortfolioCompanyID)
                    VALUES 
                        (uuid(), now(), now(), 0, NULL, (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))
        ";
        $query4 = mysqli_query($conn, $sql4);

        if($query4){
            // DO NOTHING IF SUCCESSFULL
        } else {
            echo 'Oops! There was an error Updating link of Investor to Company. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }

        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/investor.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 5;url= ../tabs/investor.php" );
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
                <p>
                    <label for="InvestorName" class="form-label" >Investment Manager Name </label> <br>
                    <input class="form-control col" type="text" name="InvestorName" placeholder="Enter InvestorName"  value="<?php echo $row['InvestorName'];?>" />
                </p>
                <p>
                    <label for="Website" class="form-label" >Website </label> <br>
                    <input class="form-control col" type="text" name="Website" placeholder="Enter Website"  value="<?php echo $row['Website'];?>" />
                </p>
                <p>
                    <label for="FundName" class="form-label" >Fund (s)</label> <br>
                    <select class="form-select" id="FundName" name="FundName" required>
                        <option value="<?php echo $row['FundName'];?>"> <?php echo $row['FundName'];?> </option>
                        <?php
                            while ($row98 = mysqli_fetch_assoc($result98)) {
                                # code...
                                echo "<option>".$row98['FundName']."</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>
                    <label for="PortfolioCompanyName" class="form-label" >Portfolio Company List</label> <br>
                    <select class="form-select" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                        <option value="<?php echo $row['PortfolioCompanyName'];?>"> <?php echo $row['PortfolioCompanyName'];?> </option>
                        <?php
                            while ($row97 = mysqli_fetch_assoc($result97)) {
                                # code...
                                echo "<option>".$row97['PortfolioCompanyName']."</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>
                    <label for="Description" class="form-label" >Description</label> <br>
                    <select class="form-select" id="Description" name="Description" required>
                        <option value="<?php echo $row['Description'];?>"> <?php echo $row['Description'];?> </option>
                        <?php
                            while ($row99 = mysqli_fetch_assoc($result99)) {
                                # code...
                                echo "<option>".$row99['Description']."</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>
                    <label for="Currency" class="form-label" >Currency</label> <br>
                    <select class="form-select" id="Currency" name="Currency" required>
                        <option value="<?php echo $row['Currency'];?>"> <?php echo $row['Currency'];?> </option>
                        <?php
                            while ($row100 = mysqli_fetch_assoc($result100)) {
                                # code...
                                echo "<option>".$row100['Currency']."</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>
                    <label for="ImpactTag" class="form-label" >ImpactTag</label> <br>
                    <input class="form-control col" type="text" name="ImpactTag" placeholder="Enter ImpactTag"  value="<?php echo $row['ImpactTag'];?>" />
                </p>
                <p>
                    <label for="YearFounded" class="form-label" >Year Founded</label> <br>
                    <input class="form-control col" type="text" name="YearFounded" placeholder="Enter YearFounded"  value="<?php echo $row['YearFounded'];?>" />
                </p>
                <p>
                    <label for="Headquarters" class="form-label" >Country</label> <br>
                    <select class="form-select" id="Headquarters" name="Headquarters" required>
                        <option value="<?php echo $row['Country'];?>"><?php echo $row['Country'];?> </option>
                        <?php
                            while ($row101 = mysqli_fetch_assoc($result101)) {
                                # code...
                                echo "<option>".$row101['Country']."</option>";
                            }
                        ?>
                    </select>
                </p>
                <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label for="img" class="form-label">Logo</label>
                    <input type="file" class="form-control" id="img" name="img" >
                </p>
                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>
