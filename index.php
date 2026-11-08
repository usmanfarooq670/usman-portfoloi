<?php
$pageTitle       = 'Usman Farooq | Computer Systems Engineering Student';
$pageDescription = "Portfolio of Usman Farooq: projects, technical skills and experience in Computer Systems Engineering at MUET.";
$active          = 'home';
require_once __DIR__ . '/includes/header.php';

// Latest 3 projects, read from the database
$latest = $conn->query('SELECT id, title, description, technologies FROM projects ORDER BY created_at DESC, id DESC LIMIT 3');
$edu    = $conn->query('SELECT institute, degree, details, start_year, end_year FROM education ORDER BY start_year DESC LIMIT 1')->fetch_assoc();
?>

<!-- Hero -->
<section class="hero py-5" aria-labelledby="hero-title">
    <div class="container">
        <div class="row align-items-center g-5 flex-column-reverse flex-md-row">
            <div class="col-md-7 text-center text-md-start">
                <p class="eyebrow">Welcome to my portfolio</p>
                <h1 id="hero-title" class="display-5 fw-bold">Hi, I'm <span class="accent">Usman Farooq</span></h1>
                <h2 class="h4 mb-3">Computer Systems Engineering Student</h2>
                <p class="lead muted mb-4">
                    I am passionate about Artificial Intelligence, Networking and Software Development.
                    I love learning new technologies and building useful projects.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <a href="projects.php" class="btn btn-accent">View My Projects</a>
                    <a href="contact.php" class="btn btn-outline-accent">Contact Me</a>
                    <a href="assets/Usman-Farooq-CV.pdf" class="btn btn-accent" download>Download CV</a>
                </div>
            </div>
            <div class="col-md-5 text-center">
                <img src="assets/img/usman.jpg" alt="Portrait of Usman Farooq" class="profile-img img-fluid" width="280" height="330">
            </div>
        </div>
    </div>
</section>

<!-- About -->
<section class="py-5 section-alt" aria-labelledby="about-title">
    <div class="container">
        <h2 id="about-title" class="section-title">About Me</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-custom h-100 p-4">
                    <h3 class="h5 accent">Who Am I?</h3>
                    <p class="mb-0">
                        I am Usman Farooq, a Computer Systems Engineering student at Mehran University of
                        Engineering and Technology (MUET), Jamshoro. I belong to the 24CS batch and I am from
                        Sukkur, Sindh. I am continuously learning new technologies and improving my technical skills.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom h-100 p-4">
                    <h3 class="h5 accent">Education</h3>
                    <?php if ($edu): ?>
                        <h4 class="h6"><?= e($edu['institute']) ?></h4>
                        <p class="mb-1"><?= e($edu['degree']) ?></p>
                        <p class="mb-1"><?= e($edu['details']) ?></p>
                        <p class="mb-0 muted"><?= e($edu['start_year']) ?> - <?= e($edu['end_year']) ?></p>
                    <?php else: ?>
                        <p class="mb-0 muted">Education details will appear here.</p>
                    <?php endif; ?>
                    <a href="skills.php" class="d-inline-block mt-3 link-accent">See skills, education and certificates &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest projects (dynamic) -->
<section class="py-5" aria-labelledby="latest-title">
    <div class="container">
        <h2 id="latest-title" class="section-title">Latest Projects</h2>
        <div class="row g-4">
            <?php while ($p = $latest->fetch_assoc()): ?>
                <div class="col-md-4">
                    <article class="card-custom h-100 p-4 d-flex flex-column">
                        <h3 class="h5 accent"><?= e($p['title']) ?></h3>
                        <p class="flex-grow-1"><?= e($p['description']) ?></p>
                        <p class="mb-0">
                            <?php foreach (split_tags($p['technologies']) as $tag): ?>
                                <span class="badge tag"><?= e($tag) ?></span>
                            <?php endforeach; ?>
                        </p>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>
        <p class="text-center mt-4"><a href="projects.php" class="btn btn-outline-accent">See all projects</a></p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
