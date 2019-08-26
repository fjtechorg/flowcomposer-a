<?php

/*
/ Here are all the constants...grouped them a bit by function / page
/ "should be escaped if it is a message send with FB as the json is in "
*/

define('MSG_STOP_CONFIRM', 'Do you really want to unsubscribe?'); //default text on unsubscribe request, asking to confirm
define('MSG_STOP_CONFIRM_BUTTON', 'Unsubscribe'); //default button text on confirm to unsubscribe

define('MSG_STOP_REPLY', 'You have successfully unsubscribed.\n\nType \"start\" to subscribe again'); //default reply on unsubscribe
define('MSG_STOP_REPLY_BUTTON', 'Start Subscription'); //default button text on unsubscribe

define('MSG_START_REPLY', 'You have successfully subscribed! The next post is coming soon, stay tuned! \n\nP.S. If you want to unsubscribe again just type \"stop\".'); //default reply on subscribe

define('BROADCAST_QR_WARNING','<strong>ATTENTION:</strong> A Quick Reply card can only be added once. It should be the only card in a broadcast, or the last one in a sequence. Other cards cannot be added right now as a Quick Reply has been added to the sequence.'); //default message when someone adds a quick reply. a quick reply is always last in a sequence

define('LIVE_CHAT_INACTIVE_USER_WARNING','This subscriber has been inactive for more than 24 hours. You can only send a message to respond to a customer service issue surfaced in in the conversation');
define('LIVE_CHAT_INACTIVE_USER_WARNING_INFO','You may only respond to a customer service issue surfaced in a Messenger conversation after a transaction has taken place. Messenger restricts this for use cases where the business requires more than 24 hours to resolve an issue and needs to give a person a status update and/or gather additional information. This cannot be used for promotional content (i.e. daily deals, coupons, discounts, or sale announcements. Nor can you use this to proactively message people to solicit feedback)');

define('FLOWCHART_LIST_WARNING','Minimum of 2 items and a maximum of 4 items per message'); //
define('FLOWCHART_LIST_ADD_ITEM','+Add Item');

define('FLOWCOMPOSER_MESSENGER_LINK','Your Messenger link is not available because your Page doesn\'t have <STRONG>@username</STRONG> setup yet. You can change your <STRONG>@username</STRONG> in the About section over at your Facebook page. In some cases you need to wait as Facebook determines when you get access to use an <STRONG>@username</STRONG>. Please see <A HREF="https://www.facebook.com/help/121237621291199" TARGET="_blank"> this article</A> on Facebook to find out more about the @username.');
