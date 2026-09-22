<?php

function rubytext_basic_enqueue_assets(): void
{
    $css_path = __DIR__ . '/basic.css';
    $js_path = __DIR__ . '/util.js';

    wp_enqueue_style(
        'rubytext-basic',
        plugins_url('basic.css', __FILE__),
        [],
        file_exists($css_path) ? filemtime($css_path) : null
    );

    wp_enqueue_script(
        'rubytext-util',
        plugins_url('util.js', __FILE__),
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true
    );
}

add_action(
    'wp_enqueue_scripts',
    'rubytext_basic_enqueue_assets'
);

function rubytext_basic_shortcode(): string
{
    $result = '';
    $errors = [];
    $logs = [];

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['rubytext_basic_submit'])
    ) {
        $text = sanitize_text_field(
            $_POST['rubytext_basic_input'] ?? ''
        );

        $start = intval(
            $_POST['rubytext_basic_start'] ?? 0
        );

        $ruby = sanitize_text_field(
            $_POST['rubytext_basic_ruby'] ?? ''
        );

        // create params
        $input = new RubytextInput(
            RubytextInput::MODE_BASIC,
            $text,
            $start,
            $ruby
        );

        $errors = rubytext_validate($input);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $logs[] = rubytext_log($error);
            }
        } else {
            $result = rubytext_convert($input);
        }
    }

    ob_start();
    ?>

    <div class="rubytext rubytext-basic">

        <form method="post">

            <div class="rubytext-input-group">

                <label for="rubytext-basic-input">
                    1. 文字列
                </label>

                <input
                    type="text"
                    id="rubytext-basic-input"
                    class="rubytext-input"
                    name="rubytext_basic_input"
                    value="<?php
                        echo esc_attr(
                            $_POST['rubytext_basic_input'] ?? ''
                        );
                    ?>"
                >

            </div>

            <div class="rubytext-input-group">

                <label for="rubytext-basic-start">
                    2. 上部の文字開始位置
                </label>

                <input
                    type="number"
                    id="rubytext-basic-start"
                    class="rubytext-start"
                    name="rubytext_basic_start"
                    value="<?php
                        echo esc_attr(
                            $_POST['rubytext_basic_start'] ?? ''
                        );
                    ?>"
                    min="1"
                >

            </div>

            <div class="rubytext-input-group">

                <label for="rubytext-basic-ruby">
                    3. 上部文字列（j/q/yを除く半角英数字のみ可）
                </label>

                <input
                    type="text"
                    id="rubytext-basic-ruby"
                    class="rubytext-input"
                    name="rubytext_basic_ruby"
                    value="<?php
                        echo esc_attr(
                            $_POST['rubytext_basic_ruby'] ?? ''
                        );
                    ?>"
                >

            </div>

            <div class="rubytext-submit-area">

                <button
                    type="submit"
                    class="rubytext-submit"
                    name="rubytext_basic_submit"
                    value="1"
                >
                    変換
                </button>

            </div>

        </form>

        <div class="rubytext-result-area">

            <p class="rubytext-result-title">
                [変換結果]
            </p>

            <div class="rubytext-result-row">

                <input
                    type="text"
                    class="rubytext-result rubytext-result-font1"
                    value="<?php echo esc_attr($result); ?>"
                    readonly
                    aria-label="変換結果"
                >

                <button
                    type="button"
                    class="rubytext-copy"
                >
                    コピー
                </button>

            </div>

            <p class="rubytext-font-title">
                ※フォント変更
            </p>

            <div class="rubytext-font-options">

                <label>
                    <input
                        type="radio"
                        name="rubytext_basic_font"
                        value="font1"
                        data-font-class="rubytext-result-font1"
                        checked
                    >
                    フォント1
                </label>

                <label>
                    <input
                        type="radio"
                        name="rubytext_basic_font"
                        value="font2"
                        data-font-class="rubytext-result-font2"
                    >
                    フォント2
                </label>

                <label>
                    <input
                        type="radio"
                        name="rubytext_basic_font"
                        value="font3"
                        data-font-class="rubytext-result-font3"
                    >
                    フォント3
                </label>

            </div>

            <p class="rubytext-log-title">
                [ログ]
            </p>

            <textarea
                class="rubytext-log"
                readonly
            ><?php echo esc_textarea(implode("\n", $logs)); ?></textarea>

        </div>

    </div>

    <?php

    return ob_get_clean();
}

add_shortcode(
    'rubytext_basic',
    'rubytext_basic_shortcode'
);
