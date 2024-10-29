<?php
/*
Plugin Name: API Key Manager
Description: This plugin securely stores API keys and allows you to manage them.
Version: 1.0.1
Author: Johannes Luger
Author URI: https://seoschmiede.at
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

function api_key_manager_enqueue_scripts() {
	wp_enqueue_script('api-key-manager', plugin_dir_url(__FILE__) . 'js/api-key-manager.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'api_key_manager_enqueue_scripts');

// Add the menu item.
add_action('admin_menu', 'api_key_manager_menu');
function api_key_manager_menu() {
	add_menu_page(
		'API Key Manager',
		'API Key Manager',
		'manage_options',
		'api_key_manager',
		'api_key_manager_page',
		'dashicons-lock'
	);
}

// Display the main page content.
function api_key_manager_page() {
	$api_keys = get_option('api_keys', array());
	if (!empty($_POST['api_key']) && !empty($_POST['api_value'])) {
		$api_key = sanitize_key($_POST['api_key']);
		$api_value = sanitize_text_field($_POST['api_value']);
		$api_keys[$api_key] = $api_value;
		update_option('api_keys', $api_keys);
	}
	if (!empty($_GET['action']) && !empty($_GET['api_key'])) {
		if ($_GET['action'] === 'delete') {
			$api_key = sanitize_key($_GET['api_key']);
			unset($api_keys[$api_key]);
			update_option('api_keys', $api_keys);
		}
	}
?>
<div class="wrap">
	<h1>API Key Manager</h1>
	<h2>Add API Key</h2>
	<form method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="api_key">API Key</label></th>
					<td><input name="api_key" type="text" id="api_key" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="api_value">API Value</label></th>
					<td><input name="api_value" type="text" id="api_value" class="regular-text"></td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('api_key_manager_add_key'); ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add API Key"></p>
	</form>
	<h2>Active API Keys</h2>
	<?php if (empty($api_keys)) : ?>
	<p>No API keys found.</p>
	<?php else : ?>
	<table class="wp-list-table widefat striped">
		<thead>
			<tr>
				<th>API Key</th>
				<th>API Value</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($api_keys as $key => $value) : ?>
			<tr>
				<td><?php echo esc_html($key); ?></td>
				<td><?php echo esc_html($value); ?></td>
				<td>
					<a href="<?php echo esc_url(add_query_arg(array('page' => 'api_key_manager', 'api_key' => $key, 'action' => 'edit'), admin_url('admin.php'))); ?>" class="button">Edit</a>
					<a href="<?php echo esc_url(add_query_arg(array('page' => 'api_key_manager', 'api_key' => $key, 'action' => 'delete'), admin_url('admin.php'))); ?>" class="button">Delete</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
<?php
}

// Handle form submissions.
add_action('admin_post', 'api_key_manager_handle_form');
function api_key_manager_handle_form() {
	$api_keys = get_option('api_keys', array());
	$nonce = $_POST['_wpnonce'];
	// Verify nonce.
	if (!wp_verify_nonce($nonce, 'api_key_manager_add_key')) {
		wp_die('Security check');
	}
	// Add API key.
	if (isset($_POST['submit'])) {
		$api_key = sanitize_key($_POST['api_key']);
		if (empty($api_key)) {
			wp_die('API key cannot be empty');
		}
		if (isset($api_keys[$api_key])) {
			wp_die('API key already exists');
		}
		$api_value = sanitize_text_field($_POST['api_value']);
		$api_keys[$api_key] = $api_value;
		update_option('api_keys', $api_keys);
	}
	// Redirect to the plugin page.
	wp_redirect(admin_url('admin.php?page=api_key_manager'));
	exit;
}

// Add the edit form to the dashboard.
add_action('admin_notices', 'api_key_manager_edit_form');
function api_key_manager_edit_form() {
	if (!empty($_GET['api_key']) && !empty($_GET['action']) && $_GET['action'] === 'edit') {
		$api_keys = get_option('api_keys', array());
		$api_key = sanitize_key($_GET['api_key']);
		$api_value = isset($api_keys[$api_key]) ? $api_keys[$api_key] : '';
?>
<div class="wrap">
	<h1>Edit API Key</h1>
	<form method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="api_key">API Key</label></th>
					<td><input name="api_key" type="text" id="api_key" class="regular-text" value="<?php echo esc_attr($api_key); ?>" readonly></td>
				</tr>
				<tr>
					<th scope="row"><label for="api_value">API Value</label></th>
					<td><input name="api_value" type="text" id="api_value" class="regular-text" value="<?php echo esc_attr($api_value); ?>"></td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('api_key_manager_edit_key'); ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>
<?php
	}
}

// Handle edit form submissions.
add_action('admin_post', 'api_key_manager_handle_edit_form');
function api_key_manager_handle_edit_form() {
	$api_keys = get_option('api_keys', array());
	$nonce = $_POST['_wpnonce'];
	// Verify nonce.
	if (!wp_verify_nonce($nonce, 'api_key_manager_edit_key')) {
		wp_die('Security check');
	}
	// Update API key.
	if (isset($_POST['submit'])) {
		$api_key = sanitize_key($_POST['api_key']);
		if (empty($api_key)) {
			wp_die('API key cannot be empty');
		}
		if (!isset($api_keys[$api_key])) {
			wp_die('API key does not exist');
		}
		$api_value = sanitize_text_field($_POST['api_value']);
		$api_keys[$api_key] = $api_value;
		update_option('api_keys', $api_keys);

	}
	// Redirect to the plugin page.
	wp_redirect(admin_url('admin.php?page=api_key_manager'));
	exit;
}

// use Nullix\CryptoJsAes\CryptoJsAes;
include(dirname( __FILE__ ).'/CryptoJsAes.php');
add_action('wp_enqueue_scripts', 'enqueue_my_scripts');
function enqueue_my_scripts() {
	$password = "rkwfPafUMSiZtAsdfrtvblF0JM07";
	wp_enqueue_script('cryptojs-aes-min', plugin_dir_url(__FILE__).'cryptojs-aes.min.js', array('jquery'),rand(),true);
	wp_enqueue_script('cryptojs-aes-format', plugin_dir_url(__FILE__).'cryptojs-aes-format.js', array('jquery'),rand(),true);
	wp_enqueue_script('my-script', plugin_dir_url(__FILE__).'script.js', array('jquery'),rand(),true);
	$api_keys = get_option('api_keys', array());
	$encrypted = CryptoJsAes::encrypt($api_keys, $password);
	$admin_url = strtok( admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ), '?' );
	wp_localize_script( 'my-script','MyAjax', array( 
		'ajaxurl' => $admin_url,
		'jsondata' => $encrypted
	));
}
function api_key_manager_enqueue_styles() {
	wp_enqueue_style('api-key-manager-style', plugin_dir_url(__FILE__) . 'css/api-key-manager.css');
}
add_action('admin_enqueue_scripts', 'api_key_manager_enqueue_styles');