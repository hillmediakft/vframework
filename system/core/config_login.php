<?php
/*
 * Configuration for: Additional login providers: Facebook
 * Self-explaining. The FACEBOOK_LOGIN_PATH is the controller-action where the user is redirected to after getting
 * authenticated via Facebook. Leave it like that unless you know exactly what you do.
 */
define('FACEBOOK_LOGIN', false);
define('FACEBOOK_LOGIN_APP_ID', 'XXX');
define('FACEBOOK_LOGIN_APP_SECRET', 'XXX');
define('FACEBOOK_LOGIN_PATH', 'login/loginWithFacebook');
define('FACEBOOK_REGISTER_PATH', 'login/registerWithFacebook');

/**
 * Configuration for: Avatars/Gravatar support
 * Set to true if you want to use "Gravatar(s)", a service that automatically gets avatar pictures via using email
 * addresses of users by requesting images from the gravatar.com API. Set to false to use own locally saved avatars.
 * AVATAR_SIZE set the pixel size of avatars/gravatars (will be 44x44 by default). Avatars are always squares.
 * AVATAR_DEFAULT_IMAGE is the default image in public/avatars/
 */
define('USE_GRAVATAR', false);
define('AVATAR_SIZE', 44);
define('AVATAR_JPEG_QUALITY', 85);
define('AVATAR_DEFAULT_IMAGE', 'default.jpg');

//define('AVATAR_PATH', 'public/avatars/');

/**
 * Configuration for: Cookies
 * Please note: The COOKIE_DOMAIN needs the domain where your app is,
 * in a format like this: .mydomain.com
 * Note the . in front of the domain. No www, no http, no slash here!
 * For local development .127.0.0.1 is fine, but when deploying you should
 * change this to your real domain, like '.mydomain.com' ! The leading dot makes the cookie available for
 * sub-domains too.
 * @see http://stackoverflow.com/q/9618217/1114320
 * @see php.net/manual/en/function.setcookie.php
 */
// 1209600 seconds = 2 weeks
define('COOKIE_RUNTIME', 1209600);
// the domain where the cookie is valid for, for local development ".127.0.0.1" and ".localhost" will work
// IMPORTANT: always put a dot in front of the domain, like ".mydomain.com" !
define('COOKIE_DOMAIN', '.localhost');


/**
 * Configuration for: Hashing strength
 * This is the place where you define the strength of your password hashing/salting
 *
 * To make password encryption very safe and future-proof, the PHP 5.5 hashing/salting functions
 * come with a clever so called COST FACTOR. This number defines the base-2 logarithm of the rounds of hashing,
 * something like 2^12 if your cost factor is 12. By the way, 2^12 would be 4096 rounds of hashing, doubling the
 * round with each increase of the cost factor and therefore doubling the CPU power it needs.
 * Currently, in 2013, the developers of this functions have chosen a cost factor of 10, which fits most standard
 * server setups. When time goes by and server power becomes much more powerful, it might be useful to increase
 * the cost factor, to make the password hashing one step more secure. Have a look here
 * (@see https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)
 * in the BLOWFISH benchmark table to get an idea how this factor behaves. For most people this is irrelevant,
 * but after some years this might be very very useful to keep the encryption of your database up to date.
 *
 * Remember: Every time a user registers or tries to log in (!) this calculation will be done.
 * Don't change this if you don't know what you do.
 *
 * To get more information about the best cost factor please have a look here
 * @see http://stackoverflow.com/q/4443476/1114320
 */

// the hash cost factor, PHP's internal default is 10. You can leave this line
// commented out until you need another factor then 10.
define("HASH_COST_FACTOR", "10");


/**
 * Configuration for: Error messages and notices
 *
 * In this project, the error messages, notices etc are all-together called "feedback".
 */
define("FEEDBACK_UNKNOWN_ERROR", "Unknown error occurred!");
define("FEEDBACK_PASSWORD_WRONG_3_TIMES", "You have typed in a wrong password 3 or more times already. Please wait 30 seconds to try again.");
define("FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET", "Your account is not activated yet. Please click on the confirm link in the mail.");
define("FEEDBACK_PASSWORD_WRONG", "Password was wrong.");
define("FEEDBACK_USER_DOES_NOT_EXIST", "This user does not exist.");
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
define("FEEDBACK_LOGIN_FAILED", "Login failed.");

