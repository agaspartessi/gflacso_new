<?php
 
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.
 
// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();                                                                                                
 
// This is used for performance, we don't need to know about these settings on every page in Moodle, only when                      
// we are looking at the admin settings pages.                                                                                      
if ($ADMIN->fulltree) {                                                                                                             
 
    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.                         
    $settings = new theme_boost_admin_settingspage_tabs('themesettinggflacso_new', get_string('configtitle', 'theme_gflacso_new'));             
 
    // Each page is a tab - the first is the "General" tab.                                                                         
    $page = new admin_settingpage('theme_gflacso_new_general', get_string('generalsettings', 'theme_gflacso_new'));                             
 
    // Replicate the preset setting from boost.                                                                                     
    $name = 'theme_gflacso_new/preset';                                                                                                   
    $title = get_string('preset', 'theme_gflacso_new');                                                                                   
    $description = get_string('preset_desc', 'theme_gflacso_new');                                                                        
    $default = 'default.scss';                                                                                                      
 
    // We list files in our own file area to add to the drop down. We will provide our own function to                              
    // load all the presets from the correct paths.                                                                                 
    $context = context_system::instance();                                                                                          
    $fs = get_file_storage();                                                                                                       
    $files = $fs->get_area_files($context->id, 'theme_gflacso_new', 'preset', 0, 'itemid, filepath, filename', false);                    
 
    $choices = [];                                                                                                                  
    foreach ($files as $file) {                                                                                                     
        $choices[$file->get_filename()] = $file->get_filename();                                                                    
    }                                                                                                                               
    // These are the built in presets from Boost.                                                                                   
    $choices['default.scss'] = 'default.scss';                                                                                      
    $choices['plain.scss'] = 'plain.scss';                                                                                          
 
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);                                     
    $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
    $page->add($setting);                                                                                                           
 
    // Preset files setting.                                                                                                        
    $name = 'theme_gflacso_new/presetfiles';                                                                                              
    $title = get_string('presetfiles','theme_gflacso_new');                                                                               
    $description = get_string('presetfiles_desc', 'theme_gflacso_new');                                                                   
 
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,                                         
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));                                                               
    $page->add($setting);     
 
    // Variable $brand-color.                                                                                                       
    // We use an empty default value because the default colour should come from the preset.                                        
    $name = 'theme_gflacso_new/brandcolor';                                                                                               
    $title = get_string('brandcolor', 'theme_gflacso_new');                                                                               
    $description = get_string('brandcolor_desc', 'theme_gflacso_new');                                                                    
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');                                               
    $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
    $page->add($setting);                                                                                                           
 
    // Must add the page after definiting all the settings!                                                                         
    $settings->add($page);                                                                                                          
 
    // Advanced settings.                                                                                                           
    $page = new admin_settingpage('theme_gflacso_new_advanced', get_string('advancedsettings', 'theme_gflacso_new'));                           
 
    // Raw SCSS to include before the content.                                                                                      
    $setting = new admin_setting_configtextarea('theme_gflacso_new/scsspre',                                                              
        get_string('rawscsspre', 'theme_gflacso_new'), get_string('rawscsspre_desc', 'theme_gflacso_new'), '', PARAM_RAW);                      
    $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
    $page->add($setting);                                                                                                           
 
    // Raw SCSS to include after the content.                                                                                       
    $setting = new admin_setting_configtextarea('theme_gflacso_new/scss', get_string('rawscss', 'theme_gflacso_new'),                           
        get_string('rawscss_desc', 'theme_gflacso_new'), '', PARAM_RAW);                                                                  
    $setting->set_updatedcallback('theme_reset_all_caches');                                                                        
    $page->add($setting);              

    // Set my - accesibility block instance Id
    $name = 'theme_gflacso_new/accesibility_block_instance';
    $title = get_string('accesibility_block_instance' , 'theme_gflacso_new');
    $description = get_string('accesibility_block_instancedesc', 'theme_gflacso_new');
    // Value 11793131 for production
    $default = 11793131;
    $setting = new admin_setting_configtext($name, $title, $description, $default );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);                                                                                             
 
    $settings->add($page);     
    
    /***************
     Banner Settings 
    ***************/

    $page = new admin_settingpage('theme_gflacso_new_slider', get_string('slider', 'theme_gflacso_new'));                           

    // Enables the slider.
    $name = 'theme_gflacso_new/enableslider';
    $title = get_string('enableslider', 'theme_gflacso_new');
    $description = get_string('enablesliderdesc', 'theme_gflacso_new');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Set Number of Slides.
    $name = 'theme_gflacso_new/slidenumber';
    $title = get_string('slidenumber' , 'theme_gflacso_new');
    $description = get_string('slidenumberdesc', 'theme_gflacso_new');
    $default = '1';
    $choices = array(
    	'1'=>'1',
    	'2'=>'2',
    	'3'=>'3',
    	'4'=>'4',
    	'5'=>'5',
    	'6'=>'6',
    	'7'=>'7',
    	'8'=>'8',
    	'9'=>'9',
    	'10'=>'10');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Set the Slide Speed.
    $name = 'theme_gflacso_new/slidespeed';
    $title = get_string('slidespeed' , 'theme_gflacso_new');
    $description = get_string('slidespeeddesc', 'theme_gflacso_new');
    $default = '2000';
    $setting = new admin_setting_configtext($name, $title, $description, $default );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Set the Slide interval.
    $name = 'theme_gflacso_new/slideinterval';
    $title = get_string('slideinterval' , 'theme_gflacso_new');
    $description = get_string('slideintervaldesc', 'theme_gflacso_new');
    $default = '4000';
    $setting = new admin_setting_configtext($name, $title, $description, $default );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Transition mode.
    $name = 'theme_gflacso_new/bannerslidermode';
    $title = get_string('bannerslidermode', 'theme_gflacso_new');
    $description = get_string('bannerslidermodedesc', 'theme_gflacso_new');
    $default = 'horizontal';
    $choices = array(
          'vertical'=>'vertical',
          'horizontal'=>'horizontal',
      'fade'=>'fade');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Set autoplay
    $name = 'theme_gflacso_new/slideautoplay';
    $title = get_string('slideautoplay', 'theme_gflacso_new');
    $description = get_string('slideautoplaydesc', 'theme_gflacso_new');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $hasslidenum = (!empty($PAGE->theme->settings->slidenumber));
    if ($hasslidenum) {
    		$slidenum = $PAGE->theme->settings->slidenumber;
    } else {
      $slidenum = '1';
    }

	  $bannertitle = array('Slide One', 'Slide Two', 'Slide Three','Slide Four','Slide Five','Slide Six','Slide Seven', 'Slide Eight', 'Slide Nine', 'Slide Ten');

    foreach (range(1, $slidenum) as $bannernumber) {

        // This is the descriptor for the Banner Settings.
        $name = 'theme_gflacso_new/banner';
        $title = get_string('bannerindicator', 'theme_gflacso_new');
        $information = get_string('bannerindicatordesc', 'theme_gflacso_new');
        $setting = new admin_setting_heading($name.$bannernumber, $title.$bannernumber, $information);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Enables the slide.
        $name = 'theme_gflacso_new/enablebanner' . $bannernumber;
        $title = get_string('enablebanner', 'theme_gflacso_new', $bannernumber);
        $description = get_string('enablebannerdesc', 'theme_gflacso_new', $bannernumber);
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide Title.
        $name = 'theme_gflacso_new/bannertitle' . $bannernumber;
        $title = get_string('bannertitle', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannertitledesc', 'theme_gflacso_new', $bannernumber);
        $default = $bannertitle[$bannernumber - 1];
        $setting = new admin_setting_configtext($name, $title, $description, $default );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
      
        // Slide Title Size.
        $name = 'theme_gflacso_new/bannertitlesize' . $bannernumber;
        $title = get_string('bannertitlesize', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannertitlesizedesc', 'theme_gflacso_new', $bannernumber);
        $default = '70';
        $setting = new admin_setting_configtext($name, $title, $description, $default );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        //Slide Title Color.
        $name = 'theme_gflacso_new/titlecolor' . $bannernumber;
        $title = get_string('titlecolor', 'theme_gflacso_new', $bannernumber);
        $description = get_string('titlecolordesc', 'theme_gflacso_new', $bannernumber);
        $default = '#000';
        $previewconfig = null;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide text.
        $name = 'theme_gflacso_new/bannertext' . $bannernumber;
        $title = get_string('bannertext', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannertextdesc', 'theme_gflacso_new', $bannernumber);
        $default = 'Bacon ipsum dolor sit amet turducken jerky beef ribeye boudin t-bone shank fatback pork loin pork short loin jowl flank meatloaf venison. Salami meatball sausage short loin beef ribs';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

	      // Slide Text Size.
        $name = 'theme_gflacso_new/bannertextsize' . $bannernumber;
        $title = get_string('bannertextsize', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannertextsizedesc', 'theme_gflacso_new', $bannernumber);
        $default = '18';
        $setting = new admin_setting_configtext($name, $title, $description, $default );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

	      // Slide Text Color.
        $name = 'theme_gflacso_new/textcolor' . $bannernumber;
        $title = get_string('textcolor', 'theme_gflacso_new', $bannernumber);
        $description = get_string('textcolordesc', 'theme_gflacso_new', $bannernumber);
        $default = '#000';
        $previewconfig = null;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Destination URL for Slide Link
        $name = 'theme_gflacso_new/bannerlinkurl' . $bannernumber;
        $title = get_string('bannerlinkurl', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannerlinkurldesc', 'theme_gflacso_new', $bannernumber);
        $default = '#';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        //Altenarte text for URL Button
        $name = 'theme_gflacso_new/bannerlinkurltext' . $bannernumber;
        $title = get_string('bannerlinkurltext', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannerlinkurltextdesc', 'theme_gflacso_new', $bannernumber);
        $default = 'Ver más';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide Image.
        $name = 'theme_gflacso_new/bannerimage' . $bannernumber;
        $title = get_string('bannerimage', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannerimagedesc', 'theme_gflacso_new', $bannernumber);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'bannerimage'.$bannernumber);
        
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide video ID.
        $name = 'theme_gflacso_new/bannervideo' . $bannernumber;
        $title = get_string('bannervideo', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannervideodesc', 'theme_gflacso_new', $bannernumber);
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide video Type.
        $name = 'theme_gflacso_new/bannervideotype' . $bannernumber;
        $title = get_string('bannervideotype' , 'theme_gflacso_new');
        $description = get_string('bannervideotypedesc', 'theme_gflacso_new');
        $default = 'vimeo';
        $choices = array(
        'youtube'=>'Youtube',
        'vimeo'=>'Vimeo');
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide video autoplay.
        $name = 'theme_gflacso_new/bannervideoautoplay' . $bannernumber;
              $title = get_string('bannervideoautoplay', 'theme_gflacso_new', $bannernumber);
              $description = get_string('bannervideoautoplaydesc', 'theme_gflacso_new', $bannernumber);
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide Background Color.
        $name = 'theme_gflacso_new/bannercolor' . $bannernumber;
        $title = get_string('bannercolor', 'theme_gflacso_new', $bannernumber);
        $description = get_string('bannercolordesc', 'theme_gflacso_new', $bannernumber);
        $default = '#000';
        $previewconfig = null;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }
    $settings->add($page); 

}
