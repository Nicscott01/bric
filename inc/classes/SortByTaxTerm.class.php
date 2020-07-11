<?php
/**
 *      Class to help sorting a bunch of queried posts by taxonomy term
 *
 *
 *
 */


class SortByTaxTerm {
    
    
    
    /**
     *  Vars
     */
    
    public $posts;
    public $sorted_posts;
    public static $taxonomy;
    
    
    public function __construct( $posts = [], $taxonomy = '' ) {
        
        
        if ( !empty( $posts ) && !empty( $taxonomy ) ) {
            
            $this->posts = $posts;
            self::$taxonomy = $taxonomy;

            
            $this->output_posts_sorted_by_term();
            
        }
        
        
        
        
    }
    
    
    
    
    
    // Call just-registered functions to get sorted array of WP_Post objects,
    // then output with foreach()
    public function output_posts_sorted_by_term() {

        //$posts = wpshout_fetch_posts_in_category_taxonomy();

        
        
        // Return if no results
        if( ! is_array( $this->posts ) ) {
            return false;
        }

        // Add category WP_Term object as a property to each WP_Post object
        $this->add_term_objects_to_posts();

        //var_dump( $this->posts );
        
        // Sort posts by category name
        //usort( $this->posts, [ $this, 'sort_posts_by_term' ] );
        //usort( $this->posts, [ $this, 'sort_posts_by_title' ] );

        //var_dump( $this->posts );
        
        //Put each post into a term array
        $arrays_by_term = $this->arrays_by_term( 'post_title' );
        
        
       // var_dump( $arrays_by_term );
        
        
        /*
        $posts_array = [];
        
        foreach( $this->posts as $post ) {
                        
            $post_arr = (array) $post;
            
            foreach( $post_arr as $k => $pa ) {
                
                if ( is_object( $pa ) ) {
                    
                    $post_arr[$k] = (array) $pa;
                }
            }
            
            $posts_array[] = $post_arr;
            
            
            
        }
        
        var_dump( $posts_array );
        */
        
       // $this->sorted_posts = $posts;
        
        return $this;
        
    }


    
    
    // Customize each of the fetched WP_Post objects: each will have a
    // 'category' property containing the WP_Term object of its first category
    public function add_term_objects_to_posts() {
        
        $taxonomy = self::$taxonomy;
        
        foreach( $this->posts as $post_index => $current_post ) :
            // Get array of WP_Term category terms for the current post
            $terms = get_the_terms( $current_post, self::$taxonomy );
            // Save the first WP_Term object to the WP_Post object
            $current_post->$taxonomy = $terms;
            // Update the $posts array with the modified WP_Post object
            $this->posts[$post_index] = $current_post;

        endforeach;

        // Return array of modified WP_Post objects
        return $this;

    }

    
    
    
    // Define sorting function to sort by category name
    public function sort_posts_by_term( $a, $b ) {

        $taxonomy = self::$taxonomy;
        
        return strcmp( 
            wp_strip_all_tags( $a->$taxonomy->name ),
            wp_strip_all_tags( $b->$taxonomy->name )
        );

    }

    
    // Define sorting function to sort by category name
    public function sort_posts_by_post_title( $a, $b ) {
        
        return strcmp( 
            wp_strip_all_tags( $a->post_title ),
            wp_strip_all_tags( $b->post_title )
        );

    }

    
    
    
    public function arrays_by_term( $sortby = null ) {
        
        $arrays_by_term = [];
        
        $taxonomy = self::$taxonomy;
        
                
        /*
        
        //Put the posts in arrays based on term slug
        foreach( $this->posts as $post ) {
            
            if ( is_array( $post->taxonomy ) ) {
                
                foreach( $post->taxonomy as $term ) {
                    
                    $arrays_by_term[$term->slug][] = $post;
                    
                }
                
                
            }
            
            
        }*/
        
        
        
        //Get the terms so we can order
        $terms = get_terms( $taxonomy, [
            'orderby' => 'term_order', 
        ]);

        //var_dump( $terms );
        
        
        $arrays_by_term_sorted = [];
        //Put the terms in order
        foreach( $terms as $term ) {
            
            //Look through the posts and throw it in this array
            foreach( $this->posts as $post ) {
                
                if ( is_array( $post->$taxonomy ) ) {
                    
                    foreach( $post->$taxonomy as $post_term ) {
                        
                        if ( $post_term->slug == $term->slug ) {
                            
                            $arrays_by_term_sorted[$term->slug][] = $post;
                            
                        }
                    }
                    
                }
            }
            
                        
        }
        
        
        $arrays_by_term = $arrays_by_term_sorted;
        

        
        
        if ( isset( $sortby ) ) {
            
            foreach( $arrays_by_term as $k => $term_group ) {
                
                usort( $term_group, [ $this, 'sort_posts_by_' . $sortby ] );                
            
                $arrays_by_term[$k] = $term_group;
                
            }

            
           /* 
            $this->sorted_posts = []; 
            
            foreach ( $arrays_by_term as $group ) {
                
                $this->sorted_posts = array_merge( $this->sorted_posts, $group );
                
            }
            
            */
            
        }
        
        
        //var_dump( $arrays_by_term );
        
        $this->sorted_posts = $arrays_by_term;
        
        
        return $this;
        
    }
    
    
    


}