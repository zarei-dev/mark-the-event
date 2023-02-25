<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

class GravityFormEvent {

    /**
     * Update city field select in gravity form if the field has the class event-cities
     * We set the choices of the field to the city taxonomy
     * 
     * @param array $form
     * 
     * @since 0.0.1
     * @see https://docs.gravityforms.com/gform_pre_render/
     * @see https://docs.gravityforms.com/gform_pre_validation/
     * @see https://docs.gravityforms.com/gform_pre_submission_filter/
     * @see https://docs.gravityforms.com/gform_admin_pre_render/
     */
    public function UpdateCityFieldSelect( $form )
    {

        foreach ( $form['fields'] as &$field ) {
 
            if ( $field->type != 'select' || strpos( $field->cssClass, 'event-cities' ) === false ) {
                continue;
            }
        
            // get city taxonomy
            $cities = get_terms( array(
                'taxonomy' => 'city',
                'hide_empty' => false,
            ) );
    
        
            $choices = array();
        
            foreach ( $cities as $city ) {
                $choices[] = array( 'text' => $city->name, 'value' => $city->term_id );
            }
        
            $field->placeholder = 'Select a City';
            $field->choices = $choices;
        
        }
        
        return $form;
    }

    /**
     * Get all gravity forms that has mark the event option enabled
     * 
     * @since 0.0.1
     * @return array
     */
    public function GetEventForms() : array
    {
        // Get all gravity forms
        $forms = \GFAPI::get_forms();


        $event_forms = [];

        foreach ( $forms as $form ) {
            // form id 
            $form_id = $form['id'];

            $markTheEvent = gform_get_meta( $form_id, '_gform_setting_markTheEvent' );

            if($markTheEvent == "on") {
                $event_forms[] = $form_id;
            }
        }

        return $event_forms;
    }

    /**
     * Add mark the event option to gravity form setting page
     * 
     * @since 0.0.1
     * @see https://docs.gravityforms.com/gform_form_settings/
     */
    public function RegisterSettingPage( array $menu_items ) : array
    {
    
        $menu_items[] = array(
            'name'       => 'mark_the_event_settings_page',
            'label'      => esc_html__( 'Mark The Event', 'mark-the-event' ),
        );
    
        return $menu_items;
    }

    /**
     * Load mark the event setting page
     * 
     * @since 0.0.1
     */
    public function LoadSettingPage()
    {
        require_once MARK_THE_EVENT_DIR . 'admin/GravityFormEventSettingPage.php';
    }

    /**
     * Create event post after form submission
     * Change post status, meta, and more with EventUpdator class
     * 
     * @since 0.0.1
     * @see https://docs.gravityforms.com/gform_after_submission/
     */
    public function AfterSubmission( $entry, $form ) : void
    {

        $form_id = $form["id"];

        $markTheEvent = gform_get_meta( $form_id, '_gform_setting_markTheEvent' );
        
        if($markTheEvent != "on") {
            return;
        }

        $markTheEventPostStatus = gform_get_meta( $form_id, '_gform_setting_markTheEventPostStatus' );
        $post_id = $entry['post_id'];

        $event_fields = [
            'EventName' => 'Event Name',
            'EventDescription' => 'Event Description',
            'EventImage' => 'Event Image',
            'EventDate' => 'Event Date',
            'EventCity' => 'Event City',
        ];

        foreach($event_fields as $key => $value) {
            $event_fields_data[$key] = gform_get_meta( $form_id, '_gform_setting_markTheEventField__'.$key );
        }

        $data = [
            'post_type'     => 'event',
            'post_status'   => $markTheEventPostStatus,
            'meta'          => [
                'event_name' => $entry[$event_fields_data['EventName']],
                'event_description' => $entry[$event_fields_data['EventDescription']],
                'event_image' => $entry[$event_fields_data['EventImage']],
                'event_date' => $entry[$event_fields_data['EventDate']],
                'event_city' => $entry[$event_fields_data['EventCity']],
            ],
            'taxonomy'     => [
                'city' => $entry[$event_fields_data['EventCity']],
            ],
        ];

        $event = new EventUpdator($post_id, $data);

        // Add form id to post meta
        update_post_meta( $post_id, 'gform_id', $form_id );
        update_post_meta( $post_id, 'entry_id', $entry['id'] );

        return;
    }
}