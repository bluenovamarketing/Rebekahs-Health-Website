$ErrorActionPreference = 'Stop'

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..\..')).Path
$statusRoot = Join-Path $projectRoot 'tmp\training-next-five'
$statusPath = Join-Path $statusRoot 'mark-audio-elevated-status.json'
New-Item -ItemType Directory -Force -Path $statusRoot | Out-Null

$sourceTokenPath = 'SOFTWARE\Microsoft\Speech_OneCore\Voices\Tokens\MSTTS_V110_enUS_MarkM'
$desktopTokenPath = 'SOFTWARE\Microsoft\Speech\Voices\Tokens\CodexTemp_MSTTS_V110_enUS_MarkM_RebekahVideos'
$machine = [Microsoft.Win32.Registry]::LocalMachine
$temporaryVoiceRegistered = $false

function Copy-RegistryKeyValues {
    param([Microsoft.Win32.RegistryKey]$Source,[Microsoft.Win32.RegistryKey]$Destination)
    foreach ($name in $Source.GetValueNames()) {
        $value = $Source.GetValue($name, $null, [Microsoft.Win32.RegistryValueOptions]::DoNotExpandEnvironmentNames)
        $Destination.SetValue($name, $value, $Source.GetValueKind($name))
    }
}

try {
    $existing = $machine.OpenSubKey($desktopTokenPath)
    if ($existing) { $existing.Dispose(); throw 'The temporary Microsoft Mark registration target already exists; it was not changed.' }
    $source = $machine.OpenSubKey($sourceTokenPath)
    if (-not $source) { throw 'The installed Microsoft Mark OneCore voice token could not be found.' }
    $destination = $machine.CreateSubKey($desktopTokenPath)
    Copy-RegistryKeyValues -Source $source -Destination $destination
    $sourceAttributes = $source.OpenSubKey('Attributes')
    $destinationAttributes = $destination.CreateSubKey('Attributes')
    Copy-RegistryKeyValues -Source $sourceAttributes -Destination $destinationAttributes
    $destinationAttributes.Dispose(); $sourceAttributes.Dispose(); $destination.Dispose(); $source.Dispose()
    $temporaryVoiceRegistered = $true

    $builder = Join-Path $PSScriptRoot 'build-mark-audio.ps1'
    $packages = @(
        '03-staff-access\staff-user-video-v1.1',
        '04-cache-and-verification\cache-video-v1.1',
        '05-blog-posts\blog-video-v1.1',
        '06-media-library\media-video-v1.1',
        '07-editing-safety\safety-video-v1.1'
    )
    foreach ($relative in $packages) {
        $package = Join-Path (Join-Path $projectRoot 'client-handoff\website-editing-instructions') $relative
        & $builder -PackageRoot $package -ExpectedScenes 13
    }
    [ordered]@{ success=$true; completed=(Get-Date).ToString('o'); packages=$packages.Count; voice='Microsoft Mark' } | ConvertTo-Json | Set-Content -LiteralPath $statusPath -Encoding utf8
}
catch {
    [ordered]@{ success=$false; completed=(Get-Date).ToString('o'); error=$_.Exception.Message } | ConvertTo-Json | Set-Content -LiteralPath $statusPath -Encoding utf8
    throw
}
finally {
    if ($temporaryVoiceRegistered) { $machine.DeleteSubKeyTree($desktopTokenPath, $false) }
}
