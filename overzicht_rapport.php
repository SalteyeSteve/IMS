<div id="sticky2" class="sticky-top">
    <table id="rapportageForm">
        <tbody>
        <tr>
<!--            <td>-->
            Datum: <input type="text" name="datum" id="datum" placeholder="van jjjj-mm-dd">&emsp;<input type="text" name="einddatum" id="einddatum" placeholder="tot jjjj-mm-dd">&emsp;
            Incident:
            <select name="incident" id="incident">
                <option value="">alles</option>
                <option value="0">open</option>
                <option value="1">gesloten</option>
            </select>
            Soort incident:
            <select name="soortincident" id="soortincident">
                <option value="">alles</option>
                <option value="0">software</option>
                <option value="1">hardware</option>
                <option value="2">advies</option>
                <option value="3">verzoek</option>
            </select>
            Type klant:
            <select name="typeklant" id="typeklant">
                <option value="">alles</option>
                <option value="0">student</option>
                <option value="1">docent</option>
                <option value="2">extern</option>
            </select>
            &emsp;Baliemedewerker: <input type="text" name="baliemedewerker" id="baliemedewerker">
            &emsp;Behandelaar: <input type="text" name="behandelaar" id="behandelaar">
            <button id="submit" class="w3-btn w3-white w3-border">Submit</button>
<!--            </td>-->
<div class="demo-container">
        <div id="chart-demo">
            <div id="chart"></div>
        </div>
    </div>
        </tr>
        </tbody>
    </table>
    <table class="table" id="testData">
        <thead class="table-bordered headerIncident">
        <tr>
            <th width="10%" class="btn-warning">Incident ID</th>
            <th width="30%" class="btn-warning">Datum Aanmelding</th>
            <th width="30%" class="btn-warning">Looptijd incident in dagen</th>
            <th width="30%" class="btn-warning">Naam klant</th>

            <th width="0" class="btn-warning">Baliemedewerker</th>
            <th width="0" class="btn-warning">Behandelaar</th>
            <th width="0" class="btn-warning">SluitDatum</th>
            <th width="0" class="btn-warning">IncidentGesloten</th>
            <th width="0" class="btn-warning">Klant_ID</th>
            <th width="0" class="btn-warning">SoortIncident_ID</th>
        </tr>
        </thead>
    </table>
</div>