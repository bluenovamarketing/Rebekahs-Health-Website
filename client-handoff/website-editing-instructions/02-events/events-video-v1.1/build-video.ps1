$ErrorActionPreference = 'Stop'

$packageRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$sourceDeck = Join-Path $packageRoot 'Create-or-Update-an-Event-v1.1.pptx'
$narratedDeck = Join-Path $packageRoot 'Create-or-Update-an-Event-v1.1-narrated.pptx'
$videoPath = Join-Path $packageRoot 'How-to-Create-or-Update-an-Event-v1.1.mp4'
$masterAudio = Join-Path $packageRoot 'Event-Editing-Narration-v1.1.wav'
$timeline = Get-Content -Raw -LiteralPath (Join-Path $packageRoot 'scene-timeline.json') | ConvertFrom-Json

if ($timeline.Count -ne 13) {
    throw "Expected 13 timed scenes, but found $($timeline.Count)."
}

if (Test-Path -LiteralPath $narratedDeck) {
    Remove-Item -LiteralPath $narratedDeck -Force
}

$powerPoint = New-Object -ComObject PowerPoint.Application
$powerPoint.Visible = -1
$presentation = $powerPoint.Presentations.Open($sourceDeck, 0, 0, 0)

try {
    if ($presentation.Slides.Count -ne $timeline.Count) {
        throw "The presentation has $($presentation.Slides.Count) slides, but the timeline has $($timeline.Count) scenes."
    }

    for ($index = 1; $index -le $presentation.Slides.Count; $index++) {
        $slide = $presentation.Slides.Item($index)
        $scene = $timeline[$index - 1]

        $slide.SlideShowTransition.AdvanceOnClick = 0
        $slide.SlideShowTransition.AdvanceOnTime = -1
        $slide.SlideShowTransition.AdvanceTime = [double]$scene.slide_seconds
    }

    $audioShape = $presentation.Slides.Item(1).Shapes.AddMediaObject2($masterAudio, 0, -1, -50, -50, 1, 1)
    $audioShape.Name = 'Continuous Narration v1.1 - Microsoft Mark'
    $audioShape.AnimationSettings.PlaySettings.PlayOnEntry = -1
    $audioShape.AnimationSettings.PlaySettings.HideWhileNotPlaying = -1
    $audioShape.AnimationSettings.PlaySettings.PauseAnimation = 0
    $audioShape.AnimationSettings.PlaySettings.StopAfterSlides = 999
    $audioShape.AnimationSettings.PlaySettings.LoopUntilStopped = 0
    $audioShape.AnimationSettings.PlaySettings.RewindMovie = 0

    $mediaSeconds = [math]::Round($audioShape.MediaFormat.Length / 1000, 3)
    $timelineSeconds = [math]::Round(($timeline | Measure-Object -Property slide_seconds -Sum).Sum, 3)
    if ([math]::Abs($mediaSeconds - $timelineSeconds) -gt 0.1) {
        throw "Continuous narration is $mediaSeconds seconds but the slide timeline is $timelineSeconds seconds."
    }
    Write-Output "Continuous narration and slide timeline both equal $timelineSeconds seconds."

    $presentation.SaveAs($narratedDeck)
    Write-Output 'Narrated PowerPoint saved.'

    if (Test-Path -LiteralPath $videoPath) {
        Remove-Item -LiteralPath $videoPath -Force
    }

    $presentation.CreateVideo($videoPath, -1, 5, 1080, 30, 85)
    do {
        Start-Sleep -Seconds 10
        $status = $presentation.CreateVideoStatus
        Write-Output "Video export status: $status"
    } while ($status -eq 1 -or $status -eq 2)

    if ($status -ne 3) {
        throw "PowerPoint video export failed with status $status."
    }

    Write-Output "Video export complete: $videoPath"
}
finally {
    $presentation.Close()
    $powerPoint.Quit()
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($presentation) | Out-Null
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($powerPoint) | Out-Null
}
