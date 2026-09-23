"""Build editable native WordPress page seeds from approved page inputs.

The legal source predates this WordPress receiver. Only the affected data-flow
sections are adapted; the remaining approved buyer copy is retained.
"""
from pathlib import Path
from html import escape
import json
import re

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'data' / 'utility'
OUT.mkdir(exist_ok=True)
SOURCE = ROOT / 'planning' / 'inputs' / 'pages'


def buyer(path, marker):
    text = path.read_text(encoding='utf-8')
    text = text.split(marker, 1)[1]
    return text.split('\n---\n\n## 2.', 1)[0].strip()


def section(text, title, replacement):
    pattern = re.compile(r'^## ' + re.escape(title) + r'\s*\n.*?(?=^## |\Z)', re.M | re.S)
    changed, count = pattern.subn(lambda _: '## ' + title + '\n\n' + replacement.strip() + '\n\n', text, count=1)
    assert count == 1, title
    return changed


def inline(text):
    escaped = escape(text)
    escaped = re.sub(r'`([^`]+)`', lambda m: '<code>' + m[1] + '</code>', escaped)
    escaped = re.sub(r'\*\*(.+?)\*\*', r'<strong>\1</strong>', escaped)
    escaped = re.sub(r'\[([^]]+)\]\((/[^)]+)\)', lambda m: '<a href="' + escape(m[2], quote=True) + '">' + m[1] + '</a>', escaped)
    return escaped.replace('  \n', '<br>')


def markdown(text):
    lines = text.splitlines(); result = []; i = 0
    while i < len(lines):
        line = lines[i].strip()
        if not line or line == '---': i += 1; continue
        if line.startswith('Actions:') or line.startswith('Tindakan:'):
            if 'READ OUR PRIVACY POLICY' in line:
                result.append('<div class="utility-actions">[tio2_cookie_settings primary="1"]<a class="button" href="/privacy-policy/">Read our Privacy Policy</a></div>')
            else:
                result.append('<div class="utility-actions"><a class="button primary" href="/contact/">' + ('Hubungi Kami Mengenai Privasi' if line.startswith('Tindakan:') else 'Contact us about privacy') + '</a>[tio2_cookie_settings' + (' lang="ms"' if line.startswith('Tindakan:') else '') + ']</div>')
            i += 1; continue
        if line.startswith('#'):
            level = len(line) - len(line.lstrip('#'))
            result.append(f'<h{level}>' + inline(line[level:].strip()) + f'</h{level}>'); i += 1; continue
        if line.startswith('- '):
            result.append('<ul>')
            while i < len(lines) and lines[i].strip().startswith('- '):
                result.append('<li>' + inline(lines[i].strip()[2:]) + '</li>'); i += 1
            result.append('</ul>'); continue
        if line.startswith('|'):
            rows = []
            while i < len(lines) and lines[i].strip().startswith('|'):
                row = [cell.strip() for cell in lines[i].strip().strip('|').split('|')]
                if not all(re.fullmatch(r':?-{3,}:?', cell) for cell in row): rows.append(row)
                i += 1
            result.append('<div class="utility-table-wrap"><table><thead><tr>' + ''.join('<th scope="col">' + inline(c) + '</th>' for c in rows[0]) + '</tr></thead><tbody>')
            for row in rows[1:]: result.append('<tr>' + ''.join('<td data-label="' + escape(rows[0][j], quote=True) + '">' + inline(c) + '</td>' for j, c in enumerate(row)) + '</tr>')
            result.append('</tbody></table></div>'); continue
        paragraph = [line]; i += 1
        while i < len(lines) and lines[i].strip() and not re.match(r'^(#|- |\|)', lines[i].strip()):
            paragraph.append(lines[i].strip()); i += 1
        result.append('<p>' + inline(' '.join(paragraph)) + '</p>')
    return '\n'.join(result)


