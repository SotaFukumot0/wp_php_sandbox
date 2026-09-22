<?php

/**
 * Rubytext Plugin mock
 *
 * rubytext_convert() の動作確認用
 * WordPressを起動せず、PHP CLIから実行する。
 */

require_once __DIR__ . '/dictionary.php';
require_once __DIR__ . '/util.php';


/**
 * テストケース
 */
$testCases = [
    [
        'name' => '英小文字',
        'input' => new RubytextInput(
            RubytextInput::MODE_BASIC,
            'abcdefg',
            1,
            'abcdefg'
        ),
    ],

    [
        'name' => '英大文字',
        'input' => new RubytextInput(
            RubytextInput::MODE_BASIC,
            'ABCDEFG',
            1,
            'ABCDEFG'
        ),
    ],

    [
        'name' => '数字',
        'input' => new RubytextInput(
            RubytextInput::MODE_BASIC,
            '0123456789',
            1,
            '0123456789'
        ),
    ],

    [
        'name' => '日本語',
        'input' => new RubytextInput(
            RubytextInput::MODE_BASIC,
            'こんにちは世界',
            1,
            'abc123'
        ),
    ],

    [
        'name' => '開始位置指定',
        'input' => new RubytextInput(
            RubytextInput::MODE_BASIC,
            'こんにちは世界',
            4,
            'abc123'
        ),
    ],
];


/**
 * テスト実行
 */
foreach ($testCases as $testCase) {

    echo "==============================\n";
    echo $testCase['name'] . "\n";
    echo "==============================\n";

    $input = $testCase['input'];

    echo 'text  : ' . $input->text . "\n";
    echo 'start : ' . $input->start . "\n";
    echo 'ruby  : ' . $input->ruby . "\n";

    /*
     * 入力値を検証
     */
    $errors = rubytext_validate($input);

    if (!empty($errors)) {

        echo "ERROR:\n";

        foreach ($errors as $error) {
            echo '- ' . $error . "\n";
        }

        echo "\n";

        continue;
    }

    /*
     * 変換
     */
    $result = rubytext_convert($input);

    echo 'result: ' . $result . "\n";
    echo "\n";
}

