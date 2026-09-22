<?php

class RubytextInput
{
    public const MODE_BASIC = 'basic';
    public const MODE_ADVANCE = 'advance';

    public string $mode;
    public string $text;
    public int $start;
    public string $ruby;

    public function __construct(
        string $mode,
        string $text,
        int $start,
        string $ruby
    ) {
        $this->mode = $mode;
        $this->text = $text;
        $this->start = $start;
        $this->ruby = $ruby;
    }
}


/**
 * Create log for wp
 * @param string $message
 * @return string
 */
function rubytext_log(string $message): string
{
    if (function_exists('wp_date')) {
        $timestamp = wp_date('Y-m-d H:i:s');
    } else {
        $timestamp = date('Y-m-d H:i:s');
    }

    return '[' . $timestamp . '] ' . $message;
}


/**
 * Validate Rubytext
 *
 * @param RubytextInput $input
 * @return array<string>
 */
function rubytext_validate(RubytextInput $input): array
{
    $errors = [];

    if (
        $input->mode !== RubytextInput::MODE_BASIC &&
        $input->mode !== RubytextInput::MODE_ADVANCE
    ) {
        $errors[] = '不正なモードです。';
    }

    //validate text
    if ($input->text === '') {
        $errors[] = '文字列を入力してください。';
    }

    //validate start
    if ($input->start < 1) {
        $errors[] = '開始位置は1以上にしてください。';
    }

    //validate text length
    $textLength = mb_strlen($input->text);

    if ($input->start > $textLength) {
        $errors[] = '開始位置が文字列の長さを超えています。';
    }

    //validate ruby
    if ($input->ruby === '') {
        $errors[] = '上部に付ける文字列を入力してください。';
    }

    if (
        $input->ruby !== '' &&
        !preg_match('/^[a-zA-Z0-9]+$/', $input->ruby)
    ) {
        $errors[] = '上部に付ける文字列は半角英語数字のみ使用できます。';
    }

    if (
        $input->ruby !== '' &&
        preg_match('/[jJqQyY]/', $input->ruby)
    ) {
        $errors[] = '上部に付ける文字列にはj、q、yを使用できません。';
    }

    if ($input->start <= $textLength) {
        $remainingLength = $textLength - $input->start + 1;
        $rubyLength = mb_strlen($input->ruby);

        if ($rubyLength > $remainingLength) {
            $errors[] = '上部に付ける文字列が長すぎます。';
        }
    }
    //advance
    if ($input->mode === RubytextInput::MODE_ADVANCE) {
        return rubytext_validate_advance($input->text);
    }

    return $errors;
}

function rubytext_validate_advance(string $text): array
{
    $errors = [];
    $markers = rubytext_find_advance_ruby($text);

    foreach ($markers as $marker) {
        // ! の直前に文字が存在するか確認する。
        $beforeBang = substr($text, 0, $marker['offset']);

        if (mb_substr($beforeBang, -1) === '') {
            $errors[] = '上部に付ける文字の前に文字列が必要です。';
        }

        // j / q / y は上部付与文字として使用できない。
        if (preg_match('/[jJqQyY]/', $marker['ruby'])) {
            $errors[] = '上部に付ける文字列にはj、q、yを使用できません。';
        }
    }

    return $errors;
}

/**
 * @param RubytextInput $input
 * @return string
 */
function rubytext_convert(RubytextInput $input): string
{
    //Basic
    if ($input->mode === RubytextInput::MODE_BASIC) {
        $text = $input->text;
        $ruby = $input->ruby;

        //to array
        $textChars = mb_str_split($text);
        $rubyChars = mb_str_split($ruby);
        $startIndex = $input->start - 1;

        foreach ($rubyChars as $i => $rubyChar) {
            $combiningChar =
                RUBYTEXT_COMBINING_LATIN_SMALL_LETTER[$rubyChar]
                ?? null;

            //except j/q/y
            if ($combiningChar === null) {
                continue;
            }

            $textIndex = $startIndex + $i;

            if (isset($textChars[$textIndex])) {
                $textChars[$textIndex] .= $combiningChar;
            }
        }

        return implode('', $textChars);
    }

    //Advance
    if ($input->mode === RubytextInput::MODE_ADVANCE) {
        return rubytext_convert_advance($input->text);
    }

    return $input->text;
}

function rubytext_convert_advance(string $text): string
{
    $markers = rubytext_find_advance_ruby($text);

    if (empty($markers)) {
        return $text;
    }

    $result = '';
    $cursor = 0;

    foreach ($markers as $marker) {
        $segment = substr(
            $text,
            $cursor,
            $marker['offset'] - $cursor
        );

        $result .= rubytext_apply_ruby(
            $segment,
            $marker['ruby']
        );

        $cursor = $marker['offset'] + 1 + strlen($marker['ruby']);
    }

    $result .= substr($text, $cursor);

    return $result;
}

function rubytext_apply_ruby(string $text, string $ruby): string
{
    $textChars = mb_str_split($text);
    $rubyChars = mb_str_split($ruby);

    $rubyLength = count($rubyChars);
    $startIndex = count($textChars) - $rubyLength;

    if ($startIndex < 0) {
        return $text;
    }

    foreach ($rubyChars as $i => $rubyChar) {
        $combiningChar =
            RUBYTEXT_COMBINING_LATIN_SMALL_LETTER[$rubyChar]
            ?? null;

        if ($combiningChar === null) {
            continue;
        }

        $textIndex = $startIndex + $i;

        if (isset($textChars[$textIndex])) {
            $textChars[$textIndex] .= $combiningChar;
        }
    }

    return implode('', $textChars);
}

/**
 * @param string $input
 * @return string
 */
function rubytext_find_advance_ruby(string $text): array
{
    preg_match_all(
        '/!([a-zA-Z0-9])/',
        $text,
        $matches,
        PREG_OFFSET_CAPTURE
    );

    $markers = [];

    foreach ($matches[1] as $match) {
        $ruby = $match[0];
        $rubyOffset = $match[1];

        $markers[] = [
            'offset' => $rubyOffset - 1,
            'ruby' => $ruby,
        ];
    }

    return $markers;
}