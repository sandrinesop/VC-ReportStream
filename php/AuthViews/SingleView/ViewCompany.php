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
        <link rel="stylesheet" href="../../../css/admin.css">
    </head>
    <body>
        <!-- HEADER CONTENT -->
        <nav class="  navbar navbar-expand-lg align-middle text-light navbar-dark" style="z-index: 1; font-size: 1rem !important;">
            <div class="container px-0">
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
        <!-- MAIN SECTION -->
        <main class="single_View py-5">
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
                    <button type="button" class="btn btn_close">
                        <a href="../portfolio-company.php"> Close</a>
                    </button>
                    <button type="button" class="btn btn_edit">
                        <a href="../../crud/edit_PC.php?PortfolioCompanyID=<?php echo $row['PortfolioCompanyID']; ?>">Edit</a>
                    </button>
                </div>
            </div>
        </main>    
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>    
    </body>
</html>