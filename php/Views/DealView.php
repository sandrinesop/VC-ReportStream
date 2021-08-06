<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FROM DATA
    $DealsID =$_GET['DealsID'];
    // QUERY DATABASE FROM DATA
    $sqlA1="    SELECT DISTINCT
                    Deals.DealsID, News.NewsID, News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, FORMAT(deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue', deals.stake, GROUP_CONCAT(DISTINCT Industry) AS Industry , GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country, UserDetail.UserFullName, Roletype.RoleType
                FROM 
                    Deals 
                -- Include investor table data through the linking table dealsinvestor
                LEFT JOIN
                    DealsInvestor
                ON 
                    DealsInvestor.DealsID = Deals.DealsID
                -- Include Investor table data
                LEFT JOIN
                    Investor
                ON
                    Investor.InvestorID = DealsInvestor.InvestorID
                -- Include fund table data through the linking table dealsfund
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
                    News.NewsID = deals.NewsID 
                LEFT JOIN 
                -- Include PortfoliCompany table data
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = deals.PortfolioCompanyID
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
                    PortfolioCompanyCountry.PortfolioCompanyID = deals.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyCountry.CountryID
                LEFT JOIN 
                    DealsIndustry
                ON 
                    DealsIndustry.DealsID = deals.DealsID
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
                    UserDetail
                ON 
                    UserDetail.UserDetailID = Deals.UserDetailID
                LEFT JOIN 
                    RoleType
                ON 
                    RoleType.RoleTypeID = UserDetail.RoleTypeID
                WHERE 
                    Deals.Deleted = 0 AND Deals.DealsID = '$DealsID'
                GROUP BY DealsID, NewsID, NewsURL, NewsDate, PortfolioCompanyName, InvestmentValue, stake, Country, UserFullName, RoleType
                ORDER BY  news.NewsDate
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
                <button><a href="../../index.php"> Close</a></button>
            </form>
        </main>
    </body>
</html>