en = buyer(SOURCE / 'legal-privacy/04_planning/LEGAL-PRIV-EN_GATE2_FULL_COPY_V0.2.md', '## 1. Buyer-visible copy')
en = en.replace('Last updated: 5 September 2026', 'Last updated: 23 September 2026')
en = section(en, 'Information We Collect', '''### Information you provide

The active general inquiry form asks for Full Name, Company, Business Email, Country / Region, Subject and Message. We use these details to review and respond to a general business question. The form does not request uploads, payment details or sensitive personal data. Please do not put these in the Message field.

The form is submitted to this WordPress site. A successful submission creates a private local inquiry record. A short-lived server-side result record may temporarily hold your entries after a validation or storage failure so you can correct and retry the form. No email is sent by this form.

Other request routes, including quotation, document and sample requests, may be linked on the site. Their collection and delivery arrangements must be confirmed separately before they are described as active here.

### Technical and usage information

Hosting and security systems may process an IP address, request time, URL, browser information and related technical data to deliver and protect the site. The general inquiry service uses a short-lived, pseudonymous rate-limit counter derived from the visitor IP address to reduce abuse; it does not add the IP address to the inquiry record.''')
en = section(en, 'How We Use Information', '''We use personal data to receive, review and respond to general business inquiries; maintain related correspondence; operate and secure the website; prevent abuse; and meet applicable legal obligations or handle legal claims.

The general inquiry form stores its submission locally for staff review. It does not send email, approve a quotation, release a document or approve a sample. Submitting an inquiry is not consent to unrelated promotional marketing. We do not use this form to make solely automated decisions with legal or similarly significant effects.''')
en = section(en, 'Service Providers and International Processing', '''The site uses WordPress and its hosting infrastructure to deliver pages and hold private inquiry records. Authorised IKHLAS personnel may access these records to handle inquiries. Hosting and operational providers may process the limited data needed to run, protect and maintain the site. The exact production provider locations and any international transfers must be verified before release; this local build does not establish them.

We may disclose information where required by applicable law or legal process, or where necessary to protect the service and legal rights. We do not sell information submitted through the general inquiry form. No Web3Forms, Google Analytics, Google Tag Manager, Google Ads or Vercel Web Analytics integration is active in this WordPress implementation.''')
en = section(en, 'How Long We Keep Information', '''The local general inquiry record is scheduled for deletion three years after submission. Related business correspondence, if any, is handled under applicable business retention controls. We may keep limited information longer when required for a legal obligation, security issue, dispute or claim.

Validation and confirmation state is stored server-side for up to 10 minutes. The browser session cookie described in the Cookie Policy expires when the browser session ends. The abuse-prevention counter expires after one hour. Hosting and security logs follow the applicable provider retention settings, which require verification before production release.''')
en = section(en, 'Cookies and Analytics', '''The general inquiry uses a necessary, random browser-session Cookie named `tio2_flow` to associate form validation and receipt state with the same browser. The Cookie does not contain your inquiry text or identity. This site does not currently use Local Storage for consent choices or activate optional Analytics or advertising technology. Cookie Settings shows this status; there is no optional category to accept or withdraw. See our [Cookie Policy](/cookie-policy/) for the active inventory and browser controls.''')
en = en.replace('You can change an available Analytics choice at any time through **Cookie Settings**. Withdrawing consent does not affect processing that was lawful before withdrawal.', 'There is currently no optional Analytics choice to change. You can review active storage through **Cookie Settings** and manage site data in your browser.')

