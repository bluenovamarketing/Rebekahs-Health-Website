$ErrorActionPreference = 'Stop'

Add-Type -AssemblyName System.Speech

$temporaryVoiceRegistered = $false
$sourceTokenPath = 'SOFTWARE\Microsoft\Speech_OneCore\Voices\Tokens\MSTTS_V110_enUS_MarkM'
$desktopTokenPath = 'SOFTWARE\Microsoft\Speech\Voices\Tokens\CodexTemp_MSTTS_V110_enUS_MarkM_RebekahVideos'

function Copy-RegistryKeyValues {
    param(
        [Microsoft.Win32.RegistryKey]$Source,
        [Microsoft.Win32.RegistryKey]$Destination
    )
    foreach ($name in $Source.GetValueNames()) {
        $value = $Source.GetValue($name, $null, [Microsoft.Win32.RegistryValueOptions]::DoNotExpandEnvironmentNames)
        $kind = $Source.GetValueKind($name)
        $Destination.SetValue($name, $value, $kind)
    }
}

$currentUser = [Microsoft.Win32.Registry]::LocalMachine
$existingDesktopToken = $currentUser.OpenSubKey($desktopTokenPath)
if ($existingDesktopToken) {
    $existingDesktopToken.Dispose()
    throw 'The temporary Microsoft Mark registration target already exists; it was not changed.'
}
else {
    $localMachine = [Microsoft.Win32.Registry]::LocalMachine
    $sourceToken = $localMachine.OpenSubKey($sourceTokenPath)
    if (-not $sourceToken) { throw 'The installed Microsoft Mark OneCore voice token could not be found.' }
    $destinationToken = $currentUser.CreateSubKey($desktopTokenPath)
    Copy-RegistryKeyValues -Source $sourceToken -Destination $destinationToken
    $sourceAttributes = $sourceToken.OpenSubKey('Attributes')
    $destinationAttributes = $destinationToken.CreateSubKey('Attributes')
    Copy-RegistryKeyValues -Source $sourceAttributes -Destination $destinationAttributes
    $destinationAttributes.Dispose()
    $sourceAttributes.Dispose()
    $destinationToken.Dispose()
    $sourceToken.Dispose()
    $temporaryVoiceRegistered = $true
}

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

$speaker = $null
$manifest = @()
try {
    $speaker = [System.Speech.Synthesis.SpeechSynthesizer]::new()
    $voiceName = 'Microsoft Mark'
    $installedVoiceNames = @($speaker.GetInstalledVoices() | ForEach-Object { $_.VoiceInfo.Name })
    if ($voiceName -notin $installedVoiceNames) {
        throw "Microsoft Mark is not installed in this speech runtime. Installed voices: $($installedVoiceNames -join ', ')"
    }
    $speaker.SelectVoice($voiceName)
    $speaker.Rate = 0
    $speaker.Volume = 100

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
    if ($speaker) { $speaker.Dispose() }
    if ($temporaryVoiceRegistered) {
        $currentUser.DeleteSubKeyTree($desktopTokenPath, $false)
    }
}

$manifest | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath (Join-Path $packageRoot 'scene-manifest.json') -Encoding utf8
Write-Output "Created $($manifest.Count) complete narrated scene files using Microsoft Mark."
