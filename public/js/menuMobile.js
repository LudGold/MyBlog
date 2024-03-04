document.addEventListener('DOMContentLoaded', function() {
    const burgerIcon = document.querySelector('.burger-icon');
    const mobileMenu = document.querySelector('.mobile-menu');

    burgerIcon.addEventListener('click', function() {
        mobileMenu.classList.toggle('show');
    });

    // Cacher le menu mobile lorsque vous cliquez en dehors du menu ou du burger
    document.addEventListener('click', function(event) {
        if (!mobileMenu.contains(event.target) && !burgerIcon.contains(event.target)) {
            mobileMenu.classList.remove('show');
        }
    });
});

