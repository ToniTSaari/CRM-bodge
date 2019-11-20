<?php
    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }
    include("yhteys.php");
    $kt = $_SESSION["kt"];

    if($_POST["toiminto"] == "addComp")
    {
        $nimi = $_POST['compName'];
        $yID = $_POST['yTunnus'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        if ($yID != '' and $nimi != '')
        {
            $haku = $yhteys->prepare("SELECT id FROM $db.companies WHERE yID = :yID");
            $haku->execute(array(':yID'=>$yID));
            $tallenna = $haku->fetch(PDO::FETCH_ASSOC);

            if($tallenna['id']=='')
            {
                $add = $yhteys->prepare("INSERT INTO $db.companies(yID, name, phone, email) values (?,?,?,?)");
                $add->execute(array($yID, $nimi,$phone,$email));
                $rivi = $yhteys->lastInsertId();
                $palautus['jono'] = "<span id=\"co$rivi\">".$yritysid."</span>";
            }
        }
    }

    if ($_POST["toiminto"] == "upComp")
    {
        $nimi = $_POST['compName'];
        $yID = $_POST['yTunnus'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        if ($yID != '' and $nimi != '')
        {
            $haku = $yhteys->prepare("SELECT yID FROM $db.companies WHERE yID = :yID");
            $haku->execute(array(':yID'=>$yID));
            $tallenna = $haku->fetch(PDO::FETCH_ASSOC);

            if ($tallenna['yID']==$yID)
            {
                $update = $yhteys->prepare("UPDATE $db.companies set name = :name, phone = :phone, email = :email where yID = :yID");
                $update->execute(array(':name'=>$nimi,':phone'=>$phone, ':email'=>$email, ':yID'=>$yID));
                $palautus['yritys'] = $yritysid;
            }
        }
    }

    if($_POST["toiminto"] == "delComp")
    {
        $ID = $_POST['yritysid'];

        $del = $yhteys->prepare("DELETE FROM $db.companies where id = :id");
        $del->execute(array(':id'=>$ID));

        $del = $yhteys->prepare("DELETE FROM $db.contacts where companyid = :id");
        $del->execute(array(':id'=>$ID));

        $del = $yhteys->prepare("DELETE FROM $db.events where companyid = :id");
        $del->execute(array(':id'=>$ID));

        $del = $yhteys->prepare("DELETE FROM $db.addressess where companyid = :id");
        $del->execute(array(':id'=>$ID));

        $palautus['yritys'] = $yritysid;
    }

    if($_POST["toiminto"] == "delCont")
    {
        $ID = $_POST['yritysid'];
        $nimi = $_POST['nimi'];

        $del = $yhteys->prepare("DELETE FROM $db.contacts where companyid = :id and name = :nimi");
        $del->execute(array(':id'=>$ID, ':nimi'=>$nimi));

        $palautus['yritys'] = $ID;
    }

    if($_POST["toiminto"] == "addCont")
    {
        $ID = $_POST['yritysid'];
        $nimi = $_POST['contName'];
        $title = $_POST['title'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $haku = $yhteys->prepare("SELECT name FROM $db.contacts WHERE companyid = :yID");
        $haku->execute(array(':yID'=>$ID));
        $tallenna = $haku->fetch(PDO::FETCH_ASSOC);

        if($tallenna['name']!=$nimi)
        {
            $add = $yhteys->prepare("INSERT INTO $db.contacts(companyid, title, name, phone, email) values (?,?,?,?,?)");
            $add->execute(array($ID,$title,$nimi,$phone,$email));

            //$jono="<div class=\"yhtiedot\">".$name."<br />".$title."<br />".$email."<br />".$phone."</div>";
        }
    }

    if($_POST["toiminto"] == "addEvent")
    {
        $ID = $_POST['yritysid'];
        $user = $_POST['user'];
        $event = $_POST['event'];

        $haku = $yhteys->prepare("SELECT id FROM $db.companies WHERE id = :yID");
        $haku->execute(array(':yID'=>$ID));
        $tallenna = $haku->fetch(PDO::FETCH_ASSOC);

        if ($tallenna['id']==$ID)
        {
            $add = $yhteys->prepare("INSERT INTO $db.events(companyid, event, saver) values (?,?,?)");
            $add->execute(array($ID,$event,$user));
        }

    }

    if ($_POST["toiminto"] == "upCont")
    {
        $nimi = $_POST['contName'];
        $yID = $_POST['yritysid'];
        $conID = $_POST['conID'];
        $title = $_POST['title'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        if ($yID != '')
        {
            $haku = $yhteys->prepare("SELECT companyid, name FROM $db.contacts WHERE companyid = :yID");
            $haku->execute(array(':yID'=>$yID));
            $tallenna = $haku->fetch(PDO::FETCH_ASSOC);

            if ($tallenna['companyid']==$yID && $tallenna['name']==$nimi)
            {
                $update = $yhteys->prepare("UPDATE $db.contacts set title = :title, phone = :phone, email = :email where name = :name and companyid = :yID");
                $update->execute(array(':title'=>$title, ':phone'=>$phone, ':email'=>$email, ':name'=>$nimi, ':yID'=>$yID));
                $palautus['yritys'] = $yritysid;
            }
        }
    }

    if($_POST["toiminto"] == "haetiedot")
    {
        $yritysid = $_POST['yritysid'];

        $haku = $yhteys->prepare("SELECT phone, email, yID, name, id FROM $db.companies WHERE id = :yritys");
        $haku->execute(array(':yritys'=>$yritysid));
        $kanta = $haku->fetch(PDO::FETCH_ASSOC);
        $tiedot = "<input type=\"button\" class=\"button\" id=\"buttonUpComp\" value=\"Päivitä Yrityksen tiedot\" /><hr>
                    <div class=\"tiedot\">".$kanta['yID']."<br />".$kanta['email']."<br />".$kanta['phone']."</div><hr>
                    <input type=\"button\" class=\"button\" id=\"delComp\" value=\"Poista Yrityksen tiedot\" />
                    <input type=\"hidden\" id=\"knownName".$kanta['id']."\" value=\"".$kanta['name']."\">
                    <input type=\"hidden\" id=\"knownYID".$kanta['id']."\" value=\"".$kanta['yID']."\">
                    <input type=\"hidden\" id=\"knownEmail".$kanta['id']."\" value=\"".$kanta['email']."\">
                    <input type=\"hidden\" id=\"knownPhone".$kanta['id']."\" value=\"".$kanta['phone']."\">";

        $haku = $yhteys->prepare("SELECT id, title, name, phone, email FROM $db.contacts WHERE companyid = :yritys");
        $haku->execute(array(':yritys'=>$yritysid));
        
        $yhteystiedot = "<input type=\"button\" class=\"button\" id=\"addContact\" value=\"Lisää Yhteyshenkilö\" /><hr>";
        $bool = false;
        while($kanta2 = $haku->fetch(PDO::FETCH_ASSOC))
        {
            $yhteystiedot.="<div class=\"yhtiedot\">".$kanta2['name']."<br />".$kanta2['title']."<br />".$kanta2['email']."<br />".$kanta2['phone']."
                            <input type=\"button\" style=\"float:left\" class=\"button\" id=\"buttonUpCont".$kanta2['id']."\" value=\"Päivitä\" />
                            <input type=\"button\" style=\"float:right\" class=\"button\" id=\"buttonDelCont".$kanta2['id']."\" value=\"Poista\" /></div>
                            <input type=\"hidden\" id=\"knownContName".$kanta2['id']."\" value=\"".$kanta2['name']."\">
                            <input type=\"hidden\" id=\"knownContTitle".$kanta2['id']."\" value=\"".$kanta2['title']."\">
                            <input type=\"hidden\" id=\"knownContEmail".$kanta2['id']."\" value=\"".$kanta2['email']."\">
                            <input type=\"hidden\" id=\"knownContPhone".$kanta2['id']."\" value=\"".$kanta2['phone']."\">
                            <input type=\"hidden\" id=\"knownContId".$kanta2['id']."\" value=\"".$kanta2['id']."\">";
            if($kanta2 != ''){$bool=true;}
        }

        $haku = $yhteys->prepare("SELECT e.id, e.event, DATE_FORMAT(e.time, '%d.%m.%Y %H:%i')
            as time, e.saver, u.name FROM $db.events e LEFT JOIN $db.users u ON u.id = e.saver WHERE e.companyid = :yritys");

        $haku->execute(array(':yritys'=>$yritysid));
        $tapaus = "<input type=\"button\" class=\"button\" id=\"addEvent\" value=\"Lisää Tapahtuma\" /><hr>";
        while($kanta3 = $haku->fetch(PDO::FETCH_ASSOC))
        {
            $tapaus.="<div class=\"tapaus\">".$kanta3['event']."<br />".$kanta3['name']." ".$kanta3['time']."</div>";
        }

        $palautus['yritys'] = $yritysid;
        $palautus['tiedot'] = $tiedot;
        $palautus['yhteystiedot'] = $yhteystiedot;
        $palautus['tapaus'] = $tapaus;
        //$palautus['jono'] = $jono;

        echo json_encode($palautus);
        exit;
    }