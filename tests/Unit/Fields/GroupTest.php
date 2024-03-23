<?php

use Tdwesten\StatamicBuilder\Enums\VisibilityOption;
use Tdwesten\StatamicBuilder\FieldTypes\Group;
use Tdwesten\StatamicBuilder\FieldTypes\Text;

test('Renders a group to array', function () {

    $group = Group::make('cta', [
        Text::make('label')
            ->displayName('Label')
            ->instructionsPosition('above')
            ->visibility(VisibilityOption::Visible)
            ->listable()
            ->width(33),
    ])->displayName('Call to Action');

    $expected = [
        'handle' => 'cta',
        'field' => [
            'antlers' => false,
            'display' => 'Call to Action',
            'duplicate' => true,
            'fields' => [
                [
                    'handle' => 'label',
                    'field' => [
                        'antlers' => false,
                        'display' => 'Label',
                        'duplicate' => true,
                        'hide_display' => false,
                        'input_type' => 'text',
                        'instructions_position' => 'above',
                        'listable' => true,
                        'replicator_preview' => true,
                        'type' => 'text',
                        'visibility' => 'visible',
                        'width' => 33,
                    ],
                ],
            ],
            'fullscreen' => false,
            'hide_display' => false,
            'instructions_position' => 'above',
            'listable' => 'visible',
            'replicator_preview' => true,
            'type' => 'group',
            'visibility' => 'visible',
        ],
    ];

    expect($group->toArray())->toBe($expected);
});

test('A group can have a group', function () {

    $group = Group::make('counties', [
        Group::make('states', [
            Text::make('label')
                ->displayName('Label')
                ->instructionsPosition('above')
                ->visibility(VisibilityOption::Visible)
                ->listable()
                ->width(33),
        ])->displayName('Call to Action'),
    ])->displayName('Call to Action');

    $expected = [
        'handle' => 'counties',
        'field' => [
            'antlers' => false,
            'display' => 'Call to Action',
            'duplicate' => true,
            'fields' => [
                [
                    'handle' => 'states',
                    'field' => [
                        'antlers' => false,
                        'display' => 'Call to Action',
                        'duplicate' => true,
                        'fields' => [
                            [
                                'handle' => 'label',
                                'field' => [
                                    'antlers' => false,
                                    'display' => 'Label',
                                    'duplicate' => true,
                                    'hide_display' => false,
                                    'input_type' => 'text',
                                    'instructions_position' => 'above',
                                    'listable' => true,
                                    'replicator_preview' => true,
                                    'type' => 'text',
                                    'visibility' => 'visible',
                                    'width' => 33,
                                ],
                            ],
                        ],
                        'fullscreen' => false,
                        'hide_display' => false,
                        'instructions_position' => 'above',
                        'listable' => 'visible',
                        'replicator_preview' => true,
                        'type' => 'group',
                        'visibility' => 'visible',
                    ],
                ],
            ],
            'fullscreen' => false,
            'hide_display' => false,
            'instructions_position' => 'above',
            'listable' => 'visible',
            'replicator_preview' => true,
            'type' => 'group',
            'visibility' => 'visible',
        ],
    ];

    expect($group->toArray())->toBe($expected);
});

test('Can set to fullscreen', function () {

    $group = Group::make('cta', [
        Text::make('label')
            ->displayName('Label')
            ->instructionsPosition('above')
            ->visibility(VisibilityOption::Visible)
            ->listable()
            ->width(33),
    ])->displayName('Call to Action')->fullscreen();

    expect($group->toArray()['field']['fullscreen'])->toBe(true);
});
