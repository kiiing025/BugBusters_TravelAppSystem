const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '..', 'figma', 'travel-app-website-ui-mockup.svg');
const W = 1440;
const H = 1024;
const GAP = 90;
const COLS = 2;

const C = {
  bg: '#F5F7FB',
  surface: '#FFFFFF',
  ink: '#111827',
  body: '#667085',
  line: '#EAECF0',
  deep: '#0B1220',
  panel: '#1F2937',
  blue: '#2563EB',
  blueSoft: '#EEF4FF',
  cyan: '#06B6D4',
  green: '#12B76A',
  orange: '#F97316',
  rose: '#F43F5E',
  yellow: '#FBBF24',
};

const defs = `
  <defs>
    <linearGradient id="tokyo" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#0EA5E9"/>
      <stop offset="45%" stop-color="#2563EB"/>
      <stop offset="100%" stop-color="#F43F5E"/>
    </linearGradient>
    <linearGradient id="weather" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#38BDF8"/>
      <stop offset="100%" stop-color="#2563EB"/>
    </linearGradient>
    <linearGradient id="hotelA" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#FDE68A"/>
      <stop offset="100%" stop-color="#F97316"/>
    </linearGradient>
    <linearGradient id="hotelB" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#A7F3D0"/>
      <stop offset="100%" stop-color="#12B76A"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="20" stdDeviation="24" flood-color="#101828" flood-opacity="0.12"/>
    </filter>
    <filter id="softShadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="10" stdDeviation="14" flood-color="#101828" flood-opacity="0.09"/>
    </filter>
  </defs>
`;

function esc(value) {
  return String(value).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
}

function rect(x, y, w, h, fill = C.surface, r = 20, stroke = 'none', sw = 1, extra = '') {
  return `<rect x="${x}" y="${y}" width="${w}" height="${h}" rx="${r}" fill="${fill}" stroke="${stroke}" stroke-width="${sw}" ${extra}/>`;
}

function circle(cx, cy, r, fill = C.blue) {
  return `<circle cx="${cx}" cy="${cy}" r="${r}" fill="${fill}"/>`;
}

function text(value, x, y, size = 16, fill = C.ink, weight = 600, anchor = 'start') {
  return `<text x="${x}" y="${y}" font-family="Inter, SF Pro Display, Arial, sans-serif" font-size="${size}" font-weight="${weight}" fill="${fill}" text-anchor="${anchor}">${esc(value)}</text>`;
}

function lines(items, x, y, size = 16, fill = C.body, weight = 500, leading = 24) {
  return items.map((line, i) => text(line, x, y + i * leading, size, fill, weight)).join('');
}

function pill(label, x, y, w, fill = C.blueSoft, color = C.blue) {
  return rect(x, y, w, 34, fill, 17) + text(label, x + w / 2, y + 22, 12, color, 800, 'middle');
}

function button(label, x, y, w, fill = C.blue, color = '#FFFFFF') {
  return rect(x, y, w, 52, fill, 16, 'none', 1, 'filter="url(#softShadow)"') +
    text(label, x + w / 2, y + 33, 15, color, 900, 'middle');
}

function input(label, value, x, y, w) {
  return text(label, x, y - 12, 13, C.body, 800) +
    rect(x, y, w, 54, '#FFFFFF', 16, C.line) +
    text(value, x + 16, y + 34, 15, C.ink, 700);
}

function brand(x, y) {
  return circle(x + 21, y + 21, 21, 'url(#weather)') +
    text('B', x + 21, y + 28, 15, '#FFFFFF', 900, 'middle') +
    text('BugBusters', x + 54, y + 17, 18, '#FFFFFF', 900) +
    text('Travel Gateway', x + 54, y + 38, 12, 'rgba(255,255,255,0.7)', 800);
}

