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
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>Index of <?= $pwd ?></title>
    <meta http-equiv="Content-Security-Policy" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <style>
        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        a {
            outline: none;
            text-decoration: none;
        }

        article {
            width: 100%;
            padding: 16px 0;
            border-top: 1px #999 solid;
            border-bottom: 1px #999 solid;
            overflow-x: auto;
        }

        table {
            min-width: 640px;
            line-height: 2;
        }

        table td:not(:last-child) {
            padding-right: 50px;
        }

        footer {
            padding: 16px 0;
            font-size: 60%;
        }

        footer a {
            font-style: oblique;
        }
    </style>
</head>

<body>
    <header>
        <h2>Index of <?= $pwd ?></h2>
    </header>
    <article>
        <table>
            <?php
            if ($pwd != '/') {
            ?>
                <tr>
                    <td><a href="?dir=<?= dirname($pwd) ?>">../</a></td>
                    <td>&nbsp;</td>
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
                        <td>&nbsp;</td>
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
                        <td>&nbsp;</td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
    </article>
    <footer>
        Powered by <a href="https://github.com/rehiy/web-indexr" target="_blank">Web Indexr</a>
    </footer>
</body>

</html>