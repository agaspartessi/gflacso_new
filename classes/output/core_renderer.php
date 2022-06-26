<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_gflacso_new\output;

use moodle_url;
use context_course;
use context_header;
use stdClass;
use core_text;
use html_writer;
use tabobject;
use completion_info;
use format_onetopic;
use action_menu;
use action_menu_filler;
use action_menu_link_secondary;
use navigation_node;
use action_link;
use pix_icon;
use theme_boost\output\core_renderer as core_renderer_base;

defined('MOODLE_INTERNAL') || die;

/**
 *
 * @package    theme_gflacso_new
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class core_renderer extends core_renderer_base {

    /**
     * We don't like these...
     *
     */
    public function edit_button(moodle_url $url) {
        return '';
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $PAGE;
        global $OUTPUT;
        global $CFG;


        $edit   = optional_param('edit', null, PARAM_BOOL);  
        // Toggle the editing state and switches
		/*if ($PAGE->user_allowed_editing()) {

		    if ($edit !== null) {             // Editing state was specified
		        $USER->editing = $edit;       // Change editing state
		    } else {                          // Editing state is in session
	            if (!empty($USER->editing)) {
	                $edit = 1;
	            } else {
	                $edit = 0;
	            }
		    }
			*/
		    // Add button for editing page
	    	$params = array('edit' => !$edit);
		    
		    if (empty($edit)) {
		        $editstring = get_string('updatemymoodleon');
		    } else {
		        $editstring = get_string('updatemymoodleoff');
		    }

		    $url = new moodle_url("$CFG->wwwroot/my/index.php", $params);
			
/*
		} else {
		    $USER->editing = $edit = 0;
		}*/


		$context = $this->page->context;
		//var_dump($context);
		if ($context->contextlevel == 50 && $this->page->user_allowed_editing()) {
            // Add the turn on/off settings

            if ($this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)) {
                // We are on the course page, retain the current page params e.g. section.
                $baseurl = clone($this->page->url);
                $baseurl->param('sesskey', sesskey());
            } else {
                // Edit on the main course page.
                $baseurl = new moodle_url('/course/view.php', array('id'=>$course->id, 'return'=>$this->page->url->out_as_local_url(false), 'sesskey'=>sesskey()));
            }

            $editurl = clone($baseurl);
            if ($this->page->user_is_editing()) {
                $editurl->param('edit', 'off');
                $editstring = get_string('turneditingoff');
            } else {
                $editurl->param('edit', 'on');
                $editstring = get_string('turneditingon');
            }
            $button = $OUTPUT->single_button($editurl, $editstring);

        }


        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        $header->button =  isset($button) ?  $button : null;
        
        

        return $this->render_from_template('core/full_header', $header);
    }


    /**
      * Renders the header bar.
      *
      * @param context_header $contextheader Header bar object.
      * @return string HTML for the header bar.
      */
    protected function render_context_header(context_header $contextheader) {

        $showheader = empty($this->page->layout_options['nocontextheader']);
        if (!$showheader) {
            return '';
        }

        // All the html stuff goes here.
        $html = html_writer::start_div('page-context-header');

        // Image data.
        if (isset($contextheader->imagedata)) {
            // Header specific image.
            $html .= html_writer::div($contextheader->imagedata, 'page-header-image');
        }

        // Headings.
        if (!isset($contextheader->heading)) {
            $heading_text = $this->page->heading;
            $headings = $this->heading($this->page->heading, $contextheader->headinglevel);
        } else {
            $heading_text = $contextheader->heading;
            $headings = $this->heading($contextheader->heading, $contextheader->headinglevel);
        }

        $context = $this->page->context;
        if ($context && $context->contextlevel == 50) {
            $size = 'small';
            if (strlen($heading_text) < 90) 
                $size = 'medium';
            if (strlen($heading_text) < 45) 
                $size = 'big';

                
            $html .= html_writer::tag('div', $headings, array('class' => 'page-header-headings titulo_curso '.$size));
        }
        else
            $html .= html_writer::tag('div', $headings, array('class' => 'page-header-headings'));

        // Buttons.
        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('btn-group header-button-group');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include js for messaging.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    if ($button['buttontype'] === 'message') {
                        \core_message\helper::messageuser_requirejs();
                    }
                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                        'class' => 'iconsmall',
                        'role' => 'presentation'
                    ));
                    $image .= html_writer::span($button['title'], 'header-button-title');
                } else {
                    $image = html_writer::empty_tag('img', array(
                        'src' => $button['formattedimage'],
                        'role' => 'presentation'
                    ));
                }
                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div();

        return $html;
    }

    /**
     * This is an optional menu that can be added to a layout by a theme. It contains the
     * menu for the course administration, only on the course main page.
     *
     * @return string
     */
    public function context_header_settings_menu() {
        $context = $this->page->context;
        $menu = new action_menu();

        $items = $this->page->navbar->get_items();
        $currentnode = end($items);

        $showcoursemenu = false;
        $showfrontpagemenu = false;
        $showusermenu = false;

        // We are on the course home page.
        if (($context->contextlevel == CONTEXT_COURSE) &&
                !empty($currentnode) &&
                ($currentnode->type == navigation_node::TYPE_COURSE || $currentnode->type == navigation_node::TYPE_SECTION)) {
            $showcoursemenu = true;
        }

        $courseformat = course_get_format($this->page->course);
        // This is a single activity course format, always show the course menu on the activity main page.
        if ($context->contextlevel == CONTEXT_MODULE &&
                !$courseformat->has_view_page()) {

            $this->page->navigation->initialise();
            $activenode = $this->page->navigation->find_active_node();
            // If the settings menu has been forced then show the menu.
            if ($this->page->is_settings_menu_forced()) {
                $showcoursemenu = true;
            } else if (!empty($activenode) && ($activenode->type == navigation_node::TYPE_ACTIVITY ||
                            $activenode->type == navigation_node::TYPE_RESOURCE)) {

                // We only want to show the menu on the first page of the activity. This means
                // the breadcrumb has no additional nodes.
                if ($currentnode && ($currentnode->key == $activenode->key && $currentnode->type == $activenode->type)) {
                    $showcoursemenu = true;
                }
            }
        }

        // This is the site front page.
        if ($context->contextlevel == CONTEXT_COURSE &&
                !empty($currentnode) &&
                $currentnode->key === 'home') {
            $showfrontpagemenu = true;
        }

        // This is the user profile page.
        if ($context->contextlevel == CONTEXT_USER &&
                !empty($currentnode) &&
                ($currentnode->key === 'myprofile')) {
            $showusermenu = true;
        }

        if ($showfrontpagemenu) {
            $settingsnode = $this->page->settingsnav->find('frontpage', navigation_node::TYPE_SETTING);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

                // We only add a list to the full settings menu if we didn't include every node in the short menu.
                if ($skipped) {
                    $text = get_string('morenavigationlinks');
                    $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
                    $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
                    $menu->add_secondary_action($link);
                }
            }
        } else if ($showcoursemenu) {
            $settingsnode = $this->page->settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);

            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

                // We only add a list to the full settings menu if we didn't include every node in the short menu.
                if ($skipped) {
                    $text = get_string('morenavigationlinks');
                    $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
                    $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
                    $menu->add_secondary_action($link);
                }
            }
        } else if ($showusermenu) {
            // Get the course admin node from the settings navigation.
            $settingsnode = $this->page->settingsnav->find('useraccount', navigation_node::TYPE_CONTAINER);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $this->build_action_menu_from_navigation($menu, $settingsnode);
            }
        }

        return $this->render($menu);
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );

        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $options['avatarsize'] = 100;
        $opts = user_get_user_navigation_info($user, $this->page, $options);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext mr-1') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, '', null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }

}


