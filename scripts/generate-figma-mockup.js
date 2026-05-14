const fs = require('fs');
const path = require('path');

const OUT = path.join(__dirname, '..', 'figma', 'travel-app-ui-mockup.svg');
const W = 1440;
const H = 1024;
const GAP = 90;
const COLS = 5;

const C = {
  ink: '#102A43',
  muted: '#627D98',
  soft: '#F4F7FB',
  line: '#D9E2EC',
  white: '#FFFFFF',
  teal: '#00A896',
  tealDark: '#087F8C',
  coral: '#FF6B5C',
  amber: '#FFC857',
  sky: '#E4F4FF',
  mint: '#E7FFF6',
  blush: '#FFF1EF',
  navy: '#16324F',
  navy2: '#1E466B',
  green: '#2F9E44',
};

function esc(value) {
  return String(value).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
}

function rect(x, y, w, h, fill = C.white, r = 8, stroke = 'none', sw = 1) {
  return `<rect x="${x}" y="${y}" width="${w}" height="${h}" rx="${r}" fill="${fill}" stroke="${stroke}" stroke-width="${sw}"/>`;
}

function circle(cx, cy, r, fill = C.teal, stroke = 'none') {
  return `<circle cx="${cx}" cy="${cy}" r="${r}" fill="${fill}" stroke="${stroke}"/>`;
}

function text(value, x, y, size = 16, fill = C.ink, weight = 400, anchor = 'start') {
  return `<text x="${x}" y="${y}" font-family="Inter, Arial, sans-serif" font-size="${size}" font-weight="${weight}" fill="${fill}" text-anchor="${anchor}">${esc(value)}</text>`;
}

function multi(lines, x, y, size = 16, fill = C.muted, weight = 400, leading = 24) {
  return lines.map((line, i) => text(line, x, y + i * leading, size, fill, weight)).join('');
}

function chip(label, x, y, w, fill = C.mint, color = C.tealDark) {
  return rect(x, y, w, 34, fill, 17) + text(label, x + w / 2, y + 22, 13, color, 700, 'middle');
}

function button(label, x, y, w, fill = C.teal) {
  return rect(x, y, w, 52, fill, 8) + text(label, x + w / 2, y + 33, 15, C.white, 700, 'middle');
}

function input(label, value, x, y, w) {
  return text(label, x, y - 14, 14, C.muted, 700) + rect(x, y, w, 56, C.white, 8, C.line) + text(value, x + 18, y + 35, 16, C.ink, 500);
}

function card(title, body, x, y, w, h, accent = C.teal) {
  return rect(x, y, w, h, C.white, 8, '#E6EDF5') +
    circle(x + 30, y + 30, 8, accent) +
    text(title, x + 52, y + 36, 18, C.ink, 700) +
    multi(body, x + 28, y + 70, 14, C.muted, 400, 21);
}

function brand(x, y, light = false) {
  const main = light ? C.white : C.teal;
  const sub = light ? C.teal : C.white;
  return circle(x + 17, y + 17, 17, main) +
    circle(x + 16, y + 16, 5, sub) +
    rect(x + 20, y + 14, 14, 4, sub, 2) +
    text('BugBusters Travel', x + 48, y + 24, 20, light ? C.white : C.ink, 800);
}

function authHero(title, subtitle) {
  return rect(0, 0, 690, H, C.navy, 0) +
    brand(82, 62, true) +
    rect(82, 126, 526, 714, C.navy2, 8) +
    circle(150, 212, 18, C.amber) +
    circle(488, 344, 24, C.coral) +
    circle(300, 610, 18, C.teal) +
    rect(168, 219, 304, 4, '#DDEBFF', 2) +
    rect(318, 617, 176, 4, '#DDEBFF', 2) +
    rect(122, 692, 224, 78, '#163B5A', 8, '#386384') +
    text('API Gateway', 146, 724, 18, C.white, 700) +
    multi(['One secured entry point', 'for travel data'], 146, 752, 13, '#C9DDF1', 400, 18) +
    text(title, 82, 936, 34, C.white, 800) +
    text(subtitle, 84, 982, 17, '#C9DDF1', 400);
}

