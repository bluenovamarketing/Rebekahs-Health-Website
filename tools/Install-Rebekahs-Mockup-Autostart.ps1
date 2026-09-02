$ErrorActionPreference = 'Stop'

$projectRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$watchdogScript = Join-Path $PSScriptRoot 'Keep-Rebekahs-Mockup-Server-Running.ps1'
$startupFolder = [Environment]::GetFolderPath('Startup')
$shortcutPath = Join-Path $startupFolder 'Rebekahs Phase Two Mockup Preview.lnk'
$powershellPath = (Get-Command powershell.exe).Source

$shell = New-Object -ComObject WScript.Shell
$shortcut = $shell.CreateShortcut($shortcutPath)
$shortcut.TargetPath = $powershellPath
$shortcut.Arguments = "-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File `"$watchdogScript`""
$shortcut.WorkingDirectory = $projectRoot
$shortcut.Description = "Keep Rebekah's local Phase Two ecommerce mockup review hub available"
$shortcut.Save()

Write-Output $shortcutPath