/**
* Course Format Topics Renderer
*/

require_once($CFG->dirroot.'/course/format/topics/renderer.php');

class format_topics_renderer extends \format_topics_renderer {

    /**
     * Generate the display of the header part of a section before
     * course modules are included
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param bool $onsectionpage true if being printed on a single-section page
     * @param int $sectionreturn The section to return to after an action
     * @return string HTML to output.
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn=null) {
        global $PAGE;

        $o = '';
        $currenttext = '';
        $sectionstyle = '';

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } else if (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }

        $o.= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
            'class' => 'section main clearfix'.$sectionstyle, 'role'=>'region',
            'aria-label'=> get_section_name($course, $section)));

        // Create a span that contains the section title to be used to create the keyboard section move menu.
        $o .= html_writer::tag('span', get_section_name($course, $section), array('class' => 'hidden sectionname'));

        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
        $o.= html_writer::start_tag('div', array('class' => 'content'));

        // When not on a section page, we display the section titles except the general section if null
        $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));

        // When on a section page, we only display the general section title, if title is not the default one
        $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));

        $classes = ' accesshide';
        if ($hasnamenotsecpg || $hasnamesecpg) {
            $classes = '';
        }

  if ((string)$section->name !== ''){
    $sectionname = html_writer::tag('span', $this->section_title($section, $course));
    $o.= $this->output->heading($sectionname, 3, 'sectionname' . $classes);
  }

        $o.= html_writer::start_tag('div', array('class' => 'summary'));
        $o.= $this->format_summary_text($section);
        $o.= html_writer::end_tag('div');

        $context = context_course::instance($course->id);
        $o .= $this->section_availability_message($section,
                has_capability('moodle/course:viewhiddensections', $context));

        return $o;
    }

}

/**
* Course Format OneTopic Renderer
*/

require_once($CFG->dirroot.'/course/format/onetopic/renderer.php');

class format_onetopic_renderer extends \format_onetopic_renderer {

