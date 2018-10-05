<?php
include "config.php";

// Redirect to login page if people are not logged in
if (!isset($_SESSION['user']))
{
    header('location:login.php');
}
$type_klant = 'SELECT * from TypeKlant';
$type_klant_result = $mysqli->query($type_klant);

$soort_incident = 'SELECT * from SoortIncident';
$soort_incident_result = $mysqli->query($soort_incident);
$getDate = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/Select2.css">
        <link rel="stylesheet" href="fontawesome-5.0.13/web-fonts-with-css/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="DataTables/datatables.min.css"/>
        <link rel="stylesheet" href="Style/style.css">
        <link href="Style/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css"/>
         <link rel="stylesheet" href="Style/dx.common.css" />
        <link rel="stylesheet" href="Style/dx.light.css" />
        <style>
            /* webkit solution */
            ::-webkit-input-placeholder {
                text-align: right;
            }

            /* mozilla solution */
            input:-moz-placeholder {
                text-align: right;
            }
        </style>
    </head>

    <body id="body">
        <!--  Navbar     -->
        <div id="collapsedNavbar" class="">

        </div>
        <nav class="navbar navbar-light navbar-custom fixed-top navbar-main" style="margin:-1px 0;">
            <nav class="navbar navbar-expand-sm">
                <!--  Logo -->

                <a class="navbar-brand" href="javascript:void(0)">
                    <img src="images/logo%20ims2.0.png" alt="Logo" style="width:100px;">
                </a>

                <!--   3 content links   -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button id="overzicht" type="button" class="btn btn-info btn-custom">Overzicht lopende incidenten
                        </button>
                    </li>
                    <li class="nav-item">
                        <button id="incident" type="button" class="btn btn-info btn-custom">Nieuw incident melden</button>
                    </li>
                    <li class="nav-item">
                        <button id="rapport" type="button" class="btn btn-info btn-custom">Rapportages</button>
                    </li>
                </ul>
            </nav>

            <nav class="navbar navbar-expand-sm  ml-auto">
                <ul class="navbar-nav">
                    <!--    Admin knop         -->
                    <?php
                    if (isset($_SESSION['user']) && isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == 1)
                    {
                        print '<li class = "nav-item">
                        <button id="admin" type="button" class="btn btn-info btn-custom">Admin</button>
                        </li>';
                    }
                    ?>
                    <!--    Navbar settings toggler    -->
                    <li class="nav-item">
                        <button id="settings" class="btn btn-info btn-custom dropdown-toggle fas fa-cog"
                                data-toggle="dropdown" aria-expanded="false">
                            Navbar
                        </button>

                        <!--    Navbar settings      -->
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <button id="autoscroll" class="set dropdown-item list-group-item-action fas fa-check"
                                    value="autoscroll">Autoscroll
                            </button>
                            <button id="autohide" class="set dropdown-item list-group-item-action" value="autohide">
                                Autohide
                            </button>
                            <!--                                <button id="cursor" class="set dropdown-item list-group-item-action" value="secret">Secret setting :)</button>-->
                        </div>
                    </li>

                    <!--    Uitlog knop         -->
                    <li class="nav-item">
                        <button id="logout" type="button" class="btn btn-info btn-custom">Log uit</button>
                    </li>
                </ul>
            </nav>
        </nav>

        <!--Logout warning modal-->
        <div class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="modalLogOut">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header"><h4> Uitloggen <i class="fa fa-lock"></i></h4></div>
                    <div class="modal-body"><i class="fa fa-question-circle"></i> Weet u zeker dat u wilt uitloggen?</div>
                    <div class="modal-footer"><a href="javascript:void(0);" id="logoutBut"
                                                 class="btn btn-primary btn-block">Uitloggen</a></div>
                </div>
            </div>
        </div>

        <!--Logout confirmation-->
        <div class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="logoutPopup">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body alert-light" style="text-align: center">Uitgelogd!</div>
                </div>
            </div>
        </div>

        <!--Incident edited/saved modal -->
        <div class="modal bs-example-modal-sm edit smol" id="edit">
            <div class="modal-content">
                <div class="icon">
                    <span class="glyphicon glyphicon-ok"></span>
                </div>
                <span class="close" style="text-align: right">&times;</span>
                <h1>Success!</h1>
                <p>Incident is aangepast!</p>
            </div>
        </div>

        <div class="modal bs-example-modal-sm added success smol" id="added">
            <div class="modal-content">
                <div class="icon">
                    <span class="glyphicon glyphicon-ok"></span>
                </div>
                <span class="close" style="text-align: right">&times;</span>
                <h1>Success!</h1>
                <p>Incident toegevoegd</p>
            </div>
        </div>

        <!--  Hier wordt de content van de geklikte link weergegeven   -->
        <div id="content">

        </div>
        <div id="modal" class="edit">
            <div id="fModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg" style="min-width: 1200px">
                    <div id="content1" style="">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h3>INCIDENT FORMULIER</h3>
                                </div>
                                <form method="post" id="incidentID">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                  id="span_margin_radius_padding"></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="input_margin_radius_padding"
                                                               name="Incident_ID" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <button type="button" class="close" data-dismiss="modal" id="modalDismiss">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="formulier">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Balie medewerker</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="input_margin_radius_padding"
                                                               name="Baliemedewerker" placeholder="*">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Datum</span>
                                                        </div>
                                                        <input placeholder="<?= $getDate ?>"
                                                               class="form-control form-control-sm"
                                                               id="input_margin_radius_padding" readonly
                                                               name="Datum">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <h3>Klantgegevens</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body with-border">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Naam</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="input_margin_radius_padding"
                                                               name="Naam" placeholder="*">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Type klant</span>
                                                        </div>
                                                        <select class="select_two TypeKlant" name="TypeKlant" id="TypeKlant"
                                                                style="width:60%; border-radius: 0px 5px 5px 0px; height: 32px;">
                                                            <option value="0"></option>
                                                            <?php
                                                            while ($row = $type_klant_result->fetch_assoc())
                                                            {
                                                                echo '<option value=' . $row["Type_ID"] . '>' . $row["TypeKlant"] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Telefoon</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="input_margin_radius_padding"
                                                               name="Telefoon">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text id_number"
                                                                  id="span_margin_radius_padding">ID Nummer</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm id_number"
                                                               id="input_margin_radius_padding"
                                                               name="ID_Nummer">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Email</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="input_margin_radius_padding"
                                                               name="Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="span_margin_radius_padding">Soort incident</span>
                                                        </div>
                                                        <select class="select_two SoortIncident" name="SoortIncident"
                                                                id="SoortIncident"
                                                                style="width:60%; border-radius: 0px 5px 5px 0px; height: 32px;">
                                                            <option value="0"></option>
                                                            <?php
                                                            while ($row = $soort_incident_result->fetch_assoc())
                                                            {
                                                                echo '<option value="' . $row['SoortIncident_ID'] . '">' . $row['SoortIncident'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col">
                                                    <label>Omschrijving incident</label>
                                                    <textarea class="form-control" name="Omschrijving" id="" cols="30"
                                                              rows="5" placeholder="*"></textarea>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col">
                                                    <label>Actie</label>
                                                    <textarea class="form-control" name="Actie" id="" cols="30" rows="5"
                                                              placeholder="*"></textarea>
                                                </div>
                                            </div>
                                            <br/>
                                            <style>
                                                #toggle_follow_up_action {
                                                    display: none;
                                                }
                                            </style>
                                            <div class="row" id="Vervolg">
                                                <div class="col">
                                                    <label>Vervolg actie</label>
                                                    <i class="fas fa-toggle-on btn" id="toggler"></i>
                                                    <textarea class="form-control" name="VervolgActie"
                                                              id="toggle_follow_up_action" cols="30" rows="5"></textarea>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col">
                                                    <label>Behandeld door</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="input_margin_radius_padding"
                                                           name="Behandelaar" placeholder="*">
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col">
                                                    <label>Uitgevoerde werkzaamheden</label>
                                                    <textarea class="form-control" name="UitgevoerdeWerkzaamheden" cols="30"
                                                              rows="5"></textarea>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col">
                                                    <label>Afspraken</label>
                                                    <textarea class="form-control" name="Afspraken" cols="30"
                                                              rows="5"></textarea>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col">
                                                        <label>Gereed voor sluiten</label>
                                                        <input type="checkbox" name="GereedVoorSluiten1">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col">
                                                        <label>Incident gesloten</label>
                                                        <input type="checkbox" name="GereedVoorSluiten2">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col">
                                                        <label>Sluit datum</label>
                                                        <input type="text" name="SluitDatum" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <input type="submit" id="yourButton" class="btn btn-custom btn-info"
                                                               value="Opslaan">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <button class="btn btn-custom-print btn-light"
                                                                onclick="window.print()">Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!--JQuery main library-->
    <script src="Scripts/jquery-3.3.1.slim.js"></script>

    <!--Popper,js plugin for bootstrap-->
    <script src="Scripts/popper.js"></script>

    <!--Select2 plugin for better dropdown menu-->
    <script src="Scripts/select2.js"></script>

    <!--Datatables plugin for better table search and sort functions-->
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>

    <!--Bootstrap script library-->
    <script src="Scripts/bootstrap.min.js"></script>

    <!--Main script of this app-->
    <script src="Scripts/contentLoader.js"></script>

    <!--Pnotify plugin-->
    <script type="text/javascript" src="Scripts/pnotify.custom.min.js"></script>
    
    <script src="Scripts/cldr.min.js"></script>
    <script src="Scripts/cldr/event.min.js"></script>
    <script src="Scripts/cldr/supplemental.min.js"></script>

    <script src="Scripts/globalize.min.js"></script>
    <script src="Scripts/globalize/message.min.js"></script>
    <script src="Scripts/globalize/number.min.js"></script>
    <script src="Scripts/globalize/currency.min.js"></script>
    <script src="Scripts/globalize/date.min.js"></script>
    <!-- A DevExtreme library -->
    <script type="text/javascript" src="Scripts/dx.all.js"></script>
</html>

<?php
