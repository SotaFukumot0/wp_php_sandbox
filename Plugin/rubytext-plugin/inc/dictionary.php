<?php

/**
 * COMBINING LATIN SMALL LETTER Dictionary
 *
 * a-z / A-Z に対応する
 * COMBINING LATIN SMALL LETTER を定義する。
 *
 * Unicode標準に対応する文字が存在しない
 * j / q / y は null とする。
 *
 * 大文字についても、小文字と同じ
 * Combining Characterを使用する。
 *
 * @var array<string, ?string>
 */
const RUBYTEXT_COMBINING_LATIN_SMALL_LETTER = [
    'a' => "\u{0363}",
    'b' => "\u{1DE8}",
    'c' => "\u{0368}",
    'd' => "\u{0369}",
    'e' => "\u{0364}",
    'f' => "\u{1DEB}",
    'g' => "\u{1DDA}",
    'h' => "\u{036A}",
    'i' => "\u{0365}",
    'j' => null,
    'k' => "\u{1DDC}",
    'l' => "\u{1DDD}",
    'm' => "\u{036B}",
    'n' => "\u{1DE0}",
    'o' => "\u{0366}",
    'p' => "\u{1DEE}",
    'q' => null,
    'r' => "\u{036C}",
    's' => "\u{1DE4}",
    't' => "\u{036D}",
    'u' => "\u{0367}",
    'v' => "\u{036E}",
    'w' => "\u{1DF1}",
    'x' => "\u{036F}",
    'y' => null,
    'z' => "\u{1DE6}",

    'A' => "\u{0363}",
    'B' => "\u{1DE8}",
    'C' => "\u{0368}",
    'D' => "\u{0369}",
    'E' => "\u{0364}",
    'F' => "\u{1DEB}",
    'G' => "\u{1DDA}",
    'H' => "\u{036A}",
    'I' => "\u{0365}",
    'J' => null,
    'K' => "\u{1DDC}",
    'L' => "\u{1DDD}",
    'M' => "\u{036B}",
    'N' => "\u{1DE0}",
    'O' => "\u{0366}",
    'P' => "\u{1DEE}",
    'Q' => null,
    'R' => "\u{036C}",
    'S' => "\u{1DE4}",
    'T' => "\u{036D}",
    'U' => "\u{0367}",
    'V' => "\u{036E}",
    'W' => "\u{1DF1}",
    'X' => "\u{036F}",
    'Y' => null,
    'Z' => "\u{1DE6}",
    //number isnt COMBINING_LATIN_SMALL_LETTER
    '0' => "\u{2070}",
    '1' => "\u{00B9}",
    '2' => "\u{00B2}",
    '3' => "\u{00B3}",
    '4' => "\u{2074}",
    '5' => "\u{2075}",
    '6' => "\u{2076}",
    '7' => "\u{2077}",
    '8' => "\u{2078}",
    '9' => "\u{2079}",
];