<?php
$pageTitle       = 'Projects | Usman Farooq';
$pageDescription = 'Projects built by Usman Farooq: C++, MySQL, web and platform projects.';
$active          = 'projects';
require_once __DIR__ . '/includes/header.php';

$projects = [];
$allTags  = [];
$res = $conn->query('SELECT id, title, description, technologies, github_link, live_link FROM projects ORDER BY created_at DESC, id DESC');
while ($row = $res->fetch_assoc()) {
    $row['tags'] = split_tags($row['technologies']);
    foreach ($row['tags'] as $t) {
        $allTags[strtolower($t)] = $t;   // unique, keeps original spelling
    }
    $projects[] = $row;
}
ksort($allTags);
?>

<div class="container py-5">
    <h1 class="section-title">My Projects</h1>
    <p class="text-center muted mb-4">Some of the projects I have worked on. Search or filter by technology.</p>

    <!-- Search + filter (JavaScript feature) -->
    <div class="row g-3 justify-content-center mb-4">
        <div class="col-md-6">
            <label for="projectSearch" class="form-label">Search projects</label>
            <input type="search" id="projectSearch" class="form-control" placeholder="Type a title, word or technology...">
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 justify-content-center mb-3" role="group" aria-label="Filter projects by technology">
        <button type="button" class="btn btn-sm btn-accent filter-btn" data-filter="all" aria-pressed="true">All</button>
        <?php foreach ($allTags as $key => $label): ?>
            <button type="button" class="btn btn-sm btn-outline-accent filter-btn"
                    data-filter="<?= e($key) ?>" aria-pressed="false"><?= e($label) ?></button>
        <?php endforeach; ?>
    </div>
    <p class="text-center muted" id="resultCount" aria-live="polite"></p>

    <div class="row g-4" id="projectList">
        <?php foreach ($projects as $p): ?>
            <div class="col-md-6 col-lg-4 project-item"
                 data-tags="<?= e(strtolower(implode(',', $p['tags']))) ?>"
                 data-text="<?= e(strtolower($p['title'] . ' ' . $p['description'] . ' ' . $p['technologies'])) ?>">
                <article class="card-custom h-100 p-4 d-flex flex-column">
                    <h2 class="h5 accent"><?= e($p['title']) ?></h2>
                    <p class="flex-grow-1"><?= e($p['description']) ?></p>
                    <p>
                        <?php foreach ($p['tags'] as $tag): ?>
                            <span class="badge tag"><?= e($tag) ?></span>
                        <?php endforeach; ?>
                    </p>
                    <div class="d-flex gap-3">
                        <?php if ($p['github_link']): ?>
                            <a class="link-accent" href="<?= e($p['github_link']) ?>" target="_blank" rel="noopener">View on GitHub</a>
                        <?php endif; ?>
                        <?php if ($p['live_link']): ?>
                            <a class="link-accent" href="<?= e($p['live_link']) ?>" target="_blank" rel="noopener">Visit Project</a>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>

    <p id="noResults" class="text-center muted mt-4" hidden>No projects match your search.</p>
    <?php if (!$projects): ?>
        <p class="text-center muted mt-4">No projects have been added yet.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
