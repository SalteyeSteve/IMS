<?php
include "../config.php";
$sql = 'select i.*, k.Naam, s.SoortIncident, t.TypeKlant from Incident i
        left join Klant k 
        on i.Klant_ID = k.Klant_ID
        left join SoortIncident s
        on i.SoortIncident_ID = s.SoortIncident_ID
        left join TypeKlant t
        on k.Type_ID = t.Type_ID
        ';

try{
    $query = $db->prepare($sql);
    $query->execute();

$dataRapport["data"] = array();

while($row = $query->fetch(PDO::FETCH_OBJ)) {
    $datum = $row->Datum;
    $delta_time = time() - strtotime($datum);
    $days = floor($delta_time / 3600 / 24); // difference in days
    $duration = getDurationIncident($datum) . ' dagen';
    $dataRapport["data"][] = array(
        "DT_RowId" => "id".$row->Incident_ID,
        "Incident_ID" => $row->Incident_ID,
        "Datum" => $row->Datum,
        "duration" => $days,
        "Naam" =>$row->Naam,
        "Baliemedewerker" => $row->Baliemedewerker,
        "Behandelaar" => $row->Behandelaar,
        "SluitDatum" => $row->SluitDatum,
        "IncidentGesloten" => $row->IncidentGesloten,
        "Klant_ID" => $row->Klant_ID,
        "SoortIncident_ID" => $row->SoortIncident_ID
    );
}

echo json_encode($dataRapport);
}
catch(PDOException $e)
{
    handle_sql_errors($sql, $e->getMessage());
}