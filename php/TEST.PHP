
<main class="container ">
    <!-- ==== LIST OF PORTFOLIO COMPANIES ==== -->
    <div class=" my-5">
        <div class="my-2">
            <div class="row">
                <!-- CREATE NEW PORTFOLIO COMPANY MODAL -->
                <span class="col-3">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Portfolio Company <img src="../resources/icons/New.svg" alt="">
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create New Fund</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="container" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <label for="FundName" class="form-label">Fund Name</label>
                                                    <input type="FundName" class="form-control" id="FundName" aria-describedby="FundName" name="FundName" required>
                                            </div>
                                            <!-- Actual Currencies as in the DB --> 
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                <label for="Currency" class="form-label">Currency</label>
                                                <select name="Currency" class="form-select" id="Currency" required>  
                                                    <option value="" selected >Choose...</option>
                                                    <option value="Ethiopia Birr" >Ethiopia Birr</option>
                                                    <option value="Angola Kwanza" >Angola Kwanza</option>
                                                    <option value="Botswana Pula" >Botswana Pula</option>
                                                    <option value="British Pound" >British Pound</option>
                                                    <option value="Canada Dollar" >Canada Dollar</option>
                                                    <option value="Central African CFA franc" >Central African CFA franc</option>
                                                    <option value="Congo Franc" >Congo Franc</option>
                                                    <option value="Denmark Krone" >Denmark Krone</option>
                                                    <option value="Egypt Pound" >Egypt Pound</option>
                                                    <option value="Euro" >Euro</option>
                                                    <option value="Ghana Cedi" >Ghana Cedi</option>
                                                    <option value="Indian Rupee" >Indian Rupee</option>
                                                    <option value="Israel Shekel" >Israel Shekel</option>
                                                    <option value="Kenya Shilling" >Kenya Shilling</option>
                                                    <option value="Liberia Dollar" >Liberia Dollar</option>
                                                    <option value="Madagascar Ariary" >Madagascar Ariary</option>
                                                    <option value="Malawi Kwacha" >Malawi Kwacha</option>
                                                    <option value="Malaysia Ringgit" >Malaysia Ringgit</option>
                                                    <option value="Mauritius Rupee" >Mauritius Rupee</option>
                                                    <option value="Morocco Dirham" >Morocco Dirham</option>
                                                    <option value="Mozambique Metical" >Mozambique Metical</option>
                                                    <option value="Namibian Dollar" >Namibian Dollar</option>
                                                    <option value="Nepal Rupee" >Nepal Rupee</option>
                                                    <option value="Nigeria Naira" >Nigeria Naira</option>
                                                    <option value="Norway Krone" >Norway Krone</option>
                                                    <option value="Pakistan Rupee" >Pakistan Rupee</option>
                                                    <option value="Rwanda Franc" >Rwanda Franc</option>
                                                    <option value="Sierra Leone Leone" >Sierra Leone Leone</option>
                                                    <option value="Singapore Dollar" >Singapore Dollar</option>
                                                    <option value="Somalia Shilling" >Somalia Shilling</option>
                                                    <option value="South African Rand" >South African Rand</option>
                                                    <option value="Sudan Pound" >Sudan Pound</option>
                                                    <option value="Switzerland Franc" >Switzerland Franc</option>
                                                    <option value="Tanzania Shilling" >Tanzania Shilling</option>
                                                    <option value="Tunisia Dinar" >Tunisia Dinar</option>
                                                    <option value="Uganda Shilling" >Uganda Shilling</option>
                                                    <option value="United Arab Emirates Dirham" >United Arab Emirates Dirham</option>
                                                    <option value="Unknown Currency" >Unknown Currency</option>
                                                    <option value="US Dollar" >US Dollar</option>
                                                    <option value="West African CFA franc" >West African CFA franc</option>
                                                    <option value="Zambia Kwacha" >Zambia Kwacha</option>
                                                    <option value="Zimbabwe Dollar" >Zimbabwe Dollar</option>                          
                                                </select>
                                            </div>  
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <label for="CommittedCapitalOfFund" class="form-label"> Committed Capital Of Fund</label>
                                                <input type="text" class="form-control" id="CommittedCapitalOfFund" name="CommittedCapitalOfFund" >
                                            </div> 
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <label for="CommittedCapital" class="form-label"> Committed Capital</label>
                                                <input type="number" class="form-control" id="CommittedCapital" name="CommittedCapital" required>
                                            </div>  
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <label for="MinimumInvestment" class="form-label"> Minimum Investment</label>
                                                <input type="number" class="form-control" id="MinimumInvestment" name="MinimumInvestment" required>
                                            </div> 
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <label for="MaximumInvestment" class="form-label"> Maximum Investment</label>
                                                <input type="number" class="form-control" id="MaximumInvestment" name="MaximumInvestment" required>
                                            </div>
                                            <!-- Investment Stage -->
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                <label for="InvestmentStage" class="form-label">Investment Stage </label>
                                                <select name="InvestmentStage" class="form-select" id="InvestmentStage" required>
                                                    <option value=""selected >Choose...</option>
                                                    <option value="Pre-Seed">Pre-Seed</option>
                                                    <option value="Seed">Seed</option>
                                                    <option value="Pre-Series A">Pre-Series A</option>
                                                    <option value="Series A">Series A</option>
                                                    <option value="Pre-Series B">Pre-Series B</option>
                                                    <option value="Series B">Series B</option>
                                                    <option value="Pre-Series C">Pre-Series C</option>
                                                    <option value="Series C">Series C</option>
                                                    <option value="Pre-Series D">Pre-Series D</option>
                                                    <option value="Series D">Series D</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                <label for="IndustryPreference" class="form-label">Industry Preference</label>
                                                <textarea class="form-control IndustryPreference" aria-label="With textarea" id=" IndustryPreference" name=" IndustryPreference"></textarea>
                                            </div>
                                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                <label for="FundNote" class="form-label">Fund Note</label>
                                                <textarea class="form-control FundNote" aria-label="With textarea" id=" FundNote" name="FundNote"></textarea>
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
                <!-- EXPORT CSV FILE -->
                <span class="col-2"> 
                    <form action="./PCExport.php" method="POST">
                        <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                    </form>
                </span>
            </div>
        </div>
        <!-- TABLE OF ALL PORTFOLIO COMPANIES -->
        <div class="table-responsive" style="overflow-x:auto;">
            <table class=" table table-hover table-striped table-success table-bordered" style="line-height: 30px;">
                <t>
                    <th scope="col" >Portfolio Company ID</th> 
                    <th scope="col" >Created Date</th> 
                    <th scope="col" >Modified Date</th>   
                    <th scope="col" >Portfolio Company Name</th>
                    <th scope="col" >Currency ID</th>
                    <th scope="col" >Portfolio Company Website</th>
                    <th scope="col" >TotalInvestment Value</th>
                    <th scope="col" >Stake</th>
                    <th scope="col" >Details</th>
                    <th scope="col" >Year Founded</th>
                    <th scope="col" >Headquarters</th>
                    <th scope="col" >Industry ID</th>
                    <th scope="col" >Sector ID</th>
                </t>
                <?php
                    while($rows = mysqli_fetch_assoc($result))
                    {
                ?>
                    <tr>
                        <td> <?php echo $rows['PortfolioCompanyID'] ?></td>
                        <td> <?php echo $rows['CreatedDate'] ?></td>
                        <td> <?php echo $rows['ModifiedDate'] ?></td>        
                        <td> <?php echo $rows['PortfolioCompanyName'] ?></td>
                        <td> <?php echo $rows['CurrencyID'] ?></td>
                        <td> <?php echo $rows['PortfolioCompanyWebsite'] ?></td>
                        <td> <?php echo $rows['TotalInvestmentValue'] ?></td>
                        <td> <?php echo $rows['Stake'] ?></td>
                        <td> <?php echo $rows['Details'] ?></td>
                        <td> <?php echo $rows['YearFounded'] ?></td>
                        <td> <?php echo $rows['Headquarters'] ?></td>
                        <td> <?php echo $rows['IndustryID'] ?></td>
                        <td> <?php echo $rows['SectorID'] ?></td>
                    </tr>
                <?php 
                    }
                ?>
            </table>
        </div> 
    </div>
