<?php

declare(strict_types=1);

namespace Ethereum;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'annotations' => [
            ],
            'commands' => [
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for he426100/ethereum-php-hyperf.',
                    'source' => __DIR__ . '/../publish/ethereum.php',
                    'destination' => BASE_PATH . '/config/autoload/ethereum.php',
                ],
            ],
        ];
    }
}