<?php

//log fileok adatai
$config['log'] = array(
    'error' => 'logs_error.log',
    'notice' => 'logs_notice.log'
);

$config['email'] = array(
    'password_reset' => array(
        'admin_url' => BASE_URL . 'admin/login/verifypasswordreset',
        'site_url' => BASE_URL . 'users/verifypasswordreset',
        'subject' => 'Új jelszó kérése.',
        'link' => 'Kattints a linkre a jelszó reseteléséhez.'
    ),
    'verification' => array(
        'site_url' => BASE_URL . 'felhasznalok/ellenorzes',
        'subject' => 'Regisztráció hitelesítése.',
        'link' => 'Kattints erre a linkre a regisztrációd aktiválásához.'
    ),
    'verification_newsletter' => array(
        'site_url' => BASE_URL . 'felhasznalok/ellenorzes_hirlevel',
        'subject' => 'Hírlevélre feliratkozás hitelesítése.',
        'link' => 'Kattints erre a linkre a feliratkozás aktiválásához.'
    )
);

$config['login'] = array(
    'facebook_login' => false,
    'facebook_login_app_id' => 'xxx',
    'facebook_login_app_secret' => 'xxx',
    'facebook_login_path' => 'login/loginWithFacebook',
    'facebook_register_path' => 'login/registerWithFacebook',
    'use_gravatar' => false,
    'avatar_size' => 44,
    'avatar_jpeg_quality' => 85,
    'avatar_default_image' => 'default.jpg',
    'avatar_path' => ''
);

$config['cookie'] = array(
    'runtime' => 1209600,
    'domain' => '.localhost'
);

$config['slider'] = array(
    'width' => 1170,
    'height' => 420,
    'thumb_width' => 200,
    'upload_path' => UPLOADS . 'slider_photo/'
);

$config['photogallery'] = array(
    'width' => 800,
    'thumb_width' => 320,
    'upload_path' => UPLOADS . 'photo_gallery/'
);

$config['hash_cost_factor'] = 10;
$config['language_default'] = 'hu';
$config['allowed_languages'] = array('hu', 'en', 'de', 'ru');
$config['reg_email_verify'] = true;


$config['user'] = array(
    'width' => 600,
    'height' => 200,
    //'thumb_width' => 80,
    'upload_path' => UPLOADS . 'user_photo/',
    'default_photo' => 'user_placeholder.png'
);

$config['clientphoto'] = array(
    'width' => 150,
    'height' => 100,
    'thumb_width' => 150,
    'upload_path' => UPLOADS . 'client_photo/',
    'default_photo' => 'client_placeholder.png'
);

$config['session'] = array(
    'expire_time_admin' => 3600,
    'expire_time_site' => 3600
        // 'last_activity_name_admin' => 'user_last_activity', // A $_SESSION['last_activity'] elem fogja tárolni az utolsó aktivitás idejét
        // 'last_activity_name_site' => 'user_site_last_activity' // A $_SESSION['last_activity'] elem fogja tárolni az utolsó aktivitás idejét
);
$config['blogphoto'] = array(
    'width' => 600,
    'height' => 400,
    'thumb_width' => 150,
    'upload_path' => UPLOADS . 'blog_photo/',
);
?>