function sidebar(active) {
  const links = ['Dashboard', 'Destination Search', 'Weather & Map', 'Hotels', 'Booking'];
  let s = rect(0, 0, 286, H, C.deep, 0) + brand(22, 28);
  links.forEach((label, i) => {
    const y = 98 + i * 54;
    const on = label === active;
    if (on) s += rect(22, y, 242, 46, 'rgba(255,255,255,0.10)', 16);
    s += circle(40, y + 23, 5, on ? C.yellow : '#667085') + text(label, 56, y + 29, 14, '#FFFFFF', on ? 900 : 700);
  });
  s += rect(22, 762, 242, 210, 'rgba(255,255,255,0.08)', 20, 'rgba(255,255,255,0.14)') +
    pill('Online', 43, 784, 60, '#FFFFFF', C.blue) +
    text('API Gateway', 43, 850, 22, '#FFFFFF', 900) +
    lines(['One public URL, one API key,', 'and secured internal service calls.'], 43, 878, 13, 'rgba(255,255,255,0.74)', 600, 21) +
    pill('X-API-KEY', 43, 920, 86, 'rgba(255,255,255,0.1)', '#FFFFFF');
  return s;
}

function topbar(title, subtitle = 'Travel App API Gateway System') {
  return text(subtitle, 340, 40, 12, C.blue, 900) +
    text(title, 340, 90, 44, C.ink, 900) +
    rect(1222, 56, 164, 62, C.surface, 18, C.line, 1, 'filter="url(#softShadow)"') +
    rect(1240, 70, 36, 36, C.blueSoft, 12) +
    text('JC', 1258, 93, 15, C.blue, 900, 'middle') +
    text('Josh Cruz', 1292, 84, 15, C.ink, 900) +
    text('Demo traveler', 1292, 104, 12, C.body, 700);
}

function abstractPhoto(x, y, w, h, grad = 'tokyo') {
  return rect(x, y, w, h, `url(#${grad})`, 28) +
    circle(x + w - 98, y + 96, 42, 'rgba(255,255,255,0.34)') +
    `<path d="M${x} ${y + h} C${x + w * .18} ${y + h * .58},${x + w * .32} ${y + h * .6},${x + w * .48} ${y + h} Z" fill="rgba(255,255,255,0.28)"/>` +
    `<path d="M${x + w * .25} ${y + h} C${x + w * .52} ${y + h * .34},${x + w * .76} ${y + h * .42},${x + w} ${y + h} Z" fill="rgba(255,255,255,0.18)"/>` +
    `<path d="M${x + 42} ${y + h - 90} C${x + 150} ${y + h - 136},${x + 238} ${y + h - 48},${x + 340} ${y + h - 108} C${x + 410} ${y + h - 150},${x + 494} ${y + h - 88},${x + w - 54} ${y + h - 132}" stroke="rgba(255,255,255,0.45)" stroke-width="7" fill="none" stroke-linecap="round"/>`;
}

function frame(name, active, content) {
  return rect(0, 0, W, H, C.bg, 0) + sidebar(active) + topbar(name) + content;
}

function serviceTile(num, title, body, x) {
  return rect(x, 664, 196, 164, C.surface, 22, C.line, 1) +
    rect(x + 20, 684, 36, 36, C.blueSoft, 12) +
    text(num, x + 38, 708, 13, C.blue, 900, 'middle') +
    text(title, x + 20, 756, 20, C.ink, 900) +
    lines(body, x + 20, 782, 14, C.body, 600, 21);
}

function dashboard() {
  return frame('Plan, search, book, and pay', 'Dashboard',
    rect(340, 174, 1046, 466, C.surface, 30, 'none', 1, 'filter="url(#shadow)"') +
    text('FEATURED DESTINATION', 398, 244, 12, C.blue, 900) +
    text('Tokyo,', 398, 318, 76, C.ink, 900) +
    text('Japan', 398, 386, 76, C.ink, 900) +
    lines(['A modern travel search experience using', 'maps, weather, hotels, booking,', 'and payment services.'], 398, 434, 18, C.body, 500, 28) +
    button('Search Tokyo', 398, 532, 148, C.blue) +
    button('Open booking', 558, 532, 150, '#FFFFFF', C.ink) +
    abstractPhoto(822, 174, 564, 466, 'tokyo') +
    serviceTile('01', 'Auth', ['Register, login,', 'profile lookup.'], 340) +
    serviceTile('02', 'Maps', ['City geocoding', 'and coordinates.'], 553) +
    serviceTile('03', 'Weather', ['Live forecast from', 'Open-Meteo.'], 766) +
    serviceTile('04', 'Hotels', ['Listings, details,', 'and prices.'], 979) +
    serviceTile('05', 'Payment', ['Booking and', 'payment status.'], 1192)
  );
}

