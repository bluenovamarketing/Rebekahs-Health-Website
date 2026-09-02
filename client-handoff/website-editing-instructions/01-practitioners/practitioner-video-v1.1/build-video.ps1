$ErrorActionPreference = 'Stop'

$packageRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$sourceDeck = Join-Path $packageRoot 'Practitioner-Editing-Video-v1.1.pptx'
$narratedDeck = Join-Path $packageRoot 'Practitioner-Editing-Video-v1.1-narrated.pptx'
$videoPath = Join-Path $packageRoot 'Practitioner-Editing-Video-v1.1.mp4'
$audioRoot = Join-Path $packageRoot 'audio'
$timeline = Get-Content -Raw -LiteralPath (Join-Path $packageRoot 'scene-timeline.json') | ConvertFrom-Json

if ($timeline.Count -ne 16) {
    throw "Expected 16 timed scenes, but found $($timeline.Count)."
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
        $audioPath = Join-Path $audioRoot $scene.audio

        $audioShape = $slide.Shapes.AddMediaObject2($audioPath, 0, -1, -50, -50, 1, 1)
        $audioShape.Name = ('Narration {0:D2}' -f $index)
        $audioShape.AnimationSettings.PlaySettings.PlayOnEntry = -1
        $audioShape.AnimationSettings.PlaySettings.HideWhileNotPlaying = -1
        $audioShape.AnimationSettings.PlaySettings.PauseAnimation = 0

        $slide.SlideShowTransition.AdvanceOnClick = 0
        $slide.SlideShowTransition.AdvanceOnTime = -1
        $slide.SlideShowTransition.AdvanceTime = [double]$scene.slide_seconds
    }

    $presentation.SaveAs($narratedDeck)
    Write-Output "Narrated PowerPoint saved."

    if (Test-Path -LiteralPath $videoPath) {
        Remove-Item -LiteralPath $videoPath -Force
    }

    $presentation.CreateVideo($videoPath, -1, 5, 720, 30, 85)
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
