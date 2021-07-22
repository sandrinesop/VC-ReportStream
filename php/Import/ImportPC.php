<?php
    include_once('../App/connect.php');
    if(isset($_POST['ImportSubmit'])){
        // ONLY ALLOWED MIME TYPES
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel');
        // VALIDATE WHETHER OR NOT A FILE UPLOADED IS A CSV FILE TYPE 
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
            // CHECK IF FILE UPLOADED SUCCESSFULLY
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                // OPEN UPLOADED FILE IN READ ONLY MODE
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                // SKIP THE FIRST LINE
                fgetcsv($csvFile);
                // PARSE DATA FROM CSV FILE, LINE BY LINE USING A WHILE LOOP
                // DEFINE AN ARRAY OUTSIDE TEH LOOP AND THEN POPULATE IT WITH VALUES FROM THE WHILE LOOP WITH EVERY ITERATION AND THEN USE IT TO OUTOUT A LIST WITH NAMES OF TEH COMPANIES WWHICH ALREADY EXIST IN THE DB.
                $msg = array();
                while(($line = fgetcsv($csvFile))!== FALSE){
                    $PortfolioCompanyName              = $line[0];
                    $Currency                          = $line[1];
                    $PortfolioCompanyWebsite           = $line[2];
                    $Industries                        = $line[3];
                    $Sectors                            = $line[4];
                    $Details                           = $line[5];
                    $YearFounded                       = $line[6];  
                    $Headquarters                      = $line[7]; 
                    $UserFullName                      = $line[8];    
                    // $Logo                              = $line[9];
                    $InvestorName                      = $line[9];      
                    $FundName                          = $line[10];

                    // =============================================
                    // CHECK FOR DUPLICATES RECORDS BEFORE INSERTING
                    // =============================================
                    $prevQuery = "  SELECT 
                                        PortfolioCompanyName 
                                    FROM 
                                        PortfolioCompany 
                                    WHERE 
                                        PortfolioCompanyName = '".$line[0]."'
                    ";
                    $prevResult = mysqli_query($conn,$prevQuery);
                    if($prevResult->num_rows>0){
                        // means company is already in the database so simply ignore
                        // echo
                        // ''
                        // .'<p style="margin: 3px; color:red; font-size:16px;"> Company: '.$PortfolioCompanyName.'<p/>'
                        // .'<a href="../tabs/portfolio-company.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                        $msg[] =$PortfolioCompanyName;
                    }else{
                        header( "refresh: 5; url= ../tabs/portfolio-company.php" );
                        // insert and create a new company then redirect back to the portfolio company page(added header function first because if set below or after echos then it will not work.)
                        $sql = "INSERT INTO 
                                    PortfolioCompany( PortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyName, CurrencyID, Website, Details, YearFounded, Headquarters)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL,'$PortfolioCompanyName', (select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite', '$Details', '$YearFounded', (select country.CountryID FROM country where country.Country = '$Headquarters'))
                        ";
                        $query = mysqli_query($conn, $sql);

                        if($query){
                            // =============================================
                            // LOOP TO INSERT SECTORS ON P.COMPANY
                            // =============================================
                            $SectorList = explode(",", $Sectors);
                            foreach($SectorList as $sector){  
                                $sql99 = "  INSERT INTO PortfolioCompanySector(PortfolioCompanySectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, SectorID)
                                            VALUES (uuid(), now(), now(), 0, NULL,(select P.PortfolioCompanyID FROM PortfolioCompany P where P.PortfolioCompanyName = '$PortfolioCompanyName'), (select S.SectorID FROM sector S where S.Sector = '$sector'))";
                                $query99 = mysqli_query($conn, $sql99);

                                if($query99){
                                    // echo 'For each iteration the Sector ID for '.$sector. 'was inserted'.'<br/>';
                                } else {
                                    echo 'Oops! There was an error inserting the sector ID from the array'.mysqli_error($conn).'<br/>';
                                }
                            }
                            // =============================================
                            // LOOP TO INSERT INDUSTRIES ON P.COMPANY
                            // =============================================
                            $IndustryList = explode(",", $Industries);
                            foreach($IndustryList AS $Industry){ 
                                $sql98 = "   INSERT INTO 
                                                PortfolioCompanyIndustry(PortfolioCompanyIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, IndustryID)
                                            VALUES 
                                                (uuid(), now(), now(), 0, NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'))";
                                $query98 = mysqli_query($conn, $sql98);
                                if($query98){
                                    // echo 'For each iteration the Sector ID for '.$sector. 'was inserted'.'<br/>';
                                } else {
                                    echo 'Oops! There was an error inserting the Industry IDs from the array'.mysqli_error($conn).'<br/>';
                                }
                            }
                        }else{
                            echo
                            '<div style="color:red; font-size:20px;">
                                <p>There was an error, please make sure your file does not have duplicate data or errors and try again.<p/>
                                <a href="../tabs/portfolio-company.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                            </div>';
                            exit;
                        };
                        // echo
                        // '<div style="color:green; font-size:20px;">
                        //     <p>All records imported successfully! You will be redirected back in 5 sec... <p/>
                        //     <a href="../tabs/portfolio-company.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                        // </div>';
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
                            The following company/companies already exists in the database:
                        </small>
                        ';
                    for($i=0; $i<$arrLength; $i++){
                        // Used the HTML elements and inlise CSS to format the output in a desirable way by making the font red. concatenated the varible $i which we initialzed if the for loop, added one to it and then appended that next to the value of the array to create an ordered-numbered list. Added an empty string to add space between.
                        echo '<p style="color:red;">'.$i+'+1'.'. '.$msg[$i].'<p/>';
                    }
                    echo '<br>'.'<a href="../tabs/portfolio-company.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                }else{
                    echo
                    '<div style="color:green; font-size:20px;">
                        <p>All records imported successfully! You will be redirected back in 5 sec... <p/>
                        <a href="../tabs/portfolio-company.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                    </div>';
                }
                // CLOSE CSV FILE
                fclose($csvFile);
            }else{
                echo 'file not a uploaded';
            }
        }else{
            // die("Error: ");
            die(
                '<div style="color:red; font-size:20px;">
                    <p>Error: File type is not a csv or file not uploaded <p/>
                    <a href="../tabs/portfolio-company.php" style="margin-top:5px; padding:5px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                </div>'
            );
        }
    }
?>