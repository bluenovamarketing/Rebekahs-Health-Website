param(
    [string]$ProjectRoot = (Split-Path -Parent $PSScriptRoot)
)

$ErrorActionPreference = 'Stop'
$themeRoot = Join-Path $ProjectRoot 'wordpress\theme\rebekahs-2026'
$pagePartRoot = Join-Path $themeRoot 'template-parts\pages'
$pageCssRoot = Join-Path $themeRoot 'assets\css\pages'
$pageJsRoot = Join-Path $themeRoot 'assets\js\pages'

@($themeRoot, $pagePartRoot, $pageCssRoot, $pageJsRoot) | ForEach-Object {
    New-Item -ItemType Directory -Path $_ -Force | Out-Null
}

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
function Write-Utf8NoBom {
    param([string]$Path, [string]$Value)
    [System.IO.File]::WriteAllText($Path, $Value, $utf8NoBom)
}

function Get-HtmlRegion {
    param([string]$Html, [string]$Tag)
    $match = [regex]::Match($Html, "<$Tag\b[^>]*>[\s\S]*?</$Tag>", 'IgnoreCase')
    if (-not $match.Success) { throw "No <$Tag> region found." }
    return $match.Value
}

function Convert-LocalMarkupPaths {
    param([string]$Markup)
    $result = [regex]::Replace($Markup, '(?<attr>src|poster)="(?<path>(?!https?:|//|data:|#)[^"]+)"', {
        param($m)
        $path = $m.Groups['path'].Value.TrimStart('./').Replace('\', '/')
        return $m.Groups['attr'].Value + '="<?php echo esc_url( rhn_theme_asset( ''' + $path.Replace("'", "\'") + ''' ) ); ?>"'
    }, 'IgnoreCase')

    $linkMap = @{
        'third-mockup-v3.1.23.html' = '/'
        'story-mockup-v2.2.0.html' = '/our-story/'
        'locations-mockup-v2.9.0.html' = '/locations/'
        'lapeer-location-mockup-v3.1.9.html' = '/locations/lapeer/'
        'grand-blanc-location-mockup-v3.1.10.html' = '/locations/grand-blanc/'
        'clarkston-location-mockup-v3.1.17.html' = '/locations/clarkston/'
        'lake-orion-location-mockup-v3.1.12.html' = '/locations/lake-orion/'
        'classes-events-mockup-v3.1.7.html' = '/events/'
        'in-store-products-mockup-v3.1.8.html' = '/in-store-products/'
        'practitioners-mockup-v2.7.0.html' = '/practitioners/'
        'contact-us-mockup-v1.0.0.html' = '/contact-us/'
        'third-mockup.html' = '/'
        'locations-mockup-v1.0.html' = '/locations/'
        'in-store-products-mockup-v1.0.0.html' = '/in-store-products/'
        'classes-events-mockup-v1.0.0.html' = '/events/'
        'privacy-policy-mockup-v1.0.0.html' = '/privacy-policy/'
        'refund-returns-policy-mockup-v1.0.0.html' = '/refund_returns/'
        'terms-conditions-mockup-v1.0.0.html' = '/terms-conditions/'
        'disclaimer-mockup-v1.0.0.html' = '/disclaimer/'
    }
    foreach ($key in $linkMap.Keys) {
        $path = $linkMap[$key]
        $result = $result.Replace('href="' + $key + '"', 'href="<?php echo esc_url( home_url( ''' + $path + ''' ) ); ?>"')
    }
    $result = [regex]::Replace($result, 'href="https?://(?:www\.)?rebekahspureliving\.com(?<path>/[^"#?]*)?(?<tail>[#?][^"]*)?"', {
        param($m)
        $path = $m.Groups['path'].Value
        if ([string]::IsNullOrWhiteSpace($path)) { $path = '/' }
        $tail = $m.Groups['tail'].Value
        return 'href="<?php echo esc_url( home_url( ''' + $path.Replace("'", "\'") + ''' ) ); ?>' + $tail + '"'
    }, 'IgnoreCase')
    return $result
}

function Convert-CssPaths {
    param([string]$Css)
    return [regex]::Replace($Css, 'url\((?<q>["'']?)(?<path>(?:output|tmp)/[^)"'']+)(?:["'']?)\)', {
        param($m)
        $path = $m.Groups['path'].Value.Replace('\', '/')
        return 'url("../../' + $path + '")'
    }, 'IgnoreCase')
}

$pages = @(
    @{ Key='home'; Source='third-mockup-v3.1.23.html' },
    @{ Key='our-story'; Source='story-mockup-v2.2.0.html' },
    @{ Key='locations'; Source='locations-mockup-v2.9.0.html' },
    @{ Key='lapeer'; Source='lapeer-location-mockup-v3.1.9.html' },
    @{ Key='grand-blanc'; Source='grand-blanc-location-mockup-v3.1.10.html' },
    @{ Key='clarkston'; Source='clarkston-location-mockup-v3.1.17.html' },
    @{ Key='lake-orion'; Source='lake-orion-location-mockup-v3.1.12.html' },
    @{ Key='events'; Source='classes-events-mockup-v3.1.7.html' },
    @{ Key='blog'; Source='wellness-source-blog\wellness-source-blog-v1.0.html' },
    @{ Key='single-post'; Source='blog-post-mockup-v1.0.0.html' },
    @{ Key='404'; Source='404-page-mockup-v1.0.0.html' },
    @{ Key='event-detail'; Source='event-detail-mockup-v1.0.0.html' },
    @{ Key='practitioner-profile'; Source='practitioner-profile-mockup-v1.0.0.html' },
    @{ Key='in-store-products'; Source='in-store-products-mockup-v3.1.8.html' },
    @{ Key='practitioners'; Source='practitioners-mockup-v2.7.0.html' },
    @{ Key='contact-us'; Source='contact-us-mockup-v1.0.0.html' },
    @{ Key='privacy-policy'; Source='privacy-policy-mockup-v1.0.0.html' },
    @{ Key='refund_returns'; Source='refund-returns-policy-mockup-v1.0.0.html' },
    @{ Key='terms-conditions'; Source='terms-conditions-mockup-v1.0.0.html' },
    @{ Key='disclaimer'; Source='disclaimer-mockup-v1.0.0.html' },
    @{ Key='shop-fullscript'; Source='shop-fullscript-mockup-v1.0.0.html' },
    @{ Key='shop-designs-for-health'; Source='shop-designs-for-health-mockup-v1.0.0.html' },
    @{ Key='shop-lifewave'; Source='shop-lifewave-mockup-v1.0.0.html' },
    @{ Key='peptides-injectables'; Source='peptides-injectables-mockup-v1.0.0.html' },
    @{ Key='our-team'; Source='our-team-mockup-v1.0.0.html' }
)

$assetPaths = New-Object 'System.Collections.Generic.HashSet[string]' ([System.StringComparer]::OrdinalIgnoreCase)

foreach ($page in $pages) {
    $sourcePath = Join-Path $ProjectRoot $page.Source
    $html = Get-Content -Raw -Encoding UTF8 $sourcePath
    # Hero video files are hosted in WordPress uploads; do not copy local video binaries into the theme.
    foreach ($assetMatch in [regex]::Matches($html, '(?:src|poster)="(?<path>(?:output|tmp)/[^"?]+)', 'IgnoreCase')) {
        [void]$assetPaths.Add($assetMatch.Groups['path'].Value.Replace('/', '\'))
    }
    $main = Convert-LocalMarkupPaths (Get-HtmlRegion -Html $html -Tag 'main')
    if ($page.Key -eq 'home') {
        $socialPattern = '<section class="social-section" id="instagram"[\s\S]*?</section>\s*<section class="social-section" id="tiktok"[\s\S]*?</section>'
        $socialRegex = [regex]::new($socialPattern, [System.Text.RegularExpressions.RegexOptions]::IgnoreCase)
        if (-not $socialRegex.IsMatch($main)) {
            throw 'Homepage social sections were not found for live-feed component replacement.'
        }
        $main = $socialRegex.Replace($main, "<?php get_template_part( 'template-parts/components/home-social-feeds' ); ?>", 1)
    }
    $main = "<?php`n/** Generated from approved mockup: $($page.Source). */`n?>`n" + $main + "`n"
    Write-Utf8NoBom -Path (Join-Path $pagePartRoot ($page.Key + '.php')) -Value $main

    $css = New-Object System.Collections.Generic.List[string]
    foreach ($styleMatch in [regex]::Matches($html, '<style[^>]*>(?<css>[\s\S]*?)</style>', 'IgnoreCase')) {
        $css.Add($styleMatch.Groups['css'].Value)
    }
    foreach ($linkMatch in [regex]::Matches($html, '<link[^>]+href="(?<href>[^"]+\.css)(?:\?[^"]*)?"[^>]*>', 'IgnoreCase')) {
        $href = $linkMatch.Groups['href'].Value
        if ($href -match '^https?://') { continue }
        $linkedPath = Join-Path (Split-Path -Parent $sourcePath) $href
        if (Test-Path $linkedPath) {
            $linkedCss = Get-Content -Raw -Encoding UTF8 $linkedPath
            foreach ($assetMatch in [regex]::Matches($linkedCss, 'url\((?:["'']?)(?<path>(?:output|tmp)/[^)"'']+)', 'IgnoreCase')) {
                [void]$assetPaths.Add($assetMatch.Groups['path'].Value.Replace('/', '\'))
            }
            $css.Add("/* Source: $href */")
            $css.Add($linkedCss)
        }
    }
    Write-Utf8NoBom -Path (Join-Path $pageCssRoot ($page.Key + '.css')) -Value (Convert-CssPaths ($css -join "`n"))

    $js = New-Object System.Collections.Generic.List[string]
    foreach ($scriptMatch in [regex]::Matches($html, '<script(?<attrs>[^>]*)>(?<code>[\s\S]*?)</script>', 'IgnoreCase')) {
        $attrs = $scriptMatch.Groups['attrs'].Value
        if ($attrs -match 'application/ld\+json') { continue }
        $srcMatch = [regex]::Match($attrs, 'src="(?<src>[^"]+)"', 'IgnoreCase')
        if ($srcMatch.Success) {
            $src = $srcMatch.Groups['src'].Value
            if ($src -notmatch '^https?://' ) {
                # Query strings and fragments are valid in HTML asset URLs, but are
                # not part of the local file name used by the theme compiler.
                $srcPath = ($src -split '[?#]', 2)[0]
                $linkedScript = Join-Path (Split-Path -Parent $sourcePath) $srcPath
                if (Test-Path $linkedScript) {
                    $js.Add("/* Source: $src */")
                    $js.Add((Get-Content -Raw -Encoding UTF8 $linkedScript))
                }
            }
        } elseif (-not [string]::IsNullOrWhiteSpace($scriptMatch.Groups['code'].Value)) {
            $js.Add($scriptMatch.Groups['code'].Value)
        }
    }
    Write-Utf8NoBom -Path (Join-Path $pageJsRoot ($page.Key + '.js')) -Value ($js -join "`n")
}

$chromeHtml = Get-Content -Raw -Encoding UTF8 (Join-Path $ProjectRoot 'header-footer-mockup-v1.11.html')
$chromeCss = ([regex]::Matches($chromeHtml, '<style[^>]*>(?<css>[\s\S]*?)</style>', 'IgnoreCase') | ForEach-Object { $_.Groups['css'].Value }) -join "`n"
Write-Utf8NoBom -Path (Join-Path $themeRoot 'assets\css\chrome.css') -Value (Convert-CssPaths $chromeCss)

$outputSource = Join-Path $ProjectRoot 'output'
$outputTarget = Join-Path $themeRoot 'assets\output'
if (Test-Path $outputTarget) {
    $resolvedTheme = [System.IO.Path]::GetFullPath($themeRoot)
    $resolvedTarget = [System.IO.Path]::GetFullPath($outputTarget)
    if (-not $resolvedTarget.StartsWith($resolvedTheme, [System.StringComparison]::OrdinalIgnoreCase)) {
        throw "Refusing to clean asset target outside the generated theme: $resolvedTarget"
    }
    Remove-Item -LiteralPath $resolvedTarget -Recurse -Force
}
foreach ($relativePath in $assetPaths) {
    $sourceAsset = Join-Path $ProjectRoot $relativePath
    if (-not (Test-Path $sourceAsset -PathType Leaf)) { continue }
    $targetAsset = Join-Path (Join-Path $themeRoot 'assets') $relativePath
    New-Item -ItemType Directory -Path (Split-Path -Parent $targetAsset) -Force | Out-Null
    Copy-Item -LiteralPath $sourceAsset -Destination $targetAsset -Force
}

$logoSource = Join-Path $ProjectRoot 'tmp\source\current-site-logo-live.png'
$logoTargetDir = Join-Path $themeRoot 'assets\tmp\source'
New-Item -ItemType Directory -Path $logoTargetDir -Force | Out-Null
if (Test-Path $logoSource) { Copy-Item -Path $logoSource -Destination (Join-Path $logoTargetDir 'current-site-logo-live.png') -Force }

Write-Output "Generated $($pages.Count) approved page templates in $themeRoot"