       /**
     * Output the html for a single section page .
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param array $mods used for print_section()
     * @param array $modnames used for print_section()
     * @param array $modnamesused used for print_section()
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE, $OUTPUT;

        $PAGE->requires->js('/course/format/topics/format.js');
        $PAGE->requires->js('/course/format/onetopic/format.js');

        $realcoursedisplay = $course->realcoursedisplay;
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();
        $course->realcoursedisplay = $realcoursedisplay;
        $sections = $modinfo->get_section_info_all();

        // Can we view the section in question?
        $context = context_course::instance($course->id);
        $canviewhidden = has_capability('moodle/course:viewhiddensections', $context);

        if (!isset($sections[$displaysection])) {
            // This section doesn't exist.
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);

        $formatdata = new stdClass();
        $formatdata->mods = $mods;
        $formatdata->modinfo = $modinfo;
        $this->_course = $course;
        $this->_format_data = $formatdata;

        // General section if non-empty and course_display is multiple.
        if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE) {
            $thissection = $sections[0];
            if ((($thissection->visible && $thissection->available) || $canviewhidden) &&
                    ($thissection->summary || $thissection->sequence || $PAGE->user_is_editing() ||
                    (string)$thissection->name !== '')) {
                echo $this->start_section_list();
                echo $this->section_header($thissection, $course, true);

                if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_NOT) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                } else if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_LIST) {
                    echo $this->custom_course_section_cm_list($course, $thissection, $displaysection);
                }

                echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);

                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        // Start single-section div.
        echo html_writer::start_tag('div', array('class' => 'single-section onetopic'));

        // Move controls.
        $canmove = false;
        if ($PAGE->user_is_editing() && has_capability('moodle/course:movesections', $context) && $displaysection > 0) {
            $canmove = true;
        }
        $movelisthtml = '';

        // Init custom tabs.
        $section = 0;

        $sectionmenu = array();
        $tabs = array();
        $inactivetabs = array();

        $defaulttopic = -1;

        $sectionsinfo = $modinfo->sections;

        while ($section <= $this->numsections) {

            if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE && $section == 0) {
                $section++;
                continue;
            }

            $thissection = $sections[$section];

            /* GENEOS - Pablo Velazquez
             * Display all non-empty sections when editing
             */
            $orphaned = false;
            $onlyRead = false;
            $empty = empty($modinfo->sections[$section]);
            if ( $empty  && !has_capability('moodle/course:update', $context)) {
                    $section++;
                    continue;
                }   
            else if ( $empty  ){
                $orphaned = true;
            }       
            else if (!$thissection->visible)
                $orphaned = true;

            if ( $orphaned && (!$PAGE->user_is_editing() or !has_capability('moodle/course:update', $context)) )
                $onlyRead = true;

            /*** FIN MODIFICACION GENEOS **/

            $showsection = true;
            if (!$thissection->visible || !$thissection->available) {
                $showsection = false;
            } else if ($section == 0 && !($thissection->summary || $thissection->sequence || $PAGE->user_is_editing())) {
                $showsection = false;
            }

            if (!$showsection) {
                $showsection = $canviewhidden || !$course->hiddensections;
            }

