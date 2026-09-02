param(
    [Parameter(Mandatory=$true)][string]$PackageRoot,
    [Parameter(Mandatory=$true)][string]$SourceDeckName,
    [Parameter(Mandatory=$true)][string]$NarratedDeckName,
    [Parameter(Mandatory=$true)][string]$VideoName,
    [int]$ExpectedScenes = 13
)
$ErrorActionPreference = 'Stop'
$sourceDeck = Join-Path $PackageRoot $SourceDeckName
$narratedDeck = Join-Path $PackageRoot $NarratedDeckName
$videoPath = Join-Path $PackageRoot $VideoName
$manifest = Get-Content -Raw -LiteralPath (Join-Path $PackageRoot 'scene-manifest.json') | ConvertFrom-Json
$timeline = Get-Content -Raw -LiteralPath (Join-Path $PackageRoot 'scene-timeline.json') | ConvertFrom-Json
if ($manifest.Count -ne $ExpectedScenes -or $timeline.Count -ne $ExpectedScenes) { throw "Expected $ExpectedScenes timed scenes." }
if (Test-Path -LiteralPath $narratedDeck) { Remove-Item -LiteralPath $narratedDeck -Force }

$powerPoint = New-Object -ComObject PowerPoint.Application
$powerPoint.Visible = -1
$presentation = $powerPoint.Presentations.Open($sourceDeck,0,0,0)
try {
    if ($presentation.Slides.Count -ne $timeline.Count) { throw 'Slide and timeline counts do not match.' }
    for ($index=1; $index -le $presentation.Slides.Count; $index++) {
        $slide = $presentation.Slides.Item($index)
        $scene = $timeline[$index-1]
        $audioPath = Join-Path (Join-Path $PackageRoot 'audio') $manifest[$index-1].audio
        if (-not (Test-Path -LiteralPath $audioPath)) { throw "Missing scene audio: $audioPath" }
        $slide.SlideShowTransition.AdvanceOnClick=0
        $slide.SlideShowTransition.AdvanceOnTime=-1
        $slide.SlideShowTransition.AdvanceTime=[double]$scene.slide_seconds
        $audio=$slide.Shapes.AddMediaObject2($audioPath,0,-1,-50,-50,1,1)
        $audio.Name=('Narration scene {0:D2} - Microsoft male voice' -f $index)
        $audio.AnimationSettings.PlaySettings.PlayOnEntry=-1
        $audio.AnimationSettings.PlaySettings.HideWhileNotPlaying=-1
        $audio.AnimationSettings.PlaySettings.PauseAnimation=0
        $audio.AnimationSettings.PlaySettings.StopAfterSlides=1
        $audio.AnimationSettings.PlaySettings.LoopUntilStopped=0
        $audio.AnimationSettings.PlaySettings.RewindMovie=0
        $actual=[math]::Round($audio.MediaFormat.Length/1000,3)
        if ([math]::Abs($actual-[double]$scene.audio_seconds) -gt 0.2) { throw "Scene $index audio duration mismatch: $actual vs $($scene.audio_seconds)." }
    }
    $presentation.SaveAs($narratedDeck)
    if (Test-Path -LiteralPath $videoPath) { Remove-Item -LiteralPath $videoPath -Force }
    $presentation.CreateVideo($videoPath,-1,5,1080,30,85)
    do { Start-Sleep -Seconds 10; $status=$presentation.CreateVideoStatus; Write-Output "Video export status: $status" } while ($status -eq 1 -or $status -eq 2)
    if ($status -ne 3) { throw "PowerPoint video export failed with status $status." }
    Write-Output "Video export complete: $videoPath"
}
finally {
    $presentation.Close()
    $powerPoint.Quit()
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($presentation)|Out-Null
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($powerPoint)|Out-Null
}
