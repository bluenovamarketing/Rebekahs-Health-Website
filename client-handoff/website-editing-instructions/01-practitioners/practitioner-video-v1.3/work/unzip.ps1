param(
    [Parameter(Position = 0, Mandatory = $true)]
    [string]$Mode,
    [Parameter(Position = 1, Mandatory = $true)]
    [string]$ArchivePath,
    [Parameter(Position = 2)]
    [string]$EntryName
)

Add-Type -AssemblyName System.IO.Compression

$stream = [System.IO.File]::OpenRead($ArchivePath)
try {
    $archive = [System.IO.Compression.ZipArchive]::new(
        $stream,
        [System.IO.Compression.ZipArchiveMode]::Read,
        $false
    )
    try {
        if ($Mode -eq 'Z1') {
            foreach ($entry in $archive.Entries) {
                [Console]::Out.WriteLine($entry.FullName)
            }
            exit 0
        }

        if ($Mode -eq 'p' -and $EntryName) {
            $entry = $archive.GetEntry($EntryName)
            if ($null -eq $entry) {
                [Console]::Error.WriteLine("Archive entry not found: $EntryName")
                exit 2
            }
            $entryStream = $entry.Open()
            try {
                $output = [Console]::OpenStandardOutput()
                $buffer = New-Object byte[] 65536
                while (($count = $entryStream.Read($buffer, 0, $buffer.Length)) -gt 0) {
                    $output.Write($buffer, 0, $count)
                }
                $output.Flush()
            }
            finally {
                $entryStream.Dispose()
            }
            exit 0
        }

        [Console]::Error.WriteLine('Supported modes are -Z1 <archive> and -p <archive> <entry>.')
        exit 2
    }
    finally {
        $archive.Dispose()
    }
}
finally {
    $stream.Dispose()
}
