<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

class EventUpdator {

    public $post_id;
    public $data;

    public function __construct( $post_id, $data )
    {
        $this->post_id = $post_id;
        $this->data = $data;

        return $this->Update();
    }

    public function Validate()
    {
        return true;
    }

    public function Update()
    {
        if ( ! $this->Validate() ) {
            return;
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

    public function UpdateMeta() : bool 
    {
        foreach( $this->data['meta'] as $key => $value ) {
            update_field( $key, $value, $this->post_id );
        }

        return true;
    }

    public function UpdateTaxonomy() : void 
    {
        $term = get_term( $this->data['taxonomy']['city'], 'city' );
        wp_set_post_terms( $this->post_id, $term->term_id, 'city' );
    }

}