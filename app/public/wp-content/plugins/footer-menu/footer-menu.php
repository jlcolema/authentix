<?php
/**
* Plugin Name: Footer Menu - Authentix
* Plugin URI: http://hunor.me/
* Description: Custom footer menu plugin for authentix
* Version: 1.0
* Author: Hunor Madaras
* Author URI: http://hunor.me/
**/

if ( ! class_exists( 'Ax_Footer_Menu' ) ) {

	// Load and Register widget
	add_action('widgets_init', function(){
		register_widget("Ax_Footer_Menu");
	});

	class Ax_Footer_Menu extends WP_Widget {

		// constructor
	  function __construct() {
			parent::WP_Widget(false, $name = __('Footer Menu - Authentix', 'ax_footer_menu') );
	  }

	  // widget form creation
	  public function form($instance) {
	  	$twitter_link = isset($instance['twitter_link']) ? $instance['twitter_link'] : '';
	  	$linkedin_link = isset($instance['linkedin_link']) ? $instance['linkedin_link'] : '';
	  	$youtube_link = isset($instance['youtube_link']) ? $instance['youtube_link'] : '';
	  	$nav_menu = isset($instance['nav_menu']) ? $instance['nav_menu'] : '';
	  	$nav_menu_class = isset( $instance['nav_menu_class'] ) ? $instance['nav_menu_class'] : 'sub-menu';
	  	$copyright = isset($instance['copyright']) ? $instance['copyright'] : '';
	  	$logos = isset($instance['logos']) ? $instance['logos'] : '';

	  	// Get menus list
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

			// If no menu exists, direct the user to create some.
			if ( ! $menus ) {
				echo '<p>' . sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.', 'ax_footer_menu' ), admin_url( 'nav-menus.php' ) ) . '</p>';

				return;
			}
	  	?>
			<p>
	  		<label for""><?php _e('Twitter Link:', 'ax_footer_menu'); ?></label>
	  		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_link' ); ?>" name="<?php echo $this->get_field_name( 'twitter_link' ); ?>" value="<?php echo esc_url( $twitter_link ); ?>"/>
	  	</p>

	  	<p>
	  		<label for""><?php _e('Linkedin Link:', 'ax_footer_menu'); ?></label>
	  		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'linkedin_link' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_link' ); ?>" value="<?php echo esc_url( $linkedin_link ); ?>"/>
	  	</p>

	  	<p>
	  		<label for""><?php _e('Youtube Link:', 'ax_footer_menu'); ?></label>
	  		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'youtube_link' ); ?>" name="<?php echo $this->get_field_name( 'youtube_link' ); ?>" value="<?php echo esc_url( $youtube_link ); ?>"/>
	  	</p>

	    <p>
	    	<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:', 'ax_footer_menu' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<?php foreach ( $menus as $menu ) {
						$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
							echo '<option' . $selected . ' value="' . $menu->term_id . '">' . $menu->name . '</option>';
						} ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu_class' ); ?>"><?php _e( 'Menu Class:', 'ax_footer_menu' ) ?>
				</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'nav_menu_class' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu_class' ); ?>" value="<?php echo esc_attr( $nav_menu_class ); ?>"/>
				<small><?php _e( 'CSS classes to use for the ul menu element. Separate classes by a space.', 'ax_footer_menu' ); ?></small>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'logos' ); ?>"><?php _e( 'Logos Text:', 'ax_footer_menu' ) ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id('logos'); ?>" name="<?php echo $this->get_field_name('logos'); ?>" rows="7" cols="20" ><?php echo $logos; ?></textarea>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'copyright' ); ?>"><?php _e( 'Copyright Text:', 'ax_footer_menu' ) ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id('copyright'); ?>" name="<?php echo $this->get_field_name('copyright'); ?>" rows="7" cols="20" ><?php echo $copyright; ?></textarea>
			</p>
	    <?php 
	  }

	  // widget update
	  public function update($new_instance, $old_instance) {
	  	$instance['twitter_link'] = esc_html($new_instance['twitter_link']);
	  	$instance['linkedin_link'] = esc_html($new_instance['linkedin_link']);
	    $instance['youtube_link'] = esc_html($new_instance['youtube_link']);
	    $instance['nav_menu'] = (int)$new_instance['nav_menu'];
	    $instance['nav_menu_class'] = $this->update_classes( $new_instance );
	    $instance['copyright'] = $new_instance['copyright'];
	    $instance['logos'] = $new_instance['logos'];

	    return $instance;
	  }

	  /**
		 *
		 * Update classes.
		 *
		 * Update menu classes and sanitizes them.
		 *
		 * @since 1.5
		 * @link https://wordpress.org/support/topic/multiple-css-classes?replies=7#post-7319138
		 *
		 * @param $new_instance
		 *
		 * @return string
		 *
		 */

		public function update_classes( $new_instance ) {
			$output  = '';
			$classes = explode( " ", preg_replace( '/\s\s+/', ' ', $new_instance['nav_menu_class'] ) );
			foreach ( $classes as $class ) {
				$output .= sanitize_html_class( $class ) . ' ';
			}
			// In some cases an extra space can occur if a bad style is stripped out by sanitize_html_class
			$output                 = trim( preg_replace( '/\s\s+/', ' ', $output ), ' ' );
			$instance['nav_menu_class'] = $output;

			return $output;
		}

	  // widget display
		public function widget($args, $instance) {
			// extract( $args );

			$nav_menu = wp_get_nav_menu_object($instance['nav_menu']);

			if (!$nav_menu) {
				return;
			}

			echo $before_widget;

			echo '<div class="widget widget_ax_footer_menu">';

			if(!empty($instance['linkedin_link']) || !empty($instance['youtube_link']) || !empty($instance['twitter_link'])) {
				echo '<div class="footer_social hide">';
				echo '<ul class="no-bullet">';
				if(!empty($instance['twitter_link'])) {
				echo '<li><a href="'.$instance['twitter_link'].'" target="_blank"><i class="fa fa-twitter"></i></a></li>';
				}
				if(!empty($instance['linkedin_link'])) {
				echo '<li><a href="'.$instance['linkedin_link'].'" target="_blank"><svg width="24" height="23" viewBox="0 0 24 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.98 2.5C4.98 3.881 3.87 5 2.5 5C1.13 5 0.02 3.881 0.02 2.5C0.02 1.12 1.13 0 2.5 0C3.87 0 4.98 1.12 4.98 2.5ZM5 7H0V23H5V7ZM12.982 7H8.014V23H12.983V14.601C12.983 9.931 19.012 9.549 19.012 14.601V23H24V12.869C24 4.989 15.078 5.276 12.982 9.155V7Z" fill="white"/></svg></a></li>';
				}
				if(!empty($instance['youtube_link'])) {
				echo '<li><a href="'.$instance['youtube_link'].'" target="_blank"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.615 0.184125C16.011 -0.0618748 7.984 -0.0608748 4.385 0.184125C0.488 0.450125 0.029 2.80412 0 9.00012C0.029 15.1851 0.484 17.5491 4.385 17.8161C7.985 18.0611 16.011 18.0621 19.615 17.8161C23.512 17.5501 23.971 15.1961 24 9.00012C23.971 2.81512 23.516 0.451125 19.615 0.184125ZM9 13.0001V5.00013L17 8.99312L9 13.0001Z" fill="white"/></svg></a></li>';
				}
				echo '</ul>';
				echo '</div>';
			}

			if(!empty($instance['logos'])) {
				echo '<div class="logos">';
				echo $instance['logos'];
				echo '</div>';
			}

			echo '<div class="row expanded bottom">';
			echo '<div class="medium-6 columns">';
				if(!empty($instance['copyright'])) {
					echo '<div class="copyright">';
					echo '<p>'.$instance['copyright'].'</p>';
					echo '</div>';
				}
				echo '</div>';

				// Display the widget
				echo '<div class="medium-6 columns">';
				echo '<div class="footer-menu medium-text-right">';

				wp_nav_menu( array(
					'fallback_cb' => '',
					'menu'        => $nav_menu,
					'menu_class'  => esc_attr( $instance['nav_menu_class'] ),
					'container'   => false
				) );
				echo '</div>';

				echo '</div>';
			echo '</div>'; // end row

			echo '</div>';
			
			echo $after_widget;
		
		}

	}

}
