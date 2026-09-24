document.addEventListener("DOMContentLoaded", function () {

    const cards = document.querySelectorAll(".product-card");

    cards.forEach(function (card, index) {

        card.style.opacity = "0";

        card.style.transform = "translateY(20px)";

        setTimeout(function () {

            card.style.transition =
                "all 0.5s ease";

            card.style.opacity = "1";

            card.style.transform =
                "translateY(0)";

        }, index * 80);

    });

});


function toggleWishlist(button) {

    if (button.innerHTML.includes("♡")) {

        button.innerHTML = "♥";

        button.classList.add("liked");

    } else {

        button.innerHTML = "♡";

        button.classList.remove("liked");

    }

}


function validateCheckout() {

    const phone =
        document.querySelector(
            'input[name="phone"]'
        );

    if (phone) {

        if (phone.value.length < 10) {

            alert(
                "Please enter a valid phone number."
            );

            return false;

        }

    }

    return true;

}


const searchInput =
    document.querySelector(
        ".search-box input"
    );

if (searchInput) {

    searchInput.addEventListener(
        "input",
        function () {

            if (this.value.length > 0) {

                this.style.borderColor =
                    "#7c3aed";

            } else {

                this.style.borderColor =
                    "";

            }

        }
    );

}