define("FEEDBACK_USERNAME_FIELD_EMPTY", "Username field was empty.");
define("FEEDBACK_USERFIRSTNAME_FIELD_EMPTY", "User firstname field was empty.");
define("FEEDBACK_USERLASTNAME_FIELD_EMPTY", "User lastname field was empty.");
define("FEEDBACK_USERPHONE_FIELD_EMPTY", "User phone field was empty.");
define("FEEDBACK_PASSWORD_FIELD_EMPTY", "Password field was empty.");
define("FEEDBACK_EMAIL_FIELD_EMPTY", "Email and passwords fields were empty.");

define("FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY", "Email field was empty.");
define("FEEDBACK_USERNAME_SAME_AS_OLD_ONE", "Sorry, that username is the same as your current one. Please choose another one.");
define("FEEDBACK_USERNAME_ALREADY_TAKEN", "Sorry, that username is already taken. Please choose another one.");
define("FEEDBACK_USER_EMAIL_ALREADY_TAKEN", "Sorry, that email is already in use. Please choose another one.");
define("FEEDBACK_USERNAME_CHANGE_SUCCESSFUL", "Your username has been changed successfully.");
define("FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY", "Username and password fields were empty.");

define("FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN", "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.");
define("FEEDBACK_USERFIRSTNAME_DOES_NOT_FIT_PATTERN", "User firstname does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.");
define("FEEDBACK_USERLASTNAME_DOES_NOT_FIT_PATTERN", "User lastname does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.");
define("FEEDBACK_USERPHONE_DOES_NOT_FIT_PATTERN", "User phone number does not fit the name scheme: only 11-22-333-444 or 11 22 333 444 or 1122333444 .");

define("FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN", "Sorry, your chosen email does not fit into the email naming pattern.");
define("FEEDBACK_EMAIL_SAME_AS_OLD_ONE", "Sorry, that email address is the same as your current one. Please choose another one.");
define("FEEDBACK_EMAIL_CHANGE_SUCCESSFUL", "Your email address has been changed successfully.");
define("FEEDBACK_CAPTCHA_WRONG", "The entered captcha security characters were wrong.");
define("FEEDBACK_PASSWORD_REPEAT_WRONG", "Password and password repeat are not the same.");
define("FEEDBACK_PASSWORD_TOO_SHORT", "Password has a minimum length of 6 characters.");

define("FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG", "Username cannot be shorter than 2 or longer than 64 characters.");
define("FEEDBACK_USERFIRSTNAME_TOO_SHORT_OR_TOO_LONG", "User firstname cannot be shorter than 2 or longer than 64 characters.");
define("FEEDBACK_USERLASTNAME_TOO_SHORT_OR_TOO_LONG", "User lastname cannot be shorter than 2 or longer than 64 characters.");

define("FEEDBACK_EMAIL_TOO_LONG", "Email cannot be longer than 64 characters.");
define("FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED", "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.");

define("FEEDBACK_USER_SUCCESSFULLY_CREATED", "User created successfully.");

