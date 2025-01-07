
window.addEventListener("scroll", () => {
    const sections = document.querySelectorAll(".section");
    const triggerHeight = window.innerHeight * 0.8;

    sections.forEach((section) => {
        const sectionTop = section.getBoundingClientRect().top;
        if (sectionTop < triggerHeight) {
            section.classList.add("visible");
        }
    });
});


document.querySelectorAll(".portfolio-item").forEach((item) => {
    item.addEventListener("mouseenter", () => {
        const img = item.querySelector(".portfolio-image");
        img.style.transform = "scale(1.1)";
    });
    item.addEventListener("mouseleave", () => {
        const img = item.querySelector(".portfolio-image");
        img.style.transform = "scale(1)";
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const titles = document.querySelectorAll(".section-title");
    titles.forEach((title, index) => {
        setTimeout(() => {
            title.style.opacity = "1";
            title.style.transform = "translateY(0)";
        }, 300 * index);
    });
});
