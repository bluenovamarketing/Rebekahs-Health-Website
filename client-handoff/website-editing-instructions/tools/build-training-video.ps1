param(
    [Parameter(Mandatory=$true)][string]$PackageRoot,
    [Parameter(Mandatory=$true)][string]$SourceDeckName,
    [Parameter(Mandatory=$true)][string]$NarratedDeckName,
    [Parameter(Mandatory=$true)][string]$VideoName,
    [Parameter(Mandatory=$true)][string]$MasterAudioName,
    [int]$ExpectedScenes = 13
)
$ErrorActionPreference = 'Stop'
$sourceDeck = Join-Path $PackageRoot $SourceDeckName
$narratedDeck = Join-Path $PackageRoot $NarratedDeckName
$videoPath = Join-Path $PackageRoot $VideoName
$masterAudio = Join-Path $PackageRoot $MasterAudioName
$timeline = Get-Content -Raw -LiteralPath (Join-Path $PackageRoot 'scene-timeline.json') | ConvertFrom-Json
if ($timeline.Count -ne $ExpectedScenes) { throw "Expected $ExpectedScenes timed scenes, found $($timeline.Count)." }
if (Test-Path -LiteralPath $narratedDeck) { Remove-Item -LiteralPath $narratedDeck -Force }

$powerPoint = New-Object -ComObject PowerPoint.Application
$powerPoint.Visible = -1
$presentation = $powerPoint.Presentations.Open($sourceDeck,0,0,0)
try {
    if ($presentation.Slides.Count -ne $timeline.Count) { throw 'Slide and timeline counts do not match.' }
    for ($index=1; $index -le $presentation.Slides.Count; $index++) {
        $slide=$presentation.Slides.Item($index); $scene=$timeline[$index-1]
        $slide.SlideShowTransition.AdvanceOnClick=0; $slide.SlideShowTransition.AdvanceOnTime=-1; $slide.SlideShowTransition.AdvanceTime=[double]$scene.slide_seconds
    }
    $audio=$presentation.Slides.Item(1).Shapes.AddMediaObject2($masterAudio,0,-1,-50,-50,1,1)
    $audio.Name='Continuous Narration v1.1 - Microsoft Mark'; $audio.AnimationSettings.PlaySettings.PlayOnEntry=-1; $audio.AnimationSettings.PlaySettings.HideWhileNotPlaying=-1; $audio.AnimationSettings.PlaySettings.PauseAnimation=0; $audio.AnimationSettings.PlaySettings.StopAfterSlides=999; $audio.AnimationSettings.PlaySettings.LoopUntilStopped=0; $audio.AnimationSettings.PlaySettings.RewindMovie=0
    $media=[math]::Round($audio.MediaFormat.Length/1000,3); $total=[math]::Round(($timeline | Measure-Object -Property slide_seconds -Sum).Sum,3)
    if ([math]::Abs($media-$total) -gt 0.1) { throw "Audio is $media seconds but timeline is $total seconds." }
    $presentation.SaveAs($narratedDeck)
    if (Test-Path -LiteralPath $videoPath) { Remove-Item -LiteralPath $videoPath -Force }
    $presentation.CreateVideo($videoPath,-1,5,1080,30,85)
    do { Start-Sleep -Seconds 10; $status=$presentation.CreateVideoStatus; Write-Output "Video export status: $status" } while ($status -eq 1 -or $status -eq 2)
    if ($status -ne 3) { throw "PowerPoint video export failed with status $status." }
    Write-Output "Video export complete: $videoPath"
}
finally {
    $presentation.Close(); $powerPoint.Quit(); [System.Runtime.InteropServices.Marshal]::ReleaseComObject($presentation)|Out-Null; [System.Runtime.InteropServices.Marshal]::ReleaseComObject($powerPoint)|Out-Null
}

