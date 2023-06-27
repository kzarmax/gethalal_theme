<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require_once ABSPATH . 'wp-load.php';

// Redirect to HTTPS login if forced to use SSL.
if ( force_ssl_admin() && ! is_ssl() ) {
    if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
        wp_safe_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
        exit;
    } else {
        wp_safe_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        exit;
    }
}


/**
 * Outputs the JavaScript to handle the form shaking on the login page.
 *
 * @since 3.0.0
 */
function wp_shake_js() {
    ?>
    <script type="text/javascript">
        document.querySelector('form').classList.add('shake');
    </script>
    <?php
}

/**
 * Outputs the viewport meta tag for the login page.
 *
 * @since 3.7.0
 */
function wp_login_viewport_meta() {
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <?php
}

//
// Main.
//

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
$errors = new WP_Error();

if ( isset( $_GET['key'] ) ) {
    $action = 'resetpass';
}

if ( isset( $_GET['checkemail'] ) ) {
    $action = 'checkemail';
}

$default_actions = array(
    'confirm_admin_email',
    'postpass',
    'logout',
    'lostpassword',
    'retrievepassword',
    'resetpass',
    'rp',
    'register',
    'checkemail',
    'confirmaction',
    'login',
    'optcheck',
    'checkagain',
    WP_Recovery_Mode_Link_Service::LOGIN_ACTION_ENTERED,
);

// Validate action so as to default to the login screen.
if ( ! in_array( $action, $default_actions, true ) && false === has_filter( 'login_form_' . $action ) ) {
    $action = 'login';
}

nocache_headers();

header( 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) );
wp_login_viewport_meta();

if ( defined( 'RELOCATE' ) && RELOCATE ) { // Move flag is set.
    if ( isset( $_SERVER['PATH_INFO'] ) && ( $_SERVER['PATH_INFO'] !== $_SERVER['PHP_SELF'] ) ) {
        $_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );
    }

    $url = dirname( set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ) );

    if ( get_option( 'siteurl' ) !== $url ) {
        update_option( 'siteurl', $url );
    }
}

// Set a cookie now to see if they are supported by the browser.
$secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure );

if ( SITECOOKIEPATH !== COOKIEPATH ) {
    setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
}

/**
 * Fires when the login form is initialized.
 *
 * @since 3.2.0
 */
do_action( 'login_init' );

/**
 * Fires before a specified login form action.
 *
 * The dynamic portion of the hook name, `$action`, refers to the action
 * that brought the visitor to the login form.
 *
 * Possible hook names include:
 *
 *  - 'login_form_checkemail'
 *  - 'login_form_confirm_admin_email'
 *  - 'login_form_confirmaction'
 *  - 'login_form_entered_recovery_mode'
 *  - 'login_form_login'
 *  - 'login_form_logout'
 *  - 'login_form_lostpassword'
 *  - 'login_form_postpass'
 *  - 'login_form_register'
 *  - 'login_form_resetpass'
 *  - 'login_form_retrievepassword'
 *  - 'login_form_rp'
 *
 * @since 2.8.0
 */
do_action( "login_form_{$action}" );

$http_post     = ( 'POST' === $_SERVER['REQUEST_METHOD'] );
$interim_login = isset( $_REQUEST['interim-login'] );

/**
 * Filters the separator used between login form navigation links.esc_url( network_site_url( 'login?action=resetpass', 'login_post' ) );
 *
 * @since 4.9.0
 *
 * @param string $login_link_separator The separator used between login form navigation links.
 */
$login_link_separator = apply_filters( 'login_link_separator', ' | ' );

