document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector('.slider');
    let counter = 0;

    function nextSlide() {
        if (counter < 2) {
            counter++;
        } else {
            counter = 0;
        }
        updateSlider();
    }

    function prevSlide() {
        if (counter > 0) {
            counter--;
        } else {
            counter = 2;
        }
        updateSlider();
    }

    function updateSlider() {
        const newTransformValue = -counter * 100 + '%';
        slider.style.transform = 'translateX(' + newTransformValue + ')';
    }

    setInterval(nextSlide, 3000); // автоматическая смена слайдов каждые 3 секунды
});
