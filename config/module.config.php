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
            'redact_values_redactions' => Api\Adapter\RedactValuesRedactionAdapter::class,
        ],
    ],
    'controllers' => [
        'invokables' => [
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
                                'action' => 'show',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
