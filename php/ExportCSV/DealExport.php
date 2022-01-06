<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('../App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('NewsDate','News URL','Portfolio Company Name','Investment Manager(s)','Fund(s)', 'Total Investment Value','Stake','Industry','Sector','Investment Stage', 'Country','Company Contact','Role Type','Fund Note'));
            $query ="SELECT
                         News.NewsDate,News.NewsURL, PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, FORMAT(Deals.InvestmentValue, 'c', 'en-US') AS 'InvestmentValue', Deals.stake, GROUP_CONCAT(DISTINCT Industry) AS Industry , GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, GROUP_CONCAT(DISTINCT Country) AS Country, UserDetail.UserFullName, RoleType.RoleType, Note.Note
                    FROM 
                        Deals 
                    -- Include investor table data through the linking table Dealsinvestor
                    LEFT JOIN
                        DealsInvestor
                    ON 
                        DealsInvestor.DealsID = Deals.DealsID
                    -- Include Investor table data
                    LEFT JOIN
                        Investor
                    ON
                        Investor.InvestorID = DealsInvestor.InvestorID
                    -- Include fund table data through the linking table Dealsfund
                    LEFT JOIN
                        DealsFund
                    ON 
                        DealsFund.DealsID = Deals.DealsID 
                    -- include Fund table data
                    LEFT JOIN
                        Fund
                    ON
                        Fund.FundID = DealsFund.FundID 
                    -- Include News table data 
                    LEFT JOIN 
                        News 
                    ON
                        News.NewsID = Deals.NewsID 
                    LEFT JOIN 
                    -- Include PortfoliCompany table data
                        PortfolioCompany
                    ON
                        PortfolioCompany.PortfolioCompanyID = Deals.PortfolioCompanyID
                    LEFT JOIN 
                    -- Link investment stage to fund
                        FundInvestmentStage      
                    ON          
                        FundInvestmentStage.FundID = Fund.FundID 
                    LEFT JOIN
                        InvestmentStage
                    ON
                        InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID 
                    LEFT JOIN 
                        PortfolioCompanyLocation
                    ON
                        PortfolioCompanyLocation.PortfolioCompanyID = Deals.PortfolioCompanyID
                    LEFT JOIN 
                        Country
                    ON 
                        Country.CountryID = PortfolioCompanyLocation.CountryID
                    LEFT JOIN 
                        DealsIndustry
                    ON 
                        DealsIndustry.DealsID = Deals.DealsID
                    LEFT JOIN 
                        Industry
                    ON 
                        Industry.IndustryID = DealsIndustry.IndustryID
                    LEFT JOIN 
                        DealsSector
                    ON 
                        DealsSector.DealsID = Deals.DealsID
                    LEFT JOIN 
                        Sector
                    ON 
                        Sector.SectorID = DealsSector.SectorID
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
                        DealsNote
                    ON 
                        DealsNote.DealsID = Deals.DealsID
                    LEFT JOIN 
                        Note
                    ON 
                        Note.NoteID =DealsNote.NoteID
                    WHERE 
                        Deals.Deleted = 0
                    GROUP BY NewsURL, NewsDate, PortfolioCompanyName, InvestmentValue, stake, Country, UserFullName, RoleType, Note
                    ORDER BY  News.NewsDate
        ";
        
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>

