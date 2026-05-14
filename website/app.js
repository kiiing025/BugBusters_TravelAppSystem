const views = [...document.querySelectorAll('[data-view]')];
const navLinks = [...document.querySelectorAll('[data-view-link]')];
const openButtons = [...document.querySelectorAll('[data-open-view]')];
const hotelCards = [...document.querySelectorAll('.hotel-card')];
const destinationForm = document.querySelector('#destinationForm');
const destinationInput = document.querySelector('#destinationInput');
const bookingForm = document.querySelector('#bookingForm');
const confirmationBox = document.querySelector('#confirmationBox');
const selectedHotel = document.querySelector('#selectedHotel');
const roomTotal = document.querySelector('#roomTotal');
const grandTotal = document.querySelector('#grandTotal');

const destinationData = {
  tokyo: {
    title: 'Tokyo, Japan',
    country: 'Japan',
    currency: 'JPY',
    summary:
      'Neon streets, transit access, food districts, hotels, live weather, and currency data combined through the gateway.',
  },
  davao: {
    title: 'Davao, Philippines',
    country: 'Philippines',
    currency: 'PHP',
    summary:
      'Island access, mountain views, local hotels, weather conditions, and guide data assembled through one secured route.',
  },
  seoul: {
    title: 'Seoul, South Korea',
    country: 'South Korea',
    currency: 'KRW',
    summary:
      'Culture districts, shopping streets, city routes, weather, currency, and hotel matches returned by the gateway.',
  },
};

function showView(name, shouldScroll = true) {
  views.forEach((view) => {
    view.classList.toggle('is-visible', view.dataset.view === name);
  });

  navLinks.forEach((link) => {
    link.classList.toggle('is-active', link.dataset.viewLink === name);
  });

  const target = document.querySelector(`[data-view="${name}"]`);
  if (shouldScroll && target) target.scrollIntoView({ block: 'start' });
}

function formatMoney(value) {
  return `$${Number(value).toFixed(2)}`;
}

navLinks.forEach((link) => {
  link.addEventListener('click', (event) => {
    event.preventDefault();
    showView(link.dataset.viewLink);
  });
});

openButtons.forEach((button) => {
  button.addEventListener('click', () => showView(button.dataset.openView));
});

hotelCards.forEach((card) => {
  card.addEventListener('click', () => {
    hotelCards.forEach((hotel) => hotel.classList.remove('is-selected'));
    card.classList.add('is-selected');

    const price = Number(card.dataset.price);
    selectedHotel.textContent = card.dataset.hotel;
    roomTotal.textContent = formatMoney(price);
    grandTotal.textContent = formatMoney(price + 24);
    showView('booking');
  });
});

destinationForm.addEventListener('submit', (event) => {
  event.preventDefault();
  const key = destinationInput.value.trim().toLowerCase();
  const result = destinationData[key] || {
    title: `${destinationInput.value.trim() || 'Tokyo'}, Travel Destination`,
    country: 'Detected by Maps API',
    currency: 'From country service',
    summary:
      'The gateway would request maps, weather, country, currency, guide, and hotels data for this destination.',
  };

  document.querySelector('#destinationTitle').textContent = result.title;
  document.querySelector('#destinationSummary').textContent = result.summary;
  document.querySelector('#countryValue').textContent = result.country;
  document.querySelector('#currencyValue').textContent = result.currency;
});

bookingForm.addEventListener('submit', (event) => {
  event.preventDefault();
  confirmationBox.classList.add('is-success');
  confirmationBox.innerHTML = `
    <strong>Booking confirmed.</strong><br>
    Booking ID: 1 · Payment status: paid · Gateway status: success
  `;
});

showView('dashboard', false);
