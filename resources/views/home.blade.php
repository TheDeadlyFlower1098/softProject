<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miracle Nursing Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>

body {
    font-family: Arial, sans-serif;
    background-color:  rgb(182, 215, 168);
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    overflow-x: hidden; 
    font-family: 'Playfair Display', serif;
}


nav {
    width: 100%;
    background-color: #f1eebfff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 10;
}
.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem; 
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
}

/* Logo */
.logo {
    display: flex;
    align-items: center;
    color: #20548bff;
    font-weight: bold;
    text-decoration: none;
    font-size: 3.5rem;
    font-family: 'Playfair Display', serif;
}

.logo-img {
    width: 70px;
    height: 70px;
    margin: 0 px;
    transition: transform 0.3s, filter 0.3s;
}

.logo-img:hover {
    transform: rotate(20deg);
    filter: drop-shadow(0 0 6px #ffd700);
}


.nav-links {
    display: flex;
    list-style: none;
    gap: 1rem; 
    margin: 0;
    padding: 0;
}

.nav-links a {
    display: flex;
    align-items: center;       
    justify-content: center;   
    padding: 0.5rem 1.2rem;    
    background-color: #2b6cb0; 
    color: #f1f2f4ff;          
    font-weight: 600;
    border-radius: 6px;      
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s;
}


.nav-links a:hover {
    background-color: #6fa6e0ff; 
    color: white;             
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        align-items: flex-start;
    }
    .nav-links {
        flex-direction: column;
        gap: 0.75rem;
        width: 100%;
    }
}


main {
    flex-grow: 1;
    margin-top: 80px; 
    padding: 20px;
    text-align: center;
}

main h1 {
    font-size: 2rem;
    color: #2b6cb0;
}

.image-gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 20px;                           
    justify-items: center;        
    align-items: center;
    margin: 40px auto;   
    max-width: 1000px;      
}


.image-gallery img {
    width: 100%;         
    height: 200px;     
    object-fit: cover;     
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.image-gallery img:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}


@media (max-width: 768px) {
    .image-gallery {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .image-gallery {
        grid-template-columns: 1fr; 
    }
}


footer {
    background-color: #f1eebfff;
    color: #20548bff;
    text-align: center;
    padding: 16px;
    position: relative;
    bottom: 0;
    width: 100%;
}

.join-us-btn {
    display: inline-block;           
    padding: 0.8rem 1.8rem;       
    background-color: #2b6cb0;    
    color: white;        
    font-weight: 600;
    font-family: 'Playfair Display', serif; 
    font-size: 1.1rem;
    text-align: center;             
    text-decoration: none;     
    border-radius: 8px;       
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: background-color 0.3s, transform 0.2s;
}


.join-us-btn:hover {
    background-color: #1e4e8c;   
    transform: translateY(-2px);   
}


.about-us, .contact {
    max-width: 900px;
    margin: 60px auto;
    padding: 20px;
    background-color:rgba(146, 171, 135, 1);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    text-align: center;
}

.about-us h2, .contact h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: #2b6cb0;
    margin-bottom: 20px;
}

.about-us p, .contact p {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #333;
}

.social-media {
    margin-top: 15px;
    display: flex;
    justify-content: center;
    gap: 20px;
    background-color: 
}

.social-media a {
    text-decoration: none;
    font-weight: 600;
    background-color: #2b6cb0;
    color: white;
    padding: 8px 14px;
    border: 1px solid #2b6cb0;
    border-radius: 6px;
    transition: background-color 0.3s, color 0.3s;
}

.social-media a:hover {
    background-color: #6284adff;
    color: white;
}


    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">
                <span>Mir</span>
                <img src="{{ asset('images/sun.png') }}" alt="Logo" class="logo-img">
                <span>cle</span>
            </a>
            <ul class="nav-links">
                <li><a href="#">LOG IN</a></li>
                <li><a href="#">SIGN UP</a></li>
            </ul>
        </div>
    </nav>

    <main>
    <h1>Welcome to Miracle Nursing Home</h1>

    <div class="image-gallery">
        <img src="https://th.bing.com/th/id/OIP.T5AxJlOW3o5dZtHWpqRPmwHaE8?w=231&h=180&c=7&r=0&o=7&cb=ucfimgc2&dpr=1.3&pid=1.7&rm=3 alt="Picture 1">
        <img src="https://th.bing.com/th/id/OIP.BoKJCLaEcojdyLQRgg0kjgHaE8?w=274&h=183&c=7&r=0&o=7&cb=ucfimgc2&dpr=1.3&pid=1.7&rm=3" alt="Picture 2">
        <img src="https://th.bing.com/th/id/OIP.eY6t-j8czcUlbxRcFmLTLgHaFY?w=275&h=200&c=7&r=0&o=7&cb=ucfimgc2&dpr=1.3&pid=1.7&rm=3" alt="Picture 3">
        <img src="https://th.bing.com/th/id/OIP.VTpF9Xk9haq2N1q4u6YdRQHaE7?w=232&h=180&c=7&r=0&o=7&cb=ucfimgc2&dpr=1.3&pid=1.7&rm=3" alt="Picture 4">
        <img src="https://th.bing.com/th/id/OIP.ImbHTMOzgUN7a90ir3_qOgHaDw?w=330&h=177&c=7&r=0&o=7&cb=ucfimgc2&dpr=1.3&pid=1.7&rm=3s" alt="Picture 5">
        <img src="https://th.bing.com/th/id/OIP.71Q3gLGPtjGoxnlINzIrpgHaFj?w=244&h=183&c=7&r=0&o=7&cb=ucfimgc2&dpr=1.3&pid=1.7&rm=3s" alt="Picture 6">
    </div>

        <a href="#" class="join-us-btn">Join Us</a>
 <section class="about-us">
        <h2>About Us</h2>
        <p>
            At Miracle Nursing Home, we are dedicated to providing compassionate care, comfort, 
            and a safe environment for our residents. Our mission is to help people live their 
            lives with dignity, respect, and joy.
        </p>
    </section>

    <section class="contact">
        <h2>Contact Us</h2>
        <p>Address: 123 Miracle Street, YourCity, USA</p>
        <p>Phone: (123) 456-7890</p>
        <div class="social-media">
            <a href="#" target="_blank">Facebook</a>
            <a href="#" target="_blank">Instagram</a>
            <a href="#" target="_blank">Twitter</a>
        </div>
    </section>
</main>


    <footer>
        © 2025 Miracle Nursing Home. All rights reserved.
    </footer>
</body>
</html>
