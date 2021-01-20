/**
 * Cette partie a été gérée et codée par Franck.
 * 
 * Nous faisons appel à ce ficher javascript lorsque nous sommes dans la section révision et dans la section quiz.
 * Dans les fichers twig, nous appelons ce fichier dans l'attribut onload de la balise body.
 * Le code permet entre autres de "randomiser" l'ordre d'affichage des cartes à réviser ou à apprendre.
 * 
 * Dans la section quiz, des fonctions permettant de vérifier la réponse de l'utilisateur sont également utilisées.
 */


 /**
  * Nous créons un carousel qui affichera un tunnel de quiz. Par la suite, nous affichons le premier élément
  * et nous camouflons les autres (s'il y a au moins deux cartes bien sûr) ainsi que la page des résultats.
  */
function initialiseQuiz()
{
  document.getElementsByClassName("bonnesReponses")[0].innerHTML = 0;

  document.getElementsByClassName("listeMauvaisesReponses")[0].innerHTML = ""; 
  document.getElementsByClassName("bonnesReponsesTexte")[0].innerHTML = "bonnes réponses"; 

  for(i=0 ; i< document.getElementsByClassName("uneReponseUser").length ; i++)
  {
    document.getElementsByClassName("uneReponseUser")[i].value = ""; 
    document.getElementsByClassName("alert-success")[i].style.display = "none";
    document.getElementsByClassName("alert-danger")[i].style.display = "none";
  }
  var elementIndex = 0;
  showElementQuiz(elementIndex);
}

/**
 * Cette fonction utilise une expression régulière pour échapper les caractères spéciaux et supprimer les espaces d'une chaine de caractères.
 * Nous l'utilisons pour "parser" une réponse de l'utilisateur ainsi que l'élément réponse de la carte.
 * 
 * De façon générale, cela permet de réduire le taux d'injustice subi par le joueur s'il tape malencontreusement un espace.
 * Cette fonction peut être améliorée à l'avenir pour prévenir une faille XSS.
 */

function escapeCharas(s) {
  return s.replace(/[\\^$.*+?()[\]{}|!]/g,"").trim();
}

/**
 * Cette fonction compare la réponse que l'utilisateur a donné avec la bonne réponse.
 * Si elles correspondent, alors nous affichons une alerte verte pour signaler une bonne réponse.
 * Autrement, une alerte en rouge prévient le joueur qu'il s'est trompé.
 * 
 * Afin d'éviter les problèmes dus au case-sensitive, nous utilisons toLowerCase() pour passer la réponse de l'utilisateur
 * et la bonne réponse en minuscules. Ainsi, "renard" et "RenARd" renverront le même résultat.
 * 
 * Une fois l'étape de vérification faite, nous passons à l'élément suivant du quiz après une seconde, grace à setTimeout().
 */

function verifierReponse(n, motOriginal, bonneReponse)
{
    if(escapeCharas(document.getElementsByClassName("uneReponseUser")[n].value.toLowerCase()) == escapeCharas(bonneReponse.toLowerCase()))
    {
      document.getElementsByClassName("alert-success")[n].style.display = "block";
      document.getElementsByClassName("bonnesReponses")[0].innerHTML = String(parseInt(document.getElementsByClassName("bonnesReponses")[0].innerHTML) + 1);

      if(parseInt(document.getElementsByClassName("bonnesReponses")[0].innerHTML) == 1)
        document.getElementsByClassName("bonnesReponsesTexte")[0].innerHTML = "bonne réponse"; 
      else
        document.getElementsByClassName("bonnesReponsesTexte")[0].innerHTML = "bonnes réponses"; 
    }
    else
    {
      document.getElementsByClassName("alert-danger")[n].style.display = "block";

      document.getElementsByClassName("listeMauvaisesReponses")[0].innerHTML += 
      "La bonne traduction de <u class='font-italic text-info'>" 
      + motOriginal 
      + "</u> était <u class='font-italic text-danger'>" 
      + bonneReponse + "</u>.<br/>"
      ; 
    }
    setTimeout(showElementQuiz,1000, n+1);
}

/**
 * Une fois notre carousel créé, nous devons camoufler l'intégralité des éléments, à l'exception de celui ayant pour index "n" qui est le
 * paramètre de cette fonction.
 * finalCard représente la carte donnant le nombre de bonnes réponses. Elle s'affiche si nous avons terminé le quiz.
 */

function showElementQuiz(n) {
  var i;
  var elements = document.getElementsByClassName("uneCarteQuestion");
  var finalCard = document.getElementsByClassName("uneCarteResultats")[0];
  
  //if (n >= elements.length) { elementIndex = 0;}
  for (i = 0; i < elements.length; i++) {
      elements[i].style.display = "none";
  }
  finalCard.style.display = "none";

  if (n < elements.length)
    elements[n].style.display = "block";
  else
    {
      finalCard.style.display = "block";
    }
} 

// Pour gérer les révisions

/**
 * La section révision nécessite un bouton "précédent" et un bouton "suivant".
 * Nous désactivons le bouton "précédent" pour la première carte du tunnel de révision
 * et le bouson "suivant" pour la dernière.
 * 
 * Dans le cadre de la fonction d'initiatlisation, nous utilisons la fonction showElementRevision.
 * Celle-ci va camoufler tous les éléments de révision, sauf celui ayant pour index le paramètre qui lui est associé,
 * ici 0.
 */

function initialiseRevision()
{
  showElementRevision(0);

  var elementsPrecedent = document.getElementsByClassName("rev-precedent");
  var elementsSuivant = document.getElementsByClassName("rev-suivant");

  elementsPrecedent[0].disabled = true;
  elementsSuivant[(elementsSuivant.length)-1].disabled = true;

}
/**
 * Cette foncion est appelée lorsque nous cliquons sur les boutons "précédent" et "suivant".
 * Ces boutons auront chacun des paramètres différents. Actuel est l'élément actuel de la révision.
 * Chaine détermine si c'est l'élément précédent ou suivant qui doit être affiché.
 */
function RevisionNavigation(actuel, chaine)
{
  if(chaine == 'precedent')
      showElementRevision(actuel-1);

  else if(chaine == 'suivant')
      showElementRevision(actuel+1);
}

/**
 * Cette fonction camoufle tous les éléments des révisions et affiche le n-ième.
 * S'il s'agit du dernier élément du tunnel de révision, nous activons également le bouton qui permet de recommencer. 
 */
function showElementRevision(n)
{
  var elements = document.getElementsByClassName("uneCarteRevision");
  for (i = 0; i < elements.length; i++)
      elements[i].style.display = "none";

  elements[n].style.display = "block";
  
  var monBoutonRecom = document.getElementsByClassName("recom-button")[0];

  if(n == elements.length-1)
    monBoutonRecom.disabled = false;
  else
    monBoutonRecom.disabled = true;
}