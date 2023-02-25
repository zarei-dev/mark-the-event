<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

use ExtCPTs\PostType;
use ExtCPTs\PostTypeAdmin;
use ExtCPTs\Taxonomy;
use ExtCPTs\TaxonomyAdmin;

class EventCPT {
    
    public function RegisterEventPostType() {

        if ( ! did_action( 'init' ) ) {
            trigger_error( esc_html__( 'Post types must be registered on the "init" hook.', 'extended-cpts' ), E_USER_WARNING );
        }

        $args = [
    
            # Add the post type to the site's main RSS feed:
            'show_in_feed' => true,
    
            # Show all posts on the post type archive:
            'archive' => [
                'nopaging' => true,
            ],
    
            # Add some custom columns to the admin screen:
            'admin_cols' => [
                'story_featured_image' => [
                    'title'          => 'Image',
                    'featured_image' => 'thumbnail'
                ],
                'story_published' => [
                    'title_icon'  => 'dashicons-calendar-alt',
                    'meta_key'    => 'published_date',
                    'date_format' => 'd/m/Y'
                ],
                'event_city' => [
                    'taxonomy' => 'city'
                ],
            ],
    
            # Add some dropdown filters to the admin screen:
            'admin_filters' => [
                'event_city' => [
                    'taxonomy' => 'city'
                ],
                'story_rating' => [
                    'meta_key' => 'star_rating',
                ],
            ],
    
        ];

        $cpt = new PostType( 'event', $args, [
    
            # Override the base names used for labels:
            'singular' => 'Event',
            'plural'   => 'Events',
            'slug'     => 'events',
    
        ] );

        $cpt->init();
    
        if ( is_admin() ) {
            $admin = new PostTypeAdmin( $cpt, $cpt->args );
            $admin->init();
        }
    }

    public function RegisterEventTaxonomies() {
        if ( ! did_action( 'init' ) ) {
            trigger_error( esc_html__( 'Taxonomies must be registered on the "init" hook.', 'extended-cpts' ), E_USER_WARNING );
        }

        $args = [
        
            # Use radio buttons in the meta box for this taxonomy on the post editing screen:
            'meta_box' => 'radio',
        
            # Show this taxonomy in the 'At a Glance' dashboard widget:
            'dashboard_glance' => true,
        
            # Add a custom column to the admin screen:
            'admin_cols' => [
                'updated' => [
                    'title'       => 'Updated',
                    'meta_key'    => 'updated_date',
                    'date_format' => 'd/m/Y'
                ],
            ],
        
        ];
    
        $taxo = new Taxonomy( 'city', ['event'], $args, [
            'singular' => 'City',
            'plural'   => 'Cities',
            'slug'     => 'event-cities'
        ] );

        $taxo->init();
    
        if ( is_admin() ) {
            $admin = new TaxonomyAdmin( $taxo, $taxo->args );
            $admin->init();
        }
    }
}