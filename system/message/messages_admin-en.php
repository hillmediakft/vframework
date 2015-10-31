<?php
$msg['unknown_error'] = 'Unknown error occurred!';
$msg['password_wrong_3_times'] = 'You have typed in a wrong password 3 or more times already. Please wait 30 seconds to try again.';
$msg['account_not_activated_yet'] = 'Your account is not activated yet. Please click on the confirm link in the mail.';
$msg['password_wrong'] = 'Password was wrong.';
$msg['user_does_not_exist'] = 'This user does not exist.';
// The 'login failed'-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
$msg['login_failed'] = 'Login failed.';

$msg['username_field_empty'] = 'Username field was empty.';
$msg['userfirstname_field_empty'] = 'User firstname field was empty.';
$msg['userlastname_field_empty'] = 'User lastname field was empty.';
$msg['userphone_field_empty'] = 'User phone field was empty.';
$msg['password_field_empty'] = 'Password field was empty.';
$msg['email_field_empty'] = 'Email and passwords fields were empty.';

$msg['email_and_password_fields_empty'] = 'Email field was empty.';
$msg['username_same_as_old_one'] = 'Sorry, that username is the same as your current one. Please choose another one.';
$msg['username_already_taken'] = 'Sorry, that username is already taken. Please choose another one.';
$msg['user_email_already_taken'] = 'Sorry, that email is already in use. Please choose another one.';
$msg['username_change_successful'] = 'Your username has been changed successfully.';
$msg['username_and_password_field_empty'] = 'Username and password fields were empty.';

$msg['username_does_not_fit_pattern'] = 'Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.';
$msg['userfirstname_does_not_fit_pattern'] = 'User firstname does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.';
$msg['userlastname_does_not_fit_pattern'] = 'User lastname does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.';
$msg['userphone_does_not_fit_pattern'] = 'User phone number does not fit the name scheme: only 11-22-333-444 or 11 22 333 444 or 1122333444 .';

$msg['email_does_not_fit_pattern'] = 'Sorry, your chosen email does not fit into the email naming pattern.';
$msg['email_same_as_old_one'] = 'Sorry, that email address is the same as your current one. Please choose another one.';
$msg['email_change_successful'] = 'Your email address has been changed successfully.';
$msg['captcha_wrong'] = 'The entered captcha security characters were wrong.';
$msg['password_repeat_wrong'] = 'Password and password repeat are not the same.';
$msg['password_too_short'] = 'Password has a minimum length of 6 characters.';

$msg['username_too_short_or_too_long'] = 'Username cannot be shorter than 2 or longer than 64 characters.';
$msg['userfirstname_too_short_or_too_long'] = 'User firstname cannot be shorter than 2 or longer than 64 characters.';
$msg['userlastname_too_short_or_too_long'] = 'User lastname cannot be shorter than 2 or longer than 64 characters.';

$msg['email_too_long'] = 'Email cannot be longer than 64 characters.';
$msg['account_successfully_created'] = 'Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.';

$msg['user_successfully_created'] = 'User created successfully.';

