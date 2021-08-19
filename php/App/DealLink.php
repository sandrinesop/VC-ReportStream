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
        $NewsNote       = mysqli_real_escape_string($conn, $_POST['NewsNote']);
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
            $sql2 = "   INSERT INTO 
                            Note(NoteID, CreatedDate, ModifiedDate,Deleted, DeletedDate, Note, NoteTypeID )
                        VALUES 
                            (uuid(), now(), now(), 0,NULL, '$NewsNote','fb44ee75-7056-11eb-a66b-96000010b114')";
            $query2 = mysqli_query($conn, $sql2);
    
            if ($query2 ){
                    // Success
            } else {
                echo 'Oops! There was an error saving Deal Note item. Please report bug to support.'.'<br/>'.mysqli_error($conn);
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
?>
