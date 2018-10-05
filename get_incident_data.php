<?php

// Gets data for DataTables plugin, page "Overzicht lopende incidenten"
include "config.php";
$query = $db->prepare('select Incident_ID, date_format(Datum, "%Y-%m-%d") as Datum, k.Naam from Incident i
                                 inner join Klant k 
                                 on i.Klant_ID = k.Klant_ID WHERE SluitDatum IS NULL');

$query->execute();

$dataIncident["data"] = array();

while ($row = $query->fetch(PDO::FETCH_OBJ))
{
    $datum = $row->Datum;
    $delta_time = time() - strtotime($datum);
    $days = floor($delta_time / 3600 / 24); // difference in days
    $colorCheck = getTimeColor($datum);
    $duration = getDurationIncident($datum) . ' dagen';
    $dataIncident["data"][] = array(
        "DT_RowId" => "id" . $row->Incident_ID,
        "incidentId" => $row->Incident_ID,
        "datum" => $row->Datum,
        "duration" => $days,
        "naam" => $row->Naam,
        "days" => $days,
    );
}
print json_encode($dataIncident);
