<html>
    <body>
        <?php
            if (session_status() == PHP_SESSION_NONE)
            {
                session_start();
            }
            include("yhteys.php");
            $user = $_POST["user"];
            $unCryptPass = $_POST["pass"];
            $pass = md5($unCryptPass);

            $hae = $yhteys->prepare("SELECT id, name FROM $db.users WHERE username = :user AND password = :pass");
            $hae->execute(array(":user"=>$user, ":pass"=>$pass));
            $haku = $hae->fetch(PDO::FETCH_ASSOC);

            if($haku['id']<>'')
            {
                $_SESSION["kt"] = $haku['id'];
                ?>
                <script>window.location.href="etusivu.php";</script>
                <?php
            }
            else
            {
                ?>
                <script>window.location.href="index.php"</script>
                <?php
            }

        ?>
    </body>
</html>