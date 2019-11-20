<?php
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	include 'yhteys.php'; 
	//include 'form.php';
	$kt = $_SESSION["kt"];
	if ($kt <> '')
	{
		$haku = $yhteys->prepare("SELECT id, name FROM $db.companies ORDER BY name");
		$haku->execute();
		$c=0;
		while ($ha = $haku->fetch(PDO::FETCH_ASSOC))
		{
			$c_id[$c] = $ha['id'];
			$c_name[$c] = $ha['name'];
			$c++;
		}

		/* $haku = $yhteys->prepare("SELECT name FROM $db.users WHERE id = :ID");
		$haku->execute(array(':ID'=>$kt));
		$userarray = $haku->fetch(PDO::FETCH_ASSOC);
		$user = $userarray['name']; */
?>

<html lang="fi">
	<head>
		<meta charset="utf-8">
		<title>CRM</title>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="css/rakenne.css" media="screen">
		<link rel="stylesheet" type="text/css" href="css/tyyli.css" media="screen">
	</head>
	<body>
		<div id="container">
			<div id="header"><span id="logo">CRM</span></div>
			<div id="user"><?php echo "<input type=\"hidden\" id=\"CRMuser\" value=\"$kt\">" ?></div>
			<div id="links">LINKIT</div>
			<div id="content">
				<div id="leftcontent">
					<?php
						for($cc=0;$cc<$c;$cc++)
						{
							echo "<span id=\"co$c_id[$cc]\">".$c_name[$cc]."</span><br />";
						}
					?>
					<hr><input type="button" class="button" id="addCompany" value="Lisää Yritys" />
					<!--<hr><input type="button" class="button" id="open" value="Tesmi" />-->
				</div>
				<div id="rightcontent">
					<div id="persu"></div>
					<div id="kontak"></div>
					<div id="tapah"></div>
				</div>
			<div id="footer"></div>
		</div>
	</body>
	<!--<div id="dialog"></div>-->
	<div id="dialogComp" title="Lisää uusi yritys">
		<form>
			Yrityksen Nimi<br />
			<input type="text" id="compName"><br />
			Y-tunnus<br />
			<input type="text" id="yTunnus"><br />
			Email<br />
			<input type="text" id="email"><br />
			Puhelin<br />
			<input type="text" id="phone"><br />
			<input type="submit" id="saveComp" class="dialog">
		</form>
	</div>
	<div id="deleteComp" title="Poista yritys">
		<form>
			Oletko varma että haluat poistaa yrityksen tiedot?<br />
			<input type="button" value="Kyllä" id="delComp" class="dialog">
		</form>
	</div>
	<div id="updateComp" title="Päivitä yritys">
		<form>
			Yrityksen Nimi<br />
			<input type="text" id="upCompName"><br />
			Y-tunnus<br />
			<input type="text" id="up-yTunnus"><br />
			Email<br />
			<input type="text" id="upEmail"><br />
			Puhelin<br />
			<input type="text" id="upPhone"><br />
			<input type="submit" id="updateComp" class="dialog">
		</form>
	</div>
	<div id="dialogCont" title="Lisää uusi yhteystieto">
		<form>
			Yhteyshenkilön Nimi<br />
			<input type="text" id="contName"><br />
			Titteli<br />
			<input type="text" id="title"><br />
			Email<br />
			<input type="text" id="contEmail"><br />
			Puhelin<br />
			<input type="text" id="contPhone"><br />
			<input type="submit" id="saveCont" class="dialog">
		</form>
	</div>
	<div id="updateCont" title="Päivitä yhteystieto">
		<form>
			Yhteyshenkilön Nimi<br />
			<input type="text" id="upContName"><br />
			Titteli<br />
			<input type="text" id="upTitle"><br />
			Email<br />
			<input type="text" id="upContEmail"><br />
			Puhelin<br />
			<input type="text" id="upContPhone"><br />
			<input type="submit" id="updateContact" class="dialog">
		</form>
	</div>
	<div id="deleteCont" title="Poista yhteystito">
		<form>
			Yhteyshenkilön Nimi<br />
			<input type="text" id="delContName"><br /><hr>
			Oletko varma että haluat poistaa yhteyshenkilön tiedot?<br />
			<input type="button" value="Kyllä" id="delCont" class="dialog">
		</form>
	</div>
	<div id="dialogEvent" title="Lisää uusi tapahtuma">
		<form>
			Tapahtuma:<br />
			<textarea id="tapaus"></textarea>
			<input type="submit" id="saveEvent" class="dialog">
		</form>
	</div>
</html>

<script>
	var mem = '';
	$(document).ready(function()
	{
		$("#dialog").dialog({autoOpen: false});
		$("#dialogComp").dialog({autoOpen: false});
		$("#updateComp").dialog({autoOpen: false});
		$("#deleteComp").dialog({autoOpen: false});
		$("#dialogCont").dialog({autoOpen: false});
		$("#updateCont").dialog({autoOpen: false});
		$("#deleteCont").dialog({autoOpen: false});
		$("#dialogEvent").dialog({autoOpen: false});

		$("#addCompany").click(function()
		{
			$("#dialogComp").dialog('open');
		})

		$(document).on('click',"#buttonUpComp",function()
		{
			var nimitemp = "#knownName" + mem;
			var tittelitemp = "#knownYID" + mem;
			var mailitemp = "#knownEmail" + mem;
			var puhelintemp = "#knownPhone" + mem;
			var nimi = $(nimitemp).val();
			var titteli = $(tittelitemp).val();
			var maili = $(mailitemp).val();
			var puhelin = $(puhelintemp).val();
			$('#upCompName').val(nimi);
			$('#up-yTunnus').val(titteli);
			$('#upEmail').val(maili);
			$('#upPhone').val(puhelin);
			$("#updateComp").dialog('open');
		})

		$(document).on('click',"#delComp",function()
		{
			$("#deleteComp").dialog('open');
		})

		$(document).on('click',"#addContact",function()
		{
			$("#dialogCont").dialog('open');
		})

		$(document).on('click','[id^=buttonUpCont]',function()
		{
			var id = $(this).attr('id');
			var nro = id.match(/\d+/);
			console.log('Painettu ' + nro);
			console.log('Painettu ' + id);
			var nimitemp = "#knownContName" + nro;
			var tittelitemp = "#knownContTitle" + nro;
			var mailitemp = "#knownContEmail" + nro;
			var puhelintemp = "#knownContPhone" + nro;
			var nimi = $(nimitemp).val();
			var titteli = $(tittelitemp).val();
			var maili = $(mailitemp).val();
			var puhelin = $(puhelintemp).val();
			$('#upContName').val(nimi);
			$('#upTitle').val(titteli);
			$('#upContEmail').val(maili);
			$('#upContPhone').val(puhelin);
			$("#updateCont").dialog('open');
		})

		$(document).on('click','[id^=buttonDelCont]',function()
		{
			var id = $(this).attr('id');
			var nro = id.match(/\d+/);
			var nimitemp = "#knownContName" + nro;
			var nimi = $(nimitemp).val();
			$('#delContName').val(nimi);
			$("#deleteCont").dialog('open');
		})

		$(document).on('click',"#addEvent",function()
		{
			$("#dialogEvent").dialog('open');
		})

		/* $("#open").click(function()
		{
			var numero = 1;
			var tiedot = "num=" + numero + "&func=form";
			$.ajax(
			{
				type: "POST",
				url: "form.php",
				data: tiedot,
				dataType: 'json',
				cache: false,
				success: function(data)
				{
					$("#dialog").html($(data.form));
				}
			})
			$("#dialog").dialog('open');
		}) */

		$("#saveComp").click(function()
		{
			var yritysnimi = $("#compName").val();
			var yTunnus = $("#yTunnus").val();
			var email = $("#email").val();
			var phone = $("#phone").val();
			var tiedot = 'compName=' + yritysnimi + '&yTunnus=' + yTunnus + '&email=' + email + '&phone=' + phone + '&toiminto=addComp';
			
			$.ajax(
			{
				type: "POST",
				url: "ajax.php",
				data: tiedot,
				dataType: 'json',
				cache: false,
				success: function(data)
				{
					$("#leftcontent").append(data.jono);
					$("#dialogComp").dialog('close');
				}
			})
		})

		$("#updateComp").click(function()
		{
			var yritysnimi = $("#upCompName").val();
			var yTunnus = $("#up-yTunnus").val();
			var email = $("#upEmail").val();
			var phone = $("#upPhone").val();
			var upTiedot = 'compName=' + yritysnimi + '&yTunnus=' + yTunnus + '&email=' + email + '&phone=' + phone + '&toiminto=upComp';

			console.log('Saatu ' + upTiedot);
			
			$.ajax(
			{
				type: "POST",
				url: "ajax.php",
				data: upTiedot,
				dataType: 'json',
				cache: false,
				success: function(data)
				{
					console.log('Päivitetty ' + data.yritys);
					$("#dialogComp").dialog('close');
				}
			})
		})

		$("#delComp").click(function()
		{
			var delTiedot = 'yritysid=' + mem + '&toiminto=delComp';

			console.log('Saatu ' + delTiedot);
			
			$.ajax(
			{
				type: "POST",
				url: "ajax.php",
				data: delTiedot,
				dataType: 'json',
				cache: false,
				success: function(data)
				{
					console.log('Poistettu ' + data.yritys);
					$("#deleteComp").dialog('close');
				}
			})
		})

		$("#saveCont").click(function()
		{
			var nimi = $("#contName").val();
			var title = $("#title").val();
			var conEmail = $("#contEmail").val();
			var conPhone = $("#contPhone").val();
			var conTiedot = 'yritysid=' + mem + '&contName=' + nimi + '&title=' + title + '&email=' + conEmail + '&phone=' + conPhone + '&toiminto=addCont';
			if(mem !== null)
			{
				console.log('Saatu ' + conTiedot);
				$.ajax(
				{
					type: "POST",
					url: "ajax.php",
					data: conTiedot,
					dataType: 'json',
					cache: false,
					success: function(data)
					{
						$("#kontak").append(data.jono);
						$("#dialogComp").dialog('close');
					}
				})
			}
		})

		$("#updateContact").click(function()
		{
			var nimi = $("#upContName").val();
			var title = $("#upTitle").val();
			var conEmail = $("#upContEmail").val();
			var conPhone = $("#upContPhone").val();
			var conID = $("#knownContId").val();
			var upConTiedot = 'yritysid=' + mem + '&conID' + conID +'&contName=' + nimi + '&title=' + title + '&email=' + conEmail + '&phone=' + conPhone + '&toiminto=upCont';

			console.log('Saatu ' + upConTiedot);
			
			$.ajax(
			{
				type: "POST",
				url: "ajax.php",
				data: upConTiedot,
				dataType: 'json',
				cache: false,
				success: function(data)
				{
					console.log('Päivitetty ' + data.yritys);
					$("#dialogComp").dialog('close');
				}
			})
		})

		$('#delCont').click(function()
		{
			var nimi = $("#delContName").val();
			var delYht = 'yritysid=' + mem + '&nimi='+ nimi + '&toiminto=delCont';

			console.log('Saatu ' + delYht);
			
			$.ajax(
			{
				type: "POST",
				url: "ajax.php",
				data: delYht,
				dataType: 'json',
				cache: false,
				success: function(data)
				{
					console.log('Poistettu ' + data.yritys);
					$("#deleteCont").dialog('close');
				}
			})
		})

		$("#saveEvent").click(function()
		{
			var user = $("#CRMuser").val();
			var event = $("#tapaus").val();
			var tapausTiedot = 'yritysid=' + mem + '&user=' + user + '&event=' + event + '&toiminto=addEvent';
			if(mem !== null)
			{
				console.log('Saatu ' + tapausTiedot);
				$.ajax(
				{
					type: "POST",
					url: "ajax.php",
					data: tapausTiedot,
					dataType: 'json',
					cache: false,
					success: function(data)
					{
						$("#dialogEvent").dialog('close');
					}
				})
			}
		})

		$('[id^=co]').on('click', 'span', function(e)
		{
			var id = $(this).attr('id');
			var nro = id.match(/\d+/);
			if(nro !== null)
			{
				console.log('Painettu' + nro);
				console.log('Painettu' + id);
				var tiedot = 'yritysid=' + nro + '&toiminto=haetiedot';
				$.ajax(
				{
					type: "POST",
					url: "ajax.php",
					data: tiedot,
					dataType: 'json',
					cache: false,
					success: function(data)
					{
						mem = data.yritys;
						console.log('Tuotu ' + data.yritys);

						$("#persu").html($(data.tiedot));
						$("#kontak").html($(data.yhteystiedot));
						$("#tapah").html($(data.tapaus));
						$('[id^=co]').css({'font-weight':'normal'});
						$('#co' + data.yritys).css({'font-weight':'bold'});
					}
				})
			}
		})
	})
</script>

<?php
	}
	else
	{
		?>
			<script>window.location.href = "index.php";</script>
		<?php 	
	}