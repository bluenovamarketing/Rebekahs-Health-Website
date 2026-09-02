$ErrorActionPreference = 'Stop'

$packageRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$scriptPath = Join-Path $packageRoot 'narration-script.md'
$audioRoot = Join-Path $packageRoot 'audio'
New-Item -ItemType Directory -Force -Path $audioRoot | Out-Null

$markdown = Get-Content -Raw -LiteralPath $scriptPath
$narrationMatches = [regex]::Matches(
    $markdown,
    '(?ms)^## Scene\s+(\d+)\s+—\s+([^\r\n]+)\r?\n\r?\n(.*?)(?=^## Scene\s+\d+\s+—|\z)'
)

if ($narrationMatches.Count -ne 16) {
    throw "Expected 16 narration scenes, but found $($narrationMatches.Count)."
}

$voiceProbe = New-Object -ComObject SAPI.SpVoice
$voiceTokens = $voiceProbe.GetVoices()
$selectedToken = $null
foreach ($tokenIndex in 0..($voiceTokens.Count - 1)) {
    $candidate = $voiceTokens.Item($tokenIndex)
    if ($candidate.GetDescription() -match 'David Desktop') {
        $selectedToken = $candidate
        break
    }
}
if ($null -eq $selectedToken) {
    $selectedToken = $voiceTokens.Item(0)
}
$voiceName = $selectedToken.GetDescription()

$manifest = @()
foreach ($match in $narrationMatches) {
    $number = [int]$match.Groups[1].Value
    $title = $match.Groups[2].Value.Trim()
    $text = $match.Groups[3].Value.Trim() -replace '\*\*', '' -replace '`', ''
    $fileName = ('scene-{0:D2}.wav' -f $number)
    $filePath = Join-Path $audioRoot $fileName
    if (Test-Path -LiteralPath $filePath) {
        Remove-Item -LiteralPath $filePath -Force
    }

    $speaker = New-Object -ComObject SAPI.SpVoice
    $speaker.Voice = $selectedToken
    $speaker.Rate = 1
    $speaker.Volume = 100
    $stream = New-Object -ComObject SAPI.SpFileStream
    $format = New-Object -ComObject SAPI.SpAudioFormat
    $format.Type = 18
    $stream.Format = $format
    $stream.Open($filePath, 3, $false)
    $speaker.AudioOutputStream = $stream
    $null = $speaker.Speak($text)
    $stream.Close()
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($format) | Out-Null
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($stream) | Out-Null
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($speaker) | Out-Null

    $manifest += [ordered]@{
        scene = $number
        title = $title
        audio = $fileName
        narration = $text
        voice = $voiceName
    }
}

$manifest | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath (Join-Path $packageRoot 'scene-manifest.json') -Encoding utf8
[System.Runtime.InteropServices.Marshal]::ReleaseComObject($voiceProbe) | Out-Null
Write-Output "Created $($manifest.Count) narrated scene files using $voiceName."
