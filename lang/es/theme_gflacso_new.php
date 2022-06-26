<?php
 
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.
 
// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();
// The name of the second tab in the theme settings.                                                                                
$string['advancedsettings'] = 'Configuración Avanzada';                                                                                  
// The brand colour setting.                                                                                                        
$string['brandcolor'] = 'Color de marca';                                                                                             
// The brand colour setting description.                                                                                            
$string['brandcolor_desc'] = 'Color de marca.';     
// A description shown in the admin theme selector.                                                                                 
$string['choosereadme'] = 'Tema gflacso_new es un tema herdado de Boost. Es un tema personalzado para la FLACSO.';                
// Name of the settings pages.                                                                                                      
$string['configtitle'] = 'Configuración de gflacso_new';                                                                                          
// Name of the first settings tab.                                                                                                  
$string['generalsettings'] = 'Confiuración General';                                                                                    
// The name of our plugin.                                                                                                          
$string['pluginname'] = 'gflacso_new';                                                                                                    
// Preset files setting.                                                                                                            
$string['presetfiles'] = 'Archivos preestablecidos adicionales del tema';                                                                           
// Preset files help text.                                                                                                          
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme. See <a href=https://docs.moodle.org/dev/Boost_Presets>Boost presets</a> for information on creating and sharing your own preset files, and see the <a href=http://moodle.net/boost>Presets repository</a> for presets that others have shared.';
// Preset setting.                                                                                                                  
$string['preset'] = 'Tema preestablecido';                                                                                                 
// Preset help text.                                                                                                                
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';                                                  
// Raw SCSS setting.                                                                                                                
$string['rawscss'] = 'Raw SCSS';                                                                                                    
// Raw SCSS setting help text.                                                                                                      
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';       
// Raw initial SCSS setting.                                                                                                        
$string['rawscsspre'] = 'Raw initial SCSS';                                                                                         
// Raw initial SCSS setting help text.                                                                                              
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
// We need to include a lang string for each block region.                                                                          
$string['region-side-pre'] = 'Derecha';
/** Slider **/
$string['slider'] = 'Slider';
$string['slidenumber'] = 'Numero';
$string['slidenumberdesc'] = 'Seleccione la cantidad de Slides que desea configurar';
$string['bannerslidermode'] = 'Efecto de Transición';
$string['bannerslidermodedesc'] = 'Seleccione el efecto de transición entre slides';
$string['enableslider'] = 'Habilitar Slider';
$string['enablesliderdesc'] = 'Si la opcion esta chequeada entoncs el slider se muestra en la pagina';
$string['slidespeed'] = 'Velocidad';
$string['slidespeeddesc'] = 'Velocidad de transicion entre Slides';
$string['slideinterval'] = 'Intervalo';
$string['slideintervaldesc'] = 'Intervalo de tiempo antes de pasar al siguiente Slide';
$string['slideautoplay'] = 'Autoplay';
$string['slideautoplaydesc'] = 'Si la opcion esta chequeada entonces el slider se reproduce de manera automatica al cargar la pagina';
$string['bannerindicator'] = 'Slider ';
$string['bannerindicatordesc'] = 'Configuracion para el slider ';
$string['enablebanner'] = 'Habilitar';
$string['enablebannerdesc'] = 'Si el slider no esta habilitado entonces el mismo no se mostrara';
$string['bannertitle'] = 'Titulo';
$string['bannertitledesc'] = 'Titulo del slider';
$string['titlecolor'] = 'Color del titulo';
$string['titlecolordesc'] = 'Seleccione un color para el titulo';
$string['bannertext'] = 'Texto';
$string['bannertextdesc'] = 'Texto del slider';
$string['textcolor'] = 'Color del texto';
$string['textcolordesc'] = 'Seleccione un color para el texto';
$string['bannerlinkurl'] = 'Link';
$string['bannerlinkurldesc'] = 'Link del slider';
$string['bannervideo'] = 'ID del video';
$string['bannervideodesc'] = 'ID del video VIMEO o YOUTUBE<br/>Ejemplo para Vimeo https://player.vimeo.com/video/<strong>76979871</strong> el id seria <strong>76979871</strong><br/>Ejemplo para Youtube https://www.youtube.com/watch?v=<strong>DjzgjhSJ2Oc</strong> el id seria <strong>DjzgjhSJ2Oc</strong>';
$string['bannervideotype'] = 'Tipo de video';
$string['bannervideotypedesc'] = 'Seleccione el tipo de video del cual esta colocando el ID, puede ser Youtube o Vimeo';
$string['bannerimage'] = 'Imagen';
$string['bannerimagedesc'] = 'Imagen de fondo para el slider';
$string['bannercolor'] = 'Color';
$string['bannercolordesc'] = 'Color de fondo para el slider';
$string['bannertitlesize'] = 'Tamaño fuente';
$string['bannertitlesizedesc'] = 'Tamaño fuente para el titulo en pixeles';
$string['bannertextsize'] = 'Tamaño fuente';
$string['bannertextsizedesc'] = 'Tamaño fuente para el texto en pixeles';
$string['bannerlinkurltext'] = 'Texto boton link';
$string['bannerlinkurltextdesc'] = 'Escriba el texto que desea que aparezca en el boton del slider';
$string['bannervideoautoplay'] = 'Reproducir Video';
$string['bannervideoautoplaydesc'] = 'Si la opcion esta habilitada el video se reproducira de manera automatica y en silencio';
$string['accesibility_block_instance'] = 'ID instancia accesibilidad';
$string['accesibility_block_instancedesc'] = 'ID instancia de bloque accesibilidad en MI ESPACIO';
$string['printpage'] = 'Imprimir';
$string['skip_access'] = 'Saltar Slider';