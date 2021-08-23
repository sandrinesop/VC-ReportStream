<?php 
    // CONNECT TO DATABASE
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $sql=" SELECT DISTINCT
                PortfolioCompany.PortfolioCompanyID,PortfolioCompany.Deleted, PortfolioCompany.DeletedDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, Currency.Currency, PortfolioCompany.Website, GROUP_CONCAT(DISTINCT Industry) AS Industry, GROUP_CONCAT(DISTINCT Sector) AS Sector,  PortfolioCompany.Details, PortfolioCompany.YearFounded, Country.Country, PortfolioCompany.Logo, UserDetail.UserFullName, Gender.Gender, Race.Race
            FROM 
                PortfolioCompany 
            LEFT JOIN 
                InvestorPortfolioCompany 
            ON 
                InvestorPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Investor 
            ON 
                Investor.InvestorID = InvestorPortfolioCompany.InvestorID 
            LEFT JOIN 
                FundPortfolioCompany 
            ON 
                FundPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Fund 
            ON 
                Fund.FundID = FundPortfolioCompany.FundID 
            LEFT JOIN 
                Currency 
            ON 
                Currency.CurrencyID = PortfolioCompany.CurrencyID 
            LEFT JOIN 
                Country 
            ON 
                Country.CountryID = PortfolioCompany.Headquarters 
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
                PortfolioCompanySector.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Sector 
            ON 
                Sector.SectorID = PortfolioCompanySector.SectorID
            LEFT JOIN 
                PortfolioCompanyUserDetail
            ON 
                PortfolioCompanyUserDetail.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                UserDetail
            ON 
                UserDetail.UserDetailID = PortfolioCompanyUserDetail.UserDetailID
            LEFT JOIN 
                RoleType
            ON 
                RoleType.RoleTypeID = UserDetail.RoleTypeID
            LEFT JOIN 
                Gender
            ON
                Gender.GenderID = UserDetail.GenderID
            LEFT JOIN 
                Race 
            ON 
                Race.RaceID =UserDetail.RaceID
            WHERE 
                PortfolioCompany.Deleted = 0
                
            GROUP BY PortfolioCompany.PortfolioCompanyID,PortfolioCompany.Deleted, PortfolioCompany.DeletedDate, PortfolioCompany.PortfolioCompanyName, Currency.Currency, PortfolioCompany.Website, PortfolioCompany.Details, PortfolioCompany.YearFounded, Country.Country, PortfolioCompany.Logo
    "; 
    $result = $conn->query($sql) or die($conn->error);
    
    //===================================================
    //===================================================
    //Pulling data from the database and into dropdowns to create a new company with standardized data 
    //===================================================
    //===================================================
    // USER FULLNAME TO SET CONTACT
    $sql5 = "   SELECT DISTINCT 
                    UserFullName
                FROM 
                    UserDetail 
                WHERE 
                    UserFullName IS NOT NULL ORDER BY UserFullName ASC";
    $result5 = mysqli_query($conn, $sql5);
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
    
    // ACCESSING INVESTORS TO POPULATE DROPDOWN FROM DATABASE
    $sql102 = "   SELECT DISTINCT 
                    InvestorName
                FROM 
                    Investor 
                WHERE 
                    InvestorName IS NOT NULL ORDER BY InvestorName ASC";
    $result102 = mysqli_query($conn, $sql102);
    
    // ACCESSING FUNDS TO POPULATE DROPDOWN FROM DATABASE
    $sql103 = "   SELECT DISTINCT 
                    FundName
                FROM 
                    Fund 
                WHERE 
                    FundName IS NOT NULL ORDER BY FundName ASC";
    $result103 = mysqli_query($conn, $sql103);

    // POPULATING PORTFOLIO COMPANIES DROPDOWN
    $sql104 = " SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                    PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result104 = mysqli_query($conn, $sql104);
    // POPULATING ROLETYPE DROPDOWN
    $sqlRoleType = " SELECT DISTINCT 
                    RoleType
                FROM 
                    RoleType 
                WHERE 
                    RoleType IS NOT NULL ORDER BY RoleType ASC
    ";
    $resultRoleType = mysqli_query($conn, $sqlRoleType);
    // POPULATING GENDER DROPDOWN
    $sqlGender = " SELECT DISTINCT 
                    Gender
                FROM 
                    Gender 
                WHERE 
                    Gender IS NOT NULL ORDER BY Gender ASC
    ";
    $resultGender = mysqli_query($conn, $sqlGender);
    // POPULATING RACE DROPDOWN
    $sqlRace = " SELECT DISTINCT 
                    Race
                FROM 
                    Race 
                WHERE 
                    Race IS NOT NULL ORDER BY Race ASC
    ";
    $resultRace = mysqli_query($conn, $sqlRace);

    if ( isset($_POST['submit']))
    {
        // DEFINED VAR FOR THE SECOND TABLE
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = mysqli_real_escape_string($conn, $_POST['PortfolioCompanyName']);
        $Currency                = $_POST['Currency'];
        $PortfolioCompanyWebsite = mysqli_real_escape_string($conn, $_POST['Website']);
        $Industries              = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        $Details                 = mysqli_real_escape_string($conn, $_POST['Details']);
        $YearFounded             = $_POST['YearFounded'];
        $Headquarters            = $_POST['Headquarters'];
        $UserFullName            = $_POST['UserFullName'];
        $Logo                    = $_FILES['img']['name'];
        $InvestorName           = $_POST['InvestorName'];
        $FundName               = $_POST['FundName'];

        // Company Logo Insert code
        $Logo = addslashes(file_get_contents($_FILES["img"]["tmp_name"]));

        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT PortfolioCompanyName FROM PortfolioCompany WHERE PortfolioCompany.PortfolioCompanyName ='$PortfolioCompanyName'";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            $conn->close();
            header( "refresh: 3; url = portfolio-company.php" );
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H4>Heads Up!</H4>
                    <p style="margin:0;"> <small>New record not created, Portfolio Company already exists.</small> </p>
                </div>'
            ;
        }else{
            // PORTFOLIO COMPANY INSERT
            $sql = "INSERT INTO 
                    PortfolioCompany( PortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyName, CurrencyID, Website, Details, YearFounded, Headquarters, Logo)
                VALUES 
                    (uuid(), now(), now(), 0, NULL,'$PortfolioCompanyName', (select C.CurrencyID FROM Currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite', '$Details', '$YearFounded', (select Country.CountryID FROM Country where Country.Country = '$Headquarters'), '$Logo')
            ";
            $query = mysqli_query($conn, $sql);
            // =========================================================================================
            // CODE BELOW: IF COMPANY IS CREATED SUCCESSFULLY THEN LINK THE COMPANY WITH OTHER ENTITIES.
            // =========================================================================================
            if($query){
                // LOOP TO INSERT SECTORS ON P.COMPANY
                foreach($Sector as $sects){  
                    $sql99 = "  INSERT INTO 
                                    PortfolioCompanySector(PortfolioCompanySectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, SectorID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL,(select P.PortfolioCompanyID FROM PortfolioCompany P where P.PortfolioCompanyName = '$PortfolioCompanyName'), (select S.SectorID FROM Sector S where S.Sector = '$sects'))
                    ";
                    $query99 = mysqli_query($conn, $sql99);

                    if($query99){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error inserting the sector ID from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                $IndustryList = explode(",", $Industries);
                foreach($IndustryList AS $Industry){ 
                    $sql98 = "   INSERT INTO 
                                    PortfolioCompanyIndustry(PortfolioCompanyIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, IndustryID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'))";
                    $query98 = mysqli_query($conn, $sql98);
                    if($query98){
                        // echo 'For each iteration the Sector ID for '.$sector. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error inserting the Industry IDs from the array'.mysqli_error($conn).'<br/>';
                        print_r($IndustryList);
                    }
                }

                // =============================================
                // LOOP TO INSERT CONTACT PERSON ON P.COMPANY
                // =============================================
                foreach($UserFullName as $Contact){  
                    $sql4 = "   INSERT INTO PortfolioCompanyUserDetail(PortfolioCompanyUserDetailID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, UserDetailID)
                                VALUES (uuid(), now(), now(), 0, NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$Contact'))";
                    $query4 = mysqli_query($conn, $sql4);

                    if($query4){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Contacts from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                // =============================================
                // LOOP TO INSERT INVESTORS ON P.COMPANY
                // =============================================
                foreach($InvestorName as $InvestmentManager){  
                    $sql104 = " INSERT INTO InvestorPortfolioCompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, InvestorID, PortfolioCompanyID)
                            VALUES (uuid(), now(), now(), 0, NULL, (select Investor.InvestorID FROM  Investor where Investor.InvestorName = '$InvestmentManager'), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
                    $query104 = mysqli_query($conn, $sql104);

                    if($query104){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Investment Manager(s) from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                // =============================================
                // LOOP TO FUNDS ON P.COMPANY
                // =============================================
                foreach($FundName as $Fund){  
                    $sql105 = "     INSERT INTO 
                                        FundPortfolioCompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, PortfolioCompanyID)
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$Fund'),(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
                    $query105 = mysqli_query($conn, $sql105);
                    if($query105){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Contacts from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // ===========================================================
                // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
                // ===========================================================
                header( "refresh: 3; url= portfolio-company.php" );
                echo 
                    '<div style="background-color:#d1e7dd; color: #0f5132; margin:0;">
                        <H4>Thank you for your contibution</H4>
                        <p style="margin:0;"> <small> New Portfolio Company created successfully! </small> </p>
                    </div>'
                ;
            } else {
                echo'There was an error creating Portfolio Company: '.mysqli_error($conn).'<br/>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Portfolio Company</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/select2.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="stylesheet" href="../../DataTables/datatables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <!-- OVERWRITING THE STYLING OF THE PLUGIN -->
        <style>
            .dataTables_wrapper,
            .dataTables_length,
            .dataTables_wrapper,
            .dataTables_filter,
            .dataTables_wrapper,
            .dataTables_info,
            .dataTables_wrapper,
            .dataTables_processing,
            .dataTables_wrapper,
            .dataTables_paginate,
            .dataTables_paginate #table_investmentManager_previous,
            .dataTables_paginate #table_investmentManager_next {
                color: #ffffff !important;
            };
        </style>
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <?php include('../Views/navBar/nav.php');?> 
        <div class="float-end">
            <form action="../Auth/logout.php" method="POST">
                <input type="submit" value="logout" name="logout">
            </form>
        </div>
        <!-- BODY CONTENT -->
        <main class="container ">
            <div class=" my-5">
                <!-- CREATE NEW PORTFOLIO COMPANY -->
                <div class="my-2">
                    <div class="row">
                        <span class="col-3">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Portfolio Company <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create A New Portfolio Company</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="PortfolioCompanyName" class="form-label"> Portfolio Company Name</label>
                                                        <input type="text" class="form-control" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                                                    </div>
                                                    <!-- Actual Currencies as in the DB --> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
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
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Website" class="form-label">Company Website</label>
                                                        <input type="text" class="form-control" id="Website" name="Website" required>
                                                    </div>
                                                    <!--   INDUSTRY DROPDOWN  -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Industry" class="form-label">Industry</label>
                                                        <select id="Industry" name="Industry" class="form-select">
                                                            <option>choose...</option>
                                                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                                                            <option value="Clothing and Apparel">Clothing Apparel</option>
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
                                                            <option value="Unknown">Unknown</option>
                                                        </select>
                                                    </div>
                                                    <!-- SECTOR DROPDOWN | Data being fed through JQuery -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 " id="ArtificialIntelligenceDrop">
                                                        <label for="Sector" class="form-label" >Sector </label>
                                                        <select id="Sector" name="Sector[]"  class="form-select sectorDropdowns" multiple="true" required>
                                                            <option>choose...</option>
                                                        </select>
                                                        <small style="color:red;">First select an industry </small>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Details" class="form-label">Details</label>
                                                        <input type="text" class="form-control" id="Details" name="Details">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="YearFounded" class="form-label">Year Founded</label>
                                                        <select class="form-control" name="YearFounded" id="YearFounded"required>
                                                                <option value=""> Select...</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Headquarters" class="form-label">Country</label>
                                                        <select class="form-select" id="Headquarters" name="Headquarters" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($row101 = mysqli_fetch_assoc($result101)) {
                                                                    # code...
                                                                    echo "<option>".$row101['Country']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- COMPANY CONTACT -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="UserFullName" class="form-label">Company Contact</label>
                                                        <select class="form-select UserFullName" id="UserFullName" name="UserFullName[]" multiple="true" required>
                                                            <option> Select Contact Person...</option>
                                                            <?php
                                                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                                                    # code...
                                                                    echo "<option>".$row5['UserFullName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <!-- New contact-->
                                                        <div class="my-1">
                                                            <button onclick="openWin()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Contact
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestorName" class="form-label">Investment Manager(s)</label>
                                                        <select class="form-select InvestorName" id="InvestorName" name="InvestorName[]" multiple="true" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($row102 = mysqli_fetch_assoc($result102)) {
                                                                    # code...
                                                                    echo "<option>".$row102['InvestorName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin2()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Manager
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="FundName" class="form-label">Fund(s)</label>
                                                        <select class="form-select FundName" id="FundName" name="FundName[]" multiple="true" required>
                                                            <option> Select Fund(s)...</option>
                                                            <?php
                                                                while ($row103 = mysqli_fetch_assoc($result103)) {
                                                                    # code...
                                                                    echo "<option>".$row103['FundName']."</option>";
                                                                } 
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin3()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Fund
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                            <label for="img" class="form-label">Logo</label>
                                                            <input type="file" class="form-control" id="img" name="img" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <!-- IMPORT CSV FILE -->
                        <span class="col-2"> 
                            <a href="javascript:void(0);" class="btn btn-outline-success" onclick="formToggle('ImportFrm');">Import</a>
                            <div id="ImportFrm" class="mt-1" style="display:none;">
                                <form action="../Import/ImportPC.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file">
                                    <input type="submit" class="btn btn-outline-primary" name="ImportSubmit" value="IMPORT" >
                                </form>
                            </div>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col-2"> 
                            <form action="../PCExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export</button>
                            </form>
                        </span>
                    </div>
                </div>
                <!-- DISPLAY TABLE OF ALL PORTFOLIO COMPANIES --> 
                <div class="card">
                    <div class="card-body" style="background-color:#5d8f18;">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" tbl table table-hover table-striped table-success table-bordered" style="Width: 3600px; line-height: 18px;" id="table_PortfolioCompany">
                                <thead> 
                                    <th scope="col" >Portfolio Company Name</th>
                                    <th scope="col" >Investment Manager(s)</th>
                                    <th scope="col" >Fund(s)</th>
                                    <th scope="col" >Currency </th>
                                    <th scope="col" >Portfolio Company Website</th>
                                    <th scope="col" >Industry</th>
                                    <th scope="col" >Sector</th>
                                    <th scope="col" >Details</th>
                                    <th scope="col" >Year Founded</th>
                                    <th scope="col" >Country</th>
                                    <th scope="col" >CEO </th>
                                    <th scope="col" >CEO Gender</th>
                                    <th scope="col" >CEO Race</th>
                                    <th scope="col" >Logo</th>
                                    <th scope="col">Edit </th>
                                    <th scope="col">Delete </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while($rows = mysqli_fetch_assoc($result))
                                        {
                                    ?>
                                        <tr data-href="../crud/edit_PC.php?PortfolioCompanyID=<?php echo $rows['PortfolioCompanyID']; ?>">     
                                            <td class="text-truncate"> <small> <?php echo $rows['PortfolioCompanyName'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['InvestorName'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['FundName'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Currency'] ?> </small></td>
                                            <td class="text-truncate"> <small> <a href="<?php echo $rows['Website'] ?>" target="_Blank"><?php echo $rows['Website'] ?></a> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Industry'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Sector'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Details'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['YearFounded'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Country'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['UserFullName'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Gender'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Race'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo '<img src="data:image;base64,'.base64_encode($rows['Logo']).'" style="width:100px; height:60px;">'?> </small></td>
                                            <td class="text-truncate"> <small> <a href="../crud/edit_PC.php?PortfolioCompanyID=<?php echo $rows['PortfolioCompanyID']; ?> ">Edit</a></small></td>
                                            <td class="text-truncate"> <small> <a href="../crud/delete_PC.php?PortfolioCompanyID=<?php echo $rows['PortfolioCompanyID']; ?> ">Delete</a></small></td>
                                            <!-- <td> <?php echo $rows['IndustryID'] ?></td>
                                            <td> <?php echo $rows['SectorID'] ?></td> -->
                                        </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../DataTables/datatables.js"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../js/DateDropDown.js"></script>
        <script src="../../js/MultiSelect.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script>
                var createWindow;
                // open window
                function openWin() {
                    createWindow = window.open("./SubFunctions/create_contact.php", "_blank", "width=920, height=500");
                };

                var createWindow2;
                // open window
                function openWin2() {
                    createWindow2 = window.open("./SubFunctions/create_investor.php", "_blank", "width=920, height=500");
                };

                var createWindow3;
                // open window
                function openWin3() {
                    createWindow3 = window.open("./SubFunctions/create_fund.php", "_blank", "width=920, height=500");
                };
        </script>
        <script>
            $(document).ready( function () {    
                // Initializing the datatable plugin
                $('#table_PortfolioCompany').DataTable();
                
                // Trigger the double tap to edit function
                $(document.body).on("dblclick", "tr[data-href]", function (){
                    window.location.href = this.dataset.href;
                })
            } );
        </script>
        <script>
            function formToggle(ID){
                 var ImportFormReview = document.getElementById(ID);
                 if(ImportFormReview.style.display === "none"){
                    ImportFormReview.style.display ="block";
                 }else{
                    ImportFormReview.style.display ="none";
                 }
            };
        </script>
    </body>
</html>