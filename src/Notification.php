<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

/**
 * Send email to users when the post is published
 * We use the Gravity Forms API to send email
 * 
 * @since 0.0.1
 */
class Notification {

    /**
     * Form ID
     * 
     * @var int
     */
    public $form_id;

    /**
     * Entry ID
     * 
     * @var int
     */
    public $entry_id;

    /**
     * Users
     * 
     * @var array
     */
    public $users;

    /**
     * Send email to users if the post is published and the post type is event
     * 
     * @param string $new_status
     * @param string $old_status
     * @param object $post
     * 
     * @since 0.0.1
     * @see https://developer.wordpress.org/reference/hooks/post_status_transition/
     */
    public function MaybeSendNotification( $new_status, $old_status, $post ) {

        if ( $new_status != 'publish' && $old_status == 'publish' || $post->post_type != 'event' ) {
            return false;
        }

        $this->form_id = get_post_meta( $post->ID, 'gform_id', true );
        $this->entry_id = get_post_meta( $post->ID, 'entry_id', true );

        $this->SendEmail( $post );

    }

    /**
     * Send email to user
     * 
     * @param object $user
     * @param object $post
     * @return bool
     * 
     * @since 0.0.1
     */
    public function SendEmail( $post ) : bool {

        $form = \GFAPI::get_form( $this->form_id );

        $entry = \GFAPI::get_entry( $this->entry_id );

        // Send email using Gravity Forms API
        $result = \GFAPI::send_notifications( $form, $entry, 'after_publish_post' );

        return $result;
    }


    /**
     * Add a new notification event to the list of available events of Gravity Forms
     * 
     * @param array $events
     * @return array
     * 
     * @since 0.0.1
     * @see https://docs.gravityforms.com/gform_notification_events/
     */
    public function AddNotificationEvent( $events ) : array
    {
        $events['after_publish_post'] = __('After Publish Post', 'mark-the-event');

        return $events;
    }

    /**
     * Change the email recipients of the notification only if the form is an event form
     * 
     * @param array $events
     * @return array
     * 
     * @since 0.0.1
     * @see https://docs.gravityforms.com/gform_pre_send_email/
     */
    public function FilterEmailRecivers( $email, $message_format, $notification, $entry ) : array {

        // Check to see if this is the notification we want to modify
        $form_id = $entry['form_id'];
        $markTheEvent = gform_get_meta( $form_id, '_gform_setting_markTheEvent' );
    
        if ( $markTheEvent != "on" ) {
            return $email;
        }
        
        // early return if the city is not set
        $city = get_the_terms( $entry['post_id'], 'city' )[0];

        if ( ! $city ) {
            return $email;
        }

        // The real magic happens here
        $this->users = get_users( array(
            'meta_key' => 'city',
            'meta_value' => $city->term_id,
        ) );

        $emails = [];

        foreach ( $this->users as $user ) {
            $emails[] = $user->user_email;
        }

        if ( ! $emails ) {
            return $email;
        }

        // Set the email recipients
        $email['to'] = implode( ',', $emails );
    
        return $email;
    }

}