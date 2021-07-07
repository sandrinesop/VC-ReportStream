<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('NewsDate','News URL','Portfolio Company Name','Investment Manager(s)', 'Total Investment Value','Industry','Sector','Fund Name','Investment Stage', 'Country'));
        $query = 
                "SELECT 
                     News.NewsDate,News.NewsURL, PortfolioCompany.PortfolioCompanyName, Investor.InvestorName, PortfolioCompany.TotalInvestmentValue,Industry.Industry, Sector.Sector, Fund.FundName, InvestmentStage.InvestmentStage, Country.Country 
                FROM 
                    PortfolioCompanyNews 
                LEFT JOIN 
                    News -- Joining inorder to get news table data
                ON
                    News.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN -- Joining inorder to get Portfolio Company table data 
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID 
                LEFT JOIN -- Joining inorder to get link the Investor table 
                    InvestorNews
                ON
                    InvestorNews.NewsID = PortfolioCompanyNews.NewsID 
                LEFT JOIN -- Joining inorder to get the Investor table data
                    Investor
                ON
                    Investor.InvestorID = InvestorNews.InvestorID 
                LEFT JOIN -- Joining inorder to link the sector data
                    PortfolioCompanySector
                ON
                    PortfolioCompanySector.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID 
                LEFT JOIN -- Joining inorder to get the sector table data
                    Sector
                ON
                    Sector.SectorID = PortfolioCompanySector.SectorID 
                LEFT JOIN -- Joining inorder to link the Fund data
                    FundPortfolioCompany
                ON
                    FundPortfolioCompany.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID 
                LEFT JOIN -- Joining inorder to get Fund table data 
                    Fund
                ON
                    Fund.FundID = FundPortfolioCompany.FundID 
                LEFT JOIN -- Joining inorder to link the FundInvestmentStage data
                    FundInvestmentStage
                ON
                    FundInvestmentStage.FundID = Fund.FundID 
                LEFT JOIN -- Joining inorder to get InvestmentStage table data 
                    InvestmentStage
                ON
                    InvestmentStage.InvestmentStageID = FundInvestmentStage.InvestmentStageID 
                LEFT JOIN -- Joining inorder to link the PortfolioCompanyCountry data
                    PortfolioCompanyCountry
                ON
                    PortfolioCompanyCountry.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyCountry.CountryID
                LEFT JOIN 
                    PortfolioCompanyIndustry
                ON 
                    PortfolioCompanyIndustry.PortfolioCompanyID = PortfolioCompanyNews.PortfolioCompanyID
                LEFT JOIN 
                    Industry
                ON 
                    Industry.IndustryID = PortfolioCompanyIndustry.IndustryID
        ";
        
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>

