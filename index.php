<?php
// Basic PHP form handling at the top of the file
// Sanitization and simple validation for POST submission
$success = false;
$errors = [];
$old = ['name' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim and sanitize inputs
    $old['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
    $old['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
    $old['message'] = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($old['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    }

    if ($old['email'] === '') {
        $errors['email'] = 'Please enter your email.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($old['message'] === '') {
        $errors['message'] = 'Please enter a message.';
    }

    
    if (empty($errors)) {
        // In a real app, you might send an email or store this in a database.
        // For this demo, we simply flag success and clear fields.
        $success = true;
        $old = ['name' => '', 'email' => '', 'message' => ''];
    }
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Simple One-Page PHP Site</title>
  <style>
    :root {
      --bg: #0f172a; /* slate-900 */
      --bg-soft: #111827; /* gray-900 */
      --text: #e5e7eb; /* gray-200 */
      --muted: #94a3b8; /* slate-400 */
      --accent: #22d3ee; /* cyan-400 */
      --accent-strong: #06b6d4; /* cyan-500 */
      --card: #111827; /* gray-900 */
      --border: #1f2937; /* gray-800 */
      --error: #fca5a5; /* red-300 */
      --success: #86efac; /* green-300 */
    }

    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; height: 100%; }
    body {
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      background: linear-gradient(180deg, #0b1220 0%, #0f172a 100%);
      color: var(--text);
      line-height: 1.6;
    }

    a { color: var(--text); text-decoration: none; }

    .container { width: 100%; max-width: 1100px; padding: 0 1rem; margin: 0 auto; }

    /* Header */
    header {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(17, 24, 39, 0.8);
      backdrop-filter: saturate(180%) blur(8px);
      border-bottom: 1px solid var(--border);
    }
    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.85rem 0;
    }
    .brand {
      font-weight: 700;
      letter-spacing: 0.4px;
    }
    .brand .accent { color: var(--accent); }
    nav ul { list-style: none; display: flex; gap: 1rem; margin: 0; padding: 0; }
    nav a { padding: 0.4rem 0.6rem; border-radius: 8px; color: var(--muted); }
    nav a:hover { color: var(--text); background: #0b1220; }

    /* Hero */
    .hero {
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 2rem;
      align-items: center;
      padding: 3rem 0 2rem;
    }
    .hero h1 {
      font-size: 2.5rem;
      line-height: 1.2;
      margin: 0 0 0.8rem 0;
    }
    .hero p { color: var(--muted); margin: 0 0 1.2rem 0; }
    .cta {
      display: inline-block;
      background: linear-gradient(135deg, var(--accent), var(--accent-strong));
      color: #001017;
      padding: 0.7rem 1rem;
      border-radius: 10px;
      font-weight: 600;
      box-shadow: 0 6px 24px rgba(34, 211, 238, 0.25);
    }

    .card {
      background: linear-gradient(180deg, #0e1629 0%, #0f182b 100%);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.25rem;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
    }

    /* Sections */
    section { padding: 3rem 0; }
    .section-title { font-size: 1.75rem; margin: 0 0 1rem 0; }
    .muted { color: var(--muted); }

    /* Services */
    .services {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }
    .service { border: 1px solid var(--border); border-radius: 12px; padding: 1rem; background: #0c1426; }
    .service h3 { margin-top: 0; }

    /* Contact */
    form { display: grid; gap: 0.8rem; }
    .field { display: grid; gap: 0.4rem; }
    label { font-size: 0.95rem; color: var(--muted); }
    input[type="text"], input[type="email"], textarea {
      width: 100%;
      padding: 0.7rem 0.8rem;
      background: #0b1220;
      color: var(--text);
      border: 1px solid var(--border);
      border-radius: 10px;
      outline: none;
    }
    textarea { min-height: 120px; resize: vertical; }
    .error { color: var(--error); font-size: 0.9rem; }
    .success { color: var(--success); font-weight: 600; margin-bottom: 0.5rem; }

    button[type="submit"] {
      background: linear-gradient(135deg, var(--accent), var(--accent-strong));
      color: #001017;
      border: none;
      padding: 0.7rem 1rem;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      justify-self: start;
    }

    /* Footer */
    footer {
      border-top: 1px solid var(--border);
      padding: 1.5rem 0;
      color: var(--muted);
      text-align: center;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .hero { grid-template-columns: 1fr; }
      .services { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="container nav">
      <div class="brand">Simple<span class="accent">PHP</span>Site</div>
      <nav>
        <ul>
          <li><a href="#hero">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero -->
  <main class="container">
    <section id="hero" class="hero">
      <div>
        <h1>Clean and Responsive One-Page Website</h1>
        <p class="muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo.</p>
        <a class="cta" href="#contact">Get in Touch</a>
      </div>
      <div class="card">
        <h3>Why this template?</h3>
        <p class="muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <ul>
          <li>Modern, clean layout</li>
          <li>Fully responsive</li>
          <li>Pure HTML, CSS, and PHP</li>
        </ul>
      </div>
    </section>

    <!-- About -->
    <section id="about">
      <h2 class="section-title">About</h2>
      <p class="muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.</p>
    </section>

    <!-- Services -->
    <section id="services">
      <h2 class="section-title">Services</h2>
      <div class="services">
        <div class="service">
          <h3>Service One</h3>
          <p class="muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
        </div>
        <div class="service">
          <h3>Service Two</h3>
          <p class="muted">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
        <div class="service">
          <h3>Service Three</h3>
          <p class="muted">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        </div>
      </div>
    </section>

    <!-- Contact -->
    <section id="contact">
      <h2 class="section-title">Contact</h2>

      <?php if ($success): ?>
        <div class="success">Thanks! Your message has been sent successfully.</div>
      <?php endif; ?>

      <form action="#contact" method="post" novalidate>
        <div class="field">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" value="<?php echo e($old['name']); ?>" placeholder="Your name" />
          <?php if (!empty($errors['name'])): ?><div class="error"><?php echo e($errors['name']); ?></div><?php endif; ?>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo e($old['email']); ?>" placeholder="you@example.com" />
          <?php if (!empty($errors['email'])): ?><div class="error"><?php echo e($errors['email']); ?></div><?php endif; ?>
        </div>
        <div class="field">
          <label for="message">Message</label>
          <textarea id="message" name="message" placeholder="How can we help?"><?php echo e($old['message']); ?></textarea>
          <?php if (!empty($errors['message'])): ?><div class="error"><?php echo e($errors['message']); ?></div><?php endif; ?>
        </div>
        <button type="submit">Send Message</button>
      </form>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <div class="container">
      &copy; <?php echo date('Y'); ?> SimplePHPSite. All rights reserved.
    </div>
  </footer>
</body>
</html>
