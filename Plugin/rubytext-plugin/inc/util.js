document.addEventListener('DOMContentLoaded', () => {
    const rubytextBlocks = document.querySelectorAll('.rubytext');

    rubytextBlocks.forEach((basicBlock) => {
        const result = basicBlock.querySelector('.rubytext-result');
        const copyButton = basicBlock.querySelector('.rubytext-copy');
        const fontRadios = basicBlock.querySelectorAll(
            '.rubytext-font-options input[type="radio"]'
        );

        if (!result || !copyButton) {
            return;
        }

        copyButton.addEventListener('click', async () => {
            const text = result.value;

            if (text === '') {
                return;
            }

            try {
                await navigator.clipboard.writeText(text);

                const originalText = copyButton.textContent;
                copyButton.textContent = 'コピー済み';

                window.setTimeout(() => {
                    copyButton.textContent = originalText;
                }, 1500);
            } catch (error) {
                // Clipboard APIが使用できない環境向けのフォールバック
                result.focus();
                result.select();

                try {
                    document.execCommand('copy');

                    const originalText = copyButton.textContent;
                    copyButton.textContent = 'コピー済み';

                    window.setTimeout(() => {
                        copyButton.textContent = originalText;
                    }, 1500);
                } catch (fallbackError) {
                    console.error('コピーに失敗しました。', fallbackError);
                }
            }
        });

        fontRadios.forEach((radio) => {
            radio.addEventListener('change', () => {
                const fontClass = radio.dataset.fontClass;

                if (!fontClass) {
                    return;
                }

                result.classList.remove(
                    'rubytext-result-font1',
                    'rubytext-result-font2',
                    'rubytext-result-font3'
                );

                result.classList.add(fontClass);
            });
        });
    });
});