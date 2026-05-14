const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '..', 'figma', 'travel-app-modern-ui-mockup.svg');

const FRAME_W = 1440;
const FRAME_H = 1024;
const PHONE_W = 390;
const PHONE_H = 844;
const GAP = 90;
const COLS = 5;

const C = {
  bg: '#F5F7FB',
  ink: '#111827',
  body: '#667085',
  line: '#EAECF0',
  white: '#FFFFFF',
  blue: '#2563EB',
  blue2: '#5B8DEF',
  cyan: '#06B6D4',
  green: '#12B76A',
  orange: '#F97316',
  yellow: '#FBBF24',
  rose: '#F43F5E',
  violet: '#7C3AED',
  deep: '#0B1220',
};

const gradients = `
  <defs>
    <linearGradient id="heroSea" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#8FD3F4"/>
      <stop offset="55%" stop-color="#5B8DEF"/>
      <stop offset="100%" stop-color="#2563EB"/>
    </linearGradient>
    <linearGradient id="heroSunset" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#FECACA"/>
      <stop offset="48%" stop-color="#FB923C"/>
      <stop offset="100%" stop-color="#7C3AED"/>
    </linearGradient>
    <linearGradient id="heroForest" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#A7F3D0"/>
      <stop offset="45%" stop-color="#12B76A"/>
      <stop offset="100%" stop-color="#064E3B"/>
    </linearGradient>
    <linearGradient id="heroNight" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#1E3A8A"/>
      <stop offset="50%" stop-color="#312E81"/>
      <stop offset="100%" stop-color="#0B1220"/>
    </linearGradient>
    <linearGradient id="cardWarm" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#FFF7ED"/>
      <stop offset="100%" stop-color="#FED7AA"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="18" stdDeviation="20" flood-color="#101828" flood-opacity="0.14"/>
    </filter>
    <filter id="softShadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#101828" flood-opacity="0.10"/>
    </filter>
  </defs>
`;

function esc(value) {
  return String(value).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
}

function rect(x, y, w, h, fill = C.white, r = 24, stroke = 'none', sw = 1, extra = '') {
  return `<rect x="${x}" y="${y}" width="${w}" height="${h}" rx="${r}" fill="${fill}" stroke="${stroke}" stroke-width="${sw}" ${extra}/>`;
}

function circle(cx, cy, r, fill = C.blue, extra = '') {
  return `<circle cx="${cx}" cy="${cy}" r="${r}" fill="${fill}" ${extra}/>`;
}

function text(value, x, y, size = 16, fill = C.ink, weight = 500, anchor = 'start') {
  return `<text x="${x}" y="${y}" font-family="Inter, SF Pro Display, Arial, sans-serif" font-size="${size}" font-weight="${weight}" fill="${fill}" text-anchor="${anchor}">${esc(value)}</text>`;
}

function lines(items, x, y, size = 16, fill = C.body, weight = 500, leading = 24) {
  return items.map((line, i) => text(line, x, y + i * leading, size, fill, weight)).join('');
}

function pill(label, x, y, w, fill = '#EEF4FF', color = C.blue) {
  return rect(x, y, w, 34, fill, 17) + text(label, x + w / 2, y + 22, 13, color, 700, 'middle');
}

function iconCircle(label, x, y, fill = '#EEF4FF', color = C.blue) {
  return circle(x, y, 22, fill) + text(label, x, y + 6, 15, color, 800, 'middle');
}

function button(label, x, y, w, fill = C.blue) {
  return rect(x, y, w, 56, fill, 18, 'none', 1, 'filter="url(#softShadow)"') +
    text(label, x + w / 2, y + 35, 16, C.white, 800, 'middle');
}

function input(label, value, x, y, w, icon = '') {
  return text(label, x, y - 12, 13, C.body, 700) +
    rect(x, y, w, 56, C.white, 18, C.line, 1) +
    (icon ? text(icon, x + 20, y + 35, 15, C.blue, 800, 'middle') : '') +
    text(value, x + (icon ? 42 : 18), y + 35, 15, C.ink, 600);
}

function phoneStart() {
  return rect(0, 0, PHONE_W, PHONE_H, C.white, 44, '#D0D5DD', 1, 'filter="url(#shadow)"') +
    rect(17, 14, PHONE_W - 34, PHONE_H - 28, '#F8FAFC', 34) +
    rect(137, 28, 116, 24, C.deep, 12);
}

function phoneFrame(name, draw) {
  const x = Math.round((FRAME_W - PHONE_W) / 2);
  const y = 90;
  return rect(0, 0, FRAME_W, FRAME_H, C.bg, 0) +
    text(name, 72, 78, 26, C.ink, 800) +
    text('BugBusters Travel App UI', 72, 112, 15, C.body, 600) +
    `<g transform="translate(${x},${y})">${phoneStart()}<g transform="translate(17,14)">${draw()}</g></g>`;
}

