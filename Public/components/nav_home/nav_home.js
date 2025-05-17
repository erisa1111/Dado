async function loadNav() {
    try {
        const navPlaceholder = document.getElementById("nav-placeholder");
        const response = await fetch("/components/nav_home/nav_home.php");
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const navHtml = await response.text();
        navPlaceholder.innerHTML = navHtml;
        setActiveLink();
        initializeSearchIcon();
        initializeSuggestionList();
        initializeChatDropdown();
        initializeRatingDropdown(); // Call the suggestion list function here
    } catch (error) {
        console.error("Error loading navbar:", error);
    }
}

function setActiveLink() {
    const navItems = document.querySelectorAll(".nav-item");
    const currentPath = window.location.pathname.split("/").pop();
    navItems.forEach(link => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
}

function initializeSearchIcon() {
    const search = document.getElementById("search_icon");
    const input = document.getElementById("search-bar");

    if (search && input) {
        search.addEventListener("click", function () {
            if (input.style.width === "0px" || input.style.width === "") {
                input.style.width = "200px";
            } else {
                input.style.width = "0px";
            }
        });
    } else {
        console.error("Required elements are missing.");
    }
}

const suggestions = [
    {
        name: 'Nanny Anna',
        profilePic: 'https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=',
        description: 'Experienced caregiver with over 5 years of experience.'
    },
    {
        name: 'Nanny Maria',
        profilePic: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s',
        description: 'Specialized in early childhood education.'
    },
    {
        name: 'Parent John',
        profilePic: 'https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp',
        description: 'Looking for a nurturing nanny for my 3 kids.'
    },
    {
        name: 'Parent Emily',
        profilePic: 'https://images.unsplash.com/photo-1619895862022-09114b41f16f?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8d29tZW4lMjBwcm9maWxlJTIwcGljdHVyZXxlbnwwfHwwfHx8MA%3D%3D',
        description: 'Seeking an experienced nanny for part-time care.'
    },
    {
        name: 'Nanny Lia',
        profilePic: 'https://imgcdn.stablediffusionweb.com/2024/6/12/4d688bcf-f53b-42b6-a98d-3254619f3b58.jpg',
        description: 'Passionate about child development activities.'
    },
    {
        name: 'Parent Liam',
        profilePic: 'https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/50dab922-5d48-4c6b-8725-7fd0755d9334/3a3f2d35-8167-4708-9ef0-bdaa980989f9.png',
        description: 'Looking for a full-time nanny to help with the newborn.'
    },
];


// This function will initialize the search suggestion list
function initializeSuggestionList() {
    const searchInput = document.getElementById('search-bar');
    const suggestionList = document.getElementById('suggestion-list');

    // Handle search input and filter suggestions
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        suggestionList.innerHTML = ''; // Clear previous suggestions

        if (query) {
            // Filter suggestions based on input
            const filteredSuggestions = suggestions.filter((item) =>
                item.name.toLowerCase().includes(query)
            );

            // Add filtered suggestions to the list
            filteredSuggestions.forEach((item) => {
                const li = document.createElement('li');
                li.classList.add('suggestion-item');

                const img = document.createElement('img');
                img.src = item.profilePic;
                img.alt = item.name;

                const infoDiv = document.createElement('div');
                infoDiv.classList.add('info');
                const name = document.createElement('h4');
                name.textContent = item.name;
                const description = document.createElement('p');
                description.textContent = item.description;

                infoDiv.appendChild(name);
                infoDiv.appendChild(description);

                li.appendChild(img);
                li.appendChild(infoDiv);

                li.addEventListener('click', () => {
                    searchInput.value = item.name;
                    suggestionList.style.display = 'none'; // Hide suggestions on selection
                });

                suggestionList.appendChild(li);
            });

            suggestionList.style.display = 'block'; // Show suggestions
        } else {
            suggestionList.style.display = 'none'; // Hide suggestions if input is empty
        }
    });

    // Close the suggestion list if clicking outside of the search input
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-container')) {
            suggestionList.style.display = 'none';
        }
    });
}
function initializeChatDropdown() {
    const chatIcon = document.querySelector('.chat-icon');
    const dropdown = document.getElementById('chatDropdown');
    const messageItems = document.querySelectorAll('.message_');

    if (chatIcon && dropdown) {
        chatIcon.addEventListener('click', () => {
            dropdown.classList.toggle('open');
        });
    } else {
        console.error("Chat icon or dropdown not found.");
    }

    // Add click event to each message to open chat window
    if (messageItems) {
        messageItems.forEach((message) => {
            message.addEventListener('click', () => {
                const userName = message.querySelector('.name').textContent;
                const userImg = message.querySelector('img').src;
                openChatWindow(userName, userImg);
            });
        });
    }
}

