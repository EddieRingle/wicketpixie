<!-- myprofiles -->
<?php $sources= new SourceAdmin; ?>
<div class="widget">
	<h3>My Profiles</h3>
	<ul id="myprofiles">
		<?php foreach( $sources->legend_types() as $legend ) { ?>
			<li><img src="<?php echo $legend->favicon; ?>" alt="<?php echo $legend->title; ?>" /><a href="<?php echo $legend->profile_url; ?>"><?php echo $legend->title; ?></a></li>
		<?php } ?>
	</ul>
</div>
<!-- /myprofiles -->