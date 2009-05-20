<?php

/**
 * @package SL Developer Info
 * @version 1.4.1
 * @author Stephen Lewis (http://experienceinternet.co.uk/)
 * @copyright Copyright (c) 2009, Stephen Lewis
 * @license http://creativecommons.org/licenses/by-sa/3.0 Creative Commons Attribution-Share Alike 3.0 Unported
 * @link http://experienceinternet.co.uk/resources/details/sl-developer-info/
*/

if ( ! defined('SL_DEVINFO_NAME'))
{
	define('SL_DEVINFO_NAME', 'SL Developer Info');
	define('SL_DEVINFO_VERSION', '1.4.1');
	define('SL_DEVINFO_CLASS', 'Sl_developer_info');
	
	// Navigation constants.
	define('SL_DEVINFO_TEMPLATES', 0);
	define('SL_DEVINFO_WEBLOGS', 1);
	define('SL_DEVINFO_FILES', 2);
	define('SL_DEVINFO_GLOBALS', 3);
	define('SL_DEVINFO_PREFS', 4);
}

class Sl_developer_info_CP
{
	/**
	 * The module version (required variable).
	 * @var version
	 */
	var $version = SL_DEVINFO_VERSION;
	
	/**
	 * The short "base" used when constructing Control Panel URLs.
	 * @access 	private
	 * @var 		short_base_url
	 */
	var $short_base_url = '';
	
	/**
	 * The full "base" used when constructing Control Panel URLs.
	 * @access	private
	 * @var 		full_base_url
	 */
	var $full_base_url = '';
	
