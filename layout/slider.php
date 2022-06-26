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

/**
 * The GFlacso theme is built upon  Bootstrapbase 3 (non-core).
 *
 * @package    gflacso
 * @subpackage theme_gflacso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* General settings */
$slidemode = (empty($PAGE->theme->settings->slidemode)) ? 'horizontal' : $PAGE->theme->settings->slidemode;
$slidespeed = (empty($PAGE->theme->settings->slidespeed)) ? 2000 : $PAGE->theme->settings->slidespeed;
$slideinterval = (empty($PAGE->theme->settings->slideinterval)) ? 4000 : $PAGE->theme->settings->slideinterval;
$slideautoplay = (empty($PAGE->theme->settings->slideautoplay)) ? 0 : 1;
$slidenumber = (empty($PAGE->theme->settings->slidenumber)) ? 1 : intval($PAGE->theme->settings->slidenumber);
$sliderotation = (empty($PAGE->theme->settings->bannersliderotationg)) ? 0 : $PAGE->theme->settings->bannersliderotationg;
$slideorientation = (empty($PAGE->theme->settings->bannerslideorientationg)) ? 'vertical' : $PAGE->theme->settings->bannerslideorientationg;
$hasslider = (!empty($PAGE->theme->settings->enableslider));


for ($i = 1; $i<= 10; $i++){
	$hasslide[$i] = (!empty($PAGE->theme->settings->{'enablebanner'.$i}));
	$hasslideimage[$i] = (!empty($PAGE->theme->settings->{'bannerimage'.$i}));
	if ($hasslideimage[$i]) {
	    $slideimage[$i] = $PAGE->theme->setting_file_url('bannerimage'.$i, 'bannerimage'.$i);
	}
	$slidetitle[$i] = (empty($PAGE->theme->settings->{'bannertitle'.$i})) ? false : $PAGE->theme->settings->{'bannertitle'.$i};
	$slidetitlesize[$i] = (empty($PAGE->theme->settings->{'bannertitlesize'.$i})) ? 70 : $PAGE->theme->settings->{'bannertitlesize'.$i};
	$slidetitlecolor[$i] = (empty($PAGE->theme->settings->{'titlecolor'.$i})) ? "#000" : $PAGE->theme->settings->{'titlecolor'.$i};
	$slidecolor[$i] = (empty($PAGE->theme->settings->{'bannercolor'.$i})) ? "#000" : $PAGE->theme->settings->{'bannercolor'.$i};
	$slidecaption[$i] = (empty($PAGE->theme->settings->{'bannertext'.$i})) ? false : $PAGE->theme->settings->{'bannertext'.$i};
	$slidecaptionsize[$i] = (empty($PAGE->theme->settings->{'bannertextsize'.$i})) ? 18 : $PAGE->theme->settings->{'bannertextsize'.$i};
	$slidecaptioncolor[$i] = (empty($PAGE->theme->settings->{'textcolor'.$i})) ? "#000" : $PAGE->theme->settings->{'textcolor'.$i};
	$slideurl[$i] = (empty($PAGE->theme->settings->{'bannerlinkurl'.$i})) ? false : $PAGE->theme->settings->{'bannerlinkurl'.$i};
	$slideurltext[$i] = (empty($PAGE->theme->settings->{'bannerlinkurltext'.$i})) ? 'Ver más' : $PAGE->theme->settings->{'bannerlinkurltext'.$i};
	
	$slidevideoautoplay[$i] = (!empty($PAGE->theme->settings->{'bannervideoautoplay'.$i})) ? true : false;

	$hasslidevideo[$i] = (!empty($PAGE->theme->settings->{'bannervideo'.$i}));
	if ($hasslidevideo[$i]) {
	    $slidevideo[$i] = $PAGE->theme->settings->{'bannervideo'.$i};
	    $slidevideotype[$i] = $PAGE->theme->settings->{'bannervideotype'.$i};
	}

}


$hasslideshow = ($hasslide[1] || $hasslide[2] || $hasslide[3] || $hasslide[4] || $hasslide[5] || $hasslide[6] || $hasslide[7] || $hasslide[8] || $hasslide[9] || $hasslide[10] ) ? true : false;

$sliderCount = 0;

function myslider_build_url($id,$type){
	if ($type == 'vimeo')
		return 'https://player.vimeo.com/video/'.$id;
	if ($type == 'youtube')
		return 'https://www.youtube.com/embed/'.$id.'?enablejsapi=1';
	return 'null';
}

?>


<a href="#sb-bxslider" class="sr-only sr-only-focusable sr-only-bxslider"><?php echo get_string('skip_access', 'theme_gflacso_new') ?></a>


