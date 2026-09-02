import json
import re
import wave
from pathlib import Path


ROOT = Path(__file__).resolve().parent
AUDIO = ROOT / "audio"
MANIFEST = json.loads((ROOT / "scene-manifest.json").read_text(encoding="utf-8-sig"))


def stamp(seconds: float) -> str:
    milliseconds = max(0, round(seconds * 1000))
    hours, remainder = divmod(milliseconds, 3_600_000)
    minutes, remainder = divmod(remainder, 60_000)
    secs, millis = divmod(remainder, 1000)
    return f"{hours:02d}:{minutes:02d}:{secs:02d}.{millis:03d}"


def duration(path: Path) -> float:
    with wave.open(str(path), "rb") as handle:
        return handle.getnframes() / handle.getframerate()


def sentence_chunks(text: str) -> list[str]:
    sentences = [part.strip() for part in re.split(r"(?<=[.!?])\s+", text) if part.strip()]
    chunks: list[str] = []
    current = ""
    for sentence in sentences:
        candidate = f"{current} {sentence}".strip()
        if current and len(candidate) > 115:
            chunks.append(current)
            current = sentence
        else:
            current = candidate
    if current:
        chunks.append(current)
    return chunks


timeline = []
vtt = ["WEBVTT", ""]
cursor = 0.0
cue_number = 1

for scene in MANIFEST:
    audio_path = AUDIO / scene["audio"]
    audio_duration = duration(audio_path)
    slide_duration = audio_duration + 0.8
    chunks = sentence_chunks(scene["narration"])
    weights = [max(1, len(chunk)) for chunk in chunks]
    weight_total = sum(weights)
    local = cursor

    for chunk, weight in zip(chunks, weights):
        cue_duration = audio_duration * weight / weight_total
        cue_end = local + cue_duration
        vtt.extend([
            str(cue_number),
            f"{stamp(local)} --> {stamp(cue_end)}",
            chunk,
            "",
        ])
        cue_number += 1
        local = cue_end

    timeline.append({
        "scene": scene["scene"],
        "title": scene["title"],
        "audio": scene["audio"],
        "audio_seconds": round(audio_duration, 3),
        "slide_seconds": round(slide_duration, 3),
        "start_seconds": round(cursor, 3),
        "end_seconds": round(cursor + slide_duration, 3),
    })
    cursor += slide_duration

(ROOT / "Practitioner-Editing-Video-v1.1.vtt").write_text("\n".join(vtt), encoding="utf-8")
(ROOT / "scene-timeline.json").write_text(json.dumps(timeline, indent=2), encoding="utf-8")
print(f"Created captions and timing for {len(timeline)} scenes; total {cursor:.1f} seconds.")

