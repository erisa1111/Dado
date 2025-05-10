// const connections = [
//     {
//         profileImg: "https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=",
//         username: "NannyA",
//         action: "sent you a connect",
//         unread: true,
//     },
//     {
//         profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s",
//         username: "NannyB",
//         action: "sent you a connect",
//         unread: false,
//     },
//     {
//         profileImg: "https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=",
//         username: "Parent 1",
//         action: "sent you a connect",
//         unread: true,
//     },
//     {
//         profileImg: "https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp",
//         username: "NannyC",
//         action: "sent you a connect",
//         unread: false,
//     },
//     {
//         profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s",
//         username: "Parent 2",
//         action: "sent you a connect",
//         unread: true,
//     },
//     {
//         profileImg: "https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/50dab922-5d48-4c6b-8725-7fd0755d9334/3a3f2d35-8167-4708-9ef0-bdaa980989f9.png",
//         username: "NannyD",
//         action: "sent you a connect",
//         unread: false,
//     },
//     {
//         profileImg: "https://marketplace.canva.com/EAFqNrAJpQs/1/0/1600w/canva-neutral-pink-modern-circle-shape-linkedin-profile-picture-WAhofEY5L1U.jpg",
//         username: "Parent 3",
//         action: "sent you a connect",
//         unread: true,
//     },
//     {
//         profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYXz402I39yGoxw90IrFr9w0vuQnuVSkgPCg&s",
//         username: "Parent 4",
//         action: "sent you a connect",
//         unread: true,
//     },
//     {
//         profileImg: "https://shotkit.com/wp-content/uploads/2021/06/cool-profile-pic-matheus-ferrero.jpeg",
//         username: "Parent 5",
//         action: "sent you a connect",
//         unread: true,
//     },
//     {
//         profileImg: "https://www.perfocal.com/blog/content/images/2021/01/Perfocal_17-11-2019_TYWFAQ_100_standard-3.jpg",
//         username: "Parent 6",
//         action: "sent you a connect",
//         unread: true,
//     }
// ];

// Base URL for connection actions
const CONNECTIONS_AJAX_URL = '/connections-action.php';
//const CONNECTIONS_AJAX_URL = '/App/Controllers/ConnectionsController/handleConnectionAction';


const CONNECTIONS_API_URL = '/App/Controllers/ConnectionsController/getConnectionsApi';

// Main initialization
const initializeConnections = () => {
    setupEventListeners();
};

// Load connections from server
const loadConnections = async () => {
    try {
        const response = await fetch(CONNECTIONS_API_URL);
        const data = await response.json();
        
        if (data.success) {
            if (data.pending && data.pending.length > 0) {
                updateConnectionsUI(data.pending);
            } else {
                showEmptyState();
            }
        }
    } catch (error) {
        console.error('Error loading connections:', error);
    }
};

// Update UI with new connections
const updateConnectionsUI = (connections) => {
    const container = document.getElementById('center');
    
    // Clear existing connections (except the hidden template)
    document.querySelectorAll('#center .connection-card:not([style*="display: none"])').forEach(el => el.remove());
    
    if (connections.length === 0) {
        showEmptyState();
        return;
    }

    const template = document.querySelector('.connection-card[style*="display: none"]');
    
    connections.forEach(connection => {
        const clone = template.cloneNode(true);
        clone.style.display = 'flex';
        clone.dataset.userId = connection.user_one_id;
        
        // Update with actual data
        const img = clone.querySelector('.connection-image');
        img.src = getUserImage(connection.user_one_id);
        img.alt = getUserName(connection.user_one_id) + ' profile';
        
        clone.querySelector('.connection-username').textContent = getUserName(connection.user_one_id);
        
        container.appendChild(clone);
    });
};

// Rest of your existing JavaScript (handleConnectionAction, setupEventListeners, etc.)
// Render connections in the DOM
const renderConnections = (connections) => {
    const connectionContainer = document.getElementById('center'); // Or your container element
    const template = document.querySelector('.connection-card');
    
    if (!template || !connectionContainer) return;
    
    // Clear existing connections
    connectionContainer.innerHTML = '';
    
    connections.forEach(connection => {
        const clone = template.cloneNode(true);
        clone.style.display = 'flex'; // Make visible
        
        // Populate with actual data
        clone.querySelector('.connection-image').src = getUserImage(connection.user_one_id);
        clone.querySelector('.connection-username').textContent = connection.username;
        
        // Set data attributes for actions
        clone.dataset.userId = connection.user_one_id;
        
        // Add to container
        connectionContainer.appendChild(clone);
    });
};

// Show empty state when no connections
const showEmptyState = () => {
    const container = document.getElementById('center');
    container.innerHTML = `
        <div class="empty-connections">
            <i class="fas fa-user-friends"></i>
            <p>No pending connection requests</p>
        </div>
    `;
};

// Handle connection actions (accept/decline)
const handleConnectionAction = async (action, userId, card) => {
    try {
        console.log('Sending request...');
        const response = await fetch(CONNECTIONS_AJAX_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: action,
                user_one_id: userId
            })
        });
    
        console.log('Waiting for response...');
    
        // Read the raw response first
        const responseText = await response.text();
        console.log('Raw response:', responseText);
    
        // Try to parse as JSON
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('Parsed JSON:', data);
        } catch (e) {
            console.error('Response is not valid JSON');
            return;
        }
    
        console.log('Response data:', data);
        if (data.success) {
            console.log('Action successful:', data);
      
          

            if (action === 'accept') {
                // Update status in card to "Accepted"
                const statusElement = card.querySelector('.connection-status');
                if (statusElement) {
                    statusElement.textContent = 'Accepted';
                    statusElement.style.color = 'green'; 
                }
                const buttonsContainer = card.querySelector('.connection-buttons');
               if (buttonsContainer) {
               buttonsContainer.remove(); // Remove accept/decline buttons
      }
            } else if (action === 'decline') {
                // Remove the card
                card?.remove();

                if (document.querySelectorAll('.connection-card').length === 0) {
                    showEmptyState();
                }
            }

            
          //      confirm(`${action === 'accept' ? 'Accepted' : 'Declined'} connection successfully`);
           
        } else {
            throw new Error(data.message || 'Action failed');
        }
    } catch (error) {
        console.error('Connection action error:', error);
        showToast('Failed to process request', 'error');
    }
};

// Show toast notification
const showToast = (message, type = 'success') => {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
};

// Set up event listeners
const setupEventListeners = () => {
    document.addEventListener('click', (event) => {
        const acceptBtn = event.target.closest('.accept-text');
        const declineBtn = event.target.closest('.decline-text');

        if (acceptBtn || declineBtn) {
            const connectionCard = event.target.closest('.connection-card');
            const userId = connectionCard?.dataset.userId;

            if (!userId || !connectionCard) return;

            if (acceptBtn) {
                console.log('Accepting connection for userId:', userId);
                handleConnectionAction('accept', userId, connectionCard);
            } else if (declineBtn) {
                console.log('Declining connection for userId:', userId);

                if (confirm('Are you sure you want to decline this connection?')) {
                    handleConnectionAction('decline', userId, connectionCard);
                }
            }
        }
    });
};


// Helper function to get user image (implement based on your app)
const getUserImage = (userId) => {
    // You'll need to implement this based on how you get user images
    return `/path/to/user/images/${userId}.jpg`;
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeConnections);

// Make functions available globally if needed
window.handleConnectionAction = handleConnectionAction;
