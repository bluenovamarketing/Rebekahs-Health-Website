$ErrorActionPreference = 'Continue'

$projectRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$serverScript = Join-Path $PSScriptRoot 'Start-Rebekahs-Mockup-Server.ps1'
$reviewUrl = 'http://127.0.0.1:8765/phase-two-ecommerce-mockup-sheet.html'

function Test-ReviewHub {
    try {
        $response = Invoke-WebRequest -Uri $reviewUrl -UseBasicParsing -TimeoutSec 2
        return $response.StatusCode -eq 200 -and $response.Content -match 'Rebekah.s Phase Two Ecommerce Mockups'
    } catch {
        return $false
    }
}

while ($true) {
    if (-not (Test-ReviewHub)) {
        $serverArguments = "-NoProfile -ExecutionPolicy Bypass -File `"$serverScript`""
        Start-Process -FilePath 'powershell.exe' -ArgumentList $serverArguments -WorkingDirectory $projectRoot -WindowStyle Hidden
        Start-Sleep -Seconds 2
    }

    Start-Sleep -Seconds 15
}