$msg['verification_mail_sending_failed'] = 'Sorry, we could not send you an verification mail. Your account has NOT been created.';
$msg['account_creation_failed'] = 'Sorry, your registration failed. Please go back and try again.';
$msg['verification_mail_sending_error'] = 'Verification mail could not be sent due to: ';
$msg['verification_mail_sending_successful'] = 'A verification mail has been sent successfully.';
$msg['account_activation_successful'] = 'Activation was successful! You can now log in.';
$msg['account_activation_failed'] = 'Sorry, no such id/verification code combination here...';
$msg['avatar_upload_successful'] = 'Avatar upload was successful.';
$msg['avatar_upload_wrong_type'] = 'Only JPEG and PNG files are supported.';
$msg['avatar_upload_too_small'] = 'Avatar source file\'s width/height is too small. Needs to be 100x100 pixel minimum.';
$msg['avatar_upload_too_big'] = 'Avatar source file is too big. 5 Megabyte is the maximum.';
$msg['avatar_folder_does_not_exist_or_not_writable'] = 'Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.';
$msg['avatar_image_upload_failed'] = 'Something went wrong with the image upload.';
$msg['password_reset_token_fail'] = 'Could not write token to database.';
$msg['password_reset_token_missing'] = 'No password reset token.';
$msg['password_reset_mail_sending_error'] = 'Password reset mail could not be sent due to: ';
$msg['password_reset_mail_sending_successful'] = 'A password reset mail has been sent successfully.';
$msg['password_reset_link_expired'] = 'Your reset link has expired. Please use the reset link within one hour.';
$msg['password_reset_combination_does_not_exist'] = 'Username/Verification code combination does not exist.';
$msg['password_reset_link_valid'] = 'Password reset validation link is valid. Please change the password now.';
$msg['password_change_successful'] = 'Password successfully changed.';
$msg['password_change_failed'] = 'Sorry, your password changing failed.';
$msg['account_upgrade_successful'] = 'Account upgrade was successful.';
$msg['account_upgrade_failed'] = 'Account upgrade failed.';
$msg['account_downgrade_successful'] = 'Account downgrade was successful.';
$msg['account_downgrade_failed'] = 'Account downgrade failed.';
$msg['note_creation_failed'] = 'Note creation failed.';
$msg['note_editing_failed'] = 'Note editing failed.';
$msg['note_deletion_failed'] = 'Note deletion failed.';
$msg['cookie_invalid'] = 'Your remember-me-cookie is invalid.';
$msg['cookie_login_successful'] = 'You were successfully logged in via the remember-me-cookie.';
$msg['facebook_login_not_registered'] = 'Sorry, you don\'t have an account here. Please register first.';
$msg['facebook_email_needed'] = 'Sorry, but you need to allow us to see your email address to register.';
$msg['facebook_uid_already_exists'] = 'Sorry, but you have already registered here (your Facebook ID exists in our database).';
$msg['facebook_email_already_exists'] = 'Sorry, but you have already registered here (your Facebook email exists in our database).';
$msg['facebook_username_already_exists'] = 'Sorry, but you have already registered here (your Facebook username exists in our database).';
$msg['facebook_register_successful'] = 'You have been successfully registered with Facebook.';
$msg['facebook_offline'] = 'We could not reach the Facebook servers. Maybe Facebook is offline (that really happens sometimes).';

$msg['user_data_update_success'] = 'A felhasználó adatai módosultak.';
$msg['user_data_update_fail'] = 'Hiba történt a felhasználó adatainak módosításakor.';
$msg['user_delete_fail'] = 'Nem törlődött a felhasználó.';
$msg['user_delete_successful'] = 'Sikeres törlés.';
$msg['page_update_success'] = 'Az oldal módosítások sikeresen elmentve!';
$msg['settings_update_success'] = 'A beállítások módosításai sikeresen elmentve!';
$msg['content_update_success'] = 'A tartalom módosításai sikeresen elmentve!';
$msg['testimonial_update_success'] = 'A rólunk mondták módosításai sikeresen elmentve!';
$msg['new_testimonial_success'] = 'Új vélemény sikeresen hozzáadva!';
$msg['testimonial_delete_success'] = 'Vélemény sikeresen törölve!';
$msg['new_slide_success'] = 'Új slide sikeresen hozzáadva!';
$msg['slide_delete_success'] = 'A slide sikeresen törölve!';
$msg['slide_update_success'] = 'A slide sikeresen frissítve!';

$msg['new_photo_success'] = 'A képet sikeresen feltöltötte!';
$msg['new_photo_gallery_success'] = 'A képgaléria új eleme sikeresen feltöltve!';
$msg['photo_delete_success'] = 'A kép sikeresen törölve a képgalériából!';
$msg['photo_update_success'] = 'A kép módosításai sikeresen elmentve!';

$msg['superadmin_can_not_be_blocked'] = 'Szuperadminisztrátor nem bolkkolható!';