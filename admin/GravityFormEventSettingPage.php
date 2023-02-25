<?php

\GFFormSettings::page_header();

$form_id = absint( rgget( 'id' ) );

$form_fields = \GFAPI::get_form( $form_id )[ 'fields'];

$event_fields = [
    'EventName' => 'Event Name',
    'EventDescription' => 'Event Description',
    'EventImage' => 'Event Image',
    'EventDate' => 'Event Date',
    'EventCity' => 'Event City',
];

if ( rgpost( 'gform-settings-save' ) ) {
    check_admin_referer( 'gform-settings-save', 'gform-settings-save-nonce' );

    $markTheEvent = rgpost( '_gform_setting_markTheEvent' );
    $markTheEventPostStatus = rgpost( '_gform_setting_markTheEventPostStatus' );


    foreach($event_fields as $key => $value) {
        $event_data = rgpost( '_gform_setting_markTheEventField__'.$key );
        gform_update_meta( $form_id, '_gform_setting_markTheEventField__'.$key, $event_data );
        $event_fields_data[$key] = $event_data;
    }


    gform_update_meta( $form_id, '_gform_setting_markTheEvent', $markTheEvent );
    gform_update_meta( $form_id, '_gform_setting_markTheEventPostStatus', $markTheEventPostStatus );

    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Settings saved.', 'mark-the-event' ) . '</p></div>';

} else {

    $markTheEvent = gform_get_meta( $form_id, '_gform_setting_markTheEvent' );
    $markTheEventPostStatus = gform_get_meta( $form_id, '_gform_setting_markTheEventPostStatus' );

    foreach($event_fields as $key => $value) {
        $event_data = gform_get_meta( $form_id, '_gform_setting_markTheEventField__'.$key );
        $event_fields_data[$key] = $event_data;
    }
}

$markTheEvent = $markTheEvent ? 'on' : 'off';
?>

<form id="gform-settings" class="gform_settings_form" data-js="page-loader" action="" method="post" enctype="multipart/form-data" novalidate="">

    <fieldset id="gform-settings-section-form-button" class="gform-settings-panel gform-settings-panel--with-title">
        <legend class="gform-settings-panel__title gform-settings-panel__title--header">Mark the Event Settings</legend>
        <div class="gform-settings-panel__content">


            <div id="gform_setting_markTheEvent" class="gform-settings-field gform-settings-field__toggle">
                <div class="gform-settings-field__header">
                    <label class="gform-settings-label" for="markTheEvent">Save Entries to `event` post type?</label>
                    <button onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_validation_summary" aria-label="<strong>Enable?</strong>Enable to save entries to `event` post type">
                        <i class="gform-icon gform-icon--question-mark" aria-hidden="true"></i>
                    </button>
                </div>
                <span class="gform-settings-input__container">
                    <input type="checkbox" name="_gform_setting_markTheEvent" id="_gform_setting_markTheEvent" value="<?php echo $markTheEvent; ?>" <?php checked( $markTheEvent, 'on' ); ?>>
                    <label class="gform-field__toggle-container" for="_gform_setting_markTheEvent">
                        <span class="gform-field__toggle-switch"></span>
                    </label>
                </span>
            </div>



            <!-- selecting post status -->
            <div id="gform_setting_markTheEventPostStatus" class="gform-settings-field gform-settings-field__toggle">
                <div class="gform-settings-field__header">
                    <label class="gform-settings-label" for="markTheEventPostStatus">Post Status</label>
                    <button onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_validation_summary" aria-label="<strong>Post Status</strong>Post Status">
                        <i class="gform-icon gform-icon--question-mark" aria-hidden="true"></i>
                    </button>
                </div>
                <span class="gform-settings-input__container">
                    <select name="_gform_setting_markTheEventPostStatus" id="markTheEventPostStatus">
                        <?php
                        $post_statuses = get_post_statuses();
                        foreach ( $post_statuses as $post_status => $label ) {
                            ?>
                            <option value="<?php echo $post_status; ?>" <?php selected( $markTheEventPostStatus, $post_status ); ?>><?php echo $label; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </span>
            </div>

            <h2 style="margin-top: 40px;">Post Fields</h2>
            <p>
                Select the form fields that match the post fields. This will be used to create the post when the form is submitted.
            </p>


            <?php

            foreach($event_fields as $key => $value) {
                ?>
                <div id="gform_setting_markTheEventField__<?php echo $key; ?>" class="gform-settings-field gform-settings-field__toggle">
                    <div class="gform-settings-field__header">
                        <label class="gform-settings-label" for="markTheEventField__<?php echo $key; ?>"><?php echo $value; ?></label>
                        <button onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_validation_summary" aria-label="<strong><?php echo $value; ?></strong>Select the matched field">
                            <i class="gform-icon gform-icon--question-mark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <span class="gform-settings-input__container">
                        <select name="_gform_setting_markTheEventField__<?php echo $key; ?>" id="markTheEventField__<?php echo $key; ?>">
                            <option value="">Select one of the form field</option>
                            <?php
                            foreach ( $form_fields as $field ) {
                                ?>
                                <option value="<?php echo $field->id; ?>" <?php selected( $event_fields_data[$key], $field->id ); ?>><?php echo $field->label; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </span>
                </div>
                <?php
            }

            ?>


        </div>
    </fieldset>
    <div class="gform-settings-save-container">
        <button type="submit" id="gform-settings-save" name="gform-settings-save" value="save" form="gform-settings" class="primary button large"><?php esc_attr_e( 'Save Settings &nbsp;→' ); ?></button>
        <?php wp_nonce_field( 'gform-settings-save', 'gform-settings-save-nonce' ); ?>
    </div>

</form>

<?php
\GFFormSettings::page_footer();