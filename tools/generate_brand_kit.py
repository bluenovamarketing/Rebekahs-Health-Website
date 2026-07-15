from pathlib import Path
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.lib.colors import HexColor, white
from PIL import Image, ImageDraw, ImageFont
import json, shutil

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "output" / "brand-kit"
PDF = ROOT / "output" / "pdf" / "Rebekahs-Health-Nutrition-Brand-Guide.pdf"
LOGOS = OUT / "assets" / "logos"
SOCIAL = OUT / "assets" / "social"
PREV = OUT / "assets" / "previews"
TOKENS = OUT / "tokens"
for p in (LOGOS, SOCIAL, PREV, TOKENS): p.mkdir(parents=True, exist_ok=True)

COLORS = {
    "Pine": "#174C3C", "Leaf": "#3F7D50", "Sage": "#A9C3A0",
    "Honey": "#D6A33A", "Berry": "#8E4B61", "Cream": "#F7F3E8",
    "Mist": "#E8EFEA", "Ink": "#26342E", "White": "#FFFFFF"
}
PINE, LEAF, SAGE, HONEY, BERRY, CREAM, MIST, INK = [COLORS[x] for x in ("Pine","Leaf","Sage","Honey","Berry","Cream","Mist","Ink")]
FONT_DIR = Path(r"C:\Windows\Fonts")
pdfmetrics.registerFont(TTFont("Georgia", str(FONT_DIR/"georgia.ttf")))
pdfmetrics.registerFont(TTFont("Georgia-Bold", str(FONT_DIR/"georgiab.ttf")))
pdfmetrics.registerFont(TTFont("Segoe", str(FONT_DIR/"segoeui.ttf")))
pdfmetrics.registerFont(TTFont("Segoe-Bold", str(FONT_DIR/"segoeuib.ttf")))

