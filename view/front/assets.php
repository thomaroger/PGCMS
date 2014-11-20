<?php
return array(
    'assetic_configuration' => array(
        'modules' => array(
            'frontend' => array(
                'root_path' => array(
                    __DIR__ . '/../../../../design/frontend/default/base/assets',
                ),
                # collection of assets
                'collections' => array(
                    /**
                     * MAIN CSS FILES
                     */
                    'frontend_css' => array(
                        'assets' => array(
                            'style.css'                      => 'css/style.css',
                        ),
                        'options' => array(
                            'output' => 'frontend/css/base'
                        ),
                    ),

                    'frontend_js' => array(
                        'assets' => array(
                            'debug.js'                      => 'js/debug.js',
                        ),
                        'options' => array(
                            'output' => 'frontend/js/debug'
                        ),
                    ),
                ),
            ),
        ),
        'routes' => array(
            'frontend.*' => array(
                '@frontend_css',
                '@frontend_js',
            ),
            'article.*' => array(
                '@frontend_css',
                '@frontend_js',
            ),
            'poll.*' => array(
                '@frontend_css',
                '@frontend_js',
            ),
            'category.*' => array(
                '@frontend_css',
                '@frontend_js',
            ),
            'tag.*' => array(
                '@frontend_css',
                '@frontend_js',
            ),           
        ),
    ),
);