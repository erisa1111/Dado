<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/components/nav_home/nav_home.css">
</head>
<body>
    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br><br><br><br><br><br>
    <div class="content">
        <div class="left">
            <div class="profile">
           
                <div class="photo">
                   
                    <img 
                        class="profile-image" 
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYXz402I39yGoxw90IrFr9w0vuQnuVSkgPCg&s" 
                        alt="Profile Image"
                    >
                    <div class="info">
                        <h3 class="name">Filan Fisteku</h3>
                        <p class="status">Status</p>
                    </div>
                </div>
        
                <div class="bio">
                    
                    <div class="bio-box">
                        <p>Hello im a nanny and i have specialized in childcare</p>
                    </div>
                </div>
               
            </div>
          
            <div class="recent">
                <div class="image-container">
                  <img src="/assets/img/event_dado.webp" alt="Event for Parents and Nannies">
                  <div class="overlay-text">
                    <h2>Join Our Parent & Nanny Event</h2>
                    <button class="register-btn">Register</button>
                  </div>
                </div>
              </div>
              
        </div>
        <div id="center">
       
        </div>


        <div class="right">
            <div class="recommend">
              <h2>Add to your feed</h2>
              
              <div class="recommendation">
                <div class="logo">
                  <img src="https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=" alt="Nanny 1" />
                </div>
                <div class="rec">
                  <div class="info">
                    <h3>Nanny 1</h3>
                    <p>Experienced caregiver</p>
                  </div>
                  <button class="follow-btn">+ Follow</button>
                </div>
              </div>
              
              <div class="recommendation">
                <div class="logo">
                  <img src="https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=" alt="Parent 1" />
                </div>
                <div class="rec">
                  <div class="info">
                    <h3>Parent 1</h3>
                    <p>Looking for a caring nanny</p>
                  </div>
                  <button class="follow-btn">+ Follow</button>
                </div>
              </div>
              
              <div class="recommendation">
                <div class="logo">
                  <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s" alt="Nanny 2" />
                </div>
                <div class="rec">
                  <div class="info">
                    <h3>Nanny 2</h3>
                    <p>Passionate about child development activities.</p>
                  </div>
                  <button class="follow-btn">+ Follow</button>
                </div>
              </div>
          
              <a href="#" class="view-all">View all recommendations →</a>
            </div>
            
            <div class="about">
              <img src="/assets/img/find_dado.webp" alt="">
            </div>
            
            <div class="app">
              <h6>Try Dado on your Mobile →</h6>
              <a href="#">Dado</a>
            </div>
          </div>
          
          <script src="/components/nav_home/nav_home.js"></script>
         <script src="/components/postcard/postcard.js"></script>

         <script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/path-to-PostsController/getPosts')
        .then(response => response.json())
        .then(posts => {
            let centerDiv = document.getElementById('center');
            posts.forEach(post => {
                let postCard = document.createElement('div');
                postCard.classList.add('post-card');
                postCard.innerHTML = `
                    <div class="post-header">
                        <h3>${post.title}</h3>
                        <p>By ${post.username}</p>
                    </div>
                    <div class="post-body">
                        <p>${post.body}</p>
                        ${post.image_url ? `<img src="${post.image_url}" alt="Post Image" />` : ''}
                    </div>
                    <div class="post-actions">
                        <button class="like-btn" data-post-id="${post.id}">
                            <i class="fas fa-thumbs-up"></i> Like <span class="like-count">${post.like_count}</span>
                        </button>
                        <button class="comment-btn" data-post-id="${post.id}">
                            <i class="fas fa-comment"></i> Comment
                        </button>
                    </div>
                `;
                centerDiv.appendChild(postCard);
            });
        })
        .catch(error => console.error('Error fetching posts:', error));
});

document.addEventListener('click', function (event) {
    if (event.target.matches('.like-btn')) {
        const postId = event.target.getAttribute('data-post-id');
        
        fetch('/path-to-PostsController/toggleLike', {
            method: 'POST',
            body: JSON.stringify({ post_id: postId }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const likeCountElement = event.target.querySelector('.like-count');
                likeCountElement.textContent = data.like_count;
            }
        })
        .catch(error => console.error('Error toggling like:', error));
    }
});

document.addEventListener('submit', function (event) {
    if (event.target.matches('.comment-form')) {
        event.preventDefault();
        
        const postId = event.target.getAttribute('data-post-id');
        const commentText = event.target.querySelector('.comment-text').value;
        
        fetch('/path-to-PostsController/addComment', {
            method: 'POST',
            body: JSON.stringify({ post_id: postId, comment: commentText }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display the new comment dynamically
                const newComment = data.comment;
                const commentSection = document.querySelector(`#post-${postId} .comments`);
                commentSection.innerHTML += `<div class="comment">${newComment.comment}</div>`;
            }
        })
        .catch(error => console.error('Error adding comment:', error));
    }
});


</script>

   
</body>



</html>

