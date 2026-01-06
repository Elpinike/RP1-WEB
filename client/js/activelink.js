const homeLink = document.getElementById('home-link');
const servicesLink = document.getElementById('services-link');
const servicesSection = document.getElementById('services');

// Handle scroll behavior
window.addEventListener('scroll', () => {
  const sectionTop = servicesSection.offsetTop - 150; // adjust for navbar height
  const sectionBottom = sectionTop + servicesSection.offsetHeight;
  const scrollY = window.scrollY;

  // Inside "Services" section → highlight Services link
  if (scrollY >= sectionTop && scrollY < sectionBottom) {
    homeLink.classList.remove('active');
    servicesLink.classList.add('active');
  } 
  // Above or below → highlight Accueil link
  else {
    servicesLink.classList.remove('active');
    homeLink.classList.add('active');
  }
});
