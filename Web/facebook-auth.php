<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'lib/Common/namespace.php');

//Checks if the user was authenticated by google and redirects to external authentication page
//Need to ask facebook token directly in the redirect_uri (?) -> Can't redirect to external auth page and then ask (???)
if (isset($_GET['code'])) { 
    //Init the Facebook SDK
    if (!session_id()) {
        session_start();
    }
    $facebook_Client = new Facebook\Facebook([
        'app_id'                => Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::FACEBOOK_CLIENT_ID),
        'app_secret'            => Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::FACEBOOK_CLIENT_SECRET),
        'default_graph_version' => 'v2.5'
    ]);

    $helper = $facebook_Client->getRedirectLoginHelper();
    $acesstoken = $helper->getAccessToken();
    $_SESSION['facebook_access_token'] = serialize($acesstoken);
    
    $code = filter_input(INPUT_GET,'code');
    header("Location: ".ROOT_DIR."Web/external-auth.php?type=fb&code=".$code);
    exit;
} else{
    header("Location:".ROOT_DIR."Web");
    exit();
}  