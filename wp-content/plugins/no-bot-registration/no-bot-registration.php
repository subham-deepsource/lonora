<?php
/*
Plugin Name: No-Bot Registration
Plugin URI: https://ajdg.solutions/product/no-bot-registration/?mtm_campaign=nobot_registration
Author: Arnan de Gans
Author URI: https://www.arnan.me/?mtm_campaign=nobot_registration
Description: Prevent people from registering by blacklisting emails and present people with a security question when registering or posting a comment.
Text Domain: ajdg-nobot
Version: 1.7.10
License: GPLv3
*/

/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2014-2022 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.

*  This software borrows some methods from and is inspired by:
*  - Banhammer (Mika Epstein)
*  - WP No Bot Question (digicompitech).
------------------------------------------------------------------------------------ */

register_activation_hook(__FILE__, 'ajdg_nobot_activate');
register_uninstall_hook(__FILE__, 'ajdg_nobot_remove');

add_action('init', 'ajdg_nobot_init');
add_action('admin_menu', 'ajdg_nobot_adminmenu');
add_action("admin_print_styles", 'ajdg_nobot_dashboard_styles');
add_action('admin_notices', 'ajdg_nobot_notifications_dashboard');
add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'ajdg_nobot_action_links');

// Protect comments
add_action('comment_form_after_fields', 'ajdg_nobot_comment_field');
add_action('comment_form_logged_in_after', 'ajdg_nobot_comment_field');
add_filter('preprocess_comment', 'ajdg_nobot_filter');

// Protect the registration form (Including custom registration in theme)
add_action('register_form', 'ajdg_nobot_registration_field');
add_action('user_registration_email', 'ajdg_nobot_filter');
add_action('register_post', 'ajdg_nobot_blacklist', 10, 3);

