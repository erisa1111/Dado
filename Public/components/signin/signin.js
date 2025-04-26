


$(this).addClass("active");
$(document).ready(function() {
   
    // Handle click on the card toggle (icon)
    $(".card-toggle").on("click", function() {
        // Remove the 'active' class from all card toggles and reset circle color
        $(".card-toggle").removeClass("active");
        
        // Add 'active' class to the clicked card toggle to change color and circle
        $(this).addClass("active");
        
        // Remove 'active' class from all cards
        $(".card").removeClass("active");
        
        // Add 'active' class to the target card
        var target = $(this).data("target");
        $(target).addClass("active");
    });
});
async function loadCard() {
    const signPlaceholder = document.getElementById("sign_up");
    

    const response = await fetch("signin/signin.html");
    const signHtml = await response.text();
    signPlaceholder.innerHTML = signHtml;


    const signCssLink = document.createElement('link');
    signCssLink.rel = 'stylesheet';
    signCssLink.href = 'signin/signin.css'; 
    document.head.appendChild(signCssLink);


    const signInScript = document.createElement('script');
    signInScript.src = 'signin/signin.js'; 
    document.body.appendChild(signInScript);
}

document.addEventListener('DOMContentLoaded', loadCard);
