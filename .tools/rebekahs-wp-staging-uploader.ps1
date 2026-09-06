$line = ''
while ($true) {
    $key = [Console]::ReadKey($true)
    if ($key.Key -eq 'Enter') {
        break
    }
    $line += $key.KeyChar
}

$credentials = $line | ConvertFrom-Json
$env:REBEKAHS_WP_USERNAME = $credentials.u
$env:REBEKAHS_WP_LOGIN_PASSWORD = $credentials.p

& 'C:\Users\todda\.cache\codex-runtimes\codex-primary-runtime\dependencies\node\bin\node.exe' --use-system-ca '.tools\rebekahs-wp-staging-uploader.mjs'
