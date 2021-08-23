<?php 
    include_once('../App/connect.php');
    include_once('../App/DealLink.php'); // WITHIN THIS SCRIPT IS WHERE I AM RUNNING ALL THE PROCESSESS OF CREATING NEW DEALS 
    // QUERY DATABASE FROM DATA
    $sqlAA="    SELECT
                    Deals.DealsID, News.NewsID, News.NewsURL, News.NewsDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, FORMAT(Deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue', Deals.stake, GROUP_CONCAT(DISTINCT Industry) AS Industry , GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country, UserDetail.UserFullName, RoleType.RoleType, Note.Note
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
                    PortfolioCompanyCountry
                ON
                    PortfolioCompanyCountry.PortfolioCompanyID = Deals.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyCountry.CountryID
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
                    UserDetail
                ON 
                    UserDetail.UserDetailID = Deals.UserDetailID
                LEFT JOIN 
                    RoleType
                ON 
                    RoleType.RoleTypeID = UserDetail.RoleTypeID
                LEFT JOIN 
                    DealsNote
                ON 
                    DealsNote.DealsID = Deals.DealsID
                LEFT JOIN 
                    Note
                ON 
                    Note.NoteID =DealsNote.NoteID
                WHERE 
                    Deals.Deleted = 0
                GROUP BY DealsID, NewsID, NewsURL, NewsDate, PortfolioCompanyName, InvestmentValue, stake, Country, UserFullName, RoleType, Note
                ORDER BY  News.NewsDate
    ";

    $resultAA = $conn->query($sqlAA);// or die($conn->error)
    $rowAA = mysqli_fetch_assoc($resultAA);

    //==================================================== 
    // BELOW IS CODE DISPLAYING DATA ON deals SCREEN TABLE
    //====================================================
    //========== | PORTFOLIO COMPANY TABLE | =============
    //====================================================
    // PORTFOLIO COMPANY DETAILS. THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    $sql = " SELECT 
                PortfolioCompanyName, Website, SUBSTRING(Details, 1, 55) AS Details FROM PortfolioCompany 
            JOIN 
                Country ON Country.CountryID = PortfolioCompany.Headquarters 
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
    $sqlB = "  SELECT 
                    FundName, CommittedCapital, MinimumInvestment, MaximumInvestment
                FROM 
                    Fund
                WHERE FundName IS NOT NULL AND CommittedCapital IS NOT NULL AND MinimumInvestment IS NOT NULL AND MaximumInvestment IS NOT NULL ";
    $resultB = mysqli_query($conn, $sqlB);
    // Pulling Fund Data into the FundName dropdown
    $sqlB1 = "  SELECT DISTINCT
                    InvestmentStage
                FROM 
                    InvestmentStage
                WHERE InvestmentStage IS NOT NULL ORDER BY InvestmentStage ASC";
    $resultB1 = mysqli_query($conn, $sqlB1);

    // QUERY DATABASE TO DISPLAY DATA INSIDE THE PieChart
    $chartQuery ="  SELECT
	                    Sector.Sector, COUNT(*) AS Percentage FROM DealsSector
                    
                    Left Join 
                        Sector
                    ON
                        Sector.SectorID = DealsSector.SectorID
                    GROUP BY
                        Sector.Sector
    ";
    $resultQuery = mysqli_query($conn, $chartQuery);

    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | deals </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/select2.min.css">
        <!-- <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/bootstrap.css"> -->
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
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row w-25 ">
                        <!-- EXPORT CSV FILE -->
                        <span class="col"> 
                            <form action="../DealExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> <small>Export CSV</small></button>
                            </form>
                        </span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body " style="background-color:#5d8f18;">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered" style="Width: 3600px; line-height: 18px;" id="table_Deals">
                                <thead>
                                    <th scope="col">Date </th>
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
                                    <th scope="col">Deal Notes </th>
                                    <th scope="col">View More </th>
                                    <th scope="col">Edit  </th>
                                    <th scope="col">Delete </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while(($rowAA = mysqli_fetch_assoc($resultAA)))
                                        {
                                    ?>
                                    <tr data-href="../crud/edit_deals.php?DealsID=<?php echo $rowAA['DealsID'];?>">
                                        <td class="text-truncate"> <small ><?php echo $rowAA["NewsDate"];?> </small> </td>
                                        <td class="text-truncate"> <a href="<?php echo $rowAA["NewsURL"];?>" target="_blank"><small > <?php echo $rowAA["NewsURL"];?></small></a></td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["PortfolioCompanyName"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["InvestorName"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["FundName"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo '$'.$rowAA["InvestmentValue"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["stake"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["Industry"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["Sector"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["Country"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["UserFullName"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["RoleType"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $rowAA["Note"];?> </small></td>
                                        <td> <a href="../Views/ViewDeal.php?DealsID=<?php echo $rowAA['DealsID'];?>">View Deal</a></td>
                                        <td class="text-truncate"> <a href="../crud/edit_deals.php?DealsID=<?php echo $rowAA['DealsID']; ?>">Edit</a></td>
                                        <td class="text-truncate"> <a href="../crud/delete_deals.php?DealsID=<?php echo $rowAA['DealsID']; ?>">Delete</a></td>
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

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
            <script type="text/javascript">
                // Loading the Visualization API and the corechart package.
                google.charts.load('current', {'packages':['corechart']});

                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                
                google.charts.setOnLoadCallback(drawChart2);

                // Callback that creates and populates a data table,
                // instantiates the pie chart, passes in the data and draws it.
                function drawChart() {

                    // Create the data table.
                    var data =  google.visualization.arrayToDataTable([
                        ['Sector', 'Percentage'],
                        <?php
                            while($chartRow = mysqli_fetch_assoc($resultQuery)){
                                echo"['".$chartRow['Sector']."', ".$chartRow['Percentage']."],";
                            }
                        ?>
                    ]);

                    var options= {
                        title: 'Percentage of Sectors'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('pieChart'));
                    chart.draw(data, options);
                };

                // second chart drawing
                function drawChart2() {

                // Create the data table.
                var data =  google.visualization.arrayToDataTable([
                    ['Sector', 'Percentage'],
                    <?php
                        mysqli_data_seek($resultQuery, 0);
                        while($chartRow = mysqli_fetch_assoc($resultQuery)){
                            echo"['".$chartRow['Sector']."', ".$chartRow['Percentage']."],";
                        }
                    ?>
                ]);

                var options= {
                    title: 'Percentage of Sectors'
                };

                var chart = new google.visualization.BarChart(document.getElementById('BarChart'));
                chart.draw(data, options);
                };
            </script>
            <!-- 
                DASHBOARD SECTION USING GOOGLEW CHARTS API 
             -->
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title"> Dashboard | Data breakdown using the MySQL Database data.</h4>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div id="pieChart"  style=" height:270px;" class="col-6 col">
                        </div>
                        <div id="BarChart"  style=" height:270px;" class="col-6 col">
                        </div> 
                    </div>  
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/select2.min.js"></script>
        <script src="../../js/scripts.js"></script>
        <script src="../../js/MultiSelect.js"></script>
        <script src="../../DataTables/datatables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script>
            var createWindow;
            // open window
            function openWin() {
                createWindow = window.open("./SubFunctions/create_contact.php", "_blank", "width=920, height=500");
            }

            var createWindow1;
            // open window
            function openWin1() {
                createWindow1 = window.open("./SubFunctions/create_investor.php", "_blank", "width=920, height=500");
            }

            var createWindow2;
            // open window
            function openWin2() {
                createWindow2 = window.open("./SubFunctions/create_fund.php", "_blank", "width=920, height=500");
            }
        </script>
        <script>
            $(document).ready( function () {    
                // Initializing the datatable plugin
                $('#table_Deals').DataTable();

                // Trigger the double tap to edit function
                $(document.body).on("dblclick", "tr[data-href]", function (){
                    window.location.href = this.dataset.href;
                });
            });
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


                 

