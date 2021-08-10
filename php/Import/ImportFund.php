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
                    $FundName               = mysqli_real_escape_string($conn,$line[0]);
                    $InvestorName           = mysqli_real_escape_string($conn,$line[1]);
                    $PortfolioCompanyName   = mysqli_real_escape_string($conn,$line[2]);
                    $Currency               = $line[3];//we will need to add this column to the csvb file to be imported or should I delete this for now and ot have the column there?
                    $CommittedCapital       = $line[4];
                    $MinimumInvestment      = $line[5];
                    $MaximumInvestment      = $line[6];  
                    $InvestmentStage        = mysqli_real_escape_string($conn,$line[7]); 
                    $FundNote               = mysqli_real_escape_string($conn,$line[8]);    

                    // =============================================
                    // CHECK FOR DUPLICATES RECORDS BEFORE INSERTING
                    // =============================================
                    $prevQuery = "  SELECT 
                                        FundName 
                                    FROM 
                                        Fund 
                                    WHERE 
                                        FundName = '".$line[0]."'
                    ";
                    $prevResult = mysqli_query($conn,$prevQuery);
                    if(!empty($prevResult) && $prevResult->num_rows>0){
                        // means company is already in the database so simply ignore
                        // echo
                        // ''
                        // .'<p style="margin: 3px; color:red; font-size:16px;"> Company: '.$FundName.'<p/>'
                        // .'<a href="../tabs/fund.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                        $msg[] =$FundName;
                    }else{
                        header( "refresh: 5; url= ../tabs/fund.php" );
                        // insert and create a new company then redirect back to the portfolio company page(added header function first because if set below or after echos then it will not work.)
                        $sql = "INSERT INTO 
                                    Fund(FundID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundName, CurrencyID, CommittedCapital, MinimumInvestment, MaximumInvestment) 
                                VALUES 
                                    (uuid(), now(), now(),0,NULL, '$FundName',(select C.CurrencyID FROM Currency C where C.Currency = '$Currency' ), '$CommittedCapital', '$MinimumInvestment', '$MaximumInvestment')
                        ";
                        $query = mysqli_query($conn, $sql);

                        if($query){
                            // =============================================
                            // Do nothing
                            // =============================================
                        }else{
                            echo
                            '<div style="color:red; font-size:20px;">
                                <p>There was an error, please make sure your file does not have duplicate data or errors and try again.<p/>
                                <a href="../tabs/fund.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                            </div>';
                            exit;
                        }
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
                            The following Fund(s) already exists in the database:
                        </small>
                        ';
                    for($i=0; $i<$arrLength; $i++){
                        // Used the HTML elements and inlise CSS to format the output in a desirable way by making the font red. concatenated the varible $i which we initialzed if the for loop, added one to it and then appended that next to the value of the array to create an ordered-numbered list. Added an empty string to add space between.
                        echo '<p style="color:red;">'.$i+'+1'.'. '.$msg[$i].'<p/>';
                    }
                    echo '<br>'.'<a href="../tabs/fund.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>';
                }else{
                    echo
                    '<div style="color:green; font-size:20px;">
                        <p>All records imported successfully! You will be redirected back in 5 sec... <p/>
                        <a href="../tabs/fund.php" style="padding:3px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
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
                    <a href="../tabs/fund.php" style="margin-top:5px; padding:5px; border:1px solid red;font-size:18px; text-decoration:none;"> Go Back </a>
                </div>'
            );
        }
    }
?>