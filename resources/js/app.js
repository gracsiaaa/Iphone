import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.toggle);
            target?.classList.toggle('hidden');
        });
    });

    window.setTimeout(() => {
        document.querySelectorAll('[data-flash]').forEach((element) => element.remove());
    }, 5000);
});
