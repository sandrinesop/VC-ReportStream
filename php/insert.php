<?php
    if ( isset($_POST['submit']))
    {
        include_once('./connect.php');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // DEFINED VAR FOR THE FIRST TABLE
        // NEWS TABLE
        $NewsDate       = date('Y-m-d', strtotime($_POST['NewsDate']));
        $NewsURL       = $_POST['NewsURL'];
        $NewsNote       = $_POST['NewsNote'];

        // DEFINED VAR FOR THE SECOND TABLE
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = $_POST['PortfolioCompanyName'];
        $PortfolioCompanyWebsite = $_POST['PortfolioCompanyWebsite'];
        $Details                 = $_POST['Details'];
        $YearFounded             = $_POST['YearFounded'];
        $Headquarters            = $_POST['Headquarters'];
        $Stake                   = $_POST['Stake'];
        $TotalInvestmentValue    = $_POST['TotalInvestmentValue'];
        $Industry                = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        $Currency                = $_POST['Currency'];

        // DEFINED VAR FOR THE THIRD TABLE
        // USER DETAIL TABLE
        $FirstName       = $_POST['FirstName'];
        $LastName        = $_POST['LastName'];         
        $Email           = $_POST['Email'];
        $ContactNumber1  = $_POST['ContactNumber1'];    
        $ContactNumber2  = $_POST['ContactNumber2'];
        $RoleType        = $_POST['RoleType'];
        $Gender          = $_POST['Gender'];
        $Race            = $_POST['Race'];

        // DEFINED VAR FOR THE FOURTH TABLE
        // INVESTOR TABLE
        $InvestorName           = $_POST['InvestorName'];
        $InvestorWebsite        = $_POST['InvestorWebsite'];
        $ImpactTag              = $_POST['ImpactTag'];
        $YearFounded            = $_POST['YearFounded'];
        $InvestorHeadquarters   = $_POST['InvestorHeadquarters'];
        $Logo                   = $_FILES['img']['name'];
        $InvestorNote           = $_POST['InvestorNote'];

        // DEFINED VAR FOR THE FIFTH TABLE
        $Description            = $_POST['Description'];

        // DEFINED VAR FOR THE SIXTH TABLE
        // FUND TABLE

        $FundName               = $_POST['FundName'];
        $CommittedCapitalOfFund = $_POST['CommittedCapitalOfFund'];
        $CommittedCapital       = $_POST['CommittedCapital'];
        $MinimumInvestment      = $_POST['MinimumInvestment'];
        $MaximumInvestment      = $_POST['MaximumInvestment'];
        $FundNote               = $_POST['FundNote'];

        // **** DEFINED VAR FOR THE MAPPING TABLES **** //
        $InvestmentStage        = $_POST['InvestmentStage'];

        // BELOW ARE THE INSERT STATEMENTS TO THE DATABASE TABLES. Also 1ST CONDITION IF 1ST SUBMISSION IS SUCCESSFULL

        $sql = "INSERT INTO news(NewsID, CreatedDate, ModifiedDate, NewsDate, NewsURL) 
        VALUES (uuid(), now(), now(),'$NewsDate', '$NewsURL')";

        // QUERY THE FIRST TABLE USING THE mysqli_query FUNCTION/METHOD
        $query = mysqli_query($conn, $sql);

        // echo ('Form 1 Submitted'.' => '.$query.'<br/>');

        // **** 2ND CONDITION IF 1ST SUBMISSION IS SUCCESSFULL **** //
        if($query){
            $sql2 = "INSERT INTO portfoliocompanynew( PortfolioCompanyID, CreatedDate, ModifiedDate, PortfolioCompanyName, CurrencyID, PortfolioCompanyWebsite,TotalInvestmentValue, Stake, Details, YearFounded, Headquarters, IndustryID, SectorID)
            VALUES (uuid(), now(), now(),'$PortfolioCompanyName', (select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite','$TotalInvestmentValue', '$Stake', '$Details', '$YearFounded', (select country.CountryID FROM country where country.Country = '$Headquarters'),(select industry.IndustryID FROM industry where industry.Industry = '$Industry'), (select sector.SectorID FROM sector where sector.Sector = '$Sector'))";
            $query2 = mysqli_query($conn, $sql2);
            // echo 'Form 2 Submitted! => '.$query2.'<br/>';
            // echo ('Var Industry'.' => '.$Industry.'<br/>');
            // echo ('Var Sector'.' => '.$Sector.'<br/>');
        } else {
            echo 'Oops! There was an error submitting Form 2';
        }

        // **** 3RD CONDITION IF 2ND SUBMISSION IS SUCCESSFULL **** //
        if($query){
            // create var m
            $m = "img/".$_FILES['img']['name'];

            // Use move_uploaded_file function to move files
            move_uploaded_file($_FILES['img']['tmp_name'], $m);

            // tmp_name a temporary dir to store our files & we'll transfer them to the m variable path
            // echo "Uploaded Successfully"; 
            $sql3 ="INSERT INTO investor(InvestorID, CreatedDate, ModifiedDate, InvestorName, Website, Description, ImpactTag, YearFounded, Headquarters, Logo) 
            VALUES (uuid(), now(), now(),'$InvestorName', '$InvestorWebsite',(select de.DescriptionID FROM description de where de.Description = '$Description'), '$ImpactTag', '$YearFounded', (select country.CountryID FROM country where country.Country = '$InvestorHeadquarters'),'$Logo')";

            $query3 = mysqli_query($conn, $sql3);
            // echo 'Form 3 Submitted! => '.$query3.'<br/>';
        }else{
            echo 'Oops! There was an error submitting Form 3';
        }
        
        // **** 4TH CONDITION IF 3RD SUBMISSION IS SUCCESSFULL **** //
        if($query3){
            $sql4 = "INSERT INTO fund(FundID, CreatedDate, ModifiedDate, FundName, CurrencyID, CommittedCapitalOfFund, CommittedCapital, MinimumInvestment, MaximumInvestment)
            VALUES (uuid(), now(), now(), '$FundName',(select C.CurrencyID FROM currency C where C.Currency = '$Currency' ), '$CommittedCapitalOfFund', '$CommittedCapital', '$MinimumInvestment', '$MaximumInvestment')";
            $query4 = mysqli_query($conn, $sql4);
            // echo 'Form 4 Submitted!'.$query4.'<br/>';
        } else {
            echo 'Oops! There was an error submitting Form 4'.'<br/>';
        }

        // **** 5TH CONDITION IF 4RD SUBMISSION IS SUCCESSFULL **** //
        if($query4){
            $sql5 = "INSERT INTO userdetail(UserDetailID, CreatedDate, ModifiedDate, FirstName, LastName, ContactNumber1, ContactNumber2, Email, RoleTypeID, GenderID, RaceID)
            VALUES (uuid(), now(), now(),'$FirstName', '$LastName', '$ContactNumber1', '$ContactNumber2', '$Email',(select R.RoleTypeID FROM roletype R where R.RoleType = '$RoleType'), (select G.GenderID FROM gender G where G.Gender = '$Gender'), (select RC.RaceID FROM race RC where RC.Race = '$Race') )";
            $query5 = mysqli_query($conn, $sql5);
            // echo 'Form 5 Submitted!=> '.$query5.'<br/>';
        } else {
            echo 'Oops! There was an error submitting Form 5'.'<br/>';
        }

        // **** 5TH CONDITION IF 4RD SUBMISSION IS SUCCESSFULL **** //
        // **** INSERT STATEMENTS FOR THE NOTE TABLE **** //
        if($query5){
            $sql6 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note)
            VALUES (uuid(), now(), now(), '$NewsNote')";
            $query6 = mysqli_query($conn, $sql6);

            $sql7 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note)
            VALUES (uuid(), now(), now(), '$InvestorNote')";
            $query7 = mysqli_query($conn, $sql7);

            $sql8 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note)
            VALUES (uuid(), now(), now(), '$FundNote')";
            $query8 = mysqli_query($conn, $sql8);

            // echo 'Form 6 Submitted!=> '.$query6.'<br/>';
            // echo 'Form 6 Submitted!=> '.$query7.'<br/>';
            // echo 'Form 6 Submitted!=> '.$query8.'<br/>';
        } else {
            echo 'Oops! There was an error submitting Form 6'.'<br/>';
        }

        // **** INSERT STATEMENTS FOR THE 14 MAPPING TABLES **** //

        // FUND TABLE MAPPINGS //
        if($query4){
            $sql9 = "INSERT INTO fundindustry(FundIndustryID, CreatedDate, ModifiedDate, FundID, IndustryID)
            VALUES (uuid(), now(), now(), (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select I.IndustryID FROM industry I where I.Industry = '$Industry') )";
            $query9 = mysqli_query($conn, $sql9);
            // echo 'Mapping table FundIndustry success! => '.$query9.'<br/>';
        }else{
            echo 'Oops! There was an error on Mapping table FundIndustry'.'<br/>';
        }

        if($query4){
            $sql10 = "INSERT INTO fundinvestmentStage(FundInvestmentStageID, CreatedDate, ModifiedDate, FundID, InvestmentStageID)
            VALUES (uuid(), now(), now(), (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select InvestmentStage.InvestmentStageID FROM InvestmentStage where InvestmentStage.InvestmentStage = '$InvestmentStage') )";
            $query10 = mysqli_query($conn, $sql10);
            // echo 'Mapping table FundInvestmentStage success! => '.$query10.'<br/>';
        }else{
            echo 'Oops! There was an error on Mapping table FundInvestmentStage'.'<br/>';
        }

        if($query4){
            $sql11 = "INSERT INTO fundinvestor(FundInvestorID, CreatedDate, ModifiedDate, FundID, InvestorID)
            VALUES (uuid(), now(), now(), (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'))";
            $query11 = mysqli_query($conn, $sql11);
            // echo 'Mapping table FundInvestor success! => '.$query11.'<br/>';
        }else{
            echo 'Oops! There was an error on Mapping table FundInvestor';
        }

        if($query4){
            $sql12 = "INSERT INTO fundnews(FundNewsID, CreatedDate, ModifiedDate, FundID, NewsID)
            VALUES (uuid(), now(), now(), (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select news.NewsID FROM news where news.NewsURL = '$NewsURL'))";
            $query12 = mysqli_query($conn, $sql12);
            // echo 'Mapping table FundNews success! => '.$query12.'<br/>';
        }else{
            echo 'Oops! There was an error on Mapping table FundNews'.'<br/>';
        }

        if($query5){
            $sql13 = "INSERT INTO fundnote(FundNoteID, CreatedDate, ModifiedDate, FundID, NoteID)
            VALUES (uuid(), now(), now(), (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select Note.NoteID FROM Note where Note.Note = '$FundNote'))";
            $query13 = mysqli_query($conn, $sql13);
            // echo 'Mapping table FundNote success! => '.$query13.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table FundNote'.'<br/>';
        }

        if($query4){
            $sql14 = "INSERT INTO fundportfoliocompany(FundPortfolioCompanyID, CreatedDate, ModifiedDate, FundID, PortfolioCompanyID)
            VALUES (uuid(), now(), now(), (select Fund.FundID FROM Fund where Fund.FundName = '$FundName'), (select portfoliocompanynew.PortfolioCompanyID FROM portfoliocompanynew where portfoliocompanynew.PortfolioCompanyName = '$PortfolioCompanyName'))";
            $query14 = mysqli_query($conn, $sql14);
            // echo 'Mapping table FundPortfolioCompany success! => '.$query14.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table FundPortfolioCompany'.'<br/>';
        }

        // INVESTOR TABLE MAPPINGS //
        if($query3){
            $sql15 = "INSERT INTO investornews(InvestorNewsID, CreatedDate, ModifiedDate, InvestorID, NewsID)
            VALUES (uuid(), now(), now(), (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select news.NewsID FROM news where news.NewsURL = '$NewsURL'))";
            $query15 = mysqli_query($conn, $sql15);
            // echo 'Mapping table InvestorNews success! => '.$query15.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table InvestorNews'.'<br/>';
        }

        if($query5){
            $sql16 = "INSERT INTO investornote(InvestorNoteID, CreatedDate, ModifiedDate, InvestorID, NoteID)
            VALUES (uuid(), now(), now(), (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select Note.NoteID FROM Note where Note.Note = '$InvestorNote'))";
            $query16 = mysqli_query($conn, $sql16);
            // echo 'Mapping table InvestorNote success! => '.$query16.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table InvestorNote'.'<br/>';
        }

        if($query2){
            $sql17 = "INSERT INTO investorportfoliocompany(InvestorPortfolioCompanyID, CreatedDate, ModifiedDate, InvestorID, PortfolioCompanyID)
            VALUES (uuid(), now(), now(), (select Investor.InvestorID FROM Investor where Investor.InvestorName = '$InvestorName'), (select portfoliocompanynew.PortfolioCompanyID FROM portfoliocompanynew where portfoliocompanynew.PortfolioCompanyName = '$PortfolioCompanyName'))";
            $query17 = mysqli_query($conn, $sql17);
            // echo 'Mapping table InvestorPortfolioCompany success! => '.$query17.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table InvestorPortfolioCompany'.'<br/>';
        }

        // PortfolioCompany TABLE MAPPINGS //
        if($query2){
            $sql18 = "INSERT INTO portfoliocompanycountry(PortfolioCompanyCountryID, CreatedDate, ModifiedDate, PortfolioCompanyID, CountryID)
            VALUES (uuid(), now(), now(),(select portfoliocompanynew.PortfolioCompanyID FROM portfoliocompanynew where portfoliocompanynew.PortfolioCompanyName = '$PortfolioCompanyName'), (select Country.CountryID FROM Country where Country.Country = '$Headquarters'))";
            $query18 = mysqli_query($conn, $sql18);
            // echo 'Mapping table PortfolioCompanyCountry success! => '.$query18.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table PortfolioCompanyCountry'.'<br/>';
        }

        if($query2){
            $sql19 = "INSERT INTO portfoliocompanynews(PortfolioCompanyNewsID, CreatedDate, ModifiedDate, PortfolioCompanyID, NewsID)
            VALUES (uuid(), now(), now(),(select portfoliocompanynew.PortfolioCompanyID FROM portfoliocompanynew where portfoliocompanynew.PortfolioCompanyName = '$PortfolioCompanyName'), (select news.NewsID FROM news where news.NewsURL = '$NewsURL'))";
            $query19 = mysqli_query($conn, $sql19);
            // echo 'Mapping table PortfolioCompanyNews success! => '.$query19.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table PortfolioCompanyNews'.'<br/>';
        }

        if($query2){
            $sql20 = "INSERT INTO portfoliocompanysector(PortfolioCompanySectorID, CreatedDate, ModifiedDate, PortfolioCompanyID, SectorID)
            VALUES (uuid(), now(), now(),(select portfoliocompanynew.PortfolioCompanyID FROM portfoliocompanynew where portfoliocompanynew.PortfolioCompanyName = '$PortfolioCompanyName'), (select S.SectorID FROM sector S where S.Sector = '$Sector'))";
            $query20 = mysqli_query($conn, $sql20);
            // echo 'Mapping table PortfolioCompanySector success! => '.$query20.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table PortfolioCompanySector'.'<br/>';
        }

        if($query2){
            $sql21 = "INSERT INTO portfoliocompanyuserdetail(PortfolioCompanyUserDetailID, CreatedDate, ModifiedDate, PortfolioCompanyID, UserDetailID)
            VALUES (uuid(), now(), now(),(select portfoliocompanynew.PortfolioCompanyID FROM portfoliocompanynew where portfoliocompanynew.PortfolioCompanyName = '$PortfolioCompanyName'), (select UserDetail.UserDetailID FROM UserDetail where UserDetail.Email = '$Email'))";
            $query21 = mysqli_query($conn, $sql21);
            // echo 'Mapping table PortfolioCompanyUserDetail success! => '.$query21.'<br/>';
        } else {
            echo 'Oops! There was an error on Mapping table PortfolioCompanyUserDetail'.'<br/>';
        }

        $conn->close();
        echo '<H3>Thanks for contibuting your data to the Deal Database!</H3> '
        .'<br/>'
        .'<small>You will be redirected shortly...</small>';
        header("Refresh:5; url=../index.php");
    };
?>