</main>
        
            <!-- 
                /////////////////////
                USER DETAIL
                /////////////////////
            -->
            <!-- <div class="row">
                <h2 class="investor-h2">
                    Portfolio Company Contact Person
                </h2>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="FirstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="LastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="LastName" name="LastName" required>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="Email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="Email" name="Email" required>
                </div> 
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="ContactNumber1" class="form-label">ContactNumber1</label>
                    <input type="text" class="form-control" id="ContactNumber1" name="ContactNumber1" required>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="ContactNumber2" class="form-label">ContactNumber2</label>
                    <input type="text" class="form-control" id="ContactNumber2" name="ContactNumber2">
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="RoleType" class="form-label">RoleType</label>
                    <select name="RoleType" class="form-select" id="RoleType" required>
                        <option value="" selected >Choose...</option>
                        <option value="President">President</option>
                        <option value="CEO">CEO</option>
                        <option value="CFO">CFO</option>
                        <option value="COO">COO</option>
                    </select>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="Gender" class="form-label">Gender</label>
                    <select name="Gender" class="form-select" id="Gender" required>
                        <option value="" selected >Choose...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Unknown">Unknown</option>
                    </select>
                </div>
                <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                    <label for="Race" class="form-label">Race</label>
                    <select name="Race" class="form-select" id="Race" required>
                        <option value="" selected >Choose...</option>
                        <option value="Black">Black</option>
                        <option value="White">White</option>
                        <option value="Asian">Asian</option>
                        <option value="Indian">Indian</option>
                        <option value="Unknown">Unknown</option>
                    </select>
                </div>
            </div> -->