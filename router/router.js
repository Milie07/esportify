import Route from "./route.js";
import { allRoutes, websiteName } from "./allRoutes.js";

// Je crée une route pour la page 404 (page introuvable)
const route404 = new Route("404", "Page introuvable", "./pages/404.html");

// Fonction pour récupérer la route correspondant à l'URL donnée
const getRouteByUrl = (url) => {
    let currentRoute = null;
  // Parcours de toutes les routes pour trouver la bonne correspondance
    allRoutes.forEach((element) => {
    if (element.url == url) {
        currentRoute = element;
    }
    });
  // Si aucune correspondance n'est trouvée, je retourne la route 404
    if (currentRoute != null) {
    return currentRoute;
    } else {
    return route404;
    }
};

// Fonction pour charger le contenu de la page 
const LoadContentPage = async () => {
    const path = window.location.pathname;
  // Je récupère l'URL actuelle
    const actualRoute = getRouteByUrl(path);
  // Je récupère le contenu HTML de la route
    const html = await fetch(actualRoute.pathHtml).then((data) => data.text());

  // j'ajoute le contenu HTML 
    document.getElementById("main-page").innerHTML = html;

  // j'ajoute du contenu JavaScript
    if (actualRoute.pathJS != "") {
    // Je crée une balise script
        let scriptTag = document.createElement("script");
        scriptTag.setAttribute("type", "text/javascript");
        scriptTag.setAttribute("src", actualRoute.pathJS);

    // j'ajoute la balise script au corps du document
        document.querySelector("body").appendChild(scriptTag);
    }
    // je change le titre de la page
        document.title = actualRoute.title + " - " + websiteName;
};

// Fonction pour gérer les événements de routage (clic sur les liens)
const routeEvent = (event) => {
    event = event || window.event;
    event.preventDefault();
  // La mise à jour de l'URL dans l'historique du navigateur
    window.history.pushState({}, "", event.target.href);
  // Chargement du contenu de la nouvelle page
    LoadContentPage();
};

// Gestion de l'événement de retour en arrière dans l'historique du navigateur
window.onpopstate = LoadContentPage;

// Assignation de la fonction routeEvent à la propriété route de la fenêtre
window.route = routeEvent;

// Chargement du contenu de la page au chargement initial
LoadContentPage();