ms = buyer(SOURCE / 'legal-privacy/04_planning/LEGAL-PRIV-MS_GATE2_FULL_COPY_V0.2.md', '## 1. Salinan yang boleh dilihat pembaca')
ms = ms.replace('Kemas kini terakhir: 5 September 2026', 'Kemas kini terakhir: 23 September 2026')
ms = section(ms, 'Maklumat yang Kami Kumpulkan', '''### Maklumat yang anda berikan

Borang pertanyaan umum yang aktif meminta Nama Penuh, Syarikat, E-mel Perniagaan, Negara / Wilayah, Subjek dan Mesej. Kami menggunakan maklumat ini untuk menyemak dan menjawab pertanyaan perniagaan umum. Borang ini tidak meminta muat naik fail, butiran pembayaran atau data peribadi sensitif. Jangan masukkan maklumat tersebut dalam medan Mesej.

Borang dihantar ke laman WordPress ini. Penghantaran yang berjaya mewujudkan rekod pertanyaan setempat yang bersifat peribadi. Rekod hasil sementara pada pelayan mungkin menyimpan entri anda seketika selepas ralat pengesahan atau penyimpanan supaya anda boleh membetulkan dan mencuba lagi. Borang ini tidak menghantar e-mel.

Laluan permintaan lain, termasuk sebut harga, dokumen dan sampel, mungkin dipautkan di laman ini. Pengumpulan dan penghantarannya perlu disahkan secara berasingan sebelum diterangkan sebagai aktif di sini.

### Maklumat teknikal dan penggunaan

Sistem pengehosan dan keselamatan mungkin memproses alamat IP, masa permintaan, URL, maklumat pelayar dan data teknikal berkaitan untuk menyampaikan dan melindungi laman. Perkhidmatan pertanyaan umum menggunakan pembilang had kadar sementara dan berpseudonim yang diperoleh daripada alamat IP pelawat untuk mengurangkan penyalahgunaan; alamat IP tidak ditambah pada rekod pertanyaan.''')
ms = section(ms, 'Cara Kami Menggunakan Maklumat', '''Kami menggunakan data peribadi untuk menerima, menyemak dan menjawab pertanyaan perniagaan umum; mengekalkan surat-menyurat berkaitan; mengendalikan dan melindungi laman web; mencegah penyalahgunaan; serta memenuhi kewajipan undang-undang yang terpakai atau mengendalikan tuntutan undang-undang.

Borang pertanyaan umum menyimpan penghantaran secara setempat untuk semakan kakitangan. Ia tidak menghantar e-mel, meluluskan sebut harga, menyerahkan dokumen atau meluluskan sampel. Menghantar pertanyaan bukan persetujuan untuk pemasaran promosi yang tidak berkaitan. Kami tidak menggunakan borang ini untuk membuat keputusan automatik sepenuhnya yang mempunyai kesan undang-undang atau kesan penting yang serupa.''')
ms = section(ms, 'Penyedia Perkhidmatan dan Pemprosesan Antarabangsa', '''Laman ini menggunakan WordPress dan infrastruktur pengehosannya untuk menyampaikan halaman dan menyimpan rekod pertanyaan peribadi. Kakitangan IKHLAS yang diberi kuasa boleh mengakses rekod ini untuk mengendalikan pertanyaan. Penyedia pengehosan dan operasi mungkin memproses data terhad yang diperlukan untuk menjalankan, melindungi dan menyelenggara laman. Lokasi tepat penyedia produksi dan sebarang pemindahan antarabangsa perlu disahkan sebelum penerbitan; binaan setempat ini tidak menetapkannya.

Kami mungkin mendedahkan maklumat apabila dikehendaki oleh undang-undang atau proses undang-undang yang terpakai, atau apabila perlu untuk melindungi perkhidmatan dan hak undang-undang. Kami tidak menjual maklumat yang dihantar melalui borang pertanyaan umum. Tiada integrasi Web3Forms, Google Analytics, Google Tag Manager, Google Ads atau Vercel Web Analytics yang aktif dalam pelaksanaan WordPress ini.''')
ms = section(ms, 'Tempoh Kami Menyimpan Maklumat', '''Rekod pertanyaan umum setempat dijadualkan untuk dipadam tiga tahun selepas penghantaran. Surat-menyurat perniagaan berkaitan, jika ada, diurus mengikut kawalan penyimpanan perniagaan yang terpakai. Kami mungkin menyimpan maklumat terhad lebih lama apabila diperlukan bagi kewajipan undang-undang, isu keselamatan, pertikaian atau tuntutan.

Keadaan pengesahan dan pengesahan penerimaan disimpan pada pelayan sehingga 10 minit. Kuki sesi pelayar yang diterangkan dalam Cookie Policy tamat apabila sesi pelayar berakhir. Pembilang pencegahan penyalahgunaan tamat selepas satu jam. Log pengehosan dan keselamatan mengikut tetapan penyimpanan penyedia yang berkenaan, yang perlu disahkan sebelum penerbitan produksi.''')
ms = section(ms, 'Kuki dan Analitik', '''Pertanyaan umum menggunakan Kuki sesi pelayar rawak yang diperlukan, bernama `tio2_flow`, untuk mengaitkan keadaan pengesahan borang dan penerimaan dengan pelayar yang sama. Kuki ini tidak mengandungi teks pertanyaan atau identiti anda. Laman ini pada masa ini tidak menggunakan Local Storage untuk pilihan persetujuan atau mengaktifkan teknologi Analitik atau pengiklanan pilihan. Cookie Settings menunjukkan keadaan ini; tiada kategori pilihan untuk diterima atau ditarik balik. Lihat [Cookie Policy](/cookie-policy/) kami untuk inventori aktif dan kawalan pelayar.''')
ms = ms.replace('Anda boleh menukar pilihan Analitik yang tersedia pada bila-bila masa melalui **Cookie Settings**. Penarikan balik persetujuan tidak menjejaskan pemprosesan yang sah sebelum persetujuan ditarik balik.', 'Pada masa ini tiada pilihan Analitik untuk diubah. Anda boleh menyemak storan aktif melalui **Cookie Settings** dan mengurus data laman dalam pelayar anda.')

