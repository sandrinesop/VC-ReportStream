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
                    $PortfolioCompanyName              = $line[0];
                    $Currency                          = $line[1];
                    $PortfolioCompanyWebsite           = $line[2];
                    $Industry                          = $line[3];
                    $Sector                            = $line[4];
                    $Details                           = $line[5];
                    $YearFounded                       = $line[6];  
                    $Headquarters                      = $line[7]; 
                    $UserFullName                      = $line[8];    
                    // $Logo                              = $line[9];
                    $InvestorName                      = $line[9];      
                    $FundName                          = $line[10];
                    
                    echo 'value for variable:$PortfolioCompanyName is: '.$PortfolioCompanyName.'<br/>';
                    echo 'value for variable:$Currency is: '.$Currency.'<br/>';
                    echo 'value for variable:$PortfolioCompanyWebsite is: '.$PortfolioCompanyWebsite.'<br/>';
                    echo 'value for variable:$Industry  is: '.$Industry .'<br/>';
                    echo 'value for variable:$Sector  is: '.$Sector .'<br/>';
                    echo 'value for variable:$Details is: '.$Details.'<br/>';
                    echo 'value for variable:$YearFounded  is: '.$YearFounded .'<br/>';
                    echo 'value for variable:$Headquarters  is: '.$Headquarters .'<br/>';
                    echo 'value for variable:$UserFullName is: '.$UserFullName.'<br/>';
                    echo 'value for variable:$InvestorName is: '.$InvestorName.'<br/>';
                    echo 'value for variable:$FundName is: '.$FundName.'<br/>'.'<br/>'.'<br/>'.'<br/>'.'<br/>';


                    $sql = "INSERT INTO PortfolioCompany( PortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyName, CurrencyID, Website, Details, YearFounded, Headquarters)
                    VALUES (uuid(), now(), now(), 0, NULL,'$PortfolioCompanyName', (select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite', '$Details', '$YearFounded', (select country.CountryID FROM country where country.Country = '$Headquarters'))";
                    $query = mysqli_query($conn, $sql);
                }
                fclose($csvFile);
            }else{
                echo 'file not a uploaded';
            }
        }else{
            echo 'file not a csv';
        }
    }

?>