function search() {
  return frame('Destination Search', 'Destination Search',
    rect(340, 150, 1046, 90, C.surface, 24, C.line) +
    input('Destination city', 'Tokyo', 370, 190, 520) +
    button('Search', 920, 186, 152, C.blue) +
    pill('5 APIs', 1104, 196, 86, '#ECFDF3', C.green) +
    pill('Hotels', 1200, 196, 86, '#FFF7ED', C.orange) +
    rect(340, 292, 664, 422, C.surface, 28, C.line, 1, 'filter="url(#softShadow)"') +
    abstractPhoto(360, 312, 280, 382, 'tokyo') +
    pill('Aggregated result', 672, 338, 132, C.blueSoft, C.blue) +
    text('Tokyo, Japan', 672, 392, 34, C.ink, 900) +
    lines(['Neon streets, transit access, food districts, hotels,', 'weather, and currency data combined through the gateway.'], 672, 430, 16, C.body, 600, 25) +
    pill('Maps', 672, 520, 70, C.blueSoft, C.blue) +
    pill('Weather', 752, 520, 90, '#FEF3C7', C.orange) +
    pill('Country', 852, 520, 88, '#ECFDF3', C.green) +
    pill('Currency', 672, 568, 96, '#FCE7F3', C.rose) +
    pill('Hotels', 778, 568, 80, '#E0F2FE', C.cyan) +
    rect(1032, 292, 354, 422, C.surface, 28, C.line, 1, 'filter="url(#softShadow)"') +
    text('Gateway response', 1062, 350, 26, C.ink, 900) +
    lines(['Country        Japan', 'Currency       JPY', 'Hotels         3 available', 'Status         Success'], 1062, 416, 18, C.ink, 800, 62)
  );
}

function weatherMap() {
  let grid = '';
  for (let i = 0; i < 8; i++) grid += rect(774 + i * 54, 220, 2, 320, '#C7E3DE', 1);
  for (let i = 0; i < 6; i++) grid += rect(754, 236 + i * 54, 454, 2, '#C7E3DE', 1);
  return frame('Weather & Map Result', 'Weather & Map',
    rect(340, 174, 386, 514, 'url(#weather)', 30, 'none', 1, 'filter="url(#shadow)"') +
    pill('Live forecast', 376, 226, 116, 'rgba(255,255,255,0.18)', '#FFFFFF') +
    text('Tokyo', 376, 292, 34, '#FFFFFF', 900) +
    text('24°C', 376, 398, 90, '#FFFFFF', 900) +
    text('Light breeze · humidity 62%', 382, 446, 16, 'rgba(255,255,255,0.84)', 700) +
    ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'].map((d, i) => rect(376 + i * 62, 544, 48, 78, 'rgba(255,255,255,0.17)', 18) + text(d, 400 + i * 62, 576, 13, '#FFFFFF', 800, 'middle') + text(`${23 + i % 3}°`, 400 + i * 62, 604, 16, '#FFFFFF', 900, 'middle')).join('') +
    rect(754, 174, 632, 514, C.surface, 30, C.line, 1, 'filter="url(#shadow)"') +
    rect(754, 174, 632, 356, '#DDF3EF', 30) +
    grid +
    rect(890, 422, 260, 8, C.rose, 4) +
    rect(954, 258, 8, 168, C.rose, 4) +
    circle(958, 270, 22, C.rose) + text('1', 958, 276, 13, '#FFFFFF', 900, 'middle') +
    circle(1154, 426, 22, C.blue) + text('2', 1154, 432, 13, '#FFFFFF', 900, 'middle') +
    text('Geocoding result', 794, 594, 26, C.ink, 900) +
    text('Latitude 35.6895 · Longitude 139.6917', 794, 626, 16, C.body, 700)
  );
}