function statusBar(dark = false) {
  const color = dark ? C.white : C.ink;
  return text('9:41', 26, 38, 13, color, 800) +
    circle(300, 33, 4, color) +
    rect(312, 29, 18, 8, color, 4) +
    rect(336, 28, 16, 10, color, 3);
}

function bottomNav(active) {
  const items = [
    ['Home', '⌂'],
    ['Search', '⌕'],
    ['Trips', '✈'],
    ['Profile', '●'],
  ];
  let s = rect(24, 736, 322, 64, C.white, 26, C.line, 1, 'filter="url(#softShadow)"');
  items.forEach(([label, icon], i) => {
    const x = 64 + i * 76;
    const on = label === active;
    if (on) s += rect(x - 24, 747, 58, 42, '#EEF4FF', 21);
    s += text(icon, x + 5, 771, 17, on ? C.blue : '#98A2B3', 800, 'middle');
  });
  return s;
}

function landscape(x, y, w, h, grad = 'heroSea') {
  return rect(x, y, w, h, `url(#${grad})`, 28) +
    circle(x + w - 72, y + 72, 30, 'rgba(255,255,255,0.65)') +
    `<path d="M${x} ${y + h} C${x + w * .18} ${y + h * .55},${x + w * .34} ${y + h * .58},${x + w * .48} ${y + h} Z" fill="rgba(255,255,255,0.42)"/>` +
    `<path d="M${x + w * .24} ${y + h} C${x + w * .48} ${y + h * .38},${x + w * .72} ${y + h * .48},${x + w} ${y + h} Z" fill="rgba(255,255,255,0.28)"/>` +
    `<path d="M${x + 24} ${y + h - 54} C${x + 90} ${y + h - 86},${x + 154} ${y + h - 24},${x + 232} ${y + h - 62} C${x + 270} ${y + h - 80},${x + 306} ${y + h - 62},${x + w - 24} ${y + h - 86}" stroke="rgba(255,255,255,0.45)" stroke-width="5" fill="none" stroke-linecap="round"/>`;
}

function mobileHeader(title, subtitle = '', dark = false) {
  const color = dark ? C.white : C.ink;
  return statusBar(dark) +
    iconCircle('‹', 38, 80, dark ? 'rgba(255,255,255,0.18)' : C.white, color) +
    text(title, 72, 86, 20, color, 800) +
    (subtitle ? text(subtitle, 72, 110, 13, dark ? 'rgba(255,255,255,0.78)' : C.body, 600) : '') +
    iconCircle('⋯', 320, 80, dark ? 'rgba(255,255,255,0.18)' : C.white, color);
}

function login() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    landscape(24, 82, 308, 254, 'heroSea') +
    statusBar(true) +
    text('Explore more', 48, 234, 31, C.white, 900) +
    text('with one gateway', 48, 270, 31, C.white, 900) +
    text('Maps, weather, hotels, booking,', 48, 304, 12, 'rgba(255,255,255,0.82)', 600) +
    text('and payment in one secured flow.', 48, 322, 12, 'rgba(255,255,255,0.82)', 600) +
    rect(24, 362, 308, 390, C.white, 30, 'none', 1, 'filter="url(#softShadow)"') +
    text('Welcome back', 52, 414, 28, C.ink, 900) +
    text('Login to continue planning your trip.', 52, 442, 14, C.body, 600) +
    input('Email', 'josh@example.com', 52, 498, 252, '@') +
    input('Password', '••••••••', 52, 594, 252, '•') +
    button('Login', 52, 682, 252, C.blue) +
    text('Create account', 178, 774, 14, C.blue, 800, 'middle');
}

function register() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    landscape(24, 72, 308, 202, 'heroSunset') +
    statusBar(true) +
    text('Create your', 48, 180, 31, C.white, 900) +
    text('traveler profile', 48, 216, 31, C.white, 900) +
    rect(24, 300, 308, 462, C.white, 30, 'none', 1, 'filter="url(#softShadow)"') +
    text('Register', 52, 352, 28, C.ink, 900) +
    text('Auth Service stores traveler profile data.', 52, 380, 13, C.body, 600) +
    input('Full name', 'Josh Cruz', 52, 436, 252, '●') +
    input('Email', 'josh@example.com', 52, 532, 252, '@') +
    input('Password', '••••••••', 52, 628, 252, '•') +
    button('Create account', 52, 704, 252, C.rose);
}

