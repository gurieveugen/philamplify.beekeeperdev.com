<?php $sq = get_search_query() ? get_search_query() : ''; ?>
<form action="<?php bloginfo('url'); ?>" class="search-form-tablet cf">
	<input type="text" name="s" placeholder="Search" value="<?php echo $sq; ?>">
	<input type="submit" value="Search">
</form>