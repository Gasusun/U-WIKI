const searchInput = document.getElementById("courseSearch");
const courseCards = document.querySelectorAll(".course-card");

searchInput.addEventListener("input", function () {

    const keyword = this.value.toLowerCase().trim();

    courseCards.forEach(card => {

        const courseName = card
            .querySelector("h3")
            .textContent
            .toLowerCase();

        if (courseName.includes(keyword)) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }

    });

});