function openChatWindow(userName, userImg) {
    // Check if a chat window for this user already exists
    if (document.querySelector(`.chat-window[data-user="${userName}"]`)) {
        return; // Avoid opening duplicate chat windows
    }

    // Create a new chat window
    const chatContainer = document.createElement('div');
    chatContainer.classList.add('chat-window');
    chatContainer.setAttribute('data-user', userName);

    chatContainer.innerHTML = `
        <div class="chat-header">
            <img src="${userImg}" alt="${userName}" class="chat-user-img">
            <span>${userName}</span>
            <button class="close-chat">&times;</button>
        </div>
        <div class="chat-body">
            <div class="messages">
                <!-- Messages will appear here -->
                <div class="message received">Hi ${userName}, how can I help you?</div>
            </div>
        </div>
        <div class="chat-footer">
            <input type="text" placeholder="Type a message...">
            <button class="send-btn">Send</button>
        </div>
    `;

    // Append the chat window to the body
    document.body.appendChild(chatContainer);

    // Close chat window functionality
    chatContainer.querySelector('.close-chat').addEventListener('click', () => {
        chatContainer.remove();
    });

    // Handle message sending
    const sendBtn = chatContainer.querySelector('.send-btn');
    const messageInput = chatContainer.querySelector('input');
    const messagesDiv = chatContainer.querySelector('.messages');

    sendBtn.addEventListener('click', () => {
        const message = messageInput.value.trim();
        if (message) {
            const sentMessage = document.createElement('div');
            sentMessage.classList.add('message', 'sent');
            sentMessage.textContent = message;

            messagesDiv.appendChild(sentMessage);
            messageInput.value = ''; // Clear input
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // Auto-scroll to the bottom
        }
    });
}

async function initializeRatingDropdown() {
    const ratingIcon = document.querySelector('.rating-icon');
    const ratingDropdown = document.getElementById('ratingDropdown');

    if (ratingIcon && ratingDropdown) {
        ratingIcon.addEventListener('click', async () => {
            // Only fetch contracts if dropdown is being opened and hasn't been loaded yet
            if (!ratingDropdown.classList.contains('open') && !ratingDropdown.dataset.loaded) {
                try {
                    // Get the logged-in user ID (you'll need to make this available)
                    const userId = getUserId(); // You'll need to implement this function
                    
                    // Fetch contracts from server
                    const response = await fetch('http://localhost:4000/views/get_jobs.php');
                    if (!response.ok) throw new Error('Failed to fetch contracts');
                    
                    const data = await response.json();
                    
                    if (data.success && data.jobs.length > 0) {
                        // Clear existing hardcoded contracts (keep header and footer)
                        const contractsContainer = ratingDropdown;
                        const header = contractsContainer.querySelector('.header');
                        const footer = contractsContainer.querySelector('.footer');
                        
                        contractsContainer.innerHTML = '';
                        contractsContainer.appendChild(header);
                        
                        // Add each contract from the database
                       data.jobs.forEach(job => {
    // Extract only the date part, ignore the time
    const startDate = job.start_date.split(' ')[0];
    const endDate = job.end_date ? job.end_date.split(' ')[0] : '';  // in case end_date is missing

    const contractDiv = document.createElement('div');
    contractDiv.className = 'contract';
    contractDiv.dataset.id = job.id;

    contractDiv.innerHTML = `
        <div class="name">${job.other_person_name || 'Unknown'}</div>
        <div class="details">${startDate}${endDate ? ' - ' + endDate : ''} | ${job.job_type}</div>
    `;

    contractsContainer.appendChild(contractDiv);
});

                        
                        contractsContainer.appendChild(footer);
                        
                        // Mark as loaded to prevent refetching
                        ratingDropdown.dataset.loaded = 'true';
                        
                        // Reattach click handlers to the new contracts
                        attachContractClickHandlers();
                    }
                } catch (error) {
                    console.error('Error loading contracts:', error);
                    // Optionally show an error message in the dropdown
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error';
                    errorDiv.textContent = 'Failed to load contracts';
                    ratingDropdown.appendChild(errorDiv);
                }
            }
            
            ratingDropdown.classList.toggle('open');
        });
    } else {
        console.error("Rating icon or dropdown not found.");
    }

    // Initial attachment of click handlers
    attachContractClickHandlers();
}

