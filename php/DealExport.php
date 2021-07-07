<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('./App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('NewsDate','News URL','Portfolio Company Name','Investment Manager(s)','Fund Name', 'Total Investment Value','Stake','Industry','Sector','Investment Stage', 'Country'));
        $query = 
                "SELECT DISTINCT
                     News.NewsDate, News.NewsURL,PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, Fund.FundName, deals.InvestmentValue, deals.stake, GROUP_CONCAT(DISTINCT Industry) AS Industry , GROUP_CONCAT(DISTINCT Sector.Sector) AS Sector, GROUP_CONCAT(DISTINCT InvestmentStage) AS InvestmentStage, Country.Country, UserDetail.UserFullName, Roletype.RoleType
                FROM 
                    deals 
                -- Include News table data 
                LEFT JOIN 
                    News 
                ON
                    News.NewsID = deals.NewsID 
                LEFT JOIN 
                -- Include PortfoliCompany table data
                    PortfolioCompany
                ON
                    PortfolioCompany.PortfolioCompanyID = deals.PortfolioCompanyID
                LEFT JOIN 
                -- Include Invesor table data
                    Investor
                ON
                    Investor.InvestorID = deals.InvestorID 
                LEFT JOIN 
                -- include Fund table data
                    Fund
                ON
                    Fund.FundID = deals.FundID 
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
                    PortfolioCompanyCountry
                ON
                    PortfolioCompanyCountry.PortfolioCompanyID = deals.PortfolioCompanyID
                LEFT JOIN 
                    Country
                ON 
                    Country.CountryID = PortfolioCompanyCountry.CountryID
                LEFT JOIN 
                    Industry
                ON 
                    Industry.IndustryID = deals.IndustryID
                LEFT JOIN 
                    DealSector
                ON 
                    DealSector.DealsID = Deals.DealsID
                LEFT JOIN 
                    Sector
                ON 
                    Sector.SectorID = DealSector.SectorID
                LEFT JOIN 
                    UserDetail
                ON 
                    UserDetail.UserDetailID = Deals.UserDetailID1
                LEFT JOIN 
                    RoleType
                ON 
                    RoleType.RoleTypeID = UserDetail.RoleTypeID

                GROUP BY NewsURL, NewsDate, PortfolioCompanyName, InvestmentValue, stake, Country, UserFullName, RoleType
                ORDER BY  news.NewsDate 
        ";
        
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
        fclose($output);
    }
?>

