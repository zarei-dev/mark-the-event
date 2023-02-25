<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

class User {

    /**
     * Add city field to user profile
     * 
     * @since 0.0.1
     */
    public function AddCityUserMetaFields( $user ) {

        // get city taxonomy
        $cities = get_terms( array(
            'taxonomy' => 'city',
            'hide_empty' => false,
        ) );

        $choices = array();
    
        foreach ( $cities as $city ) {
            $choices[] = array( 'text' => $city->name, 'value' => $city->term_id );
        }

        ?>
        <h3><?php esc_html_e( 'City', 'mark-the-event' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="city"><?php esc_html_e( 'City', 'mark-the-event' ); ?></label></th>
                <td>
                    <select name="city" id="city">
                        <option value=""><?php esc_html_e( 'Select a City', 'mark-the-event' ); ?></option>
                        <?php foreach ( $choices as $choice ) : ?>
                            <option value="<?php echo esc_attr( $choice['value'] ); ?>" <?php selected( $choice['value'], get_the_author_meta( 'city', $user->ID ) ); ?>><?php echo esc_html( $choice['text'] ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save city field to user profile
     * 
     * @since 0.0.1
     */
    public function SaveCityUserMetaFields( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        update_user_meta( $user_id, 'city', $_POST['city'] );
    }
}