<?php 
    include_once('../App/connect.php');
    include_once('../App/DealLink.php');
    // QUERY DATABASE FROM DATA
    $sqlAA="    SELECT DISTINCT
                    News.NewsID, News.NewsURL,GROUP_CONCAT(DISTINCT  NewsDate) AS NewsDate, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, GROUP_CONCAT(DISTINCT  InvestmentValue) AS InvestmentValue, GROUP_CONCAT(DISTINCT  InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT Industry) AS Industry,GROUP_CONCAT(DISTINCT Sector) AS Sector, GROUP_CONCAT(DISTINCT FundName) AS FundName, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country, UserDetail.UserFullName, Roletype.RoleType, Deals.Stake
                FROM 
                    PortfolioCompanyNews 
                -- Include News table data 
                LEFT JOIN 
                    News 
                ON
                    News.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN 
                -- Include PortfoliCompany table data
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID 
                LEFT JOIN 
                -- Link Investor to News using InvestorNews table
                    InvestorNews
                ON
                    InvestorNews.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN 
                -- Include Invesor table data
                    Investor
                ON
                    Investor.InvestorID = InvestorNews.InvestorID 
                LEFT JOIN 
                -- Link Sector to Porfolio Company using PortfolioCompanySector table
                    PortfolioCompanySector
                ON
                    PortfolioCompanySector.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID         
                -- LEFT JOIN 
                -- -- Include Sector table data
                --     Sector          
                -- ON
                --     Sector.SectorID = PortfolioCompanySector.SectorID      
                LEFT JOIN 
                -- Link Fund to PortfolioCompany using FundPortfolioCompany
                    FundPortfolioCompany
                ON
                    FundPortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                -- include Fund table data
                    Fund
                ON
                    Fund.FundID = FundPortfolioCompany.FundID 
                LEFT JOIN 
                -- Link investment stage to fund
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
                LEFT JOIN 
                    PortfolioCompanyInvestmentValue
                ON 
                    PortfolioCompanyInvestmentValue.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                -- LEFT JOIN 
                --     InvestmentValue
                -- ON 
                --     InvestmentValue.InvestmentValueID = PortfolioCompanyInvestmentValue.InvestmentValueID
                LEFT JOIN 
                    PortfolioCompanyIndustry
                ON 
                    PortfolioCompanyIndustry.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Industry
                ON 
                    Industry.IndustryID = PortfolioCompanyIndustry.IndustryID
                LEFT JOIN 
                    PortfolioCompanyUserDetail
                ON 
                    PortfolioCompanyUserDetail.portfoliocompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    UserDetail
                ON 
                    UserDetail.UserDetailID = PortfolioCompanyUserDetail.UserDetailID
                LEFT JOIN 
                    RoleType
                ON 
                    RoleType.RoleTypeID = UserDetail.RoleTypeID
                LEFT JOIN 
                    Deals
                ON 
                    Deals.portfoliocompanyID = PortfolioCompanyNews.PortfolioCompanyID

                GROUP BY News.NewsURL
                ORDER BY  news.NewsDate DESC ";

    $resultAA = $conn->query($sqlAA) or die($conn->error);
    $rowAA = mysqli_fetch_assoc($resultAA);

    //==================================================== 
    // BELOW IS CODE DISPLAYING DATA ON DEALS CAPTURE SCREEN MODAL
    // tHE DATA ON THE DROPDOWNS IS BEING PULLED FROM THE DATABASE
    //====================================================
    //========== | PORTFOLIO COMPANY TABLE | =============
    //====================================================
    // PORTFOLIO COMPANY DEATILS. THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    $sql = " SELECT DISTINCT 
                PortfolioCompanyName, Website, SUBSTRING(Details, 1, 55) AS Details FROM PortfolioCompany 
            JOIN 
                Country ON country.CountryID = PortfolioCompany.Headquarters 
            WHERE Website IS NOT NULL AND Details IS NOT NULL";
            
    $result = mysqli_query($conn, $sql);
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
        <link rel="stylesheet" href="../../css/select2.min.css">
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
                    <div class="row w-25">
                        <!--  VIEW NEW DEALS -->
                        <span class="col">
                            <a href="./NewDeals.php"><button class="btn new-button"> <small>View Recent Deals</small> </button></a>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col"> 
                            <form action="../DealExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST">
                                    <small>Export CSV</small>
                                </button>
                            </form>
                        </span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered" style="Width: 3400px; line-height: 18px;">
                                <t>
                                    <th scope="col">Date</th>
                                    <th scope="col">News Link</th>
                                    <th scope="col">Portfolio Company</th>
                                    <th scope="col">Investment Manager(s)</th>
                                    <th scope="col">Fund(s)</th>
                                    <th scope="col">Value Of Investment</th>
                                    <th scope="col">Stake</th>
                                    <th scope="col">Industry </th>
                                    <th scope="col">Sector(s)</th>
                                    <th scope="col">Portfolio Company Headquarters</th>
                                    <th scope="col">Company Contact(s)</th>
                                    <th scope="col">Role </th>
                                    <th scope="col">View More </th>
                                </t>
                                <?php
                                    while(($rowAA = mysqli_fetch_assoc($resultAA)))
                                    {
                                ?>
                                <tr>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["NewsDate"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["NewsURL"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["PortfolioCompanyName"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["InvestorName"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["FundName"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["InvestmentValue"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["Stake"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["Industry"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["Sector"];?> </small> </td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["Country"];?> </small></td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["UserFullName"];?> </small></td>
                                    <td class="text-truncate"> <small ><?php echo $rowAA["RoleType"];?> </small></td>
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
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/select2.min.js"></script>
    </body>
</html>


                 

