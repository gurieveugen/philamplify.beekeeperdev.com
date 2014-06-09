<?php
/**
 * Template name: Salesforce application
 */
?>
<?php get_header(); ?>

<?php
$email = isset($_GET['email']) ? $_GET['email'] : ''; 
?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="page-title">
	<div class="holder">
		<div class="center-wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
</div>
<div id="main" class="center-wrap cf">
	<article id="content" class="main-content cf">
		<form action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST" class="form-story">
			<input type=hidden name="oid" value="00D700000008Fiw">
			<input type=hidden name="retURL" value="http://philamplify.beekeeperdev.com/thank-subscribe/">

			<!--  ----------------------------------------------------------------------  -->
			<!--  NOTE: These fields are optional debugging elements. Please uncomment    -->
			<!--  these lines if you wish to test in debug mode.                          -->
			<!-- <input type="hidden" name="debug" value=1>                      
			<input type="hidden" name="debugEmail" value="tatarinfamily@gmail.com">   -->                    
			<!--  ----------------------------------------------------------------------  -->

			<label for="first_name">First Name</label><input  id="first_name" maxlength="40" name="first_name" size="20" type="text" /><br>
			<label for="last_name">Last Name</label><input  id="last_name" maxlength="80" name="last_name" size="20" type="text" /><br>
			<label for="email">Email</label><input  id="email" maxlength="80" name="email" size="20" type="text" value="<?php echo $email; ?>" /><br>
			<label for="company">Company</label><input  id="company" maxlength="40" name="company" size="20" type="text" /><br>
			<label for="city">City</label><input  id="city" maxlength="40" name="city" size="20" type="text" /><br>
			<label for="state">State/Province</label><input  id="state" maxlength="20" name="state" size="20" type="text" /><br><br>
			<input type="submit" name="submit" class="btn-dark-green" style="margin: 0">
		</form>
		<?php the_content(); ?>
	</article>
	<?php get_sidebar(); ?>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>
