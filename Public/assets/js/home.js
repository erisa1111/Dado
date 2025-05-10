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


document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            toggleLike(postId);
        });
    });

    // Comment submission functionality
    document.querySelectorAll('[id^="comment-"]').forEach(input => {
        // Handle Enter key press
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const postId = this.getAttribute('data-post-id');
                const commentText = this.value;
                if (commentText.trim()) {
                    addComment(postId, commentText);
                    this.value = ''; // Clear input after submission
                }
            }
        });
        
        // Handle button click
        const postId = input.getAttribute('data-post-id');
        const submitButton = document.querySelector(`button[data-post-id="${postId}"]`);
        submitButton.addEventListener('click', function() {
            const commentText = input.value;
            if (commentText.trim()) {
                addComment(postId, commentText);
                input.value = ''; // Clear input after submission
            }
        });
    });

    // Comment button click to focus input
    document.querySelectorAll('.comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            document.getElementById(`comment-${postId}`).focus();
        });
    });
});

async function toggleLike(postId) {
    try {
        const response = await fetch('like_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: postId })
        });

        // First check if response is OK
        if (!response.ok) {
            const errorData = await response.text();
            console.error('Server responded with:', errorData);
            throw new Error(`Server error: ${response.status}`);
        }

        // Then try to parse as JSON
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Action failed');
        }

        // Update UI
        const postElement = document.getElementById(`post-${postId}`);
        if (postElement) {
            const likesCountElement = postElement.querySelector('.likes');
            if (likesCountElement) {
                likesCountElement.textContent = `${data.like_count} likes`;
            }
            
            const likeButton = postElement.querySelector('.like-btn i');
            if (likeButton) {
                likeButton.classList.toggle('fa-regular', !data.is_liked);
                likeButton.classList.toggle('fa-solid', data.is_liked);
                likeButton.style.color = data.is_liked ? 'red' : '';
            }
        }

    } catch (error) {
        console.error('Error in toggleLike:', error);
        // Show user-friendly error message
        alert('Failed to update like. Please try again.');
    }
}

async function addComment(postId, commentText) {
    try {
        const response = await fetch('comment_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                post_id: postId,
                comment: commentText 
            })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        
        if (data.success) {
            // Update UI as before
            const postElement = document.getElementById(`post-${postId}`);
            const commentsCountElement = postElement.querySelector('.comments');
            commentsCountElement.textContent = `${data.comment_count} comments`;
            
            const commentsList = document.getElementById(`comments-list-${postId}`);
            const newComment = document.createElement('div');
            newComment.className = 'comment';
            newComment.innerHTML = `
                <strong>${data.username}</strong>: ${commentText}
            `;
            commentsList.appendChild(newComment);
        }
    } catch (error) {
        console.error('Error:', error);
        // You might want to show a user-friendly error message here
    }
}