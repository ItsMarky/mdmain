async function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = document.getElementById('contact-form');
    const statusDiv = document.getElementById('form-success-message');

    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.textContent = 'Sending...';
    submitButton.disabled = true;

    const response = await fetch(form.action, {
        method: form.method,
        body: new FormData(form),
        headers: {
            'Accept': 'application/json'
        }
    });

    if (response.ok) {
        form.style.display = 'none';
        statusDiv.style.display = 'block';
        setTimeout(() => form.reset(), 500);
    } else {
        alert('Oops! There was an issue sending your form. Please check your details or email me directly.');
        submitButton.textContent = 'Send Inquiry';
        submitButton.disabled = false;
    }
}

const slider = document.getElementById('preview-carousel');
const arrowLeft = document.getElementById('arrow-left');
const arrowRight = document.getElementById('arrow-right');

let isDown = false;
let startX;
let scrollLeft;

function scrollPreviews(direction) {
    if (!slider) return;
    const firstCard = slider.querySelector('.preview-card');
    if (!firstCard) return;
    
    const cardWidth = firstCard.offsetWidth;
    const gap = 15;
    const scrollDistance = cardWidth + gap;
    
    if (direction === 'left') {
        slider.scrollBy({ left: -scrollDistance, behavior: 'smooth' });
    } else if (direction === 'right') {
        slider.scrollBy({ left: scrollDistance, behavior: 'smooth' });
    }
}

function checkArrows() {
    if (!slider || !arrowLeft || !arrowRight) return;

    arrowLeft.disabled = slider.scrollLeft < 5; 
    
    const maxScroll = slider.scrollWidth - slider.clientWidth;
    arrowRight.disabled = slider.scrollLeft > maxScroll - 5;
}

if (slider) {
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.style.cursor = 'grabbing';
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.style.cursor = 'grab';
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.style.cursor = 'grab';
        checkArrows();
    });

    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; 
        slider.scrollLeft = scrollLeft - walk;
    });
    
    checkArrows();
    slider.addEventListener('scroll', checkArrows); 
}