if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	// Protect WooCommerce My-Account page
	add_action('woocommerce_register_form', 'ajdg_nobot_woocommerce_field');

	// Protect WooCommerce Registration on checkout
	add_action('woocommerce_after_checkout_registration_form', 'ajdg_nobot_woocommerce_field');
	add_action('woocommerce_register_post', 'ajdg_nobot_filter', 10 ,3);
	add_action('woocommerce_register_post', 'ajdg_nobot_blacklist', 10, 3);
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_activate
 Purpose: 	Activation/setup script
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_activate() {
	global $wp_version;

	add_option('ajdg_nobot_protect', array('registration' => 1, 'comment' => 1, 'woocommerce' => 0));
	add_option('ajdg_nobot_questions', array('What is the sum of 2 and 7?'));
	add_option('ajdg_nobot_answers', array(array('nine','9')));
	add_option('ajdg_nobot_blacklist_message', 'Your email has been banned from registration! Try using another email address or contact support for a solution.');
	add_option('ajdg_nobot_security_message', 'Please fill in the correct answer to the security question!');
	add_option('ajdg_nobot_hide_review', current_time('timestamp'));

	if(version_compare($wp_version, '5.5.0', '>=')) {
		$blacklist = explode("\n", get_option('disallowed_keys')); // wp core option
	} else {
		$blacklist = explode("\n", get_option('blacklist_keys')); // wp core option
	}
	
	$blacklist = array_merge($blacklist, array('hotmail', 'yahoo', '.cn', '.info', '.biz'));
	sort($blacklist);
	$blacklist = implode("\n", array_unique($blacklist));

	if(version_compare($wp_version, '5.5.0', '>=')) {
		update_option('disallowed_keys', $blacklist);
	} else {
		update_option('blacklist_keys', $blacklist);
	}
	unset($blacklist);
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_remove
 Purpose: 	uninstall script
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_remove() {
	delete_option('ajdg_nobot_protect');
	delete_option('ajdg_nobot_questions');
	delete_option('ajdg_nobot_answers');
	delete_option('ajdg_nobot_blacklist_message');
	delete_option('ajdg_nobot_security_message');
	delete_option('ajdg_nobot_hide_review');
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_init
 Purpose: 	Initialize
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_init() {
	wp_enqueue_script('jquery', false, false, false, true);
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_adminmenu
 Purpose: 	Set up dashboard menu
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_adminmenu() {
	$nobot_tools = '';
	$nobot_tools = add_management_page('No-Bot Registration &rarr; Settings', 'No-Bot Registration', 'moderate_comments', 'ajdg-nobot-settings', 'ajdg_nobot_admin');
	
	// Add help tabs
	add_action('load-'.$nobot_tools, 'ajdg_nobot_help_info');
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_help_info
 Purpose:   Help tab on all pages
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_help_info() {
    $screen = get_current_screen();

    $screen->add_help_tab(array(
        'id' => 'ajdg_nobot_thanks',
        'title' => 'Thank You',
        'content' => '<h4>Thank you for using No-Bot Registration</h4><p>No-Bot Registration is growing to be a popular WordPress plugins for protecting your website. No-Bot Registration wouldn\'t be possible without your support and my life wouldn\'t be what it is today without your help.</p><p><em>- Arnan</em></p>'.
        '<p><strong>Business:</strong> <a href="https://ajdg.solutions/?mtm_campaign=nobot_registration" target="_blank">ajdg.solutions website</a>.<br />'.
        '<strong>Personal:</strong> <a href="https://www.arnan.me/?mtm_campaign=nobot_registration" target="_blank">arnan.me website</a>.</p>'
	));
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_action_links
 Purpose:	Plugin page link
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_action_links($links) {
	$links['nobot-settings'] = sprintf('<a href="%s">%s</a>', admin_url('tools.php?page=ajdg-nobot-settings'), 'Settings');
	$links['nobot-help'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/no-bot-registration/', 'Support');
	$links['nobot-ajdg'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/?mtm_campaign=nobot_registration', 'ajdg.solutions');

	return $links;
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_dashboard_styles
 Purpose: 	Add security field to comment form
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_dashboard_styles() {
	wp_enqueue_style('ajdg-nobot-admin-stylesheet', plugins_url('library/dashboard.css', __FILE__));
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_comment_field
 Purpose: 	Add security field to comment form
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_comment_field() {
	ajdg_nobot_field('comment');
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_registration_field
 Purpose: 	Add security field to registration form
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_registration_field() {
	ajdg_nobot_field('registration');
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_woocommerce_field
 Purpose: 	Add security field to WooCommerce Checkout
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_woocommerce_field() {
	ajdg_nobot_field('woocommerce');
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_field
 Purpose: 	Format the security field and put a random question in there
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_field($context = 'comment') {
	$protect = get_option('ajdg_nobot_protect');

	if(current_user_can('editor') 
		OR current_user_can('administrator') 
		OR ($context == 'registration' AND !$protect['registration']) 
		OR ($context == 'comment' AND !$protect['comment']) 
		OR ($context == 'woocommerce' AND !$protect['woocommerce'])
	) return;
	?>
	<p class="comment-form-ajdg_nobot">
		<?php
		$questions = get_option('ajdg_nobot_questions');
		$answers = get_option('ajdg_nobot_answers');
		$selected_id = rand(0, count($questions)-1);
		?>
		<label for="ajdg_nobot_answer"><?php echo htmlspecialchars($questions[$selected_id]); ?> (Required)</label>
		<input id="ajdg_nobot_answer" name="ajdg_nobot_answer" type="text" value="" size="30" <?php if($context == 'registration') { ?> tabindex="25" <?php }; ?>/>
		<input type="hidden" name="ajdg_nobot_id" value="<?php echo $selected_id; ?>" />
		<input type="hidden" name="ajdg_nobot_hash" value="<?php echo ajdg_nobot_security_hash($selected_id, $questions[$selected_id], $answers[$selected_id]); ?>" />
	</p>
	<div style="display:none; height:0px;">
		<p>Leave the field below empty!</p>
		<label for="captcha">Security:</label> <input type="text" name="captcha" value="" />
		<label for="captcha_confirm">Confirm:</label> <input type="text" name="captcha_confirm" value=" " />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_filter
 Purpose: 	Check the given answer and respond accordingly
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_filter($user_login, $user_email = '', $errors = '') {

	$protect = get_option('ajdg_nobot_protect');
	$security_message = get_option('ajdg_nobot_security_message');

	if(current_user_can('editor') 
		OR current_user_can('administrator') 
		OR (!is_array($user_login) AND !$protect['registration'] AND !$protect['woocommerce']) 
		OR (is_array($user_login) AND (!$protect['comment'] OR ($user_login['comment_type'] == 'pingback' OR $user_login['comment_type'] == 'trackback')))
	) return $user_login;

	if(!array_key_exists('ajdg_nobot_answer', $_POST) OR !array_key_exists('ajdg_nobot_id', $_POST) OR trim($_POST['ajdg_nobot_answer']) == '') {
		if(!is_array($user_login) AND is_object($errors)) {
			if(is_wp_error($errors)) {
				$errors->add('nobot_answer_empty', $security_message);
			}
		} else {
			wp_die("<p class=\"error\">$security_message</p>");
		}
	}

	// Check trap fields
	$trap_captcha = $trap_confirm = null;
	if(isset($_POST['captcha'])) $trap_captcha = strip_tags($_POST['captcha']);
	if(isset($_POST['captcha_confirm'])) $trap_confirm = strip_tags($_POST['captcha_confirm']);

	if($trap_captcha != "" OR $trap_confirm != " ") {
		wp_die("<p class=\"error\">$security_message</p>");
	}

	$question_id = intval($_POST['ajdg_nobot_id']);
	$questions_all = get_option('ajdg_nobot_questions');
	$answers_all = get_option('ajdg_nobot_answers');

	// Hash verification to make sure the bot isn't picking on one answer.
	// This does not mean that they got the question right.
	if(trim($_POST['ajdg_nobot_hash']) != ajdg_nobot_security_hash($question_id, $questions_all[$question_id], $answers_all[$question_id])) {
		if(!is_array($user_login)) {
			if(is_wp_error($errors)) {
				$errors->add('nobot_answer_hash', $security_message);
			}
		} else {
			wp_die("<p class=\"error\">$security_message</p>");
		}
	}

	// Verify the answer.
	if($question_id < count($answers_all)) {
		$answers = $answers_all[$question_id];
		foreach($answers as $answer) {
			if(strtolower(strip_tags(trim($_POST['ajdg_nobot_answer']))) == strtolower($answer)) return $user_login;
		}
	}

	// As a last resort - Just fail
	if(!is_array($user_login)) {
		if(is_wp_error($errors)) {
			$errors->add('nobot_general_fail', $security_message);
		}
	} else {
		wp_die("<p class=\"error\">$security_message</p>");
	}
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_blacklist
 Purpose: 	Check for banned emails on registration
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_blacklist($user_login, $user_email, $errors) {
 	global $wp_version;
 	
	if(version_compare($wp_version, '5.5.0', '>=')) {
		$blacklist = get_option('disallowed_keys'); // wp core option
	} else {
		$blacklist = get_option('blacklist_keys'); // wp core option
	}
    $blacklist_message = get_option('ajdg_nobot_blacklist_message');

    $blacklist_array = explode("\n", $blacklist);
    $blacklist_size = sizeof($blacklist_array);

    // Go through blacklist
    for($i = 0; $i < $blacklist_size; $i++) {
        $blacklist_current = trim($blacklist_array[$i]);
        if(stripos($user_email, $blacklist_current) !== false) {
			if(is_wp_error($errors)) {
				$errors->add('invalid_email', $blacklist_message);
			}
            return;
        }
    }
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_security_hash
 Purpose: 	Generate security hash used in question verification
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_security_hash($id, $question, $answer) {
	/*
	 * Hash format: SHA256( Question ID + Question Title + serialize( Question Answers ) )
	 */
	$hash_string = strval($id).strval($question).serialize($answer);
	return hash('sha256', $hash_string);
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_template
 Purpose: 	Settings questions listing
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_template($id, $question, $answers) {
	$id = intval($id);
?>
	<p class="ajdg_nobot_row_<?php echo $id; ?>"><strong><?php _e('Question:', 'ajdg-nobot'); ?></strong></p>
	<p><input type="input" name="ajdg_nobot_question_<?php echo $id; ?>" size="50" style="width: 75%;" value="<?php echo htmlspecialchars($question); ?>" placeholder="<?php _e('Type here to add a new question', 'ajdg-nobot'); ?>" /> <a href="javascript:void(0)" onclick="ajdg_nobot_delete_entire_question(&quot;<?php echo $id; ?>&quot;)"><?php _e('Delete Question', 'ajdg-nobot'); ?></a></p>

	<fieldset class="ajdg_nobot_row_<?php echo $id; ?>"><p><strong><?php _e('Possible Answers:', 'ajdg-nobot'); ?></strong><br /><em><?php _e('Answers are case-insensitive.', 'ajdg-nobot'); ?></em></p>
	<p>
		<?php
		$i = 0;
		foreach($answers as $value) {
			echo '<span id="ajdg_nobot_answer_'.$id.'_'.$i.'">';
			echo '<input type="input" id="ajdg_nobot_answer_'.$id.'_'.$i.'" name="ajdg_nobot_answers_'.$id.'[]" size="50" style="width: 75%;" value="'.htmlspecialchars($value).'" /> <a href="javascript:void(0)" onclick="ajdg_nobot_delete(&quot;'.$id.'&quot;, &quot;'.$i.'&quot;)">Delete Answer</a>';
			echo '</span><br />';
			$i++;
		}
		echo '<script id="ajdg_nobot_placeholder_'.$id.'">ct['.$id.'] = '.$i.';</script>';
		?>
		&nbsp;<a href="javascript:void(0)" onclick="return ajdg_nobot_add_newitem(<?php echo $id; ?>)"><?php _e('Add Possible Answer', 'ajdg-nobot'); ?></a>
	</p>
	</fieldset>
<?php
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_admin
 Purpose: 	Admin screen and save settings
 Since:		1.0
-------------------------------------------------------------*/
function ajdg_nobot_admin() {
	global $wp_version;

	if(!current_user_can('moderate_comments')) return;

	if(isset($_POST['nobot_protection'])) {
		$questions = $answers = $protect = array();

		$protect['registration'] = (isset($_POST['ajdg_nobot_registration'])) ? 1 : 0;
		$protect['comment'] = (isset($_POST['ajdg_nobot_comment'])) ? 1 : 0;
		$protect['woocommerce'] = (isset($_POST['ajdg_nobot_woocommerce'])) ? 1 : 0;

		foreach($_POST as $key => $value) {
			if(strpos($key, 'ajdg_nobot_question_') === 0) {
				// value starts with ajdg_nobot_question_ (form field name)
				$q_id = str_replace('ajdg_nobot_question_', '', $key);
				if(trim(strval($value)) != '') { // if not empty
					$question_slashed = trim(strval($value));
					// WordPress seems to add quotes by default:
					$questions[] = stripslashes($question_slashed);
					$answers_slashed = array_filter($_POST['ajdg_nobot_answers_' . $q_id]);
					foreach($answers_slashed as $key => $value) {
						$answers_slashed[$key] = stripslashes($value);
					}
					$answers[] = $answers_slashed;
				}
			}
		}

		update_option('ajdg_nobot_protect', $protect);
		update_option('ajdg_nobot_questions', $questions);
		update_option('ajdg_nobot_answers', $answers);

		if(isset($_POST['ajdg_nobot_security_message'])) {
			update_option('ajdg_nobot_security_message', sanitize_text_field($_POST['ajdg_nobot_security_message']));
		}

		add_settings_error('ajdg_nobot', 'ajdg_nobot_updated', 'Settings updated.', 'updated');
	}
	
	if(isset($_POST['nobot_blacklist'])) {
		if(isset($_POST['ajdg_nobot_blacklist_message'])) {
			update_option('ajdg_nobot_blacklist_message', sanitize_text_field($_POST['ajdg_nobot_blacklist_message']));
		}

		if(isset($_POST['ajdg_nobot_blacklist'])) {
			$blacklist_new_keys = strip_tags(htmlspecialchars($_POST['ajdg_nobot_blacklist'], ENT_QUOTES));
			$blacklist_array = explode("\n", $blacklist_new_keys);
			sort($blacklist_array);
			
			if(version_compare($wp_version, '5.5.0', '>=')) {
				update_option('disallowed_keys', implode("\n", array_unique($blacklist_array)));			
			} else {
				update_option('blacklist_keys', implode("\n", array_unique($blacklist_array)));
			}
		}
		
		add_settings_error('ajdg_nobot', 'ajdg_nobot_updated', 'Settings updated.', 'updated');
	}

	$ajdg_nobot_protect = get_option('ajdg_nobot_protect', array());
	$ajdg_nobot_questions = get_option('ajdg_nobot_questions', array());
	$ajdg_nobot_answers = get_option('ajdg_nobot_answers', array());
	if(version_compare($wp_version, '5.5.0', '>=')) {
	    $ajdg_nobot_blacklist = get_option('disallowed_keys'); // WP Core
	} else {
	    $ajdg_nobot_blacklist = get_option('blacklist_keys'); // WP Core
	}

    $ajdg_nobot_blacklist_message = get_option('ajdg_nobot_blacklist_message');
    $ajdg_nobot_security_message = get_option('ajdg_nobot_security_message');
	?>

	<div class="wrap">
		<h2><?php _e('No-Bot Registration settings', 'ajdg-nobot'); ?></h2>
		<?php settings_errors(); ?>

		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
				<div id="left-column" class="ajdg-postbox-container">
		
					<div class="ajdg-postbox">
						<h2 class="ajdg-postbox-title"><?php _e('Registration protection', 'ajdg-nobot'); ?></h2>
						<div id="nobot" class="ajdg-postbox-content">

							<form method="post" name="ajdg_nobot_protection">
								<?php settings_fields('ajdg_nobot_question'); ?>
					
								<p><strong><?php _e('Where to add security questions?', 'ajdg-nobot'); ?></strong></p>
								<p><input type="checkbox" name="ajdg_nobot_registration" value="1" <?php if($ajdg_nobot_protect['registration']) echo 'checked="checked"' ?> /> <?php _e('Protect user registration.', 'ajdg-nobot'); ?></p>
					
					
								<p><input type="checkbox" name="ajdg_nobot_comment" value="1" <?php if($ajdg_nobot_protect['comment']) echo 'checked="checked"' ?> /> <?php _e('Protect blog comments.', 'ajdg-nobot'); ?></p>
					
								<p><input type="checkbox" name="ajdg_nobot_woocommerce" value="1" <?php if($ajdg_nobot_protect['woocommerce']) echo 'checked="checked"' ?> /> <?php _e('Protect WooCommerce checkout pages.', 'ajdg-nobot'); ?><br /><em><?php _e('(If user registration is enabled. Has no effect if WooCommerce is not installed)', 'ajdg-nobot'); ?></em></p>
					
								<p><strong><?php _e('Failure message:', 'ajdg-nobot'); ?></strong></p>
								<p><textarea name='ajdg_nobot_security_message' cols='70' rows='2' style="width: 100%;"><?php echo stripslashes($ajdg_nobot_security_message); ?></textarea><br /><em><?php _e('Displayed to those who fail the security question. Keep it short and simple.', 'ajdg-nobot'); ?></em></p>
					
								<script type="text/javascript">
								var ct = Array();
								function ajdg_nobot_delete(id, x) {
									jQuery("#ajdg_nobot_answer_" + id + "_" + x).remove();
								}
							
								function ajdg_nobot_delete_entire_question(id) {
									jQuery("fieldset.ajdg_nobot_row_" + id).remove();
								}
							
								function ajdg_nobot_add_newitem(id) {
									jQuery("#ajdg_nobot_placeholder_" + id).before("<span id=\"ajdg_nobot_line_" + id + "_" + ct[id] + "\"><input type=\"input\" id=\"ajdg_nobot_answer_" + id + "_" + ct + "\" name=\"ajdg_nobot_answers_" + id + "[]\" size=\"50\" style=\"width: 75%;\" value=\"\" placeholder=\"<?php _e('Enter a new answer here', 'ajdg-nobot'); ?>\" /> <a href=\"javascript:void(0)\" onclick=\"ajdg_nobot_delete(&quot;" + id + "&quot;, &quot;" + ct[id] + "&quot;)\">Delete</a><br /></span>");
									ct[id]++;
									return false;
								}
								</script>
					
								<?php
								$i = 0;
								foreach($ajdg_nobot_questions as $question) {
									ajdg_nobot_template($i, $question, $ajdg_nobot_answers[$i]);
									$i++;
								}
								ajdg_nobot_template($i, '', Array());
								?>

								<?php submit_button('Save Changes', 'primary large', 'nobot_protection'); ?>
							</form>

						</div>
					</div>


					<div class="ajdg-postbox">
						<h2 class="ajdg-postbox-title"><?php _e('Blacklisted e-mail domains', 'ajdg-nobot'); ?></h2>
						<div id="nobot" class="ajdg-postbox-content">

							<form method="post" name="ajdg_nobot_blacklist">
								<p><em><?php _e('If you get many fake accounts or paid robots registering you can blacklist their email addresses or domains to prevent them from adding multiple accounts.', 'ajdg-nobot'); ?></em></p>
						
								<p><strong><?php _e('Blacklist message:', 'ajdg-nobot'); ?></strong></p>
								<p><textarea name='ajdg_nobot_blacklist_message' cols='70' rows='2' style="width: 100%"><?php echo stripslashes($ajdg_nobot_blacklist_message); ?></textarea><br /><em><?php _e('This message is shown to users who are not allowed to register on your site. Keep it short and simple.', 'ajdg-nobot'); ?></em></p>
						
								<p><strong><?php _e('Blacklisted emails:', 'ajdg-nobot'); ?></strong></p>
								<p><textarea name='ajdg_nobot_blacklist' cols='70' rows='10' style="width: 100%"><?php echo stripslashes($ajdg_nobot_blacklist); ?></textarea><br /><?php _e('You can add: full emails (someone@hotmail.com), domains (hotmail.com) or simply a keyword (hotmail).', 'ajdg-nobot'); ?> <?php _e('One item per line! Add as many items as you need.', 'ajdg-nobot'); ?><br /><em><strong><?php _e('Caution:', 'ajdg-nobot'); ?></strong> <?php _e('This is a powerful filter matching partial words. So banning "mail" will also block Gmail users!', 'ajdg-nobot'); ?></em></p>
						
								<?php submit_button('Save Changes', 'primary large', 'nobot_blacklist'); ?>
							</form>

						</div>
					</div>

				</div>
				<div id="right-column" class="ajdg-postbox-container">

					<div class="ajdg-postbox">
						<h2 class="ajdg-postbox-title"><?php _e('No-Bot Registration', 'ajdg-nobot'); ?></h2>
						<div id="stats" class="ajdg-postbox-content">
							<p><strong><?php _e('Support No-Bot Registration', 'ajdg-nobot'); ?></strong></p>
							<p><?php _e('Consider writing a review or making a donation if you like the plugin or if you find the plugin useful. Thanks for your support!', 'ajdg-nobot'); ?></p>

							<p><a class="button-primary" href="https://ajdg.solutions/go/donate" target="_blank"><?php _e('Donate via Paypal', 'ajdg-nobot'); ?></a> <a class="button-secondary" href="https://wordpress.org/support/plugin/no-bot-registration/reviews?rate=5#postform" target="_blank"><?php _e('Write review on wordpress.org', 'ajdg-nobot'); ?></a> <a class="button-secondary" href="https://wordpress.org/support/plugin/no-bot-registration/" target="_blank"><?php _e('Support Forum', 'ajdg-nobot'); ?></a></p>
		
							<p><strong><?php _e('Plugins and services', 'ajdg-nobot'); ?></strong></p>
							<table width="100%">
								<tr>
									<td width="33%">
										<div class="ajdg-sales-widget" style="display: inline-block; margin-right:2%;">
											<a href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=nobot_registration" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/monetize-your-site.jpg", __FILE__); ?>" alt="AdRotate Professional" width="228" height="120"></div></a>
											<a href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=nobot_registration" target="_blank"><div class="title"><?php _e('AdRotate Professional', 'ajdg-nobot'); ?></div></a>
											<div class="sub_title"><?php _e('WordPress Plugin', 'ajdg-nobot'); ?></div>
											<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=nobot_registration" target="_blank">Starting at &euro; 39,-</a></div>
											<hr>
											<div class="description"><?php _e('Run successful advertisement campaigns on your WordPress website within minutes.', 'ajdg-nobot'); ?></div>
										</div>							
									</td>
									<td width="33%">
										<div class="ajdg-sales-widget" style="display: inline-block; margin-right:2%;">
											<a href="https://ajdg.solutions/product/wordpress-maintenance-and-updates/?mtm_campaign=nobot_registration" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/wordpress-maintenance.jpg", __FILE__); ?>" alt="WordPress Maintenance" width="228" height="120"></div></a>
											<a href="https://ajdg.solutions/product/wordpress-maintenance-and-updates/?mtm_campaign=nobot_registration" target="_blank"><div class="title"><?php _e('WP Maintenance', 'ajdg-nobot'); ?></div></a>
											<div class="sub_title"><?php _e('Professional service', 'ajdg-nobot'); ?></div>
											<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/wordpress-maintenance-and-updates/?mtm_campaign=nobot_registration" target="_blank">Starting at &euro; 22,50</a></div>
											<hr>								
											<div class="description"><?php _e('Get all the latest updates for WordPress and plugins. Maintenance, delete spam and clean up files.', 'ajdg-nobot'); ?></div>
										</div>
									</td>
									<td>
										<div class="ajdg-sales-widget" style="display: inline-block;">
											<a href="https://ajdg.solutions/product/woocommerce-single-page-checkout/?mtm_campaign=nobot_registration" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/single-page-checkout.jpg", __FILE__); ?>" alt="WooCommerce Single Page Checkout" width="228" height="120"></div></a>
											<a href="https://ajdg.solutions/product/woocommerce-single-page-checkout/?mtm_campaign=nobot_registration" target="_blank"><div class="title"><?php _e('Single Page Checkout', 'ajdg-nobot'); ?></div></a>
											<div class="sub_title"><?php _e('WooCommerce Plugin', 'ajdg-nobot'); ?></div>
											<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/woocommerce-single-page-checkout/?mtm_campaign=nobot_registration" target="_blank">Only &euro; 10,-</a></div>
											<hr>
											<div class="description"><?php _e('Merge your cart and checkout pages into one single page in seconds with no setup required at all.', 'ajdg-nobot'); ?></div>
										</div>
									</td>
							</table>
						</div>
					</div>

					<div class="ajdg-postbox">
						<h2 class="ajdg-postbox-title"><?php _e('News & Updates', 'ajdg-nobot'); ?></h2>
						<div id="news" class="ajdg-postbox-content">		
							<p><a href="http://ajdg.solutions/feed/" target="_blank" title="Subscribe to the AJdG Solutions RSS feed!" class="button-primary"><i class="icn-rss"></i>Subscribe via RSS feed</a> <em>No account required!</em></p>

							<?php wp_widget_rss_output(array(
								'url' => 'http://ajdg.solutions/feed/', 
								'items' => 5, 
								'show_summary' => 1, 
								'show_author' => 0, 
								'show_date' => 1)
							); ?>
						</div>
					</div>
					
				</div>
			</div>
		</div>

	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      ajdg_nobot_notifications_dashboard
 Since:		1.1
-------------------------------------------------------------*/
function ajdg_nobot_notifications_dashboard() {
	global $current_user;

	if(isset($_GET['hide'])) {
		if($_GET['hide'] == 1) update_option('ajdg_nobot_hide_review', 1);
	}

	$displayname = (strlen($current_user->user_firstname) > 0) ? $current_user->user_firstname : $current_user->display_name;
	$review_banner = get_option('ajdg_nobot_hide_review');
	if($review_banner != 1 AND $review_banner < (current_time('timestamp') - 2419200)) {
		echo '<div class="ajdg-notification notice" style="">';
		echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
		echo '	<div class="ajdg-notification-message">Welcome back <strong>'.$displayname.'</strong>! If you like <strong>No-Bot Registration</strong> let the world know that you do. Thanks for your support!.<br />If you have questions, complaints or something else that does not belong in a review, please use the <a href="https://wordpress.org/support/plugin/no-bot-registration/">support forum</a>!</div>';
		echo '	<div class="ajdg-notification-cta">';
		echo '		<a href="https://wordpress.org/support/plugin/no-bot-registration/reviews/?rate=5#postform" class="ajdg-notification-act button-primary">Write Review</a>';
		echo '		<a href="tools.php?page=ajdg-nobot-settings&hide=1" class="ajdg-notification-dismiss">Maybe later</a>';
		echo '	</div>';
		echo '</div>';
	}
}
?>