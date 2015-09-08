# Add To Interest Group
Script to allow one-click group selection for MailChimp for 28-Day Handstand Challenge email marketing campaign.

By using a link in the following format, users can be added to a group to gauge the list's interests.

http://www.28dayhandstandchallenge.com/mail-scripts/new-product-voting/?email=*|EMAIL|*&choice=Muscle+Up&list=*|LIST:UID|*

* 'email' is the user's email as it appears in the list.
* 'choice' is their interest group choice, as it appears in Mailchimp.  Note spaces must be entered as + or %20 in URLs
* 'list' parameter is the list ID number from lists/list MailChimp API call

Note, that this script has 'replace_interests' set to true, which means it will make their selection the ONLY interest they have.

This was fine at the time of implementation, but if using multiple groups in the future, then this script will need to change.