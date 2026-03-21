const track = document.querySelector('.track');
    const total = track.children.length;
    const prev = document.querySelector('.prev');
    const next = document.querySelector('.next');
    const PER_VIEW = 3;
    const max = total - PER_VIEW;
    let i = 0;

    function update() {
        track.style.transform = `translateX(-${i * (100 / PER_VIEW)}%)`;
    }

    prev.addEventListener('click', () => { i = i <= 0 ? max : i - 1; update(); });
    next.addEventListener('click', () => { i = i >= max ? 0 : i + 1; update(); });

    update();