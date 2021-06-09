<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $sqlAA="    SELECT 
                    News.NewsID, News.NewsURL,News.NewsDate, PortfolioCompany.PortfolioCompanyName, PortfolioCompany.TotalInvestmentValue, GROUP_CONCAT(DISTINCT  InvestorName) AS InvestorName,GROUP_CONCAT(DISTINCT Sector) AS Sector, GROUP_CONCAT(DISTINCT FundName) AS FundName, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country 
                FROM 
                    PortfolioCompanyNews 
                LEFT JOIN 
                    News 
                ON
                    News.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN 
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID 
                LEFT JOIN 
                    InvestorNews
                ON
                    InvestorNews.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN 
                    Investor
                ON
                    Investor.InvestorID = InvestorNews.InvestorID 
                LEFT JOIN 
                    PortfolioCompanySector
                ON
                    PortfolioCompanySector.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID         
                LEFT JOIN 
                    Sector          
                ON
                    Sector.SectorID = PortfolioCompanySector.SectorID      
                LEFT JOIN 
                    FundPortfolioCompany
                ON
                    FundPortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Fund
                ON
                    Fund.FundID = FundPortfolioCompany.FundID 
                LEFT JOIN 
                    FundInvestmentStage      
                ON          
                    FundInvestmentStage.FundID = Fund.FundID 
                LEFT JOIN
                    InvestmentStage
                ON
                    InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID 
                LEFT JOIN 
                    PortfolioCompanyCountry
                ON
                    PortfolioCompanyCountry.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyCountry.CountryID

                GROUP BY News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, PortfolioCompany.TotalInvestmentValue,Country.Country ";
    $resultAA = $conn->query($sqlAA) or die($conn->error);
    $rowAA = mysqli_fetch_assoc($resultAA);

    //=================================================== 
    //========== | PORTFOLIO COMPANY TABLE | ============
    //===================================================
    // PORTFOLIO COMPANY DEATILS. THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    $sql = " SELECT DISTINCT 
                PortfolioCompanyName, Website, SUBSTRING(Details, 1, 55) AS Details FROM PortfolioCompany 
            JOIN 
                Country ON country.CountryID = PortfolioCompany.Headquarters 
            WHERE Website IS NOT NULL AND Details IS NOT NULL";
            
    $result = mysqli_query($conn, $sql);
    // $sql2 = "   SELECT 
    //                 SUBSTRING(Details, 1, 55) AS Details 
    //             FROM 
    //                 PortfolioCompany 
    //             WHERE 
    //                 Details IS NOT NULL";
    // $result2 = mysqli_query($conn, $sql2);
    // Pulling Startup Data into the Country/Headquarters dropdown
    $sql3 = "   SELECT DISTINCT 
                    Country 
                FROM 
                    PortfolioCompany 
                LEFT JOIN 
                    Country ON Country.CountryID = PortfolioCompany.Headquarters 
                WHERE Country IS NOT NULL";
    $result3 = mysqli_query($conn, $sql3);
    // Pulling Startup Data into the Currency dropdown
    $sql4 = "   SELECT DISTINCT 
                    Currency 
                FROM 
                    PortfolioCompany 
                LEFT JOIN 
                    Currency ON currency.CurrencyID = PortfolioCompany.CurrencyID 
                WHERE Currency IS NOT NULL";
    $result4 = mysqli_query($conn, $sql4);
    //=================================================== 
    //============== | USERDETAIL TABLE | ===============
    //===================================================
    $sql5 = "   SELECT DISTINCT 
                    UserFullName
                FROM 
                    UserDetail 
                WHERE 
                    UserFullName IS NOT NULL ORDER BY UserFullName ASC";
    $result5 = mysqli_query($conn, $sql5);
    // Pulling UserDetail Data into the Email dropdown
    $sql6 = "   SELECT DISTINCT 
                    Email, ContactNumber1 
                FROM 
                    UserDetail 
                WHERE Email IS NOT NULL";
    $result6 = mysqli_query($conn, $sql6);
    // Pulling UserDetail Data into the RoleType dropdown
    $sql7 = "   SELECT DISTINCT 
                    RoleType.RoleType
                FROM 
                    UserDetail 
                LEFT JOIN 
                    RoleType ON RoleType.RoleTypeID = UserDetail.RoleTypeID ";
    $result7 = mysqli_query($conn, $sql7);
    // Pulling UserDetail Data into the Gender dropdown
    $sql8 = "   SELECT DISTINCT 
                     Gender.Gender 
                FROM 
                    UserDetail 
                LEFT JOIN 
                    Gender ON Gender.GenderID = UserDetail.GenderID ";
    $result8 = mysqli_query($conn, $sql8);
    // Pulling UserDetail Data into the Race dropdown
    $sql9 = "   SELECT DISTINCT 
                     Race.Race 
                FROM 
                    UserDetail 
                LEFT JOIN 
                    Race ON Race.RaceID = UserDetail.RaceID ";
    $result9 = mysqli_query($conn, $sql9);
    //=================================================== 
    //============== | INVESTOR TABLE | =================
    //===================================================
    $sqlA1 = "   SELECT DISTINCT 
                InvestorName
            FROM 
                Investor 
            WHERE 
                InvestorName IS NOT NULL ORDER BY InvestorName ASC";
            $resultA1 = mysqli_query($conn, $sqlA1);

            $sqlA2 = "   SELECT DISTINCT 
                Website
            FROM 
                Investor 
            WHERE 
                Website IS NOT NULL ORDER BY Website ASC";
    $resultA2 = mysqli_query($conn, $sqlA2);
    // INVETSOR NOTES. THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    // Pulling Investor Data into the Notes dropdown
    $sqlA3 = "  SELECT DISTINCT
                    SUBSTRING(Note, 1, 55) AS Note 
                FROM 
                    Note
                WHERE 
                    Note IS NOT NULL";
    $resultA3 = mysqli_query($conn, $sqlA3);
    // Pulling Investor Data into the impact tag dropdown
    $sqlA4 = "  SELECT DISTINCT
                    ImpactTag
                FROM 
                Investor
                WHERE 
                    ImpactTag IS NOT NULL";
    $resultA4 = mysqli_query($conn, $sqlA4);
    // Pulling Investor Data into the Investor Headquarters dropdown
    $sqlA5 = "  SELECT DISTINCT
                    Country
                FROM 
                    Investor
                JOIN 
                    Country ON country.CountryID = Investor.Headquarters";
    $resultA5 = mysqli_query($conn, $sqlA5);
    // Pulling Investor Data into the impact tag dropdown
    $sqlA6 = "  SELECT DISTINCT
                    Description
                FROM 
                    Investor
                JOIN 
                    Description ON Description.DescriptionID = Investor.DescriptionID";
    $resultA6 = mysqli_query($conn, $sqlA6);
    //=================================================== 
    //================ | FUND TABLE | ===================
    //===================================================
    // Pulling Fund Data into the Fund Section dropdown
    $sqlB = "  SELECT DISTINCT
                    FundName, CommittedCapitalOfFund, CommittedCapital, MinimumInvestment, MaximumInvestment
                FROM 
                    Fund
                WHERE FundName IS NOT NULL AND CommittedCapitalOfFund IS NOT NULL AND CommittedCapital IS NOT NULL AND MinimumInvestment IS NOT NULL AND MaximumInvestment IS NOT NULL ";
    $resultB = mysqli_query($conn, $sqlB);
    // Pulling Fund Data into the FundName dropdown
    $sqlB1 = "  SELECT DISTINCT
                    InvestmentStage
                FROM 
                    InvestmentStage
                WHERE InvestmentStage IS NOT NULL ORDER BY InvestmentStage ASC";
    $resultB1 = mysqli_query($conn, $sqlB1);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Deals </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/main.css">
    </head>
    <body class="pb-5">
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
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW Deal MODAL -->
                        <span class="col-2">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                New Deal <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create New Deal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" action="./php/insert.php" method="POST" enctype="multipart/form-data">
                                                <!--    
                                                        =========================================================================
                                                        ======================== NEWS SECTION ===================================
                                                        =========================================================================
                                                -->
                                                <div class="row"> 
                                                    <h2 class="news-h2">
                                                        News 
                                                    </h2>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                                                        <label for="NewsDate" class="form-label">News Date</label>
                                                        <input type="date" value="" class="form-control" id="NewsDate" name="NewsDate" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="NewsURL" class="form-label">News Link</label>
                                                        <input type="text" class="form-control" id="NewsURL" name="NewsURL" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="NewsNote" class="form-label">News Note</label>
                                                        <textarea class="form-control" aria-label="With textarea" id="NewsNote" name="NewsNote"></textarea>
                                                    </div>  
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ===================== STARTUP COMPANY SECTION ===========================
                                                        =========================================================================
                                                -->
                                                <div class="row">  
                                                    <h2 class="news-h2">
                                                        Company Details
                                                    </h2>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="PortfolioCompanyName" class="form-label"> Portfolio Company Name</label>
                                                        <select class="form-select" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                                                            <option>Select Company...</option>
                                                            <?php
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    # code...
                                                                    echo "<option>".$row['PortfolioCompanyName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="PortfolioCompanyWebsite" class="form-label">Company Website</label>
                                                        <select class="form-select" id="PortfolioCompanyWebsite" name="PortfolioCompanyWebsite" required>
                                                            <option> Website...</option>
                                                            <?php
                                                                mysqli_data_seek($result,0);
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    # code...
                                                                    echo "<option>".$row['Website']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Details" class="form-label">Details</label>
                                                        <select class="form-select" id="Details" name="Details" required>
                                                            <option> Details...</option>
                                                            <?php
                                                                mysqli_data_seek($result, 0);
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    # code...
                                                                    echo "<option>".$row['Details']."..."."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="YearFounded" class="form-label">Year Founded</label>
                                                        <input type="text" class="form-control" id="YearFounded" name="YearFounded" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Headquarters" class="form-label">Headquarters</label>
                                                        <select class="form-select" id="Headquarters" name="Headquarters" required>
                                                            <option> Headquarters...</option>
                                                            <?php
                                                                // mysqli_data_seek($result,0);
                                                                while ($row3 = mysqli_fetch_assoc($result3)) {
                                                                    # code...
                                                                    echo "<option>".$row3['Country']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Stake" class="form-label">Stake</label>
                                                        <input type="text" class="form-control" id="Stake" name="Stake">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="TotalInvestmentValue" class="form-label">Total Investment Value</label>
                                                        <input type="number" class="form-control" id="TotalInvestmentValue" name="TotalInvestmentValue" required>
                                                    </div>
                                                    <!-- 
                                                        /////////////////////
                                                            INDUSTRY SECTION
                                                        /////////////////////
                                                    -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
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
                                                    </div>
                                                    <!-- Sector Dropdown -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 sector" id="ArtificialIntelligenceDrop">
                                                        <label for="Sector" class="form-label">Sector </label>
                                                        <select id="Sector" name="Sector" class="form-select">
                                                            <option>choose...</option>
                                                        </select>
                                                        <small style="color:red;">First select an industry </small>
                                                    </div>
                                                    <!-- 
                                                        /////////////////////
                                                        CURRENCY SECTION
                                                        /////////////////////
                                                    -->
                                                    <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Currency" class="form-label">Currency</label>
                                                        <select class="form-select" id="Currency" name="Currency" required>
                                                            <option> Currency...</option>
                                                            <?php
                                                                while ($row4 = mysqli_fetch_assoc($result4)) {
                                                                    # code...
                                                                    echo "<option>".$row4['Currency']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> -->
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ======================== USERDETAIL SECTION =============================
                                                        =========================================================================
                                                -->

                                                <div class="row"> 
                                                    <h2 class="news-h2">
                                                        Contact Person
                                                    </h2>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="UserFullName" class="form-label">First Name</label>
                                                        <select class="form-select" id="UserFullName" name="UserFullName" required>
                                                            <option> Select Contact Person...</option>
                                                            <?php
                                                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                                                    # code...
                                                                    echo "<option>".$row5['UserFullName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="LastName" class="form-label">Last Name</label>
                                                        <select class="form-select" id="LastName" name="LastName" required>
                                                            <option> Select LastName...</option>
                                                            <?php
                                                                mysqli_data_seek($result5,0);
                                                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                                                    # code...
                                                                    echo "<option>".$row5['LastName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Email" class="form-label">Email</label>
                                                        <select class="form-select" id="Email" name="Email" required>
                                                            <option> Select Email...</option>
                                                            <?php
                                                                while ($row6 = mysqli_fetch_assoc($result6)) {
                                                                    # code...
                                                                    echo "<option>".$row6['Email']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="ContactNumber1" class="form-label"> Select Contact Number... </label>
                                                        <select class="form-select" id="ContactNumber1" name="ContactNumber1" required>
                                                            <option> Select Contact Number 1...</option>
                                                            <?php
                                                                mysqli_data_seek($result6,0);
                                                                while ($row6 = mysqli_fetch_assoc($result6)) {
                                                                    # code...
                                                                    echo "<option>".$row6['ContactNumber1']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="ContactNumber2" class="form-label">Select Contact Number 2...</label>
                                                        <input type="text" class="form-control" id="ContactNumber2" name="ContactNumber2">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="RoleType" class="form-label">Select Role Type...</label>                    
                                                        <select class="form-select" id="RoleType" name="RoleType" required>
                                                            <option> RoleType...</option>
                                                            <?php
                                                                while ($row7 = mysqli_fetch_assoc($result7)) {
                                                                    # code...
                                                                    echo "<option>".$row7['RoleType']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Gender" class="form-label">Gender</label>                    
                                                        <select class="form-select" id="Gender" name="Gender" required>
                                                            <option> Select Gender...</option>
                                                            <?php
                                                                while ($row8 = mysqli_fetch_assoc($result8)) {
                                                                    # code...
                                                                    echo "<option>".$row8['Gender']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Race" class="form-label">Race</label>                    
                                                        <select class="form-select" id="Race" name="Race" required>
                                                            <option> Select Race...</option>
                                                            <?php
                                                                while ($row9 = mysqli_fetch_assoc($result9)) {
                                                                    # code...
                                                                    echo "<option>".$row9['Race']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> -->
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ======================== INVESTOR SECTION ===============================
                                                        =========================================================================
                                                -->
                                                <div class="row"> 
                                                    <h2 class="news-h2">
                                                        Investor(s)
                                                    </h2>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorName" class="form-label">Investor Name</label>        
                                                        <select class="form-select" id="InvestorName" name="InvestorName" required>
                                                            <option> InvestorName...</option>
                                                            <?php
                                                                while ($rowA1 = mysqli_fetch_assoc($resultA1)) {
                                                                    # code...
                                                                    echo "<option>".$rowA1['InvestorName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>  
                                                    <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorWebsite" class="form-label">Investor Website</label>       
                                                        <select class="form-select" id="InvestorWebsite" name="InvestorWebsite" required>
                                                            <option> Website...</option>
                                                            <?php
                                                                while ($rowA2 = mysqli_fetch_assoc($resultA2)) {
                                                                    # code...
                                                                    echo "<option>".$rowA2['Website']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestorNote" class="form-label">Investor Note</label>     
                                                        <select class="form-select" id="InvestorNote" name="InvestorNote" required>
                                                            <option> Select Note...</option>
                                                            <?php
                                                                while ($rowA3 = mysqli_fetch_assoc($resultA3)) {
                                                                    # code...
                                                                    echo "<option>".$rowA3['Note']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="ImpactTag" class="form-label">Impact Tag</label>     
                                                        <select class="form-select" id="ImpactTag" name="ImpactTag" required>
                                                            <option> Select Impact Tag...</option>
                                                            <?php
                                                                while ($rowA4 = mysqli_fetch_assoc($resultA4)) {
                                                                    # code...
                                                                    echo "<option>".$rowA4['ImpactTag']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="YearFounded" class="form-label">Year Founded</label>
                                                        <input type="text" class="form-control" id="YearFounded" name="YearFounded">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestorHeadquarters" class="form-label">Headquarters</label>     
                                                        <select class="form-select" id="InvestorHeadquarters" name="InvestorHeadquarters" required>
                                                            <option> Select Headquarters...</option>
                                                            <?php
                                                                while ($rowA5 = mysqli_fetch_assoc($resultA5)) {
                                                                    # code...
                                                                    echo "<option>".$rowA5['Country']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="img" class="form-label">Logo</label>
                                                        <input type="file" class="form-control" id="img" name="img" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Description" class="form-label">Description</label>
                                                        <select name="Description" class="form-select" id="Description" required>
                                                            <option value="" selected >Select Description...</option>
                                                            <?php
                                                                while ($rowA6 = mysqli_fetch_assoc($resultA6)) {
                                                                    # code...
                                                                    echo "<option>".$rowA6['Description']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> -->
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        =========================== FUND SECTION ================================
                                                        =========================================================================
                                                -->
                                                <div class="row"> 
                                                    <h2 class="news-h2">
                                                        Investor Fund
                                                    </h2>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="FundName" class="form-label">Fund Name</label>
                                                        <select name="FundName" class="form-select" id="FundName">
                                                            <option value="">Select Fund...</option>
                                                            <?php
                                                                while($rowB = mysqli_fetch_assoc($resultB)){
                                                                    echo "<option>".$rowB['FundName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>  
                                                    <!-- <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="CommittedCapitalOfFund" class="form-label"> Committed Capital Of Fund</label>
                                                        <select name="CommittedCapitalOfFund" class="form-select" id="CommittedCapitalOfFund" required>
                                                            <option value="" selected >Select Committed Capital Of Fund...</option>
                                                            <?php
                                                                mysqli_data_seek($resultB, 0);
                                                                while ($rowB = mysqli_fetch_assoc($resultB)) {
                                                                    # code...
                                                                    echo "<option>".$rowB['CommittedCapitalOfFund']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="CommittedCapital" class="form-label"> Committed Capital</label>
                                                        <select name="CommittedCapital" class="form-select" id="CommittedCapital" required>
                                                            <option value="" selected >Select Committed Capital...</option>
                                                            <?php
                                                                mysqli_data_seek($resultB, 0);
                                                                while ($rowB = mysqli_fetch_assoc($resultB)) {
                                                                    # code...
                                                                    echo "<option>".$rowB['CommittedCapital']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>  
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="MinimumInvestment" class="form-label"> Minimum Investment</label>
                                                        <select name="MinimumInvestment" class="form-select" id="MinimumInvestment" required>
                                                            <option value="" selected >Select Minimum Investment...</option>
                                                            <?php
                                                                mysqli_data_seek($resultB, 0);
                                                                while ($rowB = mysqli_fetch_assoc($resultB)) {
                                                                    # code...
                                                                    echo "<option>".$rowB['MinimumInvestment']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="MaximumInvestment" class="form-label"> Maximum Investment</label>
                                                        <select name="MaximumInvestment" class="form-select" id="MaximumInvestment" required>
                                                            <option value="" selected >Select Maximum Investment...</option>
                                                            <?php
                                                                mysqli_data_seek($resultB, 0);
                                                                while ($rowB = mysqli_fetch_assoc($resultB)) {
                                                                    # code...
                                                                    echo "<option>".$rowB['MaximumInvestment']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestmentStage" class="form-label">Investment Stage </label>
                                                        <select name="InvestmentStage" class="form-select" id="InvestmentStage" required>
                                                            <option value="" selected >Select Investment Stage...</option>
                                                            <?php
                                                                // mysqli_data_seek($resultB, 0);
                                                                while ($rowB1 = mysqli_fetch_assoc($resultB1)) {
                                                                    # code...
                                                                    echo "<option>".$rowB1['InvestmentStage']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="IndustryPreference" class="form-label">Industry Preference</label>
                                                        <textarea class="form-control IndustryPreference" aria-label="With textarea" id=" IndustryPreference" name=" IndustryPreference"></textarea>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="FundNote" class="form-label">Fund Note</label>
                                                        <select name="InvestmentStage" class="form-select" id="InvestmentStage" required>
                                                            <option value="" selected >Select Fund Note...</option>
                                                            <?php
                                                                mysqli_data_seek($resultA3, 0);
                                                                while ($rowA3 = mysqli_fetch_assoc($resultA3)) {
                                                                    # code...
                                                                    echo "<option>".$rowA3['Note']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div> -->
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
                        <!-- EXPORT CSV FILE -->
                        <span class="col-2"> 
                            <form action="../DealExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class=" table table-hover table-striped table-success table-bordered" style="Width: 3400px;line-height: 30px;">
                        <t>
                            <th scope="col">Date</th>
                            <th scope="col">Portfolio Company</th>
                            <th scope="col">Investor(s)</th>
                            <th scope="col">Fund(s)</th>
                            <th scope="col">Value Of Investment</th>
                            <th scope="col">Investment Stage </th>
                            <th scope="col">Sector</th>
                            <th scope="col">Portfolio Company Headquarters</th>
                            <th scope="col"> View More </th>
                        </t>
                        <?php
                            while(($rowAA = mysqli_fetch_assoc($resultAA)))
                            {
                        ?>
                        <tr>
                            <td> <?php echo $rowAA["NewsDate"];?> </td>
                            <td> <?php echo $rowAA["PortfolioCompanyName"];?> </td>
                            <td> <?php echo $rowAA["InvestorName"];?> </td>
                            <td> <?php echo $rowAA["FundName"];?> </td>
                            <td> <?php echo $rowAA["TotalInvestmentValue"];?> </td>
                            <td> <?php echo $rowAA["InvestmentStage"];?> </td>
                            <td> <?php echo $rowAA["Sector"];?> </td>
                            <td> <?php echo $rowAA["Country"];?></td>
                            <td> 
                                <a href="../Views/DealView.php?NewsID=<?php echo $rowAA['NewsID'];?>">View Deal</a>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
    </body>
</html>


                 

