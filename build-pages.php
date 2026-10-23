<?php
declare(strict_types=1);

/**
 * GitHub Pages Static Site Compiler for Softrings International (Pvt) Ltd
 *
 * Renders every PHP page through an isolated PHP CLI process and writes a fully
 * static HTML build (default: `_site/`) ready for GitHub Pages.
 *
 * Usage:
 *   php build-pages.php            # builds into ./_site
 *   php build-pages.php out-dir    # builds into a custom directory
 *   php build-pages.php --root     # builds .html files next to the .php sources
 *
 * Notes:
 * - The PHP application remains the source of truth for real PHP hosting.
 * - The static build keeps every visual feature; inquiry/HR forms automatically
 *   fall back to a browser-local ledger plus a pre-filled email hand-off because
 *   GitHub Pages cannot execute PHP or persist to SQLite.
 */

$rootDir = __DIR__;
$args = array_slice($argv, 1);
$buildIntoRoot = false;
$outDir = $rootDir . '/_site';

foreach ($args as $arg) {
    if ($arg === '--root') {
        $buildIntoRoot = true;
    } elseif (trim($arg) !== '' && strpos($arg, '--') !== 0) {
        $outDir = rtrim($arg, '/');
        if (!preg_match('#^(/|[A-Za-z]:)#', $outDir)) {
            $outDir = $rootDir . '/' . $outDir;
        }
    }
}

if ($buildIntoRoot) {
    $outDir = $rootDir;
}

if (!is_dir($outDir) && !mkdir($outDir, 0775, true) && !is_dir($outDir)) {
    fwrite(STDERR, "Unable to create output directory: {$outDir}\n");
    exit(1);
}

$pages = [
    'index.php' => 'index.html',
    'about.php' => 'about.html',
    'services.php' => 'services.html',
    'branches.php' => 'branches.html',
    'careers.php' => 'careers.html',
    'contact.php' => 'contact.html',
];

/**
 * Find a PHP CLI binary that can actually execute a script in a child process.
 */
function resolve_php_binary(): string
{
    $candidates = [];

    $envBinary = getenv('PHP_CLI_BINARY');
    if (is_string($envBinary) && $envBinary !== '') {
        $candidates[] = $envBinary;
    }

    $onPath = trim((string) @shell_exec('command -v php 2>/dev/null'));
    if ($onPath !== '') {
        $candidates[] = $onPath;
    }

    $candidates[] = '/usr/local/bin/php';
    $candidates[] = '/usr/bin/php';
    $candidates[] = PHP_BINARY;

    foreach (array_unique($candidates) as $binary) {
        if ($binary === '' || !@is_file($binary)) {
            continue;
        }
        $probe = @shell_exec(escapeshellarg($binary) . ' -r ' . escapeshellarg('echo "PHP_CLI_OK";') . ' 2>/dev/null');
        if (is_string($probe) && trim($probe) === 'PHP_CLI_OK') {
            return $binary;
        }
    }

    fwrite(STDERR, "No usable PHP CLI binary found. Set PHP_CLI_BINARY.\n");
    exit(1);
}

$phpBinary = resolve_php_binary();
fwrite(STDOUT, "PHP CLI: {$phpBinary}\n");

function render_page(string $phpBinary, string $rootDir, string $page): string
{
    $command = escapeshellarg($phpBinary)
        . ' -d display_errors=stderr -d error_reporting=22527 '
        . escapeshellarg($rootDir . '/scripts/render-page.php')
        . ' ' . escapeshellarg(basename($page))
        . ' 2>&1';

    $output = @shell_exec($command);
    if (!is_string($output) || trim($output) === '') {
        throw new RuntimeException("Empty render for {$page}");
    }
    if (stripos($output, '<!DOCTYPE html') === false || stripos($output, '</html>') === false) {
        throw new RuntimeException("Invalid HTML render for {$page}:\n" . substr($output, 0, 800));
    }
    return $output;
}

/**
 * Rewrite PHP routing links to static .html routes while preserving anchors/query strings.
 */
function rewrite_php_links(string $html): string
{
    $pages = ['index', 'about', 'services', 'branches', 'careers', 'contact'];

    $html = (string) preg_replace_callback(
        '/\b(href|action)="(?:\.\/)?(' . implode('|', $pages) . ')\.php([^"]*)"/i',
        static function (array $m): string {
            $suffix = $m[3];
            $target = $m[2] . '.html' . $suffix;

            // Clean GitHub Pages URLs: index.html -> ./ , about.html -> about
            if ($m[2] === 'index' && ($suffix === '' || $suffix === '#')) {
                return $m[1] . '="./"';
            }
            return $m[1] . '="' . $target . '"';
        },
        $html
    );

    // Canonical / social URLs should not expose .php in the static build
    $html = (string) preg_replace('/(content|href)="([^"]*?)\/(index|about|services|branches|careers|contact)\.php"/i', '$1="$2/$3.html"', $html);

    return $html;
}