function dashboard() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    statusBar(false) +
    text('Hello, Josh', 26, 86, 26, C.ink, 900) +
    text('Where do you want to go?', 26, 112, 14, C.body, 600) +
    iconCircle('J', 318, 82, '#EEF4FF', C.blue) +
    rect(24, 142, 308, 58, C.white, 22, C.line) +
    text('⌕', 54, 178, 18, C.blue, 900, 'middle') +
    text('Search destination', 78, 178, 15, '#98A2B3', 600) +
    landscape(24, 224, 308, 230, 'heroSea') +
    pill('API Gateway', 48, 248, 112, 'rgba(255,255,255,0.86)', C.blue) +
    text('Tokyo', 48, 372, 38, C.white, 900) +
    text('Japan · weather · hotels · guide', 48, 402, 14, 'rgba(255,255,255,0.85)', 700) +
    button('Start search', 48, 482, 260, C.blue) +
    text('Services', 26, 580, 20, C.ink, 900) +
    pill('Auth', 26, 606, 70, '#FEE2E2', C.rose) +
    pill('Maps', 106, 606, 72, '#E0F2FE', C.cyan) +
    pill('Weather', 188, 606, 92, '#FEF3C7', C.orange) +
    pill('Hotels', 26, 650, 84, '#DCFCE7', C.green) +
    pill('Payment', 120, 650, 98, '#EDE9FE', C.violet) +
    bottomNav('Home');
}

function search() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    mobileHeader('Search', 'GET /travel-search?city=Tokyo') +
    rect(24, 138, 308, 58, C.white, 22, C.line) +
    text('Tokyo', 52, 174, 17, C.ink, 800) +
    iconCircle('⌕', 300, 167, '#EEF4FF', C.blue) +
    text('Recommended', 26, 238, 20, C.ink, 900) +
    landscape(24, 266, 224, 280, 'heroSea') +
    rect(266, 266, 66, 132, 'url(#heroForest)', 24) +
    rect(266, 414, 66, 132, 'url(#heroSunset)', 24) +
    text('Tokyo', 48, 468, 32, C.white, 900) +
    text('Japan', 48, 496, 14, 'rgba(255,255,255,0.86)', 700) +
    rect(24, 584, 308, 102, C.white, 24, 'none', 1, 'filter="url(#softShadow)"') +
    text('Combined gateway response', 52, 628, 17, C.ink, 900) +
    text('Maps + Weather + Country + Currency + Hotels', 52, 656, 12, C.body, 700) +
    bottomNav('Search');
}

function weather() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    mobileHeader('Weather', 'Open-Meteo Forecast') +
    rect(24, 144, 308, 264, 'url(#heroSea)', 30) +
    circle(268, 208, 42, 'rgba(255,255,255,0.72)') +
    text('Tokyo', 54, 202, 22, C.white, 900) +
    text('24°C', 52, 292, 66, C.white, 900) +
    text('Light breeze · humidity 62%', 56, 334, 14, 'rgba(255,255,255,0.86)', 700) +
    text('7-day forecast', 26, 462, 20, C.ink, 900) +
    ['M', 'T', 'W', 'T', 'F'].map((d, i) => rect(26 + i * 62, 490, 50, 86, C.white, 18, C.line) + text(d, 51 + i * 62, 524, 13, C.body, 800, 'middle') + text(`${23 + i % 3}°`, 51 + i * 62, 554, 16, C.ink, 900, 'middle')).join('') +
    rect(24, 620, 308, 78, C.white, 24, C.line) +
    text('Trip tip', 52, 654, 15, C.ink, 900) +
    text('Bring a light jacket and transit card.', 52, 678, 13, C.body, 600) +
    bottomNav('Trips');
}

function mapScreen() {
  let grid = '';
  for (let i = 0; i < 6; i++) grid += rect(44 + i * 52, 172, 2, 334, '#C7E3DE', 1);
  for (let i = 0; i < 5; i++) grid += rect(38, 188 + i * 62, 274, 2, '#C7E3DE', 1);
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    mobileHeader('Map', 'Geocoding result') +
    rect(24, 142, 308, 390, '#DDF3EF', 30) +
    grid +
    rect(86, 414, 188, 7, C.rose, 4) +
    rect(138, 224, 7, 194, C.rose, 4) +
    iconCircle('1', 138, 238, C.rose, C.white) +
    iconCircle('2', 274, 418, C.blue, C.white) +
    rect(24, 568, 308, 116, C.white, 24, 'none', 1, 'filter="url(#softShadow)"') +
    text('Tokyo, Japan', 52, 612, 20, C.ink, 900) +
    text('Lat 35.6895 · Lng 139.6917', 52, 642, 13, C.body, 700) +
    button('Find hotels nearby', 52, 708, 252, C.blue);
}