//var_dump($action);
switch ( $action ) {

    case 'confirm_admin_email':
        /*
         * Note that `is_user_logged_in()` will return false immediately after logging in
         * as the current user is not set, see wp-includes/pluggable.php.
         * However this action runs on a redirect after logging in.
         */
        if ( ! is_user_logged_in() ) {
            wp_safe_redirect( wp_login_url() );
            exit;
        }

        if ( ! empty( $_REQUEST['redirect_to'] ) ) {
            $redirect_to = $_REQUEST['redirect_to'];
        } else {
            $redirect_to = admin_url();
        }

        if ( current_user_can( 'manage_options' ) ) {
            $admin_email = get_option( 'admin_email' );
        } else {
            wp_safe_redirect( $redirect_to );
            exit;
        }

        /**
         * Filters the interval for dismissing the admin email confirmation screen.
         *
         * If `0` (zero) is returned, the "Remind me later" link will not be displayed.
         *
         * @since 5.3.1
         *
         * @param int $interval Interval time (in seconds). Default is 3 days.
         */
        $remind_interval = (int) apply_filters( 'admin_email_remind_interval', 3 * DAY_IN_SECONDS );

        if ( ! empty( $_GET['remind_me_later'] ) ) {
            if ( ! wp_verify_nonce( $_GET['remind_me_later'], 'remind_me_later_nonce' ) ) {
                wp_safe_redirect( wp_login_url() );
                exit;
            }

            if ( $remind_interval > 0 ) {
                update_option( 'admin_email_lifespan', time() + $remind_interval );
            }

            $redirect_to = add_query_arg( 'admin_email_remind_later', 1, $redirect_to );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        if ( ! empty( $_POST['correct-admin-email'] ) ) {
            if ( ! check_admin_referer( 'confirm_admin_email', 'confirm_admin_email_nonce' ) ) {
                wp_safe_redirect( wp_login_url() );
                exit;
            }

            /**
             * Filters the interval for redirecting the user to the admin email confirmation screen.
             *
             * If `0` (zero) is returned, the user will not be redirected.
             *
             * @since 5.3.0
             *
             * @param int $interval Interval time (in seconds). Default is 6 months.
             */
            $admin_email_check_interval = (int) apply_filters( 'admin_email_check_interval', 6 * MONTH_IN_SECONDS );

            if ( $admin_email_check_interval > 0 ) {
                update_option( 'admin_email_lifespan', time() + $admin_email_check_interval );
            }

            wp_safe_redirect( $redirect_to );
            exit;
        }

        gethalal_login_header( __( 'Confirm your administration email' ), '', $errors );

        /**
         * Fires before the admin email confirm form.
         *
         * @since 5.3.0
         *
         * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
         *                         credentials. Note that the error object may not contain any errors.
         */
        do_action( 'admin_email_confirm', $errors );

        ?>

        <form class="admin-email-confirm-form" name="admin-email-confirm-form" action="<?php echo esc_url( site_url( 'login?action=confirm_admin_email', 'login_post' ) ); ?>" method="post">
            <?php
            /**
             * Fires inside the admin-email-confirm-form form tags, before the hidden fields.
             *
             * @since 5.3.0
             */
            do_action( 'admin_email_confirm_form' );

            wp_nonce_field( 'confirm_admin_email', 'confirm_admin_email_nonce' );

            ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />

            <h1 class="admin-email__heading">
                <?php _e( 'Administration email verification' ); ?>
            </h1>
            <p class="admin-email__details">
                <?php _e( 'Please verify that the <strong>administration email</strong> for this website is still correct.' ); ?>
                <?php

                /* translators: URL to the WordPress help section about admin email. */
                $admin_email_help_url = __( 'https://wordpress.org/support/article/settings-general-screen/#email-address' );

                /* translators: Accessibility text. */
                $accessibility_text = sprintf( '<span class="screen-reader-text"> %s</span>', __( '(opens in a new tab)' ) );

                printf(
                    '<a href="%s" rel="noopener" target="_blank">%s%s</a>',
                    esc_url( $admin_email_help_url ),
                    __( 'Why is this important?' ),
                    $accessibility_text
                );

                ?>
            </p>
            <p class="admin-email__details">
                <?php

                printf(
                /* translators: %s: Admin email address. */
                    __( 'Current administration email: %s' ),
                    '<strong>' . esc_html( $admin_email ) . '</strong>'
                );

                ?>
            </p>
            <p class="admin-email__details">
                <?php _e( 'This email may be different from your personal email address.' ); ?>
            </p>

            <div class="admin-email__actions">
                <div class="admin-email__actions-primary">
                    <?php

                    $change_link = admin_url( 'options-general.php' );
                    $change_link = add_query_arg( 'highlight', 'confirm_admin_email', $change_link );

                    ?>
                    <a class="button button-large" href="<?php echo esc_url( $change_link ); ?>"><?php _e( 'Update' ); ?></a>
                    <input type="submit" name="correct-admin-email" id="correct-admin-email" class="button button-primary button-large" value="<?php esc_attr_e( 'The email is correct' ); ?>" />
                </div>
                <?php if ( $remind_interval > 0 ) : ?>
                    <div class="admin-email__actions-secondary">
                        <?php

                        $remind_me_link = wp_login_url( $redirect_to );
                        $remind_me_link = add_query_arg(
                            array(
                                'action'          => 'confirm_admin_email',
                                'remind_me_later' => wp_create_nonce( 'remind_me_later_nonce' ),
                            ),
                            $remind_me_link
                        );

                        ?>
                        <a href="<?php echo esc_url( $remind_me_link ); ?>"><?php _e( 'Remind me later' ); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <?php

        gethalal_login_footer();
        break;

    case 'postpass':
        if ( ! array_key_exists( 'post_password', $_POST ) ) {
            wp_safe_redirect( wp_get_referer() );
            exit;
        }

        require_once ABSPATH . WPINC . '/class-phpass.php';
        $hasher = new PasswordHash( 8, true );

        /**
         * Filters the life span of the post password cookie.
         *
         * By default, the cookie expires 10 days from creation. To turn this
         * into a session cookie, return 0.
         *
         * @since 3.7.0
         *
         * @param int $expires The expiry time, as passed to setcookie().
         */
        $expire  = apply_filters( 'post_password_expires', time() + 10 * DAY_IN_SECONDS );
        $referer = wp_get_referer();

        if ( $referer ) {
            $secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
        } else {
            $secure = false;
        }

        setcookie( 'wp-postpass_' . COOKIEHASH, $hasher->HashPassword( wp_unslash( $_POST['post_password'] ) ), $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );

        wp_safe_redirect( wp_get_referer() );
        exit;

    case 'logout':
        check_admin_referer( 'log-out' );

        $user = wp_get_current_user();

        wp_logout();

        if ( ! empty( $_REQUEST['redirect_to'] ) ) {
            $redirect_to           = $_REQUEST['redirect_to'];
            $requested_redirect_to = $redirect_to;
        } else {
            $redirect_to = add_query_arg(
                array(
                    'loggedout' => 'true',
                    'wp_lang'   => get_user_locale( $user ),
                ),
                wp_login_url()
            );

            $requested_redirect_to = '';
        }

        /**
         * Filters the log out redirect URL.
         *
         * @since 4.2.0
         *
         * @param string  $redirect_to           The redirect destination URL.
         * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
         * @param WP_User $user                  The WP_User object for the user that's logging out.
         */
        $redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );

        wp_safe_redirect( $redirect_to );
        exit;

    case 'lostpassword':
    case 'retrievepassword':
        if ( $http_post ) {
            $errors = retrieve_password();

            if ( ! is_wp_error( $errors ) ) {
                $redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'login?checkemail=confirm&user_login=' . $_POST['user_login'];
                wp_safe_redirect( $redirect_to );
                exit;
            }
        }

        if ( isset( $_GET['error'] ) ) {
            if ( 'invalidkey' === $_GET['error'] ) {
                $errors->add( 'invalidkey', __( '<strong>Error</strong>: Your password reset link appears to be invalid. Please request a new link below.' ) );
            } elseif ( 'expiredkey' === $_GET['error'] ) {
                $errors->add( 'expiredkey', __( '<strong>Error</strong>: Your password reset link has expired. Please request a new link below.' ) );
            }
        }

        $lostpassword_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
        /**
         * Filters the URL redirected to after submitting the lostpassword/retrievepassword form.
         *
         * @since 3.0.0
         *
         * @param string $lostpassword_redirect The redirect destination URL.
         */
        $redirect_to = apply_filters( 'lostpassword_redirect', $lostpassword_redirect );

        /**
         * Fires before the lost password form.
         *
         * @since 1.5.1
         * @since 5.1.0 Added the `$errors` parameter.
         *
         * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
         *                         credentials. Note that the error object may not contain any errors.
         */
        do_action( 'lost_password', $errors );

        gethalal_login_header( __( 'Lost Password' ), '<p class="message">' . __( 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.' ) . '</p>', $errors );

        $user_login = '';

        if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
            $user_login = wp_unslash( $_POST['user_login'] );
        }

        ?>

        <form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url( network_site_url( 'login?action=lostpassword', 'login_post' ) ); ?>" method="post">

            <div class="form-header">
                <div class="form-title"><?php echo __('Forgot your Password?') ?></div>
                <?php
                $html_link = sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( home_url( '/' ) ),
                    gethalal_get_icon_svg('ui', 'close', 24)
                );
                /**
                 * Filter the "Go to site" link displayed in the login page footer.
                 *
                 * @since 5.7.0
                 *
                 * @param string $link HTML link to the home URL of the current site.
                 */
                echo apply_filters( 'login_site_html_link', $html_link );
                ?>
                <div class="form-sub-title"><?php echo __('We’ll send you a link to reset your password', 'gethalal') ?></div>
            </div>

            <div class="user-id-wrap">
                <input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( $user_login ); ?>" placeholder="<?php echo __('Enter Your Email Address', 'gethalal') ?>" size="20" autocapitalize="off" />
            </div>

            <?php

            /**
             * Fires inside the lostpassword form tags, before the hidden fields.
             *
             * @since 2.1.0
             */
            do_action( 'lostpassword_form' );

            ?>

            <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />

            <div class="form-submit">
                <input name="rememberme" type="hidden" id="rememberme" value="forever" checked />

                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Get New Password' ); ?>" />


            </div>

            <div class="form-bottom-wrap">
                <span class="bottom-text"><?php echo __('Don`t have an account?', 'gethalal') ?> <a href="<?php echo  esc_url( wp_registration_url() ) ?>"><?php echo __('SignUp', 'gethalal') ?></a></span>
            </div>
        </form>
        <?php

        gethalal_login_footer( 'user_login' );
        break;

    case 'resetpass':
    case 'rp':
//        list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );
//        $rp_cookie       = 'wp-resetpass-' . COOKIEHASH;
//
//        if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
//            $value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
//            setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
//
//            wp_safe_redirect( remove_query_arg( array( 'key', 'login' ) ) );
//            exit;
//        }
//
//        if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
//            list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
//
//            $user = check_password_reset_key( $rp_key, $rp_login );
//
//            if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
//                $user = false;
//            }
//        } else {
//            $user = false;
//        }
//
//        if ( ! $user || is_wp_error( $user ) ) {
//            setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
//
//            if ( $user && $user->get_error_code() === 'expired_key' ) {
//                wp_redirect( site_url( 'login?action=lostpassword&error=expiredkey' ) );
//            } else {
//                wp_redirect( site_url( 'login?action=lostpassword&error=invalidkey' ) );
//            }
//
//            exit;
//        }
        if(!$http_post && ( !isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'gethalal_reset_nonce') || !isset($_GET['user_login']))){
            wp_safe_redirect('login?action=lostpassword&error=invalidkey');
            exit();
        }

        $user_login = $http_post?$_POST['user_login']:$_GET['user_login'];
        $user = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
        $errors = new WP_Error();

        if ( isset( $_POST['pass1'] ) && $_POST['pass1'] !== $_POST['pass2'] ) {
            $errors->add( 'password_reset_mismatch', __( '<strong>Error</strong>: The passwords do not match.' ) );
        }

        /**
         * Fires before the password reset procedure is validated.
         *
         * @since 3.5.0
         *
         * @param WP_Error         $errors WP Error object.
         * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
         */
        do_action( 'validate_password_reset', $errors, $user );

        if ( ( ! $errors->has_errors() ) && isset( $_POST['pass1'] ) && ! empty( $_POST['pass1'] ) ) {
            reset_password( $user, $_POST['pass1'] );
            gethalal_login_header( __( 'Password Reset' ), '<p class="message reset-pass">' . __( 'Your password has been reset.' ) . ' <a href="' . esc_url( wp_login_url() ) . '">' . __( 'Log in' ) . '</a></p>' );
            gethalal_login_footer();
            exit;
        }

        wp_enqueue_script( 'utils' );
        wp_enqueue_script( 'user-profile' );

        gethalal_login_header( __( 'Reset Password' ), '<p class="message reset-pass">' . __( 'Enter your new password below or generate one.' ) . '</p>', $errors );

        ?>
        <form name="resetpassform" id="resetpassform" action="<?php echo esc_url( network_site_url( 'login?action=resetpass', 'login_post' ) ); ?>" method="post" autocomplete="off">
            <input type="hidden" id="user_login" value="<?php echo $user_login; ?>" name="user_login" autocomplete="off" />

            <div class="user-pass1-wrap">
                <p>
                    <label for="pass1"><?php _e( 'New password' ); ?></label>
                </p>

                <div class="wp-pwd">
                    <input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" class="input password-input" size="24" value="" autocomplete="off" aria-describedby="pass-strength-result" />

                    <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
                        <span class="dashicons dashicons-hidden" aria-hidden="true"></span>
                    </button>
                    <div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php _e( 'Strength indicator' ); ?></div>
                </div>
                <div class="pw-weak">
                    <input type="checkbox" name="pw_weak" id="pw-weak" class="pw-checkbox" />
                    <label for="pw-weak"><?php _e( 'Confirm use of weak password' ); ?></label>
                </div>
            </div>

            <p class="user-pass2-wrap">
                <label for="pass2"><?php _e( 'Confirm new password' ); ?></label>
                <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
            </p>

            <p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>
            <br class="clear" />

            <?php

            /**
             * Fires following the 'Strength indicator' meter in the user password reset form.
             *
             * @since 3.9.0
             *
             * @param WP_User $user User object of the user whose password is being reset.
             */
            do_action( 'resetpass_form', $user );

            ?>
            <p class="submit reset-pass-submit">
                <button type="button" class="button wp-generate-pw hide-if-no-js" aria-expanded="true"><?php _e( 'Generate Password' ); ?></button>
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Save Password' ); ?>" />
            </p>
        </form>

        <p id="nav">
            <a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in' ); ?></a>
            <?php

            if ( get_option( 'users_can_register' ) ) {
                $registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

                echo esc_html( $login_link_separator );

                /** This filter is documented in wp-includes/general-template.php */
                echo apply_filters( 'register', $registration_url );
            }

            ?>
        </p>
        <?php

        gethalal_login_footer( 'user_pass' );
        break;

    case 'register':
        if ( is_multisite() ) {
            /**
             * Filters the Multisite sign up URL.
             *
             * @since 3.0.0
             *
             * @param string $sign_up_url The sign up URL.
             */
            wp_redirect( apply_filters( 'wp_signup_location', network_site_url( 'wp-signup.php' ) ) );
            exit;
        }

        if ( ! get_option( 'users_can_register' ) ) {
            wp_redirect( site_url( 'login?registration=disabled' ) );
            exit;
        }

        $user_pass = '';
        $user_email = '';

        if ( $http_post ) {
            if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
                $user_email = wp_unslash( $_POST['user_email'] );
            }

            if ( isset( $_POST['pwd'] ) && is_string( $_POST['pwd'] ) ) {
                $user_pass = wp_unslash( $_POST['pwd'] );
            }

            $errors = gethalal_register_new_user( $user_email, $user_pass );

            if ( ! is_wp_error( $errors ) ) {
                $redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : home_url();
                wp_safe_redirect( $redirect_to );
                exit;
            }
        }

        $registration_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

        /**
         * Filters the registration redirect URL.
         *
         * @since 3.0.0
         *
         * @param string $registration_redirect The redirect destination URL.
         */
        $redirect_to = apply_filters( 'registration_redirect', $registration_redirect );

        gethalal_login_header( __( 'Registration Form' ), '<p class="message register">' . __( 'Register For This Site' ) . '</p>', $errors );

        wp_enqueue_script( 'user-profile' );
        ?>
        <form name="registerform" id="registerform" action="<?php echo esc_url( site_url( 'login?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
            <div class="form-header">
                <div class="form-title"><?php echo __('Sign Up') ?></div>
                <?php
                $html_link = sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( home_url( '/' ) ),
                    gethalal_get_icon_svg('ui', 'close', 24)
                );
                /**
                 * Filter the "Go to site" link displayed in the login page footer.
                 *
                 * @since 5.7.0
                 *
                 * @param string $link HTML link to the home URL of the current site.
                 */
                echo apply_filters( 'login_site_html_link', $html_link );
                ?>
                <div class="form-sub-title"><?php echo __('Welcome to Get Halal', 'gethalal') ?></div>
            </div>

