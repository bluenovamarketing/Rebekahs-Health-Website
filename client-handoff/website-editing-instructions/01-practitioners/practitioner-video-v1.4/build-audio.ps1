$ErrorActionPreference = 'Stop'

Add-Type -AssemblyName System.Speech

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

$speaker = [System.Speech.Synthesis.SpeechSynthesizer]::new()
$voiceName = 'Microsoft Mark'
$installedVoiceNames = @($speaker.GetInstalledVoices() | ForEach-Object { $_.VoiceInfo.Name })
if ($voiceName -notin $installedVoiceNames) {
    throw "Microsoft Mark is not installed in this speech runtime. Installed voices: $($installedVoiceNames -join ', ')"
}
$speaker.SelectVoice($voiceName)
$speaker.Rate = 0
$speaker.Volume = 100

$manifest = @()
try {
    foreach ($match in $narrationMatches) {
        $number = [int]$match.Groups[1].Value
        $title = $match.Groups[2].Value.Trim()
        $text = $match.Groups[3].Value.Trim() -replace '\*\*', '' -replace '`', ''
        $fileName = ('scene-{0:D2}.wav' -f $number)
        $filePath = Join-Path $audioRoot $fileName
        if (Test-Path -LiteralPath $filePath) {
            Remove-Item -LiteralPath $filePath -Force
        }

        $speaker.SetOutputToWaveFile($filePath)
        $speaker.Speak($text)
        $speaker.SetOutputToNull()

        $manifest += [ordered]@{
            scene = $number
            title = $title
            audio = $fileName
            narration = $text
            voice = $voiceName
            rate = 0
        }
        Write-Output "Created $fileName"
    }
}
finally {
    $speaker.Dispose()
}

$manifest | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath (Join-Path $packageRoot 'scene-manifest.json') -Encoding utf8
Write-Output "Created $($manifest.Count) complete narrated scene files using Microsoft Mark."
