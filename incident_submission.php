<?php

include "config.php";
$getDate = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

    // Klant variables
    $klant_name = mysqli_real_escape_string($mysqli, $_POST["Naam"]);
    $klant_phone = mysqli_real_escape_string($mysqli, $_POST["Telefoon"]);
    $klant_email = mysqli_real_escape_string($mysqli, $_POST["Email"]);
    $klant_customer_type = mysqli_real_escape_string($mysqli, $_POST["TypeKlant"]);
    $klant_id_nummer = mysqli_real_escape_string($mysqli, $_POST["ID_Nummer"]);

    // Incident variables
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

//deze regels code tot aan het eerste else statement zijn nodig voor de server side validation
    $Fouten = "";

    //in deze string lengte if-statement controleren we server side of de verplichte velden zijn gezet/ingevuld
    if (strlen($klant_name) === 0)
    {
        $Fouten = $Fouten . "Vul een klantnaam in.";
    }
//    if (strlen($klant_phone) === 0)
//    {
//        $Fouten = $Fouten . "<br>" . "Vul het telefoonnummer van de klant in.";
//    }
//    if (strlen($klant_email) === 0)
//    {
//        $Fouten = $Fouten . "<br>" . "Vul het email van de klant in.";
//    }
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

        $klant_search = $db->prepare('select Klant_ID from Klant
     where (Naam = "' . $klant_name . '" and Telefoon = "' . $klant_phone . '" and Email = "' . $klant_email . '")');
        $result = $klant_search->execute();

        if ($row = $klant_search->fetch(PDO::FETCH_OBJ))
        {

            //Return Klant_ID if client exists
            $client_id = $row->Klant_ID;
        }
        else
        {
            // Insert new cient
            $insert_klant = $mysqli->prepare('INSERT INTO Klant(
            Naam,
            Telefoon,
            Email,
            Type_ID
            )
            VALUES(
            ?,?,?,?)');
            $insert_klant->bind_param('sssi', $klant_name, $klant_phone, $klant_email, $klant_customer_type);
            $insert_klant->execute();
            $client_id = $insert_klant->insert_id;

            if ($klant_customer_type !== 3)
            {

                $insert_id_number = $mysqli->prepare('INSERT INTO StudentDocentNummer(
                Klant_ID,
                ID_Nummer
                )
                VALUES(
                ?,?)');
                $insert_id_number->bind_param('ii', $client_id, $klant_id_nummer);
                $insert_id_number->execute();
            }
        }


        $insert_incident = $mysqli->prepare('INSERT INTO Incident(
        Datum,
        Baliemedewerker,
        Behandelaar,
        Omschrijving,
        Actie,
        VervolgActie,
        UitgevoerdeWerkzaamheden,
        Afspraken,
        SoortIncident_ID,
        GereedVoorSluiten,
        IncidentGesloten, 
        Klant_ID,
        SluitDatum
        )  
        VALUES(
        ?,?,?,?,?,?,?,?,?,?,?,?,?
        )');

        if ($incident_ready_for_closing == 1 && $incident_closed == 1)
        {
            $SluitDatum = $getDate;
        }
        else
        {
            $SluitDatum = null;
        }

        $insert_incident->bind_param('ssssssssiiiis', $getDate, $incident_collaborator, $incident_treated_by, $incident_description, $incident_action, $incident_follow_up_action, $incident_executed_work, $incident_appointments, $incident_type, $incident_ready_for_closing, $incident_closed, $client_id, $SluitDatum);
        $insert_incident->execute();
    }
}
?>


