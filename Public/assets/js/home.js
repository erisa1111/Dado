document.addEventListener('DOMContentLoaded', function () {
    fetch('/App/Controllers/PostsController/getPosts')
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
function initializeModal() {
    const addButton = document.getElementById("add");
    const closeButton = document.getElementById("close-modal");

    // Show the modal when the Add button is clicked
    addButton.addEventListener("click", () => {
        toggleModalVisibility(true);
    });

    // Hide the modal when the Close button is clicked
    closeButton.addEventListener("click", () => {
        toggleModalVisibility(false);
    });

    // Optional: Close the modal if the user clicks outside the modal content
    const modal = document.getElementById("post-modal");
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            toggleModalVisibility(false);
        }
    });
}