function hotels() {
  function hotel(name, area, price, y, grad) {
    return rect(340, y, 800, 154, C.surface, 26, C.line, 1, 'filter="url(#softShadow)"') +
      abstractPhoto(360, y + 18, 170, 118, grad) +
      pill('4.6 rating', 558, y + 28, 94, C.blueSoft, C.blue) +
      text(name, 558, y + 76, 28, C.ink, 900) +
      text(area, 558, y + 106, 15, C.body, 700) +
      text(price, 1018, y + 88, 24, C.blue, 900, 'end');
  }
  return frame('Hotel Listing', 'Hotels',
    rect(340, 148, 1046, 82, C.surface, 24, C.line) +
    pill('Tokyo', 370, 172, 82, '#ECFDF3', C.green) +
    pill('May 20-22', 464, 172, 118, C.blueSoft, C.blue) +
    pill('2 guests', 594, 172, 92, '#FFF7ED', C.orange) +
    button('Modify', 1238, 163, 116, C.deep) +
    hotel('BugBusters Hotel', 'Shibuya, Tokyo · near transit', '$150/night', 282, 'tokyo') +
    hotel('Tokyo Grand Hotel', 'Chiyoda, Tokyo · business district', '$130/night', 466, 'hotelA') +
    hotel('Sakura Stay', 'Asakusa, Tokyo · quiet neighborhood', '$108/night', 650, 'hotelB') +
    rect(1172, 282, 214, 338, C.deep, 28, 'none', 1, 'filter="url(#softShadow)"') +
    text('Booking', 1202, 338, 24, '#FFFFFF', 900) +
    lines(['Selected city', 'Tokyo', 'Estimated stay', '2 nights', 'Next API call', 'POST /booking'], 1202, 392, 15, '#FFFFFF', 700, 38)
  );
}

function booking() {
  return frame('Booking & Payment', 'Booking',
    rect(340, 150, 654, 620, C.surface, 28, C.line, 1, 'filter="url(#softShadow)"') +
    text('Traveler details', 376, 214, 26, C.ink, 900) +
    input('Full name', 'Josh Cruz', 376, 274, 270) +
    input('Email', 'josh@example.com', 672, 274, 270) +
    input('Check in', '2026-05-20', 376, 382, 270) +
    input('Check out', '2026-05-22', 672, 382, 270) +
    text('Payment method', 376, 524, 26, C.ink, 900) +
    input('Card number', '4242 4242 4242 4242', 376, 584, 566) +
    button('Pay now', 376, 690, 566, C.blue) +
    rect(1030, 150, 356, 620, C.deep, 28, 'none', 1, 'filter="url(#softShadow)"') +
    pill('Selected hotel', 1066, 204, 116, 'rgba(255,255,255,0.14)', '#FFFFFF') +
    text('BugBusters Hotel', 1066, 270, 30, '#FFFFFF', 900) +
    text('Tokyo · May 20-22 · 2 guests', 1066, 302, 15, 'rgba(255,255,255,0.72)', 700) +
    lines(['Room total      $300.00', 'Taxes           $24.00'], 1066, 388, 18, '#FFFFFF', 800, 62) +
    rect(1066, 548, 256, 1, 'rgba(255,255,255,0.22)', 1) +
    text('Total', 1066, 604, 18, 'rgba(255,255,255,0.72)', 800) +
    text('$324.00', 1066, 654, 42, '#FFFFFF', 900) +
    rect(1066, 704, 256, 42, '#ECFDF3', 16) +
    text('Booking confirmed after payment', 1194, 731, 13, C.green, 900, 'middle')
  );
}

const screens = [
  ['Dashboard', dashboard],
  ['Destination Search', search],
  ['Weather & Map', weatherMap],
  ['Hotels', hotels],
  ['Booking & Payment', booking],
];

const svgW = COLS * W + (COLS - 1) * GAP;
const rows = Math.ceil(screens.length / COLS);
const svgH = rows * H + (rows - 1) * GAP;
let body = rect(0, 0, svgW, svgH, '#EDEFF5', 0);

screens.forEach(([name, draw], i) => {
  const col = i % COLS;
  const row = Math.floor(i / COLS);
  const x = col * (W + GAP);
  const y = row * (H + GAP);
  body += `<g id="${esc(name)}" transform="translate(${x},${y})">${draw()}</g>`;
});

const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${svgW}" height="${svgH}" viewBox="0 0 ${svgW} ${svgH}">${defs}${body}</svg>\n`;

fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, svg, 'utf8');
console.log(OUT);
