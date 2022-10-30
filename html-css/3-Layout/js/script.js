// Feednack Form
function openForm() {
  document.getElementById("modal").classList.add("modal-show");
}

function closeForm() {
  document.getElementById("modal").classList.remove("modal-show")
}


// Slides
// Slides
var slides = document.querySelectorAll('.slide');
var currentSlide = 0;
var slideInterval = setInterval(nextSlide, 4000);
var next = document.querySelector('.prev');
var previous = document.querySelector('.next');

next.addEventListener("click", nextSlide);
previous.addEventListener("click", previousSlide);

function nextSlide() {
  clearInterval(slideInterval);
  goToSlide(currentSlide + 1);
}
function previousSlide() {
  clearInterval(slideInterval);
  goToSlide(currentSlide - 1);
}
function goToSlide(n) {
  slideInterval = setInterval(nextSlide, 4000);
  slides[currentSlide].classList.remove('show');
  currentSlide = (n + slides.length) % slides.length;
  slides[currentSlide].classList.add('show');
}
