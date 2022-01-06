<?php 
    include_once('../../App/connect.php');
    // QUERY DATABASE FROM DATA
    $DealsID =$_GET['DealsID'];
    // QUERY DATABASE FROM DATA
    $sqlA1="    SELECT DISTINCT
                    Deals.DealsID, News.NewsID, News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, FORMAT(Deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue', Deals.stake, GROUP_CONCAT(DISTINCT Industry) AS Industry , GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country, GROUP_CONCAT(DISTINCT UserFullName) AS UserFullName, RoleType.RoleType
                FROM 
                    Deals 
                -- Include investor table data through the linking table Dealsinvestor
                LEFT JOIN
                    DealsInvestor
                ON 
                    DealsInvestor.DealsID = Deals.DealsID
                -- Include Investor table data
                LEFT JOIN
                    Investor
                ON
                    Investor.InvestorID = DealsInvestor.InvestorID
                -- Include fund table data through the linking table Dealsfund
                LEFT JOIN
                    DealsFund
                ON 
                    DealsFund.DealsID = Deals.DealsID 
                -- include Fund table data
                LEFT JOIN
                    Fund
                ON
                    Fund.FundID = DealsFund.FundID 
                -- Include News table data 
                LEFT JOIN 
                    News 
                ON
                    News.NewsID = Deals.NewsID 
                LEFT JOIN 
                -- Include PortfoliCompany table data
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = Deals.PortfolioCompanyID
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
                    PortfolioCompanyLocation
                ON
                    PortfolioCompanyLocation.PortfolioCompanyID = Deals.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyLocation.CountryID
                LEFT JOIN 
                    DealsIndustry
                ON 
                    DealsIndustry.DealsID = Deals.DealsID
                LEFT JOIN 
                    Industry
                ON 
                    Industry.IndustryID = DealsIndustry.IndustryID
                LEFT JOIN 
                    DealsSector
                ON 
                    DealsSector.DealsID = Deals.DealsID
                LEFT JOIN 
                    Sector
                ON 
                    Sector.SectorID = DealsSector.SectorID
                LEFT JOIN 
                    DealsUserDetail
                ON 
                    DealsUserDetail.DealsID = Deals.DealsID
                LEFT JOIN 
                    UserDetail
                ON 
                    UserDetail.UserDetailID = DealsUserDetail.UserDetailID
                LEFT JOIN 
                    RoleType
                ON 
                    RoleType.RoleTypeID = UserDetail.RoleTypeID
                WHERE 
                    Deals.Deleted = 0 AND Deals.DealsID = '$DealsID'
                GROUP BY DealsID, NewsID, NewsURL, NewsDate, PortfolioCompanyName, InvestmentValue, stake, Country, RoleType
                ORDER BY  News.NewsDate
    ";
    $resultA1 = $conn->query($sqlA1) or die($conn->error);
    $rowA1 = mysqli_fetch_assoc($resultA1);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | View Deal </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/admin.css">
    </head>
    <body>
    <!-- HEADER CONTENT -->
        <nav class="  navbar navbar-expand-lg align-middle text-light navbar-dark" style="z-index: 1; font-size: 1rem !important;">
            <div class="container-fluid px-0">
                <a style="color:#ffffff;" class="navbar-brand" href="../../../Admin.php"><img style=" width: 48px;" class="home-ico" src="../../../resources/DCA_Admin.png" alt="Digital collective africa logo"> <small>VC ReportStream</small>  </a>
                <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
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
                        <li class="nav-item">
                            <form action="../../Auth/logout.php" method="POST"  class="profile">
                                <input class="logout_btn" type="submit" name="logout"  value="logout" formmethod="POST">
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div style="height: 10px;"></div>
        <!-- main -->
        <main  class="py-5 ">
            <form name="form"  class="form container"> 
                <div class="row">
                    <p class="col-lg-6">
                        <label for="NewsDate">News Date</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['NewsDate'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">News URL</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['NewsURL'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Portfolio Company</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['PortfolioCompanyName'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Investment Manager(s)</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['InvestorName'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Fund(s)</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['FundName'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Investment Value</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['InvestmentValue'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">stake</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['stake'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Industry</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['Industry'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Sector</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['Sector'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Country</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['Country'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Contact</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['UserFullName'];?>" DISABLED/>
                    </p>
                    <p class="col-lg-6">
                        <label for="NewsDate">Role</label>
                        <input class="form-control col" type="text" name="NewsDate" value="<?php echo $rowA1['RoleType'];?>" DISABLED/>
                    </p>

                </div>
                <button type="button" class="btn btn-danger"><a href="../NewDeals.php"> Close</a></button>
                <button type="button" class="btn btn-warning"><a href="../../crud/edit_deals.php?DealsID=<?php echo $rowA1['DealsID']; ?>">Edit</a></button>
            </form>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        
    </body>
</html>










