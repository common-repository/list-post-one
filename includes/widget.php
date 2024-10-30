<?php

defined('ABSPATH') or die;

/**
 * Custom Widget for displaying ...
 *
 * @link http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package List Posts
 * @subpackage List Posts
 * @since List Posts 1.0.4
 */

class List_Post_One_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since List Posts 1.0
	 *
	 * @return List_Post_One_Widget
	 */
	public function __construct() {
		parent::__construct( 'widget_list_box', 'List Posts', array(
			'classname'   => 'widget_list_box',
			'description' => 'Use this widget to list your posts.'
		) );
	}
	
	/**
	 * Deal with the settings when they are saved by the admin.
	 *
	 * Here is where any validation should happen.
	 *
	 * @since List Posts 1.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $instance ) {
		$instance['title'] 		= empty( $new_instance['title'] ) ? '' : esc_attr($new_instance['title']);
		$instance['show_title'] = empty( $new_instance['show_title'] ) ? 0 : absint($new_instance['show_title']);
		$instance['category']  	= empty( $new_instance['category'] ) ? 0 : absint( $new_instance['category'] );
		$instance['same_category'] = empty( $new_instance['same_category'] ) ? 0 : absint( $new_instance['same_category'] );
		$instance['displays']  	= empty( $new_instance['displays'] ) ? 3 : absint($new_instance['displays']);
		$instance['orderby'] 	= empty( $new_instance['orderby'] ) ? '' : esc_attr( $new_instance['orderby'] );
		$instance['order'] 		= empty( $new_instance['order'] ) ? 'DESC' : esc_attr( $new_instance['order'] );
		$instance['meta_value'] = empty( $new_instance['meta_value'] ) ? '' : esc_attr( $new_instance['meta_value'] );
		
		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the Admin area.
	 *
	 * @since List Posts 1.0
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {
		$title  		= empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$same_category 	= empty( $instance['same_category'] ) ? 0 : absint( $instance['same_category'] );
		$category 		= empty( $instance['category'] ) ? 0 : absint( $instance['category'] );
		$displays  		= empty( $instance['displays'] ) ? 3 : absint($instance['displays']);
		$orderby  		= empty( $instance['orderby'] ) ? '' : esc_attr( $instance['orderby'] );
		$order  		= empty( $instance['order'] ) ? '' : esc_attr( $instance['order'] );
		$meta_value  	= empty( $instance['meta_value'] ) ? '' : esc_attr( $instance['meta_value'] );
		
		?>

		<div class="list_post_one_fields">
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label></p>
			<p><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" /></p>
			<p><input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_title' ) ); ?>" <?php echo $show_title?'checked':'';?> /><label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>"><?php _e( 'Show Title' ); ?></label></p>
			<p><input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'same_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'same_category' ) ); ?>" <?php echo $same_category?'checked':'';?> /><label for="<?php echo esc_attr( $this->get_field_id( 'same_category' ) ); ?>"><?php _e( 'Show posts in the same category' ); ?></label></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _e( 'Category' ); ?>:</label></p>
			<p><select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
				<option value="0"><?php _e('All');?></option>
			<?php foreach(get_categories() as $item):?>
				<option value="<?php echo $item->term_id;?>" <?php selected( $item->term_id, $category);?>><?php echo $item->cat_name;?></option>
			<?php endforeach;?>
			</select></p>
			<p><label for="<?php echo $this->get_field_id( 'displays' ); ?>"><?php _e( 'Display' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'displays' ); ?>" name="<?php echo $this->get_field_name( 'displays' ); ?>" type="text" value="<?php echo $displays; ?>"  style="width:70px;"/></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e( 'Order By:' ); ?></label>
			<p><select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" onchange="var c = this.value.search('meta_value'), p = jQuery(this).parents('.list_post_one_fields').find('.order_by_meta_value').css( 'display', ( c>-1 ? 'block' : 'none') ); if( c>-1 ) p.find('input').focus();">
			<?php foreach( array( 
				'' => 'Default', 
				'menu_order' => 'Order', 
				'title' => 'Title', 
				'meta_value' => 'Meta Value', 
				'meta_value_num' => 'Meta Value Number' 
				) as $k => $v):?>
				<option value="<?php echo $k;?>" <?php selected( $k, $orderby);?>><?php echo $v;?></option>
			<?php endforeach;?>
			</select></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e( 'Order:' ); ?></label>
			<p><select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
			<?php foreach( array( 'DESC', 'ASC' ) as $v):?>
				<option value="<?php echo $v;?>" <?php selected( $v, $order);?>><?php echo $v;?></option>
			<?php endforeach;?>
			</select></p>

			<div class="order_by_meta_value" style="display:<?php echo preg_match('/meta_value/i', $orderby) ? 'block' :'none' ;?> ">
				<p><label for="<?php echo esc_attr( $this->get_field_id( 'meta_value' ) ); ?>"><?php _e( 'Meta Value:' ); ?></label></p>
				<p><input id="<?php echo esc_attr( $this->get_field_id( 'meta_value' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'meta_value' ) ); ?>" value="<?php echo esc_attr( $meta_value ); ?>" /></p>
			</div>
		</div>		
		<?php
	}
	
	/**
	 * Output the HTML for this widget.
	 *
	 * @access public
	 * @since List Posts 1.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {
		
		$title  		= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$category 		= empty( $instance['category'] ) ? 0 : absint( $instance['category'] );
		$same_category 	= empty( $instance['same_category'] ) ? 0 : absint( $instance['same_category'] );
		$displays 		= empty( $instance['displays'] ) ? 3 : absint($instance['displays']);
		$orderby  		= empty( $instance['orderby'] ) ? '' : esc_attr( $instance['orderby'] );
		$order  		= empty( $instance['order'] ) ? 'DESC' : esc_attr( $instance['order'] );
		$meta_value		= empty( $instance['meta_value'] ) ? '' : esc_attr( $instance['meta_value'] );
		
		$params = array(
			'category' 			=> $category,
			'posts_per_page' 	=> $displays,
		);
		
		// if( isset($_GET['legacy-widget-preview']) ) {
		// 	$same_category = 0;
		// }
		
		if( $same_category ) {
			if( is_single() ) {
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					$title = $categories[0]->name;
					$params['category'] 	= $categories[0]->term_id;
					$params['exclude'] 	= get_the_ID();
				}
			} else {
				return '';
			}
		}

		if( $orderby!='' ){
			$params['orderby'] 	= $orderby;
			$params['order'] 		= $order;
			
			if( preg_match('/meta_value/i',$orderby) ){
				$params['meta_key'] = $meta_value;
			}
		}
		
		$posts = get_posts($params);
		if( $count = count($posts) ):
			echo isset($args['before_widget']) ? $args['before_widget'] : '';
			
			if ( $title != '' && $show_title ) :
				echo isset($args['before_title']) ? $args['before_title'] : '';
				echo $title;
				echo isset($args['after_title']) ? $args['after_title'] : '';
			endif;	
		?>
			<div class="list_one_main clearfix">
				<ul>
					<?php foreach($posts as $j => $p) : $i = $j+1; ?>
					<li class="item-<?php echo $i.($count==$i?' item-last':'')?>">
						<a href="<?php echo get_permalink($p->ID); ?>" rel="bookmark"><?php echo $p->post_title; ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
			echo isset($args['after_widget']) ? $args['after_widget'] : '';
		endif;
	}

}

// setup widget
add_action( 'widgets_init', function(){
	register_widget( 'List_Post_One_Widget' );
});