initializeModal();
// Add this to your home.js
document.getElementById('post-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const content = document.getElementById('post-content').value;
    const images = document.getElementById('post-images').files;

    // Basic validation
    if (!content.trim() && images.length === 0) {
        alert('Please add content or an image');
        return;
    }

    formData.append('content', content);
    formData.append('title', 'New Post');

    // Append images
    for (let i = 0; i < images.length; i++) {
        formData.append('images[]', images[i]);
    }

    try {
        const response = await fetch('http://localhost:4000/views/create_post.php', {
            method: 'POST',
            body: formData,
        });
    
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }
    
        const data = await response.json();
    
        if (!data.success) {
            throw new Error(data.message || "Post creation failed.");
        }
    
        toggleModalVisibility(false);
        location.reload();
    
    } catch (error) {
        console.error("Post Error:", error);
        alert(`Error: ${error.message}`);
    }
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
// Add this function to your home.js
function toggleModalVisibility(show) {
    const modal = document.getElementById('post-modal');
    if (show) {
        modal.style.display = 'block';
    } else {
        modal.style.display = 'none';
    }
}

function initializeModal() {
    const addButton = document.getElementById('add');
    const closeButton = document.getElementById('close-modal');
    const modal = document.getElementById('post-modal');

    // Show modal when Add button is clicked
    addButton.addEventListener('click', () => {
        toggleModalVisibility(true);
    });

    // Hide modal when Close button is clicked
    closeButton.addEventListener('click', () => {
        toggleModalVisibility(false);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            toggleModalVisibility(false);
        }
    });
}