define("FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED", "Sorry, we could not send you an verification mail. Your account has NOT been created.");
define("FEEDBACK_ACCOUNT_CREATION_FAILED", "Sorry, your registration failed. Please go back and try again.");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR", "Verification mail could not be sent due to: ");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL", "A verification mail has been sent successfully.");
define("FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL", "Activation was successful! You can now log in.");
define("FEEDBACK_ACCOUNT_ACTIVATION_FAILED", "Sorry, no such id/verification code combination here...");
define("FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL", "Avatar upload was successful.");
define("FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE", "Only JPEG and PNG files are supported.");
define("FEEDBACK_AVATAR_UPLOAD_TOO_SMALL", "Avatar source file's width/height is too small. Needs to be 100x100 pixel minimum.");
define("FEEDBACK_AVATAR_UPLOAD_TOO_BIG", "Avatar source file is too big. 5 Megabyte is the maximum.");
define("FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE", "Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.");
define("FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED", "Something went wrong with the image upload.");
define("FEEDBACK_PASSWORD_RESET_TOKEN_FAIL", "Could not write token to database.");
define("FEEDBACK_PASSWORD_RESET_TOKEN_MISSING", "No password reset token.");
define("FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR", "Password reset mail could not be sent due to: ");
define("FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL", "A password reset mail has been sent successfully.");
define("FEEDBACK_PASSWORD_RESET_LINK_EXPIRED", "Your reset link has expired. Please use the reset link within one hour.");
define("FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST", "Username/Verification code combination does not exist.");
define("FEEDBACK_PASSWORD_RESET_LINK_VALID", "Password reset validation link is valid. Please change the password now.");
define("FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL", "Password successfully changed.");
define("FEEDBACK_PASSWORD_CHANGE_FAILED", "Sorry, your password changing failed.");
define("FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL", "Account upgrade was successful.");
define("FEEDBACK_ACCOUNT_UPGRADE_FAILED", "Account upgrade failed.");
define("FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL", "Account downgrade was successful.");
define("FEEDBACK_ACCOUNT_DOWNGRADE_FAILED", "Account downgrade failed.");
define("FEEDBACK_NOTE_CREATION_FAILED", "Note creation failed.");
define("FEEDBACK_NOTE_EDITING_FAILED", "Note editing failed.");
define("FEEDBACK_NOTE_DELETION_FAILED", "Note deletion failed.");
define("FEEDBACK_COOKIE_INVALID", "Your remember-me-cookie is invalid.");
define("FEEDBACK_COOKIE_LOGIN_SUCCESSFUL", "You were successfully logged in via the remember-me-cookie.");
define("FEEDBACK_FACEBOOK_LOGIN_NOT_REGISTERED", "Sorry, you don't have an account here. Please register first.");
define("FEEDBACK_FACEBOOK_EMAIL_NEEDED", "Sorry, but you need to allow us to see your email address to register.");
define("FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS", "Sorry, but you have already registered here (your Facebook ID exists in our database).");
define("FEEDBACK_FACEBOOK_EMAIL_ALREADY_EXISTS", "Sorry, but you have already registered here (your Facebook email exists in our database).");
define("FEEDBACK_FACEBOOK_USERNAME_ALREADY_EXISTS", "Sorry, but you have already registered here (your Facebook username exists in our database).");
define("FEEDBACK_FACEBOOK_REGISTER_SUCCESSFUL", "You have been successfully registered with Facebook.");
define("FEEDBACK_FACEBOOK_OFFLINE", "We could not reach the Facebook servers. Maybe Facebook is offline (that really happens sometimes).");



define("FEEDBACK_USER_DATA_UPDATE_SUCCESS", "A felhasználó adatai módosultak.");
define("FEEDBACK_USER_DATA_UPDATE_FAIL", "Hiba történt a felhasználó adatainak módosításakor.");
define("FEEDBACK_USER_DELETE_FAIL", "Nem törlődött a felhasználó.");
define("FEEDBACK_USER_DELETE_SUCCESSFUL", "Sikeres törlés.");
define("FEEDBACK_PAGE_UPDATE_SUCCESS", "Az oldal módosítások sikeresen elmentve!");
define("FEEDBACK_SETTINGS_UPDATE_SUCCESS", "A beállítások módosításai sikeresen elmentve!");
define("FEEDBACK_CONTENT_UPDATE_SUCCESS", "A tartalom módosításai sikeresen elmentve!");
define("FEEDBACK_TESTIMONIAL_UPDATE_SUCCESS", "A rólunk mondták módosításai sikeresen elmentve!");
define("FEEDBACK_NEW_TESTIMONIAL_SUCCESS", "Új vélemény sikeresen hozzáadva!");
define("FEEDBACK_TESTIMONIAL_DELETE_SUCCESS", "Vélemény sikeresen törölve!");
define("FEEDBACK_NEW_SLIDE_SUCCESS", "Új slide sikeresen hozzáadva!");
define("FEEDBACK_SLIDE_DELETE_SUCCESS", "A slide sikeresen törölve!");
define("FEEDBACK_SLIDE_UPDATE_SUCCESS", "A slide sikeresen frissítve!");

define("FEEDBACK_NEW_PHOTO_SUCCESS", "A képet sikeresen feltöltötte!");
define("FEEDBACK_NEW_PHOTO_GALLERY_SUCCESS", "A képgaléria új eleme sikeresen feltöltve!");
define("FEEDBACK_PHOTO_DELETE_SUCCESS", "A kép sikeresen törölve a képgalériából!");
define("FEEDBACK_PHOTO_UPDATE_SUCCESS", "A kép módosításai sikeresen elmentve!");


define("FEEDBACK_SUPERADMIN_CAN_NOT_BE_BLOCKED", "Szuperadminisztrátor nem bolkkolható!"); 

?>