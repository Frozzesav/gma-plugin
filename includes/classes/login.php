<?php 



/** Login Redirect



 * Redirect user after successful login.



 *



 * @param string $redirect_to URL to redirect to.



 * @param string $request URL the user is coming from.



 * @param object $user Logged user's data.



 * @return string



 */



function my_login_redirect( $redirect_to, $request, $user ) {



    //is there a user to check?



    global $user;



    if ( isset( $user->roles ) && is_array( $user->roles ) ) {



        //check for admins



        if ( in_array( 'administrator', $user->roles ) ) {



            // redirect them to the default place



            return home_url('/sdfg/');



        } elseif ( in_array( 'jury', $user->roles ) ) {



            return home_url('/lk-jury/');



        } elseif ( in_array( 'competitor', $user->roles ) ) {



            return home_url('/sdfg/');



        }



    } else {



        return $redirect_to;



    }



}







/*Login Error Handle*/



add_action( 'wp_login_failed', 'aa_login_failed' ); // hook failed login







function aa_login_failed( $user ) {



    // check what page the login attempt is coming from



    $referrer = $_SERVER['HTTP_REFERER'];

    // check that were not on the default login page

    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {



        // make sure we don't already have a failed login attempt



        if ( !strstr($referrer, '?login=failed' )) {



            // Redirect to the login page and append a querystring of login failed



            wp_redirect( $referrer . '?login=failed');



        } else {



            wp_redirect( $referrer );



        }







        exit;



    }



}







/*Login Empty Fields Error handling*/



add_action( 'authenticate', 'pu_blank_login');







function pu_blank_login( $user ){



    // check what page the login attempt is coming from



    $referrer = $_SERVER['HTTP_REFERER'];







    $error = false;







    if($_POST['log'] == '' || $_POST['pwd'] == '')



    {



        $error = true;



    }







    // check that were not on the default login page



    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {







        // make sure we don't already have a failed login attempt



        if ( !strstr($referrer, '?login=failed') ) {



            // Redirect to the login page and append a querystring of login failed



            wp_redirect( $referrer . '?login=failed' );



        } else {



            wp_redirect( $referrer );



        }







    exit;







    }



}







/*Logout Redirect*/



add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );







function go_home(){



  wp_redirect( home_url('/login/') );



  exit();



}



add_action('wp_logout','go_home');