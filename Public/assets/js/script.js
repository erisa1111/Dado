const testimonials = [
    {
        text: "I'm thrilled I found this nanny-finding website! It's user-friendly, safe, and offers numerous profiles with reviews. Now I can leave for work with peace of mind, knowing my child is in good hands. Thanks to the creators!",
        name: "Elisabeth",
        role: "Parent"
    },
    {
        text: "This website saved me so much time! I quickly found a trustworthy nanny that my children absolutely love. I highly recommend it to any parent.",
        name: "John",
        role: "Parent"
    },
    {
        text: "I struggled to find reliable child care until I came across this website. It has been a game changer for our family!",
        name: "Sarah",
        role: "Parent"
    }
];

let currentIndex = 0;

const opinionsElement = document.getElementById('opinions');
const authorNameElement = document.getElementById('author_name');

document.getElementById('btn_next').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % testimonials.length;
    updateTestimonial();
});

document.getElementById('btn_back').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
    updateTestimonial();
});

function updateTestimonial() {
    const testimonial = testimonials[currentIndex];
    opinionsElement.innerText = testimonial.text;
    authorNameElement.innerHTML = `<p>${testimonial.name}</p><p>${testimonial.role}</p>`;
}

const searchInput = document.getElementById('search_place_welcome');
const suggestionList = document.getElementById('suggestion_list');


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

searchInput.addEventListener('input', (e) => {
  const query = e.target.value.toLowerCase();
  suggestionList.innerHTML = '';

  if (query) {
    const filteredSuggestions = suggestions.filter((item) =>
      item.name.toLowerCase().includes(query)
    );

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
        suggestionList.style.display = 'none';
      });

      suggestionList.appendChild(li);
    });

    suggestionList.style.display = 'block';
  } else {
    suggestionList.style.display = 'none';
  }
});

document.addEventListener('click', (e) => {
  if (!e.target.closest('.search_welcome')) {
    suggestionList.style.display = 'none';
  }
});
