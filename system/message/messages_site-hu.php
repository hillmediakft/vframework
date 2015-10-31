<?php
$msg['unknown_error'] = 'Ismeretlen hiba történt!';
$msg['password_wrong_3_times'] = '3 alkalommal rossz jelszót adott meg, 30 másodperc múlva próbálkozhat újra!';
$msg['account_not_activated_yet'] = 'A fiókja még nem lett aktiválva, kattintson a visszaigazoló e-mailben található linkre az aktiváláshoz!';
$msg['password_wrong'] = 'Hibás jelszó!';
$msg['user_does_not_exist'] = 'Nem létező felhasználó!';
// The 'login failed'-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
$msg['login_failed'] = 'Hibás bejelentkezés!';

$msg['username_field_empty'] = 'A felhasználó név mező üresen maradt!';
$msg['userfirstname_field_empty'] = 'A vezetéknév mező üresen maradt!';
$msg['userlastname_field_empty'] = 'A keresztnév mező üresen maradt!';
$msg['userphone_field_empty'] = 'A telefonszám mező üresen maradt!';
$msg['password_field_empty'] = 'A jelszó mező üresen maradt!';
$msg['email_field_empty'] = 'Az e-mail mező üresen maradt!';

$msg['email_and_password_fields_empty'] = 'Az e-mail és jelszó mező üresen maradt!';
$msg['username_same_as_old_one'] = 'A felhasználó név ugyanaz, mint a régi. Válassz másikat!';
$msg['username_already_taken'] = 'A felhasználó név már foglalt. Válassz másikat!';
$msg['user_email_already_taken'] = 'Az e-mail cím már foglalt. Válassz másikat!';
$msg['username_change_successful'] = 'A felhasználó nevet módosítottuk!';
$msg['username_and_password_field_empty'] = 'A felhasználó név és jelszó mezők üresen maradtak!';

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

$msg['email_too_long'] = 'Az e-mail cím nem lehet hosszabb 64 karakternél.';
$msg['account_successfully_created'] = 'A felhasználói fiók sikeresen létrehozva';

$msg['user_successfully_created'] = 'A felhasználó sikeresen létrehozva!';
$msg['click_verification_link'] = 'A feliratkozás aktiválásához kattints az e-mailben található visszaigazolási linkre!';

$msg['verification_mail_sending_failed'] = 'Sajnos nem tudtuk a visszaigazoló e-mailt elküldeni, így a fiókot nem tudtuk lérehozni!';
$msg['account_creation_failed'] = 'Sikertelen regisztráció!';
$msg['verification_mail_sending_error'] = 'A visszaigazoló e-mail üzenetet nem tudtuk elküldeni. A hiba oka: ';
$msg['verification_mail_sending_successful'] = 'Visszaigazoló e-mail üzenet elküldve.';
$msg['account_activation_successful'] = 'Sikeres aktiválás!';
$msg['account_activation_failed'] = 'Hiba: nem létező azonosító/visszaigazoló kód!';
$msg['avatar_upload_successful'] = 'Avatar upload was successful.';
$msg['avatar_upload_wrong_type'] = 'Only JPEG and PNG files are supported.';
$msg['avatar_upload_too_small'] = 'Avatar source file\'s width/height is too small. Needs to be 100x100 pixel minimum.';
$msg['avatar_upload_too_big'] = 'Avatar source file is too big. 5 Megabyte is the maximum.';
$msg['avatar_folder_does_not_exist_or_not_writable'] = 'Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.';
$msg['avatar_image_upload_failed'] = 'A kép feltöltés nem sikerült.';
$msg['password_reset_token_fail'] = 'Nme tudtuk a tokent az adatbázisba írni.';
$msg['password_reset_token_missing'] = 'Hiányzik a jelszó reset token.';
$msg['password_reset_mail_sending_error'] = 'A jelszó reset e-mailt nem tuftuk elküldeni a következő okból: ';
$msg['password_reset_mail_sending_successful'] = 'A password reset mail has been sent successfully.';
$msg['password_reset_link_expired'] = 'A reset link lejárt. A reset linkre egy órán belül kell kattintani.';
$msg['password_reset_combination_does_not_exist'] = 'Username/Verification code combination does not exist.';
$msg['password_reset_link_valid'] = 'Password reset validation link is valid. Please change the password now.';
$msg['password_change_successful'] = 'A jelszó sikeresen módosítva.';
$msg['password_change_failed'] = 'Saknos a jelszó módosítást nem tudtuk végrehajtani.';
$msg['account_upgrade_successful'] = 'A fiók upgrade sikerült.';
$msg['account_upgrade_failed'] = 'A fiók upgrade nem sikerült.';
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