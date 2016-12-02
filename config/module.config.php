<?php

use CirclicalTwigTrans\Model\Twig\Trans;
use CirclicalTwigTrans\Factory\TransFactory;

return array(

    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'CirclicalTwigTrans\Model\Twig\Trans' => 'CirclicalTwigTrans\Factory\TransFactory',
        ),
    ),


    'zfctwig' => array(
        'extensions' => array(
            'CirclicalTwigTrans\Model\Twig\Trans',
        ),
    ),
);

