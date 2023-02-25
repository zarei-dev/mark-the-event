# Mark the Event

Event form manager for Gravity Forms.

## Task List

1- Create a new custom post type called "Event".

2- The Event post type should have the following Advanced Custom Fields:
Event Name
Event Date
Event City
Event Description
Event Image

3- Define a Gravity Forms that creates new Event on submit.

4- When a new Event is created, send a notification email to customers based on the event's city. The email content should be customizable by Admins and include all dynamic event details such as the event name and date.

5- Upload the plugin to a GitHub repository and provide the link to the repository along with any necessary instructions for installation and usage.

## Minimum Requirements
- PHP v7.2
- WordPress v4.9.5
- [Gravity Forms](https://www.gravityforms.com/)
- [Advanced Custom Fields](https://www.advancedcustomfields.com/)

## Installation

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

## Usage
1. Create a new form using Gravity Forms. the form should have the following fields:
    - Event Name (text)
    - Event Date (date)
    - Event City (select) (should have the .event-cities class)
    - Event Description (textarea)
    - Event Image (file upload)
1. Go to form settings > Mark the Event and setup the fields.
1. Go to form settings > Notifications and add a new notification.
    - Event Notification
    - Send to: {admin_email}
    - Event: After Publish Post
    - Subject: New Event is planned in {Event City}
    - Message: A new event is planned in {Event City} on {Event Date}. The event name is {Event Name}.
1. Sync the ACF JSON files through the ACF settings -> Tools page.
1. Create a new page and add the `[mark_the_event]` shortcode to it.
1. Go to your profile and Select your city. it will be used to send the notification email.
1. Go to the page and fill the form.

### Expected Result
- A new Event post should be created.
- The Event post should have the following ACF fields:
    - Event Name
    - Event Date
    - Event City
    - Event Description
    - Event Image
- A new notification email should be sent to the user based on his city.

## TODO
- [ ] Add some Validation
- [ ] Add some Tests
- [ ] Add Logging and Error Handling

## Spent Time
- 00:30 : Researching and planning
- 13:08 : Creating the plugin