<!--            TODO Social login-->
<!--            <div class="oauth-container">-->
<!--                <div class="oauth">-->
<!--                    <img src="--><?php //echo esc_url( get_template_directory_uri() ) ?><!--/assets/images/oauth_facebook.png" alt="oauth-facebook">-->
<!--                    <div class="oauth-text">--><?php //echo __('Continue with Facebook', 'gethalal') ?><!--</div>-->
<!--                </div>-->
<!--                <div class="oauth">-->
<!--                    <img src="--><?php //echo esc_url( get_template_directory_uri() ) ?><!--/assets/images/oauth_google.png" alt="oauth-google">-->
<!--                    <div class="oauth-text">--><?php //echo __('Continue with Google', 'gethalal') ?><!--</div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="form-separator">-->
<!--                <div class="left-bar"></div>-->
<!--                <div class="or-text">--><?php //echo __('OR', 'gethalal') ?><!--</div>-->
<!--                <div class="right-bar"></div>-->
<!--            </div>-->

            <div class="user-id-wrap">
                <input type="email" name="user_email" id="user_email" class="input" required value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" placeholder="<?php echo __('Enter your email address', 'gethalal') ?>" size="25" />
            </div>

            <div class="user-pass-wrap">
                <div class="wp-pwd">
                    <input type="password" name="pwd" id="user_pass" class="input password-input" required value="" size="20" placeholder="<?php echo __('Password', 'gethalal') ?>" />
                    <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password' ); ?>">
                        <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <div class="forgot-pass-wrap">
                <a href="<?php echo esc_url( wp_lostpassword_url() ) ?>">
                    <div><?php echo __('Forgot Password?', 'gethalal') ?></div>
                </a>
            </div>

            <?php

            /**
             * Fires following the 'Email' field in the user registration form.
             *
             * @since 2.1.0
             */
            do_action( 'register_form' );

            ?>

            <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Register' ); ?>" />
            </p>

            <div class="form-bottom-wrap">
                <span class="bottom-text"><?php echo __('Don`t have an account?', 'gethalal') ?> <a href="<?php echo esc_url( wp_login_url() ) ?>"><?php echo __('Login', 'gethalal') ?></a></span>
            </div>
        </form>

        <script type="text/javascript">
            var emailInput = document.getElementById("user_email");
            var passInput = document.getElementById("user_pass");
            emailInput.addEventListener('keydown', function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    passInput.focus();
                }
            })
        </script>

        <?php

        gethalal_login_footer( 'user_login' );
        break;
    case 'optcheck':
        $opt_auth = $_POST['input_1'] . $_POST['input_2'] . $_POST['input_3'] . $_POST['input_4'];
        $errors      = new WP_Error();
        if(strlen($opt_auth) == 4){
            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
            $reset_opcode = get_user_meta($user_data->ID, 'RESET_OPT_CODE', true);
            if($reset_opcode){
                if($reset_opcode['time'] > time()){
                    if($reset_opcode['opt'] == $opt_auth){
                        $reset_nonce =  wp_create_nonce('gethalal_reset_nonce');
                        wp_safe_redirect( 'login?action=resetpass&nonce='. $reset_nonce . '&user_login=' .$_POST['user_login'] );
                        exit();
                    }
                } else {
                    $errors->add('optcheck', __( 'Authentication Code is invalid. Please check email again' ),);
                }
            } else {
                $errors->add('optcheck', __( 'Authentication Code is invalid. Please check email again' ));
            }
        } else {
            $errors->add('optcheck', __( 'Authentication Code is invalid. Please check again' ));
        }
    case 'checkagain':
        {
            $errors = retrieve_password($_GET['user_login']);

            $redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'login?checkemail=confirm&user_login=' . $_GET['user_login'];
            wp_safe_redirect( $redirect_to );

        }
    case 'checkemail':
        $redirect_to = admin_url();

        if(!$_GET['user_login']){
            wp_safe_redirect('login?action=lostpassword');
            exit();
        }
        gethalal_login_header( __( 'Check your email' ), '', $errors );

        ?>
            <form name="authform" id="authform" action="<?php echo  esc_url( network_site_url( 'login?action=optcheck', 'opt_check_post' ) ); ?>" method="post">
                <div class="form-header">
                    <div class="form-title"><?php echo __('Confirm Your Number') ?></div>
                    <div class="form-sub-title"><?php echo __('Enter the 4-digit code sent  to:') ?></div>
                    <div class="form-sub-caption"><?php echo $_GET['user_login'] ?></div>
                    <input type="hidden" value="<?php echo $_GET['user_login'] ?>" id="user_login" name="user_login"/>
                </div>

                <div class="auth-wrap" id="otp">
                    <input type="text" max-length="1" id="input_1" name="input_1" class="auth-input input" value="" placeholder="" required/>
                    <input type="text" max-length="1" id="input_2" name="input_2" class="auth-input input" value="" placeholder="" required/>
                    <input type="text" max-length="1" id="input_3" name="input_3" class="auth-input input" value="" placeholder="" required/>
                    <input type="text" max-length="1" id="input_4" name="input_4" class="auth-input input" value="" placeholder="" required/>
                </div>

                <div class="send-again-wrap">
                    <span><?php echo __('Didn’t get an email?') ?></span>
                    <a href="<?php echo esc_url( network_site_url( 'login?action=checkagain&user_login=' . $_GET['user_login'], 'opt_check_again' ) ); ?>">
                        <span><?php echo __('Send again', 'gethalal') ?></span>
                    </a>
                </div>
                <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Reset Your Password' ); ?>" />
                    <input type="button" name="wp-back-btn" id="wp-back-btn" class="button button-secondary button-large" value="<?php esc_attr_e( 'Go back to log in' ); ?>" />
                    <input type="hidden" name="testcookie" value="1" />
                </p>
            </form>
            <script type="text/javascript">
                function OTPInput() {
                    const inputs = document.querySelectorAll('#otp > *[id]');
                    for (let i = 0; i < inputs.length; i++) {
                        inputs[i].addEventListener('keydown', function (event) {
                            console.log('event key', event.keyCode);
                            if (event.key === "Backspace") {
                                inputs[i].value = '';
                                if (i !== 0)
                                    inputs[i - 1].focus();
                            } else {
                                if (i === inputs.length - 1 && inputs[i].value !== '') {
                                    event.stopPropagation();
                                    event.preventDefault();
                                    return true;
                                } else if ((event.keyCode > 47 && event.keyCode < 58) || (event.keyCode > 95 && event.keyCode < 106)) {
                                    inputs[i].value = event.key;
                                    if (i !== inputs.length - 1)
                                        inputs[i + 1].focus();
                                    event.preventDefault();
                                } else if (event.keyCode > 64 && event.keyCode < 91) {
                                    inputs[i].value = String.fromCharCode(event.keyCode);
                                    if (i !== inputs.length - 1)
                                        inputs[i + 1].focus();
                                    event.preventDefault();
                                }
                            }
                        });
                    }
                }

                OTPInput();
            </script>
        <?php
        gethalal_login_footer();
        break;

    case 'confirmaction':
        if ( ! isset( $_GET['request_id'] ) ) {
            wp_die( __( 'Missing request ID.' ) );
        }

        if ( ! isset( $_GET['confirm_key'] ) ) {
            wp_die( __( 'Missing confirm key.' ) );
        }

        $request_id = (int) $_GET['request_id'];
        $key        = sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) );
        $result     = wp_validate_user_request_key( $request_id, $key );

        if ( is_wp_error( $result ) ) {
            wp_die( $result );
        }

        /**
         * Fires an action hook when the account action has been confirmed by the user.
         *
         * Using this you can assume the user has agreed to perform the action by
         * clicking on the link in the confirmation email.
         *
         * After firing this action hook the page will redirect to wp-login a callback
         * redirects or exits first.
         *
         * @since 4.9.6
         *
         * @param int $request_id Request ID.
         */
        do_action( 'user_request_action_confirmed', $request_id );

        $message = _wp_privacy_account_request_confirmed_message( $request_id );

        gethalal_login_header( __( 'User action confirmed.' ), $message );
        gethalal_login_footer();
        exit;

    case 'login':
    default:
        $secure_cookie   = '';
        $customize_login = isset( $_REQUEST['customize-login'] );

        if ( $customize_login ) {
            wp_enqueue_script( 'customize-base' );
        }

        // If the user wants SSL but the session is not SSL, force a secure cookie.
        if ( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {
            $user_name = sanitize_user( wp_unslash( $_POST['log'] ) );
            $user      = get_user_by( 'login', $user_name );

            if ( ! $user && strpos( $user_name, '@' ) ) {
                $user = get_user_by( 'email', $user_name );
            }

            if ( $user ) {
                if ( get_user_option( 'use_ssl', $user->ID ) ) {
                    $secure_cookie = true;
                    force_ssl_admin( true );
                }
            }
        }

        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $redirect_to = $_REQUEST['redirect_to'];
            // Redirect to HTTPS if user wants SSL.
            if ( $secure_cookie && false !== strpos( $redirect_to, 'wp-admin' ) ) {
                $redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
            }
        } else {
            $redirect_to = admin_url();
        }

        $reauth = empty( $_REQUEST['reauth'] ) ? false : true;

        $user = wp_signon( array(), $secure_cookie );

        if ( empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {
            if ( headers_sent() ) {
                $user = new WP_Error(
                    'test_cookie',
                    sprintf(
                    /* translators: 1: Browser cookie documentation URL, 2: Support forums URL. */
                        __( '<strong>Error</strong>: Cookies are blocked due to unexpected output. For help, please see <a href="%1$s">this documentation</a> or try the <a href="%2$s">support forums</a>.' ),
                        __( 'https://wordpress.org/support/article/cookies/' ),
                        __( 'https://wordpress.org/support/forums/' )
                    )
                );
            } elseif ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[ TEST_COOKIE ] ) ) {
                // If cookies are disabled, we can't log in even with a valid user and password.
                $user = new WP_Error(
                    'test_cookie',
                    sprintf(
                    /* translators: %s: Browser cookie documentation URL. */
                        __( '<strong>Error</strong>: Cookies are blocked or not supported by your browser. You must <a href="%s">enable cookies</a> to use WordPress.' ),
                        __( 'https://wordpress.org/support/article/cookies/#enable-cookies-in-your-browser' )
                    )
                );
            }
        }

        $requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
        /**
         * Filters the login redirect URL.
         *
         * @since 3.0.0
         *
         * @param string           $redirect_to           The redirect destination URL.
         * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
         * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
         */
        $redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user );

        if ( ! is_wp_error( $user ) && ! $reauth ) {
            if ( $interim_login ) {
                $message       = '<p class="message">' . __( 'You have logged in successfully.' ) . '</p>';
                $interim_login = 'success';
                gethalal_login_header( '', $message );

                ?>
                </div>
                <?php

                /** This action is documented in login */
                do_action( 'login_footer' );

                if ( $customize_login ) {
                    ?>
                    <script type="text/javascript">setTimeout( function(){ new wp.customize.Messenger({ url: '<?php echo wp_customize_url(); ?>', channel: 'login' }).send('login') }, 1000 );</script>
                    <?php
                }

                ?>
                </body></html>
                <?php

                exit;
            }

            // Check if it is time to add a redirect to the admin email confirmation screen.
            if ( is_a( $user, 'WP_User' ) && $user->exists() && $user->has_cap( 'manage_options' ) ) {
                $admin_email_lifespan = (int) get_option( 'admin_email_lifespan' );

                // If `0` (or anything "falsey" as it is cast to int) is returned, the user will not be redirected
                // to the admin email confirmation screen.
                /** This filter is documented in login */
                $admin_email_check_interval = (int) apply_filters( 'admin_email_check_interval', 6 * MONTH_IN_SECONDS );

                if ( $admin_email_check_interval > 0 && time() > $admin_email_lifespan ) {
                    $redirect_to = add_query_arg(
                        array(
                            'action'  => 'confirm_admin_email',
                            'wp_lang' => get_user_locale( $user ),
                        ),
                        wp_login_url( $redirect_to )
                    );
                }
            }

            if ( ( empty( $redirect_to ) || 'wp-admin/' === $redirect_to || admin_url() === $redirect_to ) ) {
                // If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
                if ( is_multisite() && ! get_active_blog_for_user( $user->ID ) && ! is_super_admin( $user->ID ) ) {
                    $redirect_to = user_admin_url();
                } elseif ( is_multisite() && ! $user->has_cap( 'read' ) ) {
                    $redirect_to = get_dashboard_url( $user->ID );
                } elseif ( ! $user->has_cap( 'edit_posts' ) ) {
                    $redirect_to = $user->has_cap( 'read' ) ? admin_url( 'profile.php' ) : home_url();
                }

                wp_redirect( $redirect_to );
                exit;
            }

            wp_safe_redirect( $redirect_to );
            exit;
        }

        $errors = $user;
        // Clear errors if loggedout is set.
        if ( ! empty( $_GET['loggedout'] ) || $reauth ) {
            $errors = new WP_Error();
        }

        if ( empty( $_POST ) && $errors->get_error_codes() === array( 'empty_username', 'empty_password' ) ) {
            $errors = new WP_Error( '', '' );
        }

        if ( $interim_login ) {
            if ( ! $errors->has_errors() ) {
                $errors->add( 'expired', __( 'Your session has expired. Please log in to continue where you left off.' ), 'message' );
            }
        } else {
            // Some parts of this script use the main login form to display a message.
            if ( isset( $_GET['loggedout'] ) && $_GET['loggedout'] ) {
                $errors->add( 'loggedout', __( 'You are now logged out.' ), 'message' );
            } elseif ( isset( $_GET['registration'] ) && 'disabled' === $_GET['registration'] ) {
                $errors->add( 'registerdisabled', __( '<strong>Error</strong>: User registration is currently not allowed.' ) );
            } elseif ( strpos( $redirect_to, 'about.php?updated' ) ) {
                $errors->add( 'updated', __( '<strong>You have successfully updated WordPress!</strong> Please log back in to see what&#8217;s new.' ), 'message' );
            } elseif ( WP_Recovery_Mode_Link_Service::LOGIN_ACTION_ENTERED === $action ) {
                $errors->add( 'enter_recovery_mode', __( 'Recovery Mode Initialized. Please log in to continue.' ), 'message' );
            } elseif ( isset( $_GET['redirect_to'] ) && false !== strpos( $_GET['redirect_to'], 'wp-admin/authorize-application.php' ) ) {
                $query_component = wp_parse_url( $_GET['redirect_to'], PHP_URL_QUERY );
                parse_str( $query_component, $query );

                if ( ! empty( $query['app_name'] ) ) {
                    /* translators: 1: Website name, 2: Application name. */
                    $message = sprintf( 'Please log in to %1$s to authorize %2$s to connect to your account.', get_bloginfo( 'name', 'display' ), '<strong>' . esc_html( $query['app_name'] ) . '</strong>' );
                } else {
                    /* translators: %s: Website name. */
                    $message = sprintf( 'Please log in to %s to proceed with authorization.', get_bloginfo( 'name', 'display' ) );
                }

                $errors->add( 'authorize_application', $message, 'message' );
            }
        }

        /**
         * Filters the login page errors.
         *
         * @since 3.6.0
         *
         * @param WP_Error $errors      WP Error object.
         * @param string   $redirect_to Redirect destination URL.
         */
        $errors = apply_filters( 'wp_login_errors', $errors, $redirect_to );

        // Clear any stale cookies.
        if ( $reauth ) {
            wp_clear_auth_cookie();
        }

        gethalal_login_header( __( 'Log In' ), '', $errors );

        if ( isset( $_POST['log'] ) ) {
            $user_login = ( 'incorrect_password' === $errors->get_error_code() || 'empty_password' === $errors->get_error_code() ) ? esc_attr( wp_unslash( $_POST['log'] ) ) : '';
        }

        $rememberme = ! empty( $_POST['rememberme'] );

        if ( $errors->has_errors() ) {
            $aria_describedby_error = ' aria-describedby="login_error"';
        } else {
            $aria_describedby_error = '';
        }

        wp_enqueue_script( 'user-profile' );
        ?>

        <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'login', 'login_post' ) ); ?>" method="post">
            <div class="form-header">
                <div class="form-title"><?php echo __('Login') ?></div>
                <?php
                $html_link = sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( home_url( '/' ) ),
                    gethalal_get_icon_svg('ui', 'close', 24)
                );
                /**
                 * Filter the "Go to site" link displayed in the login page footer.
                 *
                 * @since 5.7.0
                 *
                 * @param string $link HTML link to the home URL of the current site.
                 */
                echo apply_filters( 'login_site_html_link', $html_link );
                ?>
            </div>

