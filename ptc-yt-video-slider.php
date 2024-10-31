<?php
/*
Plugin Name: PTC YT Video Widget
Plugin URI: https://wordpress.org/plugins/ptc-yt-video-widget/
Description: PTC YT Video Widget - An awesome youtube hosted video slider.
Version: 1.0
Author: vivan jakes
Author URI: https://wordpress.org/support/profile/personaltrainercertification
*/
class ptcytwgt_video_Slider{
    
    public $options;
    
    public function __construct() {
        //you can run delete_option method to reset all data
        //delete_option('real_youtube_plugin_options');
        $this->options = get_option('ptcytwgt_plugin_options');
        $this->ptcytwgt_register_settings_and_fields();
    }
    
    public static function add_youtube_tools_options_page(){
        add_options_page('PTC YT Video Widget', 'PTC YT Video Widget ', 'administrator', __FILE__, array('ptcytwgt_video_Slider','ptcytwgt_tools_options'));
    }
    
    public static function ptcytwgt_tools_options(){
?>
<div class="wrap">
    <h2>PTC YT (Youtube) Widget Configuration</h2>
    <form method="post" action="options.php" enctype="multipart/form-data">
        <?php settings_fields('ptcytwgt_plugin_options'); ?>
        <?php do_settings_sections(__FILE__); ?>
        <p class="submit">
            <input name="submit" type="submit" class="button-primary" value="Save Changes"/>
        </p>
    </form>
</div>
<?php
    }
    public function ptcytwgt_register_settings_and_fields(){
        register_setting('ptcytwgt_plugin_options', 'ptcytwgt_plugin_options',array($this,'ptcytwgt_validate_settings'));
        add_settings_section('ptcytwgt_main_section', 'Settings', array($this,'ptcytwgt_main_section_cb'), __FILE__);
        //Start Creating Fields and Options
        //sidebar image
        //add_settings_field('sidebarImage', 'Sidebar Image', array($this,'sidebarImage_settings'),__FILE__,'ptcytwgt_main_section');
        
        //pageURL
        add_settings_field('youtube_url', 'Youtube Video ID', array($this,'pageURL_settings'), __FILE__,'ptcytwgt_main_section');
        //marginTop
        add_settings_field('marginTop', 'Margin Top', array($this,'marginTop_settings'), __FILE__,'ptcytwgt_main_section');
        //alignment option
         add_settings_field('alignment', 'Position', array($this,'position_settings'),__FILE__,'ptcytwgt_main_section');
        //width
        add_settings_field('width', 'Width', array($this,'width_settings'), __FILE__,'ptcytwgt_main_section');
        //height
        add_settings_field('height', 'Height', array($this,'height_settings'), __FILE__,'ptcytwgt_main_section');

    }
    public function ptcytwgt_validate_settings($plugin_options){
        return($plugin_options);
    }
    public function ptcytwgt_main_section_cb(){
        //optional
    }

     
    
    
    //pageURL_settings
    public function pageURL_settings() {
        if(empty($this->options['youtube_url'])) $this->options['youtube_url'] = "";
        echo "<input name='ptcytwgt_plugin_options[youtube_url]' type='text' value='{$this->options['youtube_url']}' />";
    }
    //marginTop_settings
    public function marginTop_settings() {
        if(empty($this->options['marginTop'])) $this->options['marginTop'] = "250";
        echo "<input name='ptcytwgt_plugin_options[marginTop]' type='text' value='{$this->options['marginTop']}' />";
    }
    //alignment_settings
    public function position_settings(){
        if(empty($this->options['alignment'])) $this->options['alignment'] = "left";
        $items = array('left','right');
        echo "<select name='ptcytwgt_plugin_options[alignment]'>";
        foreach($items as $item){
            $selected = ($this->options['alignment'] === $item) ? 'selected = "selected"' : '';
            echo "<option value='$item' $selected>$item</option>";
        }
        echo "</select>";
    }
    //width_settings
    public function width_settings() {
        if(empty($this->options['width'])) $this->options['width'] = "350";
        echo "<input name='ptcytwgt_plugin_options[width]' type='text' value='{$this->options['width']}' />";
    }
    //height_settings
    public function height_settings() {
        if(empty($this->options['height'])) $this->options['height'] = "400";
        echo "<input name='ptcytwgt_plugin_options[height]' type='text' value='{$this->options['height']}' />";
    }

}
add_action('admin_menu', 'ptcytwgt_trigger_options_function');

function ptcytwgt_trigger_options_function(){
    ptcytwgt_video_Slider::add_youtube_tools_options_page();
}

