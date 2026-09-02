from pathlib import Path

from reportlab.lib.colors import HexColor, white
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import letter
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.utils import ImageReader
from reportlab.pdfbase.pdfmetrics import stringWidth
from reportlab.pdfgen import canvas
from reportlab.platypus import Paragraph


ROOT = Path(__file__).resolve().parent
PROJECT = ROOT.parents[3]
OUTPUT = ROOT / "Practitioner-Editing-Checklist-v1.1.pdf"
LOGO = PROJECT / "tmp" / "source" / "current-site-logo-live.png"

PINE = HexColor("#174C3C")
LEAF = HexColor("#3F7D50")
SAGE = HexColor("#A9C3A0")
HONEY = HexColor("#D6A33A")
BERRY = HexColor("#8E4B61")
CREAM = HexColor("#F7F3E8")
INK = HexColor("#26342E")
MIST = HexColor("#E9EFEA")


def rounded_box(pdf, x, y, width, height, fill=white, stroke=SAGE):
    pdf.setFillColor(fill)
    pdf.setStrokeColor(stroke)
    pdf.setLineWidth(0.9)
    pdf.roundRect(x, y, width, height, 10, fill=1, stroke=1)


def paragraph(pdf, text, x, y_top, width, style):
    item = Paragraph(text, style)
    _, height = item.wrap(width, 1000)
    item.drawOn(pdf, x, y_top - height)
    return height


def section(pdf, number, title, items, x, y_top, width, box_height):
    rounded_box(pdf, x, y_top - box_height, width, box_height)
    pdf.setFillColor(HONEY)
    pdf.circle(x + 20, y_top - 23, 12, fill=1, stroke=0)
    pdf.setFillColor(INK)
    pdf.setFont("Helvetica-Bold", 10)
    num_width = stringWidth(str(number), "Helvetica-Bold", 10)
    pdf.drawString(x + 20 - num_width / 2, y_top - 26.5, str(number))
    paragraph(pdf, title, x + 40, y_top - 11, width - 54, STYLES["section_title"])

    cursor = y_top - 49
    for item in items:
        height = paragraph(pdf, f"<b>[ ]</b> {item}", x + 14, cursor, width - 28, STYLES["body"])
        cursor -= height + 5


STYLES = {
    "eyebrow": ParagraphStyle(
        "eyebrow", fontName="Helvetica-Bold", fontSize=8.5, leading=11,
        textColor=LEAF, alignment=TA_LEFT, spaceAfter=0,
    ),
    "title": ParagraphStyle(
        "title", fontName="Helvetica-Bold", fontSize=21, leading=24,
        textColor=PINE, alignment=TA_LEFT, spaceAfter=0,
    ),
    "subtitle": ParagraphStyle(
        "subtitle", fontName="Helvetica", fontSize=9.2, leading=12,
        textColor=INK, alignment=TA_LEFT, spaceAfter=0,
    ),
    "section_title": ParagraphStyle(
        "section_title", fontName="Helvetica-Bold", fontSize=11.2, leading=14,
        textColor=PINE, alignment=TA_LEFT, spaceAfter=0,
    ),
    "body": ParagraphStyle(
        "body", fontName="Helvetica", fontSize=8.1, leading=10.2,
        textColor=INK, alignment=TA_LEFT, spaceAfter=0,
    ),
    "small": ParagraphStyle(
        "small", fontName="Helvetica", fontSize=7.7, leading=9.6,
        textColor=INK, alignment=TA_LEFT, spaceAfter=0,
    ),
    "footer": ParagraphStyle(
        "footer", fontName="Helvetica-Bold", fontSize=9, leading=11,
        textColor=white, alignment=TA_CENTER, spaceAfter=0,
    ),
}


