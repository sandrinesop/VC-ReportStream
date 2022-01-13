<?php
    // INCLUDE DATABASE CONNECTION  
    include_once('../../App/connect.php');
    $PortfolioCompanyID = $_GET['PortfolioCompanyID'];
    // echo 'You have viewed company with ID: '. $PortfolioCompanyID;
    // QUERY DATABASE FROM DATA
    $sql="  SELECT DISTINCT
                PortfolioCompany.PortfolioCompanyID,PortfolioCompany.Deleted, PortfolioCompany.DeletedDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, Currency.Currency, PortfolioCompany.Website, GROUP_CONCAT(DISTINCT Industry) AS Industry, GROUP_CONCAT(DISTINCT Sector) AS Sector,  PortfolioCompany.Details, PortfolioCompany.YearFounded, GROUP_CONCAT(DISTINCT Country) AS Country, PortfolioCompany.Logo, UserDetail.UserFullName, Gender.Gender, Race.Race
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
                PortfolioCompanyLocation
            ON
                PortfolioCompanyLocation.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Country 
            ON 
                Country.CountryID = PortfolioCompanyLocation.CountryID
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
                PortfolioCompany.Deleted = 0 AND PortfolioCompany.PortfolioCompanyID = '$PortfolioCompanyID'
                
            GROUP BY PortfolioCompany.PortfolioCompanyID,PortfolioCompany.Deleted, PortfolioCompany.DeletedDate, PortfolioCompany.PortfolioCompanyName, Currency.Currency, PortfolioCompany.Website, PortfolioCompany.Details, PortfolioCompany.YearFounded, PortfolioCompany.Logo
    "; 
    $result = mysqli_query($conn, $sql);
    if($result){
        // echo 'Query success';
        $row = mysqli_fetch_assoc($result);
    }else{
        echo 'Query Failed: '.mysqli_error($conn);
    };
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VC ReportStream |View Company</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/main.css">
        <link rel="stylesheet" href="../../../css/navbar.css">
    </head>
    <body>
        <!-- NAVBAR -->
        <?php 
            include_once('../navBar/singleViewNav.php');
        ?>
        <!-- MAIN SECTION -->
        <main class="single_View ">
            <div class="p-2">
                <div class="container card-view-body"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="PortfolioCompanyName" class="form-label">Company Name</h3>
                            <p name="PortfolioCompanyName">
                                <?php echo $row['PortfolioCompanyName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Currency" class="form-label">Company Currency</h3>
                            <p name="Currency">
                                <?php echo $row['Currency'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Website" class="form-label">Website</h3>
                            <p name="Website">
                                <a href="<?php echo $row['Website'] ?>" target="_blank"><?php echo $row['Website'] ?></a>
                            </p>
                        </div>
                        <!--   INDUSTRY DROPDOWN  -->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Industry" class="form-label">Industry</h3>
                            <p name="Industry">
                                <?php echo $row['Industry'] ?>
                            </p>
                        </div>
                        <!-- SECTOR DROPDOWN | Data being fed through JQuery -->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Sector" class="form-label">Sector</h3>
                            <p name="Sector">
                                <?php echo $row['Sector'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="YearFounded" class="form-label">Year Founded</h3>
                            <p name="YearFounded">
                                <?php echo $row['YearFounded'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Headquarters" class="form-label">Headquarters</h3>
                            <p name="Country">
                                <?php echo $row['Country'] ?>
                            </p>
                        </div>
                        <!-- COMPANY CONTACT -->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="UserFullName" class="form-label">Contact </h3>
                            <p name="UserFullName">
                                <?php echo $row['UserFullName'] ?>
                            </p>
                        </div>
                        <div class="col-12 ">
                            <h3 for="Details" class="form-label">Details</h3>
                            <p name="Details">
                                <?php echo $row['Details'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="InvestorName" class="form-label">Investor(s)</h3>
                            <p name="InvestorName">
                                <?php echo $row['InvestorName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="FundName" class="form-label">Fund(s)</h3>
                            <p name="FundName">
                                <?php echo $row['FundName'] ?>
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <h3 for="Logo" class="form-label">Logo</h3>
                            <p style="background-color:#8080807a;  padding:10px;">
                                <?php echo '<img src="data:image;base64,'.base64_encode($row['Logo']).'" style="width:100px;"'?> 
                            </p>
                        </div>
                    </div>
                    <button><a href="../CompaniesView.php"> Close</a></button>
                </div>
            </div>
        </main>
        <!-- SCRIPTS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>        
    </body>
</html>