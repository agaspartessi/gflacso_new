<?php
try {
	$blockmanager = $PAGE->blocks;
	$accesibilityBlockExists = false;
	$targetblockpositions = array();
	foreach ($blockmanager->get_regions() as $region) {
	    foreach ($blockmanager->get_blocks_for_region($region) as $block) {
	        $instance = $block->instance;
	        if ($block->instance->blockname == 'accessibility' )
	            $accesibilityBlockExists = true;
	    }
	}

	if ( !$accesibilityBlockExists ) {
		require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
	    require_once($CFG->dirroot . '/blocks/accessibility/block_accessibility.php');
	    // Value 11793131 for production
	    // Value 8907018 for test
	    $accesibility_block_instance = (empty($PAGE->theme->settings->accesibility_block_instance)) ? '11793131' : $PAGE->theme->settings->accesibility_block_instance;

	    $cssurl = block_accessibility::CSS_URL . '?instance_id=' . $accesibility_block_instance;
	    $PAGE->requires->css($cssurl);
	}
}
catch (Exception $e) {

}
?>