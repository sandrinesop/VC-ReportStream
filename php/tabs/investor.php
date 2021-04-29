<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $sql=" SELECT * FROM investor";
    // $result = mysqli_query($conn, $sql);
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = $conn->query($sql) or die($conn->error);
    $row = mysqli_fetch_assoc($result);

    $sql2=" SELECT * FROM country";
    $result2 = $conn->query($sql2) or die($conn->error);
    $row2 = mysqli_fetch_assoc($result2);

    // INVESTOR INSERTS
    if ( isset($_POST['submit']))
        {
            $InvestorName           = $_POST['InvestorName'];
            $InvestorWebsite        = $_POST['InvestorWebsite'];
            $ImpactTag              = $_POST['ImpactTag'];
            $YearFounded            = $_POST['YearFounded'];
            $Headquarters           = $_POST['Headquarters'];
            $Logo                   = $_FILES['img']['name'];
            $InvestorNote           = $_POST['InvestorNote'];
            $Description            = $_POST['Description'];

            // INVESTOR NOTE INSERT

            $sql2 = "INSERT INTO Note(NoteID, CreatedDate, ModifiedDate, Note)
            VALUES (uuid(), now(), now(), '$InvestorNote')";
            $query2 = mysqli_query($conn, $sql2);

            if($query2){
                // echo 'Form 3 Submitted! => '.$query3.'<br/>';
            } else {
                echo 'Oops! There was an error submitting Investor Note'.'<br/>';
            }

            // INVESTOR TABLE INSERT
            $m = "../img/".$_FILES['img']['name'];

            // Use move_uploaded_file function to move files
            move_uploaded_file($_FILES['img']['tmp_name'], $m);

            // tmp_name a temporary dir to store our files & we'll transfer them to the m variable path
            // echo "Uploaded Successfully"; 
            $sql3 ="INSERT INTO investor(InvestorID, CreatedDate, ModifiedDate, InvestorName, Website, Description, ImpactTag, YearFounded, Headquarters, Logo) 
            VALUES (uuid(), now(), now(),'$InvestorName', '$InvestorWebsite',(select de.DescriptionID FROM description de where de.Description = '$Description'), '$ImpactTag', '$YearFounded', (select country.CountryID FROM country where country.Country = '$Headquarters'),'$Logo')";

            $query3 = mysqli_query($conn, $sql3);
            if($query3){
                // echo 'Thanks for your contribution! You will be redirected in 3 sec...';
                header( "refresh: 3;url= investor.php" );
        }else{
            echo 'Oops! There was an error submitting Investor table data';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Investor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/main.css">
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../../index.php"><img style=" width: 80px;" class="home-ico" src="../../resources/DCA_Icon.png" alt="Digital collective africa logo"> VC Reportstream  </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" >Digital Collective Africa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../WebInterface.php">New Deal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <div class="my-2">
                    <div class="row">
                        <!-- CREATE NEW INVESTOR MODAL -->
                        <span class="col-2">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn new-button " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                New Investor <img src="../../resources/icons/New.svg" alt="">
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create New Investor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="container" method="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorName" class="form-label">Investor Name</label>
                                                        <input list="investors" type="text" class="form-control" id="InvestorName" name="InvestorName" required>
                                                        <datalist id="investors">
                                                            <option value=" 100x Group">
                                                            <option value=" 12Tree Finance">
                                                            <option value=" 138 Pyramids">
                                                            <option value=" 27Four Investment Managers">
                                                            <option value=" 4Di Capital">
                                                            <option value=" 4DX Ventures">
                                                            <option value=" 500 Startups">
                                                            <option value=" 57 Stars">
                                                            <option value=" 9Yards Capital">
                                                            <option value=" A15">
                                                            <option value=" ABC World Asia Pte Ltd">
                                                            <option value=" ABN AMRO Social Impact Fund">
                                                            <option value=" Absa Group">
                                                            <option value=" Acceleprise">
                                                            <option value=" Accial Capital">
                                                            <option value=" Accion">
                                                            <option value=" Acorn Private Equity">
                                                            <option value=" Acorus Capital">
                                                            <option value=" Act Venture Capital">
                                                            <option value=" ACTIAM Impact Investing*">
                                                            <option value=" Actis Africa Ltd">
                                                            <option value=" Acuity Venture Partners">
                                                            <option value=" Acumen">
                                                            <option value=" Adenia Partners">
                                                            <option value=" Adinah Capital Partners">
                                                            <option value=" Adiwale Partners">
                                                            <option value=" Adlevo Capital">
                                                            <option value=" Aegon">
                                                            <option value=" AFIG Funds">
                                                            <option value=" AfrAsia Bank">
                                                            <option value=" Africa Finance Corporation (AFC)">
                                                            <option value=" Africa Lighthouse Capital">
                                                            <option value=" Africa Rainbow Capital">
                                                            <option value=" Africa Trust Group">
                                                            <option value=" African Capital Alliance">
                                                            <option value=" African Development Bank">
                                                            <option value=" African Infrastructure Investment Managers (Pty) Ltd">
                                                            <option value=" AfricInvest Group">
                                                            <option value=" Afza Capital">
                                                            <option value=" AgDevCo">
                                                            <option value=" Agile Holdings">
                                                            <option value=" Agility Ventures">
                                                            <option value=" Agis Ventures">
                                                            <option value=" Agri-Business Capital (ABC Fund)">
                                                            <option value=" AgVentures">
                                                            <option value=" AHL Venture Partners">
                                                            <option value=" Ahmed Hentati">
                                                            <option value=" Akili Partners">
                                                            <option value=" Alameda Research / FTX">
                                                            <option value=" Alex Angels">
                                                            <option value=" Alexandria Fund">
                                                            <option value=" Algebra Ventures">
                                                            <option value=" Alitheia Capital">
                                                            <option value=" Alitheia IDF">
                                                            <option value=" All On">
                                                            <option value=" Allianz Global Investors">
                                                            <option value=" AlphaCode">
                                                            <option value=" alter equity">
                                                            <option value=" Amethis">
                                                            <option value=" Amplifi VC">
                                                            <option value=" Anava Seed Fund">
                                                            <option value=" Andreessen Horowitz">
                                                            <option value=" Anesvad">
                                                            <option value=" Angel Investment Network">
                                                            <option value=" Anorak Ventures">
                                                            <option value=" Anthemis">
                                                            <option value=" Antler">
                                                            <option value=" API Capital">
                                                            <option value=" Aptive Capital">
                                                            <option value=" ARCH Emerging Markets Partners">
                                                            <option value=" Argentil Capital Partners">
                                                            <option value=" ARM-Harith Infrastructure Investment Limited">
                                                            <option value=" Aruwa Capital">
                                                            <option value=" Ascension Capital Partners">
                                                            <option value=" Ascent Capital Africa">
                                                            <option value=" Ashburton Fund Managers (Proprietary) Limited">
                                                            <option value=" Asia Africa Investment Consulting (AAIC)">
                                                            <option value=" ASMA Invest">
                                                            <option value=" ASOC Management Company">
                                                            <option value=" Ata Capital (Pty) Ltd">
                                                            <option value=" Athaal Group">
                                                            <option value=" Athena Capital (Pty) Ltd">
                                                            <option value=" Athena Capital Advisors">
                                                            <option value=" Atlantica Ventures">
                                                            <option value=" AU21 Capital">
                                                            <option value=" AUC Angels">
                                                            <option value=" AV Ventures">
                                                            <option value=" Averroes VC">
                                                            <option value=" Awethu Project Capital">
                                                            <option value=" AXA Investment Managers">
                                                            <option value=" Axian Group">
                                                            <option value=" Backstage Capital">
                                                            <option value=" Baillie Gifford">
                                                            <option value=" Bain Capital">
                                                            <option value=" Bank of America">
                                                            <option value=" Barak Fund Management">
                                                            <option value=" Beco Capital">
                                                            <option value=" Berkeley Energy">
                                                            <option value=" Bertelsmann Stiftung">
                                                            <option value=" Betatron">
                                                            <option value=" Bezos Expeditions">
                                                            <option value=" BFS Fund Manager">
                                                            <option value=" Big Society Capital">
                                                            <option value=" Bill & Melinda Gates Foundation">
                                                            <option value=" Binance Labs">
                                                            <option value=" BIO (Belgian Investment Company for Developing Countries)">
                                                            <option value=" Bittrex">
                                                            <option value=" BIX Capital">
                                                            <option value=" Black Business Council">
                                                            <option value=" Black Coffee">
                                                            <option value=" BlackRock">
                                                            <option value=" Blackstone">
                                                            <option value=" BlueOrchard Finance">
                                                            <option value=" Bopa Moruo">
                                                            <option value=" Boundary Holding">
                                                            <option value=" Bpifrance Investissement">
                                                            <option value=" Brandon Drew">
                                                            <option value=" Breakthrough Energy Ventures">
                                                            <option value=" Bridges Fund Management">
                                                            <option value=" BrightEdge">
                                                            <option value=" Brighter Life Kenya 1 Limited (BLK1)">
                                                            <option value=" Brightmore Capital">
                                                            <option value=" BTG Pactual">
                                                            <option value=" Business Partners Limited">
                                                            <option value=" Cairo Angels">
                                                            <option value=" Caisse des Depots et Consignations du Gabon">
                                                            <option value=" Calvert Impact Capital">
                                                            <option value=" Cambridge Associates*">
                                                            <option value=" Capital Impact Partners">
                                                            <option value=" Capitalworks Investment Partners">
                                                            <option value=" Capricorn Investment Group">
                                                            <option value=" Capsa Capital Partners">
                                                            <option value=" CardinalStone Capital Advisers">
                                                            <option value=" Casey Family Programs">
                                                            <option value=" Catalyst Fund">
                                                            <option value=" Catalyst Principal Partners">
                                                            <option value=" Cauris Management">
                                                            <option value=" CBRE Global Investors">
                                                            <option value=" CDC Group">
                                                            <option value=" CDG Capital Private Equity">
                                                            <option value=" Celo Ecosystem Fund">
                                                            <option value=" Ceniarth">
                                                            <option value=" Centum Investments">
                                                            <option value=" Cepheus Growth Capital Partners">
                                                            <option value=" Chandaria Capital">
                                                            <option value=" Change Ventures">
                                                            <option value=" Chapel Hill Denham Nigeria Infrastructure Debt Fund (NIDF)">
                                                            <option value=" Christian Super">
                                                            <option value=" Cinnabar Investment Management">
                                                            <option value=" Cisco Global Problem Solver Challenge">
                                                            <option value=" Citigroup">
                                                            <option value=" Clean Energy Ventures">
                                                            <option value=" ClearSky">
                                                            <option value=" Climate Fund Managers">
                                                            <option value=" Climate Investor Two (CI2)">
                                                            <option value=" ClimateWorks Foundation">
                                                            <option value=" CNBB Venture Partners">
                                                            <option value=" Co-Creation Hub (CcHub)">
                                                            <option value=" Coast2Coast Capital">
                                                            <option value=" Compass Venture Capital">
                                                            <option value=" Consonance Investment Managers">
                                                            <option value=" Convergence Finance">
                                                            <option value=" Convergence Partners">
                                                            <option value=" Cordaid Investment Management">
                                                            <option value=" Craft Silicon">
                                                            <option value=" CRE VC">
                                                            <option value=" Credit Suisse">
                                                            <option value=" Crevisse Partners">
                                                            <option value=" CrossBoundary">
                                                            <option value=" Crossfin">
                                                            <option value=" Crucis Venture Capital">
                                                            <option value=" DAAL VC">
                                                            <option value=" DAI Capital">
                                                            <option value=" Dale Williams">
                                                            <option value=" DCVC">
                                                            <option value=" Deciens Capital">
                                                            <option value=" DEG">
                                                            <option value=" Denali Venture Philanthropy">
                                                            <option value=" Deutsche Bank">
                                                            <option value=" Development Bank of Southern Africa (DBSA)">
                                                            <option value=" Development Finance Corporation (DFC)">
                                                            <option value=" Development Partners International LLP">
                                                            <option value=" DFS Lab">
                                                            <option value=" Diaspora Angel Network">
                                                            <option value=" DiGAME Advisory">
                                                            <option value=" Digital Africa Ventures">
                                                            <option value=" Digital Horizon">
                                                            <option value=" Disruptech Ventures">
                                                            <option value=" DOB Equity">
                                                            <option value=" Domini Impact Investments">
                                                            <option value=" Dr Dumebi Okwechime">
                                                            <option value=" Draper Dark Flow (DDF Capital)">
                                                            <option value=" Dream Project Incubators (DPI)">
                                                            <option value=" Dual Gate Investment Holding">
                                                            <option value=" Dubai Angel Investors">
                                                            <option value=" Dutch Fund for Climate and Development (DFCD)">
                                                            <option value=" Dutch Good Growth Fund">
                                                            <option value=" Dyres Ventures">
                                                            <option value=" E-Squared">
                                                            <option value=" Ebikar">
                                                            <option value=" EchoVC Partners">
                                                            <option value=" Ecobank Group">
                                                            <option value=" EDFI">
                                                            <option value=" Edge Growth">
                                                            <option value=" EdVentures">
                                                            <option value=" EF Logistics">
                                                            <option value=" EFG EV">
                                                            <option value=" Egbank">
                                                            <option value=" Egypt Ventures">
                                                            <option value=" Ekuity Capital">
                                                            <option value=" Électricité de France (EDF)">
                                                            <option value=" Elevar Equity">
                                                            <option value=" Emerging Capital Partners">
                                                            <option value=" Emerging Energy Corp">
                                                            <option value=" Emfato Holdings">
                                                            <option value=" Enablis Financial Corporation SA">
                                                            <option value=" Endeavor">
                                                            <option value=" Endure Capital">
                                                            <option value=" Energy Access Ventures">
                                                            <option value=" Enterprise Community Partners">
                                                            <option value=" Entree Capital">
                                                            <option value=" Entrepreneurs for Entrepreneurs (E4E)">
                                                            <option value=" Enygma Ventures">
                                                            <option value=" Enza Capital">
                                                            <option value=" Eos Capital">
                                                            <option value=" Eric Zinterhofer">
                                                            <option value=" Espin Capital">
                                                            <option value=" Essa Al-Saleh">
                                                            <option value=" Ethos Private Equity">
                                                            <option value=" European Investment Bank">
                                                            <option value=" EXEO Capital">
                                                            <option value=" Expert DOJO">
                                                            <option value=" Fajr Capital">
                                                            <option value=" FBNQuest Funds Ltd">
                                                            <option value=" Ferd AS">
                                                            <option value=" Finance in Motion">
                                                            <option value=" FINCA International">
                                                            <option value=" FinDev Canada">
                                                            <option value=" Finnfund">
                                                            <option value=" FinX Capital">
                                                            <option value=" Fitech Ventures">
                                                            <option value=" Flat6labs">
                                                            <option value=" Flourish Ventures">
                                                            <option value=" FMO">
                                                            <option value=" Fondaction">
                                                            <option value=" Fonds de solidarité FTQ">
                                                            <option value=" Fonds Souverain d'Investissements Strategiques">
                                                            <option value=" Ford Foundation">
                                                            <option value=" Foundation North">
                                                            <option value=" Foundation Ventures">
                                                            <option value=" Founder Collective">
                                                            <option value=" Frontline Ventures">
                                                            <option value=" FSD Africa">
                                                            <option value=" Future Africa">
                                                            <option value=" Future Perfect Ventures">
                                                            <option value=" Futuregrowth">
                                                            <option value=" GAIA Fund Managers (Pty) Ltd">
                                                            <option value=" GAIA Impact Fund">
                                                            <option value=" GAN Ventures">
                                                            <option value=" Gary Community Investments">
                                                            <option value=" GAWA Capital">
                                                            <option value=" GFH Holdings">
                                                            <option value=" GGV Capital">
                                                            <option value=" Glenmede">
                                                            <option value=" Glint Ventures / Consulting">
                                                            <option value=" Global Alliance for Improved Nutrition">
                                                            <option value=" Global Capital">
                                                            <option value=" Global Innovation Fund">
                                                            <option value=" Global Partnerships">
                                                            <option value=" Global Ventures">
                                                            <option value=" Golden Palm">
                                                            <option value=" Golding Capital">
                                                            <option value=" GOODsoil VC">
                                                            <option value=" Goodwell Investments">
                                                            <option value=" Google Developers Launchpad">
                                                            <option value=" Google.org">
                                                            <option value=" Government Employees Pension Fund">
                                                            <option value=" Gozem">
                                                            <option value=" Graphene Ventures">
                                                            <option value=" Grassroots Business Fund">
                                                            <option value=" Gray Matters Capital">
                                                            <option value=" GreenHouse Capital">
                                                            <option value=" Greenspring Associates">
                                                            <option value=" GreenTec Capital Partners">
                                                            <option value=" Grenfell Holdings">
                                                            <option value=" Grindrod Private Equity">
                                                            <option value=" Grindstone Ventures">
                                                            <option value=" Grofin">
                                                            <option value=" Grovest Venture Capital Company Limited">
                                                            <option value=" Growing Africa Capital">
                                                            <option value=" Growth Capital Partners">
                                                            <option value=" Growth Grid Venture Capital Partners">
                                                            <option value=" GSV Ventures">
                                                            <option value=" HaloCare">
                                                            <option value=" Hancock Natural Resource Group">
                                                            <option value=" Harith General Partners">
                                                            <option value=" HAVAIC Holdings South Africa">
                                                            <option value=" HCAP Partners*">
                                                            <option value=" Helios Investment Partners">
                                                            <option value=" Heritage Capital">
                                                            <option value=" Hermes Investment Management">
                                                            <option value=" HIMangel">
                                                            <option value=" Hlayisani Capital">
                                                            <option value=" Hubei Forbon Technology Co">
                                                            <option value=" Hult Alumni Angels">
                                                            <option value=" Hustle Fund VC">
                                                            <option value=" i-Cubed Capital (Pty) Ltd">
                                                            <option value=" Ibnsina Pharma">
                                                            <option value=" IDB">
                                                            <option value=" IDF Capital">
                                                            <option value=" IDP Foundation">
                                                            <option value=" Idriss Bello">
                                                            <option value=" IFU (Investment Fund for Developing Countries)">
                                                            <option value=" IJG Capital">
                                                            <option value=" Ikea Foundation">
                                                            <option value=" Impact Amplifier">
                                                            <option value=" Impact Community Capital">
                                                            <option value=" Impax Asset Management">
                                                            <option value=" Imvelo Ventures">
                                                            <option value=" Indigo 7 Ventures (I7V)">
                                                            <option value=" Industrial Development Corporation (IDC)">
                                                            <option value=" Infinitus Holdings (Pty) Ltd">
                                                            <option value=" InfraCo Africa">
                                                            <option value=" InfraCredit">
                                                            <option value=" Ingressive Capital">
                                                            <option value=" Injaro Investments">
                                                            <option value=" Innlife investments">
                                                            <option value=" Innovation Partners Africa">
                                                            <option value=" INOKS Capital">
                                                            <option value=" Inside Capital Partners">
                                                            <option value=" Insitor Partners">
                                                            <option value=" Inspired Evolution Investment Management (Pty) Ltd">
                                                            <option value=" Intel Capital">
                                                            <option value=" International Finance Corporation (IFC)">
                                                            <option value=" Invenfin">
                                                            <option value=" Investec Asset Management - Africa Private Equity Fund">
                                                            <option value=" Investisseurs & Partenaires">
                                                            <option value=" Itanna">
                                                            <option value=" J.P. Morgan">
                                                            <option value=" Janus Henderson Investors">
                                                            <option value=" Jarvie Group">
                                                            <option value=" Jasminum Capital">
                                                            <option value=" Jim Waltrip">
                                                            <option value=" Johannesburg Stock Exchange (JSE)">
                                                            <option value=" Jonathan Rose Companies">
                                                            <option value=" Jordan Park">
                                                            <option value=" Jozi Angels">
                                                            <option value=" JUA Kickstarter Fund">
                                                            <option value=" JUN Capital">
                                                            <option value=" Kagiso Capital">
                                                            <option value=" Kagiso Tiso Holdings">
                                                            <option value=" Kalon Venture Partners">
                                                            <option value=" Karim Jouini">
                                                            <option value=" Kasada Capital Management">
                                                            <option value=" KawiSafi Ventures">
                                                            <option value=" Ke Nako Capital (Pty) Ltd">
                                                            <option value=" Kepple Africa Ventures">
                                                            <option value=" KfW">
                                                            <option value=" Khula Lula">
                                                            <option value=" Khumovest">
                                                            <option value=" Kibo Capital">
                                                            <option value=" Kingson Capital Partners">
                                                            <option value=" Kleoss Capital">
                                                            <option value=" Knarrs Ventures">
                                                            <option value=" KNF Ventures/Knife Capital">
                                                            <option value=" Kois Invest">
                                                            <option value=" Kupanda Capital">
                                                            <option value=" KZN Growth Fund Trust">
                                                            <option value=" Labs by ARM">
                                                            <option value=" Lachy Groom">
                                                            <option value=" Lagos Angel Network">
                                                            <option value=" Lateral Capital">
                                                            <option value=" LeapFrog Investments">
                                                            <option value=" Lendable Inc">
                                                            <option value=" Lendable">
                                                            <option value=" Lexington Partners">
                                                            <option value=" LGT Lightstone">
                                                            <option value=" Linkmakers Capital">
                                                            <option value=" Lion’s Head Global Partners (LHGP)">
                                                            <option value=" LionPride Investment Holdings">
                                                            <option value=" Liquid 2 Ventures">
                                                            <option value=" LocalGlobe">
                                                            <option value=" LoftyInc">
                                                            <option value=" Lombard Odier & Cie">
                                                            <option value=" Lorax Capital Partners">
                                                            <option value=" Loyal VC">
                                                            <option value=" Ludlow Ventures">
                                                            <option value=" Luxembourg Microfinance and Development Fund (LMDF)">
                                                            <option value=" M&G Investments">
                                                            <option value=" MAC Capital Partners">
                                                            <option value=" MaC Venture Capital">
                                                            <option value=" Mahlako-a-Phahla Financial Services">
                                                            <option value=" MAIF">
                                                            <option value=" Makalani Management Company (Pty) Ltd">
                                                            <option value=" Mark Forrester">
                                                            <option value=" Matuca Sarl">
                                                            <option value=" Maxula Gestion">
                                                            <option value=" MCB Equity Fund">
                                                            <option value=" Mediterrania Capital Partners">
                                                            <option value=" Medu Capital">
                                                            <option value=" Meninx Holding">
                                                            <option value=" Mennonite Economic Development Association (MEDA)">
                                                            <option value=" Mercy Corps Ventures">
                                                            <option value=" Mergence">
                                                            <option value=" Mesoamerica">
                                                            <option value=" MEST">
                                                            <option value=" Metier">
                                                            <option value=" MFS Africa">
                                                            <option value=" Michael & Susan Dell Foundation">
                                                            <option value=" Michael Siebel">
                                                            <option value=" Microtraction">
                                                            <option value=" Milton A. & Charlotte R. Kramer Charitable Foundation">
                                                            <option value=" MITC Capital">
                                                            <option value=" MN">
                                                            <option value=" Mobility 54">
                                                            <option value=" Modus Capital">
                                                            <option value=" Montegray Capital">
                                                            <option value=" Morgan Stanley">
                                                            <option value=" Mozilla Corporation">
                                                            <option value=" MSA Capital">
                                                            <option value=" Multiply Group">
                                                            <option value=" Musha Ventures">
                                                            <option value=" Naspers Foundry">
                                                            <option value=" National Australia Bank">
                                                            <option value=" National Empowerment Fund (NEF)">
                                                            <option value=" Nedbank Private Equity">
                                                            <option value=" Nephila">
                                                            <option value=" Neuberger Berman">
                                                            <option value=" New Forests">
                                                            <option value=" NewAfrica Impact">
                                                            <option value=" Newtown Partners">
                                                            <option value=" Niche Capital">
                                                            <option value=" Nigeria Impact Startup Relief Facility (NISRF)">
                                                            <option value=" Nigeria Sovereign Investment Authority">
                                                            <option value=" Nigerian Stock Exchange">
                                                            <option value=" Nile Development & Investment">
                                                            <option value=" Ninety One">
                                                            <option value=" Nonprofit Finance Fund*">
                                                            <option value=" Norfund">
                                                            <option value=" Norican">
                                                            <option value=" Norsad Finance">
                                                            <option value=" Northern Trust">
                                                            <option value=" Novare Equity Partners (Pty) Ltd">
                                                            <option value=" Novastar Ventures">
                                                            <option value=" NREP">
                                                            <option value=" Oasis Capital">
                                                            <option value=" Obviam">
                                                            <option value=" Oikocredit International">
                                                            <option value=" Old Mutual Alternative Investments">
                                                            <option value=" Olumide Soyombo">
                                                            <option value=" Oman Technology Fund">
                                                            <option value=" Omidyar Network">
                                                            <option value=" Omnivore">
                                                            <option value=" One Africa Capital Partners">
                                                            <option value=" One to Watch">
                                                            <option value=" Open Society Foundations">
                                                            <option value=" Orange Ventures">
                                                            <option value=" Ortus Africa Capital">
                                                            <option value=" Outlierz Ventures">
                                                            <option value=" Owl Ventures">
                                                            <option value=" P1 Ventures">
                                                            <option value=" Pacer Ventures">
                                                            <option value=" Pacific Community Ventures">
                                                            <option value=" PAPEfunds">
                                                            <option value=" Paper Plane Ventures/ Platform Investment Partners">
                                                            <option value=" Partech">
                                                            <option value=" PCM Capital Partners">
                                                            <option value=" Pearl Capital Partners">
                                                            <option value=" Pembani Remgro Infrastructure Managers (Pty) Ltd">
                                                            <option value=" Persistent Energy Capital (PEC)">
                                                            <option value=" Pharos Capital Group">
                                                            <option value=" Phatisa">
                                                            <option value=" Pick n Pay">
                                                            <option value=" Pioneer">
                                                            <option value=" Platform Capital Investment Partners">
                                                            <option value=" Plexo Capital">
                                                            <option value=" Plug and Play">
                                                            <option value=" Plus VC (+VC)">
                                                            <option value=" Point Nine Capital">
                                                            <option value=" Polychain">
                                                            <option value=" Portocolom EAF">
                                                            <option value=" Portugal Gateway Growth Fund">
                                                            <option value=" Principal Partners">
                                                            <option value=" ProfitShare Partners">
                                                            <option value=" Proparco">
                                                            <option value=" Prudential">
                                                            <option value=" Public Investment Corporation">
                                                            <option value=" Q-Impact">
                                                            <option value=" Quona Capital">
                                                            <option value=" Raba Partnership">
                                                            <option value=" Rabo Foundation">
                                                            <option value=" Rally Cap Ventures">
                                                            <option value=" Raptor Group">
                                                            <option value=" RBC Global Asset Management">
                                                            <option value=" Realdania">
                                                            <option value=" Renew / Impact Angel Network (IAN)">
                                                            <option value=" ResponsAbility">
                                                            <option value=" Reyl">
                                                            <option value=" RGAx">
                                                            <option value=" RH Managers">
                                                            <option value=" Ribbit Capital">
                                                            <option value=" Rising Tide Africa">
                                                            <option value=" RMB Corvest">
                                                            <option value=" ROAM Africa">
                                                            <option value=" RobecoSAM*">
                                                            <option value=" Rockwood Private Equity">
                                                            <option value=" Ronald Lauder">
                                                            <option value=" Root Capital">
                                                            <option value=" Rothschild & Co Merchant Banking">
                                                            <option value=" RP Global">
                                                            <option value=" Ruby Rock Investment">
                                                            <option value=" Sagemcom">
                                                            <option value=" Sahel Capital">
                                                            <option value=" Sahil Lavingia">
                                                            <option value=" Saisan Company Ltd">
                                                            <option value=" SaltPay">
                                                            <option value=" Samata Capital">
                                                            <option value=" Sampada Private Equity (Pty) Ltd">
                                                            <option value=" Samurai Incubate">
                                                            <option value=" Sanari Capital">
                                                            <option value=" Sandeep Nailwal">
                                                            <option value=" Sango Capital">
                                                            <option value=" Sanlam Private Equity">
                                                            <option value=" Sarona Asset Management">
                                                            <option value=" Sasfin Private Equity Fund Managers">
                                                            <option value=" Savannah Fund">
                                                            <option value=" Savant Fund Managers">
                                                            <option value=" Saviu Ventures">
                                                            <option value=" Sawari Ventures">
                                                            <option value=" SBC">
                                                            <option value=" SBI Investment">
                                                            <option value=" SBM Mauritius Asset Managers">
                                                            <option value=" SEAF">
                                                            <option value=" Secha Capital">
                                                            <option value=" Seedrs">
                                                            <option value=" Segal Family Foundation">
                                                            <option value=" Senatla Capital">
                                                            <option value=" SETsquared">
                                                            <option value=" SGC7375">
                                                            <option value=" Shell Foundation*">
                                                            <option value=" ShEquity">
                                                            <option value=" Sherpa Ventures">
                                                            <option value=" SIMA Funds">
                                                            <option value=" Sithega">
                                                            <option value=" SixThirty">
                                                            <option value=" SJF Ventures*">
                                                            <option value=" Small Foundation">
                                                            <option value=" Smollan">
                                                            <option value=" Soma Capital">
                                                            <option value=" South Suez Capital Ltd">
                                                            <option value=" SPARK+ Africa Fund">
                                                            <option value=" SPE Capital Partners">
                                                            <option value=" SSE Angel Network">
                                                            <option value=" Standard Bank Group">
                                                            <option value=" Stellenbosch Graduate Institute (SGI)">
                                                            <option value=" STOA">
                                                            <option value=" Stockdale Street">
                                                            <option value=" Stripe">
                                                            <option value=" Subtropico Limited">
                                                            <option value=" Summit Private Equity">
                                                            <option value=" SunFunder">
                                                            <option value=" Surdna Foundation">
                                                            <option value=" SUSI Partners">
                                                            <option value=" Swedfund International AB">
                                                            <option value=" Swiss Investment Fund for Emerging Markets (SIFEM)">
                                                            <option value=" Symbiotics">
                                                            <option value=" Synergy Capital Managers">
                                                            <option value=" Takura Capital">
                                                            <option value=" Tamela Holdings (Pty) Ltd">
                                                            <option value=" Tana Africa Capital Managers">
                                                            <option value=" Tangerine Life">
                                                            <option value=" Target Global">
                                                            <option value=" Team Africa Ventures">
                                                            <option value=" Techstars">
                                                            <option value=" The California Endowment">
                                                            <option value=" The Carlyle Group">
                                                            <option value=" The Eastern and Southern African Trade & Development Bank TDB">
                                                            <option value=" The Forest Company">
                                                            <option value=" The Innovation Village">
                                                            <option value=" The Kresge Foundation">
                                                            <option value=" The Lemelson Foundation">
                                                            <option value=" The Oakmont Partnership">
                                                            <option value=" The Rise Fund">
                                                            <option value=" The Rockefeller Foundation">
                                                            <option value=" The SA SME Fund Limited">
                                                            <option value=" The Sasakawa Peace Foundation">
                                                            <option value=" The Vistria Group">
                                                            <option value=" Thinkroom Consulting">
                                                            <option value=" Third Way Investment Partners (TWIP)">
                                                            <option value=" ThomasLloyd Group">
                                                            <option value=" Thuso Incubation Partners">
                                                            <option value=" Tides">
                                                            <option value=" TLcom Capital">
                                                            <option value=" Total Energy Ventures">
                                                            <option value=" TPG Growth">
                                                            <option value=" TRG Capital">
                                                            <option value=" TriLinc Global*">
                                                            <option value=" Trinitas Private Equity">
                                                            <option value=" Trinity Wall Street">
                                                            <option value=" Triodos Investment Management">
                                                            <option value=" Tsadik Foundation">
                                                            <option value=" U.S. Africa Development Foundation (USADF)">
                                                            <option value=" U.S.-African TIES program">
                                                            <option value=" UBank">
                                                            <option value=" UBS">
                                                            <option value=" UGFS-NA">
                                                            <option value=" UK Foreign Commonwealth and Development Office (FCDO)">
                                                            <option value=" uKheshe">
                                                            <option value=" Umkhati Wethu Ventures">
                                                            <option value=" UNICEF">
                                                            <option value=" USADF">
                                                            <option value=" Van Lanschot Kempen">
                                                            <option value=" Vantage Capital Group">
                                                            <option value=" VentureBuilder">
                                                            <option value=" Ventures Platform">
                                                            <option value=" VentureWave Capital">
                                                            <option value=" Verod Capital Management">
                                                            <option value=" VestedWorld">
                                                            <option value=" VFD Group">
                                                            <option value=" ViaMedia">
                                                            <option value=" ViKtoria Ventures">
                                                            <option value=" Village Capital">
                                                            <option value=" Villgro Africa">
                                                            <option value=" Virginia Community Capital">
                                                            <option value=" Vision Ventures">
                                                            <option value=" Vodacom Digital Accelerator">
                                                            <option value=" Vostok New Ventures">
                                                            <option value=" VPB (Pty) Ltd / Temo Capital">
                                                            <option value=" Vumela">
                                                            <option value=" Wallace Global Fund">
                                                            <option value=" Wamda Capital">
                                                            <option value=" Wangara Green Ventures">
                                                            <option value=" Wellington Management">
                                                            <option value=" Wendel Africa">
                                                            <option value=" Wespath Benefits and Investments">
                                                            <option value=" West Africa Capital Advisors">
                                                            <option value=" Westbrooke Capital Management">
                                                            <option value=" WIC Capital">
                                                            <option value=" Women Entrepreneurs Finance Initiative (We-Fi)">
                                                            <option value=" Women's World Banking">
                                                            <option value=" Womena">
                                                            <option value=" XSML Capital">
                                                            <option value=" Y Combinator">
                                                            <option value=" Yellowwoods">
                                                            <option value=" Yoyo">
                                                            <option value=" ZAIS Group">
                                                            <option value=" ZB Capital">
                                                            <option value=" Zebu Investment Partners">
                                                            <option value=" Zedcrest Capital">
                                                            <option value=" Zephyr Management">
                                                            <option value=" Zetogon">
                                                            <option value=" Zurich Insurance Group">
                                                        </datalist>
                                                    </div>  
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="InvestorWebsite" class="form-label">Investor Website</label>
                                                        <input type="text" class="form-control" id="InvestorWebsite" name="InvestorWebsite" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="InvestorNote" class="form-label">Investor Note</label>
                                                        <textarea class="form-control InvestorNote" aria-label="With textarea" id=" InvestorNote" name=" InvestorNote"></textarea>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="ImpactTag" class="form-label">Impact Tag</label>
                                                        <select name="ImpactTag" class="form-select" id="ImpactTag" required>
                                                            <option value="" selected >Choose...</option>
                                                            <option value="Impact">Impact</option>
                                                            <option value="Gender">Gender</option>
                                                            <option value="Race">Race</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="YearFounded" class="form-label">Year Founded</label>
                                                        <input type="text" class="form-control" id="YearFounded" name="YearFounded">
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Headquarters" class="form-label">Headquarters</label>
                                                        <!-- <input type="text" class="form-control" id="Headquarters" name="Headquarters" required> -->
                                                        <select id="Headquarters" name="Headquarters" class="form-select">
                                                            <option>choose...</option>
                                                            <option value="Unknown">Unknown</option>
                                                            <option value="Finland">Finland</option>
                                                            <option value="Canada">Canada</option>
                                                            <option value="Angola">Angola</option>
                                                            <option value="Benin">Benin</option>
                                                            <option value="Botswana">Botswana</option>
                                                            <option value="Burkina Faso">Burkina Faso</option>
                                                            <option value="Cameroon">Cameroon</option>
                                                            <option value="Chad">Chad</option>
                                                            <option value="Democratic Republic of Congo">Democratic Republic of Congo</option>
                                                            <option value="Denmark">Denmark</option>
                                                            <option value="Egypt">Egypt</option>
                                                            <option value="Eswatini">Eswatini</option>
                                                            <option value="Ethiopia">Ethiopia</option>
                                                            <option value="EU">EU</option>
                                                            <option value="Gabon">Gabon</option>
                                                            <option value="Germany">Germany</option>
                                                            <option value="Ghana">Ghana</option>
                                                            <option value="India">India</option>
                                                            <option value="Israel">Israel</option>
                                                            <option value="Ivory Coast">Ivory Coast</option>
                                                            <option value="Kenya">Kenya</option>
                                                            <option value="Lesotho">Lesotho</option>
                                                            <option value="Liberia">Liberia</option>
                                                            <option value="Madagascar">Madagascar</option>
                                                            <option value="Malawi">Malawi</option>
                                                            <option value="Malaysia">Malaysia</option>
                                                            <option value="Mali">Mali</option>
                                                            <option value="Mauritius">Mauritius</option>
                                                            <option value="Morocco">Morocco</option>
                                                            <option value="Mozambique">Mozambique</option>
                                                            <option value="Namibia">Namibia</option>
                                                            <option value="Nepal">Nepal</option>
                                                            <option value="Netherlands">Netherlands</option>
                                                            <option value="Niger">Niger</option>
                                                            <option value="Nigeria">Nigeria</option>
                                                            <option value="Pakistan">Pakistan</option>
                                                            <option value="Pan-African">Pan-African</option>
                                                            <option value="Republic of Congo">Republic of Congo</option>
                                                            <option value="Rwanda">Rwanda</option>
                                                            <option value="Senegal">Senegal</option>
                                                            <option value="Sierra Leone">Sierra Leone</option>
                                                            <option value="Singapore">Singapore</option>
                                                            <option value="Somalia">Somalia</option>
                                                            <option value="South Africa">South Africa</option>
                                                            <option value="South Sudan">South Sudan</option>
                                                            <option value="Sub-Saharan Africa">Sub-Saharan Africa</option>
                                                            <option value="Switzerland">Switzerland</option>
                                                            <option value="Tanzania">Tanzania</option>
                                                            <option value="Togo">Togo</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                        <label for="img" class="form-label">Logo</label>
                                                        <input type="file" class="form-control" id="img" name="img" required>
                                                    </div>
                                                    <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                                                        <label for="Description" class="form-label">Description</label>
                                                        <select name="Description" class="form-select" id="Description" required>
                                                            <option value="" selected >Choose...</option>
                                                            <option value="Asset Manager">Asset Manager</option>
                                                            <option value="Asset Owner">Asset Owner</option>
                                                            <option value="Accelerator">Accelerator</option>
                                                            <option value="Angel Investor">Angel Investor</option>
                                                            <option value="Angel Network">Angel Network</option>
                                                            <option value="Corporate Accelerator">Corporate Accelerator</option>
                                                            <option value="Service Provider">Service Provider</option>
                                                            <option value="Non profit">Non profit</option>
                                                            <option value="Crowdfunding Platform">Crowdfunding Platform</option>
                                                            <option value="Unknown">Unknown</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <!-- EXPORT CSV FILE -->
                        <span class="col-2"> 
                            <form action="../InvestorExport.php" method="POST">
                                <button class="btn new-button" type="submit" name="export" formmethod="POST"> Export CSV</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class=" table table-hover table-striped table-success table-bordered" style="Width: 2400px;line-height: 30px;">
                        <t>
                            <th>Edit </th>
                            <th>Delete </th>
                            <th>Investor ID </th>
                            <th>Created Date</th>
                            <th>Modified Date</th>
                            <th>Investor Name</th>
                            <th>Website</th>
                            <th>Description</th>
                            <th>ImpactTag </th>
                            <th>Year Founded</th>
                            <th>Headquarters</th>
                            <th>Logo</th>
                        </t>
                        <?php
                            while($rows = mysqli_fetch_assoc($result))
                            {
                        ?>
                            <tr>
                                <td> <a href="../crud/Edit.php?InvestorID=<?php echo $rows['InvestorID']; ?>">Edit</a></td>
                                <td> <a href="../crud/Delete.php?InvestorID=<?php echo $rows['InvestorID']; ?>">Delete</a></td>
                                <td> <?php echo $rows['InvestorID'] ?></td>
                                <td> <?php echo $rows['CreatedDate'] ?></td>
                                <td> <?php echo $rows['ModifiedDate'] ?></td>
                                <td> <?php echo $rows['InvestorName'] ?></td>
                                <td> <?php echo $rows['Website'] ?></td>
                                <td> <?php echo $rows['Description'] ?></td>
                                <td> <?php echo $rows['ImpactTag'] ?></td>
                                <td> <?php echo $rows['YearFounded'] ?></td>
                                <td> <?php echo $rows['Headquarters'] ?></td>
                                <td> <?php echo $rows['Logo'] ?></td>
                            </tr>
                        <?php 
                            }
                        ?>
                    </table>
                </div>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../js/scripts.js"></script>
    </body>
</html>