cookie = buyer(SOURCE / 'legal-privacy/04_planning/LEGAL-COOKIE-EN_GATE2_FULL_COPY_V0.2.md', '## 1. Buyer-visible copy — current no-Analytics state')
cookie = cookie.replace('Last updated: 2 September 2026', 'Last updated: 23 September 2026')
cookie = section(cookie, 'Categories We Use', '''### Necessary

Necessary technologies support site delivery, security and the general inquiry workflow. The site sets a random session Cookie when the Contact page or its form is used. WordPress may use authentication Cookies for administrators who log in. These do not enable optional Analytics or advertising.

### Analytics

No optional Analytics technology is active. Google Analytics, Google Tag Manager and Vercel Web Analytics are not active in this implementation.

### Advertising and personalisation

No advertising or advertising-personalisation technology is active. Google Ads is not enabled.''')
cookie = section(cookie, 'Current Cookie and Storage Inventory', '''| Name | Provider | Type | Purpose | Duration | Category |
|---|---|---|---|---|---|
| `tio2_flow` | TiO2 Malaysia | First-party HttpOnly Cookie | Binds Contact form errors or receipt state to one browser; also binds any future receiver-confirmed Thank You state | Browser session | Necessary |

The Cookie contains a random value, not a name, email address or inquiry text. Contact form validation and receipt data are held temporarily on the server for up to 10 minutes. A pseudonymous rate-limit counter is held for one hour. This site does not set a consent Local Storage record and does not set optional Analytics or advertising Cookies in the current implementation. WordPress administrator login may use separate authentication Cookies.''')
cookie = section(cookie, 'How Advanced Consent Mode Works', '''Google measurement and Advanced Consent Mode are not active in this WordPress implementation. No Google measurement transmission or consent state should be inferred from a future implementation direction. If optional measurement is introduced, this policy and the controls will be updated after the actual technology and network behaviour are verified.''')
cookie = section(cookie, 'Manage or Withdraw Your Choice', '''Use **Cookie Settings** in the Footer to review the current status. There is no optional Analytics or advertising category to accept or withdraw, so the interface does not show a first-visit consent request. You can remove the necessary `tio2_flow` Cookie through your browser's site-data controls; doing so clears the browser link to a pending form result.''')
cookie = section(cookie, 'Browser Controls', '''Most browsers allow you to view, delete or block Cookies and site data. Blocking the `tio2_flow` Cookie may prevent a Contact form result from being associated with your browser. Browser controls operate separately from Cookie Settings, which currently displays status only. Refer to your browser's help information for instructions.''')

