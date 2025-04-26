
async function loadNav() {
    const navPlaceholder = document.getElementById("nav-placeholder");
    const response = await fetch("navbar/nav.html");
    const navHtml = await response.text();
    navPlaceholder.innerHTML = navHtml;


    setActiveLink();
    setActiveLink2();
}

function setActiveLink() {

    const navItems = document.querySelectorAll('.nav-item');


    const currentHash = window.location.hash;


    navItems.forEach(link => {

        if (link.getAttribute('href') === currentHash) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

function setActiveLink2() {

    const navItems = document.querySelectorAll('.nav-item2');


    const currentHash = window.location.hash;


    navItems.forEach(link => {

        if (link.getAttribute('href') === currentHash) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}


window.addEventListener('hashchange', () => {
    setActiveLink();
    setActiveLink2();
});


document.addEventListener('DOMContentLoaded', loadNav);



