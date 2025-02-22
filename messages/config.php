<?php
/**
 * Configuration file for 'yii message/extract' command.
 *
 * This file is automatically generated by 'yii message/config' command.
 * It contains parameters for source code messages extraction.
 * You may modify this file to suit your needs.
 *
 * You can use 'yii message/config-template' command to create
 * template configuration file with detailed description for each parameter.
 */
return [
    'color' => null,
    'interactive' => true,
    'help' => null,
    'sourcePath' => '@vendor/DauTuTuDau/yii2-blog/',
    'messagePath' => '@vendor/DauTuTuDau/yii2-blog/messages',
    'languages' => [
        'en-US',
        'ru-RU',
        'hy',
    ],
    'translator' => 'Module::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'markUnused' => true,
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/vendor',
        '/BaseYii.php',
        '/node_modules',
        '/vagrant',
        '*test*',
        '*fixture*',
        'environments'
    ],
    'only' => [
        '*.php',
    ],
    'format' => 'db',
    'db' => 'db',
    'sourceMessageTable' => '{{%source_message}}',
    'messageTable' => '{{%message}}',
    'catalog' => 'messages',
    'ignoreCategories' => [],
    'phpFileHeader' => '',
    'phpDocBlock' => null,
];
