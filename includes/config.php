<?php
declare(strict_types=1);

/**
 * Softrings International (Pvt) Ltd — Central Corporate Configuration & Verified Data
 * Timezone: Sri Lanka Standard Time (Asia/Colombo, UTC+05:30)
 */

date_default_timezone_set('Asia/Colombo');

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

const COMPANY_NAME = 'Softrings International (Pvt) Ltd';
const COMPANY_SHORT = 'Softrings International';
const GROUP_NAME = 'Softrings Group of Companies';
const COMPANY_TAGLINE = 'Your Partner in Technology';
const COMPANY_FOUNDED_GROUP = 2000;
const COMPANY_INCORPORATED = '11 September 2009';
const COMPANY_DOMAIN = 'www.softrings.lk';
const COMPANY_PRIMARY_EMAIL = 'international@softrings.lk';
const COMPANY_HR_EMAIL = 'hr@softrings.lk';
const COMPANY_HOTLINE = '+94 71 700 0090';
const COMPANY_HOTLINE_RAW = '+94717000090';
const COMPANY_KANDY_HOTLINE = '+94 81 222 7417';

/**
 * Trilingual Corporate Identity Banner (English / Sinhala / Tamil)
 */
function get_trilingual_motto(): array
{
    return [
        'en' => [
            'label' => 'EN',
            'name' => 'Softrings International (Pvt) Ltd',
            'motto' => 'Sri Lanka’s Trusted Partner in Telecommunications, Mobile Retail & Island-Wide Distribution Since 2000',
            'badge' => 'Authorized SLT-Mobitel Dealer Network',
        ],
        'si' => [
            'label' => 'සිං',
            'name' => 'සොෆ්ට්රින්ග්ස් ඉන්ටර්නැෂනල් (පුද්) සමාගම',
            'motto' => 'වසර 2000 සිට ශ්‍රී ලංකාව පුරා විශ්වාසනීය ජංගම දුරකථන සහ විදුලි සංදේශ බෙදාහැරීමේ ප්‍රමුඛයා',
            'badge' => 'SLT-Mobitel බලයලත් අලෙවි ජාලය',
        ],
        'ta' => [
            'label' => 'தமிழ்',
            'name' => 'சொஃப்ட்ரிங்ஸ் இன்டர்நேஷனல் (பிரைவேட்) லிமிடெட்',
            'motto' => '2000 முதல் இலங்கையின் நம்பகமான தொலைத்தொடர்பு மற்றும் கையடக்கத் தொலைபேசி விநியோகப் பங்காளர்',
            'badge' => 'அங்கீகரிக்கப்பட்ட SLT-Mobitel முகவர் வலையமைப்பு',
        ],
    ];
}

/**
 * Corporate Ethos & Statement (Verified from Official Softrings International Profile)
 */
function get_corporate_charter(): array
{
    return [
        'quote' => 'It is our ultimate target to maintain our good name in the market and to serve our clients with utmost integrity, preserving their trust. You can always keep your trust in us for superior quality and authentic warranty standards across every product and connection we deliver.',
        'vision' => 'To be Sri Lanka’s most trusted and accessible telecommunications and mobile technology distribution enterprise, connecting urban centers and regional communities alike with genuine devices and seamless digital services.',
        'mission' => 'To empower consumers, retail merchants, and enterprise clients across Western, Sabaragamuwa, and Central Sri Lanka through authorized SLT-Mobitel connectivity, multi-brand original mobile devices, smart mPOS payment solutions, and responsive technical care.',
        'values' => [
            [
                'code' => '01',
                'title' => 'Market Integrity & Trust',
                'desc' => 'Preserving customer confidence for over two decades with transparent pricing, official TRCSL-compliant devices, and authorized manufacturer warranties.',
            ],
            [
                'code' => '02',
                'title' => 'Regional Territory Depth',
                'desc' => 'Deep operational roots linking Greater Colombo (Maharagama, Homagama, Nugegoda, Moratuwa, Avissawella) with Central Hill Country hubs (Kandy, Peradeniya, Gampola).',
            ],
            [
                'code' => '03',
                'title' => 'Authorized Telecom Excellence',
                'desc' => 'Long-standing Authorized Dealer partnership with Sri Lanka Telecom Mobitel, recognized across dealer conventions and national distribution benchmarks.',
            ],
            [
                'code' => '04',
                'title' => 'The Human Factor',
                'desc' => 'Investing in trained customer care executives, certified mobile hardware/software technicians, and dedicated island-wide logistics teams.',
            ],
        ],
    ];
}

