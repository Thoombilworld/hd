<?php
require __DIR__ . '/bootstrap.php';

$settings = hs_settings();
$theme = hs_current_theme();
$palette = hs_theme_palette($theme);

$site_title = $settings['site_title'] ?? 'NEWS HDSPTV';
$host = parse_url(HS_BASE_URL, PHP_URL_HOST) ?: 'example.com';
$contact_email = $settings['contact_email'] ?? ('support@' . $host);
$contact_phone = $settings['contact_phone'] ?? '+91 00000 00000';
$contact_content = $settings['contact_content'] ?? '';
$prefill_note = trim($_GET['note'] ?? '');
$prefill_subject = trim($_GET['subject'] ?? '');
$from_article = trim($_GET['from_article'] ?? '');
$source = trim($_GET['source'] ?? '');
$handoff_article_url = $from_article ? hs_news_url($from_article) : '';
$handoff_body = $prefill_note;
if ($handoff_article_url) {
    $handoff_body .= ($handoff_body ? "\n\n" : '') . 'Article: ' . $handoff_article_url;
}
if ($source) {
    $handoff_body .= "\n" . 'Source: ' . $source;
}
$mail_subject = $prefill_subject ?: 'Article feedback';
$mailto_link = 'mailto:' . $contact_email . '?subject=' . rawurlencode($mail_subject) . '&body=' . rawurlencode($handoff_body ?: '');
$page_title = 'Contact ' . $site_title;
$meta_desc = $settings['seo_meta_description'] ?? ($settings['tagline'] ?? '');
$meta_keys = $settings['seo_meta_keywords'] ?? '';
$canonical = hs_base_url('contact');
$languageCode = hs_current_language_code();
$languageDir = hs_is_rtl($languageCode) ? 'rtl' : 'ltr';
?>
<!doctype html>
<html lang="<?= htmlspecialchars($languageCode) ?>" dir="<?= htmlspecialchars($languageDir) ?>">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($meta_keys) ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= htmlspecialchars($settings['favicon'] ?? hs_base_url('assets/images/favicon.png')) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
  <link rel="stylesheet" href="<?= hs_base_url('assets/css/style.css') ?>">
  <style>
    :root {
      --hs-primary: <?= $palette['primary'] ?>;
      --hs-primary-dark: <?= $palette['primary_dark'] ?>;
      --hs-accent: <?= $palette['accent'] ?>;
      --hs-bg: <?= $palette['bg'] ?>;
      --hs-surface: <?= $palette['surface'] ?>;
      --hs-card: <?= $palette['card'] ?>;
      --hs-text: <?= $palette['text'] ?>;
      --hs-muted: <?= $palette['muted'] ?>;
      --hs-border: <?= $palette['border'] ?>;
    }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: radial-gradient(circle at top, var(--hs-primary) 0, var(--hs-bg) 45%, var(--hs-bg) 100%);
      color: var(--hs-text);
    }

    a { color: var(--hs-accent); text-decoration: none; }
    a:hover { text-decoration: underline; }

    header { position: sticky; top: 0; z-index: 40; backdrop-filter: blur(18px); background: linear-gradient(90deg, rgba(15,23,42,0.92), rgba(15,23,42,0.96)); border-bottom: 1px solid rgba(15,23,42,0.9); padding: 8px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; }
    .top-left { display:flex; align-items:center; gap:10px; }
    .logo-link { display:flex; align-items:center; gap:10px; color:inherit; text-decoration:none; }
    .logo-link:hover { text-decoration:none; color:#FACC15; }
    .logo-mark { width:32px; height:32px; border-radius:14px; background: radial-gradient(circle at 20% 0, #FACC15 0, #1E3A8A 45%, #020617 100%); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px; color:#F9FAFB; box-shadow:0 10px 25px rgba(15,23,42,0.6); }
    .logo-text { display:flex; flex-direction:column; }
    .logo-text-main { font-weight:800; letter-spacing:.18em; font-size:13px; }
    .logo-text-tag { font-size:11px; color:#E5E7EB; opacity:.85; }

    .nav-main { display:flex; align-items:center; gap:12px; font-size:12px; text-transform:uppercase; letter-spacing:.12em; }
    .nav-main a { color:#E5E7EB; padding:4px 6px; border-radius:999px; }
    .nav-main a:hover { background:rgba(15,23,42,0.8); color:#FACC15; text-decoration:none; }

    .nav-search { margin-left:auto; margin-right:12px; margin-top:4px; }
    .nav-search input[type="text"] { padding:4px 10px; border-radius:999px; border:1px solid rgba(148,163,184,0.9); font-size:12px; background:#FFFFFF; color:#111827; min-width:200px; }
    .nav-search input[type="text"]::placeholder { color:#9CA3AF; }
    .nav-search button { display:none; }

    .user-bar { font-size:12px; color:#E5E7EB; padding:6px 0; }
    .user-bar a { color:#FACC15; }

    .page { padding:20px 18px 34px; max-width:1080px; margin:0 auto; }
    .grid { display:grid; grid-template-columns:2fr 1fr; gap:18px; }
    .card { background: var(--hs-card); border:1px solid var(--hs-border); border-radius:16px; padding:22px; box-shadow:0 25px 55px rgba(0,0,0,0.28); }
    .card h1 { margin:0 0 8px; font-size:26px; }
    .card h2 { margin:0 0 8px; font-size:18px; }
    .card p { margin:0 0 10px; color:var(--hs-muted); line-height:1.6; }
    .card ul { margin:0 0 12px; padding-left:18px; color:var(--hs-muted); line-height:1.6; }
    .contact-block { display:flex; align-items:flex-start; gap:12px; padding:10px 0; }
    .contact-icon { width:32px; height:32px; border-radius:10px; background:rgba(250,204,21,0.14); display:flex; align-items:center; justify-content:center; color:#FACC15; font-weight:700; }
    .cta { display:flex; flex-direction:column; }
    .cta strong { color:var(--hs-text); }
    .cta small { color:var(--hs-muted); }

    .aside { background: var(--hs-card); border:1px solid var(--hs-border); border-radius:16px; padding:16px 18px; box-shadow:0 18px 45px rgba(0,0,0,0.24); }
    .aside h3 { margin:0 0 8px; font-size:16px; }
    .aside p { margin:0 0 10px; color:var(--hs-muted); line-height:1.6; }
    .list-plain { list-style:none; padding:0; margin:0; }
    .list-plain li { margin-bottom:8px; color:var(--hs-muted); }

    footer { border-top:1px solid rgba(31,41,55,0.9); padding:10px 18px 16px; font-size:11px; color:#9CA3AF; text-align:center; background:linear-gradient(180deg, rgba(15,23,42,0.98), #020617); }
    .footer-links { margin-bottom:6px; }
    .footer-links a { color:#E5E7EB; }
    .footer-links a:hover { color:#FACC15; text-decoration:none; }

    @media (max-width:840px) {
      .grid { grid-template-columns:1fr; }
    }
    @media (max-width:640px) {
      header { padding:8px 10px; }
      .nav-search { width:100%; margin:6px 0 0; }
      .nav-search input[type="text"] { width:100%; }
      .nav-main { overflow-x:auto; width:100%; padding:6px 0; }
      .card, .aside { padding:16px; }
    }
  </style>
</head>
<body>
  <header>
    <div class="top-left">
      <a href="<?= hs_base_url('index.php') ?>" class="logo-link">
        <div class="logo-mark">H</div>
        <div class="logo-text">
          <div class="logo-text-main">NEWS HDSPTV</div>
          <div class="logo-text-tag"><?= htmlspecialchars($settings['tagline'] ?? 'GCC • INDIA • KERALA • WORLD') ?></div>
        </div>
      </a>
    </div>
    <nav class="nav-main">
      <?php foreach (hs_primary_nav_items() as $item): ?>
        <a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars(hs_t('nav_' . $item['slug'], $item['label'])) ?></a>
      <?php endforeach; ?>
    </nav>
    <form class="nav-search" action="<?= hs_search_url() ?>" method="get">
      <input type="text" name="q" placeholder="<?= htmlspecialchars(hs_t('search_placeholder', 'Search news...')) ?>" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
      <button type="submit"><?= htmlspecialchars(hs_t('search_label', 'Search')) ?></button>
    </form>
    <div class="user-bar">
      <?php $u = hs_current_user(); ?>
      <?php if ($u): ?>
        <?= htmlspecialchars($u['name']) ?>
        <?php if (!empty($u['is_premium'])): ?> · <strong><?= htmlspecialchars(hs_t('nav_premium', 'Premium')) ?></strong><?php endif; ?>
        · <a href="<?= hs_dashboard_url() ?>"><?= htmlspecialchars(hs_t('nav_dashboard', 'Dashboard')) ?></a>
        · <a href="<?= hs_logout_url() ?>"><?= htmlspecialchars(hs_t('nav_logout', 'Logout')) ?></a>
      <?php else: ?>
        <a href="<?= hs_login_url() ?>"><?= htmlspecialchars(hs_t('nav_login', 'Login')) ?></a> ·
        <a href="<?= hs_register_url() ?>"><?= htmlspecialchars(hs_t('nav_register', 'Register')) ?></a>
      <?php endif; ?>
    </div>
  </header>

<main class="page">
  <div class="grid">
    <section class="card">
      <h1>Contact <?= htmlspecialchars($site_title) ?></h1>
      <?php if (!empty($contact_content)): ?>
        <div class="content-section"><?= $contact_content ?></div>
      <?php else: ?>
        <p>We want to hear your feedback, story tips, and partnership ideas. Choose the best channel below and our editorial or support team will respond as soon as possible.</p>
      <?php endif; ?>

      <?php if ($handoff_body || $handoff_article_url): ?>
      <div class="contact-block" style="align-items:flex-start; gap:14px; padding:12px; border-radius:14px; background:rgba(12,18,30,0.85); border:1px solid rgba(148,163,184,0.35);">
        <div class="contact-icon">💬</div>
        <div class="cta" style="gap:6px;">
          <strong>Message from article</strong>
          <?php if ($handoff_article_url): ?>
            <small><a href="<?= htmlspecialchars($handoff_article_url) ?>"><?= htmlspecialchars($handoff_article_url) ?></a></small>
          <?php endif; ?>
          <small>This note travelled with you from the comments drawer. You can edit it or send as-is.</small>
          <form id="handoff-form" method="post" action="#" onsubmit="return false;" style="margin-top:6px;">
            <label class="sr-only" for="handoff_note">Your note</label>
            <textarea id="handoff_note" name="note" rows="5" style="width:100%; border-radius:10px; border:1px solid rgba(148,163,184,0.35); background:rgba(15,23,42,0.65); color:#E5E7EB; padding:10px; box-sizing:border-box; font-size:14px;" aria-describedby="handoff_help"><?= htmlspecialchars($handoff_body ?: '') ?></textarea>
            <div id="handoff_help" style="font-size:12px; color:#94A3B8; margin-top:6px;">We include the article link and your note so the newsroom can reply quickly.</div>
            <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
              <a id="handoff-mail" class="comment-link" href="<?= htmlspecialchars($mailto_link) ?>" style="background:#1D4ED8; color:#FFF; border:1px solid rgba(37,99,235,0.4);">✉ Email this note</a>
              <button type="button" id="handoff-copy" class="comment-link" style="border:1px solid rgba(148,163,184,0.35); background:rgba(148,163,184,0.16); color:#E5E7EB;">📋 Copy to clipboard</button>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <div class="contact-block">
        <div class="contact-icon">@</div>
        <div class="cta">
          <strong>Email</strong>
          <small><a href="mailto:<?= htmlspecialchars($contact_email) ?>"><?= htmlspecialchars($contact_email) ?></a></small>
          <small>General inquiries, news tips, and account support</small>
        </div>
      </div>

      <div class="contact-block">
        <div class="contact-icon">☎</div>
        <div class="cta">
          <strong>Phone</strong>
          <small><a href="tel:<?= htmlspecialchars($contact_phone) ?>"><?= htmlspecialchars($contact_phone) ?></a></small>
          <small>Weekdays 10:00–18:00 local time</small>
        </div>
      </div>

      <div class="contact-block">
        <div class="contact-icon">✉</div>
        <div class="cta">
          <strong>Postal</strong>
          <small><?= htmlspecialchars($site_title) ?> Editorial Desk</small>
          <small><?= htmlspecialchars($settings['office_address'] ?? 'Add your newsroom address in settings.') ?></small>
        </div>
      </div>

      <h2>How can we help?</h2>
      <ul>
        <li>Press and media partnerships</li>
        <li>Corrections or takedown requests</li>
        <li>Advertising and sponsorship opportunities</li>
        <li>Technical support for registered users</li>
      </ul>

      <p>Include as much detail as possible so we can route your request to the right editor or support specialist.</p>
    </section>

    <aside class="aside">
      <h3>Stay connected</h3>
      <p>Follow our channels for breaking news, regional updates, and live video alerts.</p>
      <ul class="list-plain">
        <?php if (!empty($settings['social_facebook'])): ?>
          <li><a href="<?= htmlspecialchars($settings['social_facebook']) ?>" target="_blank">Facebook</a></li>
        <?php endif; ?>
        <?php if (!empty($settings['social_youtube'])): ?>
          <li><a href="<?= htmlspecialchars($settings['social_youtube']) ?>" target="_blank">YouTube</a></li>
        <?php endif; ?>
        <?php if (!empty($settings['social_instagram'])): ?>
          <li><a href="<?= htmlspecialchars($settings['social_instagram']) ?>" target="_blank">Instagram</a></li>
        <?php endif; ?>
        <?php if (!empty($settings['social_x'])): ?>
          <li><a href="<?= htmlspecialchars($settings['social_x']) ?>" target="_blank">X</a></li>
        <?php endif; ?>
        <?php if (!empty($settings['social_tiktok'])): ?>
          <li><a href="<?= htmlspecialchars($settings['social_tiktok']) ?>" target="_blank">TikTok</a></li>
        <?php endif; ?>
        <?php if (!empty($settings['social_telegram'])): ?>
          <li><a href="<?= htmlspecialchars($settings['social_telegram']) ?>" target="_blank">Telegram</a></li>
        <?php endif; ?>
      </ul>

      <h3>Response times</h3>
      <p>We do our best to acknowledge messages within one business day. Urgent editorial matters should be clearly marked in the subject line.</p>

      <h3>Looking for help logging in?</h3>
      <p>Visit the <a href="<?= hs_forgot_password_url() ?>">password reset</a> page or head to your <a href="<?= hs_dashboard_url() ?>">dashboard</a> for account updates.</p>
    </aside>
  </div>
</main>

<footer>
  <div class="footer-links"><?= hs_footer_links_html(); ?></div>
  <div class="footer-copy">© <?= date('Y') ?> <?= htmlspecialchars($site_title) ?>. <?= htmlspecialchars(hs_t('footer_rights', 'All rights reserved.')) ?></div>
</footer>
<script>
  const noteField = document.getElementById('handoff_note');
  const mailLink = document.getElementById('handoff-mail');
  const copyBtn = document.getElementById('handoff-copy');
  const mailtoBase = 'mailto:<?= htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8') ?>?subject=<?= rawurlencode($mail_subject) ?>&body=';
  if (noteField && mailLink) {
    const updateMailto = () => {
      const val = (noteField.value || '').trim();
      mailLink.href = mailtoBase + encodeURIComponent(val);
    };
    noteField.addEventListener('input', updateMailto);
    updateMailto();
  }
  if (copyBtn && noteField && navigator.clipboard) {
    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(noteField.value || '');
      copyBtn.textContent = '✓ Copied';
      setTimeout(() => { copyBtn.textContent = '📋 Copy to clipboard'; }, 1600);
    });
  }
</script>
</body>
</html>
