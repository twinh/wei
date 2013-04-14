<?php if (!isset($message)) { return; } ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?= $message ?></title>
<style type="text/css">
body { font-size: 12px; color: #333; padding: 15px 20px 20px 20px; }
h1, h2, p, pre { margin: 0; padding: 0; }
body, pre { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif, "\5fae\8f6f\96c5\9ed1", "\5b8b\4f53"; }
h1 { font-size: 36px; }
h2 { font-size: 20px; margin: 20px 0 0; }
pre { line-height: 18px; }
strong, .error-text { color: #FF3000; }
</style>
</head>
<body>
<h1><?= $message ?></h1>
<?php if ($debug) : ?>
    <h2>File</h2>
    <p class="error-text"><?= $file ?> modified at <?= $mtime ?></p>
    <p><pre><?= $fileInfo ?></pre></p>

    <h2>Trace</h2>
    <p class="error-text"><?= $detail ?></p>
    <p><pre><?= $trace ?></pre></p>
<?php else : ?>
    <p>Unfortunately, an error occurred. Please try again later.</p>
<?php endif ?>
</body>
</html>