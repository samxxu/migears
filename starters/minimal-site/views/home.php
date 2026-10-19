<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->e($title) ?></title>
    <style>
        :root { color-scheme: light dark; }
        body { margin: 0; font: 16px/1.6 system-ui, -apple-system, "Segoe UI", sans-serif; }
        main { max-width: 720px; margin: 0 auto; padding: 56px 24px; }
        h1 { font-size: clamp(2rem, 5vw, 3rem); line-height: 1.1; letter-spacing: -0.03em; }
        p { color: #555; }
        ul { padding-left: 20px; }
        li { margin: 6px 0; }
        code { font-family: ui-monospace, "SF Mono", Menlo, monospace; }
        a { color: #067A46; }
    </style>
</head>
<body>
<main>
    <h1><?= $this->e($title) ?></h1>
    <p>This page was routed by <code>migears/web</code> and rendered by <code>migears/template</code>.</p>
    <ul>
        <?php foreach ($checks as $check): ?>
            <li><?= $this->e($check) ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Try <a href="/health"><code>/health</code></a> for JSON.
        Edit <code>resources/index.php</code>, <code>views/home.php</code>, or add a new file under <code>resources/</code>.
    </p>
</main>
</body>
</html>
