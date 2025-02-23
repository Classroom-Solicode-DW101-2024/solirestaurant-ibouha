// Array of image sources
const images = [
  "../assets/images/Burgerbar_hamburgers-restaurant-best-burgers-kolksteeg-amsterdam_BURGER_03.png",
  "../assets/images/image2.png",
  "../assets/images/image3.png",
  "../assets/images/image4.png",
];
let currentIndex = 0;
const slideshow = document.getElementById("slideshow");

function changeImage() {
  currentIndex = (currentIndex + 1) % images.length;
  slideshow.style.opacity = "0"; // Fade out effect

  setTimeout(() => {
    slideshow.src = images[currentIndex];
    slideshow.style.opacity = "1"; // Fade in effect
  }, 500);
}

setInterval(changeImage, 3000); // Change image every 3 seconds
