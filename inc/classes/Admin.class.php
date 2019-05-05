<?php
/**
 *		Modifications and Customizations to the 
 *		wp-admin section
 *
 */
namespace Bric;

class Admin {
	
	
	var $post_types = [];
	var $taxonomies = [];
	
	public function __construct() {
		
		
		add_action( 'init', [ $this, 'init'] );
		
		//Doesn't work with gutenberg
		//Featured image / Thumbnail extra fields
	//	add_filter( 'admin_post_thumbnail_html', [ $this, 'add_featured_image_display_settings'], 10, 2 );

	}
	
	
	
	
	public function init() {
		
		
		$this->get_post_types()->discover_archives();
		
		//v_dump( $this->terms );

		
		//Label the page set as this posts archives
		add_filter( 'display_post_states', array( $this, 'archive_page_label'), 10, 2 );
		

	
		
	}
	
	
	
	/**
	 *	This doesn't work with Gutenberg
	 *
	 *
	 */
	
	
	public function add_featured_image_display_settings( $content, $post_id ) {
		
		
		$field_id    = 'show_featured_image';
		$field_value = esc_attr( get_post_meta( $post_id, $field_id, true ) );
		$field_text  = esc_html__( 'Show image.', 'generatewp' );
		$field_state = checked( $field_value, 1, false);

		$field_label = sprintf(
			'<p><label for="%1$s"><input type="checkbox" name="%1$s" id="%1$s" value="%2$s" %3$s> %4$s</label></p>',
			$field_id, $field_value, $field_state, $field_text
		);

		return $content .= $field_label;

	}
	
	
	
	public function get_post_types() {
		
		
		$this->post_types = get_post_types( [
			'public' => true,
			'has_archive' => true,
		]);
		

		/*$this->taxonomies = get_object_taxonomies( $this->post_types, 'objects');

		
		foreach ( $this->taxonomies as $tax ) {
			
			$this->terms[] = get_terms([
				'taxonomy' => $tax->name,
				'hide_empty' => false,
			]);
			
		}
		*/
				
		return $this;		
		
	}
	
	
	
	
	public function discover_archives() {
		
		$pts = [];
		
		foreach ( $this->post_types as $pt ) {
			
			$pt_obj = get_post_type_object( $pt );
			
			$pts[$pt] = $pt_obj;
			
			$this->slugs[$pt_obj->rewrite['slug']] = $pt_obj;
			
		}
		
		$this->post_types = $pts;
		
		return $this;
		
	}
	
	
	
	
	
	
	public function archive_page_label( $post_states, $post ) {
			
		
		if ( isset( $this->slugs[ $post->post_name ] ) ) {

			
			$post_type_obj = $this->slugs[ $post->post_name ];
			

			$post_states[] = $post_type_obj->label.' Landing Page';



			
		}
		
		
		
		
		return $post_states;
		
		
	}
	
	
	
	
	
	
	
	
	
	
}

new Admin;