<!--            TODO Social login-->
<!--            <div class="oauth-container">-->
<!--                <div class="oauth">-->
<!--                    <img src="--><?php //echo esc_url( get_template_directory_uri() ) ?><!--/assets/images/oauth_facebook.png" alt="oauth-facebook">-->
<!--                    <div class="oauth-text">--><?php //echo __('Continue with Facebook', 'gethalal') ?><!--</div>-->
<!--                </div>-->
<!--                <div class="oauth">-->
<!--                    <img src="--><?php //echo esc_url( get_template_directory_uri() ) ?><!--/assets/images/oauth_google.png" alt="oauth-google">-->
<!--                    <div class="oauth-text">--><?php //echo __('Continue with Google', 'gethalal') ?><!--</div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="form-separator">-->
<!--                <div class="left-bar"></div>-->
<!--                <div class="or-text">--><?php //echo __('OR', 'gethalal') ?><!--</div>-->
<!--                <div class="right-bar"></div>-->
<!--            </div>-->

            <div class="user-id-wrap">
                <input type="text" name="log" id="user_login"<?php echo $aria_describedby_error; ?> class="input" value="<?php echo esc_attr( $user_login ); ?>" placeholder="<?php echo __('Enter your email address', 'gethalal') ?>" size="20" autocapitalize="off" />
            </div>

            <div class="user-pass-wrap">
                <div class="wp-pwd">
                    <input type="password" name="pwd" id="user_pass"<?php echo $aria_describedby_error; ?> class="input password-input" value="" size="20" placeholder="<?php echo __('Password', 'gethalal') ?>" />
                    <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password' ); ?>">
                        <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <div class="forgot-pass-wrap">
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
                    <div><?php echo __('Forgot Password?', 'gethalal') ?></div>
                </a>
            </div>
            <?php

            /**
             * Fires following the 'Password' field in the login form.
             *
             * @since 2.1.0
             */
            do_action( 'login_form' );

            ?>
            <p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <label for="rememberme"><?php esc_html_e( 'Remember Me' ); ?></label></p>
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In' ); ?>" />
                <?php

                if ( $interim_login ) {
                    ?>
                    <input type="hidden" name="interim-login" value="1" />
                    <?php
                } else {
                    ?>
                    <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
                    <?php
                }

                if ( $customize_login ) {
                    ?>
                    <input type="hidden" name="customize-login" value="1" />
                    <?php
                }

                ?>
                <input type="hidden" name="testcookie" value="1" />
            </p>
            <div class="form-bottom-wrap">
                <span class="bottom-text"><?php echo __('Don`t have an account?', 'gethalal') ?> <a href="<?php echo esc_url( wp_registration_url() ) ?>"><?php echo __('SignUp', 'gethalal') ?></a></span>
            </div>
        </form>

        <?php

        $login_script  = 'function wp_attempt_focus() {';
        $login_script .= 'setTimeout( function() {';
        $login_script .= 'try {';

        if ( $user_login ) {
            $login_script .= 'd = document.getElementById( "user_pass" ); d.value = "";';
        } else {
            $login_script .= 'd = document.getElementById( "user_login" );';

            if ( $errors->get_error_code() === 'invalid_username' ) {
                $login_script .= 'd.value = "";';
            }
        }

        $login_script .= 'd.focus(); d.select();';
        $login_script .= '} catch( er ) {}';
        $login_script .= '}, 200);';
        $login_script .= "}\n"; // End of wp_attempt_focus().

        /**
         * Filters whether to print the call to `wp_attempt_focus()` on the login screen.
         *
         * @since 4.8.0
         *
         * @param bool $print Whether to print the function call. Default true.
         */
        if ( apply_filters( 'enable_login_autofocus', true ) && ! $error ) {
            $login_script .= "wp_attempt_focus();\n";
        }

        // Run `wpOnload()` if defined.
        $login_script .= "if ( typeof wpOnload === 'function' ) { wpOnload() }";

        ?>
        <script type="text/javascript">
            <?php echo $login_script; ?>
        </script>
        <?php

        if ( $interim_login ) {
            ?>
            <script type="text/javascript">
                ( function() {
                    try {
                        var i, links = document.getElementsByTagName( 'a' );
                        for ( i in links ) {
                            if ( links[i].href ) {
                                links[i].target = '_blank';
                                links[i].rel = 'noopener';
                            }
                        }
                    } catch( er ) {}
                }());
            </script>
            <?php
        }

        gethalal_login_footer();
        break;
} // End action switch.
