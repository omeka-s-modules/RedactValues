<?php
namespace RedactValues;

use Laminas\Router\Http;

return [
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => sprintf('%s/../language', __DIR__),
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            sprintf('%s/../view', __DIR__),
        ],
    ],
    'entity_manager' => [
        'mapping_classes_paths' => [
            sprintf('%s/../src/Entity', __DIR__),
        ],
        'proxy_paths' => [
            sprintf('%s/../data/doctrine-proxies', __DIR__),
        ],
    ],
    'api_adapters' => [
        'invokables' => [
            'redact_values_patterns' => Api\Adapter\RedactValuesPatternAdapter::class,
            'redact_values_redactions' => Api\Adapter\RedactValuesRedactionAdapter::class,
        ],
    ],
    'controllers' => [
        'invokables' => [
            'RedactValues\Controller\Admin\Pattern' => Controller\Admin\PatternController::class,
            'RedactValues\Controller\Admin\Redaction' => Controller\Admin\RedactionController::class,
        ],
    ],
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'Redact Values', // @translate
                'route' => 'admin/redact-values-redaction',
                'controller' => 'redaction',
                'action' => 'browse',
                'resource' => 'RedactValues\Controller\Admin\Redaction',
                'useRouteMatch' => true,
                'pages' => [
                    [
                        'label' => 'Redactions', // @translate
                        'route' => 'admin/redact-values-redaction',
                        'controller' => 'redaction',
                        'action' => 'browse',
                        'resource' => 'RedactValues\Controller\Admin\Redaction',
                        'useRouteMatch' => true,
                        'pages' => [
                            [
                                'route' => 'admin/redact-values-redaction',
                                'controller' => 'redaction',
                                'action' => 'add',
                                'visible' => false,
                            ],
                            [
                                'route' => 'admin/redact-values-redaction-id',
                                'controller' => 'redaction',
                                'action' => 'edit',
                                'visible' => false,
                            ],
                        ],
                    ],
                    [
                        'label' => 'Patterns', // @translate
                        'route' => 'admin/redact-values-pattern',
                        'controller' => 'pattern',
                        'action' => 'browse',
                        'resource' => 'RedactValues\Controller\Admin\Pattern',
                        'useRouteMatch' => true,
                        'pages' => [
                            [
                                'route' => 'admin/redact-values-pattern',
                                'controller' => 'pattern',
                                'action' => 'add',
                                'visible' => false,
                            ],
                            [
                                'route' => 'admin/redact-values-pattern-id',
                                'controller' => 'pattern',
                                'action' => 'edit',
                                'visible' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'redact-values-redaction' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => '/redact-values/redaction[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'RedactValues\Controller\Admin',
                                'controller' => 'redaction',
                                'action' => 'browse',
                            ],
                        ],
                    ],
                    'redact-values-redaction-id' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => '/redact-values/redaction/:redaction-id[/:action]',
                            'constraints' => [
                                'redaction-id' => '\d+',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'RedactValues\Controller\Admin',
                                'controller' => 'redaction',
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'redact-values-pattern' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => '/redact-values/pattern[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'RedactValues\Controller\Admin',
                                'controller' => 'pattern',
                                'action' => 'browse',
                            ],
                        ],
                    ],
                    'redact-values-pattern-id' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => '/redact-values/pattern/:pattern-id[/:action]',
                            'constraints' => [
                                'pattern-id' => '\d+',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'RedactValues\Controller\Admin',
                                'controller' => 'pattern',
                                'action' => 'edit',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
