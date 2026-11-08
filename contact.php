<?php
$pageTitle       = 'Contact | Usman Farooq';
$pageDescription = 'Contact Usman Farooq for collaboration, opportunities or any queries.';
$active          = 'contact';
require_once __DIR__ . '/includes/db.php';

$errors = [];
$old    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) {
        $old[$k] = trim((string) ($_POST[$k] ?? ''));
    }

    if (!csrf_valid()) {
        $errors['form'] = 'Your session expired. Please try again.';
    }
    // Honeypot: real visitors never fill this hidden field, bots usually do.
    if (!empty($_POST['website'])) {
        $errors['form'] = 'Submission rejected.';
    }
    if (mb_strlen($old['name']) < 2 || mb_strlen($old['name']) > 100) {
        $errors['name'] = 'Please enter your name (2-100 characters).';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 150) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (mb_strlen($old['subject']) < 3 || mb_strlen($old['subject']) > 200) {
        $errors['subject'] = 'Subject must be 3-200 characters.';
    }
    if (mb_strlen($old['message']) < 10 || mb_strlen($old['message']) > 2000) {
        $errors['message'] = 'Message must be 10-2000 characters.';
    }

    if (!$errors) {
        $stmt = $conn->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $old['name'], $old['email'], $old['subject'], $old['message']);
        $stmt->execute();
        $stmt->close();
        flash_set('success', 'Thank you! Your message has been sent successfully.');
        header('Location: contact.php');   // Post/Redirect/Get: refresh will not resend the form
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <h1 class="section-title">Contact Me</h1>
    <p class="text-center muted mb-5">Feel free to contact me for collaboration, opportunities or any queries.</p>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-custom p-4">
                <?php flash_show(); ?>
                <?php if ($errors): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= isset($errors['form']) ? e($errors['form']) : 'Please fix the highlighted fields and try again.' ?>
                    </div>
                <?php endif; ?>

                <form id="contactForm" method="post" action="contact.php" novalidate>
                    <?= csrf_field() ?>
                    <div class="d-none" aria-hidden="true">
                        <label for="website">Leave this field empty</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" maxlength="100" required
                               class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>"
                               value="<?= e($old['name']) ?>" autocomplete="name">
                        <div class="invalid-feedback"><?= e($errors['name'] ?? 'Please enter your name (at least 2 characters).') ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" maxlength="150" required
                               class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                               value="<?= e($old['email']) ?>" autocomplete="email">
                        <div class="invalid-feedback"><?= e($errors['email'] ?? 'Please enter a valid email address.') ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" maxlength="200" required
                               class="form-control<?= isset($errors['subject']) ? ' is-invalid' : '' ?>"
                               value="<?= e($old['subject']) ?>">
                        <div class="invalid-feedback"><?= e($errors['subject'] ?? 'Please enter a subject (at least 3 characters).') ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" rows="5" maxlength="2000" required
                                  class="form-control<?= isset($errors['message']) ? ' is-invalid' : '' ?>"><?= e($old['message']) ?></textarea>
                        <div class="invalid-feedback"><?= e($errors['message'] ?? 'Message must be at least 10 characters.') ?></div>
                        <div class="form-text"><span id="msgCount">0</span> / 2000 characters</div>
                    </div>

                    <button type="submit" class="btn btn-accent">Send Message</button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="row g-3">
                <div class="col-12"><div class="card-custom p-4">
                    <h2 class="h6 accent">Email</h2>
                    <p class="mb-1">usmanfarooq0998@gmail.com</p>
                    <a class="link-accent" href="mailto:usmanfarooq0998@gmail.com">Send Email</a>
                </div></div>
                <div class="col-12"><div class="card-custom p-4">
                    <h2 class="h6 accent">Phone</h2>
                    <p class="mb-1">+92 312 3718717</p>
                    <a class="link-accent" href="tel:+923123718717">Call Me</a>
                </div></div>
                <div class="col-6"><div class="card-custom p-4 h-100">
                    <h2 class="h6 accent">LinkedIn</h2>
                    <a class="link-accent" href="https://www.linkedin.com/in/usman-farooq-2aa0b6334" target="_blank" rel="noopener">Visit Profile</a>
                </div></div>
                <div class="col-6"><div class="card-custom p-4 h-100">
                    <h2 class="h6 accent">GitHub</h2>
                    <a class="link-accent" href="https://github.com/usmanfarooq670" target="_blank" rel="noopener">Visit GitHub</a>
                </div></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
