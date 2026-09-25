<?php
/** @var array $faq */
$faq = $faq ?? require dirname(__DIR__) . '/data/faq.php';
$faqIdPrefix = $faqIdPrefix ?? 'faq';
$keys = array_keys($faq);
$first = $keys[0] ?? null;
?>
<div class="faq-compact" data-faq>
    <div class="faq-tabs" role="tablist" aria-label="Rubriques FAQ">
        <?php foreach ($faq as $id => $group): ?>
            <button
                type="button"
                class="faq-tab<?= $id === $first ? ' is-active' : '' ?>"
                role="tab"
                aria-selected="<?= $id === $first ? 'true' : 'false' ?>"
                data-faq-tab="<?= htmlspecialchars($id) ?>"
            ><?= htmlspecialchars($group['title']) ?></button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($faq as $id => $group): ?>
        <?php
        $items = $group['items'];
        $preview = array_slice($items, 0, 5);
        $rest = array_slice($items, 5);
        ?>
        <div
            class="faq-panel<?= $id === $first ? ' is-active' : '' ?>"
            role="tabpanel"
            data-faq-panel="<?= htmlspecialchars($id) ?>"
            <?= $id === $first ? '' : 'hidden' ?>
        >
            <?php foreach ($preview as $item): ?>
                <details class="faq-item">
                    <summary>
                        <span><?= htmlspecialchars($item['q']) ?></span>
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </summary>
                    <div class="faq-item__body"><?= htmlspecialchars($item['a']) ?></div>
                </details>
            <?php endforeach; ?>

            <?php if ($rest): ?>
                <details class="faq-more">
                    <summary class="faq-more__summary">
                        Voir plus (<?= count($rest) ?>)
                    </summary>
                    <?php foreach ($rest as $item): ?>
                        <details class="faq-item">
                            <summary>
                                <span><?= htmlspecialchars($item['q']) ?></span>
                                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                            </summary>
                            <div class="faq-item__body"><?= htmlspecialchars($item['a']) ?></div>
                        </details>
                    <?php endforeach; ?>
                </details>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
