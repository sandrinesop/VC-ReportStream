<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('Fund Name', 'Investment Manager(s)', 'Portfolio Companies', 'Currency','Currency Code', 'Committed Capital', 'Minimum Investment', 'Maximum Investment', 'Investment Stage', 'Note'));
        $query = "  SELECT 
                        Fund.FundName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT PortfolioCompanyName) AS PortfolioCompanyName , Currency.Currency,Currency.CurrencyCode, FORMAT(Fund.CommittedCapital, 'c', 'en-US') AS 'CommittedCapital' , FORMAT(Fund.MinimumInvestment, 'c', 'en-US') AS 'MinimumInvestment', FORMAT(Fund.MaximumInvestment, 'c', 'en-US') AS 'MaximumInvestment', GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Note.Note
                    FROM 
                        Fund 
                        -- JOINING FUNDINVESTOR TO ACCESS LINKED INVESTORS 
                    LEFT JOIN 
                        FundInvestor 
                    ON 
                    FundInvestor.FundID = Fund.FundID
                    LEFT JOIN 
                        Investor 
                    ON 
                    Investor.InvestorID = FundInvestor.InvestorID
                    --    JOINING FUNDPORTFOLIOCOMPANY TO ACCESS LINKED COMPANIES
                    LEFT JOIN 
                        FundPortfolioCompany 
                    ON 
                    FundPortfolioCompany.FundID = Fund.FundID
                    LEFT JOIN 
                        PortfolioCompany 
                    ON 
                    PortfolioCompany.PortfolioCompanyID = FundPortfolioCompany.PortfolioCompanyID
                    --    JOINING FUNDPINVESTMENTSTAGE TO ACCESS LINKED INVESTMENTSTAGE
                    LEFT JOIN 
                        FundInvestmentStage 
                    ON 
                    FundInvestmentStage.FundID = Fund.FundID
                    LEFT JOIN 
                        InvestmentStage 
                    ON 
                    InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID
                    --    JOINING FUNDNOTE TO ACCESS LINKED NOTE
                    LEFT JOIN 
                        FundNote 
                    ON 
                    FundNote.FundID = Fund.FundID
                    LEFT JOIN 
                        Note 
                    ON 
                    Note.NoteID = FundNote.NoteID

                    LEFT JOIN 
                        Currency 
                    ON 
                        Currency.CurrencyID = Fund.CurrencyID 
                    WHERE  
                        Fund.Deleted = 0

                    GROUP BY  FundName, Currency, CurrencyCode, CommittedCapital, MinimumInvestment, MaximumInvestment,  Note
        ";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>