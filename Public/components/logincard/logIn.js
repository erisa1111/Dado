
var signInButton = document.getElementById('signIn');
var container = document.getElementById('container');


signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});
