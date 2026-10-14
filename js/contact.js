
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="contact.php"]');

    if (!form) return;

    form.addEventListener('submit', (event) => {
        const name = form.elements.name.value.trim();
        const email = form.elements.email.value.trim();
        const subject = form.elements.subject.value.trim();
        const message = form.elements.message.value.trim();

        const errors = [];

        if (!name || name.length > 100) {
            errors.push('Enter a name of up to 100 characters.');
        }

        if (
            !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ||
            email.length > 254
        ) {
            errors.push('Enter a valid email address.');
        }

        if (!subject || subject.length > 150) {
            errors.push('Enter a subject of up to 150 characters.');
        }

        if (!message || message.length > 5000) {
            errors.push('Enter a message of up to 5000 characters.');
        }

        if (errors.length > 0) {
            event.preventDefault();
            alert(errors.join('\n'));
        }
    });
});
