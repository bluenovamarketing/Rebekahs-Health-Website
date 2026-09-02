import json
import struct
import sys
from pathlib import Path


CONTAINERS = {b"moov", b"trak", b"mdia", b"minf", b"stbl"}


def boxes(data, start=0, end=None):
    end = len(data) if end is None else end
    offset = start
    while offset + 8 <= end:
        size, kind = struct.unpack_from(">I4s", data, offset)
        header = 8
        if size == 1:
            size = struct.unpack_from(">Q", data, offset + 8)[0]
            header = 16
        elif size == 0:
            size = end - offset
        if size < header or offset + size > end:
            break
        payload_start = offset + header
        node = {"type": kind, "start": offset, "end": offset + size, "payload": payload_start}
        if kind in CONTAINERS:
            node["children"] = list(boxes(data, payload_start, offset + size))
        yield node
        offset += size


def child(node, kind):
    return next((item for item in node.get("children", []) if item["type"] == kind), None)


def main(path_string):
    path = Path(path_string)
    data = path.read_bytes()
    top = list(boxes(data))
    moov = next(item for item in top if item["type"] == b"moov")
    tracks = []
    for trak in (item for item in moov["children"] if item["type"] == b"trak"):
        tkhd = child(trak, b"tkhd")
        mdia = child(trak, b"mdia")
        hdlr = child(mdia, b"hdlr")
        mdhd = child(mdia, b"mdhd")
        handler = data[hdlr["payload"] + 8 : hdlr["payload"] + 12].decode("ascii", "replace")
        version = data[mdhd["payload"]]
        if version == 1:
            timescale = struct.unpack_from(">I", data, mdhd["payload"] + 20)[0]
            duration = struct.unpack_from(">Q", data, mdhd["payload"] + 24)[0]
        else:
            timescale = struct.unpack_from(">I", data, mdhd["payload"] + 12)[0]
            duration = struct.unpack_from(">I", data, mdhd["payload"] + 16)[0]
        result = {"handler": handler, "duration_seconds": round(duration / timescale, 3)}
        if handler == "vide":
            payload_end = tkhd["end"]
            result["width"] = struct.unpack_from(">I", data, payload_end - 8)[0] / 65536
            result["height"] = struct.unpack_from(">I", data, payload_end - 4)[0] / 65536
        tracks.append(result)
    print(json.dumps({"file": str(path), "bytes": len(data), "tracks": tracks}, indent=2))


if __name__ == "__main__":
    main(sys.argv[1])