/**
 * Softrings Group of Companies — Corporate Structure & Entities
 */
function get_group_entities(): array
{
    return [
        [
            'name' => 'Softrings International (Pvt) Ltd',
            'commenced' => '11 September 2009',
            'role' => 'Flagship Multi-Brand Handset Retail, Corporate Distribution & Authorized Dealer Operations',
            'hq' => 'No. 65/10, Piliyandala Road, Maharagama & No. 102, Nuwara Eliya Road, Gampola',
            'domain_archive' => 'international.softrings.lk',
            'highlight' => true,
            'summary' => 'Spearheads multi-brand smartphone and mobile device retail, original accessories, corporate procurement, and authorized SLT-Mobitel customer service centers across Western and Central Provinces.',
        ],
        [
            'name' => 'Softrings (Pvt) Ltd',
            'commenced' => 'Founded 2000',
            'role' => 'Founding Group Parent & Pioneer SLT-Mobitel Authorized Dealership',
            'hq' => 'No. 74, Stanley Thilakaratne Mawatha, Nugegoda / Kandy',
            'domain_archive' => 'softrings.lk',
            'highlight' => false,
            'summary' => 'Established the Softrings name in Sri Lanka’s telecommunications sector in 2000, building foundational dealer relationships, mCash retail points, and regional distribution trust.',
        ],
        [
            'name' => 'Softrings Technologies (Pvt) Ltd',
            'commenced' => '02 July 2003',
            'role' => 'Authorized Regional SLT-Mobitel Distribution & Broadband Connectivity',
            'hq' => 'No. 120, High Level Road, Maharagama & No. 50/1, High Level Road, Homagama',
            'domain_archive' => 'technologies.softrings.lk',
            'highlight' => false,
            'summary' => 'Dedicated regional telecom distributor authorized across Maharagama, Homagama, Avissawella, Moratuwa, Kandy, and Gampola for Prepaid/Postpaid SIMs, broadband routers, Upahara packages, reloads, and scratch cards.',
        ],
        [
            'name' => 'Softrings Phone Company (Pvt) Ltd',
            'commenced' => '01 July 2010',
            'role' => 'National Brand Distribution Heritage (Alcatel OneTouch) & Technical Hub',
            'hq' => 'No. 74, Stanley Thilakaratne Mawatha, Nugegoda',
            'domain_archive' => 'phonecompany.softrings.lk',
            'highlight' => false,
            'summary' => 'Appointed National Distributor for Alcatel OneTouch mobile phones and USB broadband modems in Sri Lanka, supporting island-wide sub-dealer wholesale and technical service.',
        ],
    ];
}

/**
 * Historical Milestones Timeline
 */
function get_company_timeline(): array
{
    return [
        [
            'year' => '2000',
            'title' => 'Foundation of Softrings in Sri Lanka',
            'detail' => 'Softrings commences operations as an Authorized Dealer for Sri Lanka Telecom Mobitel, establishing early retail and subscriber service points in Colombo and Kandy districts.',
        ],
        [
            'year' => '2003',
            'title' => 'Incorporation of Softrings Technologies (Pvt) Ltd',
            'detail' => 'Commenced on 02 July 2003 to scale dedicated territory-wide SLT-Mobitel wholesale distribution across Maharagama, Homagama, Avissawella, Moratuwa, Kandy, and Gampola.',
        ],
        [
            'year' => '2009',
            'title' => 'Launch of Softrings International (Pvt) Ltd',
            'detail' => 'Commenced on 11 September 2009 as the Group’s flagship multi-brand mobile handset, original accessories, and corporate retail enterprise linking Colombo and Central Province.',
        ],
        [
            'year' => '2010',
            'title' => 'National Alcatel Distribution Mandate',
            'detail' => 'Softrings Phone Company (Pvt) Ltd commences on 01 July 2010 at Stanley Thilakaratne Mawatha, Nugegoda, serving as Sri Lanka’s island-wide Alcatel OneTouch handset and HSDPA modem distributor.',
        ],
        [
            'year' => '2017',
            'title' => 'Softrings mPOS Rollout & Mobitel Awards Recognition',
            'detail' => 'Expansion of Softrings mPOS utility and reload terminals for Sri Lankan retail merchants, celebrated alongside annual Staff Conventions and SLT-Mobitel Dealer Awards.',
        ],
        [
            'year' => 'Present',
            'title' => 'Integrated Multi-Channel Telecom & Device Partner',
            'detail' => 'Operating consolidated corporate offices in Maharagama and Gampola alongside flagship SLT-Mobitel showrooms in Homagama, Maharagama, Kandy, Avissawella, and Moratuwa.',
        ],
    ];
}

