<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['pre_system'][] = array(
    'class' => 'SiteOffline',
    'function' => 'offline',
    'filename' => 'SiteOffline.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Access',
    'function' => 'checkAccess',
    'filename' => 'Access.php',
    'filepath' => 'hooks',
    'params'   => array()
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'UnAccess',
    'function' => 'checkAccess',
    'filename' => 'UnAccess.php',
    'filepath' => 'hooks',
    'params'   => array()
);

$hook['post_controller_constructor'][] = array(
    'class' => 'LanguageLoader',
    'function' => 'initialize',
    'filename' => 'LanguageLoader.php',
    'filepath' => 'hooks'
    );

/*$hook['post_controller_constructor'][] = array(
    'class' => 'ValidateController',
    'function' => 'sessionActive',
    'filename' => 'ValidateController.php',
    'filepath' => 'hooks'
);*/
/*$hook['pre_system'][] = array(
    'class' => 'ValidateController',
    'function' => 'validateController',
    'filename' => 'ValidateController.php',
    'filepath' => 'hooks'
);*/