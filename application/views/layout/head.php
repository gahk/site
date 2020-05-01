<head>
		<meta charset="utf-8">
		<title>G. A. Hagemanns Kollegium - København</title>
		<meta name="description" content="Traditionsrigt kollegie på Østerbro, København">

		<script type="text/javascript" src="<?=base_url('public/js/jquery/jquery-3.2.1.min.js')?>"></script>
		
		<script type="text/javascript" src="<?=base_url('public/js/jquery-ui/js/jquery-ui-1.12.1.min.js')?>"></script>

		<!-- Including Twitter Bootstrap -->
		<link rel="stylesheet" href="<?=base_url('public/bootstrap/css/bootstrap.min.css')?>" />
		<script type="text/javascript" src="<?=base_url('public/bootstrap/js/bootstrap.min.js')?>"></script>

		<script type="text/javascript" src="<?=base_url('public/js/gototop.js')?>"></script>

		<!-- Adding fonts -->
		<link href='https://fonts.googleapis.com/css?family=Ubuntu|Julius+Sans+One' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="<?=base_url('public/css/screen.css')?>" />


		<?if(isset($bgpic)):?>
		<style>
			html, body { 
				background: url(<?=$bgpic?>) no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
		</style>
		<?endif;?>

	</head>
