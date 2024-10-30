<?php
/**
 * Register a Knowledge Base shortcode.
 *
 * @package     knowledge-base-cpt
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Register the shortcode.
 *
 * @param array $atts User definited attributes.
 */
function ot_knowledge_shortcode( $atts ) {

	// See WP_Term_Query::__construct() for information on accepted arguments.
	$a = shortcode_atts( array(
		'taxonomy'               => 'section',
		'object_ids'             => null,
		'orderby'                => 'name',
		'order'                  => 'ASC',
		'hide_empty'             => true,
		'include'                => array(),
		'exclude'                => array(),
		'exclude_tree'           => array(),
		'number'                 => '',
		'offset'                 => '',
		'fields'                 => 'all',
		'count'                  => false,
		'name'                   => '',
		'slug'                   => '',
		'term_taxonomy_id'       => '',
		'hierarchical'           => true,
		'search'                 => '',
		'name__like'             => '',
		'description__like'      => '',
		'pad_counts'             => false,
		'get'                    => '',
		'child_of'               => 0,
		'parent'                 => '',
		'childless'              => false,
		'cache_domain'           => 'core',
		'update_term_meta_cache' => true,
		'meta_query'             => '',
		'meta_key'               => '',
		'meta_value'             => '',
		'meta_type'              => '',
		'meta_compare'           => '',
	), $atts );

	$return = '';

	// Get Knowledge Base Sections.
	$sections = get_terms( $a );

	// For each knowledge base section.
	foreach ( $sections as $section ) {

	    $return .= '<div class="kb-section">';

	    // Display Section Name.
	    $return .= '<h4 class="kb-section-name"><a href="' . get_term_link( $section ) . '" title="' . $section->name . '" >' . $section->name . '</a></h4>';

		$return .= '<ul class="kb-articles-list">';

	    // Fetch posts in the section.
	    $kb_args = array(
	        'post_type'      => 'knowledge_base',
	        'posts_per_page' => -1,
	        'tax_query'      => array(
	            array(
	                'taxonomy'            => 'section',
	                'terms'               => $section,
	                'include_children'    => false,
	            ),
	        ),
	    );

	    $the_query = new WP_Query( $kb_args );

		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) :
				$the_query->the_post();
	            $return .= '<li class="kb-article-name">';
	            $return .= '<a href="' . get_permalink( $the_query->ID ) . '" rel="bookmark" title="' . get_the_title( $the_query->ID ) . '">' . get_the_title( $the_query->ID ) . '</a>';
	            $return .= '</li>';
			endwhile;
	    	wp_reset_postdata();
			else :
				$return .= '<p>' . esc_html__( 'No Articles Found', 'ot-knowledge' ) . '</p>';
	        endif;
			$return .= '</ul></div>';
	} // End foreach().
	return $return;
}
// Create shortcode.
add_shortcode( 'knowledgebase', 'ot_knowledge_shortcode' );
