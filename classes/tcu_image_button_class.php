<?php
/**
 * Widget that creates a custom button
 * to use in any TCU theme
 *
 * @author Mayra Perales <m.j.perales@tcu.edu>
 */

// Load our widget
add_action( 'widgets_init', function() {
	register_widget( 'TCU_Image_Button' );
});

class TCU_Image_Button extends WP_Widget
{
	/**
	 * Register widget with WordPress
	 */
	public function __construct() {
		parent::__construct(
				'tcu_image_button', // Base ID
				__('Image Button'), // Name
				array( 'description' => __('Add a button with an image as a background') ) // Args
			);
		add_action( 'sidebar_admin_setup', array( $this, 'upload_scripts' ) );
	}

	/**
	 * Enqueue all the javascript.
	 */
	public function upload_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'tcu-image-button', plugins_url('js/tcu-image-button.js', dirname( __FILE__ ) ), array( 'jquery', 'media-upload', 'media-views' ), '', true );
		wp_register_style( 'back-end-button-styles', plugins_url( 'css/back-end-button-styles.css' , dirname(__FILE__) ), array(), '', 'all' );
		wp_enqueue_style( 'back-end-button-styles' );
	}

	/**
	 * Front-end display of widget
	 * @see WP_Widget::widget()
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		// these are the widget options
		$image_url = $instance['image_url'];
		$url       = $instance['url'];
		$link_text = $instance['link_text'];
		$target    = $instance['target'];
		$align     = $instance['align'];
		$height    = $instance['height'];
		$width     = $instance['width'];

		global $post;

		// Before widget (defined by themes)
		echo $before_widget;

		?>
			<div style="background: transparent url(<?php echo $instance['image_url'] ?>) top center no-repeat; height: <?php echo $instance['height']; ?>px; max-width: <?php echo $instance['width'] ?>px; <?php echo $this->align_image( $instance ); ?>" class="btn btn-image">
				<div class="btn-image__hover"></div>
				<a target="<?php echo $this->link_target( $instance ); ?>" href="<?php echo $instance['url'] ?>" class="button-home btn-text"><?php echo $instance['link_text'] ?></a>
			</div>
		<?php

		// After widget (defined by themes)
		echo $after_widget;

	}

	/**
	 * Form UI
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database
	 */
	public function form( $instance ) {

		// check our values
		if( $instance ) {
			$image_url = esc_attr( $instance['image_url'] );
			$url       = esc_attr( $instance['url'] );
			$link_text = esc_attr( $instance['link_text'] );
			$target    = esc_attr( $instance['target'] );
			$align     = esc_attr( $instance['align'] );
			$height    = esc_attr( $instance['height'] );
			$width     = esc_attr( $instance['width'] );
		} else {
			$image_url  = '';
			$url        = '';
			$link_text  = '';
			$target     = '';
			$align      = '';
			$height     = '';
			$width      = '';
		} ?>

		<div class="tcu-image-button-container">
			<div class="tcu-image-widget">

				<?php if( $image_url ) {
					echo '<img class="tcu-custom-image" src="'.$image_url.'" /><br>
					<a class="tcu-image-button-clear-image" href="#">Remove Image</a>';
				} else {
					echo '<a href="#" class="tcu-image-button-upload button-primary">Upload Image</a>';
				} ?>

				<input class="tcu-image-button-url" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="hidden" value="<?php echo $image_url; ?>">


			</div><!-- end of .tcu-image-widget -->

			<p>
				<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link:'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Link Text:'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo $link_text; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('align') ?>"><?php _e('Align Image:'); ?></label>
				<select name="<?php echo $this->get_field_name('align'); ?>" class="widefat">
					<?php
						$align_options = array('none', 'left', 'center', 'right');
						foreach( $align_options as $option ) {
							echo '<option value="'.$option.'" id="'.$option.'"', $align == $option ?  'selected="selected"' : '', '>', $option, '</option>';
						}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('target') ?>"><?php echo _e('Link Target'); ?></label>
				<select name="<?php echo $this->get_field_name('target'); ?>" class="widefat">
					<?php
						$options = array('Stay in window', 'Open in new window');
						foreach( $options as $option ) {
							echo '<option value="'.$option.'" id="'.$option.'"', $target == $option ?  'selected="selected"' : '', '>', $option, '</option>';
						}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('height') ?>"><?php _e('Height:'); ?></label>
				<input class="widefat tcu-image-height" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('width') ?>"><?php _e('Width:'); ?></label>
				<input class="widefat tcu-image-width" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>">
			</p>
		</div><!-- end of .tcu-image-button-container -->

	<?php }

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved
	 * @param array $old_instance Previously saved values from database
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['image_url'] = strip_tags( $new_instance['image_url'] );
		$instance['url']       = strip_tags( $new_instance['url'] );
		$instance['link_text'] = strip_tags( $new_instance['link_text'] );
		$instance['align']     = strip_tags( $new_instance['align'] );
		$instance['target']    = strip_tags( $new_instance['target'] );
		$instance['height']    = strip_tags( $new_instance['height'] );
		$instance['width']     = strip_tags( $new_instance['width'] );
		return $instance;

	}

	/**
	 * Returns correct string to use for aligning our image
	 * @param array $instance
	 *
	 * @return string Updated string value
	 */

	private function align_image( $instance ) {

		switch( $instance['align'] ) {
			case 'none':
				return 'float: none;';
				break;
			case 'left':
				return 'float: left;';
				break;
			case 'center':
				return 'margin: 0 auto;';
				break;
			case 'right':
				return 'float: right;';
				break;
		}
	}

	/**
	 * Returns correct string to use for link target attribute
	 * @param array $instance
	 *
	 * @return string Updated string value
	 */

	private function link_target( $instance ) {

		switch( $instance['target'] ) {
			case 'Stay in window':
				return '_self';
				break;
			case 'Open in new window':
				return '_blank';
				break;
		}
	}

}


?>