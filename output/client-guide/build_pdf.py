from io import BytesIO
from pathlib import Path
from xml.sax.saxutils import escape

from docx import Document
from docx.oxml.ns import qn
from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT, TA_RIGHT
from reportlab.lib.pagesizes import letter
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import inch
from reportlab.platypus import (
    BaseDocTemplate, Frame, Image, KeepTogether, PageBreak, PageTemplate,
    Paragraph, Spacer, Table, TableStyle,
)


ROOT = Path(r"C:\Users\todda\Blue Nova Projects\Rebekahs Health Website")
DOCX = ROOT / "output" / "client-guide" / "Rebekahs-Website-Content-Guide.docx"
OUT = ROOT / "output" / "pdf" / "Rebekahs-Website-Content-Guide.pdf"
OUT.parent.mkdir(parents=True, exist_ok=True)

GREEN = colors.HexColor("#285943")
GOLD = colors.HexColor("#B58A45")
INK = colors.HexColor("#24312B")
MUTED = colors.HexColor("#66736D")
PALE = colors.HexColor("#EEF5F1")
LIGHT_GOLD = colors.HexColor("#FBF5E9")


styles = getSampleStyleSheet()
body = ParagraphStyle(
    "Body", parent=styles["BodyText"], fontName="Helvetica", fontSize=9.8,
    leading=12.25, textColor=INK, spaceAfter=5.5,
)
h1 = ParagraphStyle(
    "H1", parent=body, fontName="Helvetica-Bold", fontSize=16,
    leading=19, textColor=GREEN, spaceBefore=9, spaceAfter=8, keepWithNext=True,
)
h2 = ParagraphStyle(
    "H2", parent=body, fontName="Helvetica-Bold", fontSize=12.5,
    leading=15, textColor=GREEN, spaceBefore=7, spaceAfter=5, keepWithNext=True,
)
h3 = ParagraphStyle(
    "H3", parent=body, fontName="Helvetica-Bold", fontSize=11.5,
    leading=14, textColor=INK, spaceBefore=6, spaceAfter=4, keepWithNext=True,
)
bullet = ParagraphStyle(
    "Bullet", parent=body, leftIndent=0.31 * inch, firstLineIndent=-0.16 * inch,
    bulletIndent=0.12 * inch, spaceAfter=4,
)
numbered = ParagraphStyle(
    "Numbered", parent=body, leftIndent=0.31 * inch, firstLineIndent=-0.18 * inch,
    bulletIndent=0.09 * inch, spaceAfter=4.5,
)
caption = ParagraphStyle(
    "Caption", parent=body, fontName="Helvetica-Oblique", fontSize=8.3,
    leading=10, textColor=MUTED, alignment=TA_CENTER, spaceBefore=2, spaceAfter=7,
)
title_style = ParagraphStyle(
    "Title", parent=body, fontName="Helvetica-Bold", fontSize=26,
    leading=30, textColor=GREEN, spaceBefore=22, spaceAfter=7,
)
kicker = ParagraphStyle(
    "Kicker", parent=body, fontName="Helvetica-Bold", fontSize=9,
    leading=11, textColor=GOLD, spaceBefore=10, spaceAfter=2,
)
subtitle = ParagraphStyle(
    "Subtitle", parent=body, fontName="Helvetica", fontSize=14,
    leading=17, textColor=MUTED, spaceAfter=14,
)


def safe_text(text):
    return escape(text).replace("\n", "<br/>")


def has_page_break(paragraph):
    return bool(paragraph._p.xpath('.//w:br[@w:type="page"]'))


def picture_blob(paragraph, doc):
    blips = paragraph._p.xpath('.//a:blip')
    if not blips:
        return None
    rid = blips[0].get(qn("r:embed"))
    if not rid:
        return None
    return doc.part.related_parts[rid].blob


def callout_colors(paragraph):
    shd = paragraph._p.find('.//' + qn("w:shd"))
    pbdr = paragraph._p.find('.//' + qn("w:pBdr"))
    if shd is None or pbdr is None:
        return None
    fill = shd.get(qn("w:fill"), "EEF5F1")
    left = pbdr.find(qn("w:left"))
    accent = left.get(qn("w:color"), "285943") if left is not None else "285943"
    return colors.HexColor("#" + fill), colors.HexColor("#" + accent)


def image_flowable(blob):
    im = Image(BytesIO(blob))
    max_w = 6.12 * inch
    max_h = 3.30 * inch
    scale = min(max_w / im.imageWidth, max_h / im.imageHeight)
    im.drawWidth = im.imageWidth * scale
    im.drawHeight = im.imageHeight * scale
    im.hAlign = "CENTER"
    return im


