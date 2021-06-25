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
        // Loop through the array of sectors
        $sectors="";  
        foreach($Sector as $sects)  
        {  
            $sectors.= $sects.",";  
        }  

        // USER DETAIL TABLE
        $StartUpContact       = $_POST['UserFullName'];
        $InvestorContact       = $_POST['UserFullName1'];
        // INVESTOR TABLE
        $InvestorName           = $_POST['InvestorName'];
        // FUND TABLE
        $FundName               = $_POST['FundName'];


        // ====================================================================================================================================================
        // ====================================================================================================================================================
        // BELOW ARE THE INSERT STATEMENTS TO THE NEWS AND NOTE TABLE. THIS IS ONE OF THE ONLY  TWO TABLES THAT WILL COLLECT NEW DATA UPON ENTERING A NEW DEAL.
        // ====================================================================================================================================================
        // ====================================================================================================================================================
        // ===============================
        // ===============================
        // ===============================
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

        // ===============================
        // ===============================
        // ===============================
        // NO LONGER NEED BELOW TABLES
        // THE DATA IS IN DEALS TABLE
        // ===============================        
        // ===============================

        // $sql3 = "    INSERT INTO 
        //                 Stake(StakeID, CreatedDate, ModifiedDate,Deleted, DeletedDate, Stake) 
        //             VALUES 
        //                 (uuid(), now(), now(),0,NULL,'$Stake')";
        // $query3 = mysqli_query($conn, $sql3);
        // Query End

        // $sql4 = "    INSERT INTO 
        //                 InvestmentValue(InvestmentValueID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestmentValue) 
        //             VALUES 
        //                 (uuid(), now(), now(),0,NULL,'$InvestmentValue')";
        // $query4 = mysqli_query($conn, $sql4);




        // =====================================================
        // =====================================================
        // ***** INSERT STATEMENTS FOR THE MAPPING TABLES ******
        // =====================================================
        // =====================================================
        
        // ===============================
        // ===============================
        // ===============================
        // PortfolioCompany Mapping tables
        // ===============================
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
        // A1  END
        // $sqlB1 = "  INSERT INTO PortfolioCompanySector(PortfolioCompanySectorID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, SectorID)
        //             VALUES (uuid(), now(), now(),0,NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct Sector.SectorID FROM Sector where Sector.Sector = '$Sector'))";
        // $queryB1 = mysqli_query($conn, $sqlB1);
        // if ( $queryB1 ){
            // Success
        // } else {
        //     echo 'Oops! There was an error on linking Portfolio Company Sector. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
        // }
        // B1  END
        // $sqlC1 = "  INSERT INTO PortfolioCompanyIndustry(PortfolioCompanyIndustryID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, IndustryID)
        //             VALUES (uuid(), now(), now(),0,NULL, (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct Industry.IndustryID FROM Industry where Industry.Industry = '$Industry') )";
        //             // echo $Industry;
        // $queryC1 = mysqli_query($conn, $sqlC1);
        // if ( $queryC1 ){
        //     Success
        // } else {
        //     echo 'Oops! There was an error on linking Portfolio Company Industry. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
        // }
        // C1  END
        // $sqlD1 = "  INSERT INTO PortfolioCompanyUserDetail(PortfolioCompanyUserDetailID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, UserDetailID)
        //             VALUES (uuid(), now(), now(),0,NULL,(select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact'))";
        // $queryD1 = mysqli_query($conn, $sqlD1);
        // if ( $queryD1 ){
        //     // Success
        // } else {
        //     echo 'Oops! There was an error on linking Portfolio Company User. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
        // }
        // D1  END

        // $sqlF1 = "  INSERT INTO PortfolioCompanyStake(PortfolioCompanyStakeID, CreatedDate, ModifiedDate,Deleted, DeletedDate, PortfolioCompanyID, StakeID)
        //             VALUES (uuid(), now(), now(),0,NULL,(select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct stake.StakeID FROM stake where stake.Stake = '$Stake'))";
        // $queryF1 = mysqli_query($conn, $sqlF1);

        // if ( $queryF1 ){
        //     // Success
        // } else {
        //     echo 'Oops! There was an error on linking Portfolio Company Stake. Please report bug to support.'.'<br/>'.'<br/>'.mysqli_error($conn);
        // }


        // =======================
        // =======================
        // =======================
        // INVESTOR MAPPING TABLES
        // =======================
        // =======================
        // =======================
        // $sqlA2 = "  INSERT INTO InvestorNews(InvestorNewsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestorID, NewsID)
        //             VALUES (uuid(), now(), now(),0,NULL, (select distinct Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select news.NewsID FROM news where news.NewsURL = '$NewsURL'))";
        // $queryA2 = mysqli_query($conn, $sqlA2);
        // A2 END
        // $sqlB2 = "  INSERT INTO InvestorPortfolioCompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestorID, PortfolioCompanyID)
        //             VALUES (uuid(), now(), now(),0,NULL, (select distinct Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
        // $queryB2 = mysqli_query($conn, $sqlB2);
        // B2 END
        $sqlC2 = "  INSERT INTO InvestorUserDetail(InvestorUserDetailID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestorID, UserDetailID)
                    VALUES (uuid(), now(), now(),0,NULL,(select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$InvestorContact'))";
        $queryC2 = mysqli_query($conn, $sqlC2);
        // C2 END
        // $sqlD2 = "  INSERT INTO InvestorInvestmentValue(InvestorInvestmentValueID, CreatedDate, ModifiedDate,Deleted, DeletedDate, InvestorID, InvestmentValueID)
        //             VALUES (uuid(), now(), now(),0,NULL,(select distinct Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select InvestmentValue.InvestmentValueID FROM InvestmentValue where InvestmentValue.InvestmentValue = '$InvestmentValue'))";
        // $queryD2 = mysqli_query($conn, $sqlD2);
        // D2 END

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
        // A3
        // $sqlB3 = "  INSERT INTO FundPortfolioCompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate,Deleted, DeletedDate, FundID, PortfolioCompanyID)
        //             VALUES (uuid(), now(), now(),0,NULL, (select distinct Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
        // $queryB3 = mysqli_query($conn, $sqlB3);
        // B3
        // $sqlC3 = "  INSERT INTO FundInvestor(FundInvestorID, CreatedDate, ModifiedDate,Deleted, DeletedDate, FundID, InvestorID)
        //             VALUES (uuid(), now(), now(),0,NULL, (select distinct Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'))";
        // $queryC3 = mysqli_query($conn, $sqlC3);
        // C3
        // $sqlD3 = "   INSERT INTO FundIndustry(FundIndustryID, CreatedDate, ModifiedDate,Deleted, DeletedDate, FundID, IndustryID)
        //             VALUES (uuid(), now(), now(),0,NULL, (select distinct Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select distinct Industry.IndustryID FROM Industry where Industry.Industry = '$Industry') )";
        // $queryD3 = mysqli_query($conn, $sqlD3);
                    // echo $Industry;
                    // echo $Sector;
        // D3
        // if ($queryA3 && $queryB3 && $queryC3 && $queryD3){
        //     // Success
        // } else {
        //     echo 'Oops! There was an error on linking Fund Section. Please report bug to support.'.'<br/>'.mysqli_error($conn);
        // }

        
        // =====================================================================
        // =====================================================================
        // ===================
        // ===================
        // **** INSERT STATEMENTS FOR THE DEALS CENTRAL CAPTURING TABLES **** //
        // ===================
        // ===================
        // =====================================================================
        // =====================================================================

        $sqlDLS = "  INSERT INTO Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestorID, FundID, InvestmentValue, stake, IndustryID, Sector, UserDetailID1, UserDetailID2)
                    VALUES (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select distinct Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'),(select distinct Fund.FundID FROM Fund where Fund.FundName = '$FundName'), '$InvestmentValue', '$Stake', (select distinct Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'), '$sectors', (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$InvestorContact'))";
        $queryDLS = mysqli_query($conn, $sqlDLS);
        // DLS
        if ($queryDLS){
        // Success
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
