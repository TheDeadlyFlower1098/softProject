<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miracle Nursing Home</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
        font-family: 'Playfair Display', serif;
        background-color: rgb(182, 215, 168);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ------------------------------ */
    /* TOP NAVIGATION (TAN BAR)       */
    /* ------------------------------ */
    nav {
        width: 100%;
        background-color: #f1eebf;
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
        padding: 1rem 1.5rem; 
        width: 100%; 
        margin: 0; 
        box-sizing: border-box;
    }

    .logo {
        display: flex;
        align-items: center;
        color: #20548b;
        font-weight: bold;
        text-decoration: none;
        font-size: 3rem;
        white-space: nowrap;
    }

    .logo-img {
        width: 70px;
        height: 70px;
        margin: 0 6px;
        transition: transform 0.3s, filter 0.3s;
    }

    .logo-img:hover {
        transform: rotate(20deg);
        filter: drop-shadow(0 0 6px #ffd700);
    }

    /* NAV LINKS ROW */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 0.3rem;        /* smaller gap so more buttons fit */
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: nowrap;  /* keep all buttons on one line */
    }

    .nav-links li {
        flex: 0 0 auto;
    }

    .nav-links li a {
        padding: 0.5rem 1.1rem;
        background-color: #2b6cb0;
        color: #f1f2f4;
        font-weight: 600;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.95rem;
        white-space: nowrap;
        transition: background-color .2s, transform .2s;
    }

    .nav-links li a:hover {
        background-color: #6fa6e0;
        transform: translateY(-2px);
    }

    /* Make logout button match nav buttons */
    .nav-links form {
        margin: 0;
    }

    .nav-links form button {
        padding: 0.5rem 1.1rem;
        background-color: #2b6cb0;
        color: #f1f2f4;
        font-weight: 600;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        white-space: nowrap;
        transition: background-color .2s, transform .2s;
    }

    .nav-links form button:hover {
        background-color: #6fa6e0;
        transform: translateY(-2px);
    }

    /* Slightly compress things on narrower screens before wrapping */
    @media (max-width: 1200px) {
        .nav-container {
            padding: 0.8rem 1.5rem;
        }
        .logo {
            font-size: 2.4rem;
        }
        .nav-links {
            gap: 0.4rem;
        }
        .nav-links li a,
        .nav-links form button {
            padding: 0.4rem 0.9rem;
            font-size: 0.85rem;
        }
    }

        /* ------------------------------ */
        /* MAIN CONTENT                   */
        /* ------------------------------ */
        main {
            margin-top: 110px;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 2.4rem;
            color: #2b6cb0;
        }

        /* ------------------------------ */
        /* IMAGE GALLERY                  */
        /* ------------------------------ */
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
            max-width: 1000px;
            margin: 40px auto;
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
            }
        }

        @media (max-width: 480px) {
            .image-gallery {
                grid-template-columns: 1fr;
            }
        }

        /* ------------------------------ */
        /* JOIN US BUTTON                 */
        /* ------------------------------ */
        .join-us-btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            background-color: #2b6cb0;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1rem;
            margin-top: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background-color 0.3s, transform 0.2s;
        }

        .join-us-btn:hover {
            background-color: #1e4e8c;
            transform: translateY(-3px);
        }

        /* ------------------------------ */
        /* ABOUT + CONTACT SECTIONS       */
        /* ------------------------------ */
        .about-us, .contact {
            max-width: 900px;
            margin: 60px auto;
            padding: 20px;
            background-color: rgba(146, 171, 135, 1);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            text-align: center;
        }

        .about-us h2, .contact h2 {
            font-size: 2rem;
            color: #2b6cb0;
        }

        .about-us p, .contact p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
        }

        .social-media {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-media a {
            text-decoration: none;
            background-color: #2b6cb0;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .social-media a:hover {
            background-color: #6284ad;
        }

        footer {
            text-align: center;
            padding: 16px;
            background-color: #f1eebf;
            color: #20548b;
            margin-top: 40px;
        }


    </style>
</head>

<body>

    {{-- ---------------------------- --}}
    {{-- TOP NAVIGATION BAR           --}}
    {{-- ---------------------------- --}}
    <nav>
        <div class="nav-container">

            <a href="{{ route('home') }}" class="logo">
                <span>Mir</span>
                <img src="{{ asset('images/sun.png') }}" alt="Logo" class="logo-img">
                <span>cle</span>
            </a>

            
            <ul class="nav-links">
                @auth
                    @include('partials.nav-links', ['layout' => 'top'])
                @else
                    <li><a href="{{ route('login') }}">LOG IN</a></li>
                    <li><a href="{{ route('signup') }}">SIGN UP</a></li>
                @endauth
            </ul>

        </div>
    </nav>


    {{-- ---------------------------- --}}
    {{-- MAIN HOME CONTENT            --}}
    {{-- ---------------------------- --}}
    <main>
        <h1>Welcome to Miracle Nursing Home</h1>

        {{-- Image Gallery --}}
        <div class="image-gallery">
            <img src="https://th.bing.com/th/id/OIP.T5AxJlOW3o5dZtHWpqRPmwHaE8?w=231&h=180&c=7&r=0&o=7&pid=1.7" alt="Image">
            <img src="https://th.bing.com/th/id/OIP.BoKJCLaEcojdyLQRgg0kjgHaE8?w=274&h=183&c=7&r=0&o=7&pid=1.7" alt="Image">
            <img src="https://th.bing.com/th/id/OIP.eY6t-j8czcUlbxRcFmLTLgHaFY?w=275&h=200&c=7&r=0&o=7&pid=1.7" alt="Image">
            <img src="https://th.bing.com/th/id/OIP.VTpF9Xk9haq2N1q4u6YdRQHaE7?w=232&h=180&c=7&r=0&o=7&pid=1.7" alt="Image">
            <img src="https://th.bing.com/th/id/OIP.ImbHTMOzgUN7a90ir3_qOgHaDw?w=330&h=177&c=7&r=0&o=7&pid=1.7" alt="Image">
            <img src="https://th.bing.com/th/id/OIP.71Q3gLGPtjGoxnlINzIrpgHaFj?w=244&h=183&c=7&r=0&o=7&pid=1.7" alt="Image">
        </div>

        <a href="{{ route('signup') }}" class="join-us-btn">Join Us</a>

        {{-- About Section --}}
        <section class="about-us">
            <h2>About Us</h2>
            <p>
                At Miracle Nursing Home, we are dedicated to providing compassionate care,
                comfort, and a safe environment for our residents. Our mission is to help
                people live their lives with dignity, respect, and joy.
            </p>
        </section>

        {{-- Contact Section --}}
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
