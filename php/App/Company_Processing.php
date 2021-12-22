<?php
    // QUERY DATABASE FROM DATA
    $sql=" SELECT DISTINCT
                PortfolioCompany.PortfolioCompanyID,PortfolioCompany.Deleted, PortfolioCompany.DeletedDate, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, Currency.Currency, PortfolioCompany.Website, GROUP_CONCAT(DISTINCT Industry) AS Industry, GROUP_CONCAT(DISTINCT Sector) AS Sector,  PortfolioCompany.Details, PortfolioCompany.YearFounded, Country.Country, PortfolioCompany.Logo, UserDetail.UserFullName, Gender.Gender, Race.Race
            FROM 
                PortfolioCompany 
            LEFT JOIN 
                InvestorPortfolioCompany 
            ON 
                InvestorPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Investor 
            ON 
                Investor.InvestorID = InvestorPortfolioCompany.InvestorID 
            LEFT JOIN 
                FundPortfolioCompany 
            ON 
                FundPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Fund 
            ON 
                Fund.FundID = FundPortfolioCompany.FundID 
            LEFT JOIN 
                Currency 
            ON 
                Currency.CurrencyID = PortfolioCompany.CurrencyID 
            LEFT JOIN 
                Country 
            ON 
                Country.CountryID = PortfolioCompany.Headquarters 
            LEFT JOIN 
                PortfolioCompanyIndustry 
            ON 
                PortfolioCompanyIndustry.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Industry 
            ON 
                Industry.IndustryID = PortfolioCompanyIndustry.IndustryID
            LEFT JOIN 
                PortfolioCompanySector
            ON 
                PortfolioCompanySector.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                Sector 
            ON 
                Sector.SectorID = PortfolioCompanySector.SectorID
            LEFT JOIN 
                PortfolioCompanyUserDetail
            ON 
                PortfolioCompanyUserDetail.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
            LEFT JOIN 
                UserDetail
            ON 
                UserDetail.UserDetailID = PortfolioCompanyUserDetail.UserDetailID
            LEFT JOIN 
                RoleType
            ON 
                RoleType.RoleTypeID = UserDetail.RoleTypeID
            LEFT JOIN 
                Gender
            ON
                Gender.GenderID = UserDetail.GenderID
            LEFT JOIN 
                Race 
            ON 
                Race.RaceID =UserDetail.RaceID
            WHERE 
                PortfolioCompany.Deleted = 0
                
            GROUP BY PortfolioCompany.PortfolioCompanyID,PortfolioCompany.Deleted, PortfolioCompany.DeletedDate, PortfolioCompany.PortfolioCompanyName, Currency.Currency, PortfolioCompany.Website, PortfolioCompany.Details, PortfolioCompany.YearFounded, Country.Country, PortfolioCompany.Logo
    "; 
    $result = $conn->query($sql) or die($conn->error);
    
    //===================================================
    //===================================================
    //Pulling data from the database and into dropdowns to create a new company with standardized data 
    //===================================================
    //===================================================
    // USER FULLNAME TO SET CONTACT
    $sql5 = "   SELECT DISTINCT 
                    UserFullName
                FROM 
                    UserDetail 
                WHERE 
                    UserFullName IS NOT NULL ORDER BY UserFullName ASC";
    $result5 = mysqli_query($conn, $sql5);
    // ACCESSING CURRENCIES TO POPULATE DROPDOWN FROM DATABASE
    $sql100 = "   SELECT DISTINCT 
                    Currency
                FROM 
                    Currency 
                WHERE 
                    Currency IS NOT NULL ORDER BY Currency ASC";
    $result100 = mysqli_query($conn, $sql100);
    // ACCESSING COUNTRIES TO POPULATE DROPDOWN FROM DATABASE
    $sql101 = "   SELECT DISTINCT 
                    Country
                FROM 
                    Country 
                WHERE 
                    Country IS NOT NULL ORDER BY Country ASC";
    $result101 = mysqli_query($conn, $sql101);
    
    // ACCESSING INVESTORS TO POPULATE DROPDOWN FROM DATABASE
    $sql102 = "   SELECT DISTINCT 
                    InvestorName
                FROM 
                    Investor 
                WHERE 
                    InvestorName IS NOT NULL ORDER BY InvestorName ASC";
    $result102 = mysqli_query($conn, $sql102);
    
    // ACCESSING FUNDS TO POPULATE DROPDOWN FROM DATABASE
    $sql103 = "   SELECT DISTINCT 
                    FundName
                FROM 
                    Fund 
                WHERE 
                    FundName IS NOT NULL ORDER BY FundName ASC";
    $result103 = mysqli_query($conn, $sql103);

    // POPULATING PORTFOLIO COMPANIES DROPDOWN
    $sql104 = " SELECT DISTINCT 
                    PortfolioCompanyName
                FROM 
                    PortfolioCompany 
                WHERE 
                    PortfolioCompanyName IS NOT NULL ORDER BY PortfolioCompanyName ASC
    ";
    $result104 = mysqli_query($conn, $sql104);
    // POPULATING ROLETYPE DROPDOWN
    $sqlRoleType = " SELECT DISTINCT 
                    RoleType
                FROM 
                    RoleType 
                WHERE 
                    RoleType IS NOT NULL ORDER BY RoleType ASC
    ";
    $resultRoleType = mysqli_query($conn, $sqlRoleType);
    // POPULATING GENDER DROPDOWN
    $sqlGender = " SELECT DISTINCT 
                    Gender
                FROM 
                    Gender 
                WHERE 
                    Gender IS NOT NULL ORDER BY Gender ASC
    ";
    $resultGender = mysqli_query($conn, $sqlGender);
    // POPULATING RACE DROPDOWN
    $sqlRace = " SELECT DISTINCT 
                    Race
                FROM 
                    Race 
                WHERE 
                    Race IS NOT NULL ORDER BY Race ASC
    ";
    $resultRace = mysqli_query($conn, $sqlRace);

    if ( isset($_POST['submit']))
    {
        // DEFINED VAR FOR THE SECOND TABLE
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = mysqli_real_escape_string($conn, $_POST['PortfolioCompanyName']);
        $Currency                = $_POST['Currency'];
        $PortfolioCompanyWebsite = mysqli_real_escape_string($conn, $_POST['Website']);
        $Industries              = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        $Details                 = mysqli_real_escape_string($conn, $_POST['Details']);
        $YearFounded             = $_POST['YearFounded'];
        $Headquarters            = $_POST['Headquarters'];
        $UserFullName            = $_POST['UserFullName'];
        $Logo                    = $_FILES['img']['name'];
        $InvestorName           = $_POST['InvestorName'];
        $FundName               = $_POST['FundName'];

        echo 'The country list is '.$Headquarters;
        // Company Logo Insert code
        if(isset($_FILES['img']['name'])){ 
            $logoName = $_FILES['img']['name'];
            $logoSize = $_FILES['img']['size'];

            if($logoSize>0):
                // echo'file uploaded' .$logo;
                $logo =mysqli_real_escape_string($conn, (file_get_contents($_FILES['img']['tmp_name'])));
            else:
                // echo 'Image not set';
            endif;
        }else {
            error_reporting(0);
        }
        // $Logo = addslashes(file_get_contents($_FILES['img']['tmp_name']));

        // ===========================================================================================================
        // THE CODE BLOCK BELOW CHECKS IF RECORD ALREADY EXISTS IN THE DB OR NOT. WE'LL USE THIS TO PREVENT DUPLICATES
        // ===========================================================================================================
        $DuplicateCheck = " SELECT PortfolioCompanyName FROM PortfolioCompany WHERE PortfolioCompany.PortfolioCompanyName ='$PortfolioCompanyName'";
        $checkResult = mysqli_query($conn, $DuplicateCheck);

        if($checkResult -> num_rows >0){
            $conn->close();
            header( "refresh: 3; url = portfolio-company.php" );
            echo 
                '<div style="background-color:#f8d7da; color: #842029; margin:0;">
                    <H4>Heads Up!</H4>
                    <p style="margin:0;"> <small>New record not created, Portfolio Company already exists.</small> </p>
                </div>'
            ;
        }else{
            // PORTFOLIO COMPANY INSERT
            $sql = "INSERT INTO 
                    PortfolioCompany( PortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyName, CurrencyID, Website, Details, YearFounded, Logo)
                VALUES 
                    (uuid(), now(), now(), 0, NULL,'$PortfolioCompanyName', (select C.CurrencyID FROM Currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite', '$Details', '$YearFounded', '$Logo')
            ";
            $query = mysqli_query($conn, $sql);
            // =========================================================================================
            // CODE BELOW: IF COMPANY IS CREATED SUCCESSFULLY THEN LINK THE COMPANY WITH OTHER ENTITIES.
            // =========================================================================================
            if($query){
                // =============================================
                // LOOP TO INSERT COUNTRIES ON P.COMPANY
                // =============================================
                foreach($Headquarters as $Country){  
                    $sql = " INSERT INTO 
                                    PortfolioCompanyLocation(PortfolioCompanyLocationID, CreatedDate, ModifiedDate, PortfolioCompanyID, CountryID)
                                VALUES 
                                    (uuid(), now(), now(), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select Country.CountryID FROM Country where Country.Country = '$Country'))
                             ";
                    $query = mysqli_query($conn, $sql);

                    if($query){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Country(ies) from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // ====================================
                // LOOP TO INSERT SECTORS ON P.COMPANY
                // ====================================
                foreach($Sector as $sects){  
                    $sql99 = "  INSERT INTO 
                                    PortfolioCompanySector(PortfolioCompanySectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, SectorID)
                                VALUES 
                                    (uuid(), now(), now(), 0, NULL,(select P.PortfolioCompanyID FROM PortfolioCompany P where P.PortfolioCompanyName = '$PortfolioCompanyName'), (select S.SectorID FROM Sector S where S.Sector = '$sects'))
                    ";
                    $query99 = mysqli_query($conn, $sql99);

                    if($query99){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error inserting the sector ID from the array'.mysqli_error($conn).'<br/>';
                    }
                }

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
                        print_r($IndustryList);
                    }
                }

                // =============================================
                // LOOP TO INSERT CONTACT PERSON ON P.COMPANY
                // =============================================
                foreach($UserFullName as $Contact){  
                    $sql4 = "   INSERT INTO PortfolioCompanyUserDetail(PortfolioCompanyUserDetailID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, UserDetailID)
                                VALUES (uuid(), now(), now(), 0, NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.UserFullName = '$Contact'))";
                    $query4 = mysqli_query($conn, $sql4);

                    if($query4){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Contacts from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                // =============================================
                // LOOP TO INSERT INVESTORS ON P.COMPANY
                // =============================================
                foreach($InvestorName as $InvestmentManager){  
                    $sql104 = " INSERT INTO InvestorPortfolioCompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, InvestorID, PortfolioCompanyID)
                            VALUES (uuid(), now(), now(), 0, NULL, (select Investor.InvestorID FROM  Investor where Investor.InvestorName = '$InvestmentManager'), (select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
                    $query104 = mysqli_query($conn, $sql104);

                    if($query104){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Investment Manager(s) from the array'.mysqli_error($conn).'<br/>';
                    }
                }

                // =============================================
                // LOOP TO FUNDS ON P.COMPANY
                // =============================================
                foreach($FundName as $Fund){  
                    $sql105 = "     INSERT INTO 
                                        FundPortfolioCompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, FundID, PortfolioCompanyID)
                                    VALUES 
                                        (uuid(), now(), now(), 0, NULL, (select Fund.FundID FROM Fund where Fund.FundName = '$Fund'),(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'))";
                    $query105 = mysqli_query($conn, $sql105);
                    if($query105){
                        // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                    } else {
                        echo 'Oops! There was an error saving the Contacts from the array'.mysqli_error($conn).'<br/>';
                    }
                }
                // ===========================================================
                // REFRESH PAGE TO SHOW NEW ENTRIES IF INSERTION WAS A SUCCESS
                // ===========================================================
                header( "refresh: 3; url= portfolio-company.php" );
                echo 
                    '<div style="background-color:#d1e7dd; color: #0f5132; margin:0;">
                        <H4>Thank you for your contribution</H4>
                        <p style="margin:0;"> <small> New Portfolio Company created successfully! </small> </p>
                    </div>'
                ;
            } else {
                echo'There was an error creating Portfolio Company: '.mysqli_error($conn).'<br/>';
            }
        }
    }
?>