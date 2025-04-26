async function loadNav() {
    try {
        const navPlaceholder = document.getElementById("nav-placeholder");
        const response = await fetch("nav_home/nav_home.html");
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const navHtml = await response.text();
        navPlaceholder.innerHTML = navHtml;
        setActiveLink();
        initializeSearchIcon();
        initializeSuggestionList(); // Call the suggestion list function here
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

// Load navigation on page load
document.addEventListener("DOMContentLoaded", loadNav);