/**
 * Board of Directors & Executive Leadership (Verified Corporate Records)
 */
function get_leadership(): array
{
    return [
        'board' => [
            [
                'name' => 'Mr. P. Amal Nishantha Silva', 'photo' => 'assets/images/team/amal-nishantha-silva.jpg',
                'role' => 'Chairman',
                'email' => 'amal@softrings.lk',
                'bio' => 'Provides overarching strategic governance and commercial vision across Softrings International (Pvt) Ltd and the Softrings Group of Companies, guiding over two decades of SLT-Mobitel dealership growth and island-wide device distribution.',
            ],
            [
                'name' => 'Mrs. C. Anne Olivia Bernadeth Silva', 'photo' => 'assets/images/team/anne-olivia-bernadeth-silva.jpg',
                'role' => 'Director',
                'email' => 'oliviasilva@softrings.lk',
                'bio' => 'Serves on the Board of Directors overseeing corporate administration, financial stewardship, and multi-branch operational compliance across Western and Central Provinces.',
            ],
        ],
        'management' => [
            ['role' => 'General Manager', 'name' => 'Mr. Kshemendra Karunaratne', 'photo' => 'assets/images/team/kshemendra-karunaratne.jpg', 'dept' => 'Executive Operations', 'email' => 'kshemendra@softrings.lk'],
            ['role' => 'Human Resources Manager', 'name' => 'Mr. S. P. Somasiri Perera', 'photo' => 'assets/images/team/somasiri-perera.jpg', 'dept' => 'People & Administration', 'email' => 'perera@softrings.lk'],
            ['role' => 'Sales Manager', 'name' => 'Mr. Wasana Rajapaksha', 'photo' => 'assets/images/team/wasana-rajapaksha.jpg', 'dept' => 'Island-Wide Distribution', 'email' => 'wasana@softrings.lk'],
            ['role' => 'Chief Accountant', 'name' => 'Mr. Upul Deshapriya', 'photo' => 'assets/images/team/upul-deshapriya.jpg', 'dept' => 'Finance & Audit', 'email' => 'upul@softrings.lk'],
            ['role' => 'Company Secretary', 'name' => 'Ms. Pradeepa Dilrukshi', 'photo' => 'assets/images/team/pradeepa-dilrukshi.jpg', 'dept' => 'Corporate Governance', 'email' => 'pradeepa@softrings.lk'],
            ['role' => 'Technical Manager', 'name' => 'Mr. Neranjan Santha Kumara', 'photo' => 'assets/images/team/neranjan-santha-kumara.jpg', 'dept' => 'Hardware & Warranty Lab', 'email' => 'kumara@softrings.lk'],
        ],
    ];
}

/**
 * Core Business Verticals & Solutions
 */
