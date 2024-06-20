<?php

return [
    'models' => [
        'user' => [
            'presenter' => \Pearls\User\Transformers\UserPresenter::class,
            'resource_url' => 'users',
            'default_picture' => 'assets/pearls/images/avatars/avatar.png',
        ],
        'role' => [
            'presenter' => \Pearls\User\Transformers\RolePresenter::class,
            'resource_url' => 'roles',
        ],
    ],
];