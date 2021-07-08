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
        $NewsURL       = $_POST['NewsURL'];
        $NewsNote       = $_POST['NewsNote'];
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = $_POST['PortfolioCompanyName'];
        $Stake                   = $_POST['Stake'];
        $InvestmentValue         = $_POST['InvestmentValue'];
        $Industry                = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];

        // USER DETAIL TABLE
        $StartUpContact       = $_POST['UserFullName'];
        $InvestorContact       = $_POST['UserFullName1'];
        // INVESTOR TABLE
        $InvestorName           = $_POST['InvestorName'];
        // FUND TABLE
        $FundName               = $_POST['FundName'];
        
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
        // Query End

        $sql2 = "   INSERT INTO 
                        Note(NoteID, CreatedDate, ModifiedDate, Note, NoteTypeID )
                    VALUES 
                        (uuid(), now(), now(), '$NewsNote','fb44ee75-7056-11eb-a66b-96000010b114')";
        $query2 = mysqli_query($conn, $sql2);

        if ($query && $query2 ){
                // Success
        } else {
            echo 'Oops! There was an error saving news item. Please report bug to support.'.'<br/>'.mysqli_error($conn);
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
        // =======================
        // INVESTOR MAPPING TABLES
        // =======================
        // =======================
        // =======================

        $sqlC2 = "  INSERT INTO InvestorUserDetail(InvestorUserDetailID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestorID, UserDetailID)
                    VALUES (uuid(), now(), now(),0,NULL,(select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$InvestorContact'))";
        $queryC2 = mysqli_query($conn, $sqlC2);

        if ( $queryC2){
            //  Success
        } else{
            echo 'Oops! There was an error on linking Investor Section. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        }

        // ===================
        // ===================
        // ===================
        // FUND MAPPING TABLES
        // ===================
        // ===================
        // ===================
        $sqlA3 = "  INSERT INTO FundNews(FundNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, FundID, NewsID)
                    VALUES (uuid(), now(), now(),0,NULL, (select distinct Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'))";
        $queryA3 = mysqli_query($conn, $sqlA3);
        
        // =====================================================================
        // =====================================================================
        // ===================
        // ===================
        // **** INSERT STATEMENTS FOR THE DEALS CENTRAL CAPTURING TABLES **** //
        // ===================
        // ===================
        // =====================================================================
        // =====================================================================

        $sqlDLS = "  INSERT INTO Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestorID, FundID, InvestmentValue, stake, IndustryID, UserDetailID1, UserDetailID2)
                    VALUES (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'),(select distinct Fund.FundID FROM Fund where Fund.FundName = '$FundName'), '$InvestmentValue', '$Stake', (select distinct Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$InvestorContact'))";
        $queryDLS = mysqli_query($conn, $sqlDLS);
        // DLS
        if ($queryDLS){
        // Success
            foreach($Sector as $sects){  
                // $sectors.= $sects.",";
                // $testQuery = " SELECT sector.SectorID FROM sector WHERE sector.Sector = '$sects'";
                // $queryResult = mysqli_query($conn, $testQuery);

                // while($queryRows = mysqli_fetch_assoc($queryResult) ){
                // // echo 'For each iteration this is the Sector ID'.$queryRows['SectorID'].'<br/>';
                // }

                $sqlDealSector = "  INSERT INTO DealSector(DealSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, SectorID)
                            VALUES (uuid(), now(), now(), 0, NULL,(select Deals.DealsID FROM Deals where Deals.NewsID = (select news.NewsID FROM news where news.NewsURL = '$NewsURL')), (select S.SectorID FROM sector S where S.Sector = '$sects'))";

                $query99 = mysqli_query($conn, $sqlDealSector);
                

                // Echo 'DealID is:'.;

                if($query99){
                    // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                } else {
                    echo 'Oops! There was an error inserting the sector ID from the array'.mysqli_error($conn).'<br/>';
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
