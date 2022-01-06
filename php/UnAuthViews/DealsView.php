<?php 
    include_once('../App/connect.php');
    // QUERY DATABASE FOR DATA WE'LL DISPLAY ON THE SCREEN
    $sql="  SELECT Deals.DealsID
                ,News.NewsID
                ,News.NewsURL
                ,News.NewsDate
                ,PortfolioCompany.PortfolioCompanyName
                ,GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName
                ,GROUP_CONCAT(DISTINCT FundName) AS FundName
                ,FORMAT(Deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue'
                ,Deals.stake
                ,GROUP_CONCAT(DISTINCT Industry) AS Industry
                ,GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector
                ,GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage
                ,Country.Country
                ,Note.Note
            FROM Deals
            -- Joining linking tables
            LEFT JOIN DealsInvestor ON DealsInvestor.DealsID = Deals.DealsID
            LEFT JOIN Investor ON Investor.InvestorID = DealsInvestor.InvestorID
            LEFT JOIN DealsFund ON DealsFund.DealsID = Deals.DealsID
            LEFT JOIN Fund ON Fund.FundID = DealsFund.FundID
            -- Include News table data 
            LEFT JOIN News ON News.NewsID = Deals.NewsID
            LEFT JOIN
                -- Include PortfoliCompany table data
                PortfolioCompany ON PortfolioCompany.PortfolioCompanyID = Deals.PortfolioCompanyID
            LEFT JOIN
                -- Link investment stage to fund
                FundInvestmentStage ON FundInvestmentStage.FundID = Fund.FundID
            LEFT JOIN InvestmentStage ON InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID
            LEFT JOIN PortfolioCompanyLocation ON PortfolioCompanyLocation.PortfolioCompanyID = Deals.PortfolioCompanyID
            LEFT JOIN Country ON Country.CountryID = PortfolioCompanyLocation.CountryID
            LEFT JOIN DealsIndustry ON DealsIndustry.DealsID = Deals.DealsID
            LEFT JOIN Industry ON Industry.IndustryID = DealsIndustry.IndustryID
            LEFT JOIN DealsSector ON DealsSector.DealsID = Deals.DealsID
            LEFT JOIN Sector ON Sector.SectorID = DealsSector.SectorID
            LEFT JOIN DealsNote ON DealsNote.DealsID = Deals.DealsID
            LEFT JOIN Note ON Note.NoteID = DealsNote.NoteID
            WHERE Deals.Deleted = 0
            GROUP BY DealsID
                ,NewsID
                ,NewsURL
                ,NewsDate
                ,PortfolioCompanyName
                ,InvestmentValue
                ,stake
                ,Note
            ORDER BY News.NewsDate
        "; 
            
    $result = $conn->query($sql); //or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    //==================================================== 
    // BELOW IS CODE DISPLAYING DATA ON deals SCREEN TABLE
    //====================================================

    // POPULATING PORTFOLIO COMPANIES DROPDOWN
    $sql1 ="SELECT DISTINCT PortfolioCompanyName
            FROM PortfolioCompany 
            WHERE PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result1 = mysqli_query($conn, $sql1);
    
    // POPULATING UserDetail DROPDOWN 
    $sql2 ="SELECT DISTINCT UserFullName
            FROM UserDetail 
            WHERE UserFullName IS NOT NULL 
            ORDER BY UserFullName ASC";
    $result2 = mysqli_query($conn, $sql2);

    // POPULATING IMPACT-TAG DROPDOWN
    $sql3 ="SELECT DISTINCT InvestorName
            FROM Investor 
            WHERE InvestorName IS NOT NULL ORDER BY InvestorName ASC
    ";
    $result3 = mysqli_query($conn, $sql3);
    // POPULATING COUNTRIES DROPDOWN
    $sql4 ="SELECT DISTINCT FundName
            FROM Fund
            WHERE FundName IS NOT NULL
            ORDER BY FundName ASC
    ";
    $result4 = mysqli_query($conn, $sql4);

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
        <link rel="stylesheet" href="../../css/index.css">
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
        <?php include('./navBar/UnAuth_navBar.php');?> 
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row w-50">
                        <!-- EXPORT CSV FILE -->
                        <span class="col"> 
                            <form action="../ExportCSV/DealExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> <small>Export CSV</small></button>
                            </form>
                        </span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body " style="background-color:#5d8f18;">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" table table-hover table-striped table-success table-bordered" style=" line-height: 18px;" id="table_Deals">
                                <thead>
                                    <th scope="col">Date </th>
                                    <th scope="col">News Link</th>
                                    <th scope="col">Portfolio Company</th>
                                    <th scope="col">Investment Manager(s)</th>
                                    <!-- <th scope="col">Fund(s)</th> -->
                                    <th scope="col">Value Of Investment</th>
                                    <!-- <th scope="col">Stake</th>
                                    <th scope="col">Industry </th>
                                    <th scope="col">Sector(s)</th>
                                    <th scope="col">Portfolio Company Headquarters</th>
                                    <th scope="col">Company Contact(s)</th>
                                    <th scope="col">Role </th>
                                    <th scope="col">Deal Notes </th> -->
                                    <th scope="col">View More </th>
                                </thead>
                                <tbody>
                                    <?php
                                        while(($row = mysqli_fetch_assoc($result)))
                                        {
                                    ?>
                                    <tr data-href="../crud/edit_deals.php?DealsID=<?php echo $row['DealsID'];?>">
                                        <td class="text-truncate"> <small ><?php echo $row["NewsDate"];?> </small> </td>
                                        <td class="text-truncate"> <a href="<?php echo $row["NewsURL"];?>" target="_blank"><small > <?php echo $row["NewsURL"];?></small></a></td>
                                        <td class="text-truncate"> <small ><?php echo $row["PortfolioCompanyName"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["InvestorName"];?> </small> </td>
                                        <!-- <td class="text-truncate"> <small ><?php echo $row["FundName"];?> </small> </td> -->
                                        <td class="text-truncate"> <small ><?php echo '$'.$row["InvestmentValue"];?> </small> </td>
                                        <!-- <td class="text-truncate"> <small ><?php echo $row["stake"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["Industry"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["Sector"];?> </small> </td>
                                        <td class="text-truncate"> <small ><?php echo $row["Country"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $row["UserFullName"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $row["RoleType"];?> </small></td>
                                        <td class="text-truncate"> <small ><?php echo $row["Note"];?> </small></td> -->
                                        <td> <a href="./SingleView/ViewDeal.php?DealsID=<?php echo $row['DealsID'];?>">View Deal</a></td>
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
                    <h4 class="card-title"> Visualization Dashboard | Sector breakdowns.</h4>
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
            $(document).ready( function () {    
                // Initializing the datatable plugin
                $('#table_Deals').DataTable();
            });
        </script>    
    </body>
</html>


                 

