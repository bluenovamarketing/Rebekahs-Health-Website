import array
import math
import sys
import wave

path = sys.argv[1]
with wave.open(path, "rb") as wav:
    sample_rate = wav.getframerate()
    channels = wav.getnchannels()
    frame_count = wav.getnframes()
    samples = array.array("h", wav.readframes(frame_count))

block_samples = max(1, int(sample_rate * 0.02) * channels)
threshold = 100
longest = 0
current = 0

for start in range(0, len(samples), block_samples):
    block = samples[start : start + block_samples]
    rms = math.sqrt(sum(value * value for value in block) / max(1, len(block)))
    current = current + 1 if rms < threshold else 0
    longest = max(longest, current)

print(f"duration_seconds={frame_count / sample_rate:.3f}")
print(f"sample_rate={sample_rate}")
print(f"channels={channels}")
print(f"longest_low_audio_seconds={longest * 0.02:.2f}")
