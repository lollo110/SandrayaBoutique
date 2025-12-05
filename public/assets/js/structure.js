// Menu pour connexion/inscription
let omino = document.getElementById("omino");
let panier = document.getElementById("panier");

let show = document.querySelector(".show");
let showPanier = document.querySelector(".showPanier");

// ouverture / fermeture menu
omino.addEventListener("click", function (e) {
    e.stopPropagation();

    showPanier.classList.remove("active");

    show.classList.toggle("active");
});

// --- OUVERTURE / FERMETURE PANIER ---
panier.addEventListener("click", function (e) {
    e.stopPropagation();

 
    show.classList.remove("active");


    showPanier.classList.toggle("active2");
});

// --- CLIC EXTÉRIEUR ---
window.addEventListener("click", function () {
    show.classList.remove("active");
    showPanier.classList.remove("active2");
});

// -------------------------
// CAROUSEL IMAGES
// -------------------------

const left = document.querySelector(".freccia");
const right = document.querySelector(".freccia2");
const container = document.querySelector(".contImage");

let indexImages = 0;

const images = document.querySelectorAll(".exa");
const totalImages = images.length;
const visibleImages = 1;
const maxIndexImages = totalImages - visibleImages;

function updateSlide() {
    if (!container) return;
    const width = container.querySelector(".exa").clientWidth;
    container.style.transform = `translateX(-${indexImages * width}px)`;
    container.style.transition = "2s ease";
}

if (left && right) {
    right.addEventListener("click", () => {
        indexImages = (indexImages < maxIndexImages) ? indexImages + 1 : 0;
        updateSlide();
    });

    left.addEventListener("click", () => {
        indexImages = (indexImages > 0) ? indexImages - 1 : maxIndexImages;
        updateSlide();
    });
}

// Défilement automatique toutes les 5 secondes
setInterval(() => {
    indexImages = (indexImages < maxIndexImages) ? indexImages + 1 : 0;
    updateSlide();
}, 5000);


// -------------------------
// CAROUSEL CARTES
// -------------------------

const leftCards = document.querySelector(".freccia3");
const rightCards = document.querySelector(".freccia4");
const containerCards = document.querySelector(".carte-container");

let indexCards = 0;

const cards = document.querySelectorAll(".carte");
const totalCards = cards.length;
const visibleCards = 3;
const maxIndexCards = totalCards - visibleCards;

function updateSlideCards() {
    if (!containerCards) return;
    const width = containerCards.querySelector(".carte").clientWidth;
    containerCards.style.transform = `translateX(-${indexCards * width}px)`;
    containerCards.style.transition = "2s ease";
}

if (leftCards && rightCards) {
    rightCards.addEventListener("click", () => {
        indexCards = (indexCards < maxIndexCards) ? indexCards + 1 : 0;
        updateSlideCards();
    });

    leftCards.addEventListener("click", () => {
        indexCards = (indexCards > 0) ? indexCards - 1 : maxIndexCards;
        updateSlideCards();
    });
}