def svg_logo(path, mode="horizontal", reverse=False):
    fg = "#FFFFFF" if reverse else PINE
    leaf = SAGE if reverse else LEAF
    honey = "#F2CF78" if reverse else HONEY
    if mode == "mark":
        body = f'''<path d="M50 84 C48 61 52 41 65 22" fill="none" stroke="{fg}" stroke-width="7" stroke-linecap="round"/><path d="M63 26 C69 8 87 8 93 11 C91 28 79 38 63 34Z" fill="{leaf}"/><path d="M52 48 C42 29 24 31 17 35 C22 52 35 61 53 57Z" fill="{honey}"/><path d="M47 76 C57 64 70 60 82 61" fill="none" stroke="{fg}" stroke-width="5" stroke-linecap="round"/>'''
        vb="0 0 110 100"
    elif mode == "stacked":
        body = f'''<g transform="translate(145 8) scale(.72)"><path d="M50 84 C48 61 52 41 65 22" fill="none" stroke="{fg}" stroke-width="7" stroke-linecap="round"/><path d="M63 26 C69 8 87 8 93 11 C91 28 79 38 63 34Z" fill="{leaf}"/><path d="M52 48 C42 29 24 31 17 35 C22 52 35 61 53 57Z" fill="{honey}"/></g><text x="200" y="115" text-anchor="middle" font-family="Georgia" font-weight="700" font-size="38" fill="{fg}">Rebekah's</text><text x="200" y="145" text-anchor="middle" font-family="Segoe UI" font-weight="700" letter-spacing="4" font-size="13" fill="{fg}">HEALTH &amp; NUTRITION</text>'''
        vb="0 0 400 170"
    else:
        body = f'''<g transform="translate(3 4) scale(.82)"><path d="M50 84 C48 61 52 41 65 22" fill="none" stroke="{fg}" stroke-width="7" stroke-linecap="round"/><path d="M63 26 C69 8 87 8 93 11 C91 28 79 38 63 34Z" fill="{leaf}"/><path d="M52 48 C42 29 24 31 17 35 C22 52 35 61 53 57Z" fill="{honey}"/></g><text x="95" y="61" font-family="Georgia" font-weight="700" font-size="39" fill="{fg}">Rebekah's</text><text x="98" y="88" font-family="Segoe UI" font-weight="700" letter-spacing="3" font-size="13" fill="{fg}">HEALTH &amp; NUTRITION</text>'''
        vb="0 0 390 105"
    path.write_text(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="{vb}" role="img" aria-label="Rebekah’s Health and Nutrition logo">{body}</svg>', encoding="utf-8")

for name, mode, rev in [
    ("rebekahs-primary-horizontal.svg","horizontal",False),("rebekahs-primary-stacked.svg","stacked",False),
    ("rebekahs-mark.svg","mark",False),("rebekahs-horizontal-white.svg","horizontal",True),("rebekahs-mark-white.svg","mark",True)]:
    svg_logo(LOGOS/name, mode, rev)
shutil.copy2(ROOT/"tmp"/"source"/"current-logo.png", LOGOS/"legacy-current-logo.png")

def fonts(sz, bold=False, serif=False):
    f = "georgiab.ttf" if serif and bold else "georgia.ttf" if serif else "segoeuib.ttf" if bold else "segoeui.ttf"
    return ImageFont.truetype(str(FONT_DIR/f), sz)

def draw_mark(d, box, bg=PINE):
    x,y,s=box
    d.ellipse((x+s*.52,y+s*.05,x+s*.91,y+s*.40), fill=LEAF)
    d.ellipse((x+s*.12,y+s*.31,x+s*.55,y+s*.62), fill=HONEY)
    d.line((x+s*.52,y+s*.86,x+s*.52,y+s*.48,x+s*.67,y+s*.19), fill=bg, width=max(3,int(s*.07)), joint="curve")
    d.arc((x+s*.38,y+s*.55,x+s*.86,y+s*.94),210,300,fill=bg,width=max(2,int(s*.045)))

def brand_board():
    im=Image.new("RGB",(1600,1000),CREAM); d=ImageDraw.Draw(im)
    d.rectangle((0,0,1600,260), fill=PINE); draw_mark(d,(95,52,150),CREAM)
    d.text((280,65),"Rebekah's",font=fonts(66,True,True),fill=CREAM)
    d.text((284,144),"HEALTH & NUTRITION",font=fonts(23,True),fill=CREAM)
    d.text((830,65),"Brand Kit",font=fonts(70,True),fill=CREAM)
    d.text((834,150),"Own your everyday wellness.",font=fonts(30),fill="#DDE8D9")
    names=[("Pine",PINE),("Leaf",LEAF),("Sage",SAGE),("Honey",HONEY),("Berry",BERRY),("Cream",CREAM)]
    for i,(n,c) in enumerate(names):
        x=85+i*250; d.rounded_rectangle((x,330,x+205,505),18,fill=c,outline="#D7DED7",width=2)
        light=c in (PINE,LEAF,BERRY); tc="#FFFFFF" if light else INK
        d.text((x+20,365),n,font=fonts(24,True),fill=tc); d.text((x+20,415),c,font=fonts(22),fill=tc)
    d.text((85,590),"Core idea",font=fonts(31,True,True),fill=PINE)
    d.text((85,642),"Trusted guidance for confident, everyday wellness choices.",font=fonts(29),fill=INK)
    d.text((85,730),"Voice",font=fonts(31,True,True),fill=PINE)
    d.text((85,782),"Warm · knowledgeable · empowering · grounded · clear",font=fonts(29),fill=INK)
    d.text((85,870),"Type",font=fonts(31,True,True),fill=PINE)
    d.text((230,865),"Georgia",font=fonts(40,True,True),fill=PINE); d.text((515,874),"+  Segoe UI",font=fonts(31),fill=INK)
    im.save(PREV/"brand-board.png", quality=95)
brand_board()

def social_avatar(size=1080):
    im=Image.new("RGB",(size,size),PINE); d=ImageDraw.Draw(im); draw_mark(d,(190,170,700),CREAM)
    im.save(SOCIAL/"profile-avatar-1080.png")
social_avatar()

def social_cover():
    im=Image.new("RGB",(1640,624),CREAM); d=ImageDraw.Draw(im)
    d.rectangle((0,0,520,624),fill=PINE); draw_mark(d,(115,95,300),CREAM)
    d.text((610,132),"Own your everyday",font=fonts(55,True,True),fill=PINE)
    d.text((610,205),"wellness.",font=fonts(55,True,True),fill=PINE)
    d.text((615,320),"Trusted products. Knowledgeable people.",font=fonts(28),fill=INK)
    d.text((615,365),"Four Michigan locations + online shopping.",font=fonts(28),fill=INK)
    d.rounded_rectangle((615,455,1005,515),16,fill=HONEY); d.text((656,467),"rebekahspureliving.com",font=fonts(23,True),fill=PINE)
    im.save(SOCIAL/"facebook-cover-1640x624.png")
social_cover()

def youtube_banner():
    im=Image.new("RGB",(2560,1440),PINE); d=ImageDraw.Draw(im)
    # Keep all essential copy inside YouTube's cross-device center safe area.
    d.rounded_rectangle((660,535,1900,905),28,fill=CREAM)
    draw_mark(d,(735,615,210),PINE)
    d.text((1010,610),"Own your everyday",font=fonts(55,True,True),fill=PINE)
    d.text((1010,675),"wellness.",font=fonts(55,True,True),fill=PINE)
    d.text((1012,765),"Education · Products · Community",font=fonts(28,True),fill=LEAF)
    d.text((1012,810),"rebekahspureliving.com",font=fonts(25),fill=INK)
    im.save(SOCIAL/"youtube-channel-banner-2560x1440.png")
youtube_banner()

def youtube_watermark():
    im=Image.new("RGBA",(150,150),(0,0,0,0)); d=ImageDraw.Draw(im); d.ellipse((8,8,142,142),fill=PINE); draw_mark(d,(35,31,82),CREAM)
    im.save(SOCIAL/"youtube-video-watermark-150.png")
youtube_watermark()

def square_post():
    im=Image.new("RGB",(1080,1080),CREAM); d=ImageDraw.Draw(im)
    d.rectangle((0,0,1080,135),fill=PINE); d.text((62,43),"WELLNESS, MADE CLEARER",font=fonts(27,True),fill=CREAM)
    d.text((70,235),"Helpful headline",font=fonts(58,True,True),fill=PINE)
    d.text((70,315),"goes here.",font=fonts(58,True,True),fill=PINE)
    d.rounded_rectangle((70,445,1010,825),28,fill=MIST); d.text((125,595),"PHOTO / PRODUCT / TIP",font=fonts(35,True),fill=LEAF)
    d.text((70,945),"@rebekahspureliving",font=fonts(25,True),fill=PINE); draw_mark(d,(900,910,95),PINE)
    im.save(SOCIAL/"instagram-square-post-template-1080.png")
square_post()

def vertical_template():
    im=Image.new("RGB",(1080,1920),PINE); d=ImageDraw.Draw(im)
    d.rounded_rectangle((65,170,1015,1715),35,fill=CREAM)
    d.text((120,265),"Everyday wellness",font=fonts(58,True,True),fill=PINE)
    d.text((120,340),"starts here.",font=fonts(58,True,True),fill=PINE)
    d.rounded_rectangle((120,500,960,1280),28,fill=MIST); d.text((260,865),"VIDEO / PHOTO",font=fonts(42,True),fill=LEAF)
    d.text((120,1410),"Add one practical takeaway",font=fonts(34,True),fill=INK)
    d.text((120,1470),"and one clear next step.",font=fonts(34),fill=INK)
    d.rounded_rectangle((120,1575,650,1655),20,fill=HONEY); d.text((175,1595),"VISIT · SAVE · ASK · SHOP",font=fonts(24,True),fill=PINE)
    im.save(SOCIAL/"instagram-story-template-1080x1920.png")
    im.save(SOCIAL/"tiktok-video-cover-template-1080x1920.png")
vertical_template()

for platform in ("instagram","tiktok","youtube"):
    shutil.copy2(SOCIAL/"profile-avatar-1080.png", SOCIAL/f"{platform}-profile-avatar-1080.png")

def highlight(label, color):
    im=Image.new("RGB",(1080,1080),CREAM); d=ImageDraw.Draw(im); d.ellipse((165,165,915,915),fill=color); draw_mark(d,(365,280,350),CREAM)
    d.text((540,825),label.upper(),anchor="mm",font=fonts(34,True),fill=PINE); return im
for label,color in [("Shop",HONEY),("Learn",SAGE),("Events",BERRY),("Stores",LEAF),("Team",PINE)]:
    highlight(label,color).save(SOCIAL/f"instagram-highlight-{label.lower()}-1080.png")

(TOKENS/"brand-tokens.css").write_text('''/* Rebekah's Health & Nutrition - brand tokens */
:root {
  --brand-pine: #174C3C; --brand-leaf: #3F7D50; --brand-sage: #A9C3A0;
  --brand-honey: #D6A33A; --brand-berry: #8E4B61; --brand-cream: #F7F3E8;
  --brand-mist: #E8EFEA; --brand-ink: #26342E; --brand-white: #FFFFFF;
  --font-display: Georgia, 'Times New Roman', serif;
  --font-body: 'Segoe UI', Arial, sans-serif;
  --radius-sm: 8px; --radius-md: 16px; --radius-pill: 999px;
  --shadow-soft: 0 8px 30px rgba(23,76,60,.12);
  --space-1: .25rem; --space-2: .5rem; --space-3: .75rem; --space-4: 1rem;
  --space-6: 1.5rem; --space-8: 2rem; --space-12: 3rem; --space-16: 4rem;
}
''',encoding="utf-8")
(TOKENS/"brand-tokens.json").write_text(json.dumps({"color":COLORS,"font":{"display":"Georgia","body":"Segoe UI"},"radius":{"sm":"8px","md":"16px","pill":"999px"}},indent=2),encoding="utf-8")

W,H=letter
def hexcol(h): return HexColor(h)
def txt(c,x,y,s,text,font="Segoe",color=INK): c.setFont(font,s); c.setFillColor(hexcol(color)); c.drawString(x,y,text)
def wrap(c,text,x,y,w,size=10,leading=14,font="Segoe",color=INK):
    words=text.split(); lines=[]; line=""
    for word in words:
        test=(line+" "+word).strip()
        if c.stringWidth(test,font,size)<=w: line=test
        else: lines.append(line); line=word
    if line: lines.append(line)
    for line in lines: txt(c,x,y,size,line,font,color); y-=leading
    return y
def header(c,n,title,kicker):
    c.setFillColor(hexcol(PINE)); c.rect(0,H-86,W,86,fill=1,stroke=0)
    txt(c,42,H-35,9,f"REBEKAH'S HEALTH & NUTRITION  /  BRAND GUIDE  /  {n:02d}","Segoe-Bold",CREAM)
    txt(c,42,H-68,22,title,"Georgia-Bold",CREAM); txt(c,410,H-64,9,kicker.upper(),"Segoe-Bold",SAGE)
def footer(c,n): txt(c,42,25,8,"Version 1.0  |  July 2026", "Segoe", "#66756E"); txt(c,545,25,8,str(n),"Segoe-Bold",PINE)
def pill(c,x,y,w,label,fill=PINE,color=CREAM): c.setFillColor(hexcol(fill)); c.roundRect(x,y,w,25,12,fill=1,stroke=0); txt(c,x+10,y+8,8,label,"Segoe-Bold",color)

c=canvas.Canvas(str(PDF),pagesize=letter)
# 1 cover
c.setFillColor(hexcol(CREAM)); c.rect(0,0,W,H,fill=1,stroke=0); c.setFillColor(hexcol(PINE)); c.rect(0,H-310,W,310,fill=1,stroke=0)
txt(c,48,H-74,11,"COMPLETE BRAND KIT  /  2026","Segoe-Bold",SAGE); txt(c,48,H-145,45,"Rebekah's","Georgia-Bold",CREAM); txt(c,50,H-183,19,"HEALTH & NUTRITION","Segoe-Bold",CREAM)
txt(c,48,H-250,22,"Own your everyday wellness.","Georgia",HONEY)
txt(c,48,415,20,"A warmer, clearer system for trusted wellness retail.","Georgia-Bold",PINE)
wrap(c,"This guide turns nearly two decades of local trust into a practical identity for stores, ecommerce, education, events, and social media.",48,372,500,12,18)
for i,(a,b) in enumerate([("POSITIONING","Trusted guidance"),("PERSONALITY","Warm + grounded"),("DESIGN","Botanical + modern"),("MARKET","Four Michigan communities")]):
    y=265-i*46; txt(c,48,y,8,a,"Segoe-Bold",LEAF); txt(c,168,y,11,b,"Segoe",INK)
footer(c,1); c.showPage()
# 2 strategy
header(c,2,"Brand strategy","Foundation");
txt(c,42,665,20,"Core brand idea","Georgia-Bold",PINE); txt(c,42,632,18,"Wellness feels possible when guidance feels personal.","Georgia",BERRY)
txt(c,42,580,12,"POSITIONING","Segoe-Bold",LEAF); wrap(c,"Rebekah's is the approachable Michigan wellness destination for people who want carefully selected products and real human guidance - without feeling overwhelmed, judged, or left to figure it out alone.",42,557,520,10.5,15)
txt(c,42,480,12,"PROMISE","Segoe-Bold",LEAF); wrap(c,"We help people make informed, practical choices that support their everyday wellness.",42,457,520,10.5,15)
txt(c,42,402,12,"MISSION","Segoe-Bold",LEAF); wrap(c,"Make trusted natural wellness products, education, and encouragement easier to access in every community we serve.",42,379,520,10.5,15)
txt(c,42,323,12,"AUDIENCE","Segoe-Bold",LEAF)
for i,t in enumerate(["Everyday wellness seekers who want clarity and confidence","Customers managing changing routines, energy, stress, or nutrition goals","Families who value ingredient quality, local relationships, and practical support","Practitioners and informed shoppers seeking professional-grade options"]):
    txt(c,52,295-i*30,9,"•", "Segoe-Bold",HONEY); txt(c,70,295-i*30,9.5,t,"Segoe",INK)
footer(c,2); c.showPage()
# 3 identity
header(c,3,"Visual identity","Color + typography"); txt(c,42,665,20,"Color system","Georgia-Bold",PINE)
cols=list(COLORS.items())[:8]
for i,(n,h) in enumerate(cols):
    x=42+(i%4)*137; y=570-(i//4)*105; c.setFillColor(hexcol(h)); c.roundRect(x,y,118,70,8,fill=1,stroke=0)
    tc=CREAM if n in ("Pine","Leaf","Berry","Ink") else INK; txt(c,x+9,y+40,10,n,"Segoe-Bold",tc); txt(c,x+9,y+20,8,h,"Segoe",tc)
txt(c,42,430,20,"Typography","Georgia-Bold",PINE); txt(c,42,388,27,"Georgia Bold","Georgia-Bold",PINE); txt(c,310,395,13,"Display / editorial warmth","Segoe",INK)
txt(c,42,342,22,"Segoe UI Semibold","Segoe-Bold",INK); txt(c,310,348,13,"UI / navigation / labels","Segoe",INK)
wrap(c,"Use Georgia for headlines, pull quotes, campaign lines, and editorial moments. Use Segoe UI for body copy, product information, buttons, prices, forms, and operational content. Web fallback: Georgia -> serif; Segoe UI -> Arial -> sans-serif.",42,290,520,10,15)
txt(c,42,185,12,"ACCESSIBILITY","Segoe-Bold",LEAF); wrap(c,"Use Pine or Ink text on Cream, Mist, or White. Use White or Cream text on Pine. Honey is an accent, not a body-text color. Avoid Sage text on Cream and Berry text on Pine.",42,160,520,9.5,14)
footer(c,3); c.showPage()
# 4 logo
header(c,4,"Logo system","Recognition + flexibility"); txt(c,42,665,20,"Recommended direction","Georgia-Bold",PINE)
wrap(c,"Retain the name recognition and botanical equity of the current logo, while simplifying the sprout into a scalable mark and removing visual clutter. The refreshed system supports horizontal, stacked, icon, and reverse applications.",42,637,520,10.5,15)
c.setFillColor(hexcol(MIST)); c.roundRect(42,470,520,120,12,fill=1,stroke=0); txt(c,78,535,34,"Rebekah's","Georgia-Bold",PINE); txt(c,81,505,11,"HEALTH & NUTRITION","Segoe-Bold",PINE); pill(c,390,514,135,"PRIMARY LOCKUP",HONEY,PINE)
txt(c,42,420,12,"CLEAR SPACE","Segoe-Bold",LEAF); wrap(c,"Keep clear space equal to the cap height of the letter H around every lockup. Minimum digital width: 220 px horizontal, 120 px stacked, 32 px mark.",42,397,520,9.5,14)
txt(c,42,326,12,"APPROVED","Segoe-Bold",LEAF)
for i,t in enumerate(["Pine/Leaf/Honey on Cream or White","White reverse on Pine or dark photography","One-color Pine for forms, labels, stamps, and embroidery"]): txt(c,55,300-i*26,9.5,"•  "+t,"Segoe",INK)
txt(c,310,326,12,"DO NOT","Segoe-Bold",BERRY)
for i,t in enumerate(["Stretch, rotate, outline, or add effects","Recolor leaves with unrelated hues","Place over busy imagery without a field","Use the legacy logo beside the refresh"]): txt(c,323,300-i*26,9.5,"•  "+t,"Segoe",INK)
txt(c,42,165,11,"TRANSITION NOTE","Segoe-Bold",LEAF); wrap(c,"The supplied assets are a polished direction, not a trademark clearance. Confirm the final naming hierarchy ('Health & Nutrition' vs. legacy 'Source') and complete legal review before permanent signage or packaging production.",42,140,520,9,13)
footer(c,4); c.showPage()
# 5 voice
header(c,5,"Voice + messaging","Warm expertise"); txt(c,42,665,20,"Voice principles","Georgia-Bold",PINE)
items=[("Warm, not sugary","Speak person to person. Welcome questions and acknowledge uncertainty."),("Knowledgeable, not clinical","Explain clearly. Do not diagnose, prescribe, or imply guaranteed outcomes."),("Empowering, not alarming","Offer choices and next steps without fear-based urgency."),("Grounded, not trendy","Connect recommendations to quality, routine, education, and responsible use.")]
for i,(a,b) in enumerate(items):
    y=620-i*77; pill(c,42,y,150,a.upper(),PINE,CREAM); wrap(c,b,210,y+6,350,9.5,14)
txt(c,42,296,12,"MESSAGE HOUSE","Segoe-Bold",LEAF); txt(c,42,267,16,"Own your everyday wellness.","Georgia-Bold",PINE)
wrap(c,"Support: Carefully selected products. Knowledgeable people. Practical education. Local relationships. Online convenience.",42,239,520,10,15)
txt(c,42,180,11,"CLAIM-SAFE LANGUAGE","Segoe-Bold",LEAF); wrap(c,"Prefer: supports, may help maintain, designed for, part of a wellness routine, talk with a qualified professional. Avoid: cures, treats, prevents, reverses, guaranteed, miracle, doctor-approved unless substantiated and permitted.",42,155,520,9.2,13)
footer(c,5); c.showPage()
# 6 web/social
header(c,6,"Digital application","Web + social"); txt(c,42,665,20,"Practical rules","Georgia-Bold",PINE)
txt(c,42,628,12,"WEB UI","Segoe-Bold",LEAF); wrap(c,"Primary buttons: Honey field with Pine text. Secondary buttons: Pine outline. Use Cream as the default canvas, Mist for section bands, Pine for navigation/footer, and Berry sparingly for editorial emphasis. Keep product imagery bright, honest, and consistent.",42,605,520,9.5,14)
txt(c,42,515,12,"SOCIAL PROFILE SYSTEM","Segoe-Bold",LEAF)
for i,t in enumerate(["Avatar: leaf-and-path mark only; never squeeze the full wordmark into a circle.","Cover: one promise, one proof line, one URL. Keep text centered in platform safe zones.","Post mix: 40% education, 25% product guidance, 20% community/events, 15% founder/team.","Photography: real staff, customers with consent, product texture, Michigan seasons, warm daylight.","Caption formula: helpful hook -> plain-language insight -> practical next step -> measured CTA."]): txt(c,52,488-i*29,9.3,"•  "+t,"Segoe",INK)
txt(c,42,315,12,"READY-TO-USE BIOS","Segoe-Bold",LEAF); txt(c,42,286,10,"Short:","Segoe-Bold",PINE); wrap(c,"Trusted wellness products + personal guidance. Four Michigan locations. Shop online.",90,286,470,9.5,14)
txt(c,42,231,10,"Instagram:","Segoe-Bold",PINE); wrap(c,"Helping Michigan own everyday wellness since 2007. Trusted supplements, natural products & real guidance. Lapeer | Clarkston | Grand Blanc | Lake Orion.",110,231,450,9.5,14)
txt(c,42,152,10,"CTA bank:","Segoe-Bold",PINE); wrap(c,"Explore the collection. Ask our team. Save this for your next visit. Find your nearest store. Build a routine that fits real life.",105,152,455,9.5,14)
footer(c,6); c.showPage()
# 7 implementation
header(c,7,"Implementation","Handoff checklist"); txt(c,42,665,20,"What is included","Georgia-Bold",PINE)
for i,t in enumerate(["7-page client-facing brand guide (PDF)","One-page brand board (PNG)","Horizontal, stacked, mark, and reverse logo directions (SVG)","Social avatar and Facebook cover (PNG)","CSS and JSON design tokens","Legacy logo archived for transition reference"]): txt(c,55,625-i*31,10,"•  "+t,"Segoe",INK)
txt(c,42,405,12,"90-DAY ROLLOUT","Segoe-Bold",LEAF)
steps=[("1. Confirm","Naming hierarchy, tagline, logo direction, and trademark review."),("2. Standardize","Website header, email signatures, profile images, bios, and location listings."),("3. Build","Reusable post, event, product, and educational templates."),("4. Extend","Packaging labels, store signage, uniforms, and campaign photography.")]
for i,(a,b) in enumerate(steps): y=365-i*56; txt(c,42,y,10,a,"Segoe-Bold",PINE); wrap(c,b,125,y,430,9.5,13)
txt(c,42,120,8,"SOURCE NOTE","Segoe-Bold",LEAF); wrap(c,"Strategy is based on public website content reviewed July 15, 2026, including the home, Our Story, Meet the Owner, and Locations pages. Business facts should be verified by the client before production.",42,100,520,7.8,11,color="#66756E")
footer(c,7); c.save()

print(PDF)
print(PREV/"brand-board.png")
