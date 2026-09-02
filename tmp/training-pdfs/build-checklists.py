from pathlib import Path
import re

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER
from reportlab.lib.pagesizes import letter
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import inch
from reportlab.platypus import KeepTogether, ListFlowable, ListItem, Paragraph, SimpleDocTemplate, Spacer, Table, TableStyle


ROOT = Path(r"C:\Users\todda\Blue Nova Projects\Rebekahs Health Website")
OUT = ROOT / "output" / "pdf"
OUT.mkdir(parents=True, exist_ok=True)

JOBS = [
    ("03-staff-access/staff-user-video-v1.1", "How-to-Add-Change-or-Remove-a-Staff-WordPress-User-v1.1-Checklist.pdf"),
    ("04-cache-and-verification/cache-video-v1.1", "How-to-Clear-the-Website-Cache-Safely-v1.1-Checklist.pdf"),
    ("05-blog-posts/blog-video-v1.1", "How-to-Create-or-Update-a-Blog-Post-v1.1-Checklist.pdf"),
    ("06-media-library/media-video-v1.1", "How-to-Upload-and-Use-Images-in-the-Media-Library-v1.1-Checklist.pdf"),
    ("07-editing-safety/safety-video-v1.1", "Website-Editing-Safety-and-Recovery-v1.1-Checklist.pdf"),
]

GREEN = colors.HexColor("#174F40")
GOLD = colors.HexColor("#D9A12D")
CREAM = colors.HexColor("#F7F3E8")
INK = colors.HexColor("#26332E")
MAGENTA = colors.HexColor("#944766")

styles = getSampleStyleSheet()
title_style = ParagraphStyle("TitleBN", parent=styles["Title"], fontName="Helvetica-Bold", fontSize=20, leading=23, textColor=GREEN, spaceAfter=8)
meta_style = ParagraphStyle("MetaBN", parent=styles["BodyText"], fontName="Helvetica-Bold", fontSize=8.6, leading=11, textColor=MAGENTA)
head_style = ParagraphStyle("HeadBN", parent=styles["Heading2"], fontName="Helvetica-Bold", fontSize=10.5, leading=12, textColor=GREEN, spaceBefore=4, spaceAfter=3)
body_style = ParagraphStyle("BodyBN", parent=styles["BodyText"], fontName="Helvetica", fontSize=8.1, leading=10.1, textColor=INK, spaceAfter=1.2)
finish_style = ParagraphStyle("FinishBN", parent=styles["BodyText"], fontName="Helvetica-Bold", fontSize=8.5, leading=10.5, textColor=colors.white)
footer_style = ParagraphStyle("FooterBN", parent=styles["BodyText"], fontName="Helvetica", fontSize=7.2, leading=8.5, alignment=TA_CENTER, textColor=GREEN)


def inline(text: str) -> str:
    text = text.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")
    text = re.sub(r"\*\*(.+?)\*\*", r"<b>\1</b>", text)
    text = re.sub(r"`(.+?)`", r"<font name='Courier'>\1</font>", text)
    return text


def make_pdf(source: Path, target: Path) -> None:
    lines = source.read_text(encoding="utf-8-sig").splitlines()
    title = lines[0].removeprefix("# ").strip()
    version = next((line for line in lines if line.startswith("**Version:**")), "**Version:** v1.1")
    role = next((line for line in lines if line.startswith("**Required")), "")
    finish = next((line for line in lines if line.startswith("**Always finish with:")), "")
    content = []
    current_header = None
    current_items = []

    def flush():
        nonlocal current_items
        if current_header:
            content.append(Paragraph(inline(current_header), head_style))
        if current_items:
            content.append(ListFlowable([ListItem(Paragraph(inline(item), body_style), leftIndent=9) for item in current_items], bulletType="bullet", start="circle", leftIndent=12, bulletFontSize=5, spaceAfter=2))
        current_items = []

    for line in lines[1:]:
        stripped = line.strip()
        if not stripped or stripped.startswith("**Version:") or stripped.startswith("**Required") or stripped == finish:
            continue
        if stripped.startswith("## "):
            flush()
            current_header = stripped[3:]
        elif re.match(r"^(?:- |\d+\. )", stripped):
            current_items.append(re.sub(r"^(?:- |\d+\. )", "", stripped))
        elif current_items:
            current_items[-1] += " " + stripped
        else:
            current_items.append(stripped)
    flush()

    doc = SimpleDocTemplate(str(target), pagesize=letter, rightMargin=0.45*inch, leftMargin=0.45*inch, topMargin=0.38*inch, bottomMargin=0.38*inch, title=title, author="Blue Nova Marketing")
    story = [Paragraph(title, title_style)]
    meta = inline(version.replace("**", "")) + (" &nbsp;&nbsp;|&nbsp;&nbsp; " + inline(role.replace("**", "")) if role else "")
    story += [Paragraph(meta, meta_style), Spacer(1, 4), Table([[""]], colWidths=[7.6*inch], rowHeights=[3], style=TableStyle([("BACKGROUND", (0,0), (-1,-1), GOLD)])), Spacer(1, 4)]
    story.extend(content)
    if finish:
        finish_text = finish.replace("**Always finish with:", "<b>Always finish with:</b>").replace("**", "")
        box = Table([[Paragraph(inline(finish_text).replace("&lt;b&gt;", "<b>").replace("&lt;/b&gt;", "</b>"), finish_style)]], colWidths=[7.45*inch], style=TableStyle([("BACKGROUND", (0,0), (-1,-1), GREEN), ("BOX", (0,0), (-1,-1), 0.8, GOLD), ("LEFTPADDING", (0,0), (-1,-1), 10), ("RIGHTPADDING", (0,0), (-1,-1), 10), ("TOPPADDING", (0,0), (-1,-1), 7), ("BOTTOMPADDING", (0,0), (-1,-1), 7)]))
        story += [Spacer(1, 5), KeepTogether(box)]
    story += [Spacer(1, 5), Paragraph("Rebekah's Health &amp; Nutrition Source | Blue Nova Marketing | Keep with the matching narrated video", footer_style)]
    doc.build(story)


for package, filename in JOBS:
    make_pdf(ROOT / "client-handoff" / "website-editing-instructions" / package / "one-page-checklist.md", OUT / filename)
    print(OUT / filename)
