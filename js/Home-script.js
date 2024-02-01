// Wait for the DOM to be fully loaded before executing the script
document.addEventListener('DOMContentLoaded', event => {
    // Set the current year in the footer
    document.getElementById('currentYear').textContent = new Date().getFullYear()

    // Initialize carousel images
    const carouselImages = [
        { src: '../img/player1.png', alt: 'Player 1' },
        { src: '../img/player2.png', alt: 'Player 2' },
        { src: '../img/player3.png', alt: 'Player 3' },
        { src: '../img/player4.png', alt: 'Player 4' },
        { src: '../img/player5.png', alt: 'Player 5' },
        { src: '../img/player6.png', alt: 'Player 6' }
    ]

    const imageCarousel = document.getElementById('imageCarousel')

    // Add images to the carousel
    carouselImages.forEach(image => {
        const imgElement = document.createElement('img')
        imgElement.src = image.src
        imgElement.alt = image.alt
        imgElement.classList.add('carousel-image')
        imageCarousel.appendChild(imgElement)
    })

    // Define changeImage function
    let currentImageIndex = 0
    let images = document.querySelectorAll('.carousel-image')

    function changeImage(direction) {
        images[currentImageIndex].style.display = 'none'
        currentImageIndex += direction

        if (currentImageIndex >= images.length) {
            currentImageIndex = 0
        } else if (currentImageIndex < 0) {
            currentImageIndex = images.length - 1
        }

        images[currentImageIndex].style.display = 'block'
    }

    // Initial call to changeImage with a direction of 1 to display the first image
    changeImage(1)

    // Set up an interval to automatically change the image every 3 seconds (3000 milliseconds)
    setInterval(() => {
        changeImage(1)
    }, 3000) // Change image every 3000 milliseconds (3 seconds)
})

// Output a message to the console
console.log('HOME')
