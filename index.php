<?php
error_reporting(0);

$pwd = '/';
if (isset($_GET['dir'])) {
    $pwd = '/' . trim($_GET['dir'], './');
    $pwd = preg_replace('#/[./]+/#', '/', $pwd);
}

function dpath($path)
{
    return str_replace(__DIR__, '', $path);
}

function fpath($path)
{
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
}

function format_bytes($size)
{
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2) . $units[$i];
}
?>
<html>

<head>
    <title>Index of <?= $pwd ?></title>
    <style>
        td:not(:last-child) {
            padding-right: 40px;
        }
    </style>
</head>

<body>
    <h2>Index of <?= $pwd ?></h2>
    <hr>
    <table>
        <?php
        if ($pwd != '/') {
        ?>
            <tr>
                <td><a href="?dir=<?= dirname($pwd) ?>">../</a></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php
        }
        $it = new FilesystemIterator(__DIR__ . $pwd);
        foreach ($it as $file) {
            $path = $file->getRealPath();
            $name = $file->getFilename();
            $time = date('Y-m-d H:i', $file->getCTime());
            if ($file->isDir()) {
            ?>
                <tr>
                    <td><a href="?dir=<?= dpath($path) ?>"><?= $name ?>/</a></td>
                    <td><?= $time ?></td>
                    <td>_</td>
                </tr>
            <?php
            } elseif ($file->isFile()) {
                if ($path == __FILE__) {
                    continue;
                }
            ?>
                <tr>
                    <td><a href="<?= fpath($path) ?>"><?= $name ?></a></td>
                    <td><?= $time ?></td>
                    <td><?= format_bytes($file->getSize()) ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </table>
    <hr>
    <small>Powered by <i>Rehiy Indexr</i></small>
</body>

</html>