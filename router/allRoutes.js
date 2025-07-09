import Route from "./route.js";

//Définir ici vos routes
export const allRoutes = [
    new Route("/", "Accueil", "./pages/home.html"),
    new Route("/events", "Events", "./pages/events.html"),
    new Route("/contact", "Contact", "./pages/contact.html"),
    new Route("/signup", "Inscription", "./pages/signup.html"),
    new Route("/signin", "Connexion", "./pages/signin.html"),
];
//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "Esportify";