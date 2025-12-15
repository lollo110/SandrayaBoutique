// MENU CONNEXION / PANIER --------------------------------------------
let omino = document.getElementById("omino");
let panier = document.getElementById("panier");
let collection = document.querySelector(".collection");

let show = document.querySelector(".show");
let showPanier = document.querySelector(".showPanier");

showPanier.addEventListener("click", function (e) {
    e.stopPropagation();
});

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

window.addEventListener("DOMContentLoaded", function () {
    if (banner) {
        banner.classList.add("visible");
    }
});

// DETAIL PRODUIT - IMAGES ---------------------------------------------

const imgBig = document.querySelector(".imgGrand img");
const imgSmall = document.querySelectorAll(".imgPicco img");

if (imgBig && imgSmall)
    imgSmall.forEach((img) => {
        img.addEventListener("click", function () {
            imgBig.src = img.src;
        });
    });

// ADD TO PANIER --------------------------------

let plusDetails = document.querySelector(".addPanier");
let plus = document.querySelectorAll(".addToCart");
let panierList = document.getElementById("panier-list");
let totalQtyElem = document.getElementById("total-qty");
let totalPriceElem = document.getElementById("total-price");
let panierMap = {};

const quantiteInput = document.getElementById("quantite-produit");

document.querySelectorAll("#panier-list li").forEach((li) => {
    panierMap[li.dataset.id] = li;
});

async function addToCart(id, qtyToAdd) {
    if (isNaN(qtyToAdd) || qtyToAdd < 1) {
        qtyToAdd = 1;
    }
    try {
        const res = await fetch("/panier/ajouter/" + id, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ qty: qtyToAdd }),
        });

        if (!res.ok) throw new Error("Erreur ajout panier");

        const data = await res.json();
        const produit = data.produit;

        if (panierMap[produit.id]) {
            const li = panierMap[produit.id];
            li.querySelector(".qty").textContent = `Quantité: ${produit.qty}`;
            li.querySelector(".price").textContent = `Prix: ${(
                produit.prix * produit.qty
            ).toFixed(2)} €`;
        } else {
            const li = document.createElement("li");
            li.dataset.id = produit.id;
            li.dataset.price = produit.prix;
            li.innerHTML = `
            <div class="imgPanier">
                <img src="${produit.images[0]}" alt="${produit.nom}" />
            </div>
            <div class="textPanier">
                <span class="name">${produit.nom}</span>
                <span class="qty">Quantité: ${produit.qty}</span>
                <span class="price">Prix: ${(
                    produit.prix * produit.qty
                ).toFixed(2)} €</span>
                <div class="plusMoins">
				<button class="remove-btn">-</button>
                <button class="add-one-btn">+</button>
				</div>
                </div>
            `;
            panierList.appendChild(li);
            panierMap[produit.id] = li;
        }

        updateTotal();
    } catch (error) {
        console.error(error);
    }
}

async function updateQty(id, change) {
    try {
        const res = await fetch("/panier/ajouter/" + id, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ qty: change }),
        });

        if (!res.ok) throw new Error("Erreur mise à jour panier");

        const data = await res.json();
        const produit = data.produit;
        if (produit.qty <= 0) {
            const li = panierMap[produit.id];
            li.remove();
            delete panierMap[produit.id];
        } else {
            const li = panierMap[produit.id];
            li.querySelector(".qty").textContent = `Quantité: ${produit.qty}`;
            li.querySelector(".price").textContent = `Prix: ${(
                produit.prix * produit.qty
            ).toFixed(2)} €`;
        }

        updateTotal();
    } catch (error) {
        console.error(error);
    }
}

function updateTotal() {
    let totalQty = 0;
    let totalPrice = 0;

    Object.values(panierMap).forEach((li) => {
        const qtyGet = li
            .querySelector(".qty")
            .textContent.replace("Quantité: ", "");

        const qty = parseInt(qtyGet);
        const datasetPrice = parseFloat(li.dataset.price);
        const price = datasetPrice * qty;
        totalQty += qty;
        totalPrice += price;
    });
    totalQtyElem.textContent = totalQty;
    totalPriceElem.textContent = totalPrice.toFixed(2);
}

if (plus) {
    plus.forEach((button) => {
        button.addEventListener("click", () => addToCart(button.id));
    });
}

if (plusDetails) {
    plusDetails.addEventListener("click", async () => {
        const quantite = parseInt(quantiteInput.value);

        try {
            await addToCart(plusDetails.id, quantite);
            quantiteInput.value = 1;
            prixTotalAffiche.textContent = prixUnitaire;
        } catch (error) {
            console.error("Erreur lors de l'ajout au panier:", error);
        }
    });
}

const checkoutBtn = document.getElementById("checkout-btn");
const clearCartBtn = document.getElementById("clear-cart-btn");

// // Rediriger vers la page checkout
// checkoutBtn.addEventListener("click", () => {
//     window.location.href = "/checkout"; // Change selon ta route Symfony
// });

clearCartBtn.addEventListener("click", async () => {
    try {
        const res = await fetch("/panier/vider", { method: "POST" });
        if (!res.ok) throw new Error("Erreur lors de la vidange du panier");

        Object.values(panierMap).forEach((li) => li.remove());
        panierMap = {};

        updateTotal();
    } catch (error) {
        console.error(error);
    }
});

panierList.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-btn")) {
        const li = e.target.closest("li");
        const id = li.dataset.id;
        updateQty(id, -1);
    }

    if (e.target.classList.contains("add-one-btn")) {
        const li = e.target.closest("li");
        const id = li.dataset.id;
        updateQty(id, 1);
    }
});

// AFFICHE PRIX TOTAL PAGE DETAILSPRODUITS -------------------------------

const prixTotalAffiche = document.getElementById("prixTotalAffiche");

const prixUnitaire = parseFloat(quantiteInput.dataset.prix);

const stockDisponible = parseInt(quantiteInput.dataset.stock);

function updateTotalPrice() {
    let quantite = parseInt(quantiteInput.value);

    if (isNaN(quantite) || quantite < 1) {
        quantite = 1;
        quantiteInput.value = 1;
    }

    if (quantite > stockDisponible) {
        quantite = stockDisponible;

        console.warn(
            `Seulament ${stockDisponible} articles disponibles en stock`
        );
    }

    quantiteInput.value = quantite;

    const nouveauPrixTotal = prixUnitaire * quantite;

    if (prixTotalAffiche) {
        prixTotalAffiche.textContent = nouveauPrixTotal.toFixed(2);
    }
}

if (quantiteInput) {
    quantiteInput.addEventListener("input", updateTotalPrice);
}
