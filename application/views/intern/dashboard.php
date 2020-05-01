<head>



<script type="text/javascript">

$(document).ready(function(){




});




</script>

</head>





<div class="row">

	<div class="col-lg-12">

		<h1 class="page-header">Velkommen til GAHK Intern</h1>

		<br /> <br />

	</div>

</div>


<div class="row">
	
	<div class="col-lg-6">
			Du kan ændre dit password <a href="http://gahk.dk/nyintern/admin/changepassword">her</a>.<br />
			<br />
			Finder du fejl eller har foreslag til ændringer? <br />
			Send netværksgruppen en mail: <a href="mailto:it@gahk.dk">it@gahk.dk</a><br />
			<br />
		<?php
				if (insideGAHK()):
		?>
			Koden til det trådløse netværk er: IAlleDeRigerOgLande1908.<br />
			Du kan læse mere om netværket <a href="http://gahk.dk/wiki/index.php?title=WiFi">her</a>.<br />
			Du kan tilføje MAC-adresser <a href="http://gahk.dk/nyintern/mydata">her</a>.<br />
			<br />
		<?
			endif;
		?>

		<b>Alumnelisten til Android og iPhone</b> <br>
		<a href="https://gahk.dk/public/misc/app" target="new"><img src="https://www.gahk.dk/public/misc/app/simple-logo.png" style="float: left;width: 42px;margin-right: 5px;border-radius: 8px;"></a>
		Alumnelisten kan hentes som app <a href="https://gahk.dk/public/misc/app" target="new">her</a>. <br>
		Instruktioner om hvordan app'en installeres findes i menuen. <br>
		<br>
		<b>GAHK Intern som Android app</b><br>
		<a href='https://play.google.com/store/apps/details?id=dk.gahk.gahkintern&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img width="150" alt='Nu på Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/da_badge_web_generic.png'/></a> <br>
		<br>
		<b>Alumnelisten som Android app</b><br>
		<a href='https://play.google.com/store/apps/details?id=dk.gahk.alumnelisten&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img width="150" alt='Nu på Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/da_badge_web_generic.png'/></a>

		<!--<img class="img-responsive" src="<?=base_url("/public/image/intern/netgrp-google.png")?>" />
		<h2>Bas! Netværksgruppen arbejder!</h2>-->

	</div>

	<div class="col-lg-6">
		
		<b>Begivenheder på GAHK</b><br>
		<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=2&amp;bgcolor=%23ffffff&amp;src=gahkkalender%40gmail.com&amp;src=mnic13suhuvarq6ffitg2j30m4%40group.calendar.google.com&amp;color=%23BE6D00&amp;ctz=Europe%2FCopenhagen" style=" border:solid 1px #777 " width="250" height="500" frameborder="0" scrolling="no"></iframe>
		<?php
            if(insideGAHK()):
        ?>
                <br><br>
                Tilføj noget til kalenderen:<br>
                <a href="https://www.google.com/accounts/ServiceLogin?service=cl&passive=true&nui=1&continue=http%3A%2F%2Fwww.google.com%2Fcalendar%2Frender&followup=http%3A%2F%2Fwww.google.com%2Fcalendar%2Frender">Log ind her</a><br>
                E-mail: gahkkalender<br>
                Adgangskode: nokolugter
        <?php
            endif;
        ?>

	</div>

</div>






