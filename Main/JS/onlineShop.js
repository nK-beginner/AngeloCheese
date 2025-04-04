document.addEventListener("DOMContentLoaded", () => {
    const categorySections = document.querySelectorAll('.category-section');

    categorySections.forEach(section => {
        const products = section.querySelectorAll('.forms-container');

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    products.forEach((product, index) => {
                        setTimeout(() => {
                            product.classList.add('show');
                        }, index * 250);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        observer.observe(section);
    });
});