            if (isset($displaysection)) {
                if ($showsection) {

                    if ($defaulttopic < 0) {
                        $defaulttopic = $section;

                        if ($displaysection == 0) {
                            $displaysection = $defaulttopic;
                        }
                    }

                    $formatoptions = course_get_format($course)->get_format_options($thissection);

                    $sectionname = get_section_name($course, $thissection);

                    if ($displaysection != $section) {
                        $sectionmenu[$section] = $sectionname;
                    }

                    $customstyles = '';
                    $level = 0;
                    if (is_array($formatoptions)) {

                        if (!empty($formatoptions['fontcolor'])) {
                            $customstyles .= 'color: ' . $formatoptions['fontcolor'] . ';';
                        }

                        if (!empty($formatoptions['bgcolor'])) {
                            $customstyles .= 'background-color: ' . $formatoptions['bgcolor'] .';border-color: ' . $formatoptions['bgcolor'] . ';';
                        }

                        if (!empty($formatoptions['cssstyles'])) {
                            $customstyles .= $formatoptions['cssstyles'] . ';';
                        }

                        if (isset($formatoptions['level'])) {
                            $level = $formatoptions['level'];
                        }
                    }

                    if ($section == 0) {
                        $url = new moodle_url('/course/view.php', array('id' => $course->id, 'section' => 0));
                    } else {
                        $url = course_get_url($course, $section);
                    }

                    $specialstyle = 'tab_position_' . $section . ' tab_level_' . $level;
                    if ($course->marker == $section) {
                        $specialstyle = ' marker ';
                    }

                    

                    $newtab = new theme_gflacso_new_tabobject("tab_topic_" . $section, $url,
                                s($sectionname), s($sectionname));

                    $newtab->inactive = false;
                    $newtab->onlyRead = $onlyRead;
                    $newtab->custom_styles = $customstyles;


                    /*if ($orphaned){
                        $inactivetabs[]="tab_topic_" . $section;
                        $newtab->inactive = true;         
                    }*/

                    if (!$thissection->visible || !$thissection->available) {
                        $specialstyle .= ' dimmed ';
                        $newtab->custom_styles .= 'font-style: italic;color: #555 !important;';

                        if (!$canviewhidden) {
                            $inactivetabs[] = "tab_topic_" . $section;
                        }
                    }


                    if (is_array($formatoptions) && isset($formatoptions['level'])) {

                        if ($formatoptions['level'] == 0 || count($tabs) == 0) {
                            $tabs[] = $newtab;
                            $newtab->level = 1;
                        } else {
                            $parentindex = count($tabs) - 1;
                            if (!is_array($tabs[$parentindex]->subtree)) {
                                $tabs[$parentindex]->subtree = array();
                            } else if (count($tabs[$parentindex]->subtree) == 0) {
                                $tabs[$parentindex]->subtree[0] = clone($tabs[$parentindex]);
                                $tabs[$parentindex]->subtree[0]->id .= '_index';
                                $parentsection = $sections[$section - 1];
                                $parentformatoptions = course_get_format($course)->get_format_options($parentsection);
                                if ($parentformatoptions['firsttabtext']) {
                                    $firsttabtext = $parentformatoptions['firsttabtext'];
                                } else {
                                    $firsttabtext = get_string('index', 'format_onetopic');
                                }
                                $tabs[$parentindex]->subtree[0]->text = '<div class="tab_content tab_initial">' .
                                                                        $firsttabtext . "</div>";
                                $tabs[$parentindex]->subtree[0]->level = 2;

                                if ($displaysection == $section - 1) {
                                    $tabs[$parentindex]->subtree[0]->selected = true;
                                }
                            }
                            $newtab->level = 2;
                            $tabs[$parentindex]->subtree[] = $newtab;
                        }
                    } else {
                        $tabs[] = $newtab;
                    }

                    // Init move section list.
                    if ($canmove) {
                        if ($section > 0) { // Move section.
                            $baseurl = course_get_url($course, $displaysection);
                            $baseurl->param('sesskey', sesskey());

                            $url = clone($baseurl);

                            $url->param('move', $section - $displaysection);

                            // Define class from sublevels in order to move a margen in the left.
                            // Not apply if it is the first element (condition !empty($movelisthtml))
                            // because the first element can't be a sublevel.
                            $liclass = '';
                            if (is_array($formatoptions) && isset($formatoptions['level']) && $formatoptions['level'] > 0 &&
                                    !empty($movelisthtml)) {
                                $liclass = 'sublevel';
                            }

                            if ($displaysection != $section) {
                                $movelisthtml .= html_writer::tag('li', html_writer::link($url, $sectionname),
                                                array('class' => $liclass));
                            } else {
                                $movelisthtml .= html_writer::tag('li', $sectionname, array('class' => $liclass));
                            }
                        } else {
                            $movelisthtml .= html_writer::tag('li', $sectionname);
                        }
                    }
                    // End move section list.
                }
            }

