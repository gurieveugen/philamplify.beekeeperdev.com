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
		<!--  <input type="hidden" name="debug" value=1>                              -->
		<!--  <input type="hidden" name="debugEmail"                                  -->
		<!--  value="teamncrp@beekeepergroup.com">                                    -->
		<!--  ----------------------------------------------------------------------  -->

		<label for="salutation">Salutation</label><select  id="salutation" name="salutation"><option value="">--None--</option><option value="Mr.">Mr.</option>
		<option value="Ms.">Ms.</option>
		<option value="Mrs.">Mrs.</option>
		<option value="Dr.">Dr.</option>
		<option value="Prof.">Prof.</option>
		<option value="Rev.">Rev.</option>
		<option value="Sr.">Sr.</option>
		<option value="Fr.">Fr.</option>
		<option value="Rabbi">Rabbi</option>
		</select><br>

		<label for="first_name">First Name</label><input  id="first_name" maxlength="40" name="first_name" size="20" type="text" required /><br>

		<label for="last_name">Last Name</label><input  id="last_name" maxlength="80" name="last_name" size="20" type="text" required /><br>

		<label for="title">Title</label><input  id="title" maxlength="40" name="title" size="20" type="text" /><br>

		<label for="company">Organization</label><input  id="company" maxlength="40" name="company" size="20" type="text" required /><br>

		<label for="street">Address</label><textarea name="street"></textarea><br>

		<label for="city">City</label><input  id="city" maxlength="40" name="city" size="20" type="text" required /><br>

		<label for="state">State/Province</label><input  id="state" maxlength="20" name="state" size="20" type="text" /><br>

		<label for="zip">Zip</label><input  id="zip" maxlength="20" name="zip" size="20" type="text" /><br>

		<label for="country">Country</label><input  id="country" maxlength="40" name="country" size="20" type="text" /><br>

		<label for="email">Email</label><input  id="email" maxlength="80" name="email" size="20" type="text" required /><br>

		<label for="phone">Phone</label><input  id="phone" maxlength="40" name="phone" size="20" type="text" /><br>

		<input type="submit" name="submit" class="btn-dark-green" style="margin: 0">

		</form>
		
		<?php the_content(); ?>
	</article>
	<?php get_sidebar(); ?>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>
