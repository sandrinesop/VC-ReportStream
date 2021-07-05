<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $PortfolioCompanyID =$_REQUEST['PortfolioCompanyID'];
    $sql=" SELECT DISTINCT
            portfoliocompany.PortfolioCompanyID,portfoliocompany.Deleted, portfoliocompany.DeletedDate, portfoliocompany.PortfolioCompanyName, currency.Currency, portfoliocompany.Website, GROUP_CONCAT(DISTINCT Industry) AS Industry, GROUP_CONCAT(DISTINCT Sector) AS Sector, portfoliocompany.TotalInvestmentValue, portfoliocompany.Stake, portfoliocompany.Details, portfoliocompany.YearFounded, country.Country, portfoliocompany.Logo, UserDetail.UserFullName, gender.Gender, race.Race
            FROM 
                portfoliocompany 
            LEFT JOIN 
                currency 
            ON 
                currency.CurrencyID = portfoliocompany.CurrencyID 
            LEFT JOIN 
                country 
            ON 
                country.CountryID = portfoliocompany.Headquarters 
            LEFT JOIN 
                PortfolioCompanyIndustry 
            ON 
                PortfolioCompanyIndustry.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Industry 
            ON 
                Industry.IndustryID = PortfolioCompanyIndustry.IndustryID
            LEFT JOIN 
                PortfolioCompanySector
            ON 
                PortfolioCompanySector.PortfolioCompanyID = portfoliocompany.PortfolioCompanyID
            LEFT JOIN 
                Sector 
            ON 
                Sector.SectorID = PortfolioCompanySector.SectorID
            LEFT JOIN 
                PortfolioCompanyUserDetail
            ON 
                PortfolioCompanyUserDetail.portfoliocompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                UserDetail
            ON 
                UserDetail.UserDetailID = PortfolioCompanyUserDetail.UserDetailID
            LEFT JOIN 
                RoleType
            ON 
                RoleType.RoleTypeID = UserDetail.RoleTypeID
            LEFT JOIN 
                gender
            ON
                gender.GenderID = userdetail.GenderID
            LEFT JOIN 
                race 
            ON 
                race.RaceID =userdetail.RaceID
            
            WHERE 
                PortfolioCompany.PortfolioCompanyID = '$PortfolioCompanyID'
            
            GROUP BY portfoliocompany.PortfolioCompanyID,portfoliocompany.Deleted, portfoliocompany.DeletedDate, portfoliocompany.PortfolioCompanyName, currency.Currency, portfoliocompany.Website, portfoliocompany.TotalInvestmentValue, portfoliocompany.Stake, portfoliocompany.Details, portfoliocompany.YearFounded, country.Country, portfoliocompany.Logo
    "; 
    $result = mysqli_query($conn, $sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    /* 
        $tempHeadquarter = $row['Headquarters'];
        $sql2 = " Select Country from Country where CountryID = '$tempHeadquarter' ";
        $result2 = mysqli_query($conn, $sql2) or die($conn->error);
        $row2 = mysqli_fetch_assoc($result2);
        echo 'Headquaters =>'.$row2['Country'];
        $country = $row2['Country'];
    */
    // PULLING DATA INTO THE DROPDOWN ON THE EDIT/UPDATE SCREEN
    $sql100 = "   SELECT DISTINCT 
        Currency
    FROM 
        Currency 
    WHERE 
        Currency IS NOT NULL ORDER BY Currency ASC";
    $result100 = mysqli_query($conn, $sql100);

    // COUNTRIES
    $sql101 = "   SELECT DISTINCT 
                    Country
                FROM 
                    Country 
                WHERE 
                    Country IS NOT NULL ORDER BY Country ASC";
    $result101 = mysqli_query($conn, $sql101);
    // INDUSTRY
    // $sql99 = "   SELECT DISTINCT 
    //     Industry
    // FROM 
    //     Industry 
    // WHERE 
    //     Industry IS NOT NULL ORDER BY Industry ASC";
    // $result99 = mysqli_query($conn, $sql99);


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
        $Industry                = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        // $Logo                    = $_FILES['img']['name'];
        // ,Logo='".$Logo."'
        // Company Logo Insert code
        // $Logo = addslashes(file_get_contents($_FILES["img"]["tmp_name"]));

        $update="UPDATE PortfolioCompany SET ModifiedDate= NOW(),PortfolioCompanyName='".$PortfolioCompanyName."', CurrencyID = (SELECT C.CurrencyID FROM currency C WHERE C.Currency = '$Currency' ), Website='".$Website."', TotalInvestmentValue='".$TotalInvestmentValue."', Stake='".$Stake."', Details='".$Details."', YearFounded='".$YearFounded."', Headquarters=(select country.CountryID FROM country where country.Country = '$Headquarters') WHERE PortfolioCompanyID='".$PortfolioCompanyID."'";

        mysqli_query($conn, $update) or die($conn->error);
        $status = "Record Updated Successfully. </br></br>
        <a href='../tabs/portfolio-company.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 5;url= ../tabs/portfolio-company.php" );
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
        <link rel="stylesheet" href="../../css/select2.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
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
                <div class="row">
                    <input type="hidden" name="new" value="1" />
                    <input name="PortfolioCompanyID" type="hidden" value="<?php echo $row['PortfolioCompanyID'];?>" />
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="PortfolioCompanyName" class="form-label">Portfolio Company Name</label>
                        <input class="form-control col" type="text" name="PortfolioCompanyName" placeholder="Enter PortfolioCompanyName"  value="<?php echo $row['PortfolioCompanyName'];?>" />
                    </p>
                    <!-- Currency Dropdown -->
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Currency" class="form-label">Currency</label>
                        <select class="form-select" id="Currency" name="Currency" required>
                            <option> Select Currency...</option>
                            <?php
                                while ($row100 = mysqli_fetch_assoc($result100)) {
                                    # code...
                                    echo "<option>".$row100['Currency']."</option>";
                                }
                            ?>
                        </select>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Website" class="form-label">Website</label>
                        <input class="form-control col" type="text" name="Website" placeholder="Enter Website"  value="<?php echo $row['Website'];?>" />
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="TotalInvestmentValue" class="form-label">Total Investment Value</label>
                        <input class="form-control col" type="text" name="TotalInvestmentValue" placeholder="Enter TotalInvestmentValue"  value="<?php echo $row['TotalInvestmentValue'];?>" />
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Industry" class="form-label">Industry</label>
                        <select id="Industry" name="Industry" class="form-select">
                            <option>choose...</option>
                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                            <option value="Clothing Apparel">Clothing Apparel</option>
                            <option value="Administrative Services">Administrative Services</option>
                            <option value="Advertising">Advertising</option>
                            <option value="Agriculture and Farming">Agriculture and Farming</option>
                            <option value="Apps">Apps</option>
                            <option value="Biotechnology">Biotechnology</option>
                            <option value="Commerce and Shopping">Commerce and Shopping</option>
                            <option value="Community and Lifestyle">Community and Lifestyle</option>
                            <option value="Consumer Electronics">Consumer Electronics</option>
                            <option value="Consumer Goods">Consumer Goods</option>
                            <option value="Content and Publishing">Content and Publishing</option>
                            <option value="Data and Analytics">Data and Analytics</option>
                            <option value="Design">Design</option> 
                            <option value="Education">Education</option>
                            <option value="Energy">Energy</option>
                            <option value="Events">Events</option>
                            <option value="Financial Services">Financial Services</option>
                            <option value="Food and Beverage">Food and Beverage</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Government and Military">Government and Military</option>
                            <option value="Hardware">Hardware</option>
                            <option value="Health Care">Health Care</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Internet Services">Internet Services</option>
                            <option value="Lending and Investments">Lending and Investments</option>
                            <option value="Manufacturing">Manufacturing</option>
                            <option value="Media and Entertainment">Media and Entertainment</option>
                            <option value="Messaging and Telecommunications">Messaging and Telecommunications</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Music and Audio">Music and Audio</option>
                            <option value="Natural Resources">Natural Resources</option>
                            <option value="Navigation and Mapping">Navigation and Mapping</option>
                            <option value="Payments">Payments</option>
                            <option value="Platforms">Platforms</option>
                            <option value="Privacy and Security">Privacy and Security</option>
                            <option value="Professional Services">Professional Services</option>
                            <option value="Real Estate">Real Estate</option>
                            <option value="Sales and Marketing">Sales and Marketing</option>
                            <option value="Science and Engineering">Science and Engineering</option>
                            <option value="Software">Software</option>
                            <option value="Sports">Sports</option>
                            <option value="Sustainability">Sustainability</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Travel and Tourism">Travel and Tourism</option>
                            <option value="Video">Video</option>
                            <option value="Other">Other</option>
                        </select>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Sector" class="form-label" >Sector </label> <br>
                        <select id="Sector" name="Sector[]"  class="form-select sectorDropdowns" multiple="true">
                            <option value="<?php echo $row['Sector'];?>"><?php echo $row['Sector'];?></option>
                        </select> <br>
                        <small style="color:red;">First select an industry </small>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Stake" class="form-label">Stake</label>
                        <input class="form-control col" type="text" name="Stake" placeholder="Enter Stake"  value="<?php echo $row['Stake'];?>" />
                    </p>
                    <p class="mb-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label for="Details" class="form-label">Details</label>
                        <textarea class="form-control col" type="text" name="Details" placeholder="Enter Details"   > <?php echo $row['Details'];?></textarea>
                    </p>
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="YearFounded" class="form-label">Year Founded</label>
                        <input class="form-control col" type="text" name="YearFounded" placeholder="Enter YearFounded"  value="<?php echo $row['YearFounded'];?>" />
                    </p>
                    <!-- Country dropdown -->
                    <p class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label for="Headquarters" class="form-label">Headquarters</label>
                        <select class="form-select" id="Headquarters" name="Headquarters" required>
                            <option> Select Headquarters...</option>
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
                </div>

                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
    </body>
</html>