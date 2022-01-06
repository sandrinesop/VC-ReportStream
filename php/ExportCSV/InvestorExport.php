<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('../App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('Investment Manager','Website','Fund(s)','Portfolio Company List','Note','Description','Currency','YearFounded','Country', 'Logo'));
        $query = "  SELECT 
                        Investor.InvestorName, GROUP_CONCAT(DISTINCT Investor.Website) AS Website, GROUP_CONCAT(DISTINCT FundName) AS FundName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName, Note.Note, Description.Description, Currency.Currency, Investor.YearFounded, GROUP_CONCAT(DISTINCT Country) AS Country
                    FROM 
                        Investor
                        -- Joining linking table so that we can access funds linked to investor
                    LEFT JOIN 
                        FundInvestor 
                    ON 
                        FundInvestor.InvestorID = Investor.InvestorID
                    LEFT JOIN 
                        Fund 
                    ON 
                        Fund.FundID = FundInvestor.FundID
                        -- Joining linking table so that we can access Portfolio Companies linked to investor
                    LEFT JOIN 
                        InvestorPortfolioCompany 
                    ON 
                        InvestorPortfolioCompany.InvestorID = Investor.InvestorID
                    LEFT JOIN 
                        PortfolioCompany 
                    ON 
                        PortfolioCompany.PortfolioCompanyID = InvestorPortfolioCompany.PortfolioCompanyID
                        -- Joining linking table so that we can access Notes linked to investor
                    LEFT JOIN 
                        InvestorNote 
                    ON 
                        InvestorNote.InvestorID = Investor.InvestorID
                    LEFT JOIN 
                        Note 
                    ON 
                        Note.NoteID = InvestorNote.NoteID
                    LEFT JOIN 
                        Currency 
                    ON 
                        Currency.CurrencyID=Investor.CurrencyID
                        
                    LEFT JOIN 
                        Description 
                    ON 
                        Description.DescriptionID=Investor.DescriptionID 
                    LEFT JOIN 
                        InvestorLocation
                    ON
                        InvestorLocation.InvestorID = Investor.InvestorID
                    LEFT JOIN 
                        Country 
                    ON 
                        Country.CountryID = InvestorLocation.CountryID
                    WHERE 
                        Investor.Deleted= 0 
                    
                    GROUP BY InvestorName, Description, Currency, Note, YearFounded
                    ORDER BY InvestorName;
        ";

        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>