<?php

/**
 * Rubytext Front
 *
 * Basic / Advanceをタブで切り替えて表示する。
 */
function rubytext_front_shortcode(): string
{
    $active_tab = 'basic';

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['rubytext_advance_submit'])
    ) {
        $active_tab = 'advance';
    } elseif (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['rubytext_basic_submit'])
    ) {
        $active_tab = 'basic';
    }

    /*
     * 同一ページに複数の [rubytext] があっても
     * id が重複しないようにする。
     */
    $instance_id = wp_unique_id('rubytext-front-');

    $basic_tab_id = $instance_id . '-tab-basic';
    $advance_tab_id = $instance_id . '-tab-advance';

    $basic_panel_id = $instance_id . '-panel-basic';
    $advance_panel_id = $instance_id . '-panel-advance';

    ob_start();
    ?>

    <div class="rubytext-front">

        <div
            class="rubytext-front-tabs"
            role="tablist"
            aria-label="Rubytext mode"
        >

            <button
                type="button"
                class="rubytext-front-tab <?php
                    echo $active_tab === 'basic'
                        ? 'is-active'
                        : '';
                ?>"
                id="<?php echo esc_attr($basic_tab_id); ?>"
                role="tab"
                aria-controls="<?php echo esc_attr($basic_panel_id); ?>"
                aria-selected="<?php
                    echo $active_tab === 'basic'
                        ? 'true'
                        : 'false';
                ?>"
                data-rubytext-tab="basic"
            >
                Basic
            </button>

            <button
                type="button"
                class="rubytext-front-tab <?php
                    echo $active_tab === 'advance'
                        ? 'is-active'
                        : '';
                ?>"
                id="<?php echo esc_attr($advance_tab_id); ?>"
                role="tab"
                aria-controls="<?php echo esc_attr($advance_panel_id); ?>"
                aria-selected="<?php
                    echo $active_tab === 'advance'
                        ? 'true'
                        : 'false';
                ?>"
                data-rubytext-tab="advance"
            >
                Advance
            </button>

        </div>

        <div
            id="<?php echo esc_attr($basic_panel_id); ?>"
            class="rubytext-front-panel <?php
                echo $active_tab === 'basic'
                    ? 'is-active'
                    : '';
            ?>"
            role="tabpanel"
            aria-labelledby="<?php echo esc_attr($basic_tab_id); ?>"
            data-rubytext-panel="basic"
            <?php
                echo $active_tab === 'basic'
                    ? ''
                    : 'hidden';
            ?>
        >
            <?php echo rubytext_basic_shortcode(); ?>
        </div>

        <div
            id="<?php echo esc_attr($advance_panel_id); ?>"
            class="rubytext-front-panel <?php
                echo $active_tab === 'advance'
                    ? 'is-active'
                    : '';
            ?>"
            role="tabpanel"
            aria-labelledby="<?php echo esc_attr($advance_tab_id); ?>"
            data-rubytext-panel="advance"
            <?php
                echo $active_tab === 'advance'
                    ? ''
                    : 'hidden';
            ?>
        >
            <?php echo rubytext_advance_shortcode(); ?>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const frontBlocks = document.querySelectorAll(
                '.rubytext-front'
            );

            frontBlocks.forEach((frontBlock) => {
                const tabs = frontBlock.querySelectorAll(
                    '[data-rubytext-tab]'
                );

                const panels = frontBlock.querySelectorAll(
                    '[data-rubytext-panel]'
                );

                tabs.forEach((tab) => {
                    tab.addEventListener('click', () => {
                        const target = tab.dataset.rubytextTab;

                        if (!target) {
                            return;
                        }

                        tabs.forEach((item) => {
                            const isActive =
                                item.dataset.rubytextTab === target;

                            item.classList.toggle(
                                'is-active',
                                isActive
                            );

                            item.setAttribute(
                                'aria-selected',
                                isActive ? 'true' : 'false'
                            );
                        });

                        panels.forEach((panel) => {
                            const isActive =
                                panel.dataset.rubytextPanel === target;

                            panel.classList.toggle(
                                'is-active',
                                isActive
                            );

                            panel.hidden = !isActive;
                        });
                    });
                });
            });
        });
    </script>

    <?php

    return ob_get_clean();
}

add_shortcode(
    'rubytext',
    'rubytext_front_shortcode'
);