function get_services_catalog(): array
{
    return [
        [
            'slug' => 'mobitel-dealer',
            'code' => 'DIV-01',
            'title' => 'Authorized SLT-Mobitel Dealer Network',
            'subtitle' => 'Official subscriber onboarding, enterprise & state-sector packages, and regional reload distribution',
            'metrics' => '6 Core Authorized Territories',
            'items' => [
                'Prepaid & Postpaid SIM registration, VoLTE/5G-ready SIM upgrades, and instant number replacements',
                'Wholesale distribution of SLT-Mobitel reloads, top-up vouchers, and scratch cards to thousands of retail outlets',
                '4G/LTE & Fiber-companion Home Broadband routers, pocket Wi-Fi devices, and high-speed USB modems',
                'Dedicated facilitation of Upahara state-sector & pensioner voice and broadband concessionary packages',
                'Authorized SLT-Mobitel bill payment counters and mCash mobile wallet agent services',
            ],
        ],
        [
            'slug' => 'mobile-distribution',
            'code' => 'DIV-02',
            'title' => 'Multi-Brand Smartphones & Accessories Distribution',
            'subtitle' => 'TRCSL-approved mobile handsets and genuine accessories with official company warranty',
            'metrics' => 'Island-Wide Retail & Wholesale',
            'items' => [
                'Authorized retail and sub-dealer supply of global brands including Samsung, Nokia, Huawei, Alcatel, Xiaomi, and entry-level feature phones',
                'Proven national brand distribution heritage as Sri Lanka’s appointed island-wide Alcatel OneTouch distributor',
                'Original chargers, power banks, audio gear, protective accessories, and data cables backed by seller warranty',
                'Corporate bulk handset & tablet procurement with customized invoicing for Sri Lankan SMEs and enterprises',
                'Direct doorstep and retail-partner delivery coordinated by our in-house Colombo & Kandy logistics fleet',
            ],
        ],
        [
            'slug' => 'mpos-solutions',
            'code' => 'DIV-03',
            'title' => 'Softrings mPOS & Digital Merchant Terminals',
            'subtitle' => 'Smart one-terminal bill settlement, utility payments, and instant reload infrastructure for retailers',
            'metrics' => '24/7 Real-Time Settlement',
            'items' => [
                'Deployment of compact wireless Softrings mPOS machines to groceries, pharmacies, communication shops, and supermarkets',
                'Instant processing of mobile reloads, postpaid telephone bills, electricity/water utility bills, and e-vouchers',
                'Automated thermal receipt printing, daily merchant commission tracking, and instant float top-up support',
                'On-site merchant staff training and rapid terminal maintenance across Western and Central Provinces',
            ],
        ],
        [
            'slug' => 'technical-care',
            'code' => 'DIV-04',
            'title' => 'Technical Service Lab & Customer Care',
            'subtitle' => 'In-house mobile diagnostics, warranty claim management, and fleet logistics',
            'metrics' => 'Colombo & Kandy Support Hubs',
            'items' => [
                'Qualified Mobile Phone Technicians stationed at Nugegoda, Maharagama, Homagama, and Kandy service points',
                'Component-level hardware diagnostics, firmware recovery, and manufacturer warranty coordination',
                'Dedicated customer care hotlines handling package activations, broadband troubleshooting, and home delivery orders',
            ],
        ],
    ];
}

/**
 * Verified Branch & Corporate Office Network across Sri Lanka
 * lat / lng are real geographic coordinates and are projected onto the district
 * map (viewBox 0 0 450 793) by sri_lanka_map_point().
 */
