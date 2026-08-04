 document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".reservation-card").forEach((card) => {
                const fav = card.querySelector(".entete-reserv");
                if (!fav) return;

                card.addEventListener("mouseenter", () => favvisible(fav));
                card.addEventListener("mouseleave", () => favhidden(fav));
            });
        });

        function favvisible(element) {
            element.style.display = "flex";
        }

        function favhidden(element) {
            element.style.display = "none";
        }