<?php

error_reporting(error_reporting() & ~E_NOTICE);
$hey = null;
include "config.php";
$getDate = date("Y-m-d H:i:s");

//echo $getDate;
$incident_id = $_POST['Incident_ID'];
$incident_collaborator = mysqli_real_escape_string($mysqli, $_POST['Baliemedewerker']);
$incident_treated_by = mysqli_real_escape_string($mysqli, $_POST['Behandelaar']);
$incident_description = mysqli_real_escape_string($mysqli, $_POST['Omschrijving']);
$incident_action = mysqli_real_escape_string($mysqli, $_POST['Actie']);
$incident_follow_up_action = mysqli_real_escape_string($mysqli, $_POST['VervolgActie']);
$incident_executed_work = mysqli_real_escape_string($mysqli, $_POST['UitgevoerdeWerkzaamheden']);
$incident_appointments = mysqli_real_escape_string($mysqli, $_POST['Afspraken']);
$incident_type = mysqli_real_escape_string($mysqli, $_POST['SoortIncident']);

$incident_ready_for_closing = 0;
$incident_closed = 0;

if (isset($_POST['GereedVoorSluiten1']) && $_POST['GereedVoorSluiten1'] == 'on')
    $incident_ready_for_closing = 1;
if (isset($_POST['GereedVoorSluiten2']) && $_POST['GereedVoorSluiten2'] == 'on')
    $incident_closed = 1;

$klant_id_nummer = mysqli_real_escape_string($mysqli, $_POST["ID_Nummer"]);
$klant_name = mysqli_real_escape_string($mysqli, $_POST["Naam"]);
$klant_phone = mysqli_real_escape_string($mysqli, $_POST["Telefoon"]);
$klant_email = mysqli_real_escape_string($mysqli, $_POST["Email"]);
$klant_customer_type = mysqli_real_escape_string($mysqli, $_POST["TypeKlant"]);

//deze regels code tot aan het eerste else statement zijn nodig voor de server side validation
$Fouten = "";

//in deze sring lengte if-statement controleren we server side of de verplichte velden zijn gezet/ingevuld
if (strlen($klant_name) === 0)
{
    $Fouten = $Fouten . "Vul een klantnaam in.";
}
//if (strlen($klant_phone) === 0)
//{
//    $Fouten = $Fouten . "<br>" . "Vul het telefoonnummer van de klant in.";
//}
//if (strlen($klant_email) === 0)
//{
//    $Fouten = $Fouten . "<br>" . "Vul het email van de klant in.";
//}
if (strlen($klant_customer_type) === 0)
{
    $Fouten = $Fouten . "<br>" . "Vul het type klant in.";
}
if (strlen($incident_collaborator) === 0)
{
    $Fouten = $Fouten . "<br>" . "Vul de baliemedewerker in.";
}
if (strlen($incident_treated_by) === 0)
{
    $Fouten = $Fouten . "<br>" . "Vul de behandelaar in.";
}
if (strlen($incident_description) === 0)
{
    $Fouten = $Fouten . "<br>" . "Vul het omschrijving veld in.";
}
if (strlen($incident_action) === 0)
{
    $Fouten = $Fouten . "<br>" . " Vul het actie veld in.";
}
if (strlen($incident_type) === 0)
{
    $Fouten = $Fouten . "<br>" . "Vul het soort incident in.";
}

//dit if statement is nodig om via de pnotify plugin de server side validation te kunnen tonen aan de gebruiker
if (strlen($Fouten) > 0)
{
    ob_clean();
    http_response_code(400);
    print $Fouten;
    exit();
}
else
{
    $edit_incident = $db->prepare('update Incident
    set 
    Datum = "' . $getDate . '",
    Baliemedewerker = :balie,
    Behandelaar = :bahandelaar,
    Omschrijving = :omschrijving,
    Actie = :actie,
    VervolgActie = :vactie,
    UitgevoerdeWerkzaamheden = :uwerkzaamheden,
    Afspraken = :afspraken,
    SoortIncident_ID = :incidentid,
    GereedVoorSluiten = :gvoorsluiten,
    IncidentGesloten = :incidentgesloten,
    SluitDatum = :SluitDatum
    where Incident_ID = :id
');

    $edit_customer = $db->prepare('update Klant
    set
    Naam = :naam,
    Telefoon = :tel,
    Email = :email,
    Type_ID = :type
    where  Klant_ID = ( select Klant_ID from Incident where Incident_ID = :id) 
');
    if ($klant_customer_type !== 3)
    {
        $edit_customer_id = $db->prepare('   
   
     INSERT INTO StudentDocentNummer (
    Klant_ID,
    ID_Nummer
    )
    VALUES(( select Klant_ID from Incident where Incident_ID = :id), :idnum)
    
    ON DUPLICATE KEY
    UPDATE
    ID_Nummer = :idnum
');
    }

    if ($incident_ready_for_closing == 1 && $incident_closed == 1)
    {
        $edit_incident->bindParam(':SluitDatum', $getDate);
    }

    else
    {
        $edit_incident->bindParam(':SluitDatum', $hey);
    }


    $edit_incident->bindParam(':balie', $incident_collaborator);
    $edit_incident->bindParam(':bahandelaar', $incident_treated_by);
    $edit_incident->bindParam(':omschrijving', $incident_description);
    $edit_incident->bindParam(':actie', $incident_action);
    $edit_incident->bindParam(':vactie', $incident_follow_up_action);
    $edit_incident->bindParam(':uwerkzaamheden', $incident_executed_work);
    $edit_incident->bindParam(':afspraken', $incident_appointments);
    $edit_incident->bindParam(':incidentid', $incident_type);
    $edit_incident->bindParam(':gvoorsluiten', $incident_ready_for_closing);
    $edit_incident->bindParam(':incidentgesloten', $incident_closed);
    $edit_incident->bindParam(':id', $incident_id);

    $edit_customer->bindParam(':id', $incident_id);
    $edit_customer->bindParam(':naam', $klant_name);
    $edit_customer->bindParam(':tel', $klant_phone);
    $edit_customer->bindParam(':email', $klant_email);
    $edit_customer->bindParam(':type', $klant_customer_type);

    $edit_customer_id->bindParam(':idnum', $klant_id_nummer);
    $edit_customer_id->bindParam(':id', $incident_id);

    $edit_incident->execute();
    $edit_customer->execute();
    $edit_customer_id->execute();
}