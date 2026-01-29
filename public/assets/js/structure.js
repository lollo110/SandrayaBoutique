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

let indexCards = 0;

document.querySelectorAll(".collection").forEach(collection => {
    const left = collection.querySelector(".freccia3");
    const right = collection.querySelector(".freccia4");
    const container = collection.querySelector(".carte-container");
    const cards = collection.querySelectorAll(".carte");

    if (!left || !right || !container || cards.length === 0) return;

    let index = 0;
    const visibleCards = 3;
    const maxIndex = cards.length - visibleCards;

    function updateSlide() {
        const width = cards[0].clientWidth;
        container.style.transform = `translateX(-${index * width}px)`;
        container.style.transition = "0.6s ease";
    }

    right.addEventListener("click", () => {
        index = index < maxIndex ? index + 1 : 0;
        updateSlide();
    });

    left.addEventListener("click", () => {
        index = index > 0 ? index - 1 : maxIndex;
        updateSlide();
    });
});

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
        button.addEventListener("click", async () => {
            try {
                await addToCart(button.id, 1); // aggiunge 1 prodotto
                showCartMessage("Produit ajouté au panier !");
            } catch (error) {
                console.error("Erreur lors de l'ajout au panier:", error);
            }
        });
    });
}



const checkoutBtn = document.getElementById("checkout-btn");
const clearCartBtn = document.getElementById("clear-cart-btn");

// Rediriger vers la page checkout
checkoutBtn.addEventListener("click", () => {
    window.location.href = "/panier"; // Change selon ta route Symfony
});

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
        const qtyElem = li.querySelector(".qty");
        const qty = parseInt(qtyElem.textContent.replace("Quantité: ", ""));

        if (qty <= 1) {
            li.remove();
            delete panierMap[id];
            updateTotal();
        } else {
            updateQty(id, -1);
        }
    }

    if (e.target.classList.contains("add-one-btn")) {
        const li = e.target.closest("li");
        const id = li.dataset.id;
        updateQty(id, 1);
    }
});

// AFFICHE PRIX TOTAL PAGE DETAILSPRODUITS -------------------------------


const prixTotalAffiche = document.getElementById("prixTotalAffiche");

let prixUnitaire = 0;
let stockDisponible = 0;

if (quantiteInput) {
    prixUnitaire = parseFloat(quantiteInput.dataset.prix) || 0;
    stockDisponible = parseInt(quantiteInput.dataset.stock) || 0;

    quantiteInput.addEventListener("input", () => {
        let quantite = parseInt(quantiteInput.value) || 1;
        if (quantite < 1) quantite = 1;
        if (quantite > stockDisponible) quantite = stockDisponible;
        quantiteInput.value = quantite;

        if (prixTotalAffiche) {
            prixTotalAffiche.textContent = (prixUnitaire * quantite).toFixed(2);
        }
    });
}

// Ajouter au panier depuis la page detail_produit

if (plusDetails && quantiteInput && prixTotalAffiche) {
    plusDetails.addEventListener("click", async () => {
        let quantite = parseInt(quantiteInput.value) || 1;

        try {
            await addToCart(plusDetails.id, quantite);
            quantiteInput.value = 1;

            if (prixTotalAffiche) {
                prixTotalAffiche.textContent = (prixUnitaire).toFixed(2);
            }
            showCartMessage("Produit ajouté au panier !");
        } catch (error) {
            console.error("Erreur lors de l'ajout au panier:", error);
        }
    });
}


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

function renderCartItem(produit) {
    if (panierMap[produit.id]) {
        const li = panierMap[produit.id];
        li.querySelector(".qty").textContent = `Quantité: ${produit.qty}`;
        li.querySelector(".price").textContent = `Prix: ${(produit.prix * produit.qty).toFixed(2)} €`;
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
                <span class="price">Prix: ${(produit.prix * produit.qty).toFixed(2)} €</span>
                <div class="plusMoins">
                    <button class="remove-btn">-</button>
                    <button class="add-one-btn">+</button>
                </div>
            </div>
        `;
        panierList.appendChild(li);
        panierMap[produit.id] = li;
    }
}

window.addEventListener("DOMContentLoaded", async () => {
    const res = await fetch("/panier/get");
    const data = await res.json();
    data.items.forEach(produit => {
        renderCartItem(produit); // solo render, senza POST
    });
    updateTotal();
});

const cartMessage = document.getElementById("cart-message");

function showCartMessage(msg) {
    if (!cartMessage) return;
    cartMessage.textContent = msg;
    cartMessage.classList.add("show");
    cartMessage.style.display = "block";

    setTimeout(() => {
        cartMessage.classList.remove("show");
        cartMessage.style.display = "none";
    }, 2000);
}




