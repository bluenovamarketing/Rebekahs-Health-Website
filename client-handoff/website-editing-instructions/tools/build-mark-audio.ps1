param(
    [Parameter(Mandatory=$true)][string]$PackageRoot,
    [int]$ExpectedScenes = 13
)
$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.Speech

$scriptPath = Join-Path $PackageRoot 'narration-script.md'
$audioRoot = Join-Path $PackageRoot 'audio'
New-Item -ItemType Directory -Force -Path $audioRoot | Out-Null
$markdown = Get-Content -Raw -LiteralPath $scriptPath
$matches = [regex]::Matches($markdown,'(?ms)^## Scene\s+(\d+)\s+—\s+([^\r\n]+)\r?\n\r?\n(.*?)(?=^## Scene\s+\d+\s+—|\z)')
if ($matches.Count -ne $ExpectedScenes) { throw "Expected $ExpectedScenes narration scenes, but found $($matches.Count)." }

$speaker = $null
$manifest = @()
try {
    $speaker = [System.Speech.Synthesis.SpeechSynthesizer]::new()
    $voiceName = 'Microsoft Mark'
    if ($voiceName -notin @($speaker.GetInstalledVoices() | ForEach-Object { $_.VoiceInfo.Name })) { throw 'Microsoft Mark is not available in the speech runtime.' }
    $speaker.SelectVoice($voiceName); $speaker.Rate = 0; $speaker.Volume = 100
    foreach ($match in $matches) {
        $number = [int]$match.Groups[1].Value
        $title = $match.Groups[2].Value.Trim()
        $text = $match.Groups[3].Value.Trim() -replace '\*\*','' -replace '`',''
        $fileName = ('scene-{0:D2}.wav' -f $number)
        $filePath = Join-Path $audioRoot $fileName
        if (Test-Path -LiteralPath $filePath) { Remove-Item -LiteralPath $filePath -Force }
        $speaker.SetOutputToWaveFile($filePath); $speaker.Speak($text); $speaker.SetOutputToNull()
        $manifest += [ordered]@{ scene=$number; title=$title; audio=$fileName; narration=$text; voice=$voiceName; rate=0 }
    }
}
finally {
    if ($speaker) { $speaker.Dispose() }
}
$manifest | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath (Join-Path $PackageRoot 'scene-manifest.json') -Encoding utf8
Write-Output "Created $($manifest.Count) narrated scenes with Microsoft Mark in $PackageRoot"
