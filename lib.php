<?php
 
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.
 
// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();
 
// We will add callbacks here as we add features to our theme.

function theme_gflacso_new_get_main_scss_content($theme) {                                                                                
    global $CFG;                                                                                                                    
 
    $scss = '';                                                                                                                     
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;                                                 
    $fs = get_file_storage();                                                                                                       
 
    $context = context_system::instance();                                                                                          
    if ($filename == 'default.scss') {                                                                                              
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/gflacso_new/scss/preset/default.scss');                                        
    } else if ($filename == 'plain.scss') {                                                                                         
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/gflacso_new/scss/preset/plain.scss');                                          
 
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_gflacso_new', 'preset', 0, '/', $filename))) {              
        // This preset file was fetched from the file area for theme_gflacso_new and not theme_boost (see the line above).                
        $scss .= $presetfile->get_content();                                                                                        
    } else {                                                                                                                        
        // Safety fallback - maybe new installs etc.                                                                                
        $scss .= file_get_contents($CFG->dirroot . '/theme/gflacso_new/scss/preset/default.scss');                                        
    }                                                                                                                                       
 
    return $scss;                                                                                                                   
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_gflacso_new_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
	if ($context->contextlevel == CONTEXT_SYSTEM){
		$theme = theme_config::load('gflacso_new');
		if ($filearea === 'logo') {
		    return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage1') {
		    return $theme->setting_file_serve('bannerimage1', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage2') {
		    return $theme->setting_file_serve('bannerimage2', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage3') {
		    return $theme->setting_file_serve('bannerimage3', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage4') {
		    return $theme->setting_file_serve('bannerimage4', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage5') {
		    return $theme->setting_file_serve('bannerimage5', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage6') {
		    return $theme->setting_file_serve('bannerimage6', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage7') {
		    return $theme->setting_file_serve('bannerimage7', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage8') {
		    return $theme->setting_file_serve('bannerimage8', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage9') {
		    return $theme->setting_file_serve('bannerimage9', $args, $forcedownload, $options);
		} else if ($filearea === 'bannerimage10') {
		    return $theme->setting_file_serve('bannerimage10', $args, $forcedownload, $options);
	    } else {
		send_file_not_found();
	    }
	} else {
		send_file_not_found();
	}
}
