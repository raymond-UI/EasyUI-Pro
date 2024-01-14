<?php

// Function to generate color palette
function easyui_pro_generate_color_palette($base_color) {
    $palette = [];

    // Define the number of tones in the palette
    $num_tones = 20;

    // Convert base color to RGB
    list($r, $g, $b) = sscanf($base_color, "#%02x%02x%02x");

    // Calculate step size for lightening
    $lighten_step = 100 / $num_tones;

    // Generate color tones
    for ($i = 0; $i <= $num_tones; $i++) {
        // Calculate lightening percentage
        $lighten_percentage = $i * $lighten_step;

        // Lighten the color
        $lighten_r = round($r + ($lighten_percentage / 100) * (255 - $r));
        $lighten_g = round($g + ($lighten_percentage / 100) * (255 - $g));
        $lighten_b = round($b + ($lighten_percentage / 100) * (255 - $b));

        // Format the color values as hexadecimal
        $lighten_color = sprintf("#%02x%02x%02x", $lighten_r, $lighten_g, $lighten_b);

        // Assign the colors to the palette
        $palette["color_light_{$i}"] = $lighten_color;
    }

    return $palette;
}


// // Enqueue the custom JavaScript file
// function easyui_pro_enqueue_custom_script() {
//     wp_enqueue_script('easyui-pro-custom-script', plugin_dir_url(__FILE__) . 'custom-admin-script.js', array('jquery', 'wp-color-picker'), null, true);

//     // Pass initial color values to the script
//     wp_localize_script('easyui-pro-custom-script', 'easyuiProInitialColors', array(
//         'primary' => get_option('previous_primary_color', '#ff0000'),
//         'secondary' => get_option('previous_secondary_color', '#00ff00'),
//         'tertiary' => get_option('previous_tertiary_color', '#0000ff'),
//     ));

//     // Enqueue the color picker script
//     wp_enqueue_script('wp-color-picker');
//     // wp_enqueue_style('wp-color-picker');

// 	// Enqueue additional script to handle color picker changes
// 	wp_enqueue_script('easyui-pro-color-picker-handler', plugin_dir_url(__FILE__) . 'color-picker-handler.js', array('jquery', 'wp-color-picker', 'easyui-pro-custom-script'), null, true);
	
// }

// Admin settings page
function easyui_pro_settings_page() {
?>
<div class="wrap reset">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	<form method="post" action="options.php">
		<?php
									 settings_fields('easyui_pro_settings');
		do_settings_sections('easyui_pro_settings');
		submit_button('Save Changes');
		?>
	</form>
</div>
<?php
}

// Register settings
function easyui_pro_register_settings() {
	register_setting('easyui_pro_settings', 'primary_base_color', 'sanitize_hex_color');
	register_setting('easyui_pro_settings', 'secondary_base_color', 'sanitize_hex_color');
	register_setting('easyui_pro_settings', 'tertiary_base_color', 'sanitize_hex_color');
}

// Add settings fields
function easyui_pro_settings_fields() {
	add_settings_section('easyui_pro_section', 'Color Settings', '', 'easyui_pro_settings');
	add_settings_field('primary_base_color', 'Primary Base Color', 'easyui_pro_color_field', 'easyui_pro_settings', 'easyui_pro_section', ['base_color' => 'primary']);
	add_settings_field('secondary_base_color', 'Secondary Base Color', 'easyui_pro_color_field', 'easyui_pro_settings', 'easyui_pro_section', ['base_color' => 'secondary']);
	add_settings_field('tertiary_base_color', 'Tertiary Base Color', 'easyui_pro_color_field', 'easyui_pro_settings', 'easyui_pro_section', ['base_color' => 'tertiary']);
}

// Color field callback
function easyui_pro_color_field($args) {
    $base_color_option = get_option($args['base_color'] . '_base_color', 'ff0000');
    $base_color = sanitize_hex_color($base_color_option);
    ?>
    <input type="color" id="color-preview" name="<?php echo esc_attr($args['base_color']); ?>_base_color" value="<?php echo esc_attr($base_color); ?>">
    <?php
}


// Dynamic CSS generation
function easyui_pro_dynamic_styles() {

	$primary_base_color = get_option('primary_base_color', '#ff0000');
	$secondary_base_color = get_option('secondary_base_color', '#00ff00');
	$tertiary_base_color = get_option('tertiary_base_color', '#0000ff');

	$primary_palette = easyui_pro_generate_color_palette($primary_base_color);
	$secondary_palette = easyui_pro_generate_color_palette($secondary_base_color);
	$tertiary_palette = easyui_pro_generate_color_palette($tertiary_base_color);


	$css = ":root {\n";
		foreach ($primary_palette as $key => $value) {
			$css .= "    --primary_{$key}: {$value};\n";
		}
		foreach ($secondary_palette as $key => $value) {
			$css .= "    --secondary_{$key}: {$value};\n";
		}
		foreach ($tertiary_palette as $key => $value) {
			$css .= "    --tertiary_{$key}: {$value};\n";
		}
		$css .= "}\n";

	// Create a temporary stylesheet file
	$upload_dir = wp_upload_dir();
	$stylesheet_path = trailingslashit($upload_dir['basedir']) . 'easyui-pro-dynamic.css';

	// Attempt to write the dynamic styles to the file
	$success = file_put_contents($stylesheet_path, $css);

	// Check for success and handle errors
	if ($success === false) {
		// Log the error
		error_log("Failed to write dynamic styles to file. Path: $stylesheet_path");

		// Output an error message to the browser
		wp_die('Failed to update dynamic styles. Please check your server logs for more information.');
	}

	// Enqueue the dynamic stylesheet
	wp_enqueue_style('easyui-pro-dynamic-styles', $upload_dir['baseurl'] . '/easyui-pro-dynamic.css');

	// Update previous color values in WordPress options
	update_option('previous_primary_color', $primary_base_color);
	update_option('previous_secondary_color', $secondary_base_color);
	update_option('previous_tertiary_color', $tertiary_base_color);
}

// add_action('admin_enqueue_scripts', 'easyui_pro_enqueue_custom_script');
add_action('admin_enqueue_scripts', 'easyui_pro_dynamic_styles');
add_action('admin_init', 'easyui_pro_register_settings');
add_action('admin_init', 'easyui_pro_settings_fields');