function shell(active) {
  const nav = ['Dashboard', 'Search', 'Weather', 'Map', 'Hotels', 'Booking'];
  let s = rect(0, 0, W, H, C.soft, 0) + rect(0, 0, 268, H, C.navy, 0) + brand(32, 34, true);
  nav.forEach((n, i) => {
    const y = 128 + i * 58;
    if (n === active) s += rect(20, y - 22, 228, 44, '#25577C', 8);
    s += circle(52, y - 2, 10, n === active ? C.amber : '#6A8CAF') + text(n, 76, y + 4, 15, C.white, n === active ? 700 : 500);
  });
  s += rect(32, 884, 204, 84, C.navy2, 8) +
    text('Gateway Status', 52, 924, 14, '#C9DDF1', 700) +
    circle(58, 948, 8, C.green) +
    text('Online', 76, 954, 16, C.white, 700) +
    rect(268, 0, W - 268, 88, C.white, 0) +
    text('Travel App API Gateway System', 312, 54, 22, C.ink, 800) +
    rect(1056, 22, 140, 42, C.mint, 8) +
    text('X-API-KEY OK', 1126, 49, 13, C.tealDark, 700, 'middle') +
    circle(1253, 43, 21, C.sky) +
    text('JC', 1253, 49, 14, C.navy, 800, 'middle');
  return s;
}

function login() {
  return authHero('Plan trips with one secure travel gateway.', 'Maps, weather, hotels, bookings, and payments in one API.') +
    text('Welcome back', 820, 212, 42, C.ink, 800) +
    text('Sign in to continue your travel booking flow.', 822, 254, 18, C.muted) +
    input('Email', 'josh@example.com', 820, 328, 420) +
    input('Password', '••••••••', 820, 426, 420) +
    button('Login', 820, 526, 420, C.teal) +
    text('Forgot password?', 820, 620, 14, C.tealDark, 700) +
    text('New traveler?  Create account', 1006, 620, 14, C.muted, 500) +
    card('Demo account ready', ['Use the gateway URL and', 'X-API-KEY header in Postman.'], 820, 728, 420, 92, C.amber);
}

function register() {
  return authHero('Create an account before booking.', 'Auth Service handles registration, login, and profile lookup.') +
    text('Create account', 820, 178, 42, C.ink, 800) +
    text('Register a traveler profile for hotel booking.', 822, 220, 18, C.muted) +
    input('Full name', 'Josh Cruz', 820, 288, 420) +
    input('Email', 'josh@example.com', 820, 386, 420) +
    input('Password', '••••••••', 820, 484, 420) +
    button('Register', 820, 584, 420, C.coral) +
    card('Validation rules', ['Full name, valid email, and a', 'password of at least 6 characters.'], 820, 730, 420, 112, C.teal);
}

function dashboard() {
  const flow = ['Register', 'Search Tokyo', 'Weather', 'Hotel', 'Payment'];
  let s = shell('Dashboard') +
    text('Dashboard', 312, 162, 34, C.ink, 800) +
    text('Start a destination search and monitor each connected service.', 314, 200, 18, C.muted) +
    rect(312, 234, 760, 142, C.white, 8, C.line) +
    text('Where do you want to go?', 342, 292, 24, C.ink, 800) +
    rect(342, 314, 470, 46, C.soft, 8, C.line) +
    text('Tokyo', 362, 344, 16, C.ink, 500) +
    button('Search destination', 832, 314, 190, C.teal) +
    rect(1100, 234, 248, 142, C.navy, 8) +
    text('Gateway Route', 1124, 286, 18, C.white, 700) +
    multi(['/travel-search aggregates', 'maps, weather, country,', 'currency, guide, hotels.'], 1124, 318, 14, '#C9DDF1', 400, 19);
  [
    ['Auth', 'Login and profiles', C.coral],
    ['Maps', 'Geocode destinations', C.teal],
    ['Weather', 'Forecast lookup', C.amber],
    ['Hotels', 'Listings and details', C.tealDark],
    ['Payment', 'Bookings and payment', C.navy],
  ].forEach((v, i) => s += card(v[0], [v[1]], 312 + (i % 3) * 342, 442 + Math.floor(i / 3) * 140, 300, 110, v[2]));
  s += rect(312, 750, 1036, 160, C.white, 8, C.line) + text('Recommended demo flow', 342, 808, 22, C.ink, 800);
  flow.forEach((step, i) => {
    const x = 372 + i * 186;
    s += circle(x, 840, 17, i === 0 ? C.teal : C.sky) + text(String(i + 1), x, 846, 13, i === 0 ? C.white : C.navy, 800, 'middle') + text(step, x, 894, 13, C.muted, 700, 'middle');
    if (i < 4) s += rect(x + 32, 838, 118, 4, C.line, 2);
  });
  return s;
}

