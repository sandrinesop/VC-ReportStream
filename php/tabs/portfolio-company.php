<?php 

    // ======================================
    // STARTING A NEW SESSION FOR EACH USER
    // ======================================
    session_start();
    // NOW WE SET A CONDITION TO PREVENT UNAUTHORISED USERS TO ACCESS THIS PAGE.
    if( $_SESSION == []){
        header('refresh:5; url = ../../index.php');
        echo'
            <p> 
                Access denied. Only Admins can access this page. <br/>
                <small>You are being redirected back to the home page.</small>
            </p>
        ';
        exit;
    }
    
    // CONNECT TO DATABASE
    include_once('../App/connect.php');
    include('../App/Company_Processing.php');
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
            <div class=" my-5">
                <!-- CREATE NEW PORTFOLIO COMPANY -->
                <div class="my-2">
                    <div class="row">
                        <span class="col-4">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#companiesModal">
                                Portfolio Company <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="companiesModal" tabindex="-1" aria-labelledby="companiesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="companiesModalLabel">Create A New Portfolio Company</h5>
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
                                                        <!-- <select class="form-select" id="Headquarters" name="Headquarters" required>
                                                            <option> Select...</option>
                                                            
                                                        </select> -->

                                                        <select id="Headquarters" name="Headquarters[]"  class="form-select headquartersDropdowns" multiple="true" required>
                                                            <option>choose...</option>
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
                                                            <input type="file" class="form-control" id="img" name="img" >
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
                        <span class="col-4"> 
                            <a href="javascript:void(0);" class="btn btn-outline-success" onclick="formToggle('ImportFrm');">Import</a>
                            <div id="ImportFrm" class="mt-1" style="display:none;">
                                <form action="../Import/ImportPC.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" accept=".csv" name="file"><br>
                                    <input type="submit" class="btn btn-outline-primary" name="ImportSubmit" value="IMPORT" >
                                </form>
                            </div>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col-3"> 
                            <form action="../PCExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export</button>
                            </form>
                        </span>
                    </div>
                </div>
                <!-- DISPLAY TABLE OF ALL PORTFOLIO COMPANIES --> 
                <div class="card">
                    <div class="card-body table_container" >
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class=" tbl table table-hover table-striped table-success table-bordered" style=" line-height: 10px;" id="table_PortfolioCompany">
                                <thead> 
                                    <th scope="col" > Company </th>
                                    <!-- <th scope="col" >Investment Manager(s)</th>
                                    <th scope="col" >Fund(s)</th>
                                    <th scope="col" >Currency </th> -->
                                    <th scope="col" > Website</th>
                                    <th scope="col" >Industry</th>
                                    <!-- <th scope="col" >Sector</th> -->
                                    <!-- <th scope="col" >Details</th>
                                    <th scope="col" >Year Founded</th>
                                    <th scope="col" >Country</th> -->
                                    <th scope="col" >CEO </th>
                                    <!-- <th scope="col" >CEO Gender</th>
                                    <th scope="col" >CEO Race</th> -->
                                    <th scope="col" >Logo</th>
                                    <th scope="col" >View More</th>
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
                                            <!-- <td class="text-truncate"> <small> <?php echo $rows['InvestorName'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['FundName'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Currency'] ?> </small></td> -->
                                            <td class="text-truncate"> <small> <a href="<?php echo $rows['Website'] ?>" target="_Blank"><?php echo $rows['Website'] ?></a> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Industry'] ?> </small></td>
                                            <!-- <td class="text-truncate"> <small> <?php echo $rows['Sector'] ?> </small></td> -->
                                            <!-- <td class="text-truncate"> <small> <?php echo $rows['Details'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['YearFounded'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Country'] ?> </small></td> -->
                                            <td class="text-truncate"> <small> <?php echo $rows['UserFullName'] ?> </small></td>
                                            <!-- <td class="text-truncate"> <small> <?php echo $rows['Gender'] ?> </small></td>
                                            <td class="text-truncate"> <small> <?php echo $rows['Race'] ?> </small></td> -->
                                            <td class="text-truncate"> <small> <?php echo '<img src="data:image;base64,'.base64_encode($rows['Logo']).'" style="width:100px; height:60px;">'?> </small></td>
                                            <td> <a href="./SingleView/ViewCompany.php?PortfolioCompanyID=<?php echo $rows['PortfolioCompanyID'];?>">View More</a></td>
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