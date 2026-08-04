param(
    [string]$BaseUrl = 'https://wordpress-1651482-6565113.cloudwaysapps.com'
)

$routes = @(
    @{ Key = 'home'; Path = '/' },
    @{ Key = 'our-story'; Path = '/our-story/' },
    @{ Key = 'our-team'; Path = '/our-team/' },
    @{ Key = 'locations'; Path = '/locations/' },
    @{ Key = 'lapeer'; Path = '/locations/lapeer/' },
    @{ Key = 'grand-blanc'; Path = '/locations/grand-blanc/' },
    @{ Key = 'clarkston'; Path = '/locations/clarkston/' },
    @{ Key = 'lake-orion'; Path = '/locations/lake-orion/' },
    @{ Key = 'events'; Path = '/events/' },
    @{ Key = 'blog'; Path = '/blog/' },
    @{ Key = 'in-store-products'; Path = '/in-store-products/' },
    @{ Key = 'practitioners'; Path = '/practitioners/' },
    @{ Key = 'contact-us'; Path = '/contact-us/' },
    @{ Key = 'privacy-policy'; Path = '/privacy-policy/' },
    @{ Key = 'refund_returns'; Path = '/refund_returns/' },
    @{ Key = 'terms-conditions'; Path = '/terms-conditions/' },
    @{ Key = 'disclaimer'; Path = '/disclaimer/' },
    @{ Key = 'shop-fullscript'; Path = '/shop-fullscript/' },
    @{ Key = 'shop-designs-for-health'; Path = '/shop-designs-for-health/' },
    @{ Key = 'shop-lifewave'; Path = '/shop-lifewave/' },
    @{ Key = 'peptides-injectables'; Path = '/peptides-injectables/' }
)

$cacheBuster = [DateTimeOffset]::UtcNow.ToUnixTimeSeconds()
$results = foreach ($route in $routes) {
    $url = $BaseUrl.TrimEnd('/') + $route.Path + '?sitewide_qa=' + $cacheBuster
    $response = curl.exe --ssl-no-revoke --max-time 30 -L -s -w "`n__STATUS__:%{http_code}" $url
    $joined = $response -join "`n"
    $marker = $joined.LastIndexOf("`n__STATUS__:")
    if ($marker -ge 0) {
        $html = $joined.Substring(0, $marker)
        $status = [int]$joined.Substring($marker + 12).Trim()
    } else {
        $html = $joined
        $status = 0
    }

    $titleMatch = [regex]::Match($html, '<title[^>]*>(?<text>[\s\S]*?)</title>', 'IgnoreCase')
    $h1Matches = [regex]::Matches($html, '<h1(?:\s[^>]*)?>(?<text>[\s\S]*?)</h1>', 'IgnoreCase')
    $h1Text = if ($h1Matches.Count) {
        [System.Net.WebUtility]::HtmlDecode(([regex]::Replace($h1Matches[0].Groups['text'].Value, '<[^>]+>', ' ') -replace '\s+', ' ').Trim())
    } else { '' }

    [pscustomobject]@{
        key = $route.Key
        path = $route.Path
        status = $status
        title = if ($titleMatch.Success) { [System.Net.WebUtility]::HtmlDecode(($titleMatch.Groups['text'].Value -replace '\s+', ' ').Trim()) } else { '' }
        h1Count = $h1Matches.Count
        h1 = $h1Text
        templateClass = $html -match ('rhn-template-' + [regex]::Escape($route.Key))
        criticalError = $html -match 'Fatal error|There has been a critical error'
        deadHashLinks = [regex]::Matches($html, 'href=["'']#["'']', 'IgnoreCase').Count
        mockupLinks = [regex]::Matches($html, 'href=["''][^"'']+\.html(?:[?#][^"'']*)?["'']', 'IgnoreCase').Count
        chatWidget = $html -match 'web-chat-widget-script|webchat-client'
        noindex = $html -match '<meta\s+name=["'']robots["''][^>]+noindex'
    }
}

$results | ConvertTo-Json -Depth 4

$failures = @($results | Where-Object {
    $_.status -ne 200 -or
    [string]::IsNullOrWhiteSpace($_.title) -or
    $_.h1Count -ne 1 -or
    -not $_.templateClass -or
    $_.criticalError -or
    $_.deadHashLinks -gt 0 -or
    $_.mockupLinks -gt 0 -or
    $_.chatWidget -or
    -not $_.noindex
})

if ($failures.Count) {
    Write-Error ("Staging QA failed for: " + (($failures | ForEach-Object { $_.key }) -join ', '))
    exit 1
}
