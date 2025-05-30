<?php
use App\Controllers\AuthController;
use App\Controllers\PostsController;
use App\Controllers\LogOutController;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Manually include files
require_once dirname(__DIR__) . '/App/Models/User.php';
require_once dirname(__DIR__) . '/App/Controllers/AuthController.php';
require_once dirname(__DIR__) . '/App/Controllers/LogoutController.php';
require_once dirname(__DIR__) . '/App/Controllers/PostsController.php';
require_once dirname(__DIR__) . '/App/vendor/autoload.php';


// Get the current URL path
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request_uri) {
    
    case '/home':
        require __DIR__ . '/views/home.php'; 
        break;

    case '/signup':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/views/signup.php'; 
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController())->signup();
        }
        break;

    case '/login':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $verified = $_GET['verified'] ?? null;
        $error = $_GET['error'] ?? null;
        include __DIR__ . '/views/login.php'; 
        exit();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        (new AuthController())->login(); 
    }
    break;
     case '/verify-email':
        (new AuthController())->verifyEmail();
        break;

    case '/logout':
        require_once dirname(__DIR__) . '/App/Controllers/LogoutController.php'; // make sure it's included

        (new \App\Controllers\LogoutController())->logout();
        break;  
            
        
    default:
        header("HTTP/1.0 404 Not Found");
        break;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Nanny</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="components/navbar/nav.css">
    <link rel="stylesheet" href="components/signin/signin.css">



    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body onload="loadCard()">

    <header>
        <div id="nav-placeholder"></div>
    </header>
    <main id="about_">

        <div class="about_">
            <img src="/assets/img/main_img.png" alt="">
            <div class="main_text">
                <p class="main_p">Helping families to meet trusted babysitters</p>
                <div class="search_w">
                    <div class="search_welcome"> <i class="fa-solid fa-magnifying-glass"
                            id="search_icon_welcome"></i><input type="search"
                            placeholder="Search for nannies or parents" id="search_place_welcome"></div>
                    <ul id="suggestion_list"></ul>
                </div>


            </div>
        </div>
    </main>


    <section id="features_">
        <div class="features_content">
            <h2 class="features_h2">How Dado simplifies ChildCare</h2>
            <p>Empowering Connections, Ensuring Peace of Mind</p>
            <p>At Dado, we understand the importance of finding the perfect match between families and childminders. Our
                platform is designed to make this journes no just easier, but also safer and more reliable for everyone
                involved</p>
        </div>

        <div class="features_cards">
            <div class="card">
                <img src="/assets/img/post.png" alt="">
                <p>Post your needs</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae, beatae!</p>
            </div>
            <div class="card">
                <img src="/assets/img/recieve.png" alt="">
                <p>Receive proposals</p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Hic, voluptates!
                </p>
            </div>
            <div class="card">
                <img src="/assets/img/select.png" alt="">
                <p>Select your caregiver</p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatem, aspernatur.</p>
            </div>

        </div>

        </div>
        <br><br>

    </section>
    <div id="sign_up"></div>


    <div class="content" id="reviews_">
        <img src="/assets/img/22-Tech-Finds-That-Are-Way-More-Useful-Than-What-You-Have-Right-Now.jpg" alt="">
        <div class="cont_opinions">
            <p id="opinions_p"><strong>What do our <br> customers say?</strong></p>
            <p id="opinions">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum fugiat numquam nobis
                necessitatibus amet dolorem quo ad.</p>
            <div class="opinion_author">
                <div id="author_name">
                    <p>Elisabeth</p>
                    <p>Parent</p>
                </div>
                <div class="opinion_btn">
                    <button id="btn_back"><i class="fa-solid fa-arrow-left"></i></button>
                    <button id="btn_next"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div id="faq_">
        <div class="faq_title">
            <h1 id="title">
                Frequently asked questions
            </h1>
            <p id="answers"> Didnt find the answers here? <br>
                See more frequestly asked questions</p>
            <button id="faq_more">See more</button>
        </div>
        <div class="questions">
            <details>
                <summary>How do I find a babysitter through your website?</summary>
                <div class="faq-content">
                    You can browse profiles, read reviews, and select a babysitter based on your requirements.
                </div>
            </details>
            <details>
                <summary>What qualifications do your babysitters have?</summary>
                <div class="faq-content">
                    All babysitters are screened and have relevant experience, certifications, and references.
                </div>
            </details>
            <details>
                <summary>How do I know if a babysitter is reliable and trustworthy?</summary>
                <div class="faq-content">
                    We carefully vet all babysitters before they are approved to join our platform. Additionally, you
                    can read
                    <a href="#">reviews</a> and ratings from other parents who have used their services.
                </div>
            </details>
            <details>
                <summary>Can I meet the babysitter before booking?</summary>
                <div class="faq-content">
                    Yes, you can arrange a meeting with the babysitter before making a booking.
                </div>
            </details>
            <details>
                <summary>What are the rates for babysitting services?</summary>
                <div class="faq-content">
                    Rates vary based on experience, qualifications, and the number of children. Please refer to
                    individual profiles for details.
                </div>
            </details>
            <details>
                <summary>Are your babysitters trained in CPR and first aid?</summary>
                <div class="faq-content">
                    Yes, most of our babysitters are trained in CPR and first aid. Check their profiles for
                    certifications.
                </div>
            </details>
        </div>
    </div>
    <footer>
        <div class="columns">
            <div class="col1">
                <ul>
                    <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="#AboutUs"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="#HowItWorks"><i class="fas fa-cogs"></i> How it works</a></li>
                    <li><a href="#ContactUs"><i class="fas fa-phone"></i> Contact Us</a></li>

                </ul>
            </div>
            <div class="col2">
                <ul>
                    <li><a href="mailto:dado@gmail.com"><i class="fas fa-envelope"></i> Dado</a></li>

                    <li><a href="#Phonenumber"><i class="fas fa-phone-alt"></i> Phone number</a></li>
                    <li><a href="#SocialMedia"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="#SocialMedia"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
            <div class="col3">
                <ul>
                    <li><a href="#faq"><i class="fas fa-question-circle"></i> FAQ</a></li>
                    <li><a href="#TermsServices"><i class="fas fa-file-contract"></i> Terms of Services</a></li>
                    <li><a href="#PrivacyPolicy"><i class="fas fa-user-shield"></i> Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="rowbottom">
            <p>&copy; 2024 Dado. All rights reserved to Merjeme Bajrami and Erisa Matoshi.</p>
        </div>
    </footer>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="components/navbar/nav.js"></script>
<script src="assets/js/script.js"></script>
<script src="components/signin/signin.js"></script>
</body>

</html>