


<? if(isset($oldStyleNews)): ?>
<?
/*=============
 Info! This is a old news system which is replaced by facebook.
==============*/
?>

<div class="contentBox smallSecondBox" id="news_box">
	<div class="transparency"></div>
	<div class="content">

	<iframe id="frameBox" src="<?=site_url('news/listBox');?>" scrolling="no" style="width: 100%; border: 0px; height:400px;"></iframe>

	</div>
</div>


<? else: ?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<div class="contentBox smallSecondFacebookBox" id="news_box">
	<div class="fb-page" data-href="http://www.facebook.com/pages/GA-Hagemanns-Kollegium/299814993380395" data-width="280" data-height="500" data-hide-cover="false" data-show-facepile="false" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="http://www.facebook.com/pages/GA-Hagemanns-Kollegium/299814993380395"><a href="http://www.facebook.com/pages/GA-Hagemanns-Kollegium/299814993380395">G.A. Hagemanns Kollegium</a></blockquote></div></div>
</div>

<? endif; ?>
