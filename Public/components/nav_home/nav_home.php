<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updated Navbar</title>
    <link rel="stylesheet" href="/components/nav_home/nav_home.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>

<body>
    <nav class="stroke">

        <div class="search-container">
            <i class="fa-solid fa-magnifying-glass" id="search_icon"></i>
            <input type="text" placeholder="Search..." id="search-bar">
            <ul id="suggestion-list"></ul> <!-- This will hold the suggestions -->
        </div>



        <div class="links">
            <ul class="left_nav ubuntu-medium">
                <li><a href="home.php" class="nav-item" id="home">Home</a></li>
                <li><a href="notifications.php" class="nav-item" id="notifications">Notifications</a></li>
                <li><a href="connections.php" class="nav-item" id="connections">Connections</a></li>
                <li><a href="profile.php" class="nav-item" id="profile">Profile</a></li>
            </ul>
        </div>
        <div class="right_nav">

            <ul>
                <li>
                    <div class="right_icons">


                        <div class="rating-icon">
                            <img src="/../assets/img/rate.png" alt="">
                            <div class="dropdown_rating" id="ratingDropdown">
                                <div class="header">Past Contracts</div>
                                <!-- Contracts will be loaded here dynamically -->
                                <div class="footer">
                                    
                                </div>
                            </div>
                        </div>



                        <div class="chat-icon">
                            <img src="/../assets/img/chat.png" alt="">
                            <div class="dropdown_chat" id="chatDropdown">
                                <div class="header">Messages</div>
                                <div class="message-list">
                                    <div class="message_">
                                        <img src="https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp"
                                            alt="User 1">
                                        <div class="content_msg">
                                            <div class="name">John Doe</div>
                                            <div class="snippet">Hey! How are you?</div>
                                        </div>
                                    </div>
                                    <div class="message_">
                                        <img src="https://images.unsplash.com/photo-1619895862022-09114b41f16f?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8d29tZW4lMjBwcm9maWxlJTIwcGljdHVyZXxlbnwwfHwwfHx8MA%3D%3D"
                                            alt="User 2">
                                        <div class="content_msg">
                                            <div class="name">Jane Smith</div>
                                            <div class="snippet">Are we still meeting tomorrow?</div>
                                        </div>
                                    </div>
                                    <div class="message_">
                                        <img src="https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/50dab922-5d48-4c6b-8725-7fd0755d9334/3a3f2d35-8167-4708-9ef0-bdaa980989f9.png"
                                            alt="User 3">
                                        <div class="content_msg">
                                            <div class="name">Mark Wilson</div>
                                            <div class="snippet">Can you send me the file?</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <a href="#">See all messages</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown">
                        <i class="fa-solid fa-cog" id="settings-icon"></i>
                        <div class="dropdown-content">
                            <a href="account_settings.html">Account Settings</a>
                            <a href="privacy.html">Privacy</a>
                            <a href="help.html">Help</a>

                        </div>
                    </div>
                </li>
                <li><a href="/logout" class="nav-item" id="logout">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <script src="/components/nav_home/nav_home.js"></script>
</body>

</html>