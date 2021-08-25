<?php 
    include_once('../App/connect.php');
    include_once('../App/DealLink.php'); // WITHIN THIS SCRIPT IS WHERE I AM RUNNING ALL THE PROCESSESS OF CREATING NEW DEALS 

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
        <link rel="stylesheet" href="../../css/admin.css">
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
                    <div class="row w-50">
                        <!-- CREATE NEW Deal MODAL -->
                        <span class="col">
                            <!-- Button trigger modal -->
                            <small>
                                <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <small>New Deal</small> <img src="../../resources/icons/New.svg" alt="">
                                </button>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create New Deal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" action="../App/DealLink.php" method="POST" enctype="multipart/form-data">
                                                <!--    
                                                        =========================================================================
                                                        ======================== NEWS SECTION ===================================
                                                        =========================================================================
                                                -->
                                                <div class="row"> 
                                                    <h5>
                                                        News 
                                                    </h5>
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
                                                        <textarea class="form-control" aria-label="With textarea" id="NewsNote" name="NewsNote" required></textarea>
                                                    </div>  
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ===================== STARTUP COMPANY SECTION ===========================
                                                        =========================================================================
                                                -->
                                                <div class="row">  
                                                    <h5>
                                                        Company Details
                                                    </h5>
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
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Stake" class="form-label">Stake</label>
                                                        <input type="text" class="form-control" id="Stake" name="Stake"  min="0.01" max="1" step="any">
                                                        <small style="color:red;">Place a zero if stake not disclosed </small>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestmentValue" class="form-label">Total Investment Value</label>
                                                        <input type="number" class="form-control" id="InvestmentValue" name="InvestmentValue" min="1" max="1000000000000" step="any">
                                                    </div>
                                                    <!-- 
                                                        /////////////////////
                                                            INDUSTRY DROPDOWN
                                                        /////////////////////
                                                    -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Industry" class="form-label">Industry</label>
                                                        <select id="Industry" name="Industry[]" class="form-select">
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
                                                    <!-- 
                                                        /////////////////////
                                                            SECTOR DROPDOWN
                                                        /////////////////////
                                                    -->
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 " id="ArtificialIntelligenceDrop">
                                                        <label for="Sector" class="form-label" >Sector </label>
                                                        <select id="Sector" name="Sector[]"  class="form-select sectorDropdowns" multiple="true">
                                                            <option>choose...</option>
                                                        </select>
                                                        <small style="color:red;">First select an industry </small>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                                                        <label for="UserFullName" class="form-label">Contact Person</label>
                                                        <select class="form-select" id="UserFullName" name="UserFullName" required>
                                                            <option> Select Contact Person...</option>
                                                            <?php
                                                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                                                    # code...
                                                                    echo "<option>".$row5['UserFullName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Contact
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ======================== STARTUP CONTACT SECTION ========================
                                                        =========================================================================
                                                -->
                                                <div class="row">
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ======================== INVESTOR SECTION ===============================
                                                        =========================================================================
                                                -->
                                                <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12"> 
                                                        <h5>
                                                            Investment Manager(s)
                                                        </h5>
                                                        <label for="InvestorName" class="form-label"> Name</label>        
                                                        <select class="form-select InvestorName" id="InvestorName" name="InvestorName[]" multiple="true" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                while ($rowA1 = mysqli_fetch_assoc($resultA1)) {
                                                                    # code...
                                                                    echo "<option>".$rowA1['InvestorName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin1()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Manager
                                                            </button>
                                                        </div>
                                                    </div> 
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <h5>
                                                            Fund
                                                        </h5>
                                                        <label for="FundName" class="form-label">Fund Name</label>
                                                        <select  class="form-select FundName" id="FundName" name="FundName[]" multiple="true" required>
                                                            <option value="">Select...</option>
                                                            <?php
                                                                while($rowB = mysqli_fetch_assoc($resultB)){
                                                                    echo "<option>".$rowB['FundName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                        <div class="my-1">
                                                            <button onclick="openWin2()" target="_blank" class="btn btn-outline-success btn-sm">
                                                                Add new Fund
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--    
                                                        =========================================================================
                                                        ======================== INVESTOR CONTACT SECTION ====================
                                                        =========================================================================
                                                -->
                                                <!-- <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 "> 
                                                        <h5>
                                                            Contact Person
                                                        </h5> 
                                                        <label for="UserFullName1" class="form-label">Contact</label>
                                                        <select class="form-select" id="UserFullName1" name="UserFullName1" required>
                                                            <option> Select...</option>
                                                            <?php
                                                                mysqli_data_seek($result5, 0);
                                                                while ($row5 = mysqli_fetch_assoc($result5)) {
                                                                    # code...
                                                                    echo "<option>".$row5['UserFullName']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> -->
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
                            <div id="ImportFrm" class="my-1" style="display:none;">
                                <form action="../Import/DealsImport.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="file">
                                    <input type="submit" class="btn btn-outline-success" name="ImportSubmit" value="IMPORT">
                                </form>
                            </div>
                        </span>
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


                 

