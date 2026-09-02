param(
    [int]$Port = 8765
)

$ErrorActionPreference = 'Stop'
$projectRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$defaultPage = 'phase-two-ecommerce-mockup-sheet.html'
$utf8NoBom = [System.Text.UTF8Encoding]::new($false)

function Write-HttpResponse {
    param(
        [System.Net.Sockets.NetworkStream]$Stream,
        [int]$StatusCode,
        [string]$StatusText,
        [string]$ContentType,
        [byte[]]$Body,
        [bool]$HeadOnly = $false
    )

    $headers = @(
        "HTTP/1.1 $StatusCode $StatusText"
        "Content-Type: $ContentType"
        "Content-Length: $($Body.Length)"
        'Cache-Control: no-store, no-cache, must-revalidate'
        'Pragma: no-cache'
        'Connection: close'
        ''
        ''
    ) -join "`r`n"

    $headerBytes = $utf8NoBom.GetBytes($headers)
    $Stream.Write($headerBytes, 0, $headerBytes.Length)
    if (-not $HeadOnly -and $Body.Length -gt 0) {
        $Stream.Write($Body, 0, $Body.Length)
    }
    $Stream.Flush()
}

function Get-ContentType {
    param([string]$Extension)

    switch ($Extension.ToLowerInvariant()) {
        '.html' { 'text/html; charset=utf-8' }
        '.htm'  { 'text/html; charset=utf-8' }
        '.css'  { 'text/css; charset=utf-8' }
        '.js'   { 'application/javascript; charset=utf-8' }
        '.json' { 'application/json; charset=utf-8' }
        '.svg'  { 'image/svg+xml' }
        '.png'  { 'image/png' }
        '.jpg'  { 'image/jpeg' }
        '.jpeg' { 'image/jpeg' }
        '.gif'  { 'image/gif' }
        '.webp' { 'image/webp' }
        '.ico'  { 'image/x-icon' }
        '.woff' { 'font/woff' }
        '.woff2' { 'font/woff2' }
        '.pdf'  { 'application/pdf' }
        '.xlsx' { 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }
        default { 'application/octet-stream' }
    }
}

$listener = [System.Net.Sockets.TcpListener]::new([System.Net.IPAddress]::Loopback, $Port)

try {
    $listener.Start()
} catch [System.Net.Sockets.SocketException] {
    # Another preview server is already using the fixed local URL.
    exit 0
}

try {
    while ($true) {
        $client = $listener.AcceptTcpClient()
        try {
            $client.ReceiveTimeout = 5000
            $client.SendTimeout = 5000
            $stream = $client.GetStream()
            $reader = [System.IO.StreamReader]::new(
                $stream,
                [System.Text.Encoding]::ASCII,
                $false,
                1024,
                $true
            )

            $requestLine = $reader.ReadLine()
            while ($null -ne ($headerLine = $reader.ReadLine()) -and $headerLine -ne '') {
                # Consume request headers.
            }

            if ($requestLine -notmatch '^(GET|HEAD)\s+([^\s]+)\s+HTTP/') {
                $body = $utf8NoBom.GetBytes('Bad request')
                Write-HttpResponse -Stream $stream -StatusCode 400 -StatusText 'Bad Request' -ContentType 'text/plain; charset=utf-8' -Body $body
                continue
            }

            $method = $Matches[1]
            $requestTarget = ($Matches[2] -split '\?', 2)[0]
            $decodedPath = [System.Uri]::UnescapeDataString($requestTarget).TrimStart('/')
            if ([string]::IsNullOrWhiteSpace($decodedPath)) {
                $decodedPath = $defaultPage
            }

            $relativePath = $decodedPath.Replace('/', [System.IO.Path]::DirectorySeparatorChar)
            $requestedFile = [System.IO.Path]::GetFullPath((Join-Path $projectRoot $relativePath))
            $rootPrefix = $projectRoot.TrimEnd([System.IO.Path]::DirectorySeparatorChar) + [System.IO.Path]::DirectorySeparatorChar
            $insideProject = $requestedFile.StartsWith($rootPrefix, [System.StringComparison]::OrdinalIgnoreCase)

            if (-not $insideProject -or -not [System.IO.File]::Exists($requestedFile)) {
                $body = $utf8NoBom.GetBytes('Not found')
                Write-HttpResponse -Stream $stream -StatusCode 404 -StatusText 'Not Found' -ContentType 'text/plain; charset=utf-8' -Body $body -HeadOnly ($method -eq 'HEAD')
                continue
            }

            $fileBytes = [System.IO.File]::ReadAllBytes($requestedFile)
            $contentType = Get-ContentType -Extension ([System.IO.Path]::GetExtension($requestedFile))
            Write-HttpResponse -Stream $stream -StatusCode 200 -StatusText 'OK' -ContentType $contentType -Body $fileBytes -HeadOnly ($method -eq 'HEAD')
        } catch {
            # Close the current connection and keep the local preview server alive.
        } finally {
            if ($reader) { $reader.Dispose() }
            if ($stream) { $stream.Dispose() }
            $client.Dispose()
        }
    }
} finally {
    $listener.Stop()
}
