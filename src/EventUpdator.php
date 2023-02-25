<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

class EventUpdator {

    public $post_id;
    public $data;

    /**
     * Update Event constructor
     * 
     * @param int $post_id
     * @param array $data
     * 
     * @since 0.0.1
     */
    public function __construct( $post_id, $data )
    {
        $this->post_id = $post_id;
        $this->data = $data;

        return $this->Update();
    }

    /**
     * Validate data
     * 
     * @TODO: Add validation
     */
    public function Validate()
    {
        return true;
    }

    /**
     * Update Event
     * Change post type, post status, post title, post content, ACF fields and taxonomy
     * 
     * @since 0.0.1
     */
    public function Update() : bool
    {
        if ( ! $this->Validate() ) {
            return false;
        }
        

        // Change post type
        set_post_type( $this->post_id, $this->data['post_type'] );

        // Change post status
        wp_update_post([
            'ID' => $this->post_id,
            'post_status' => $this->data['post_status']
        ]);

        // Update Post
        $this->UpdatePost();

        // Update ACF Fields
        if( class_exists('ACF') ) {
            $this->UpdateMeta();
        }

        // Update Taxonomy
        $this->UpdateTaxonomy();

        return true;
    }

    /**
     * Update Post
     * Change post title, post content, post status and post type
     * 
     * @since 0.0.1
     * @see https://developer.wordpress.org/reference/functions/wp_update_post/
     */
    public function UpdatePost()
    {
        $post = [
            'ID' => $this->post_id,
            'post_title' => $this->data['meta']['event_name'],
            'post_content' => $this->data['meta']['event_description'],
            'post_status' => $this->data['post_status'],
            'post_type' => $this->data['post_type'],
        ];

        wp_update_post( $post );
    }

    /**
     * Update ACF Fields
     * 
     * @since 0.0.1
     * @see https://www.advancedcustomfields.com/resources/update_field/
     * 
     * @return bool
     */
    public function UpdateMeta() : bool 
    {
        foreach( $this->data['meta'] as $key => $value ) {
            update_field( $key, $value, $this->post_id );
        }

        return true;
    }

    /**
     * Update Taxonomy
     * 
     * @since 0.0.1
     * @see https://developer.wordpress.org/reference/functions/wp_set_post_terms/
     * 
     * @return void
     */
    public function UpdateTaxonomy() : void 
    {
        $term = get_term( $this->data['taxonomy']['city'], 'city' );
        wp_set_post_terms( $this->post_id, $term->term_id, 'city' );
    }

}