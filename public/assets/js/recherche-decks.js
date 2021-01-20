// Ces fonctions sont appelée via l'attribut onClick des fichers twig.

// nous lançons une recherche avec ce que l'user a tapé dans l'input. Se reporter au controller DecksController pour plus d'information.

function lancerRecherche()
{
    var monTermeARechercher = document.getElementsByClassName("uneRecherche")[0].value;
    
    window.location = "rechercher/" + monTermeARechercher;
}

// Cette fonction est appelée si nous sommes déjà dans une recherche.

function relancerRecherche()
{
    var monTermeARechercher = document.getElementsByClassName("uneRecherche")[0].value;
    
    window.location = monTermeARechercher;
}
