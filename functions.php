<?php
/**
 *		Theme: Bric
 *		@author: Nic Scott
 *		@date September 2017
 *
 *
 *		Theme for basic small-business brochure-ware websites.
 *
 *
 */


include get_template_directory().'/inc/functions/bric_functions.php';







//add_action( 'init', 'test123' );

function test123() {
	
	global $wp_query;
	
	var_dump( $wp_query );
	
	var_dump( $wp_query->max_num_pages );
	
}





//add_action( 'init', 'test_php_command_line' );


function test_php_command_line( $commit = 'dev' ) {
	
	/*if ( !isset( $commit ) ) {
		
		$commit = 'dev';
		
	}*/
	
	//if ( $_GET['phpcl']) {
		
		/**
		 *	export PATH=$HOME/webapps/sass/bin:$PATH
			export GEM_HOME=$HOME/webapps/sass/gems
			export RUBYLIB=$HOME/webapps/sass/lib
		*/
		
		
		//var_dump( get_template_directory() );
		
		//var_dump( $_SERVER );
		/*
		$uid = posix_getuid();
		$shell_user = posix_getpwuid($uid);
		$home = $shell_user['dir'];
		*/
		
		$env = array(
			array( 'PATH', '/home/creare2/webapps/node/bin' ),
			array( 'PATH', '/home/creare2/webapps/sass/bin' ),
			array( 'GEM_HOME', '/home/creare2/webapps/sass/gems' ),
			array( 'RUBYLIB', '/home/creare2/webapps/sass/lib' ),			
		);
		
		/*
		$node_path = '/home/creare2/webapps/node/bin';
		$sass_path = '/home/creare2/webapps/sass/bin';
		$gem_home = '/home/creare2/webapps/sass/gems';
		$ruby_lib = '/home/creare2/webapps/sass/lib';
		*/
		
		
		$command = sprintf( 'cd %s && grunt sass:%s --no-color', get_stylesheet_directory(), $commit );
		//$command = '';
		
		
		foreach ( $env as $putenv ) {
			
			$ext = ( $putenv[0] == 'PATH' ) ? ':'.getenv('PATH') : '';
			
			$put = $putenv[0] .'='. $putenv[1] . $ext;
			
			if ( $_GET['front_end'] ) {
				var_dump( $put );
			}
			putenv( $put );
			
		}
		
		/*		
		putenv( 'PATH='.$node_path.':'.getenv('PATH') );
		putenv( 'PATH='.$sass_path.':'.getenv('PATH') );
		putenv( 'GEM_HOME='.$home.'/webapps/sass/gems');
		putenv( 'RUBYLIB='.$home.'/webapps/sass/lib');
		*/
		
		//var_dump( $command );
		//	$exec = exec( get_template_directory().' whoami', $output );
		exec( $command , $output );
		
		//$output = exec( escapeshellcmd( $command ) );
		
		if ( $_GET['front_end']) {
			
			var_dump (  $output  );

			wp_die();
		}
		
		
		return $output;
	//}
	
	
}