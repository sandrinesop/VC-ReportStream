<?php 
    include_once('../App/connect.php');
    if(isset($_POST['ImportSubmit'])){
        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        // Validate whether selected file is a csv file or not
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
            // Checkif file is uploaded
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                // open uploaded file is read only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                // skip the first line
                fgetcsv($csvFile);
                // Parse data from CSV file, line by line
                while(($line = fgetcsv($csvFile))!== FALSE){
                    $NewsDate              = $line[0];
                    $NewsURL               = $line[1];
                    $PortfolioCompanyName  = $line[3];
                    $InvestorName          = $line[4];
                    $FundName              = $line[5];
                    $InvestmentValue       = $line[6];
                    $Stake                 = $line[7];
                    $Industry              = $line[8];
                    $Sector                = $line[9];
                    $Contact               = $line[10];
                    $NewsNote              = $line[11]; 

                    echo 'NewsDate is:'.$NewsDate.'<br/>'
                    .'NewsURL is:'.$NewsURL.'<br/>'
                    .'PortfolioCompanyName is:'.$PortfolioCompanyName.'<br/>'
                    .'InvestorName is:'.$InvestorName.'<br/>'
                    .'FundName is:'.$FundName.'<br/>'
                    .'InvestmentValue is:'.$InvestmentValue.'<br/>'
                    .'Stake is:'.$Stake.'<br/>'
                    .'Industry is:'.$Industry.'<br/>'
                    .'Sector is:'.$Sector.'<br/>'
                    .'Contact is:'.$Contact.'<br/>'
                    .'NewsNote is:'.$NewsNote.'<br/>'.'<br/>';
                    // check whether company already exists in the database with the same website
                    // $prevQuery = "SELECT NewsID FROM News WHERE NewsURL = '".$NewsURL."'";
                    // $prevResult = mysqli_query($conn, $prevQuery);

                    // if($prevResult -> num_rows > 0){
                        // update portfolio company details
                    //     $update = "UPDATE Deals SET ModifiedDate=NOW(), NewsID=(SELECT News.NewsID FROM News WHERE News.NewsURL = $NewsURL),PortfolioCompanyID=(SELECT PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany WHERE PortfolioCompany.PortfolioCompanyName = $PortfolioCompanyName), InvestmentValue='".$InvestmentValue."', stake ='".$Stake."', IndustryID=(SELECT Industry.IndustryID FROM Industry WHERE Industry.Industry = $Industry), UserDetailID=(SELECT UserDetail.UserDetailID FROM UserDetail WHERE UserDetail.UserFullName = $Contact) WHERE NewsID =(SELECT News.NewsID FROM News WHERE News.NewsURL = $NewsURL)";

                    //     $UpdateResult = mysqli_query($conn, $update);
                    //         if($UpdateResult){
                    //             // Do nothing
                    //         }else{
                    //             echo 'Error updating company while importing csv '.mysqli_error($conn).'<br/>';
                    //         }
                    // }else{
                        // Insert data in the database
                        // $sqlDLS = "  INSERT INTO 
                        //                 Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestmentValue, stake, IndustryID, UserDetailID)
                        //             VALUES 
                        //                 (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), '$InvestmentValue', '$Stake', (select distinct Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'), (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$Contact'))
                        // ";
                        // $queryDLS = mysqli_query($conn, $sqlDLS);

                        // if ($queryDLS){
                        //     // IF THE QUERY ABOVE IS A SUCCESS THEN EXECUTE BELOW CODE
                        //     // =================================================================
                        //     // LOOP TO INSERT INVESTMENT MANAGERS TO THE LINKING TABLE ON DEALS
                        //     // =================================================================
                        //     $Sectors = explode(',',$Sector);
                        //     foreach($Sectors as $sects){  
                        //         $sqlDealSector = "  INSERT INTO 
                        //                                 DealsSector(DealsSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, SectorID)
                        //                             VALUES 
                        //                                 (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT news.NewsID FROM news WHERE news.NewsURL = '$NewsURL')), (SELECT S.SectorID FROM sector S WHERE S.Sector = '$sects'))
                        //         ";
                        //         $query99 = mysqli_query($conn, $sqlDealSector);
                        //         if($query99){
                        //             // Do nothing
                        //         } else {
                        //             echo 'Oops! There was an error inserting the sector IDs from the array'.mysqli_error($conn).'<br/>';
                        //         }
                        //     }
                        //     // =================================================================
                        //     // LOOP TO INSERT INVESTMENT MANAGERS TO THE LINKING TABLE ON DEALS
                        //     // =================================================================
                        //     $InvestorNames = explode(',',$InvestorName);
                        //     foreach($InvestorNames as $InvestmentManager){  
                        //         $sqlDealInvestor = "  INSERT INTO 
                        //                                 DealsInvestor(DealsInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, InvestorID)
                        //                             VALUES 
                        //                                 (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT news.NewsID FROM news WHERE news.NewsURL = '$NewsURL')), (SELECT Investor.InvestorID FROM Investor WHERE Investor.InvestorName = '$InvestmentManager'))
                        //         ";
                        //         $query98 = mysqli_query($conn, $sqlDealInvestor);
                                
                        //         if($query98){
                        //             // Do nothing
                        //         } else {
                        //             echo 'Oops! There was an error saving links between Investment Manager and Deals from the array'.mysqli_error($conn).'<br/>';
                        //         }
                        //     }
                        //     // =================================================================
                        //     // LOOP TO INSERT FUNDS TO THE LINKING TABLE ON DEALS
                        //     // =================================================================
                        //     $FundNames = explode(',',$FundName);
                        //     foreach($FundNames as $Fund){  
                        //         $sqlDealFund = "  INSERT INTO 
                        //                                 DealsFund(DealsFundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, FundID)
                        //                             VALUES 
                        //                                 (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT news.NewsID FROM news WHERE news.NewsURL = '$NewsURL')), (SELECT Fund.FundID FROM Fund WHERE Fund.FundName = '$Fund'))
                        //         ";
                
                        //         $query97 = mysqli_query($conn, $sqlDealFund);
                                
                        //         if($query97){
                        //             // Do nothing
                        //         } else {
                        //             echo 'Oops! There was an error saving links between Funds and Deals from the array'.mysqli_error($conn).'<br/>';
                        //         }
                        //     }
                        // }
                    // }

                }
                fclose($csvFile);
            }
        }
        header( "refresh: 5; url= ../tabs/NewDeals.php" );
    }
?>