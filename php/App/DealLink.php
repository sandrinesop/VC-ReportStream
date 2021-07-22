<?php
    $conn = mysqli_connect('localhost', 'root', '','AA');
    
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
        $Industry                = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];

        // USER DETAIL TABLE
        $StartUpContact          = $_POST['UserFullName'];
        $InvestorContact         = $_POST['UserFullName1'];
        // INVESTOR TABLE
        $InvestorName            = $_POST['InvestorName'];
        // FUND TABLE
        $FundName                = $_POST['FundName'];
        
        /* 
            ///////////////////////////////////////////////////
            Loop through the array of sectors
            ///////////////////////////////////////////////////
        */

        // foreach($Sector as $sects)  
        // {  
        //     $sectors.= $sects.",";  
        // }  

        // ====================================================================================================================================================
        // ====================================================================================================================================================
        // BELOW ARE THE INSERT STATEMENTS TO THE NEWS AND NOTE TABLE. THIS IS ONE OF THE ONLY  TWO TABLES THAT WILL COLLECT NEW DATA UPON ENTERING A NEW DEAL.
        // ====================================================================================================================================================
        // ====================================================================================================================================================
        // ===============================
        // ===============================
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
                        Note(NoteID, CreatedDate, ModifiedDate, Note, NoteTypeID )
                    VALUES 
                        (uuid(), now(), now(), '$NewsNote','fb44ee75-7056-11eb-a66b-96000010b114')";
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
        // PortfolioCompany Mapping tables
        // ===============================
        // ===============================

        $sqlA1 = "  INSERT INTO PortfolioCompanyNews(PortfolioCompanyNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, NewsID)
                    VALUES (uuid(), now(), now(),0,NULL, (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct news.NewsID FROM news where news.NewsURL = '$NewsURL'))";
        $queryA1 = mysqli_query($conn, $sqlA1);
        if ($queryA1 ){
            // Success
        } else {
            echo 'Oops! There was an error on linking Portfolio Company News. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
        }

        // =======================
        // =======================
        // INVESTOR MAPPING TABLES
        // =======================
        // =======================
        foreach($InvestorName as $InvestmentManager){ 
            $sqlC2 = "  INSERT INTO InvestorUserDetail(InvestorUserDetailID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestorID, UserDetailID)
                        VALUES (uuid(), now(), now(),0,NULL,(select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestmentManager'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$InvestorContact'))";
            $queryC2 = mysqli_query($conn, $sqlC2);
    
            if ( $queryC2){
                //  Success - do nothing
            } else{
                echo 'Oops! There was an error on linking Investor and contact. Please report bug to support.'.'<br/>'.mysqli_error($conn);
            }
        }

        // ===================
        // ===================
        // FUND MAPPING TABLES
        // ===================
        // ===================
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
        // =====================================================================
        // **** INSERT STATEMENTS FOR THE DEALS CENTRAL CAPTURING TABLES **** //
        // =====================================================================
        // =====================================================================

        $sqlDLS = "  INSERT INTO Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestmentValue, stake, IndustryID, UserDetailID, UserDetailID2)
                    VALUES (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), '$InvestmentValue', '$Stake', (select distinct Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$InvestorContact'))";
        $queryDLS = mysqli_query($conn, $sqlDLS);
        // DLS
        if ($queryDLS){
            // IF THE QUERY ABOVE IS A SUCCESS THEN EXECUTE BELOW CODE
            // =================================================================
            // LOOP TO INSERT INVESTMENT MANAGERS TO THE LINKING TABLE ON DEALS
            // =================================================================
            foreach($Sector as $sects){  
                $sqlDealSector = "  INSERT INTO 
                                        DealsSector(DealsSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, SectorID)
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT news.NewsID FROM news WHERE news.NewsURL = '$NewsURL')), (SELECT S.SectorID FROM sector S WHERE S.Sector = '$sects'))
                ";
                $query99 = mysqli_query($conn, $sqlDealSector);
                if($query99){
                    // Do nothing
                } else {
                    echo 'Oops! There was an error inserting the sector IDs from the array'.mysqli_error($conn).'<br/>';
                }
            }
            // =================================================================
            // LOOP TO INSERT INVESTMENT MANAGERS TO THE LINKING TABLE ON DEALS
            // =================================================================
            foreach($InvestorName as $InvestmentManager){  
                $sqlDealInvestor = "  INSERT INTO 
                                        DealsInvestor(DealsInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, InvestorID)
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT news.NewsID FROM news WHERE news.NewsURL = '$NewsURL')), (SELECT Investor.InvestorID FROM Investor WHERE Investor.InvestorName = '$InvestmentManager'))
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
                                        (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT news.NewsID FROM news WHERE news.NewsURL = '$NewsURL')), (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$Fund'))
                ";

                $query97 = mysqli_query($conn, $sqlDealFund);
                
                if($query97){
                    // Do nothing
                } else {
                    echo 'Oops! There was an error saving links between Funds and Deals from the array'.mysqli_error($conn).'<br/>';
                }
            }

        } else {
            echo 'Oops! There was an error on Deals Capture Table. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }

        $conn->close();
        echo '<H3>Thanks for contibuting your data to the Deal Database!</H3> '
        .'<br/>'
        .'<small>You will be redirected shortly...</small>';
        header("Refresh:5; url=../tabs/NewDeals.php");
    };
?>
