$ErrorActionPreference = 'Stop'

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

if (-not (Test-ReviewHub)) {
    $serverArguments = "-NoProfile -ExecutionPolicy Bypass -File `"$serverScript`""
    Start-Process -FilePath 'powershell.exe' -ArgumentList $serverArguments -WorkingDirectory $projectRoot -WindowStyle Hidden

    $ready = $false
    for ($attempt = 0; $attempt -lt 20; $attempt++) {
        Start-Sleep -Milliseconds 250
        if (Test-ReviewHub) {
            $ready = $true
            break
        }
    }

    if (-not $ready) {
        throw "The local preview server did not start at $reviewUrl"
    }
}

Start-Process $reviewUrl
