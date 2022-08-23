<?php


class ACFBlocks {
	
	
	
	public function __construct() {
		
		add_action( 'acf/init', [ $this, 'load_parent_blocks' ] );
		add_action( 'acf/init', [ $this, 'load_child_blocks' ] );

	}

	public function load_parent_blocks() {

		$this->load_blocks(  get_template_directory() . '/inc/acf-blocks' );
		
	}

	public function load_child_blocks() {

		$this->load_blocks(  get_stylesheet_directory() . '/inc/acf-blocks' );
		
	}



	public function load_blocks( $folder ) {

		if (  !file_exists( $folder ) ) {
			return false;
		}

		//add_action( 'init', [ $this, 'init'] );
		$files = scandir( $folder );
		
		if ( !empty( $files ) && is_array( $files ) ) {

			foreach( $files as $file ) {

				if ( strpos( $file, '.' ) > 3 ) {

					$path = $folder . '/' . $file;

					if ( file_exists( $path ) ) {

						include_once( $path );
					
					}

				}

			}
		}


	}
	
	
	
	
	
	
}

new ACFBlocks();