def main():
    pdf = canvas.Canvas(str(OUTPUT), pagesize=letter)
    page_width, page_height = letter
    pdf.setTitle("Update an Existing Practitioner Listing - Checklist v1.1")
    pdf.setAuthor("Blue Nova Marketing")
    pdf.setFillColor(CREAM)
    pdf.rect(0, 0, page_width, page_height, fill=1, stroke=0)

    logo = ImageReader(str(LOGO))
    pdf.drawImage(logo, 36, 691, width=175, height=74, preserveAspectRatio=True, mask="auto")
    paragraph(pdf, "REBEKAH'S WEBSITE EDITING LIBRARY", 232, 760, 334, STYLES["eyebrow"])
    paragraph(pdf, "Update an Existing<br/>Practitioner Listing", 232, 737, 334, STYLES["title"])
    paragraph(pdf, "Beginner checklist v1.1 | Live website | Recommended staff role: <b>Editor</b>", 232, 682, 334, STYLES["subtitle"])

    pdf.setStrokeColor(HONEY)
    pdf.setLineWidth(2)
    pdf.line(36, 662, 576, 662)

    col_width = 170
    gap = 15
    x1, x2, x3 = 36, 36 + col_width + gap, 36 + 2 * (col_width + gap)

    section(pdf, 1, "Before you edit", [
        "Sign in at <b>rebekahspureliving.com/wp-admin</b>.",
        "Confirm you are on the live domain, not a future staging site.",
        "Use your own Editor or Administrator account.",
        "Gather approved name, credentials, practice, category, description, and public contact details.",
    ], x1, 653, col_width, 202)

    section(pdf, 2, "Open the correct record", [
        "Select <b>Medical Practicioners</b> in the left menu.",
        "Select <b>All Medical Practicioners</b>.",
        "Search for the person when needed.",
        "Hover over the correct row and select <b>Edit</b>.",
        "Do not use Quick Edit, Trash, or Clear Cache yet.",
    ], x2, 653, col_width, 202)

    section(pdf, 3, "Edit approved fields only", [
        "Open <b>Medical Practicioner Fields</b>. If hidden, select Meta Boxes and use Drag to resize.",
        "Medical Service: approved category or categories only.",
        "Medical Center: public practice name.",
        "Full Name: public name and credentials.",
        "Description and Contact Info: select <b>Visual</b> first.",
    ], x3, 653, col_width, 202)

    section(pdf, 4, "Review and save", [
        "Check spelling, credentials, categories, first and last description lines, and every link.",
        "Do not change the page address, SEO settings, code, or unrelated controls.",
        "Select the blue <b>Save</b> button once.",
        "Wait for the saved confirmation before leaving.",
    ], x1, 434, col_width, 190)

    section(pdf, 5, "Clear the applicable cache", [
        "Return to All Medical Practicioners.",
        "Hover over the updated row and select its <b>Clear Cache</b> action.",
        "Do not use Purge All Cache, Purge Varnish, or another server-wide control unless the cache lesson directs you.",
    ], x2, 434, col_width, 190)

    section(pdf, 6, "Verify the public result", [
        "Open <b>rebekahspureliving.com/practitioners/</b> with nothing added after the slash.",
        "Search for the person and check the card.",
        "Select View full listing and check all details and links.",
        "Repeat the check on a phone or narrow browser width.",
    ], x3, 434, col_width, 190)

    rounded_box(pdf, 36, 128, 540, 98, fill=MIST, stroke=SAGE)
    paragraph(pdf, "STOP AND CONTACT BLUE NOVA", 52, 210, 220, STYLES["section_title"])
    paragraph(
        pdf,
        "Stop before saving if the category is missing, a requested field is unclear, WordPress shows an error, the old information remains after clearing the record cache, the layout breaks, or a public link does not work.",
        52,
        184,
        508,
        STYLES["small"],
    )

    pdf.setFillColor(PINE)
    pdf.roundRect(36, 52, 540, 60, 12, fill=1, stroke=0)
    paragraph(pdf, "SAVE  >  CLEAR THE APPLICABLE CACHE  >  VERIFY THE EXACT PUBLIC PAGE ON DESKTOP AND PHONE", 55, 91, 502, STYLES["footer"])
    pdf.setFillColor(BERRY)
    pdf.setFont("Helvetica", 7.5)
    pdf.drawCentredString(page_width / 2, 31, "Blue Nova Marketing | Practitioner pilot v1.1 | Keep this PDF with the matching narrated video")

    pdf.showPage()
    pdf.save()
    print(OUTPUT)


if __name__ == "__main__":
    main()
