from pathlib import Path

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER
from reportlab.lib.pagesizes import letter
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.units import inch
from reportlab.platypus import Paragraph, SimpleDocTemplate, Spacer, Table, TableStyle


ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / "output" / "pdf"
OUT.mkdir(parents=True, exist_ok=True)

PINE = colors.HexColor("#174C3C")
HONEY = colors.HexColor("#D6A33A")
CREAM = colors.HexColor("#F7F3E8")
INK = colors.HexColor("#26342E")
BERRY = colors.HexColor("#8E4B61")


def build(filename, title, access, steps, note):
    path = OUT / filename
    doc = SimpleDocTemplate(
        str(path),
        pagesize=letter,
        leftMargin=0.46 * inch,
        rightMargin=0.46 * inch,
        topMargin=0.4 * inch,
        bottomMargin=0.36 * inch,
    )
    styles = {
        "title": ParagraphStyle("title", fontName="Helvetica-Bold", fontSize=18, leading=21, textColor=PINE, alignment=TA_CENTER, spaceAfter=5),
        "access": ParagraphStyle("access", fontName="Helvetica-Bold", fontSize=9.5, leading=12, textColor=INK, alignment=TA_CENTER),
        "step": ParagraphStyle("step", fontName="Helvetica", fontSize=8.35, leading=10.35, textColor=INK),
        "note": ParagraphStyle("note", fontName="Helvetica", fontSize=8.4, leading=10.4, textColor=BERRY),
        "finish": ParagraphStyle("finish", fontName="Helvetica-Bold", fontSize=10.5, leading=13, textColor=colors.white, alignment=TA_CENTER),
        "footer": ParagraphStyle("footer", fontName="Helvetica", fontSize=7.2, leading=9, textColor=PINE, alignment=TA_CENTER),
    }

    rows = []
    for number, step in enumerate(steps, 1):
        rows.append([
            Paragraph(str(number), ParagraphStyle("number", fontName="Helvetica-Bold", fontSize=8.4, leading=10.4, textColor=PINE, alignment=TA_CENTER)),
            Paragraph(step, styles["step"]),
        ])

    table = Table(rows, colWidths=[0.28 * inch, 6.95 * inch], hAlign="CENTER")
    table.setStyle(TableStyle([
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("BACKGROUND", (0, 0), (0, -1), CREAM),
        ("BOX", (0, 0), (-1, -1), 0.45, colors.HexColor("#C9D8C4")),
        ("INNERGRID", (0, 0), (-1, -1), 0.25, colors.HexColor("#DCE6D8")),
        ("LEFTPADDING", (0, 0), (-1, -1), 5),
        ("RIGHTPADDING", (0, 0), (-1, -1), 5),
        ("TOPPADDING", (0, 0), (-1, -1), 3),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 3),
    ]))

    finish = Table([[Paragraph("SAVE  >  CLEAR APPLICABLE CACHE  >  VERIFY LIVE", styles["finish"])]], colWidths=[7.23 * inch])
    finish.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, -1), PINE),
        ("BOX", (0, 0), (-1, -1), 1.2, HONEY),
        ("TOPPADDING", (0, 0), (-1, -1), 7),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 7),
    ]))

    story = [
        Paragraph("REBEKAH'S WEBSITE EDITING LIBRARY", ParagraphStyle("eyebrow", fontName="Helvetica-Bold", fontSize=8.5, leading=10, textColor=HONEY, alignment=TA_CENTER)),
        Paragraph(title, styles["title"]),
        Paragraph(access, styles["access"]),
        Spacer(1, 0.12 * inch),
        table,
        Spacer(1, 0.09 * inch),
        Paragraph(note, styles["note"]),
        Spacer(1, 0.09 * inch),
        finish,
        Spacer(1, 0.07 * inch),
        Paragraph("Revision v1.1 - September 1, 2026", styles["footer"]),
    ]
    doc.build(story)
    return path


practitioner = build(
    "How-to-Add-a-New-Practitioner-or-Practice-v1.1-Checklist.pdf",
    "Add a New Practitioner or Practice Listing",
    "Required access: WordPress Editor or Administrator. Editor is enough.",
    [
        "Go to <b>rebekahspureliving.com/wp-admin</b> and sign in with your own account.",
        "Select <b>Medical Practicioners</b>, then <b>All Medical Practicioners</b>.",
        "Select <b>Add New Medical Practitioner</b> once.",
        "Keep the new record as a <b>Draft</b> until every field is complete.",
        "Open <b>Medical Practicioner fields</b> under Meta Boxes. Stop if the fields are missing.",
        "Choose only the approved <b>Medical Service</b> category or categories.",
        "Enter the approved <b>Medical Center</b> and exact public name.",
        "Add the approved description in <b>Visual</b> mode and preserve paragraph breaks.",
        "Add only approved public contact details. Test the phone, email, and website.",
        "Review every field against the approved information.",
        "Select <b>Publish</b> or <b>Save</b> once and wait for confirmation.",
        "Clear only the applicable record, Breeze, or Varnish cache.",
        "Open <b>rebekahspureliving.com/practitioners/</b> with no extra address text.",
        "Search the name, practice, or city and verify every public detail and link.",
        "Open <b>View full listing</b> and repeat the check on a phone-size screen.",
    ],
    "Do not create unnecessary duplicate records for one practice or team. There is no separate public practitioner page to manage.",
)

event = build(
    "How-to-Create-or-Update-an-Event-v1.1-Checklist.pdf",
    "Create or Update an Event",
    "Required access: WordPress Editor or Administrator. Editor is enough.",
    [
        "Go to <b>rebekahspureliving.com/wp-admin</b> and sign in with your own account.",
        "Select <b>Events</b>. For a new event choose <b>Add New Event</b>; for an update choose the correct title and <b>Edit</b>.",
        "Keep a new event as a <b>Draft</b> until every required detail is complete.",
        "Enter the exact approved title and complete description in <b>Visual</b> mode.",
        "Choose the correct store category: Clarkston, Grand Blanc, Lake Orion, or Lapeer.",
        "Enter the start and end dates and times. Use <b>All Day</b> only when appropriate.",
        "Confirm the <b>America/Detroit</b> time zone.",
        "Choose an existing venue and organizer when possible.",
        "Add the cost, external event website when needed, and a 16:9 featured image with alt text.",
        "Preview the event at desktop and phone size. Fix every incorrect or missing detail.",
        "Select <b>Publish</b> or <b>Update</b> once and wait for confirmation.",
        "For a cancellation, set <b>Events Status</b> to Canceled, add a clear note, and update it. Do not immediately delete it.",
        "Clear only the applicable page, record, Breeze, or Varnish cache.",
        "Open <b>rebekahspureliving.com/events/</b> with no extra address text.",
        "Find and open the event. Check the title, store, date, time, image, details, and every public link.",
        "Repeat the check on a phone-size screen and confirm nothing runs off the side.",
    ],
    "This lesson does not include user-account management, event tickets, or RSVP setup.",
)

print(practitioner)
print(event)