function attachContractClickHandlers() {
    // Handle click on a contract to open the review section
    const contracts = document.querySelectorAll('.contract');
    contracts.forEach(contract => {
        contract.addEventListener('click', () => {
            const nannyName = contract.querySelector('.name').textContent;
            const details = contract.querySelector('.details').textContent.split('|')[0].trim();
            const job_type = contract.querySelector('.details').textContent.split('|')[1].trim();
            
            openRatingModal(nannyName, details, job_type);
        });
    });
}

// You'll need to implement this function to get the current user's ID
function getUserId() {
    // This depends on how you store user info in the frontend
    // Could be from a global variable, localStorage, or a meta tag
    return localStorage.getItem('userId') || document.querySelector('meta[name="user-id"]')?.content;
}

function openRatingModal(nannyName, details,job_type) {
    const modal = document.createElement('div');
    modal.classList.add('rating-modal');
    modal.innerHTML = `
    <span class="close-modal">&times;</span>
        <div class="modal-content">
            
            <h3>Rate & Review</h3>
            <div class="contract-details">
           
            
                <strong>Person:</strong> ${nannyName} <br>
                <strong>Date:</strong> ${details} <br>
                <strong>Job Type:</strong> ${job_type} <br>
            </div>
            <div class="star-rating">
                    <input type="number" step="0.1" min="1" max="5" placeholder="Rate (e.g. 4.5)" class="rating-number" />

            </div>
            <textarea placeholder="Write your review here..." class="review-text"></textarea>
            <button class="submit-review">Submit</button>
        </div>
    `;

    // Append modal to the body
    document.body.appendChild(modal);

    // Handle star rating
    const stars = modal.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('click', (e) => {
            const rating = e.target.getAttribute('data-value');
            stars.forEach(s => s.classList.remove('selected'));
            for (let i = 0; i < rating; i++) {
                stars[i].classList.add('selected');
            }
        });
    });

    // Handle review submission
    modal.querySelector('.submit-review').addEventListener('click', () => {
        const selectedStars = modal.querySelectorAll('.star.selected').length;
        const reviewText = modal.querySelector('.review-text').value.trim();

        if (selectedStars > 0 || reviewText) {
            console.log(`Nanny: ${nannyName}`);
            console.log(`Details: ${details}`);
            console.log(`Submitted Rating: ${selectedStars}`);
            console.log(`Submitted Review: ${reviewText}`);
            alert("Thank you for your feedback!");
            modal.remove(); // Close modal
        } else {
            alert("Please provide a rating or review before submitting.");
        }
    });

    // Close modal functionality
    modal.querySelector('.close-modal').addEventListener('click', () => {
        modal.remove();
    });
}

// Load navigation on page load
document.addEventListener("DOMContentLoaded", loadNav);
