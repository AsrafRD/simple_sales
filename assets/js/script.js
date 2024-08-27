document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.product a');

    buttons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            button.classList.add('added');
            setTimeout(() => {
                button.classList.remove('added');
            }, 1000);
        });
    });
});
