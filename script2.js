
document.getElementById("btn3").addEventListener("click", function () {
    const hiddenCategories = document.querySelectorAll(".hid");

    // Loop through hidden categories and reveal them
    hiddenCategories.forEach((category, index) => {
        if (index < 4) { // Adjust this number to show more or fewer categories at once
            category.classList.add("vis");
            category.classList.remove("hid");
        }
    });

    // Hide "Load More" button if all categories are displayed
    if (document.querySelectorAll(".hid").length === 0) {
        this.style.display = "none";
    }
});

