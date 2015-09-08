<?php

/**
	* Script to allow one-click group selection for MailChimp.
	*
	* By using a link in the following format, users can be added to a group to gauge the list's interests.
	*
	* http://www.28dayhandstandchallenge.com/mail-scripts/new-product-voting/?email=*|EMAIL|*&choice=Muscle+Up&list=*|LIST:UID|*
	*
	* 'email' is the user's email as it appears in the list.
	* 'choice' is their interest group choice, as it appears in Mailchimp.  Note spaces must be entered as + or %20 in URLs
	*	'list' parameter is the list ID number from lists/list MailChimp API call
	*
	* Note, that this script has 'replace_interests' set to true, which means it will make their selection the ONLY interest they have.
	* This was fine at the time of implementation, but if using multiple groups int the future, then this script will need to change.
	*/

require_once( 'MailChimp.class.php' );
$MailChimp = new MailChimp('MAILCHIMP_API_KEY_HERE');

$error_message = 'Oops!  Something went wrong!  Did you click on this link from an email?<br/>If so, contact <a href="mailto:chris@chrissalvato.com">chris@chrissalvato.com</a> directly so that he can fix the problem.';

if( !isset($_GET['email']) || !isset($_GET['choice']) || !isset($_GET['list']) || empty($_GET['choice']) || empty( $_GET['list'] )) {
	echo($error_message);
} else {

	$email = $_GET['email'];
	$choice = $_GET['choice'];
	$listID = $_GET['list']; // Should be c0e7cb6e65 for the handstand list.
	
	$grouping_array = $MailChimp->call('lists/interest-groupings', array(
			'id' 					=> $listID,														// The list ID to connect to.
	));

	$groupingID = $grouping_array[0]['id'];


	$merge_vars = array(
			'GROUPINGS' => array(
										 	array('id' => $groupingID, // ID provided by lists/interest-groupings
													 'groups' => array('name' => $choice)
											)
										 )
								);

	$update = $MailChimp->call('lists/update-member', array(
			'id' 					=> $listID,														// The list ID to connect to.
			'email' 			=> array('email' => $email ),					// Array with one of the following keys: email => email address as string; euid => unique ID for the address; leid => list email id for list-member-info type call.
			'merge_vars' 	=> $merge_vars,												// array of new fields to update the member with. (More info: http://apidocs.mailchimp.com/api/2.0/lists/subscribe.php)
			'email_type'	=> "", 																// optional - change the email type preferece for the member ('html' or 'text').  Leave blank to keep existing.
			'replace_interests' => false 												// boolean - OPTIONAL - should the interests be replaced? true replaces, false adds.  Defaults true.

	));

	// Set up success messages for reporting to user.
	$success_messages["Flexibility Training"] = 'Thanks for opting into the free Flexibility Bootcamp!
					  <br/><br/>The blog series will start sometime within the next few weeks, when all of the content is written.  Please be patient!';
  $success_messages["28-Day Handstand Challenge App"] = "Oops!  The handstand challenge app isn't quite ready yet...
  					<br/><br/> Stay tuned for the announcement of it's release within the next few weeks!";
	$success_messages["Android Challenge App"] = "Oops!  The handstand challenge Android app isn't quite ready yet...
  					<br/><br/> Stay tuned for the announcement of it's release within the next few weeks!";  		
	$success_messages["iPhone Challenge App"] = "Oops!  The handstand challenge iPhone app isn't quite ready yet...
  					<br/><br/> Stay tuned for the announcement of it's release within the next few weeks!";  		


	echo('<br/>');
	if( isset($update['status']) && $update['status'] == 'error') {
		echo($error_message);
	} elseif ( isset( $success_messages[ $choice ] ) && !empty( $success_messages[ $choice ]) ) {
		echo( $success_messages[ $choice ] );
	} else {
		echo('Congrats! You have successfully chosen ' . $choice . '.<br/><br/>
    Remember, only your last choice is saved!  <br/><br/>
    If you aren\'t interested in ' . $choice .
    ' as much as the other options then go back to the email and choose again!');
	}
}
?>