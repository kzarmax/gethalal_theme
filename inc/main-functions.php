<?php

if(!function_exists('gethalal_generate_user_id')) {
    function gethalal_generate_user_id($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $id = '';
        for ($i = 0; $i < $length; $i++) {
            $id .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
        }

        return $id;
    }
}

if(!function_exists('gethalal_register_new_user')){
    function gethalal_register_new_user($user_email, $user_pass){
        $errors = new WP_Error();

        $user_login = gethalal_generate_user_id();

        $sanitized_user_login = sanitize_user( $user_login );
        /**
         * Filters the email address of a user being registered.
         *
         * @since 2.1.0
         *
         * @param string $user_email The email address of the new user.
         */
        $user_email = apply_filters( 'user_registration_email', $user_email );

        // Check the username.
        if ( '' === $sanitized_user_login ) {
            $errors->add( 'empty_username', __( '<strong>Error</strong>: Please enter a username.' ) );
        } elseif ( ! validate_username( $user_login ) ) {
            $errors->add( 'invalid_username', __( '<strong>Error</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
            $sanitized_user_login = '';
        } elseif ( username_exists( $sanitized_user_login ) ) {
            $errors->add( 'username_exists', __( '<strong>Error</strong>: This username is already registered. Please choose another one.' ) );

        } else {
            /** This filter is documented in wp-includes/user.php */
            $illegal_user_logins = (array) apply_filters( 'illegal_user_logins', array() );
            if ( in_array( strtolower( $sanitized_user_login ), array_map( 'strtolower', $illegal_user_logins ), true ) ) {
                $errors->add( 'invalid_username', __( '<strong>Error</strong>: Sorry, that username is not allowed.' ) );
            }
        }

        // Check the email address.
        if ( '' === $user_email ) {
            $errors->add( 'empty_email', __( '<strong>Error</strong>: Please type your email address.' ) );
        } elseif ( ! is_email( $user_email ) ) {
            $errors->add( 'invalid_email', __( '<strong>Error</strong>: The email address isn&#8217;t correct.' ) );
            $user_email = '';
        } elseif ( email_exists( $user_email ) ) {
            $errors->add( 'email_exists', __( '<strong>Error</strong>: This email is already registered. Please choose another one.' ) );
        }

        /**
         * Fires when submitting registration form data, before the user is created.
         *
         * @since 2.1.0
         *
         * @param string   $sanitized_user_login The submitted username after being sanitized.
         * @param string   $user_email           The submitted email.
         * @param WP_Error $errors               Contains any errors with submitted username and email,
         *                                       e.g., an empty field, an invalid username or email,
         *                                       or an existing username or email.
         */
        do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

        /**
         * Filters the errors encountered when a new user is being registered.
         *
         * The filtered WP_Error object may, for example, contain errors for an invalid
         * or existing username or email address. A WP_Error object should always be returned,
         * but may or may not contain errors.
         *
         * If any errors are present in $errors, this will abort the user's registration.
         *
         * @since 2.1.0
         *
         * @param WP_Error $errors               A WP_Error object containing any errors encountered
         *                                       during registration.
         * @param string   $sanitized_user_login User's username after it has been sanitized.
         * @param string   $user_email           User's email.
         */
        $errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

        if ( $errors->has_errors() ) {
            return $errors;
        }

        $user_id   = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
        if ( ! $user_id || is_wp_error( $user_id ) ) {
            $errors->add(
                'registerfail',
                sprintf(
                /* translators: %s: Admin email address. */
                    __( '<strong>Error</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">site admin</a>!' ),
                    get_option( 'admin_email' )
                )
            );
            return $errors;
        }

        update_user_meta( $user_id, 'default_password_nag', true ); // Set up the password change nag.

        /**
         * Fires after a new user registration has been recorded.
         *
         * @since 4.4.0
         *
         * @param int $user_id ID of the newly registered user.
         */
        do_action( 'register_new_user', $user_id );

        return $user_id;
    }
}