function hotels() {
  const item = (name, area, price, y, grad) =>
    rect(24, y, 308, 118, C.white, 24, 'none', 1, 'filter="url(#softShadow)"') +
    rect(42, y + 18, 82, 82, `url(#${grad})`, 20) +
    text(name, 142, y + 48, 16, C.ink, 900) +
    text(area, 142, y + 72, 12, C.body, 700) +
    text(price, 142, y + 98, 14, C.blue, 900);
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    mobileHeader('Hotels', 'Filtered by Tokyo') +
    rect(24, 138, 308, 58, C.white, 22, C.line) +
    text('May 20 - May 22 · 2 guests', 52, 174, 15, C.ink, 800) +
    item('BugBusters Hotel', 'Shibuya, Tokyo', '$150/night', 234, 'heroSea') +
    item('Tokyo Grand Hotel', 'Chiyoda, Tokyo', '$130/night', 376, 'heroSunset') +
    item('Sakura Stay', 'Asakusa, Tokyo', '$108/night', 518, 'heroForest') +
    bottomNav('Trips');
}

function details() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    rect(0, 0, 356, 380, 'url(#heroSea)', 34) +
    mobileHeader('Hotel Details', '', true) +
    pill('4.6 rating', 42, 294, 92, 'rgba(255,255,255,0.86)', C.blue) +
    rect(0, 334, 356, 482, C.white, 34) +
    text('BugBusters Hotel', 28, 396, 28, C.ink, 900) +
    text('Shibuya, Tokyo · near transit and food districts', 28, 426, 13, C.body, 700) +
    pill('Free Wi-Fi', 28, 468, 94, '#EEF4FF', C.blue) +
    pill('Breakfast', 132, 468, 94, '#ECFDF3', C.green) +
    pill('Flexible', 236, 468, 84, '#FFF7ED', C.orange) +
    text('May 20 - May 22, 2026', 28, 558, 16, C.ink, 900) +
    text('$300 total · 2 nights', 28, 586, 14, C.body, 700) +
    button('Continue booking', 28, 706, 300, C.rose);
}

function booking() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    mobileHeader('Booking', 'POST /booking then /payment') +
    rect(24, 134, 308, 126, C.white, 26, 'none', 1, 'filter="url(#softShadow)"') +
    text('BugBusters Hotel', 52, 184, 18, C.ink, 900) +
    text('Tokyo · May 20-22 · 2 guests', 52, 212, 13, C.body, 700) +
    input('Traveler', 'Josh Cruz', 28, 318, 300, '●') +
    input('Card number', '4242 4242 4242 4242', 28, 414, 300, '◆') +
    rect(28, 526, 300, 126, C.white, 26, C.line) +
    text('Total', 56, 574, 14, C.body, 700) +
    text('$324.00', 56, 616, 32, C.ink, 900) +
    text('Room + taxes', 214, 616, 13, C.body, 700) +
    button('Pay now', 28, 704, 300, C.blue);
}

function confirmation() {
  return rect(0, 0, 356, 816, '#F8FAFC', 34) +
    statusBar(false) +
    circle(178, 174, 72, '#ECFDF3') +
    circle(178, 174, 46, C.green) +
    text('✓', 178, 190, 42, C.white, 900, 'middle') +
    text('Trip confirmed', 178, 294, 30, C.ink, 900, 'middle') +
    text('Booking and payment were processed successfully.', 178, 322, 13, C.body, 700, 'middle') +
    rect(28, 384, 300, 172, C.white, 28, 'none', 1, 'filter="url(#softShadow)"') +
    text('Tokyo, Japan', 56, 436, 20, C.ink, 900) +
    text('BugBusters Hotel', 56, 466, 14, C.body, 800) +
    text('Booking ID: 1', 56, 512, 13, C.blue, 900) +
    pill('Payment paid', 198, 494, 104, '#ECFDF3', C.green) +
    button('Back to dashboard', 28, 692, 300, C.deep);
}

const screens = [
  ['Login', login],
  ['Register', register],
  ['Home Dashboard', dashboard],
  ['Search Destination', search],
  ['Weather View', weather],
  ['Maps Result', mapScreen],
  ['Hotel Listing', hotels],
  ['Hotel Details', details],
  ['Booking Payment', booking],
  ['Booking Confirmation', confirmation],
];

const svgW = COLS * FRAME_W + (COLS - 1) * GAP;
const svgH = 2 * FRAME_H + GAP;
let body = rect(0, 0, svgW, svgH, '#EDEFF5', 0);

screens.forEach(([name, draw], i) => {
  const col = i % COLS;
  const row = Math.floor(i / COLS);
  const x = col * (FRAME_W + GAP);
  const y = row * (FRAME_H + GAP);
  body += `<g id="${esc(name)}" transform="translate(${x},${y})">${phoneFrame(name, draw)}</g>`;
});

const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${svgW}" height="${svgH}" viewBox="0 0 ${svgW} ${svgH}">${gradients}${body}</svg>\n`;

fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, svg, 'utf8');
console.log(OUT);