function search() {
  return shell('Search') +
    text('Search Destination', 312, 162, 34, C.ink, 800) +
    text('Gateway request: GET /travel-search?city=Tokyo', 314, 200, 18, C.muted) +
    rect(312, 230, 1036, 112, C.white, 8, C.line) +
    input('Destination city', 'Tokyo', 342, 276, 520) +
    button('Search', 900, 276, 180, C.teal) +
    chip('5 APIs', 1104, 286, 86, C.mint, C.tealDark) +
    chip('Hotels', 1198, 286, 86, C.blush, C.coral) +
    rect(312, 392, 640, 420, C.white, 8, C.line) +
    text('Tokyo, Japan', 342, 456, 30, C.ink, 800) +
    multi(['High-energy destination with dense rail access, food districts,', 'temples, shopping, and seasonal events.'], 344, 500, 17, C.muted, 400, 26) +
    circle(858, 464, 38, C.amber) +
    text('JP', 858, 474, 20, C.navy, 800, 'middle') +
    ['Maps', 'Weather', 'Country', 'Currency', 'Guide', 'Hotels'].map((v, i) => chip(v, 342 + (i % 3) * 140, 566 + Math.floor(i / 3) * 50, 112, i % 2 ? C.sky : C.mint, i % 2 ? C.navy : C.tealDark)).join('') +
    text('Combined response', 342, 732, 20, C.ink, 700) +
    multi(['Destination metadata, forecast, exchange rate, guide summary,', 'and hotel availability are assembled by the gateway.'], 342, 766, 15, C.muted, 400, 22) +
    rect(986, 392, 362, 420, C.navy, 8) +
    text('Travel Snapshot', 1022, 456, 24, C.white, 800) +
    multi(['Local currency: JPY', 'Sample conversion: PHP 100', 'Hotel matches: Available', 'Weather: Live forecast'], 1022, 524, 19, C.white, 700, 72);
}

function weather() {
  return shell('Weather') +
    text('Weather View', 312, 162, 34, C.ink, 800) +
    text('Maps coordinates plus Open-Meteo forecast data.', 314, 200, 18, C.muted) +
    rect(312, 230, 500, 300, C.tealDark, 8) +
    text('Tokyo', 350, 292, 26, C.white, 800) +
    text('Current forecast', 350, 326, 16, '#C9FFF3', 500) +
    text('24°C', 350, 432, 76, C.white, 800) +
    text('Light breeze · humidity 62% · chance of rain 12%', 354, 478, 17, '#C9FFF3') +
    circle(721, 359, 45, C.amber) +
    rect(662, 428, 118, 14, '#DDEBFF', 7) +
    rect(846, 230, 502, 300, C.white, 8, C.line) +
    text('Packing suggestions', 880, 292, 24, C.ink, 800) +
    multi(['Light jacket', 'Comfortable walking shoes', 'Portable umbrella', 'Transit card'], 914, 344, 16, C.ink, 500, 50) +
    rect(312, 588, 1036, 210, C.white, 8, C.line) +
    text('7-day forecast', 342, 650, 22, C.ink, 800) +
    ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].map((d, i) => rect(352 + i * 136, 670, 100, 88, i === 0 ? C.mint : C.soft, 8, C.line) + text(d, 402 + i * 136, 714, 14, C.muted, 700, 'middle') + text(`${23 + (i % 3)}°`, 402 + i * 136, 744, 24, C.ink, 800, 'middle')).join('');
}

