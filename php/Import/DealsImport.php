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
                // PARSE DATA FROM CSV FILE, LINE BY LINE USING A WHILE LOOP
                // DEFINE AN ARRAY OUTSIDE TEH LOOP AND THEN POPULATE IT WITH VALUES FROM THE WHILE LOOP WITH EVERY ITERATION AND THEN USE IT TO OUTOUT A LIST WITH NAMES OF TEH COMPANIES WWHICH ALREADY EXIST IN THE DB.
                $msg = array();
                while(($line = fgetcsv($csvFile))!== FALSE){
                    $NewsDate              = $line[0];
                    $NewsURL               = mysqli_real_escape_string($conn, $line[1]);
                    $PortfolioCompanyName  = mysqli_real_escape_string($conn, $line[2]);
                    $InvestorName          = mysqli_real_escape_string($conn, $line[3]);
                    $FundName              = mysqli_real_escape_string($conn, $line[4]);
                    $InvestmentValue       = $line[5];
                    $Stake                 = $line[6];
                    $Industries            = $line[7];
                    $Sectors               = $line[8];
                    $StartUpContact        = $line[9]; //The contact person of the startup related the deal.
                    $NewsNote              = mysqli_real_escape_string($conn, $line[10]); 

                    // echo 'NewsDate is:'.$NewsDate.'<br/>'
                    // .'NewsURL is:'.$NewsURL.'<br/>'
                    // .'PortfolioCompanyName is:'.$PortfolioCompanyName.'<br/>'
                    // .'InvestorName is:'.$InvestorName.'<br/>'
                    // .'FundName is:'.$FundName.'<br/>'
                    // .'InvestmentValue is:'.$InvestmentValue.'<br/>'
                    // .'Stake is:'.$Stake.'<br/>'
                    // .'Industry is:'.$Industry.'<br/>'
                    // .'Sector is:'.$Sector.'<br/>'
                    // .'Contact is:'.$Contact.'<br/>'
                    // .'NewsNote is:'.$NewsNote.'<br/>'.'<br/>';

                    // =============================================
                    // CHECK FOR DUPLICATES RECORDS BEFORE INSERTING
                    // =============================================
                    $prevQuery = "  SELECT 
                                        NewsDate, NewsURL, InvestmentValue 
                                    FROM 
                                        Deals
                                    -- Include News table data 
                                    LEFT JOIN 
                                        News 
                                    ON
                                        News.NewsID = Deals.NewsID  
                                    WHERE 
                                        News.NewsDate = '".$line[0]."'AND News.NewsURL = '".$line[1]."'
                    ";
                    $prevResult = mysqli_query($conn,$prevQuery);
                    if(!empty($prevResult) && $prevResult->num_rows>0){
                        // means deal is already in the database so simply ignore
                        $msg[] =$NewsURL;
                    }else{
                        header( "refresh: 8; url= ../tabs/NewDeals.php" );
                        // insert and create a new deal then redirect back to the deals page(added header function first because if set below or after echos then it will not work.)
                        // ==================================================================================================================================================================
                        // BEFORE IMPORTING THE DEAL, WE NEED TO MAK SURE THE News, COMPANIES, INVESTORS AND FUNDS ALREADY EXISTS IN THE LOOK UP TABLES SO WE'LL INSERT THOSE ENTITIES FIRST.
                        // ==================================================================================================================================================================
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
                        
                        // INSERT NOTE
                        $sqlNote = "   INSERT INTO 
                                        Note(NoteID, CreatedDate, ModifiedDate, Note, NoteTypeID )
                                    VALUES 
                                        (uuid(), now(), now(), '$NewsNote','fb44ee75-7056-11eb-a66b-96000010b114')
                        ";
                        $queryNote = mysqli_query($conn, $sqlNote);

                        if ($queryNote ){
                        // Success
                        } else {
                        echo 'Oops! There was an error saving Deal Note item. Please report bug to support.'.'<br/>'.mysqli_error($conn);
                        }

                        // INSERT INTO DEALS TABLE
                        $sqlDLS = "  INSERT INTO 
                                        Deals(DealsID, CreatedDate, ModifiedDate,Deleted, DeletedDate, NewsID, PortfolioCompanyID, InvestmentValue, stake, UserDetailID)
                                    VALUES 
                                        (uuid(), now(), now(),0,NULL, (select distinct News.NewsID FROM News where News.NewsURL = '$NewsURL'), (select distinct PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), '$InvestmentValue', '$Stake', (select distinct UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$StartUpContact'))
                        ";
                        $queryDLS = mysqli_query($conn, $sqlDLS);

                        if($queryDLS){
                            // ==========================================================
                            // LINKING THE NOTE TO THE DEAL IN THE LINKING/MAPPING TABLE
                            // ==========================================================
                            $sqlDealsNote = "INSERT INTO 
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
                            // =============================================
                            // LOOP TO INSERT SECTORS ON DEALS
                            // =============================================
                            $SectorList = explode(",", $Sectors);
                            foreach($SectorList as $Sector){  
                                $sql99 = "  INSERT INTO 
                                                DealsSector(DealsSectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate,DealsID, SectorID)
                                            VALUES 
                                                (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select S.SectorID FROM Sector S where S.Sector = '$Sector'))
                                ";
                                $query99 = mysqli_query($conn, $sql99);

                                if($query99){
                                    // echo 'For each iteration the Sector ID for '.$Sector. 'was inserted'.'<br/>';
                                } else {
                                    echo 'Oops! There was an error inserting the Sector ID from the array'.mysqli_error($conn).'<br/>';
                                }
                            }
                            
                            // =============================================
                            // LOOP TO INSERT INVESTORS ON DEALS
                            // =============================================
                            $InvestorList = explode(",", $InvestorName);
                            foreach($InvestorList as $Investor){  
                                $sql100 = "  INSERT INTO 
                                                DealsInvestor(DealsInvestorID, CreatedDate, ModifiedDate, Deleted, DeletedDate,DealsID, InvestorID)
                                            VALUES 
                                                (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select Investor.InvestorID FROM Investor  where Investor.InvestorName = '$Investor'))
                                ";
                                $query100 = mysqli_query($conn, $sql100);

                                if($query100){
                                    // echo 'For each iteration the Sector ID for '.$Sector. 'was inserted'.'<br/>';
                                } else {
                                    echo 'Oops! There was an error inserting the Investor ID from the array'.mysqli_error($conn).'<br/>';
                                }
                            }
                            // =============================================
                            // LOOP TO INSERT FUNDS ON DEALS
                            // =============================================
                            $FundsList = explode(",", $FundName);
                            foreach($FundsList as $Fund){  
                                $sql101 = "  INSERT INTO 
                                                DealsFund(DealsFundID, CreatedDate, ModifiedDate, Deleted, DeletedDate,DealsID, FundID)
                                            VALUES 
                                                (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select Fund.FundID FROM Fund  where Fund.FundName = '$Fund'))
                                ";
                                $query101 = mysqli_query($conn, $sql101);

                                if($query101){
                                    // echo 'For each iteration the Sector ID for '.$Sector. 'was inserted'.'<br/>';
                                } else {
                                    echo 'Oops! There was an error inserting the Fund ID from the array'.mysqli_error($conn).'<br/>';
                                }
                            }
                            // =============================================
                            // LOOP TO INSERT INDUSTRIES ON DEALS
                            // =============================================
                            $IndustryList = explode(",", $Industries);
                            foreach($IndustryList AS $Industry){ 
                                $sql102 = "   INSERT INTO 
                                                DealsIndustry(DealsIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, DealsID, IndustryID)
                                            VALUES 
                                                (uuid(), now(), now(), 0, NULL,(SELECT Deals.DealsID FROM Deals WHERE Deals.NewsID = (SELECT News.NewsID FROM News WHERE News.NewsURL = '$NewsURL')), (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'))";
                                $query102 = mysqli_query($conn, $sql102);
                                if($query102){
                                    // echo 'For each iteration the Sector ID for '.$Sector. 'was inserted'.'<br/>';
                                } else {
                                    echo 'Oops! There was an error inserting the Industry IDs from the array'.mysqli_error($conn).'<br/>';
                                }
                            }
                        }else{
                            echo
                            '<div >
                                <p style="color:red; font-size:20px;">Oops! There was an error:: '.mysqli_error($conn).
                                '<br/>'
                                .'Please make sure your file does not have duplicates or missing required data and try again.<p/>
                                <a href="../tabs/NewDeals.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                            </div>';
                            exit;
                        };
                    }
                }
                // ==================================================================
                // FROM HERE THE ARRAY MSG HAS BEEN CREATED AND NOW READY TO BE USED.
                // NEXT UP, CREATE A VARIABLE AND THEN POPULATE IT WITH THE LENGHT OF THE ARRAY WHICH YOU WILL GET BY USING THE PHP COUNT() FUNCTION TO GET THE ARRAY LENGTH.
                // ==================================================================
                // print_r($msg);
                $arrLength = count($msg);
                if($arrLength>0){
                    echo'
                        <p style="color:green; font-size:20px;">
                            All unique records were imported successfully!
                        <p/>
                        <small>
                            The following deal(s) already exists in the database:
                        </small>
                        ';
                    for($i=0; $i<$arrLength; $i++){
                        // Used the HTML elements and inlise CSS to format the output in a desirable way by making the font red. concatenated the varible $i which we initialzed if the for loop, added one to it and then appended that next to the value of the array to create an ordered-numbered list. Added an empty string to add space between.
                        echo '<p style="color:red;">'.$i+'+1'.'. '.$msg[$i].'<p/>';
                    }
                    echo '<br>'.'<a href="../tabs/NewDeals.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                }else{
                    echo
                    '<div style="color:green; font-size:20px;">
                        <p>All records imported successfully! You will be redirected back in 5 sec... <p/>
                        <a href="../tabs/NewDeals.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                    </div>';
                };

                fclose($csvFile);
            }
        }
    }
?>