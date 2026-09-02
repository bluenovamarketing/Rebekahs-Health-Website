import argparse
import json
import subprocess
import sys
import wave
from pathlib import Path

parser = argparse.ArgumentParser()
parser.add_argument("package")
parser.add_argument("--master", required=True)
parser.add_argument("--output", required=True)
args = parser.parse_args()

package = Path(args.package).resolve()
timeline = json.loads((package / "scene-timeline.json").read_text(encoding="utf-8-sig"))
if len(timeline) != 13:
    raise SystemExit(f"Expected 13 scenes, found {len(timeline)}.")

work = package / "work"
work.mkdir(parents=True, exist_ok=True)
slides = []
for index, scene in enumerate(timeline, start=1):
    image = (package / "deck-preview" / f"slide-{index:02d}.png").resolve()
    if not image.exists():
        raise SystemExit(f"Missing slide image: {image}")
    slides.append((image, float(scene["slide_seconds"])))

audio = (package / args.master).resolve()
output = (package / args.output).resolve()
if not audio.exists():
    raise SystemExit(f"Missing master narration: {audio}")
with wave.open(str(audio), "rb") as wav:
    exact_seconds = wav.getnframes() / wav.getframerate()

ffmpeg = Path(__file__).resolve().parents[3] / "tmp" / "ffmpeg.exe"
if not ffmpeg.exists():
    raise SystemExit(f"Missing local video encoder: {ffmpeg}")

cmd = [str(ffmpeg), "-hide_banner", "-y"]
for image, seconds in slides:
    boundary_compensated = seconds + (1 / 30)
    cmd.extend(["-loop", "1", "-framerate", "30", "-t", f"{boundary_compensated:.6f}", "-i", str(image)])
cmd.extend(["-i", str(audio)])

filter_parts = []
for index in range(len(slides)):
    filter_parts.append(
        f"[{index}:v]scale=1920:1080:flags=lanczos,fps=30,format=yuv420p,setpts=PTS-STARTPTS[v{index}]"
    )
joined = "".join(f"[v{index}]" for index in range(len(slides)))
filter_parts.append(f"{joined}concat=n={len(slides)}:v=1:a=0[vout]")
cmd.extend([
    "-filter_complex",
    ";".join(filter_parts),
    "-map",
    "[vout]",
    "-map",
    f"{len(slides)}:a:0",
    "-c:v",
    "libx264",
    "-preset",
    "medium",
    "-crf",
    "18",
    "-c:a",
    "aac",
    "-b:a",
    "192k",
    "-t",
    f"{exact_seconds:.6f}",
    "-movflags",
    "+faststart",
    str(output),
])
result = subprocess.run(cmd, text=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
log_path = work / (output.stem + "-ffmpeg.log")
log_path.write_text(result.stdout, encoding="utf-8")
if result.returncode:
    print(result.stdout[-5000:])
    raise SystemExit(result.returncode)
print(f"Rendered {output.name} ({output.stat().st_size} bytes)")
