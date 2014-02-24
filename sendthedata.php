<?php
/**
 * Form to email script
 * by Sergey Pozhilov 
 * http://pozhilov.com
 * Details & docs: https://github.com/pozh/Form2email
 * 
 * License: MIT
 * 
 * This program may be used and modified free of charge by anyone, so long as this
 * copyright notice and the header above remain intact.  By using this program you agree
 * to indemnify Sergey from any liability.
 * 
 */


/////////////////////////////////////////////////////////////////////
//                          SETTINGS                               // 
//               EDIT THE 2 LINES BELOW AS REQUIRED                //
/////////////////////////////////////////////////////////////////////

$to_address = 'your@domain.xx';
$subject = 'Data submitted';


/////////////////////////////////////////////////////////////////////
//                   DON'T EDIT THE CODE BELOW                     // 
/////////////////////////////////////////////////////////////////////


/**
 * Helper function which returns error message to the frontend
 * @param string $error The error details
 */
function report_error( $error ) {
	$last_error = error_get_last();
	echo 'Error: ' . $error . ' | ' . $last_error['message'];
}
 
/**
 * Helper function which makes user input safer
 * via subject, from, etc. fields.
 * @param  string $string String to clean up
 * @return string safe string
 */
function clean_string( $string ) {
	$remove_words = array( 'content-type', 'bcc:', 'to:', 'cc:', 'href', 'script' );
	$str = str_replace( $remove_words, '', $string );
	return htmlspecialchars( $str, ENT_QUOTES, 'utf-8' );
}



// POST validation
if( empty( $_POST['name'] )  ||
   !empty( $_POST['addr'] )  ||      // isset, that's right. url is an antispam hook, not a field for user data
	empty( $_POST['msg'] ) ) {
		report_error( 'Empty field(s)' );
		die();
	}
if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) )	{
	report_error( 'Invalid email address' );
	die();
}

 
// POST is OK, continue
$name  = clean_string( $_POST['name'] );
$email = clean_string( $_POST['email'] ); 
$msg   = clean_string( $_POST['msg'] );
$subscribe = ( $_POST['subscribe'] == 'yes' ) ? 'Yes' : 'No';
	 
$error_message = '';
$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

if( !preg_match( $email_exp, $email ) )
	$error_message .= 'The Email address you entered is invalid.<br />';

if( strlen( $msg ) < 4 )
	$error_message .= 'The msg text seems to be invalid.<br />';

if( $error_message != '' ) {
	report_error( $error_message );
	die();
}


// Prepare message body...
$message_body = "Form details below.\n\n";
$message_body .= "Name: "  . $name  . "\n";
$message_body .= "Email: " . $email . "\n";
$message_body .= "Subscribe to news: " . $subscribe . "\n";
$message_body .= "Message: " . $msg . "\n";
	 
// ...and email headers
$headers = 'From: ' . $email . "\r\n" . 'Reply-To: ' . $email. "\r\n" . 'X-Mailer: PHP/' . phpversion();

// Send email
if( mail( $to_address, $subject, $message_body, $headers ) ) {
	echo 'success';
} else 
	report_error( 'Can\'t send the message. ' . ' - ' . $to_address . ' - ' . $subject . ' - ' . $message_body . ' - ' . $headers );
