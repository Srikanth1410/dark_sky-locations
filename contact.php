
<?php
require_once 'includes/db.php';

$pageTitle = 'Contact Us';

$pdo = getDbConnection();

$errors = [];
$success = false;

$name = '';
$email = '';
$subject = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Server-side validation
    if ($name === '' || strlen($name) > 100) {
        $errors[] = 'Name is required and must be at most 100 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 254) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($subject === '' || strlen($subject) > 150) {
        $errors[] = 'Subject is required and must be at most 150 characters.';
    }

    if ($message === '' || strlen($message) > 5000) {
        $errors[] = 'Message is required and must be at most 5000 characters.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO contact_messages
                    (name, email, subject, message)
                 VALUES (?, ?, ?, ?)'
            );

            $stmt->execute([
                $name,
                $email,
                $subject,
                $message
            ]);

            $success = true;

            // Clear the form after a successful submission.
            $name = '';
            $email = '';
            $subject = '';
            $message = '';

        } catch (PDOException $exception) {
            // Log technical details on the server, not in the browser.
            error_log('Contact form database error: ' . $exception->getMessage());
            $errors[] = 'Unable to submit your enquiry right now. Please try again.';
        }
    }
}

require_once 'includes/header.php';
?>

<main>
    <h2>Contact Us</h2>

    <p>
        Have a question about a dark-sky location or astronomical
        observations? Send us an enquiry.
    </p>


    <?php if (!empty($errors)): ?>
        <div class="site-details" role="alert">
            <h3>Please correct the following errors:</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="contact.php">
        <div class="form-field">
            <label for="name">Full name</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                autocomplete="name"
                value="<?php echo e($name); ?>"
                required
            >
        </div>

        <div class="form-field">
            <label for="email">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="254"
                autocomplete="email"
                value="<?php echo e($email); ?>"
                required
            >
        </div>

        <div class="form-field">
            <label for="subject">Subject</label>
            <input
                type="text"
                id="subject"
                name="subject"
                maxlength="150"
                value="<?php echo e($subject); ?>"
                required
            >
        </div>

        <div class="form-field" style="flex-basis: 100%;">
            <label for="message">Message</label>
            <textarea
                id="message"
                name="message"
                rows="6"
                maxlength="5000"
                required
            ><?php echo e($message); ?></textarea>
        </div>

        <button type="submit">Submit enquiry</button>
    </form>
</main>

<?php if (!empty($success)): ?>
    <div
        id="contact-toast"
        class="toast-notification"
        role="status"
        aria-live="polite"
    >
        <span>Enquiry Successfully Sent</span>
    </div>
<?php endif; ?>

<script src="js/contact.js" defer></script>

<?php require_once 'includes/footer.php'; ?>

<!-- code to disapear the notification -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("contact-toast");

    if (toast) {
        setTimeout(function () {
            toast.classList.add("hide");

            setTimeout(function () {
                toast.remove();
            }, 350);
        }, 4000);
    }
});
</script>