	/**
	 * An array containing details of the module navigation.
	 * @access	private
	 * @var 		nav
	 */
	var $nav = array();
	
	
	/**
	 * An array containing "friendly" titles for the Template types.
	 * @access	private
	 * @var 		template_types
	 */
	var $template_types = array();
	
	
	/**
	 * An array containing "friendly" titles for the Weblog Field types.
	 * @access	private
	 * @var 		weblog_field_types
	 */
	var $weblog_field_types = array();
	
	
	/**
	 * An array containing "friendly" titles for the File types.
	 * @access	private
	 * @var 		file_types
	 */
	var $file_types = array();
	
	
	/**
	 * The site ID.
	 * @access  private
	 * @var     site_id
	 */
	var $site_id = 1;
	
	
	/**
	 * PHP4 constructor.
	 * @see __construct
	 */
	function Sl_developer_info_CP($switch = TRUE)
	{
		$this->__construct($switch);
	}
	
	
	/**
	 * PHP5 constructor.
	 * @param 		bool		$switch
	 */
	function __construct($switch = TRUE)
	{
		global $IN, $LANG, $PREFS;
		
		// Initialise some variables.		
		$this->short_base_url = 'C=modules' . AMP . 'M=Sl_developer_info' . AMP;
		$this->full_base_url 	= BASE . AMP . $this->short_base_url;
		$this->site_id        = $PREFS->ini('site_id');
		
		// The module top-level navigation items.
		$this->nav = array(
			array(
				'page' 				=> 'templates',
				'title' 			=> $LANG->line('nav_templates'),
				'section_id' 	=> SL_DEVINFO_TEMPLATES
				),
			array(
				'page' 				=> 'weblogs',
				'title' 			=> $LANG->line('nav_weblogs'),
				'section_id' 	=> SL_DEVINFO_WEBLOGS
				),
			array(
				'page' 				=> 'files',
				'title' 			=> $LANG->line('nav_files'),
				'section_id' 	=> SL_DEVINFO_FILES
				),
			array(
				'page'				=> 'globals',
				'title'				=> $LANG->line('nav_globals'),
				'section_id'	=> SL_DEVINFO_GLOBALS
				),
			array(
			  'page'        => 'prefs',
			  'title'       => $LANG->line('nav_prefs'),
			  'section_id'  => SL_DEVINFO_PREFS
			  )
			);
			
		// "Friendly" titles for the Template types.
		$this->template_types = array(			
			'css'				=> $LANG->line('template_type_css'),
			'js'				=> $LANG->line('template_type_js'),
			'rss'				=> $LANG->line('template_type_rss'),
			'static'		=> $LANG->line('template_type_static'),
			'webpage'		=> $LANG->line('template_type_webpage'),
			'xml'				=> $LANG->line('template_type_xml')
			);
			
		// "Friendly" titles for the Weblog Field types.
		$this->weblog_field_types = array(
			'text'			=> $LANG->line('weblog_field_type_text'),
			'textarea'	=> $LANG->line('weblog_field_type_textarea'),
			'select'		=> $LANG->line('weblog_field_type_select'),
			'date'			=> $LANG->line('weblog_field_type_date'),
			'rel'				=> $LANG->line('weblog_field_type_rel')			
			);
			
		// "Friendly" titles for the File types.
		$this->file_types = array(
			'img'		=> $LANG->line('file_type_image'),
			'all'		=> $LANG->line('file_type_all')		
			);
		
		// Display the appropriate page content.
		if ($switch)
		{
			switch ($IN->GBL('P'))
			{
				case 'templates':
					$this->_display_templates();
					break;
					
				case 'weblogs':
					$this->_display_weblogs();
					break;
					
				case 'files':
					$this->_display_files();
					break;
					
				case 'globals':
					$this->_display_globals();
					break;
					
				case 'prefs':
				  $this->_display_prefs();
				  break;
					
				case 'home':
				default:
					$this->_display_home();
					break;
			}
		}
	}
	
	
	/**
	 * Creates a control panel page URL.
	 * @access  private
	 * @param 	string 		$page 		The page name.
	 * @param 	array 		$params 	An array of key=>value pairs to be included as parameters in the URL.
	 * @param 	bool 			$short 		A boolean value specifying whether to use the short base URL.
	 * @return 	string 		A string containing the full page URL.
	 */
	function _generate_url($page = '', $params = '', $short = FALSE)
	{
		$r = ($short === TRUE) ? $this->short_base_url : $this->full_base_url;
		$r .= 'P=' . $page;
		
		// If we don't have an array of parameters, the URL is complete.
		if ( ! is_array($params))
		{
			return $r;
		}
		
		// Add the parameters to the URL.
		foreach ($params AS $key => $value)
		{
			$r .= AMP . $key . '=' . $value;
		}
		
		return $r;
	}
	
	
	/**
	 * Builds the module navigation.
	 * @access  private
	 * @param 	integer 		$section_id 		The section ID.
	 */
	function _generate_nav($section_id = '')
	{
		global $DSP, $LANG;
		
		$r = '<td valign="top" style="width:220px;">';
		$r .= '<div class="tableHeadingAlt"><a href="' . $this->full_base_url . '">' . $LANG->line('home_title') . '</a></div>';
		$r .= '<div class="profileMenuInner">';
		
		// Loop through the navigation items.
		foreach ($this->nav as $s)
		{
			$r .= ($s['section_id'] === $section_id) ? '<div class="navPad active">' : '<div class="navPad">';
			$r .= $DSP->anchor($this->_generate_url($s['page']), $s['title'], 'title="' . $s['title'] . '"');
			$r .= '</div>';
		}
		
		$r .= '</div>';
		$r .= '</td>';

		return $r;
	}
	
	
	/**
	 * Builds the breadcrumb navigation.
	 * @access  private
	 * @param 	array 		$pages 				An array of key=>url pairs to include in the breadcrumb navigation.
	 * @param 	bool 			$underline 		A boolean value specifying whether to underline the breadcrumb navigation.
	 * @param 	bool 			$root_link 		A boolean value specifying whether the "root" breadcrumb should be a link.
	 */
	function _generate_breadcrumbs($pages = '', $underline = FALSE, $root_link = TRUE)
	{
		global $DSP, $LANG;
		
		//$url = ($root_link === FALSE) ? '' : $this->base_url;
		$url = $this->full_base_url;
		
		$DSP->crumbline = ($underline === TRUE);
		$DSP->crumb = $DSP->anchor($url, $LANG->line('home_title'));
		$DSP->crumb .= $DSP->build_crumb($pages);
	}
	
	
	/**
	 * Builds the "right breadcrumb"; i.e. the "action" button that appears in the top-right.
	 * @access  private
	 * @param 	string 		$title 		The button title.
	 * @param 	string 		$target 	The target URL.
	 */
	function _generate_action_button($title = '', $target = '')
	{
		global $DSP;
		$DSP->right_crumb($title, $target);
	}
	
	
	/**
	 * Builds the page (helper function, called by every function that generates a UI).
	 * @access  private
	 * @param 	integer 	$section 		The active section.
	 * @param 	string 		$title 			The page title.
	 * @param 	string 		$content 		The page content.
	 * @param 	string 		$body_id An ID to add to the body element.
	 */
	function _generate_ui($section = '', $title = '', $content = '', $body_id = '')
	{
		global $DSP, $LANG;
		
		if ($body_id !== '')
		{
			$DSP->body_props = ' id="' . $body_id . '"';
		}
		
		// Set the page title.
		$DSP->title = $title;
		
		// Build the page body.
		$DSP->body = '<table border="0" cellspacing="0" cellpadding="0" style="width : 100%;">';
		$DSP->body .= '<tr>';
		
		$DSP->body .= $this->_generate_nav($section);
		
		$DSP->body .= '<td class="default" style="width : 8px;"></td>';
		
		$DSP->body .= '<td valign="top">';
		$DSP->body .= $content;					// The page content.		
		$DSP->body .= '</td></tr></table>';
	}
	
	
	/**
	 * Adds a drop-down list "jump" navigation to the page.
	 * @access	private
	 * @param		array 	  $items			An array of items to display in the "jump" navigation. Structure of items: array('id' => ID, 'title' => TITLE);
	 * @param 	string	  $target			The form target URL.
	 */	
	function _display_jump_nav($items, $target = '')
	{
		global $DSP, $LANG, $EXT;
		
		$c = '';
		
		if (( ! is_array($items)) OR (! $target))
		{
			return $c;
		}

		// Create the jump navigation form.
		$c .= $DSP->form_open(
			array(
				'action'	=> $target,
				'method'	=> 'post',
				'name'		=> 'sl-devinfo-jump-nav',
				'id'			=> 'sl-devinfo-jump-nav',
				'style'		=> 'margin : 0.5em 0'
				)			
			);
		
		$c .= '<div><label for="sl-devinfo-jump-to">' . $LANG->line('nav_jump_to') . NBS . NBS . '</label>';
		$c .= $DSP->input_select_header('sl-devinfo-jump-to', '', 0, '', 'id="sl-devinfo-jump-to"');
	
		foreach ($items AS $o)
		{
			$c .= $DSP->input_select_option("{$o['id']}", "{$o['title']}");
		}
	
		$c .= $DSP->input_select_footer();
		$c .= $DSP->input_submit($LANG->line('nav_go'));
		$c .= '</div>' . $DSP->form_close();
		
		// If we have some jQuery to play with, we hijack the "jump-to" navigation.
		if (isset($EXT->version_numbers['Cp_jquery']) === TRUE OR empty($SESS->cache['scripts']['jquery']) === FALSE)
		{
			$c .= <<<JSBLOCK
<script type="text/javascript">

	jQuery(document).ready(function() {					
		jQuery('#sl-devinfo-jump-nav').submit(function() {
			target = jQuery('#' + jQuery('#sl-devinfo-jump-to').val());
			if (target.length) {
				var offset = target.offset().top;
				jQuery('html,body').animate({scrollTop: offset}, 1000);

				return false;
			}

			return true;
		});					
	});

</script>
JSBLOCK;
		}
		
		return $c;
	}
	
	
	/**
	 * Checks whether the page load is as a result of a "jump nav" form submission.
	 * If so, the page is reloaded with the appropriate hash anchor.
	 *
	 * NOTE:
	 * This should be called before echoing any data to the page, as it uses header('Location:').
	 * @access	private
	 * @param 	string 			$base_url 			The base URL onto which we attach the hash location.
	 */
	function _check_for_jump($base_url = '')
	{
		if (( ! $base_url) OR ( ! isset($_POST['sl-devinfo-jump-to'])))
		{
			return FALSE;
		}
		
		header('Location: ' . $base_url . '#' . $_POST['sl-devinfo-jump-to']);
		exit;
	}
	
	
	/**
	 * Renders the "global variables" page.
	 * @access	private
	 */
	function _display_globals()
	{
		global $DSP, $LANG, $DB;
		
		// Browser title.
		$meta_title = $LANG->line('globals_meta_title');
		
		// Breadcrumbs.
		$this->_generate_breadcrumbs(array($LANG->line('globals_title') => ''), FALSE, TRUE);
		$this->_generate_action_button($LANG->line('globals_action_link'), BASE . AMP . 'C=templates' . AMP . 'M=global_variables');
		
		// Content.
		$c = '';
		
		// Page heading.
		$c .= $DSP->heading($LANG->line('globals_title'));
		
		// List the Global Variables.	  
		$vars = $DB->query("SELECT variable_id, variable_name, variable_data
		  FROM exp_global_variables
		  WHERE site_id = '$this->site_id'
		  ORDER BY variable_name ASC");
		
		// If we have no Global Variables, display a message to that effect, and leave.
		if ($vars->num_rows == 0)
		{
			$c .= $DSP->qdiv('itemWrapper', $LANG->line('globals_no_vars'));
			$this->_generate_ui(SL_DEVINFO_GLOBALS, $meta_title, $c);
			return;
		}
		
		// Open the table.
		$c .= $DSP->table_open(array('class' => 'tableBorder', 'border' => '0', 'style' => 'width : 100%; margin-bottom : 1.5em;'));
		$td_style = 'tableHeading';
		
		// Headings
		$c .= $DSP->tr();
		$c .= $DSP->table_qcell($td_style, $LANG->line('globals_var_id'), '7%');
		$c .= $DSP->table_qcell($td_style, $LANG->line('globals_var_name'), '23%');
		$c .= $DSP->table_qcell($td_style, $LANG->line('globals_var_value'));
		$c .= $DSP->tr_c();
		
		// Loop through the File Upload Locations.
		$row = 0;
		foreach ($vars->result AS $v)
		{
			$td_style = ($row++ % 2) ? 'tableCellOne' : 'tableCellTwo';
			$edit_var = BASE . AMP . 'C=templates' . AMP . 'M=edit_global_var' . AMP . 'id=' . $v['variable_id'];
					
			$c .= $DSP->tr();
			$c .= $DSP->table_qcell($td_style, $v['variable_id'], '', 'top');
			$c .= $DSP->table_qcell($td_style, $DSP->anchor($edit_var, $v['variable_name']), '', 'top');			
			$c .= $DSP->table_qcell($td_style, '<code>' . htmlentities($v['variable_data']) . '</code>', '', 'top');
			
			// Close the table row.
			$c .= $DSP->tr_c();
		}
				
		// Close the table.
		$c .= $DSP->table_close();
		
		$this->_generate_ui(SL_DEVINFO_GLOBALS, $meta_title, $c);
	}
	
	
	/**
	 * Renders the "template information" page.
	 * @access	private
	 */
	function _display_templates()
	{
		global $DSP, $LANG, $DB;
		
		// Check whether we need to jump to a page anchor. This must be done first.
		$this->_check_for_jump(BASE . '&C=modules&M=Sl_developer_info&P=templates');
		
		// Browser title.
		$meta_title = $LANG->line('templates_meta_title');
		
		// Breadcrumbs.
		$this->_generate_breadcrumbs(array($LANG->line('templates_title') => ''), FALSE, TRUE);
		$this->_generate_action_button($LANG->line('templates_action_link'), BASE . AMP . 'C=templates');
		
		// Content.
		$c = '';
		
		// Page heading.
		$c .= $DSP->heading($LANG->line('templates_title'));
		
		// List the Template Groups.
		$groups = $DB->query("SELECT group_id, group_name, is_site_default
			FROM exp_template_groups
			WHERE site_id = '$this->site_id'
			ORDER BY group_order
			ASC");
		
		// If we have no Template Groups, display a message to that effect, and leave.
		if ($groups->num_rows == 0)
		{
			$c .= $DSP->qdiv('itemWrapper', $LANG->line('templates_no_groups'));
			$this->_generate_ui(SL_DEVINFO_TEMPLATES, $meta_title, $c);
			return;
		}
		
		// Create the "jump to" navigation.
		foreach ($groups->result AS $group)
		{
			$jump_nav[] = array('id' => "group-{$group['group_id']}", 'title' => "{$group['group_name']}");
		}
		
		$c .= $this->_display_jump_nav($jump_nav, $this->short_base_url . 'P=templates');
		
		// Loop through the Template Groups.
		foreach ($groups->result AS $group)
		{
			$edit_prefs = BASE . AMP . 'C=templates' . AMP . 'M=edit_preferences' . AMP .
				'id=' . $group['group_id'] . AMP . 'tgpref=' . $group['group_id'];
			
			$c .= $DSP->table_open(
				array(
					'id' => "group-{$group['group_id']}",
					'class' => 'tableBorder',
					'border' => '0',
					'style' => 'width : 100%; margin-bottom : 1.5em'
				)
			);
			
			$c .= $DSP->tr();
			$c .= $DSP->td('tableHeading', '', '3');
			$c .= $group['group_name'];
			$c .= $DSP->td_c();
			
			$c .= $DSP->td('tableHeading', '', '2', '', '', 'right');
			$c .= $DSP->anchor($edit_prefs, $LANG->line('template_edit_prefs'), 'style="font-weight: normal; color: #EEF4F9;"');
			$c .= $DSP->td_c();
			$c .= $DSP->tr_c();
			
			// The Templates.
			// Note: attempts to insert this directly into the SQL string throw an error in PHP4.
			$group_id = $group['group_id'];
			$sql = "SELECT
				template_id,
				template_name,
				save_template_file,
				template_type,
				cache,
				refresh,
				allow_php,
				php_parse_location
				FROM exp_templates
				WHERE group_id = '$group_id'
				AND site_id = '$this->site_id'
				ORDER BY template_name ASC";
				
			$templates = $DB->query($sql);
			
			if ($templates->num_rows == 0)
			{
				// This should be impossible (index will always exist), but just in case...
				$c .= $DSP->tr();
				$c .= $DSP->td('', '', '5');
				$c .= $DSP->qdiv('itemWrapper', $LANG->line('templates_no_fields'));
				$c .= $DSP->td_c();
				$c .= $DSP->tr_c();
			}
			else
			{
				$td_style = 'tableCellOne';
				
				// Headings				
				$c .= $DSP->tr();				
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('template_name')));
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('template_type')), '20%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('template_file')), '15%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('template_cache')), '20%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('template_php')), '15%');
				$c .= $DSP->tr_c();
				
				// Information for each Template.	
				$td_style = 'tableCellTwo';		
				foreach ($templates->result AS $template)
				{				
					$edit_prefs = BASE . AMP . 'C=templates' . AMP . 'M=edit_template' . AMP .
						'id=' . $template['template_id'] . AMP . 'tgpref=' . $group['group_id'];
						
					$c .= $DSP->tr();
					
					// Name.
					$c .= $DSP->table_qcell($td_style, $DSP->anchor($edit_prefs, $template['template_name']));
					
					// Type.
					if (isset($this->template_types[$template['template_type']]))
					{
						$c .= $DSP->table_qcell($td_style, $this->template_types[$template['template_type']]);
					}
					else
					{
						$c .= $DSP->table_qcell($td_style, $LANG->line('template_type_unknown'));
					}
					
					// Saved as a file?
					$c .= $DSP->table_qcell($td_style, ($template['save_template_file'] == 'y') ? $LANG->line('yes') : $LANG->line('no'));
					
					// Cached?
					$c .= $DSP->table_qcell($td_style,
						($template['cache'] == 'y') ? $LANG->line('yes') . ' (' . $template['refresh'] . ' ' . $LANG->line('minutes') . ')' : $LANG->line('no'));
					
					// Allow PHP parsing?
					$c .= $DSP->td($td_style);
					if ($template['allow_php'] == 'y')
					{
						$c .= $LANG->line('yes') . ' (' . ($template['php_parse_location'] == 'o' ? $LANG->line('output') : $LANG->line('input')) . ')';
					}
					else
					{
						$c .= $LANG->line('no');
					}
					
					// Close the PHP parsing table cell.
					$c .= $DSP->td_c();
					
					// Close the table row.
					$c .= $DSP->tr_c();
				}
				
				// Close the Template Group table.
				$c .= $DSP->table_close();
			}
		}
		
		$this->_generate_ui(SL_DEVINFO_TEMPLATES, $meta_title, $c);
	}
	
	
	/**
	 * Renders the categories in the specified group, with the specified parent.
	 * @access  private
	 * @param 	string 		$group_id 		A category group ID.
	 * @param		string 		$parent_id		A category ID (or 0).
	 * @param 	string		$prefix				A string to place in front of the category, to indicate nesting.
	 * @return 	string 		A content string.
	 */
	function _display_weblog_categories($group_id, $parent_id, $prefix)
	{
		global $DB, $PREFS;
		
		$c = '';
		
		$img_root			= '<img border="0" alt="" title="" height="14" src="' . $PREFS->ini('theme_folder_url', 1) . 'cp_global_images/';
		$nested_arrow = $img_root . 'cat_marker.gif" width="18" />';
		$spacer				= $img_root . 'clear.gif" width="24" />';
		$thin_spacer	= $img_root . 'clear.gif" width="1" />';
		
		$cats = $DB->query("SELECT cat_id, cat_name, parent_id
			FROM exp_categories
			WHERE group_id = '$group_id'
			AND parent_id = '$parent_id'
			AND site_id = '$this->site_id'
			ORDER BY cat_order ASC");
			
		if ($cats->num_rows > 0)
		{
			foreach ($cats->result AS $cat)
			{
				$c .= ($prefix != '') ? $prefix . $nested_arrow : $prefix;
				$c .= '(ID: ' . $cat['cat_id'] . ') ' . $cat['cat_name'] . $thin_spacer . '<br />';
				$c .= $this->_display_weblog_categories($group_id, $cat['cat_id'], $prefix . $spacer);
			}
		}
		
		return $c;
	}
	
	
	/**
	 * Renders the specified category group(s). Called from display_weblogs.
	 * @access  private
	 * @param 	string 		$group_ids 		A pipe delimited string of category group IDs.
	 * @return 	string 		A content string.
	 */
	function _display_weblog_category_groups($group_ids)
	{
		global $DB, $LANG, $DSP;
		
		$c = "<p><strong>" . $LANG->line('weblog_categories') . ":&nbsp;&nbsp;</strong></p>";
		
		if ( ! $group_ids)
		{
			// We do this whole table thing, just to ensure that everything lines up neatly.
			// Y'know, like it's 1996 all over again.
			$c .= $DSP->table_open(array('border' => '0'));
			$c .= $DSP->tr();
			$c .= $DSP->td('', '', '', '', 'top');
			$c .= "<p>" . $LANG->line('weblog_no_categories') . "</p>";
			$c .= $DSP->td_c();
			$c .= $DSP->tr_c();
			$c .= $DSP->table_close();
			
			return $c;
		}
		
		// If we've got to here, we have category groups to process.		
		$cat_groups = explode('|', $group_ids);
		
		$c .= $DSP->table_open(array('border' => '0'));
		$c .= $DSP->tr();
		
		if (count($cat_groups) > 0)
		{			
			// Loop through the category groups.
			foreach ($cat_groups AS $group_id)
			{				
				$edit_url = BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=category_editor' . AMP . 'group_id=' . $group_id;
				
				$group_name = $DB->query("SELECT group_name
					FROM exp_category_groups
					WHERE group_id = '$group_id'
					AND site_id = '$this->site_id'");
				
				if ($group_name->num_rows !== 1)
				{
					// Something has gone squiffy, and we can't find the category group.
					// Just continue to the next category group.
					continue;
				}
				
				// Open the table cell for this category group.
				$c .= $DSP->td('', '', '', '', 'top');
				
				// Output the category group name.
				$c .= "<p><strong>" . $DSP->anchor($edit_url, $group_name->row['group_name']) . "</strong></p>";
				$c .= '<p>';
				
				// Display the categories for this group.
				$cats = $this->_display_weblog_categories($group_id, 0, '');				
				$c .= ($cats == '') ? $LANG->line('weblog_no_categories') : $cats;
				
				// End of the categories for this group.
				$c .= '</p>';
				$c .= $DSP->td_c();
				
				// Gutter.
				$c .= $DSP->table_qcell('', '', '25px');
			}
		}
		else
		{
			$c .= $DSP->td('', '', '', '', 'top');
			$c .= '<p>' . $LANG->line('weblog_no_categories') . '</p>';
			$c .= $DSP->td_c();
		}	
		
		$c .= $DSP->tr_c();
		$c .= $DSP->table_close();		
		
		return $c;
	}
	
	
	/**
	 * Renders the specified status group. Called from display_weblogs.
	 * @access  private
	 * @param 	string 		$group_id 		A status group ID.
	 * @return 	string 		A content string.
	 */
	function _display_weblog_statuses($group_id)
	{
		global $DB, $LANG, $DSP;
		
		$c = "<p><strong>" . $LANG->line('weblog_statuses') . ":&nbsp;&nbsp;</strong></p>";
		
		if ( ! $group_id)
		{
			// We do this whole table thing, just to ensure that everything lines up neatly.
			// Y'know, like it's 1996 all over again.
			$c .= $DSP->table_open(array('border' => '0'));
			$c .= $DSP->tr();
			$c .= $DSP->td('', '', '', '', 'top');
			$c .= "<p>" . $LANG->line('weblog_no_statuses') . "</p>";
			$c .= $DSP->td_c();
			$c .= $DSP->tr_c();
			$c .= $DSP->table_close();
			
			return $c;
		}
		
		// If we've got to here, we have a status ID to process.		
		$c .= $DSP->table_open(array('border' => '0'));
		$c .= $DSP->tr();
		$c .= $DSP->td('', '', '', '', 'top');
		
		$edit_url = BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=status_editor' . AMP . 'group_id=' . $group_id;
		
		$group_name = $DB->query("SELECT group_name
			FROM exp_status_groups
			WHERE group_id = '$group_id'
			AND site_id = '$this->site_id'");
				
		if ($group_name->num_rows == 1)
		{
			// Output the status group name.
			$c .= "<p><strong>" . $DSP->anchor($edit_url, $group_name->row['group_name']) . "</strong></p>";
			$c .= '<p>';
			
			// Retrieve the statuses from this status group.
			$statuses = $DB->query("SELECT status_id, status
				FROM exp_statuses
				WHERE group_id = '{$group_id}'
				AND site_id = '$this->site_id'
				ORDER BY status_order ASC");			
			
			if ($statuses->num_rows == 0)
			{
				$c .= $LANG->line('weblog_no_statuses');
			}
			else
			{
				foreach ($statuses->result AS $s)
				{
					$c .= '(ID: ' . $s['status_id'] . ') ' . $s['status'] . '<br />';
				}
			}

			// Close the status "list" paragraph.
			$c .= '</p>';
		}
		
		// Close the table.
		$c .= $DSP->td_c();
		$c .= $DSP->tr_c();
		$c .= $DSP->table_close();	
		
		return $c;
	}
	
	
	/**
	 * Renders the "weblogs information" page.
	 * @access	private
	 */
	function _display_weblogs()
	{
		global $DSP, $LANG, $DB;
		
		// Check whether we need to jump to a page anchor. This must be done first.
		$this->_check_for_jump(BASE . '&C=modules&M=Sl_developer_info&P=weblogs');
		
		// Browser title.
		$meta_title = $LANG->line('weblogs_meta_title');
		
		// Breadcrumbs.
		$this->_generate_breadcrumbs(array($LANG->line('weblogs_title') => ''), FALSE, TRUE);
		$this->_generate_action_button($LANG->line('weblogs_action_link'), BASE . AMP . 'C=admin&area=weblog_administration');
		
		// Content.
		$c = '';
		
		// Page heading.
		$c .= $DSP->heading($LANG->line('weblogs_title'));
		
		// Retrieve the Weblogs.	
		$weblogs = $DB->query("SELECT weblog_id, blog_name, blog_title, field_group, cat_group, status_group
			FROM exp_weblogs
			WHERE site_id = '$this->site_id'
			ORDER BY blog_title ASC");
		
		// If we have no Weblogs, display a message to that effect, and leave.
		if ($weblogs->num_rows == 0)
		{
			$c .= $DSP->qdiv('itemWrapper', $LANG->line('weblogs_no_weblogs'));
			$this->_generate_ui(SL_DEVINFO_WEBLOGS, $meta_title, $c);
			return;
		}
		
		// Create the "jump to" navigation.
		foreach ($weblogs->result AS $blog)
		{
			$jump_nav[] = array('id' => "blog-{$blog['weblog_id']}", 'title' => "{$blog['blog_title']} ({$blog['blog_name']})");
		}
		
		$c .= $this->_display_jump_nav($jump_nav, $this->short_base_url . 'P=weblogs');
		
		// Display the Weblog information.
		foreach ($weblogs->result AS $blog)
		{
			$edit_prefs = BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=blog_prefs' . AMP . 'weblog_id=' . $blog['weblog_id'];
			$edit_groups = BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=group_prefs' . AMP . 'weblog_id=' . $blog['weblog_id'];
			$edit_field_group = BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=field_editor' . AMP . 'group_id=' . $blog['field_group'];
			
			$c .= $DSP->table_open(array('id' => "blog-{$blog['weblog_id']}", 'class' => 'tableBorder', 'border' => '0', 'style' => 'width : 100%; margin-bottom : 2em'));
			$c .= $DSP->tr();
			$c .= $DSP->td('tableHeading', '', '3');
			$c .= $blog['blog_title'] . '<br /><span style="font-weight : normal;">Short Name: ' . $blog['blog_name'] . ', Weblog ID: ' . $blog['weblog_id'] . '</span>';
			$c .= $DSP->td_c();
			
			$c .= $DSP->td('tableHeading', '', '3', '', '', 'right');
			$c .= $DSP->anchor($edit_prefs, $LANG->line('weblog_edit_prefs'), 'style="font-weight: normal; color: #EEF4F9;"');
			$c .= NBS . NBS . '<span style="font-weight: normal; color: #6C8494">|</span>' . NBS . NBS;
			$c .= $DSP->anchor($edit_groups, $LANG->line('weblog_edit_groups'), 'style="font-weight: normal; color: #EEF4F9;"');
			$c .= NBS . NBS . '<span style="font-weight: normal; color: #6C8494">|</span>' . NBS . NBS;
			$c .= $DSP->anchor($edit_field_group, $LANG->line('weblog_edit_field_group'), 'style="font-weight: normal; color: #EEF4F9;"');
			$c .= $DSP->td_c();
			$c .= $DSP->tr_c();

			// Categories and Statuses.
			$c .= $DSP->tr();
			$c .= $DSP->td('', '', '6');
			$c .= "<div class='box' style='border-width : 0 0 1px 0; margin : 0; padding : 10px 5px'>";
			
			$c .= $DSP->table_open(array('border' => '0'));
			$c .= $DSP->tr();
			
			$c .= $DSP->td('', '', '', '', 'top');
			$c .= $this->_display_weblog_category_groups($blog['cat_group']);
			$c .= $DSP->td_c();
			
			$c .= $DSP->table_qcell('', '', '50px');
			
			$c .= $DSP->td('', '', '', '', 'top');
			$c .= $this->_display_weblog_statuses($blog['status_group']);
			$c .= $DSP->td_c();
			
			$c .= $DSP->tr_c();
			$c .= $DSP->table_close();

			$c .= "</div>";
			$c .= $DSP->td_c();
			$c .= $DSP->tr_c();
			
			// The Weblog fields.
			// Note: attempts to insert this directly in the SQL throw an error in PHP4.
			$group_id = $blog['field_group'];
			
			$fields = $DB->query("SELECT field_id, field_name, field_label, field_type, field_fmt, field_required
				FROM exp_weblog_fields
				WHERE group_id = '$group_id'
				AND site_id = '$this->site_id'
				ORDER BY field_order ASC");
			
			if ($fields->num_rows == 0)
			{
				// No fields.
				$c .= $DSP->tr();
				$c .= $DSP->td('tableCellTwo', '', '6');
				$c .= $DSP->qdiv('itemWrapper', $LANG->line('weblog_no_fields'));
				$c .= $DSP->td_c();
				$c .= $DSP->tr_c();
			}
			else
			{				
				$td_style = 'tableCellOne';
				
				// Headings
				$c .= $DSP->tr();
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('weblog_field_id')), '5%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('weblog_field_name')), '30%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('weblog_field_label')), '25%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('weblog_field_type')), '15%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('weblog_field_format')), '15%');
				$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('weblog_field_mandatory')));
				$c .= $DSP->tr_c();
				
				// Information for each Field.	
				$td_style = 'tableCellTwo';	
				$edit_url	= BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=edit_field' . AMP . 'field_id=';	
				foreach ($fields->result AS $f)
				{					
					$c .= $DSP->tr();
					
					// ID.
					$c .= $DSP->table_qcell($td_style, $f['field_id']);
					
					// Short name.
					$c .= $DSP->table_qcell($td_style, $DSP->anchor($edit_url . $f['field_id'], $f['field_name']));
					
					// Label.
					$c .= $DSP->table_qcell($td_style, $f['field_label']);
					
					// Type.
					if (isset($this->weblog_field_types[$f['field_type']]))
					{
						$c .= $DSP->table_qcell($td_style, $this->weblog_field_types[$f['field_type']]);
					}
					else
					{
						$c .= $DSP->table_qcell($td_style, ucwords($f['field_type']));
					}
					
					// Format.
					$c .= $DSP->table_qcell($td_style, ucwords($f['field_fmt']));
					
					// Mandatory?
					$c .= $DSP->table_qcell($td_style, ($f['field_required'] == 'y') ? $LANG->line('yes') : $LANG->line('no'));
					
					// Close the table row.
					$c .= $DSP->tr_c();
				}
			}
			
			// Close the Weblog table.
			$c .= $DSP->table_close();
		}
		
		$this->_generate_ui(SL_DEVINFO_WEBLOGS, $meta_title, $c);
	}
	
	
	/**
	 * Renders the "files information" page.
	 * @access	private
	 */
	function _display_files()
	{
		global $DSP, $LANG, $DB;
		
		// Browser title.
		$meta_title = $LANG->line('files_meta_title');
		
		// Breadcrumbs.
		$this->_generate_breadcrumbs(array($LANG->line('files_title') => ''), FALSE, TRUE);
		$this->_generate_action_button($LANG->line('files_action_link'), BASE . AMP . 'C=admin&M=blog_admin&P=upload_prefs');
		
		// Content.
		$c = '';
		
		// Page heading.
		$c .= $DSP->heading($LANG->line('files_title'));
		
		// List the File Upload Locations.
		$locations = $DB->query("SELECT id, name, server_path, url, allowed_types, max_size
			FROM exp_upload_prefs
			WHERE site_id = '$this->site_id'
			ORDER BY id ASC");
		
		// If we have no File Upload Locations, display a message that effect, and leave.
		if ($locations->num_rows == 0)
		{
			$c .= $DSP->qdiv('itemWrapper', $LANG->line('files_no_locations'));
			$this->_generate_ui(SL_DEVINFO_FILES, $meta_title, $c);
			return;
		}
		
		// Open the table.
		$c .= $DSP->table_open(array('class' => 'tableBorder', 'border' => '0', 'style' => 'width : 100%; margin-bottom : 1.5em;'));
		
		$td_style = 'tableHeading';
		
		// Headings
		$c .= $DSP->tr();
		$c .= $DSP->table_qcell($td_style, $LANG->line('file_location_id'));
		$c .= $DSP->table_qcell($td_style, $LANG->line('file_location_name'));
		$c .= $DSP->table_qcell($td_style, $LANG->line('file_location_path'));
		$c .= $DSP->table_qcell($td_style, $LANG->line('file_location_url'));
		$c .= $DSP->table_qcell($td_style, $LANG->line('file_location_types'));
		$c .= $DSP->table_qcell($td_style, $LANG->line('file_location_size'));
		$c .= $DSP->tr_c();
		
		// Loop through the File Upload Locations.
		$row = 0;
		foreach ($locations->result AS $loc)
		{
			$td_style = ($row++ % 2) ? 'tableCellOne' : 'tableCellTwo';
			
			$edit_prefs = BASE . AMP . 'C=admin' . AMP . 'M=blog_admin' . AMP . 'P=edit_upload_pref' . AMP . 'id=' . $loc['id'];
					
			$c .= $DSP->tr();
			$c .= $DSP->table_qcell($td_style, $loc['id']);						// ID.
			$c .= $DSP->table_qcell($td_style, $DSP->anchor($edit_prefs, $loc['name']));	// Name.
			$c .= $DSP->table_qcell($td_style, $loc['server_path']);	// Server Path.
			$c .= $DSP->table_qcell($td_style, $loc['url']);					// URL.
			
			// Allowed Types.
			if (isset($this->file_types[$loc['allowed_types']]))
			{
				$c .= $DSP->table_qcell($td_style, $this->file_types[$loc['allowed_types']]);
			}
			else
			{
				$c .= $DSP->table_qcell($td_style, $LANG->line('file_type_unknown'));
			}
			
			// Maximum File Size.
			$c .= $DSP->table_qcell($td_style, $loc['max_size'] == '' ? '' : round((intval($loc['max_size']) / 1024), 2));
			
			// Close the table row.
			$c .= $DSP->tr_c();
		}
		
		// Close the table.
		$c .= $DSP->table_close();
		
		$this->_generate_ui(SL_DEVINFO_FILES, $meta_title, $c);
	}
	
	
	/**
	 * Renders the "PREFS object" page.
	 * @access  private
	 */
	function _display_prefs()
	{
	  global $PREFS, $LANG, $DSP;
	  
	  // Browser title.
		$meta_title = $LANG->line('prefs_meta_title');
		
		// Breadcrumbs.
		$this->_generate_breadcrumbs(array($LANG->line('prefs_title') => ''), FALSE, TRUE);
		
		// Content.
		$c = '';
		
		// Page heading.
		$c .= $DSP->heading($LANG->line('prefs_title'));
		
		// Create the "jump to" navigation.
		foreach ($PREFS AS $group_id => $group_array)
		{
			$jump_nav[] = array('id' => 'prefs-' . $group_id, 'title' => '$PREFS->' . $group_id);
		}
		
		$c .= $this->_display_jump_nav($jump_nav, $this->short_base_url . 'P=prefs');
		
		// Loop through the properties of the $PREFS object.
		foreach ($PREFS AS $group_id => $group_array)
		{
		  // Open the table.		  
  		$c .= $DSP->table_open(array('id' => 'prefs-' . $group_id, 'class' => 'tableBorder', 'border' => '0', 'style' => 'width : 100%; margin-bottom : 1.5em;'));

  		$td_style = 'tableHeading';
  		
  		// Main table heading.
  		$c .= $DSP->tr();
  		$c .= $DSP->td($td_style, '', '2') . '$PREFS->' . $group_id . $DSP->td_c();
  		$c .= $DSP->tr_c();

      // Do we have information to display?
  		if (is_array($group_array) && count($group_array))
  		{
  		  // Column headings.
    		$td_style = 'tableCellOne';
    		$c .= $DSP->tr();
    		$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('prefs_id')), '35%');
    		$c .= $DSP->table_qcell($td_style, $DSP->qdiv('defaultBold', $LANG->line('prefs_value')));
    		$c .= $DSP->tr_c();
    		
  		  // Sort the keys into alphabetical order.
  		  ksort($group_array);
  		  
  		  // Loop through the properties.
  		  $td_style = 'tableCellTwo';
  		  foreach ($group_array AS $property_id => $property_value)
    		{
    		  $c .= $DSP->tr();
    			$c .= $DSP->table_qcell($td_style, $property_id);
    			$c .= $DSP->table_qcell($td_style, $property_value);
    			$c .= $DSP->tr_c();
    		}
  		}
  		else
  		{
  		  // No properties.
  		  $td_style = 'tableCellTwo';
  		  
				$c .= $DSP->tr();
				$c .= $DSP->td($td_style, '', '2');
				$c .= $DSP->qdiv('itemWrapper', $LANG->line('prefs_no_properties'));
				$c .= $DSP->td_c();
				$c .= $DSP->tr_c(); 
  		}

  		// Close the table.
  		$c .= $DSP->table_close();
		}
		
		$this->_generate_ui(SL_DEVINFO_PREFS, $meta_title, $c);
	}
	
	
	/**
	 * Renders the module home page.
	 * @access	private
	 */
	function _display_home()
	{
		global $DSP, $LANG;
		
		if ( ! $DSP->allowed_group('can_access_admin'))
		{
			return $DSP->no_access_message();
		}
		
		// Write out the page title.
		$c = $DSP->div('roundBox');
		$c .= '<b class="roundBoxTop"><b class="roundBox1"></b><b class="roundBox2"></b><b class="roundBox3"></b><b class="roundBox4"></b></b>';
		$c .= $DSP->div('', '', '', '', ' style="padding : 0 0 0 10px"');
		
		$c .= $DSP->heading($LANG->line('home_title') . ' <small>v' . $this->version . '</small>', 2);
		$c .= $LANG->line('home_intro');
		
		// Create the navigation list.
		$c .= '<ul>';
		
		foreach ($this->nav as $s)
		{
			$c .= '<li>';
			$c .= $DSP->anchor($this->_generate_url($s['page']), $s['title'], ' title="' . $s['title'] . '"');
			$c .= '</li>';
		}
		
		$c .= $DSP->div_c();
		$c .= "<b class='roundBoxBottom'><b class='roundBox4'></b><b class='roundBox3'></b><b class='roundBox2'></b><b class='roundBox1'></b></b>";
		$c .= $DSP->div_c();
			
		// Build the UI.
		$this->_generate_breadcrumbs(array(), TRUE, FALSE);
		$this->_generate_ui(NULL, $LANG->line('home_title'), $c);
	}
	
	
	/**
	 * Installs the module.
	 */
	function sl_developer_info_module_install()
	{
		global $DB;
		
		// Register the module.
		$sql[] = $DB->insert_string('exp_modules', array(
				'module_id'	 			=> '',
				'module_name'			=> SL_DEVINFO_CLASS,
				'module_version'	=> $this->version,
				'has_cp_backend'	=> 'y'
				));
		
		// Run all the SQL queries.
		foreach ($sql AS $query)
		{
			$DB->query($query);
		}
				
		return TRUE;
	}
	
	
	/**
	 * Deinstalls the module.
	 */
	function sl_developer_info_module_deinstall()
	{
		global $DB;  
		
		// Retrieve the module ID.
		$mod = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = '" . SL_DEVINFO_CLASS . "'");
		  
		// Delete all the database records associated with this module.
		$sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '" . $mod->row['module_id'] . "'";
		$sql[] = "DELETE FROM exp_modules WHERE module_name = '" . SL_DEVINFO_CLASS . "'";
		
		foreach ($sql AS $query)
		{
			$DB->query($query);
		}
		
		return TRUE;
	}	
	
}

?>