$built = [];

foreach ($pages as $source => $target) {
    try {
        $html = render_page($phpBinary, $rootDir, $source);
    } catch (Throwable $e) {
        fwrite(STDERR, 'FAILED: ' . $e->getMessage() . "\n");
        exit(1);
    }

    $html = rewrite_php_links($html);
    $destination = $outDir . '/' . $target;

    if (file_put_contents($destination, $html) === false) {
        fwrite(STDERR, "Unable to write {$destination}\n");
        exit(1);
    }

    $built[$target] = strlen($html);
    fwrite(STDOUT, "Compiled {$source} -> {$target} (" . number_format(strlen($html)) . " bytes)\n");
}

// GitHub Pages: skip Jekyll processing so asset filenames are served verbatim.
file_put_contents($outDir . '/.nojekyll', '');

// Branded 404 handler for GitHub Pages (rendered from the real 404.php page).
if (is_file($rootDir . '/404.php')) {
    try {
        $notFoundHtml = rewrite_php_links(render_page($phpBinary, $rootDir, '404.php'));
        file_put_contents($outDir . '/404.html', $notFoundHtml);
        fwrite(STDOUT, "Generated branded 404.html (" . number_format(strlen($notFoundHtml)) . " bytes)\n");
    } catch (Throwable $e) {
        fwrite(STDERR, 'WARNING: 404 page render failed: ' . $e->getMessage() . "\n");
        copy($outDir . '/index.html', $outDir . '/404.html');
    }
}

// Search engine discovery files (robots.txt + sitemap.xml)
$baseUrl = rtrim((string) (getenv('SITE_BASE_URL') ?: ''), '/');
if ($baseUrl === '') {
    $repoSlug = (string) (getenv('GITHUB_REPOSITORY') ?: 'AMSVA011002/softringsinternational');
    [$repoOwner, $repoName] = array_pad(explode('/', $repoSlug, 2), 2, 'softringsinternational');
    $baseUrl = 'https://' . strtolower($repoOwner) . '.github.io/' . $repoName;
}

$staticRoutes = [
    '' => '1.0',
    'about.html' => '0.9',
    'services.html' => '0.9',
    'branches.html' => '0.8',
    'careers.html' => '0.7',
    'contact.html' => '0.8',
];

$sitemapXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
    . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$today = gmdate('Y-m-d');
foreach ($staticRoutes as $route => $priority) {
    $loc = $route === '' ? $baseUrl . '/' : $baseUrl . '/' . $route;
    $sitemapXml .= "  <url>\n"
        . "    <loc>{$loc}</loc>\n"
        . "    <lastmod>{$today}</lastmod>\n"
        . "    <changefreq>monthly</changefreq>\n"
        . "    <priority>{$priority}</priority>\n"
        . "  </url>\n";
}
$sitemapXml .= '</urlset>' . "\n";

file_put_contents($outDir . '/sitemap.xml', $sitemapXml);

$robotsTxt = "User-agent: *\n"
    . "Allow: /\n\n"
    . "Sitemap: {$baseUrl}/sitemap.xml\n";
file_put_contents($outDir . '/robots.txt', $robotsTxt);

fwrite(STDOUT, "Generated sitemap.xml and robots.txt for {$baseUrl}\n");

// Copy static assets when compiling outside the repository root.
if (realpath($outDir) !== realpath($rootDir)) {
    $copyRecursive = static function (string $srcDir, string $dstDir) use (&$copyRecursive): void {
        if (!is_dir($dstDir) && !mkdir($dstDir, 0775, true) && !is_dir($dstDir)) {
            throw new RuntimeException("Unable to create {$dstDir}");
        }
        foreach (scandir($srcDir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $source = $srcDir . '/' . $entry;
            $target = $dstDir . '/' . $entry;
            if (is_dir($source)) {
                $copyRecursive($source, $target);
            } else {
                copy($source, $target);
            }
        }
    };

    $copyRecursive($rootDir . '/assets', $outDir . '/assets');
    fwrite(STDOUT, "Copied assets/ -> " . str_replace($rootDir . '/', '', $outDir) . "/assets\n");
}

fwrite(STDOUT, "Static build ready in " . str_replace($rootDir . '/', '', $outDir) . "\n");
