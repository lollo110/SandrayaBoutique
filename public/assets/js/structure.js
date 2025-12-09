// MENU CONNEXION / PANIER --------------------------------------------
let omino = document.getElementById("omino");
let panier = document.getElementById("panier");
let collection = document.querySelector(".collection");

let show = document.querySelector(".show");
let showPanier = document.querySelector(".showPanier");

omino.addEventListener("click", function (e) {
    e.stopPropagation();

    showPanier.classList.remove("active2");

    show.classList.toggle("active");
});

panier.addEventListener("click", function (e) {
    e.stopPropagation();

    show.classList.remove("active");
    showPanier.classList.toggle("active2");

    if (showPanier.classList.contains("active2")) {
        if (collection) collection.style.zIndex = "-1";
        if (right) right.style.zIndex = "-1";
    } else {
        if (collection) collection.style.zIndex = "1";
        if (right) right.style.zIndex = "2001";
    }
});

window.addEventListener("click", function () {
    show.classList.remove("active");
    showPanier.classList.remove("active2");
    if (collection) {
        collection.style.zIndex = "1";
    }
    if (right) {
        right.style.zIndex = "2001";
    }
});

// CAROUSEL IMAGES ---------------------------------------------

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
    container.style.zIndex = " -1";
}

if (left && right) {
    right.addEventListener("click", () => {
        indexImages = indexImages < maxIndexImages ? indexImages + 1 : 0;
        updateSlide();
    });

    left.addEventListener("click", () => {
        indexImages = indexImages > 0 ? indexImages - 1 : maxIndexImages;
        updateSlide();
    });
}

setInterval(() => {
    indexImages = indexImages < maxIndexImages ? indexImages + 1 : 0;
    updateSlide();
}, 5000);

// CAROUSEL CARTES ---------------------------------------------

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
        indexCards = indexCards < maxIndexCards ? indexCards + 1 : 0;
        updateSlideCards();
    });

    leftCards.addEventListener("click", () => {
        indexCards = indexCards > 0 ? indexCards - 1 : maxIndexCards;
        updateSlideCards();
    });
}

// PRODUIT BANNER ---------------------------------------------------

const banner = document.querySelector(".produitBanner");

window.addEventListener('DOMContentLoaded', function(){
    if(banner){

        banner.classList.add("visible");
    }
})


// DETAIL PRODUIT - IMAGES ---------------------------------------------

const imgBig = document.querySelector(".imgGrand img");
const imgSmall = document.querySelectorAll(".imgPicco img");

if (imgBig && imgSmall)
imgSmall.forEach((img) => {
    img.addEventListener("click", function () {
        imgBig.src = img.src;
    });
});
