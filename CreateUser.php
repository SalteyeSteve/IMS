<?php
/* Include config file */
require_once 'config.php';

//controleer of de user een admin is
if ((isset($_SESSION['user']) && isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == 1) == false)
{
    //print 'Welkom ' . $_SESSION['user'] . "<br /><br />";
    //geen admin = geen toegang tot Creat(/update/delete)User pagina
    /* Redirect to login page */
    header('Location: http://localhost/IMS/'); // deze location moet nog veranderd worden in de indexpagina van Vlad
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    // controleer of er een user_id bekend is (in adres balk)
    if (isset($_GET['User_ID']))
    {
        $sql = "SELECT User_ID, UserName, IsAdmin FROM User WHERE User_ID = ?";

        $stmt = $link->prepare($sql);
        $stmt->bind_Param("i", $_GET['User_ID']);

        //is de $sql query gelukt of niet
        if ($stmt->execute() == false)
        {
            //Query mislukt
            print 'Oops er is iets misgegaan.';
            exit;
        }

        //Data ophalen
        $result = $stmt->get_result();

        //de ingevoerde username bestaat niet er worden dus 0 records gevonden
        if ($result->num_rows === 0)
        {
            print 'Geen gebruikers gevonden.';
            exit;
        }

        $user = $result->fetch_assoc();
    }
    elseif (isset($_GET['Delete_ID']))
    {
        $sql = "DELETE FROM User WHERE User_ID = ?";

        $stmt = $link->prepare($sql);
        $stmt->bind_Param("i", $_GET['Delete_ID']);

        //is de $sql query gelukt of niet
        if ($stmt->execute() == false)
        {
            //Query mislukt
            print 'Oops, er is iets misgegaan.';
            exit;
        }
        header('Location: UserOverzicht.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $username = $_POST['UserName'];
    $pwd = $_POST['Password'];
    $PwdFouten = "";

    //in deze sring lengte if-statement controleren we server side of er een username is gezet/ingevuld
    if (strlen($username) === 0)
    {
        $GeenUsername = true;
    }

    //in deze string lengte if-statement controleren we server side of het password aan de eis voldoet
    if (strlen($pwd) <= 8)
    {
        $GeenPassword = true;
        $PwdFouten = "Minstens 8 karakters";
    }

    //in de onderstaande 4 preg_match if-statements controleren we server side of het password aan die 4 eisen voldoet
    if (preg_match("([!,%,&,@,#,$,^,*,?,_,~])", $pwd) === 0)
    {
        $GeenPassword = true;
        $PwdFouten = $PwdFouten . "<br>" . "Uw invoer dient minimaal 1 speciaal teken te bevaten";
    }

    if (preg_match("([a-z])", $pwd) === 0)
    {
        $GeenPassword = true;
        $PwdFouten = $PwdFouten . "<br>" . "Uw invoer dient minimaal 1 kleine letter";
    }

    if (preg_match("([A-Z])", $pwd) === 0)
    {
        $GeenPassword = true;
        $PwdFouten = $PwdFouten . "<br>" . "Uw invoer dient minimaal 1 hoofdletter";
    }

    if (preg_match("([0-9])", $pwd) === 0)
    {
        $GeenPassword = true;
        $PwdFouten = $PwdFouten . "<br>" . "Uw invoer dient minimaal 1 getal";
    }


    if (!isset($GeenUsername) && !isset($GeenPassword))
    {
        //als er een user_id bekend is komen we in deze edit/update functie en vullen we dat bij behorende username alvast in
        if (isset($_POST['User_ID']))
        {
            $isadmin = isset($_POST['IsAdmin']) ? true : false;

            //als er geen nieuw wachtwoord wordt ingesteld dan blijft het oude wachtwoord in de database staan 
            if ($_POST['Password'] !== 'N1#t het wachtwoord')
            {
                $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);
            }

            $sql = "UPDATE User SET UserName = ?, IsAdmin = ?";
            //als er wel een nieuw wachtwoord wordt ingesteld dan wordt de passwordhash geupdate
            if (isset($password))
            {
                $sql = $sql . ", PasswordHash = ?";
            }
            $sql = $sql . " WHERE User_ID = ?";

            $stmt = $link->prepare($sql);

            //als er wel een nieuw wachtwoord wordt ingesteld dan wordt de passwordhash geupdate
            if (isset($password))
            {
                $stmt->bind_param("sisi", $username, $isadmin, $password, $_POST['User_ID']);
            }
            else //als er geen nieuw wachtwoord wordt ingesteld
            {
                $stmt->bind_param("sii", $username, $isadmin, $_POST['User_ID']);
            }

            print($sql);

            //is de $sql query voor het updaten gelukt of niet
            if ($stmt->execute() === true)
            {
                //Query gelukt
                header('Location: UserOverzicht.php');
            }
            else
            {
                print $link->error;
            }
        }
        else // als er geen user_id bekend is komen we in deze create user functie
        {
            $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);

            $sql = "INSERT INTO User (UserName, PasswordHash) Values (?, ?)";

            $stmt = $link->prepare($sql);
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute())
            {
                //Query gelukt
                header('Location: UserOverzicht.php');
            }
            else
            {
                print $link->error;
            }
        }
        $link->close();
    }
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
        <form id="formuser" method="post" style="height:100%">
            <div class="container" style="height:95%">
                <div class="row justify-content-around" style="height:100%">
                    <div class="col-4" style="margin: auto">
                        <h2 align="center" style="color: #17a2b8">Nieuwe gebruiker aanmaken</h2><br>
                        <label>Gebruikersnaam: </label><br>
                        <?php if (isset($user)) print '<input name="User_ID" type="hidden" value="'.$user["User_ID"].'" />'; ?>
                        <input class="form-control" type="text" name="UserName" value="<?php if (isset($user)) print $user['UserName']; ?>" required/><br><br>
                        <?php if (isset($GeenUsername)) print '<span style="color:red">Voer alstublieft een username in.</span><br>'; ?>
                        <label>Wachtwoord: </label><br>
                        <input class="form-control" type="password" name="Password" value="<?php if (isset($user)) print 'N1#t het wachtwoord' ?>" required/><br><br>
                        <?php if (isset($GeenPassword)) print '<span style="color:red">' . $PwdFouten . '</span><br>'; ?>
                        <label>Is Admin?</label>
                        <input type="checkbox" name="IsAdmin" <?php if (isset($user) && $user['IsAdmin'] == 1) print 'checked="true"' ?>/><br>
                        <input class="btn btn-outline-info btn-custom" type="submit" value="Opslaan">
                        <a id="canceluser" class="btn btn-outline-warning" href="#" role="button">Annuleren</a>
                    </div>
                </div>
            </div>
        </form>
    </body>

    <script>
        //deze scripts zijn hier nodig om Vlad z'n scripts to omzeilen (anders werken de buttons niet goed en komt de navbar onder aan mijn pagina's)
        $(document).ready(function ()
        {
            //Start password controle
            $(":password").PassRequirements();
            
            //Knop opslaan
            $("#formuser").submit(function (event) {
                event.preventDefault();

                if ($(':password').data('password-valid') == true)
                {
                    $.ajax({
                        type: "post",
                        url: "CreateUser.php",
                        data: $("#formuser").serialize(),
                        success: function (response)
                        {
                            $(content).empty();
                            $(content).append(response);
                        }
                    });
                } else
                {
                    $(":password").focus();
                    return false;
                }
            });

            //Cancel knop
            $('#canceluser').click(function () {
                $(content).empty();
                $.ajax({
                    url: 'UserOverzicht.php',
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
</html>
