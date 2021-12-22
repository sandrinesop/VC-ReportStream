<?php
    $conn = mysqli_connect('localhost', 'root', '','AA');
    // $conn = mysqli_connect('services.methys-pe.com', 'remote', 'PmHjW$?R/wh:l:cpW%pF@t#*=','AA');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ( isset($_POST['submit']))
    {
        // DECLARED AND SET VARIABLES
        // NEWS TABLE
        $NewsDate       = date('Y-m-d', strtotime($_POST['NewsDate']));
        $NewsURL        = mysqli_real_escape_string($conn, $_POST['NewsURL']);

        if(isset($_POST['NewsNote'])){
            $NewsNote       = mysqli_real_escape_string($conn, $_POST['NewsNote']);
        }
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = $_POST['PortfolioCompanyName'];
        $Stake                   = $_POST['Stake'];
        $InvestmentValue         = $_POST['InvestmentValue'];
        $Industries              = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        // USER DETAIL TABLE
        $StartUpContact          = $_POST['UserFullName'];
        // INVESTOR TABLE
        $InvestorName            = $_POST['InvestorName'];
        // FUND TABLE
        $FundName                = $_POST['FundName'];

        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT NewsURL FROM News WHERE News.NewsURL ='$NewsURL'";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            $conn->close();
            header( "refresh: 3;url= ../tabs/NewDeals.php" );
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H3>Heads Up!</H3>
                    <p style="margin:0;"> <small>New record not created, Deal already exists.</small> </p>
                </div>'
            ;
        }else{
            // ===================================================================================
            // BELOW ARE THE INSERT STATEMENTS TO THE NEWS AND NOTE TABLE. 
            // THESE ARE THE ONLY  TWO TABLES THAT WILL COLLECT NEW DATA UPON ENTERING A NEW DEAL.
            // ===================================================================================
            $sql = "    INSERT INTO 
                            News(NewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsDate, NewsURL) 
                        VALUES 
                            (uuid(), now(), now(),0,NULL,'$NewsDate', '$NewsURL')";
            $query = mysqli_query($conn, $sql);
            
            if ($query ){
                    // Success
            } else {
                echo 'Oops! There was an error saving News item. Please report bug to support.'.'<br/>'.mysqli_error($conn); 
            }
            // Query End

            // CONDITIONAL STATEMENT TO INSERT DATA INTO THE NOTE TABLE
            // IF THE NOTE VARIABLE IS SET THEN INSERT INTO NOTE OTHERWISE DO NOT INSERT.
            if(!empty($NewsNote)){
                $sql2 = "   INSERT INTO 
                                Note(NoteID, CreatedDate, ModifiedDate,Deleted, DeletedDate, Note, NoteTypeID )
                            VALUES 
                                (uuid(), now(), now(), 0,NULL, '$NewsNote','fb44ee75-7056-11eb-a66b-96000010b114')";
                $query2 = mysqli_query($conn, $sql2);
            }else {
                // echo 'Oops! There was an error saving Deal Note item. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
    
            // =====================================================
            // =====================================================
            // ***** INSERT STATEMENTS FOR THE MAPPING TABLES ******
            // =====================================================
            // =====================================================
    
            // PORTFOLIOCOMPANY MAPPING TABLES
            $sqlA1 = "  INSERT INTO PortfolioCompanyNews(PortfolioCompanyNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, NewsID)
                        VALUES (uuid(), now(), now(),0,NULL, (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'))";
            $queryA1 = mysqli_query($conn, $sqlA1);
            if ($queryA1 ){
                // Success
            } else {
                echo 'Oops! There was an error on linking Company and News. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
            }
    
            // FUND MAPPING TABLES
            foreach($FundName as $Fund){ 
                $sqlA3 = "  INSERT INTO 
                                FundNews(FundNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, FundID, NewsID)
                            VALUES 
                                (uuid(), now(), now(),0,NULL, (select distinct Fund.FundID FROM Fund where Fund.FundName = '$Fund'), (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'))";
                $queryA3 = mysqli_query($conn, $sqlA3);
                
                if($queryA3){
                    // Do nothing
                } else {
                    echo 'Oops! There was an error saving links between Funds and Newss from the array'.mysqli_error($conn).'<br/>';
                }
            }
            
            // =====================================================================
            // **** INSERT STATEMENTS FOR THE DEALS CENTRAL CAPTURING TABLES **** //
            // =====================================================================
    
            $sqlDLS = "  INSERT INTO 
                            Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestmentValue, stake, UserDetailID)
                        VALUES 
                            (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), '$InvestmentValue', '$Stake', (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact'))
            ";
            $queryDLS = mysqli_query($conn, $sqlDLS);
            // DLS
            if ($queryDLS){
                // IF THE QUERY ABOVE IS A SUCCESS THEN EXECUTE BELOW CODE
                // =========================================================
                // LOOP TO INSERT INDUSTRIES TO THE LINKING TABLE ON DEALS
                // =========================================================
                foreach($Industries as $Industry){  
                    $sqlDealIndustry = "  INSERT INTO 
                                            DealsIndustry(DealsIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, IndustryID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT Industry.IndustryID FROM Industry WHERE Industry.Industry = '$Industry'))
                    ";
                    $queryIndustry = mysqli_query($conn, $sqlDealIndustry);
                    if($queryIndustry){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error inserting the Industry IDs from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // ======================================================
                // LOOP TO INSERT SectorS TO THE LINKING TABLE ON DEALS
                // ======================================================
                foreach($Sector as $sects){  
                    $sqlDealSector = "  INSERT INTO 
                                            DealsSector(DealsSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, SectorID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT S.SectorID FROM Sector S WHERE S.Sector = '$sects'))
                    ";
                    $query99 = mysqli_query($conn, $sqlDealSector);
                    if($query99){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error inserting the Sector IDs from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // =================================================================
                // LOOP TO INSERT INVESTMENT MANAGERS TO THE LINKING TABLE ON DEALS
                // =================================================================
                foreach($InvestorName as $InvestmentManager){  
                    $sqlDealInvestor = "  INSERT INTO 
                                            DealsInvestor(DealsInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, InvestorID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT Investor.InvestorID FROM Investor WHERE Investor.InvestorName = '$InvestmentManager'))
                    ";
    
                    $query98 = mysqli_query($conn, $sqlDealInvestor);
                    
                    if($query98){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error saving links between Investment Manager and Deals from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // =================================================================
                // LOOP TO INSERT FUNDS TO THE LINKING TABLE ON DEALS
                // =================================================================
                foreach($FundName as $Fund){  
                    $sqlDealFund = "  INSERT INTO 
                                            DealsFund(DealsFundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, FundID)
                                        VALUES 
                                            (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$Fund'))
                    ";
    
                    $query97 = mysqli_query($conn, $sqlDealFund);
                    
                    if($query97){
                        // Do nothing
                    } else {
                        echo 'Oops! There was an error saving links between Funds and Deals from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                        
                // ===============================
                // ===============================
                // Deal Note Mapping tables
                // ===============================
                // ===============================
                if(!empty($NewsNote)){
                    $sqlDealsNote = "   INSERT INTO 
                                            DealsNote(DealsNoteID, CreatedDate, ModifiedDate,Deleted, DeletedDate, DealsID, NoteID)
                                        VALUES 
                                            (uuid(), now(), now(),0,NULL, (SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select Note.NoteID FROM Note where Note.Note = '$NewsNote'))
                    ";
                    $queryDealsNote = mysqli_query($conn, $sqlDealsNote);
                    if ($queryDealsNote ){
                        // Success
                    } else {
                        echo 'Oops! There was an error on linking Deal and Note. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
                    }
                }else {
                    // echo 'Oops! There was an error linking the Deal and Note because Notes field is empty. Please report bug to support.'.'<br/>'.mysqli_error($conn);
                }    
            } else {
                echo 'Oops! There was an error on Deals Capture Table. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
            
            // ===========================================================
            // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
            // ===========================================================
            header("Refresh:5; url=../tabs/NewDeals.php");
            // ===========================================================
            echo '<H3>Thank you for your contibution</H3> '
            .'<br/>'
            .'<small>You will be redirected shortly...</small>';
        }
    };

    
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
    // ================================================
    // ============| CURRENCY DROPDOWN |===============
    // ================================================
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
    //=================================================== 
    //============== | INVESTOR DROPDOWN | =================
    //===================================================
    $sqlA1 = "   SELECT DISTINCT 
                InvestorName
            FROM 
                Investor 
            WHERE 
                InvestorName IS NOT NULL ORDER BY InvestorName ASC
    ";
    $resultA1 = mysqli_query($conn, $sqlA1);

    // ================================================
    // ============| INVETSOR NOTES |===============
    // ================================================. 
    // THIS OVERFLOWS IN THE <OPTION ELEMENT> AND THAT IS WHY I USED THE SUBSTRING METHOD TO TRUNCATE THE STRONG
    $sqlA3 = "  SELECT DISTINCT
                    SUBSTRING(Note, 1, 55) AS Note 
                FROM 
                    Note
                WHERE 
                    Note IS NOT NULL";
    $resultA3 = mysqli_query($conn, $sqlA3);
    //=================================================== 
    //================ | FUND TABLE | ===================
    //===================================================
    // Pulling Fund Data into the Fund Section dropdown
    $sqlB = "  SELECT DISTINCT
                    FundName
                FROM 
                    Fund
                WHERE FundName IS NOT NULL
    ";
    $resultB = mysqli_query($conn, $sqlB);
    
    
    //====================================================================
    //======== | DISPLAY DATA INSIDE THE google charts PieChart | ========
    //====================================================================
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