def save(page_id, slug, title, seo_title, seo_description, content, parent=''):
    # The approved legacy copy predates the confirmed public mailbox domain.
    content = content.replace('info@tio2malaysia.com', 'info@tio2products.com')
    data = dict(page_id=page_id, slug=slug, parent=parent, title=title, seo_title=seo_title,
                seo_description=seo_description, content=content)
    (OUT / (page_id + '.json')).write_text(json.dumps(data, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')

save('LEGAL-PRIV-EN','privacy-policy','Privacy Policy','Privacy Policy | TiO2 Malaysia','Learn how TiO2 Malaysia handles general business inquiry data, retention, necessary Cookies and privacy requests.', markdown(en))
save('LEGAL-PRIV-MS','privacy-policy','Dasar Privasi','Dasar Privasi | TiO2 Malaysia','Ketahui cara TiO2 Malaysia mengendalikan data pertanyaan perniagaan umum, tempoh penyimpanan, Kuki yang diperlukan dan permintaan privasi.', markdown(ms), 'ms')
save('LEGAL-COOKIE-EN','cookie-policy','Cookie Policy','Cookie Policy | TiO2 Malaysia','Learn which necessary Cookies TiO2 Malaysia uses for its contact form and how to review browser storage.', markdown(cookie))

contact = '''<section class="utility-hero"><div class="wrap utility-hero-inner"><p class="eyebrow">General contact</p><h1>Contact TiO2 Malaysia</h1><p class="lead">Use this page for a general question about the company, a partnership or another business matter. For a quotation, product documents or a sample, choose the relevant dedicated request route below.</p><a class="button primary" href="#general-inquiry">Send a General Inquiry</a></div></section>
<section class="wrap"><h2>General contact details</h2><p>For a general question about the company or another business matter, use the form on this page. You can also find the General Inquiries email, Operating Company and Manufacturing Site below.</p><div class="utility-card-grid"><div class="utility-card"><h3>General Inquiries</h3><a href="mailto:info@tio2products.com">info@tio2products.com</a></div><div class="utility-card"><h3>Operating Company</h3><p>IKHLAS TITANIUM (MALAYSIA) SDN. BHD.</p></div><div class="utility-card"><h3>Manufacturing Site</h3><p>NO.33 Industrial Perusahaan Ringan Tupai, 34000 Taiping, Perak, Malaysia</p></div></div></section>
<section class="wrap"><h2>Choose a dedicated request when you need one</h2><p>Requests for quotations, product documents and samples have their own routes. Choose the option that matches your task.</p><div class="utility-card-grid"><div class="utility-card"><h3>Request a Quote</h3><p>Share the commercial and product information needed to review a quotation request.</p><a href="/request-a-quote/">Request a Quote</a></div><div class="utility-card"><h3>Request Documents</h3><p>Identify the product documents you need and provide the context for your request.</p><a href="/request-documents/">Request Documents</a></div><div class="utility-card"><h3>Request a Sample</h3><p>Share your product and application context for a sample request.</p><a href="/request-sample/">Request a Sample</a></div></div></section>
<section class="wrap" id="general-inquiry"><h2>Send a general inquiry</h2><p>Use this form for a question about the company, a partnership or another general business matter. Please use the dedicated routes above for quotations, product documents or samples.</p>[tio2_contact_form]</section>'''
save('CONTACT-001','contact','Contact','Contact TiO2 Malaysia | General Inquiries','Contact TiO2 Malaysia with a general company or business inquiry, or use the dedicated pages to request a quote, product documents or a sample.',contact)

thank = '''[tio2_thank_state kind="quote"]<section class="utility-hero"><div class="wrap utility-hero-inner"><p class="eyebrow">Request received</p><h1>Thank you. We’ve received your quotation request.</h1><p class="lead">Our team will review the details and contact you using the information provided.</p><div class="utility-actions"><a class="button primary" href="/products/">Explore Products</a><a class="button" href="/">Go to Homepage</a></div></div></section>[/tio2_thank_state]
[tio2_thank_state kind="documents"]<section class="utility-hero"><div class="wrap utility-hero-inner"><p class="eyebrow">Request received</p><h1>Thank you. We’ve received your document request.</h1><p class="lead">Our team will review the requested documents and contact you using the information provided.</p><div class="utility-actions"><a class="button primary" href="/documents/">Return to Documents</a><a class="button" href="/products/">Explore Products</a></div></div></section>[/tio2_thank_state]
[tio2_thank_state kind="sample"]<section class="utility-hero"><div class="wrap utility-hero-inner"><p class="eyebrow">Request received</p><h1>Thank you. We’ve received your sample request.</h1><p class="lead">Our team will review your application and sample requirements and contact you using the information provided.</p><div class="utility-actions"><a class="button primary" href="/products/">Explore Products</a><a class="button" href="/applications/">View Applications</a></div></div></section>[/tio2_thank_state]
[tio2_thank_state kind="invalid"]<section class="utility-hero"><div class="wrap utility-hero-inner"><h1>How can we help?</h1><p class="lead">Choose the request you’d like to make, and our team will guide you through the next step.</p><div class="utility-actions"><a class="button primary" href="/request-a-quote/">Request a Quote</a><a class="button" href="/request-documents/">Request Documents</a><a class="button" href="/request-sample/">Request a Sample</a></div></div></section>[/tio2_thank_state]'''
save('CONV-THANK','thank-you','Thank You','Thank You | TiO2 Malaysia','View confirmation and next steps for a TiO2 Malaysia request, or choose the request you would like to make.',thank)
