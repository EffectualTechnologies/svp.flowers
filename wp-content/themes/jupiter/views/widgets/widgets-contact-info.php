<?php 
class Artbees_Widget_Contact_Info extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_contact_info', 'description' => 'Displays a list of contact info.' );
		WP_Widget::__construct( 'contact_info', THEME_SLUG.' - '. 'Contact Info', $widget_ops );

	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$company = $instance['company'];
		$person = $instance['person'];
		$phone = $instance['phone'];
		$fax = $instance['fax'];
		$email = $instance['email'];
		$address = $instance['address'];
		$website = $instance['website'];
		$skype = $instance['skype'];



		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

?>
			<ul <?php echo get_schema_markup('person'); ?>>
			<?php if ( !empty( $person ) ):?><li><i class="mk-moon-user-7"></i><span itemprop="name"><?php echo $person;?></span></li><?php endif;?>	
			<?php if ( !empty( $company ) ):?><li><i class="mk-moon-office"></i><span itemprop="jobTitle"><?php echo $company;?></span></li><?php endif;?>
			<?php if ( !empty( $address ) ):?><li><i class="mk-icon-home"></i><span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"><?php echo $address;?></span></li><?php endif;?>
			<?php if ( !empty( $phone ) ):?><li><i class="mk-icon-phone"></i><span><?php echo $phone;?></span></li><?php endif;?>
			<?php if ( !empty( $fax ) ):?><li><i class="mk-icon-print"></i><span><?php echo $fax;?></span></li><?php endif;?>
			<?php if ( !empty( $email ) ):?><li><i class="mk-icon-envelope"></i><span><a itemprop="email" href="mailto:<?php echo antispambot($email); ?>"><?php echo antispambot($email);?></a></span></li><?php endif;?>
			<?php if ( !empty( $website ) ):?><li><i class="mk-icon-globe"></i><span><a href="<?php echo $website; ?>" itemprop="url"><?php echo $website; ?></a></span></li><?php endif;?>
			<?php if ( !empty( $skype ) ):?><li><i class="mk-moon-skype"></i><span><a href="skype:<?php echo $skype; ?>?call"><?php echo $skype;?></a></span></li><?php endif;?>
			</ul>
		<?php
		echo $after_widget; 

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['phone'] = strip_tags( $new_instance['phone'] );
		$instance['fax'] = strip_tags( $new_instance['fax'] );
		$instance['email'] = strip_tags( $new_instance['email'] );
		$instance['address'] = $new_instance['address'];
		$instance['website'] = strip_tags( $new_instance['website'] );
		$instance['person'] = strip_tags( $new_instance['person'] );
		$instance['company'] = strip_tags( $new_instance['company'] );
		$instance['skype'] = strip_tags( $new_instance['skype'] );


		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$phone = isset( $instance['phone'] ) ? esc_attr( $instance['phone'] ) : '';
		$fax = isset( $instance['fax'] ) ? esc_attr( $instance['fax'] ) : '';
		$email = isset( $instance['email'] ) ? esc_attr( $instance['email'] ) : '';
		$address = isset( $instance['address'] ) ? esc_attr( $instance['address'] ) : '';
		$website = isset( $instance['website'] ) ? esc_attr( $instance['website'] ) : '';
		$person = isset( $instance['person'] ) ? esc_attr( $instance['person'] ) : '';
		$company = isset( $instance['company'] ) ? esc_attr( $instance['company'] ) : '';
		$skype = isset( $instance['skype'] ) ? esc_attr( $instance['skype'] ) : '';

?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'person' ); ?>"><?php _e('Person:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'person' ); ?>" name="<?php echo $this->get_field_name( 'person' ); ?>" type="text" value="<?php echo $person; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'company' ); ?>"><?php _e('Company:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'company' ); ?>" name="<?php echo $this->get_field_name( 'company' ); ?>" type="text" value="<?php echo $company; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e('Address:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" type="text" value="<?php echo $address; ?>" />
		<em>You can use HTML content in this field (e.g. for line breaks use &lt;br/&gt;)</em>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e('Phone:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo $phone; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'fax' ); ?>"><?php _e('Fax:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'fax' ); ?>" name="<?php echo $this->get_field_name( 'fax' ); ?>" type="text" value="<?php echo $fax; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Email:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo $email; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'website' ); ?>"><?php _e('Website:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'website' ); ?>" name="<?php echo $this->get_field_name( 'website' ); ?>" type="text" value="<?php echo $website; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'skype' ); ?>"><?php _e('Skype Username:', 'mk_framework'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'skype' ); ?>" name="<?php echo $this->get_field_name( 'skype' ); ?>" type="text" value="<?php echo $skype; ?>" /></p>



		

<?php
	}

}

register_widget("Artbees_Widget_Contact_Info");