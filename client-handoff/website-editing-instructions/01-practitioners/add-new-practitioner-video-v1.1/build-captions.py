import json
import re
import wave
from pathlib import Path


ROOT = Path(__file__).resolve().parent
AUDIO = ROOT / "audio"
MANIFEST = json.loads((ROOT / "scene-manifest.json").read_text(encoding="utf-8-sig"))
VTT_PATH = ROOT / "How-to-Add-a-New-Practitioner-or-Practice-v1.1.vtt"
TIMELINE_PATH = ROOT / "scene-timeline.json"
MASTER_PATH = ROOT / "Add-New-Practitioner-Narration-v1.1.wav"

LEADING_SILENCE = 0.15
BETWEEN_SCENES = 0.35


def stamp(seconds: float) -> str:
    milliseconds = max(0, round(seconds * 1000))
    hours, remainder = divmod(milliseconds, 3_600_000)
    minutes, remainder = divmod(remainder, 60_000)
    secs, millis = divmod(remainder, 1000)
    return f"{hours:02d}:{minutes:02d}:{secs:02d}.{millis:03d}"


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


def silence_frames(seconds: float, sample_rate: int, channels: int, sample_width: int) -> bytes:
    return b"\x00" * round(seconds * sample_rate) * channels * sample_width


timeline = []
vtt = ["WEBVTT", ""]
master_frames = bytearray()
cursor = 0.0
cue_number = 1
audio_format = None

for index, scene in enumerate(MANIFEST):
    audio_path = AUDIO / scene["audio"]
    with wave.open(str(audio_path), "rb") as handle:
        current_format = (
            handle.getnchannels(),
            handle.getsampwidth(),
            handle.getframerate(),
            handle.getcomptype(),
        )
        if audio_format is None:
            audio_format = current_format
        elif current_format != audio_format:
            raise ValueError(f"Audio format mismatch in {audio_path.name}: {current_format} != {audio_format}")
        frames = handle.readframes(handle.getnframes())
        audio_duration = handle.getnframes() / handle.getframerate()

    channels, sample_width, sample_rate, _ = audio_format
    leading = LEADING_SILENCE if index == 0 else 0.0
    if leading:
        master_frames.extend(silence_frames(leading, sample_rate, channels, sample_width))
    speech_start = cursor + leading
    master_frames.extend(frames)
    master_frames.extend(silence_frames(BETWEEN_SCENES, sample_rate, channels, sample_width))

    chunks = sentence_chunks(scene["narration"])
    weights = [max(1, len(chunk)) for chunk in chunks]
    weight_total = sum(weights)
    local = speech_start
    for chunk, weight in zip(chunks, weights):
        cue_duration = audio_duration * weight / weight_total
        cue_end = local + cue_duration
        vtt.extend([str(cue_number), f"{stamp(local)} --> {stamp(cue_end)}", chunk, ""])
        cue_number += 1
        local = cue_end

    slide_duration = leading + audio_duration + BETWEEN_SCENES
    timeline.append({
        "scene": scene["scene"],
        "title": scene["title"],
        "audio": scene["audio"],
        "audio_seconds": round(audio_duration, 3),
        "leading_silence_seconds": round(leading, 3),
        "trailing_silence_seconds": BETWEEN_SCENES,
        "slide_seconds": round(slide_duration, 3),
        "start_seconds": round(cursor, 3),
        "speech_start_seconds": round(speech_start, 3),
        "speech_end_seconds": round(speech_start + audio_duration, 3),
        "end_seconds": round(cursor + slide_duration, 3),
    })
    cursor += slide_duration

channels, sample_width, sample_rate, compression = audio_format
with wave.open(str(MASTER_PATH), "wb") as master:
    master.setnchannels(channels)
    master.setsampwidth(sample_width)
    master.setframerate(sample_rate)
    master.setcomptype(compression, "not compressed")
    master.writeframes(bytes(master_frames))

VTT_PATH.write_text("\n".join(vtt), encoding="utf-8")
TIMELINE_PATH.write_text(json.dumps(timeline, indent=2), encoding="utf-8")

with wave.open(str(MASTER_PATH), "rb") as master:
    master_duration = master.getnframes() / master.getframerate()

if abs(master_duration - cursor) > 0.02:
    raise ValueError(f"Master narration length {master_duration:.3f}s does not match timeline {cursor:.3f}s")

print(f"Created one continuous narration track for {len(timeline)} scenes; total {cursor:.1f} seconds.")