def header_footer(canvas, doc):
    canvas.saveState()
    canvas.setFont("Helvetica-Bold", 7.8)
    canvas.setFillColor(MUTED)
    canvas.drawString(1.0 * inch, 10.55 * inch,
                      "REBEKAH'S HEALTH & NUTRITION  |  WEBSITE CONTENT GUIDE")
    canvas.setStrokeColor(colors.HexColor("#D9E3DE"))
    canvas.setLineWidth(0.45)
    canvas.line(1.0 * inch, 10.42 * inch, 7.5 * inch, 10.42 * inch)
    canvas.setFont("Helvetica", 8)
    canvas.drawRightString(7.5 * inch, 0.45 * inch,
                           f"Client reference  |  Page {doc.page}")
    canvas.restoreState()


def convert():
    src = Document(DOCX)
    story = []
    list_number = 0
    previous_was_number = False

    for p in src.paragraphs:
        if has_page_break(p):
            story.append(PageBreak())
            previous_was_number = False
            list_number = 0
            continue

        blob = picture_blob(p, src)
        if blob:
            story.append(Spacer(1, 3))
            story.append(image_flowable(blob))
            previous_was_number = False
            continue

        text = p.text.strip()
        if not text:
            continue

        callout = callout_colors(p)
        if callout:
            fill, accent = callout
            para = Paragraph(safe_text(text), ParagraphStyle(
                "CalloutLocal", parent=body, fontSize=9.4, leading=11.7,
                leftIndent=3, rightIndent=3, spaceAfter=0,
            ))
            tbl = Table([[para]], colWidths=[6.34 * inch])
            tbl.setStyle(TableStyle([
                ("BACKGROUND", (0, 0), (-1, -1), fill),
                ("LINEBEFORE", (0, 0), (0, -1), 3.0, accent),
                ("LEFTPADDING", (0, 0), (-1, -1), 10),
                ("RIGHTPADDING", (0, 0), (-1, -1), 8),
                ("TOPPADDING", (0, 0), (-1, -1), 8),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 8),
            ]))
            story.extend([Spacer(1, 3), tbl, Spacer(1, 6)])
            previous_was_number = False
            continue

        style_name = p.style.name if p.style else "Normal"
        if style_name == "Heading 1":
            story.append(Paragraph(safe_text(text), h1))
            previous_was_number = False
        elif style_name == "Heading 2":
            story.append(Paragraph(safe_text(text), h2))
            previous_was_number = False
        elif style_name == "Heading 3":
            story.append(Paragraph(safe_text(text), h3))
            previous_was_number = False
        elif style_name == "List Bullet":
            story.append(Paragraph(safe_text(text), bullet, bulletText="•"))
            previous_was_number = False
        elif style_name == "List Number":
            if not previous_was_number:
                list_number = 1
            else:
                list_number += 1
            story.append(Paragraph(safe_text(text), numbered, bulletText=f"{list_number}."))
            previous_was_number = True
        else:
            previous_was_number = False
            max_size = 0
            first_color = None
            bold = False
            for run in p.runs:
                if run.font.size:
                    max_size = max(max_size, run.font.size.pt)
                if run.font.color and run.font.color.rgb:
                    first_color = str(run.font.color.rgb)
                bold = bold or bool(run.bold)
            if max_size >= 24:
                st = title_style
            elif max_size >= 14:
                st = subtitle
            elif max_size <= 10 and bold and first_color == "B58A45":
                st = kicker
            elif p.alignment == 1 and max_size <= 10:
                st = caption
            else:
                st = body
            story.append(Paragraph(safe_text(text), st))

    pdf = BaseDocTemplate(
        str(OUT), pagesize=letter,
        leftMargin=1.0 * inch, rightMargin=1.0 * inch,
        topMargin=0.76 * inch, bottomMargin=0.70 * inch,
        title="Rebekah's Health & Nutrition - Website Content Guide",
        author="Blue Nova Marketing",
        subject="Client instructions for creating Events and Blog Posts",
    )
    frame = Frame(pdf.leftMargin, pdf.bottomMargin, pdf.width, pdf.height,
                  leftPadding=0, rightPadding=0, topPadding=0, bottomPadding=0)
    pdf.addPageTemplates(PageTemplate(id="Guide", frames=[frame], onPage=header_footer))
    pdf.build(story)
    print(OUT)


if __name__ == "__main__":
    convert()
