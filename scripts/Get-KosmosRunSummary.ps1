param(
    [Parameter(Mandatory = $true)][string]$Path,
    [string]$ObservedStart,
    [string]$TargetProductId
)
$ErrorActionPreference = 'Stop'
$raw = Get-Content -LiteralPath $Path -Raw
$match = [regex]::Match($raw, '(?m)^\s*\[\s*$')
if (-not $match.Success) { throw 'No JSON report array found.' }
$header = $raw.Substring(0, $match.Index)
$entries = @($raw.Substring($match.Index) | ConvertFrom-Json)
$statusMatch = [regex]::Match($header, '>>> Status: (.*?) <<<')
$status = $statusMatch.Groups[1].Value
$expected = @($entries | Where-Object {
    $_.method -eq 'POST 400' -and (($_.Results -join ' ') -match '^Item already exists\. Preparing to update item$')
})
$errors = @($entries | Where-Object {
    $_.method -match '\s[45]\d\d$' -and -not (
        $_.method -eq 'POST 400' -and (($_.Results -join ' ') -match '^Item already exists\. Preparing to update item$')
    )
})
$writes = @($entries | Where-Object { $_.method -match '^(PUT|POST) 20[01]$' })
# Never print URLs, payloads, credentials, IPs, or raw response bodies.
$target = @($entries | Where-Object {
    $TargetProductId -match '^\d+$' -and $_.url -match ('/products/' + [regex]::Escape($TargetProductId) + '$')
} | ForEach-Object { [pscustomobject]@{ Method = $_.method; Sequence = $_.'Sequence Number' } })
$elapsed = $null
$lastSync = [regex]::Match($header, '(?m)^Last sync ran: (.*)$').Groups[1].Value.Trim()
if ($ObservedStart -and $lastSync) {
    $dateMatch = [regex]::Match($lastSync, '^\w+ (\w+ \d+ \d{4} \d{2}:\d{2}:\d{2}) GMT([+-]\d{4})')
    if ($dateMatch.Success) {
        $zone = $dateMatch.Groups[2].Value.Insert(3, ':')
        $end = [DateTimeOffset]::ParseExact(($dateMatch.Groups[1].Value + ' ' + $zone), 'MMM d yyyy HH:mm:ss zzz', [Globalization.CultureInfo]::InvariantCulture)
        $elapsed = [math]::Round(($end - [DateTimeOffset]::Parse($ObservedStart)).TotalSeconds)
    }
}
[pscustomobject]@{
    Status = $status
    LogEntries = $entries.Count
    SuccessfulWriteRequests = $writes.Count
    DistinctSuccessfulProductIds = @($writes | ForEach-Object {
        if ($_.url -match '/products/(\d+)$') { $Matches[1] }
    } | Sort-Object -Unique).Count
    ExpectedExistingItemResponses = $expected.Count
    UnexpectedHttpErrors = $errors.Count
    ErrorMethods = @($errors | Group-Object method | ForEach-Object { [pscustomobject]@{ Method=$_.Name; Count=$_.Count } })
    TargetWrites = $target
    LastSyncDisplay = $lastSync
    SecondsSinceObservedStart = $elapsed
    TimingIsFinal = ($status -eq 'Done')
    TimingCaveat = 'Observed start is approximate; last-sync while In progress is not a completion time. Verify final log and all selected products before declaring success.'
} | ConvertTo-Json -Depth 5