add_action('admin_init','ptcytwgt_trigger_create_object');
function ptcytwgt_trigger_create_object(){
    new ptcytwgt_video_Slider();
}
add_action('wp_footer','ptcytwgt_add_content_in_footer');
function ptcytwgt_add_content_in_footer(){
    
    $o = get_option('ptcytwgt_plugin_options');
    extract($o);
    $total_height=$height-95;
	$mheight = $height-85;
    $max_height=$total_height+10;
$print_youtube = '';
if($youtube_url == ''){
$print_youtube.='<div class="error_kudos">Please Fill Out The PTC YT (Youtube) Widget Configuration First</div>';	
} else {
$print_youtube .= '
<iframe width="'.trim($width).'" height="'.trim($height).'"
 src="http://www.youtube.com/embed/'.$youtube_url.'" frameborder="0" allowfullscreen="yes" 
 "></iframe>';
}
$imgURL = plugins_url('assets/youtube-icon.png', __FILE__);

?>

<?php if($alignment=='left'){?>
<div id="real_youtube_display">
    <div id="ybox1" class="YT_area_left">
    <a class="open" id="ylink" href="javascript:;"><img style="top: 0px;right:-50px;" src="<?php echo $imgURL;?>" alt=""></a>
        <div id="ybox2" class="YT_inner_area_left">
             <?php echo $print_youtube; ?>
        </div>
        <div style="font-size: 9px; color: #808080; font-weight: normal; font-family: tahoma,verdana,arial,sans-serif; line-height: 1.28; text-align: left; direction: ltr;padding:3px 3px 0px; position:absolute;bottom:0px;left:0px;"><a href="https://www.nationalcprassociation.com/" target="_blank" style="color: #808080;">nationalcprassociation.com</a></div>
    </div>
</div>

<script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script>
<style>
 
  div.YT_area_left{

	left: -<?php echo trim($width+10);?>px; 

	top: <?php echo $marginTop;?>px; 

	z-index: 10000; 

	height:<?php echo trim($height+30);?>px;

	-webkit-transition: all .5s ease-in-out;

	-moz-transition: all .5s ease-in-out;

	-o-transition: all .5s ease-in-out;

	transition: all .5s ease-in-out;

	}

div.YT_area_left.showdiv{

	left:0;

	}	

div.YT_inner_area_left{

	text-align: left;

	width:<?php echo trim($width);?>px;

	height:<?php echo trim($height);?>px;

	}

</style>
<?php } else { ?>
<div id="real_youtube_display">
    <div class="YT_area_right" id="ybox1">
                <a class="open" id="ylink" href="javascript:;"><img style="top: 0px;left:-50px;" src="<?php echo $imgURL;?>" alt=""></a>
				 <div id="ybox2" class="YT_inner_area_right" >
            <?php echo $print_youtube; ?>
        </div>
        <div style="font-size: 9px; color: #808080; font-weight: normal; font-family: tahoma,verdana,arial,sans-serif; line-height: 1.28; text-align: right; direction: ltr;padding:3px 3px 0px; position:absolute;bottom:0px;right:0px;"><a href="https://www.nationalcprassociation.com/" target="_blank" style="color: #808080;">nationalcprassociation.com</a></div>
        
    </div>
</div>

<style type="text/css">

div.YT_area_right{

	right: -<?php echo trim($width+10);?>px;

	top: <?php echo $marginTop;?>px;

	z-index: 10000; 

	height:<?php echo trim($height+30);?>px;

	-webkit-transition: all .5s ease-in-out;

	-moz-transition: all .5s ease-in-out;

	-o-transition: all .5s ease-in-out;

	transition: all .5s ease-in-out;

	}

div.YT_area_right.showdiv{

	right:0;

	}	

div.YT_inner_area_right{

	text-align: left;

	width:<?php echo trim($width);?>px;

	height:<?php echo trim($height);?>px;

	}
	

</style>
<?php } ?>
<script type="text/javascript">

jQuery(document).ready(function() {
jQuery('#ylink').click(function(){
	jQuery(this).parent().toggleClass('showdiv');
});});
</script>

<?php
}
add_action( 'wp_enqueue_scripts', 'register_YTube1_slider_styles' );
 function register_YTube1_slider_styles() {
    wp_register_style( 'register_YTube1_slider_styles', plugins_url( 'assets/ytube_style.css' , __FILE__ ) );
    wp_enqueue_style( 'register_YTube1_slider_styles' );
        wp_enqueue_script('jquery');
 }