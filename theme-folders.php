<?php
/*
Plugin Name: Theme Folders
Plugin URI: http://www.wp-plugin-dev.com
Description: Add folders to themes
Author: wp-plugin-dev.com
Version: 0.2
Author URI: http://www.wp-plugin-dev.com
*/
//apply_filters("theme_root","tr");
global $wp_theme_directories;

$theme_folders = explode(", ", get_option('design_folders'));

register_theme_directory("themes");

foreach ($theme_folders as $theme_register_dir)
{
	register_theme_directory("" . $theme_register_dir . "");
}

function remove_menus()
{
	add_theme_page(__('Theme Folders'), __('Theme Folders'), 'edit_theme_options', 'themes-in-folders', 'themes_in_folders');
}

add_action('admin_menu', 'remove_menus');

function themes_in_folders()
{
	global $wp_theme_directories;

	if (isset($_POST['design_folders']))
	{
		$folder_vars = update_option("design_folders", $_POST['design_folders']);
		$folder_vars = get_option("design_folders");

	} else
	{
		$folder_vars = 'themes, '.get_option("design_folders");
	}
	echo "<div id=\"wrap\">";
?>
<div class="wrap">
<div class="card">
<h2><span  class="dashicons dashicons-portfolio"></span> <?php _e('Theme Folders'); ?></h2>
<form method="post">
Theme Folders <input type="text" value="<?php
	echo $folder_vars;
	?>" size=50 name="design_folders" /><br><small>(divided by comma + space ", ")</small><br />
<input type="submit" class="button" />
</form><br><br>
Tools: <span class="dashicons dashicons-yes"></span> Activate theme <span class="dashicons dashicons-admin-appearance"></span> Go to customizer <span class="dashicons dashicons-hammer"></span> Edit theme <span class="dashicons dashicons-dismiss"></span> Delete theme.
</div>
<style>
#theme_folder_item{display:inline-block;background: white; width:102px; border: 2px solid lightgray; padding:3px; margin:5px;vertical-align:middle;}
.no-lines a{text-decoration: none;}
</style>
<?php	

	foreach ($wp_theme_directories as $themes)
	{
		$themefolder = explode("/", $themes);
		$last        = count($themefolder);
		$themefolder = $themefolder[$last - 1];

		echo '<div class="card no-lines"><span class="dashicons dashicons-category"></span>';
		echo "<b>" . $themefolder . "</b><br>";
		$my_theme_folders = glob($themes . "/*");
		foreach ($my_theme_folders as $theme)
		{
			$themefolder2 = explode("/", $theme);
			$last         = count($themefolder2);
			$nonce        = wp_nonce_url("" . get_bloginfo('url') . "/wp-content/" . $themefolder . "/" . $themefolder2[$last - 1] . "");
			echo "<div id=\"theme_folder_item\">";

			$stylesheet = $themefolder2[7];
			$themeN = wp_get_theme( $stylesheet );

			echo "" . $themeN['Name'] . "";

			echo "<img alt='" . $themefolder2[$last - 1] . "' src='" . get_bloginfo('url') . "/wp-content/" . $themefolder . "/" . $themefolder2[$last - 1] . "/screenshot.png' width=100 height=50 /></a>";
			echo "<a href='" . get_bloginfo('url') . "/wp-admin/themes.php?action=activate&stylesheet=" . $themefolder2[$last - 1] . "&_wpnonce=" . wp_create_nonce("switch-theme_" . $themefolder2[$last - 1]) . "' >";
			echo '<span class="dashicons dashicons-yes"></span></a> ';
			echo "<a href='" . get_bloginfo('url') . "/wp-admin/customize.php?theme=" . $themefolder2[$last - 1] . "' >";
			echo '<span class="dashicons dashicons-admin-appearance"></span></a>';

			echo "<form id='theme-form-".$themefolder2[$last - 1] ."' style='height:20px;display:inline;' action='../wp-admin/theme-editor.php' method='post' />";
			echo "<input type=\"hidden\" name=\"theme\" value=\"" . $themefolder2[$last - 1] . "\" />";
			echo '<a href="#" onclick="document.getElementById(\'theme-form-'.$themefolder2[$last - 1].'\').submit();"><span class="dashicons dashicons-hammer"></span></a>';
			//echo "<input type='submit' name='cmdSubmit' value='submit' />";
			echo "</form>";

			echo "<a href='" . get_bloginfo('url') . "/wp-admin/themes.php?action=delete&stylesheet=" . $themefolder2[$last - 1] . "&_wpnonce=" . wp_create_nonce("delete-theme_" . $themefolder2[$last - 1]) . "' >";
			echo '<span class="dashicons dashicons-dismiss"></span></a>';

			echo "</div>";
			//echo "idi=".wp_create_nonce('twentytwelve')." = 01b31ef862<br>";
		}
		echo "</div>";
	}
	echo "</div>";
}

?>