<?php
$pageTitle       = 'Skills & Education | Usman Farooq';
$pageDescription = 'Technical skills, education, leadership experience and certificates of Usman Farooq.';
$active          = 'skills';
require_once __DIR__ . '/includes/header.php';

// Skills grouped by category
$skillsByCategory = [];
$res = $conn->query('SELECT name, category FROM skills ORDER BY category, sort_order, name');
while ($row = $res->fetch_assoc()) {
    $skillsByCategory[$row['category']][] = $row['name'];
}

$education    = $conn->query('SELECT * FROM education ORDER BY start_year DESC');
$experience   = $conn->query('SELECT * FROM experience ORDER BY sort_order, id');
$certificates = $conn->query('SELECT * FROM certificates ORDER BY sort_order, id');
?>

<div class="container py-5">

    <h1 class="section-title">Skills &amp; Education</h1>

    <!-- Skills -->
    <section class="mb-5" aria-labelledby="skills-title">
        <h2 id="skills-title" class="h3 accent mb-3">Technical Skills</h2>
        <?php if (!$skillsByCategory): ?>
            <p class="muted">No skills added yet.</p>
        <?php endif; ?>
        <div class="row g-4">
            <?php foreach ($skillsByCategory as $category => $names): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card-custom h-100 p-4">
                        <h3 class="h6 text-uppercase muted"><?= e($category) ?></h3>
                        <ul class="list-unstyled mb-0 d-flex flex-wrap gap-2">
                            <?php foreach ($names as $name): ?>
                                <li><span class="badge tag fs-6"><?= e($name) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Education -->
    <section class="mb-5" aria-labelledby="edu-title">
        <h2 id="edu-title" class="h3 accent mb-3">Education</h2>
        <ul class="timeline list-unstyled">
            <?php while ($ed = $education->fetch_assoc()): ?>
                <li class="card-custom p-4 mb-3">
                    <h3 class="h5 mb-1"><?= e($ed['institute']) ?></h3>
                    <p class="mb-1"><?= e($ed['degree']) ?></p>
                    <?php if ($ed['details']): ?><p class="mb-1 muted"><?= e($ed['details']) ?></p><?php endif; ?>
                    <p class="mb-0 accent fw-bold"><?= e($ed['start_year']) ?> - <?= e($ed['end_year'] ?: 'Present') ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    </section>

    <!-- Experience -->
    <section class="mb-5" aria-labelledby="exp-title">
        <h2 id="exp-title" class="h3 accent mb-3">Experience &amp; Leadership</h2>
        <div class="row g-4">
            <?php while ($ex = $experience->fetch_assoc()): ?>
                <div class="col-md-6">
                    <article class="card-custom h-100 p-4">
                        <h3 class="h5 mb-1"><?= e($ex['role']) ?></h3>
                        <p class="mb-1 accent"><?= e($ex['organization']) ?></p>
                        <?php if ($ex['period_text']): ?><p class="small muted mb-2"><?= e($ex['period_text']) ?></p><?php endif; ?>
                        <p class="mb-0"><?= e($ex['description']) ?></p>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Certificates -->
    <section aria-labelledby="cert-title">
        <h2 id="cert-title" class="h3 accent mb-3">Certificates</h2>
        <div class="row g-4">
            <?php while ($c = $certificates->fetch_assoc()): ?>
                <div class="col-md-6">
                    <article class="card-custom h-100 p-3">
                        <a href="<?= e($c['image_path']) ?>" target="_blank" rel="noopener">
                            <img src="<?= e($c['image_path']) ?>" alt="<?= e($c['title']) ?> certificate"
                                 class="cert-img" loading="lazy">
                        </a>
                        <h3 class="h5 accent mt-3 mb-1"><?= e($c['title']) ?></h3>
                        <p class="mb-1 fw-semibold"><?= e($c['issuer']) ?><?= $c['issued_on'] ? ' | ' . e($c['issued_on']) : '' ?></p>
                        <p class="mb-0 muted"><?= e($c['description']) ?></p>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
