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
		
	}
	
	
	
	
	public function init() {
		
		
		$this->get_post_types()->discover_archives();
		
		//v_dump( $this->terms );

		
		//Label the page set as this posts archives
		add_filter( 'display_post_states', array( $this, 'archive_page_label'), 10, 2 );
		
	
		
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