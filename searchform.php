<?php $sq = get_search_query() ? get_search_query() : ''; ?>
<form action="<?php bloginfo('url'); ?>" class="search-form pc-visible">
	<input type="text" placeholder="Search" name="s" value="<?php echo $sq; ?>">
	<input type="submit" value="Search">
</form>