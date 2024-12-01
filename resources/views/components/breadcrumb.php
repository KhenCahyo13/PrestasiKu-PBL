<div class="d-flex flex-column gap-1">
    <h1 class="my-0 heading-5 font-semibold"><?= $pageTitle ?? '' ?></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumbItems as $index => $item): ?>
                <?php if ($index + 1 === count($breadcrumbItems)): ?>
                    <li class="breadcrumb-item text-sm text-primary" aria-current="page"><?= htmlspecialchars($item['label']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item text-sm"><a href="<?= htmlspecialchars($item['url']) ?>" class="text-secondary"><?= htmlspecialchars($item['label']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>