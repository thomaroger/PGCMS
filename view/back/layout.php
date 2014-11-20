<?php
return array(
    'core_layout' => array(
        'admin' => array(
            'layout' => 'layout/admin',
            'modules' => array(
                'playgrounduser' => array(
                    'controllers' => array(
                        'playgrounduseradmin_login' => array(
                            'layout' => 'layout/adminlogin',
                        ),
                    ),
                ),
                'playgroundcore' => array(
                    'controllers' => array(
                        'playgrounduseradmin_core' => array(
                            'layout' => 'layout/adminlogin',
                        ),
                    ),
                ),
            ),
        ),
    ),
);