function mapScreen() {
  let grid = '';
  for (let i = 0; i < 8; i++) grid += rect(350 + i * 92, 260, 2, 548, '#C7E3DE', 1);
  for (let i = 0; i < 6; i++) grid += rect(340, 296 + i * 82, 700, 2, '#C7E3DE', 1);
  return shell('Map') +
    text('Maps Result', 312, 162, 34, C.ink, 800) +
    text('Geocoding result from Maps service displayed as a destination map.', 314, 200, 18, C.muted) +
    rect(312, 230, 774, 610, '#DDF3EF', 8, C.line) +
    grid +
    rect(420, 704, 530, 8, C.coral, 4) +
    rect(556, 346, 8, 354, C.coral, 4) +
    circle(530, 390, 17, C.coral) + text('1', 530, 396, 14, C.white, 800, 'middle') +
    circle(818, 626, 17, C.teal) + text('2', 818, 632, 14, C.white, 800, 'middle') +
    rect(1118, 230, 230, 610, C.white, 8, C.line) +
    text('Location data', 1144, 292, 22, C.ink, 800) +
    multi(['City: Tokyo', 'Country: Japan', 'Latitude: 35.6895', 'Longitude: 139.6917'], 1144, 360, 18, C.ink, 700, 86) +
    button('Find hotels', 1144, 748, 158, C.teal);
}

function hotels() {
  let s = shell('Hotels') +
    text('Hotel Listing', 312, 162, 34, C.ink, 800) +
    text('Results from the Hotels service filtered by city.', 314, 200, 18, C.muted) +
    rect(312, 228, 1036, 86, C.white, 8, C.line) +
    chip('Tokyo', 344, 254, 110, C.mint, C.tealDark) +
    chip('May 20-22', 470, 254, 132, C.sky, C.navy) +
    chip('2 guests', 618, 254, 108, C.blush, C.coral) +
    button('Modify', 1164, 248, 136, C.navy);
  [['BugBusters Hotel', 'Shibuya, Tokyo', '$150/night', '4.6', C.teal], ['Tokyo Grand Hotel', 'Chiyoda, Tokyo', '$130/night', '4.8', C.amber], ['Sakura Stay', 'Asakusa, Tokyo', '$108/night', '4.4', C.coral]].forEach((h, i) => {
    const y = 360 + i * 164;
    s += rect(312, y, 750, 128, C.white, 8, C.line) + rect(336, y + 22, 120, 84, ['#E3F8F2', '#FFF7D6', '#FFE8E4'][i], 8) + text(h[0], 484, y + 52, 22, C.ink, 800) + text(h[1], 486, y + 82, 15, C.muted) + chip(`Rating ${h[3]}`, 486, y + 92, 104, C.mint, C.tealDark) + text(h[2], 1010, y + 60, 22, C.ink, 800, 'end') + button('View details', 876, y + 78, 132, h[4]);
  });
  return s + rect(1094, 360, 254, 456, C.navy, 8) + text('Booking summary', 1124, 420, 22, C.white, 800) + multi(['Selected city: Tokyo', 'Estimated stay: 2 nights', 'Next API call: POST /booking'], 1124, 492, 18, C.white, 700, 84);
}

function details() {
  return shell('Hotels') +
    text('Hotel Details', 312, 162, 34, C.ink, 800) +
    text('Detailed hotel response before creating a booking.', 314, 200, 18, C.muted) +
    rect(312, 230, 646, 320, '#E3F8F2', 8) +
    rect(984, 230, 364, 320, C.white, 8, C.line) +
    text('BugBusters Hotel', 342, 628, 32, C.ink, 800) +
    text('Shibuya, Tokyo · Rating 4.6 · Near transit and food districts', 344, 666, 17, C.muted) +
    chip('Free Wi-Fi', 342, 698, 144, C.mint, C.tealDark) +
    chip('Breakfast', 502, 698, 144, C.sky, C.navy) +
    chip('Transit nearby', 342, 746, 144, C.mint, C.tealDark) +
    chip('Flexible booking', 502, 746, 144, C.sky, C.navy) +
    text('Price', 1020, 286, 15, C.muted, 700) +
    text('$150', 1020, 336, 42, C.ink, 800) +
    text('per night', 1160, 334, 15, C.muted) +
    text('Dates', 1020, 418, 15, C.muted, 700) +
    text('May 20 - May 22, 2026', 1020, 446, 18, C.ink, 700) +
    text('Total', 1020, 506, 15, C.muted, 700) +
    text('$300.00', 1020, 540, 28, C.ink, 800) +
    button('Continue booking', 1020, 604, 280, C.coral) +
    rect(312, 818, 1036, 82, C.white, 8, C.line) +
    text('API path', 342, 870, 14, C.muted, 700) +
    text('GET /hotels/{id} then POST /booking', 448, 870, 20, C.ink, 700);
}

