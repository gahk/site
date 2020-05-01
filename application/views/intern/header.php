<?php

function menuItem($pname, $linkname, $url, $name, $condition = true) {
  if ($condition) {
    echo '<li ' . ($linkname==$pname?"class='active'":"") . '>' . anchor($url, $name) .'</a></li>';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GAHK Intern - <?=$pageheader?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('public/intern/css/bootstrap.css')?>" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="<?=base_url('public/intern/css/sb-admin.css')?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('public/intern/font-awesome/css/font-awesome.min.css')?>">
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="<?=base_url('public/intern/morris-0.4.3.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('public/intern/css/stamtree.css')?>" />
     <link rel="stylesheet" href="<?=base_url('public/intern/css/roomcheck.css')?>" />

    <!-- JavaScript -->
    <script src="<?=base_url('public/js/jquery/jquery-3.2.1.min.js')?>"></script>
	<!--<script src="<?=base_url('public/js/jquery/jquery-1.8.3.min.js')?>"></script>-->
    <!--<script src="<?=base_url('public/intern/js/jquery-1.10.2.js')?>"></script>-->
    <script src="<?=base_url('public/intern/js/bootstrap.js')?>"></script>
    <script src="<?=base_url('public/intern/d3.v3.min.js')?>"></script>

    <!-- Page Specific Plugins -->
    <script src="<?=base_url('public/intern/raphael-min.js')?>"></script>
    <script src="<?=base_url('public/intern/morris-0.4.3.min.js')?>"></script>
    <script src="<?=base_url('public/intern/js/tablesorter/jquery.tablesorter.js')?>"></script>
    <script src="<?=base_url('public/intern/js/tablesorter/tables.js')?>"></script>


    <script src="<?=base_url('public/intern/js/jquery.dataTables-1.10.16.min.js')?>"></script>


<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"></link>

  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/nyintern"><img src="<?=base_url('public/intern/logo-k90.png')?>" style="width: 30px; margin: -15px 7px 0px 0px;" alt="" />Gahk Intern</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <?
              menuItem($pagename, "logind", "nyintern/admin", "Log ind", !$username);
              menuItem($pagename, "glemtpassword", "nyintern/admin/forgotpass", "Glemt password", !$username);

              menuItem($pagename, "dashboard", "nyintern/dashboard", "Forside", $username);

              menuItem($pagename, "alumneliste", "nyintern/alumneliste", "Alumneliste", $username || insideGAHK());

              menuItem($pagename, "luknetværk", "nyintern/alumneliste/closeNetwork", "Luk netværk", $username && ($inspektion || $kokkengruppe));

              menuItem($pagename, "ansogninger", "optagelse/listAnsoegninger", "Ansøgninger", $username && $indstilling);


              menuItem($pagename, "oelkaelder", "nyintern/oelkaelder/overview", "Ølkælderen", $username);

              menuItem($pagename, "ak", "nyintern/ak", "Ak-krydser", $username);
              menuItem($pagename, "soegvaerelse", "nyintern/soegvaerelse", "Søg værelse", $username);
              menuItem($pagename, "vaerelsestjek", "nyintern/vaerelsestjek", "Værelsestjek", $username);
              menuItem($pagename, "statistik", "nyintern/statistik", "Statistik", $username);
              menuItem($pagename, "stamtree", "nyintern/stamtree", "Stamtræ", $username);

              if ($username || insideGAHK()) {
            ?>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i> Gahk Wiki <b class="caret"></b></a>
              <ul class="dropdown-menu">
              	<li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki")?>">Forside</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=Reng%C3%B8ring")?>">Rengøring</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=K%C3%B8kkenreng%C3%B8ring")?>">Køkkenrengøring</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=Printer")?>">Printere</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=WiFi")?>">WiFi</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=GAHK-Hjernen")?>">GAHK-Hjernen</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=Embedsgrupper")?>">Embedsgrupper</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=Håndbogen")?>">Håndbogen</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="<?=base_url("wiki/index.php?title=H%C3%A5ndbogen#Alumneprojektpuljen")?>">Alumneprojektpuljen</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-caret-square-o-down"></i> Andet <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a  style="padding: 3px 15px 3px 25px;" href="http://gahk.dk/intern/andet/Den_Gyldne_Bog_F2020_Praeventionen.pdf" target="_blank">Den Gyldne Bog</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="http://gahk.dk/intern/andet/GahkPakken.pdf" target="_blank">GAHK-pakken</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="http://gahk.dk/intern/andet/Fremlejekontrakt.pdf" target="_blank">Fremlejekontrakt</a></li>
				<li><a  style="padding: 3px 15px 3px 25px;" href="http://gahk.dk/intern/andet/Guide_til_fremleje_af_vaerelse.pdf" target="_blank">Fremlejeguide</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="http://gahk.dk/intern/andet/opsigelse.pdf" target="_blank">Opsigelse</a></li>
                <li><a  style="padding: 3px 15px 3px 25px;" href="http://gahk.dk/intern/classic.php" target="_blank">Intern classic</a></li>
              </ul>
            </li>
            <?
              }
            ?>
          </ul>

	<?if($username):?>
          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$fullname?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?=base_url('nyintern/mydata')?>"><i class="fa fa-wifi"></i> MAC registrering</a></li>
                <li><a href="<?=base_url('nyintern/admin/editinfo')?>"><i class="fa fa-edit"></i> Rediger oplysninger</a></li>
                <li><a href="<?=base_url('nyintern/admin/changepassword')?>"><i class="fa fa-key"></i> Skift kodeord</a></li>
                <li><a href="<?=base_url('nyintern/admin/logout')?>"><i class="fa fa-power-off"></i> Log af</a></li>
              </ul>
            </li>
          </ul>
	<?endif?>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
