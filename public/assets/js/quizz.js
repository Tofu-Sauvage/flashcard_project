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

function escapeCharas(s) {
  return s.replace(/[\\^$.*+?()[\]{}|!]/g,"").trim();
}

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

function initialiseRevision()
{
  var elementIndex = 0;
  showElementRevision(elementIndex);

  var elementsPrecedent = document.getElementsByClassName("rev-precedent");
  var elementsSuivant = document.getElementsByClassName("rev-suivant");

  elementsPrecedent[0].disabled = true;
  elementsSuivant[(elementsSuivant.length)-1].disabled = true;

}

function RevisionNavigation(actuel, chaine)
{
  if(chaine == 'precedent')
      showElementRevision(actuel-1);

  else if(chaine == 'suivant')
      showElementRevision(actuel+1);
}


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

// pour la section Recherche.

function lancerRecherche()
{
    var monTermeARechercher = document.getElementsByClassName("uneRecherche")[0].value;
    
    window.location = "rechercher/" + monTermeARechercher;
}

function relancerRecherche()
{
    var monTermeARechercher = document.getElementsByClassName("uneRecherche")[0].value;
    
    window.location = monTermeARechercher;
}