function booking() {
  return shell('Booking') +
    text('Booking Payment', 312, 162, 34, C.ink, 800) +
    text('Creates a booking, then submits payment through Payment service.', 314, 200, 18, C.muted) +
    rect(312, 228, 660, 614, C.white, 8, C.line) +
    text('Traveler details', 348, 288, 24, C.ink, 800) +
    input('Full name', 'Josh Cruz', 348, 338, 270) +
    input('Email', 'josh@example.com', 644, 338, 270) +
    input('Check in', '2026-05-20', 348, 446, 270) +
    input('Check out', '2026-05-22', 644, 446, 270) +
    text('Payment method', 348, 584, 24, C.ink, 800) +
    input('Card number', '4242 4242 4242 4242', 348, 634, 566) +
    input('Expiry', '12/28', 348, 742, 270) +
    input('CVC', '123', 644, 742, 270) +
    rect(1004, 228, 344, 614, C.navy, 8) +
    text('Order summary', 1040, 288, 24, C.white, 800) +
    multi(['Hotel: BugBusters Hotel', 'Nights: 2', 'Room total: $300.00', 'Taxes: $24.00'], 1040, 360, 19, C.white, 700, 72) +
    text('Total', 1040, 674, 16, '#C9DDF1', 700) +
    text('$324.00', 1040, 716, 34, C.white, 800) +
    button('Pay now', 1040, 756, 260, C.coral);
}

function confirmation() {
  return shell('Booking') +
    rect(312, 150, 1036, 240, C.mint, 8) +
    circle(392, 240, 36, C.teal) +
    text('✓', 392, 252, 34, C.white, 800, 'middle') +
    text('Booking confirmed', 458, 242, 38, C.ink, 800) +
    text('Hotel booking and payment were processed successfully through the gateway.', 460, 284, 18, C.muted) +
    chip('Booking ID: 1', 460, 312, 142, C.white, C.tealDark) +
    chip('Payment: Paid', 622, 312, 132, C.white, C.green) +
    rect(312, 444, 498, 290, C.white, 8, C.line) +
    text('Trip itinerary', 348, 504, 24, C.ink, 800) +
    multi(['Destination: Tokyo, Japan', 'Hotel: BugBusters Hotel', 'Dates: May 20 - May 22, 2026'], 348, 562, 18, C.ink, 700, 58) +
    rect(850, 444, 498, 290, C.white, 8, C.line) +
    text('Demo talking points', 886, 504, 24, C.ink, 800) +
    multi(['All client traffic enters API Gateway', 'Gateway forwards internal secrets', 'External APIs enrich search results', 'Payment service updates booking status'], 914, 562, 16, C.ink, 500, 44) +
    button('Back to dashboard', 312, 800, 220, C.navy) +
    button('New search', 552, 800, 220, C.teal);
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

const svgW = COLS * W + (COLS - 1) * GAP;
const svgH = 2 * H + GAP;
let body = rect(0, 0, svgW, svgH, '#EFEFEF', 0);

screens.forEach(([name, draw], i) => {
  const col = i % COLS;
  const row = Math.floor(i / COLS);
  const x = col * (W + GAP);
  const y = row * (H + GAP);
  body += `<g id="${esc(name)}" transform="translate(${x},${y})">${rect(0, 0, W, H, C.soft, 0)}${draw()}</g>`;
});

const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${svgW}" height="${svgH}" viewBox="0 0 ${svgW} ${svgH}">${body}</svg>\n`;

fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, svg, 'utf8');
console.log(OUT);