            $section++;
        }

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $sections, $displaysection);
        $sectiontitle = '';

        if (!$course->hidetabsbar && count($tabs[0]) > 0) {

            if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {

                // Increase number of sections.
                $straddsection = get_string('increasesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php',
                    array('courseid' => $course->id,
                        'increase' => true,
                        'sesskey' => sesskey(),
                        'insertsection' => 0));
                $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
                $tabs[] = new tabobject("tab_topic_add", $url, $icon, s($straddsection));

            }

            $sectiontitle .= $OUTPUT->tabtree($tabs, "tab_topic_" . $displaysection, $inactivetabs);
        }

        echo $sectiontitle;

        if ($sections[$displaysection]->uservisible || $canviewhidden) {

            if ($course->realcoursedisplay != COURSE_DISPLAY_MULTIPAGE || $displaysection !== 0) {
                // Now the list of sections.
                echo $this->start_section_list();

                // The requested section page.
                $thissection = $sections[$displaysection];
                echo $this->section_header($thissection, $course, true);
                // Show completion help icon.
                $completioninfo = new completion_info($course);
                echo $completioninfo->display_help_icon();

                if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_NOT) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                } else if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_LIST) {
                    echo $this->custom_course_section_cm_list($course, $thissection, $displaysection);
                }

                echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        $sectionbottomnav .= html_writer::end_tag('div');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');

        if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {

            echo '<br class="utilities-separator" />';
            print_collapsible_region_start('move-list-box clearfix collapsible mform', 'course_format_onetopic_config_movesection',
                get_string('utilities', 'format_onetopic'), '', true);

            // Move controls.
            if ($canmove && !empty($movelisthtml)) {
                echo html_writer::start_div("form-item clearfix");
                    echo html_writer::start_div("form-label");
                        echo html_writer::tag('label', get_string('movesectionto', 'format_onetopic'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-setting");
                        echo html_writer::tag('ul', $movelisthtml, array('class' => 'move-list'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-description");
                        echo html_writer::tag('p', get_string('movesectionto_help', 'format_onetopic'));
                    echo html_writer::end_div();
                echo html_writer::end_div();
            }

            $baseurl = course_get_url($course, $displaysection);
            $baseurl->param('sesskey', sesskey());

            $url = clone($baseurl);

            global $USER, $OUTPUT;
            if (isset($USER->onetopic_da[$course->id]) && $USER->onetopic_da[$course->id]) {
                $url->param('onetopic_da', 0);
                $textbuttondisableajax = get_string('enable', 'format_onetopic');
            } else {
                $url->param('onetopic_da', 1);
                $textbuttondisableajax = get_string('disable', 'format_onetopic');
            }

            echo html_writer::start_div("form-item clearfix");
                echo html_writer::start_div("form-label");
                    echo html_writer::tag('label', get_string('disableajax', 'format_onetopic'));
                echo html_writer::end_div();
                echo html_writer::start_div("form-setting");
                    echo html_writer::link($url, $textbuttondisableajax);
                echo html_writer::end_div();
                echo html_writer::start_div("form-description");
                    echo html_writer::tag('p', get_string('disableajax_help', 'format_onetopic'));
                echo html_writer::end_div();
            echo html_writer::end_div();

            // Duplicate current section option.
            if (has_capability('moodle/course:manageactivities', $context)) {
                $urlduplicate = new moodle_url('/course/format/onetopic/duplicate.php',
                                array('courseid' => $course->id, 'section' => $displaysection, 'sesskey' => sesskey()));

                $link = new action_link($urlduplicate, get_string('duplicate', 'format_onetopic'));
                /*
                $link->add_action(new confirm_action(get_string('duplicate_confirm', 'format_onetopic'), null,
                    get_string('duplicate', 'format_onetopic')));
                */

                echo html_writer::start_div("form-item clearfix");
                    echo html_writer::start_div("form-label");
                        echo html_writer::tag('label', get_string('duplicatesection', 'format_onetopic'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-setting");
                        echo $this->render($link);
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-description");
                        echo html_writer::tag('p', get_string('duplicatesection_help', 'format_onetopic'));
                    echo html_writer::end_div();
                echo html_writer::end_div();
            }

            echo html_writer::start_div("form-item clearfix form-group row fitem");
                echo $this->change_number_sections($course, 0);
            echo html_writer::end_div();

            print_collapsible_region_end();
        }
    }

    /**
     * Output the html for a single section page .
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param array $mods used for print_section()
     * @param array $modnames used for print_section()
     * @param array $modnamesused used for print_section()
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page2($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE, $OUTPUT;;

        $PAGE->requires->js('/course/format/topics/format.js');
        $PAGE->requires->js('/course/format/onetopic/format.js');


        $realcoursedisplay = $course->realcoursedisplay;
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();
        $course->realcoursedisplay = $realcoursedisplay;
        $sections = $modinfo->get_section_info_all();

        // Can we view the section in question?
        $context = context_course::instance($course->id);
        $canviewhidden = has_capability('moodle/course:viewhiddensections', $context);

        if (!isset($sections[$displaysection])) {
            // This section doesn't exist.
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);

        $formatdata = new stdClass();
        $formatdata->mods = $mods;
        $formatdata->modinfo = $modinfo;
        $this->_course = $course;
        $this->_format_data = $formatdata;

        // General section if non-empty and course_display is multiple.
        if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE) {
            $thissection = $sections[0];
            if ((($thissection->visible && $thissection->available) || $canviewhidden) &&
                    ($thissection->summary || $thissection->sequence || $PAGE->user_is_editing() ||
                    (string)$thissection->name !== '')) {
                echo $this->start_section_list();
                echo $this->section_header($thissection, $course, true);

                if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_NOT) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                } else if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_LIST) {
                    echo $this->custom_course_section_cm_list($course, $thissection, $displaysection);
                }

                echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);

                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        // Start single-section div.
        echo html_writer::start_tag('div', array('class' => 'single-section onetopic'));

        // Move controls.
        $canmove = false;
        if ($PAGE->user_is_editing() && has_capability('moodle/course:movesections', $context) && $displaysection > 0) {
            $canmove = true;
        }
        $movelisthtml = '';

        // Init custom tabs.
        $section = 0;

        $sectionmenu = array();
        $tabs = array();
        $inactivetabs = array();

        $defaulttopic = -1;

        while ($section <= $this->numsections) {

            if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE && $section == 0) {
                $section++;
                continue;
            }

            $thissection = $sections[$section];

            $showsection = true;
            if (!$thissection->visible || !$thissection->available) {
                $showsection = false;
            } else if ($section == 0 && !($thissection->summary || $thissection->sequence || $PAGE->user_is_editing())) {
                $showsection = false;
            }

            if (!$showsection) {
                $showsection = $canviewhidden || !$course->hiddensections;
            }

            if (isset($displaysection)) {
                if ($showsection) {

                    if ($defaulttopic < 0) {
                        $defaulttopic = $section;

                        if ($displaysection == 0) {
                            $displaysection = $defaulttopic;
                        }
                    }

                    $formatoptions = course_get_format($course)->get_format_options($thissection);

                    $sectionname = get_section_name($course, $thissection);

                    if ($displaysection != $section) {
                        $sectionmenu[$section] = $sectionname;
                    }

                    $customstyles = '';
                    $level = 0;
                    if (is_array($formatoptions)) {

                        if (!empty($formatoptions['fontcolor'])) {
                            $customstyles .= 'color: ' . $formatoptions['fontcolor'] . ';';
                        }

                        if (!empty($formatoptions['bgcolor'])) {
                            $customstyles .= 'background-color: ' . $formatoptions['bgcolor'] . ';';
                        }

                        if (!empty($formatoptions['cssstyles'])) {
                            $customstyles .= $formatoptions['cssstyles'] . ';';
                        }

                        if (isset($formatoptions['level'])) {
                            $level = $formatoptions['level'];
                        }
                    }

                    if ($section == 0) {
                        $url = new moodle_url('/course/view.php', array('id' => $course->id, 'section' => 0));
                    } else {
                        $url = course_get_url($course, $section);
                    }

                    $specialstyle = 'tab_position_' . $section . ' tab_level_' . $level;
                    if ($course->marker == $section) {
                        $specialstyle = ' marker ';
                    }

                    if (!$thissection->visible || !$thissection->available) {
                        $specialstyle .= ' dimmed ';

                        if (!$canviewhidden) {
                            $inactivetabs[] = "tab_topic_" . $section;
                        }
                    }

                    $newtab = new tabobject("tab_topic_" . $section, $url,
                    '<div style="' . $customstyles . '" class="tab_content ' . $specialstyle . '">' .
                    '<span>' . $sectionname . "</span></div>", $sectionname);

                    if (is_array($formatoptions) && isset($formatoptions['level'])) {

                        if ($formatoptions['level'] == 0 || count($tabs) == 0) {
                            $tabs[] = $newtab;
                            $newtab->level = 1;
                        } else {
                            $parentindex = count($tabs) - 1;
                            if (!is_array($tabs[$parentindex]->subtree)) {
                                $tabs[$parentindex]->subtree = array();
                            } else if (count($tabs[$parentindex]->subtree) == 0) {
                                $tabs[$parentindex]->subtree[0] = clone($tabs[$parentindex]);
                                $tabs[$parentindex]->subtree[0]->id .= '_index';
                                $parentsection = $sections[$section - 1];
                                $parentformatoptions = course_get_format($course)->get_format_options($parentsection);
                                if ($parentformatoptions['firsttabtext']) {
                                    $firsttabtext = $parentformatoptions['firsttabtext'];
                                } else {
                                    $firsttabtext = get_string('index', 'format_onetopic');
                                }
                                $tabs[$parentindex]->subtree[0]->text = '<div class="tab_content tab_initial">' .
                                                                        $firsttabtext . "</div>";
                                $tabs[$parentindex]->subtree[0]->level = 2;

                                if ($displaysection == $section - 1) {
                                    $tabs[$parentindex]->subtree[0]->selected = true;
                                }
                            }
                            $newtab->level = 2;
                            $tabs[$parentindex]->subtree[] = $newtab;
                        }
                    } else {
                        $tabs[] = $newtab;
                    }

                    // Init move section list.
                    if ($canmove) {
                        if ($section > 0) { // Move section.
                            $baseurl = course_get_url($course, $displaysection);
                            $baseurl->param('sesskey', sesskey());

                            $url = clone($baseurl);

                            $url->param('move', $section - $displaysection);

                            // Define class from sublevels in order to move a margen in the left.
                            // Not apply if it is the first element (condition !empty($movelisthtml))
                            // because the first element can't be a sublevel.
                            $liclass = '';
                            if (is_array($formatoptions) && isset($formatoptions['level']) && $formatoptions['level'] > 0 &&
                                    !empty($movelisthtml)) {
                                $liclass = 'sublevel';
                            }

                            if ($displaysection != $section) {
                                $movelisthtml .= html_writer::tag('li', html_writer::link($url, $sectionname),
                                                array('class' => $liclass));
                            } else {
                                $movelisthtml .= html_writer::tag('li', $sectionname, array('class' => $liclass));
                            }
                        } else {
                            $movelisthtml .= html_writer::tag('li', $sectionname);
                        }
                    }
                    // End move section list.
                }
            }

            $section++;
        }

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $sections, $displaysection);
        $sectiontitle = '';

        if (!$course->hidetabsbar && count($tabs[0]) > 0) {

            if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {

                // Increase number of sections.
                $straddsection = get_string('increasesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php',
                    array('courseid' => $course->id,
                        'increase' => true,
                        'sesskey' => sesskey(),
                        'insertsection' => 0));
                $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
                $tabs[] = new tabobject("tab_topic_add", $url, $icon, s($straddsection));

            }

            $sectiontitle .= $OUTPUT->tabtree($tabs, "tab_topic_" . $displaysection, $inactivetabs);
        }

        echo $sectiontitle;

        if ($sections[$displaysection]->uservisible || $canviewhidden) {

            if ($course->realcoursedisplay != COURSE_DISPLAY_MULTIPAGE || $displaysection !== 0) {
                // Now the list of sections.
                echo $this->start_section_list();

                // The requested section page.
                $thissection = $sections[$displaysection];
                echo $this->section_header($thissection, $course, true);
                // Show completion help icon.
                $completioninfo = new completion_info($course);
                echo $completioninfo->display_help_icon();

                if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_NOT) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                } else if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_LIST) {
                    echo $this->custom_course_section_cm_list($course, $thissection, $displaysection);
                }

                echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        $sectionbottomnav .= html_writer::end_tag('div');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');

        if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {

            echo '<br class="utilities-separator" />';
            print_collapsible_region_start('move-list-box clearfix collapsible mform', 'course_format_onetopic_config_movesection',
                get_string('utilities', 'format_onetopic'), '', true);

            // Move controls.
            if ($canmove && !empty($movelisthtml)) {
                echo html_writer::start_div("form-item clearfix");
                    echo html_writer::start_div("form-label");
                        echo html_writer::tag('label', get_string('movesectionto', 'format_onetopic'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-setting");
                        echo html_writer::tag('ul', $movelisthtml, array('class' => 'move-list'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-description");
                        echo html_writer::tag('p', get_string('movesectionto_help', 'format_onetopic'));
                    echo html_writer::end_div();
                echo html_writer::end_div();
            }

            $baseurl = course_get_url($course, $displaysection);
            $baseurl->param('sesskey', sesskey());

            $url = clone($baseurl);

            global $USER, $OUTPUT;
            if (isset($USER->onetopic_da[$course->id]) && $USER->onetopic_da[$course->id]) {
                $url->param('onetopic_da', 0);
                $textbuttondisableajax = get_string('enable', 'format_onetopic');
            } else {
                $url->param('onetopic_da', 1);
                $textbuttondisableajax = get_string('disable', 'format_onetopic');
            }

            echo html_writer::start_div("form-item clearfix");
                echo html_writer::start_div("form-label");
                    echo html_writer::tag('label', get_string('disableajax', 'format_onetopic'));
                echo html_writer::end_div();
                echo html_writer::start_div("form-setting");
                    echo html_writer::link($url, $textbuttondisableajax);
                echo html_writer::end_div();
                echo html_writer::start_div("form-description");
                    echo html_writer::tag('p', get_string('disableajax_help', 'format_onetopic'));
                echo html_writer::end_div();
            echo html_writer::end_div();

            // Duplicate current section option.
            if (has_capability('moodle/course:manageactivities', $context)) {
                $urlduplicate = new moodle_url('/course/format/onetopic/duplicate.php',
                                array('courseid' => $course->id, 'section' => $displaysection, 'sesskey' => sesskey()));

                $link = new action_link($urlduplicate, get_string('duplicate', 'format_onetopic'));
                $link->add_action(new confirm_action(get_string('duplicate_confirm', 'format_onetopic'), null,
                    get_string('duplicate', 'format_onetopic')));

                echo html_writer::start_div("form-item clearfix");
                    echo html_writer::start_div("form-label");
                        echo html_writer::tag('label', get_string('duplicatesection', 'format_onetopic'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-setting");
                        echo $this->render($link);
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-description");
                        echo html_writer::tag('p', get_string('duplicatesection_help', 'format_onetopic'));
                    echo html_writer::end_div();
                echo html_writer::end_div();
            }

            echo html_writer::start_div("form-item clearfix form-group row fitem");
                echo $this->change_number_sections($course, 0);
            echo html_writer::end_div();

            print_collapsible_region_end();
        }
    }

}

class theme_gflacso_new_tabobject extends \tabobject {
    
  /**
   * Export for template.
   *
   * @param renderer_base $output Renderer.
   * @return object
   */

  public function export_for_template(\renderer_base $output) {
    if ($this->inactive || ($this->selected && !$this->linkedwhenselected) || $this->activated) {
        $link = null;
    } else {
        $link = $this->link;
    }
    $active = $this->activated || $this->selected;

    return (object) [
        'id' => $this->id,
        'link' => is_object($link) ? $link->out(false) : $link,
        'text' => $this->text,
        'title' => $this->title,
        'inactive' => !$active && $this->inactive,
        'active' => $active,
        'level' => 8,
        'custom_styles' => $this->custom_styles,
    ];
  }

}