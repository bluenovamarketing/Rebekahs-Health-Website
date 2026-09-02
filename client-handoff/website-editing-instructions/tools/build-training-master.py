import argparse, json, re, wave
from pathlib import Path

parser = argparse.ArgumentParser()
parser.add_argument("package")
parser.add_argument("--master", required=True)
parser.add_argument("--vtt", required=True)
args = parser.parse_args()

root = Path(args.package)
manifest = json.loads((root / "scene-manifest.json").read_text(encoding="utf-8-sig"))
leading, gap = 0.15, 0.35

def silence(seconds, rate, channels, width): return b"\x00" * round(seconds * rate) * channels * width
def stamp(seconds):
    ms=max(0,round(seconds*1000)); h,ms=divmod(ms,3600000); m,ms=divmod(ms,60000); s,ms=divmod(ms,1000)
    return f"{h:02d}:{m:02d}:{s:02d}.{ms:03d}"
def chunks(text):
    out=[]; current=""
    for sentence in [x.strip() for x in re.split(r"(?<=[.!?])\s+", text) if x.strip()]:
        candidate=(current+" "+sentence).strip()
        if current and len(candidate)>115: out.append(current); current=sentence
        else: current=candidate
    if current: out.append(current)
    return out

fmt=None; frames=bytearray(); timeline=[]; vtt=["WEBVTT",""]; cursor=0.0; cue=1
for index,scene in enumerate(manifest):
    with wave.open(str(root/"audio"/scene["audio"]),"rb") as w:
        current=(w.getnchannels(),w.getsampwidth(),w.getframerate(),w.getcomptype()); data=w.readframes(w.getnframes()); duration=w.getnframes()/w.getframerate()
    if fmt is None: fmt=current
    elif current!=fmt: raise ValueError("Audio format mismatch")
    channels,width,rate,_=fmt; lead=leading if index==0 else 0.0
    frames.extend(silence(lead,rate,channels,width)); speech_start=cursor+lead; frames.extend(data); frames.extend(silence(gap,rate,channels,width))
    parts=chunks(scene["narration"]); weights=[max(1,len(x)) for x in parts]; total=sum(weights); local=speech_start
    for part,weight in zip(parts,weights):
        end=local+duration*weight/total; vtt += [str(cue),f"{stamp(local)} --> {stamp(end)}",part,""]; cue+=1; local=end
    slide=lead+duration+gap
    timeline.append({"scene":scene["scene"],"title":scene["title"],"audio":scene["audio"],"audio_seconds":round(duration,3),"leading_silence_seconds":round(lead,3),"trailing_silence_seconds":gap,"slide_seconds":round(slide,3),"start_seconds":round(cursor,3),"speech_start_seconds":round(speech_start,3),"speech_end_seconds":round(speech_start+duration,3),"end_seconds":round(cursor+slide,3)})
    cursor += slide

with wave.open(str(root/args.master),"wb") as out:
    out.setnchannels(fmt[0]); out.setsampwidth(fmt[1]); out.setframerate(fmt[2]); out.setcomptype(fmt[3],"not compressed"); out.writeframes(bytes(frames))
(root/args.vtt).write_text("\n".join(vtt),encoding="utf-8")
(root/"scene-timeline.json").write_text(json.dumps(timeline,indent=2),encoding="utf-8")
print(f"Created {len(timeline)} scenes; total {cursor:.1f} seconds")