function get_branches(): array
{
    return [
        [
            'id' => 'maharagama-hq',
            'name' => 'Colombo Corporate Office — Maharagama',
            'type' => 'Corporate Head Office',
            'province' => 'Western',
            'district' => 'Colombo',
            'city' => 'Maharagama',
            'postal' => '10280',
            'address' => 'No. 65/10, Piliyandala Road, Maharagama, Sri Lanka',
            'phones' => ['+94 71 700 0090', '+94 71 872 8635'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:00', '18:00'],
            'hours_sat' => ['08:30', '14:00'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:00 AM – 6:00 PM · Sat 8:30 AM – 2:00 PM',
            'coords' => '6.8448° N, 79.9285° E',
            'lat' => 6.8448,
            'lng' => 79.9285,
            'services' => ['Corporate Administration', 'Wholesale Inquiries', 'Device Retail', 'Fleet Dispatch'],
        ],
        [
            'id' => 'gampola-corp',
            'name' => 'Central Province Corporate Office — Gampola',
            'type' => 'Regional Corporate Office',
            'province' => 'Central',
            'district' => 'Kandy',
            'city' => 'Gampola',
            'postal' => '20500',
            'address' => 'No. 102, Nuwara Eliya Road, Gampola 20500, Sri Lanka',
            'phones' => ['+94 71 404 1369', '+94 81 235 1212'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:30', '18:00'],
            'hours_sat' => ['09:00', '14:00'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:30 AM – 6:00 PM · Sat 9:00 AM – 2:00 PM',
            'coords' => '7.1644° N, 80.5766° E',
            'lat' => 7.1644,
            'lng' => 80.5766,
            'services' => ['Central Region Ops', 'SLT-Mobitel Dealer Point', 'Handset Distribution', 'mPOS Support'],
        ],
        [
            'id' => 'homagama-flagship',
            'name' => 'Homagama Authorized SLT-Mobitel Flagship',
            'type' => 'Authorized Dealer & HR Hub',
            'province' => 'Western',
            'district' => 'Colombo',
            'city' => 'Homagama',
            'postal' => '10200',
            'address' => 'No. 50 & 50/1, Avissawella / High Level Road, Homagama 10200',
            'phones' => ['+94 11 289 5061', '+94 70 873 8611', '+94 71 534 3131'],
            'email' => 'hr@softrings.lk',
            'hours_mf' => ['08:30', '19:00'],
            'hours_sat' => ['08:30', '19:00'],
            'hours_sun' => ['08:30', '19:00'],
            'hours_label' => 'Daily 8:30 AM – 7:00 PM (Extended Customer Care)',
            'coords' => '6.8410° N, 79.9999° E',
            'lat' => 6.841,
            'lng' => 79.9999,
            'services' => ['SLT-Mobitel Bill Pay', 'SIM & Broadband', 'Original Phones & Warranty', 'HR & Admin'],
        ],
        [
            'id' => 'maharagama-mobitel',
            'name' => 'Maharagama High Level Rd Showroom',
            'type' => 'Authorized SLT-Mobitel Showroom',
            'province' => 'Western',
            'district' => 'Colombo',
            'city' => 'Maharagama',
            'postal' => '10280',
            'address' => 'No. 120 (120A), High Level Road, Maharagama 10280',
            'phones' => ['+94 11 285 0998', '+94 71 404 1368'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:30', '18:00'],
            'hours_sat' => ['09:00', '14:00'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:30 AM – 6:00 PM · Sat 9:00 AM – 2:00 PM',
            'coords' => '6.8480° N, 79.9267° E',
            'lat' => 6.848,
            'lng' => 79.9267,
            'services' => ['Mobitel Authorized Point', 'mCash & Bill Pay', 'Smartphones & Modems', 'Accounts'],
        ],
        [
            'id' => 'kandy-regional',
            'name' => 'Kandy Regional Office & Dealer Center',
            'type' => 'Regional Hub & Showroom',
            'province' => 'Central',
            'district' => 'Kandy',
            'city' => 'Kandy',
            'postal' => '20000',
            'address' => 'No. 130/A, D. S. Senanayake Veediya & No. 141, Kotugodella St, Kandy',
            'phones' => ['+94 81 222 7417', '+94 81 223 8222', '+94 71 700 0099'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:30', '18:00'],
            'hours_sat' => ['09:00', '14:00'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:30 AM – 6:00 PM · Sat 9:00 AM – 2:00 PM',
            'coords' => '7.2955° N, 80.6378° E',
            'lat' => 7.2955,
            'lng' => 80.6378,
            'services' => ['Hill Country Distribution', 'SLT-Mobitel Services', 'mCash Hub', 'Retail Showroom'],
        ],
        [
            'id' => 'avissawella-branch',
            'name' => 'Avissawella Authorized Dealer Center',
            'type' => 'Authorized SLT-Mobitel Branch',
            'province' => 'Western',
            'district' => 'Colombo',
            'city' => 'Avissawella',
            'postal' => '10700',
            'address' => 'No. 45, Yatiyanthota Road & No. 17/A, Ratnapura Road, Avissawella',
            'phones' => ['+94 36 223 2732', '+94 71 457 2626'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:30', '18:00'],
            'hours_sat' => ['09:00', '14:00'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:30 AM – 6:00 PM · Sat 9:00 AM – 2:00 PM',
            'coords' => '6.9543° N, 80.2046° E',
            'lat' => 6.9543,
            'lng' => 80.2046,
            'services' => ['Seethawaka Distribution', 'Mobitel Connections', 'Mobile Phones & Accessories'],
        ],
        [
            'id' => 'moratuwa-branch',
            'name' => 'Moratuwa Retail & Telecom Showroom',
            'type' => 'Retail & Dealer Branch',
            'province' => 'Western',
            'district' => 'Colombo',
            'city' => 'Moratuwa',
            'postal' => '10400',
            'address' => 'No. 107, Old Galle Road, Moratuwa 10400, Sri Lanka',
            'phones' => ['+94 11 264 3444', '+94 71 533 5884'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:30', '18:00'],
            'hours_sat' => ['09:00', '14:00'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:30 AM – 6:00 PM · Sat 9:00 AM – 2:00 PM',
            'coords' => '6.7730° N, 79.8816° E',
            'lat' => 6.773,
            'lng' => 79.8816,
            'services' => ['Coastal Corridor Retail', 'SLT-Mobitel Bill Pay', 'Device Warranty Support'],
        ],
        [
            'id' => 'nugegoda-tech',
            'name' => 'Nugegoda Distribution & Technical Center',
            'type' => 'Distribution & Service Lab',
            'province' => 'Western',
            'district' => 'Colombo',
            'city' => 'Nugegoda',
            'postal' => '10250',
            'address' => 'No. 74, Stanley Thilakaratne Mawatha, Nugegoda 10250',
            'phones' => ['+94 11 282 9888', '+94 11 280 9405', '+94 71 533 5880'],
            'email' => 'international@softrings.lk',
            'hours_mf' => ['08:30', '17:30'],
            'hours_sat' => ['09:00', '13:30'],
            'hours_sun' => null,
            'hours_label' => 'Mon–Fri 8:30 AM – 5:30 PM · Sat 9:00 AM – 1:30 PM',
            'coords' => '6.8729° N, 79.8886° E',
            'lat' => 6.8729,
            'lng' => 79.8886,
            'services' => ['National Handset Wholesale', 'Hardware Repair Lab', 'Group Operations'],
        ],
    ];
}

/**
 * Extended Satellite & Historical Distribution Touchpoints
 */
function get_extended_touchpoints(): array
{
    return [
        ['city' => 'Peradeniya (Kandy Office)', 'address' => 'No. 68, 2nd Floor, Gampola Road, Peradeniya', 'phone' => '+94 71 457 2606'],
        ['city' => 'Colombo 11 (Pettah)', 'address' => 'No. 130/6, Main Street, Golden Plaza, Colombo 11', 'phone' => '+94 11 244 1113'],
        ['city' => 'Kaduwela', 'address' => 'No. 452/2, New Kandy Road, Kaduwela', 'phone' => '+94 11 253 8988'],
        ['city' => 'Athurugiriya', 'address' => 'No. 31, Godagama Road, Athurugiriya', 'phone' => '+94 11 274 2771'],
        ['city' => 'Kadawatha', 'address' => 'No. 06, Kandy Road, Kadawatha', 'phone' => '+94 11 292 1712'],
        ['city' => 'Kegalle', 'address' => 'No. 99/1, Main Street, Kegalle', 'phone' => '+94 35 222 2755'],
        ['city' => 'Nuwara Eliya', 'address' => 'No. 4/1, Kandy Road, Nuwara Eliya', 'phone' => '+94 52 222 3110'],
    ];
}

/**
 * Project a real Sri Lanka latitude/longitude onto the district map viewBox.
 *
 * The supplied district map (includes/sri-lanka-districts.php) is drawn in a
 * 0 0 450 793 viewBox whose north-west corner corresponds to 9.8125 N /
 * 79.7066 E and whose south-east corner corresponds to 5.9499 N / 81.8774 E
 * (the exact geographic extent of the source boundary data). The projection is
 * therefore a plain latitude/longitude to pixel mapping, so pins land in the
 * correct district.
 *
 * @return array{x: float, y: float}
 */
function sri_lanka_map_point(float $lat, float $lng): array
{
    $bounds = [
        'x0' => 0.257125,
        'y0' => 0.260747,
        'x1' => 449.417120,
        'y1' => 792.260750,
        'lng0' => 79.706597,
        'lng1' => 81.877419,
        'lat0' => 5.949911,
        'lat1' => 9.812519,
    ];

    $x = $bounds['x0'] + (($lng - $bounds['lng0']) / ($bounds['lng1'] - $bounds['lng0'])) * ($bounds['x1'] - $bounds['x0']);
    $y = $bounds['y0'] + (($bounds['lat1'] - $lat) / ($bounds['lat1'] - $bounds['lat0'])) * ($bounds['y1'] - $bounds['y0']);

    // Calibration nudge: the district source is a generalised tracing, so the raw
    // projection is a few map units west of the drawn coastline. This offset was
    // measured by testing every branch coordinate against its own district
    // polygon (isPointInFill) and keeps all eight inside the correct boundary.
    $x += 11.0;
    $y -= 2.0;

    return ['x' => round($x, 2), 'y' => round($y, 2)];
}

/**
 * Compute real-time open/closed status in Sri Lanka Standard Time (Asia/Colombo)
 */
function get_branch_live_status(array $branch): array
{
    $dow = (int) date('N'); // 1 (Mon) .. 7 (Sun)
    $nowMinutes = ((int) date('G')) * 60 + (int) date('i');

    $window = null;
    if ($dow >= 1 && $dow <= 5) {
        $window = $branch['hours_mf'];
    } elseif ($dow === 6) {
        $window = $branch['hours_sat'];
    } else {
        $window = $branch['hours_sun'];
    }

    if ($window === null) {
        return [
            'is_open' => false,
            'label' => 'Closed Today (Opens Mon)',
        ];
    }

    [$openH, $openM] = array_map('intval', explode(':', $window[0]));
    [$closeH, $closeM] = array_map('intval', explode(':', $window[1]));
    $openMinutes = $openH * 60 + $openM;
    $closeMinutes = $closeH * 60 + $closeM;

    if ($nowMinutes >= $openMinutes && $nowMinutes < $closeMinutes) {
        return [
            'is_open' => true,
            'label' => 'Open Now · Closes ' . date('g:i A', mktime($closeH, $closeM)),
        ];
    }

    if ($nowMinutes < $openMinutes) {
        return [
            'is_open' => false,
            'label' => 'Opens Today at ' . date('g:i A', mktime($openH, $openM)),
        ];
    }

    return [
        'is_open' => false,
        'label' => 'Closed for Today',
    ];
}

/**
 * Career Openings ("The Human Factor" — Authentic Roles at Softrings International)
 */
function get_career_openings(): array
{
    return [
        [
            'code' => 'SFT-HR-01',
            'title' => 'Branch Sales & Territory Manager',
            'locations' => 'Colombo & Kandy Districts',
            'type' => 'Full-Time · On-Site',
            'department' => 'Retail & SLT-Mobitel Distribution',
            'summary' => 'Lead branch retail performance, supervise field distribution executives, and grow SLT-Mobitel and smartphone wholesale volume across assigned DS divisions.',
        ],
        [
            'code' => 'SFT-HR-02',
            'title' => 'IT & Systems Executive',
            'locations' => 'Maharagama / Colombo Corporate Office',
            'type' => 'Full-Time · On-Site',
            'department' => 'Information Technology',
            'summary' => 'Manage multi-branch ERP/POS connectivity, Softrings mPOS merchant terminal provisioning, network security, and internal IT infrastructure.',
        ],
        [
            'code' => 'SFT-HR-03',
            'title' => 'Customer Care & Telecom Service Executive',
            'locations' => 'Homagama, Maharagama, Gampola & Kandy',
            'type' => 'Full-Time · On-Site',
            'department' => 'Customer Experience',
            'summary' => 'Assist walk-in subscribers with SLT-Mobitel SIM registrations, broadband packages, bill settlements, and genuine smartphone selections in Sinhala, Tamil, or English.',
        ],
        [
            'code' => 'SFT-HR-04',
            'title' => 'Accounts & Administration Executive',
            'locations' => 'Maharagama Corporate Office',
            'type' => 'Full-Time · On-Site',
            'department' => 'Finance & Audit',
            'summary' => 'Handle daily branch reconciliations, dealer credit control, inventory audit schedules, and statutory financial reporting.',
        ],
        [
            'code' => 'SFT-HR-05',
            'title' => 'Mobile Phone Hardware & Software Technician',
            'locations' => 'Nugegoda & Kandy Service Labs',
            'type' => 'Full-Time · On-Site',
            'department' => 'Technical Service Lab',
            'summary' => 'Perform board-level smartphone diagnostics, firmware flashing, and authorized warranty repairs across multi-brand mobile devices.',
        ],
        [
            'code' => 'SFT-HR-06',
            'title' => 'Field Marketing Executive & Fleet Driver',
            'locations' => 'Western & Central Provinces',
            'type' => 'Full-Time · Field',
            'department' => 'Island-Wide Distribution',
            'summary' => 'Support retail sub-dealer visits, reload/mPOS merchant onboarding, and safe commercial fleet dispatch between Colombo and Hill Country hubs.',
        ],
    ];
}

/**
 * CSRF Token Helpers
 */
function csrf_token(): string
{
    if (empty($_SESSION['softrings_csrf'])) {
        $_SESSION['softrings_csrf'] = bin2hex(random_bytes(24));
    }
    return (string) $_SESSION['softrings_csrf'];
}

function verify_csrf(?string $token): bool
{
    if (empty($_SESSION['softrings_csrf']) || empty($token)) {
        return false;
    }
    return hash_equals((string) $_SESSION['softrings_csrf'], $token);
}

/**
 * Clean HTML escaping helper
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