<?php if ($hasslideshow && $hasslider) { ?>
	<ul class="bxslider">
		<?php for ($i = 1; $i<= 10; $i++)  {?>
			<?php if ($hasslide[$i]) { $sliderCount++;?>
	
				<li  id="slide<?php echo $i ?>" class="sl-slide" >
					<div class="sl-slide-inner" style="background-color: <?php echo $slidecolor[$i] ?>;">
						<?php if ($hasslidevideo[$i]) { ?>
							<iframe data-videotype="<?php echo $slidevideotype[$i] ?>" data-autoplay="<?php echo $slidevideoautoplay[$i] ?>" id="iframe<?php echo $i ?>" class="iframe" src="<?php echo myslider_build_url($slidevideo[$i],$slidevideotype[$i])?>" title="Diapositiva número <?php echo $i ?>" ></iframe>

						<?php } elseif ($hasslideimage[$i]){?>
							<img class="img" src="<?php echo $slideimage[$i] ?>" alt="Imagen número <?php echo $i ?>"/>
						<?php } ?>
			
						<?php if (!$hasslidevideo[$i]) { ?>
							<h2 style="font-size:<?php echo $slidetitlesize[$i] ?>px; color:<?php echo $slidetitlecolor[$i] ?>;"><?php echo $slidetitle[$i] ?></h2>
							<blockquote><p style="font-size:<?php echo $slidecaptionsize[$i] ?>px;color:<?php echo $slidecaptioncolor[$i] ?>;"><?php echo $slidecaption[$i] ?></p></blockquote>
							<?php if ($slideurl[$i]) { ?>
								<a class="btn" target="_blank" href="http://<?php echo $slideurl[$i] ?>"><?php echo $slideurltext[$i]?></a>
							<?php } ?>
						<?php } ?>
					</div>
				</li>

			<?php } ?>
		<?php } ?>

	</ul>
	<?php } ?>

<!-- Block Skip Link Target -->
<span id="sb-bxslider"></span>

<!-- Vimeo API -->
<script src="https://player.vimeo.com/api/player.js"></script>

<script>
var onYouTubeIframeAPIReady = null;
</script>

<?php
$PAGE->requires->js_amd_inline("require(['jquery','theme_gflacso_new/jquery.bxslider'], function($) {
	var bxslider = null;
	var playersArray = [];

	var tag = document.createElement('script');

  tag.src = 'https://www.youtube.com/iframe_api';
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);



	function getPlayer(index) {
		for (i=0; i<playersArray.length ; i++){
			if (playersArray[i].name == 'iframe'+index)
				return playersArray[i];
		}
		return null;
	}

	/* Inicializo Slider */
	$( document ).ready( function(){
		bxslider = $('.bxslider').bxSlider({
		  mode: '".$slidemode."',
		  speed: ".$slidespeed.",
		  auto: ".$slideautoplay.",
		  pause:".$slideinterval.",
		  onSlideAfter: function(\$slideElement, oldIndex, newIndex){ 
					//Freno ultimo slider si corresponde
					var aux1 = getPlayer(oldIndex+1);
					if (aux1 != null){
						if (aux1.type == 'vimeo')
							aux1.pause();
						else
							aux1.pauseVideo();
					}

					//Reproduzco slider si corresponde
					var aux2 = getPlayer(newIndex+1);
					if (aux2 != null && aux2.autoplay){
						console.log('Autoplay ON',aux2);
						if (aux2.type == 'vimeo')
							aux2.play();
						else
							aux2.playVideo();
						aux2.setVolume(0);
						
					}
					
				},
		onSliderLoad: function(currentIndex){ 
					//Reproduzco slider si corresponde
					var aux = getPlayer(currentIndex+1);
					if (aux != null && aux.autoplay){
						console.log('Autoplay ON',aux);
						if (aux.type == 'vimeo')
							aux.play();
						else
							aux.playVideo();
						aux.setVolume(0);
					}
				}
		});
	  });

	/* Inicializo videos de VIMEO */
	$( document ).ready( function(){
		$('.sl-slide-inner iframe.iframe').each(function() {
			if (this.getAttribute(\"data-videotype\") == 'vimeo') {
				//Loads VIMEO Player
				var player = new Vimeo.Player(jQuery(this));
		
				player.on('play', function() {
					console.log('Parando slider');
					if (bxslider != null)
						bxslider.stopAuto();

				    });

				player.on('ended', function() {
					console.log('Reanudando slider');
					if (bxslider != null)
						bxslider.startAuto();

				    });

				player.name = this.id;
				player.type = this.getAttribute(\"data-videotype\");
				player.autoplay = this.getAttribute(\"data-autoplay\");
				playersArray.push(player);
			}
		});
		

		
	  });


	/* Inicializo videos de YOUTUBE */

	onYouTubeIframeAPIReady = function() {
		console.log('onYouTubeIframeAPIReady');
		$('.sl-slide-inner iframe.iframe').each(function() {
			if (this.getAttribute(\"data-videotype\") == 'youtube') {
				//Loads VIMEO Player
				var player = new YT.Player(this.id,{
					playerVars: {},
					events: {
					    'onStateChange': function(event) {
								if (event.data == YT.PlayerState.PLAYING) {
									console.log('Parando slider');
									if (bxslider != null)
										bxslider.stopAuto();
								}
								if (event.data == YT.PlayerState.ENDED) {
									console.log('Reaunudando slider');
									if (bxslider != null)
										bxslider.startAuto();
								}
							      }
					  }
				    });
				player.name = this.id;
				player.type = this.getAttribute(\"data-videotype\");
				player.autoplay = this.getAttribute(\"data-autoplay\");
				playersArray.push(player);
			}
		});
	}

	function initialize(){

	    // Update the controls on load
	    updateTimerDisplay();
	    updateProgressBar();

	    // Clear any old interval.
	    clearInterval(time_update_interval);

	    // Start interval to update elapsed time display and
	    // the elapsed part of the progress bar every second.
	    time_update_interval = setInterval(function () {
	        updateTimerDisplay();
	        updateProgressBar();
	    }, 1000)

	}
});");?>