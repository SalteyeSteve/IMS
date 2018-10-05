<?php
/* Include config file */
require_once 'config.php';

//geen admin = geen toegang tot UserOverzicht pagina
if ((isset($_SESSION['user']) && isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == 1) == false)
{
    //print 'Welkom ' . $_SESSION['user'] . "<br /><br />";
    /* Redirect to login page */
    header('Location: login.php');
    exit;
}
?>
<html>
    <head>
        <meta charset="uft-8">
        <title></title>
    </head>
    <body>
        <div style=" position: absolute;opacity: 0.1;width: 100%; height: 95%; background-size: 100% auto; background-repeat: no-repeat; overflow: hidden;background-image: url('http://localhost/IMSProject/images/logo%20ims2.0.png');">
            &nbsp;
        </div>
        <div class="container-fluid" style="height:95%">
            <div class="row justify-content-around" style="height:100%">
                <div class="col-3" style="margin: auto">
                    <h2 align="center" style="color: #17a2b8">Gebruikersoverzicht</h2><br>
                    <?php
                    $sql = "SELECT User_ID, UserName, IsAdmin FROM User";
                    if (!$result = $link->query($sql))
                    {
                        //Query mislukt
                    }

                    if ($result->num_rows === 0)
                    {
                        print 'Geen gebruikers gevonden';
                        exit;
                    }

                    print "<TABLE class='table table-hover'><thead><TR><TH>Username</TH><TH>Is Administrator</TH><TH>Acties</TH></TR></thead><tbody>";
                    while ($user = $result->fetch_assoc())
                    {
                        print "<TR>" . "<TD>" . $user['UserName'] . "</TD>" . "<TD>" . $user['IsAdmin'] . "</TD>" . "<TD>" . "<a href='#' onclick='EditUser(event," . $user['User_ID'] . ");'><IMG src='images/user-edit-icon.png' alt='edit user' width='40px'/></a>" . "<a href='#' onclick='DeleteUser(event," . $user['User_ID'] . ");'><IMG src='images/trash-bin-icon.png' alt='delete user' width='40px'/></a>" . "</TD>" . "</TR>";
                    }

                    print "</tbody></TABLE>";

                    $result->free();
                    $link->close();
                    ?>
                    <button id="createuser" type="button" class="btn btn-info btn-custom">Nieuwe gebruiker</button>
                </div>
            </div>
        </div>
    </body>

    <script>
        //deze scripts zijn hier nodig om Vlad z'n scripts to omzeilen (anders werken de buttons niet goed en komt de navbar onder aan mijn pagina's)
        function EditUser(event, id)
        {
            event.preventDefault();
            $(content).empty();
            $.ajax({
                url: 'CreateUser.php?User_ID=' + id,
                type: 'get',
                success: function (response) {
                    if (response == null) {
                        alert('error');
                    }
                    $(content).append(response);
                    $(content).css('padding', '0');

                    // Check the state of the navbar settings
                    if (!$('#autoscroll').hasClass('fas fa-check')) {
                        $('#sticky2').removeClass('sticky-top').css('padding-top', '8px');
                    }
                }
            });
            $(content).off('click', ".btn-warning, .btn-danger, .btn-outline-info");
        }

        function DeleteUser(event, id)
        {
            event.preventDefault();
            if (confirm('Weet u zeker dat u wilt verwijderen?'))
            {
                $(content).empty();

                $.ajax({
                    url: 'CreateUser.php?Delete_ID=' + id,
                    type: 'get',
                    success: function (response) {
                        if (response == null) {
                            alert('error');
                        }
                        $(content).append(response);
                        $(content).css('padding', '0');

                        // Check the state of the navbar settings
                        if (!$('#autoscroll').hasClass('fas fa-check')) {
                            $('#sticky2').removeClass('sticky-top').css('padding-top', '8px');
                        }
                    }
                });
                $(content).off('click', ".btn-warning, .btn-danger, .btn-outline-info");
            }
        }

        //Create user knop
        $(document).ready(function () {
            $('#createuser').click(function () {
                $(content).empty();
                $.ajax({
                    url: 'CreateUser.php',
                    type: 'get',
                    success: function (response) {
                        if (response == null) {
                            alert('error');
                        }
                        $(content).append(response);
                        $(content).css('padding', '0');

                        // Check the state of the navbar settings
                        if (!$('#autoscroll').hasClass('fas fa-check')) {
                            $('#sticky2').removeClass('sticky-top').css('padding-top', '8px');
                        }
                    }
                });
                $(content).off('click', ".btn-warning, .btn-danger, .btn-outline-info");
            });
        });
    </script>
    <!--Jetske's special script-->
    <script src="Scripts/PassRequirements.js"></script>
</html>