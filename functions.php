<?php
// check if item is selected and then set is as chosen value when submit is pressed
function selected ($line){
$selected = '';
if ($_POST['line'] == $line){
$selected = 'selected';
}
return $selected;
}

function print_array($array){
echo '<pre>';
    print_r($array);
    echo '</pre>';
}

// Get the right style for the right time difference
function getTimeColor($timestamp){
$delta_time = time() - strtotime($timestamp);
$days = floor($delta_time / 3600 / 24); // difference in days

if ($days > 356) {
$color = 'btn-danger';
} elseif ($days > 160) {
$color = 'btn-warning';
} else {
$color = 'btn-outline-info';
}
return $color;
}

function getDurationIncident($timestamp){
$delta_time = time() - strtotime($timestamp);
$days = floor($delta_time / 3600 / 24); // difference in days
return $days;
}

function handle_sql_errors($query, $error_message)
{
    echo '<pre>';
    echo $query;
    echo '</pre>';
    echo $error_message;
    die;
}
// Display pdo query result (old)
function displayResult($result){
foreach($result as $row)
{
$incident_id = $row->Incident_ID;
$datum = $row->Datum;
$behandelaar = $row->Behandelaar;
$id = $incident_id;
$colorCheck = getTimeColor($datum);
$duration = getDurationIncident($datum).' dagen';
echo '
<div data-toggle="collapse" id="id'.$id.'" href="#l'.$id.'" class="btn '.$colorCheck.' btn-block text-left incident" style="margin: 2px">
    <div id="header">
        <div class="row d-flex align-items-center">
            <div class="col-lg-3">'.$incident_id.'</div>
            <div class="col-lg-3">'. $datum.'</div>
            <div class="col-lg-3">'. $duration.'</div>
            <div class="col-lg-3">'. $behandelaar.'</div>
        </div>
    </div>
</div>

<div id="l'.$id.'" class="collapse incident-form">
    <div class="list-group" id="form'.$id.'">

    </div>
</div>
';
}
}
