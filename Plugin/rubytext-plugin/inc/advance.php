<?php

function rubytext_advance_enqueue_assets(): void
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
    'rubytext_advance_enqueue_assets'
);

function rubytext_advance_shortcode(): string
{
    $result = '';
    $errors = [];
    $logs = [];
    $text = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['rubytext_advance_submit'])
    ) {
        $text = sanitize_textarea_field(
            $_POST['rubytext_advance_input'] ?? ''
        );

        // Advanceは入力文字列のみを使用する。
        $input = new RubytextInput(
            RubytextInput::MODE_ADVANCE,
            $text,
            0,
            ''
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

    <div class="rubytext rubytext-advance">

        <form method="post">

            <div class="rubytext-input-group">

                <label for="rubytext-advance-input">
                    1. 文字列<p>（!の後ろ1文字の半角英数字が上部に変換されます）</p>
                </label>

                <textarea
                    id="rubytext-advance-input"
                    class="rubytext-textarea"
                    name="rubytext_advance_input"
                ><?php echo esc_textarea($text); ?></textarea>

            </div>

            <div class="rubytext-submit-area">

                <button
                    type="submit"
                    class="rubytext-submit"
                    name="rubytext_advance_submit"
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

            <div class="rubytext-basic-result-row">

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
                        name="rubytext_advance_font"
                        value="font1"
                        data-font-class="rubytext-result-font1"
                        checked
                    >
                    フォント1
                </label>

                <label>
                    <input
                        type="radio"
                        name="rubytext_advance_font"
                        value="font2"
                        data-font-class="rubytext-result-font2"
                    >
                    フォント2
                </label>

                <label>
                    <input
                        type="radio"
                        name="rubytext_advance_font"
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
    'rubytext_advance',
    'rubytext_advance_shortcode'
);