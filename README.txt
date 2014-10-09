Filter / Lectora


Het Lectora filter maakt het mogelijk het lectora bestand browser vullend te tonen. Er is afgesproken dat binnen de lectora modules triggers ingebouwd worden. Dit zijn HTML elementen, zoals een plaatje van een knop die een alt-tag hebben gekregen.

Bijvoorbeeld:
<img src="knopje.jpg" alt="end_of_lectora_module">

Het lectora filter voegt aan dit html element een javascript onclick actie toe. Hierdoor wordt de gebruiker terug naar de cursus geleid wanneer op dit knopje geklikt wordt.

de triggers zijn:

tijdens het doorlopen van de module:
alt=end_of_lectora_module

aan het einde van de module
lectora_module_completed

acties:
alt=end_of_lectora_module => vervang door link.

lectora_module_completed => zet de moodle completion status op voltooid.




Installatie instructies
=======================

Download dit filter van

http://basbrands.nl/private/filter-lectora-2014100900.zip
username: malmberg
password: sonsbeek

Verplaats het bestand naar de /filter map van de Moodle omgeving. Unzip het bestand en controleer of er een nieuwe folder "lectora" aangemaakt is.

Log in op de Moodle omgeving met een administrator account.

Navigeer dan naar: Site Beheer -> Meldingen. Dit start de plugin installatie procedure.

Na installatie dient het filter geactiveerd te worden. Navigeer daarvoor naar:

"Site Beheer -> Plugins -> Filters -> Beheer filters". Schakel op die pagina het filter in en pas het toe op "Content and headings".




Een lectora module toevoegen
============================
Navigeer naar de locatie waar de lectora module toegevoegd moet worden.
Klik op "Menu" -> "Zet wijzigingen aan"

In de cursus pagina wordt nu de link "Activiteit of bron toevoegen" beschikbaar. Klik hierop en selecteer bij kopje "Bronnen" het type bestand en klik op "Voeg toe".

Voer een naam en een beschrijving in. Selecteer dan een bestand om toe te voegen (de lectora zip).

Wanneer het bestand toegevoegd is wordt het zichtbaar in de "Files" box. Klik op het icoon voor het zip bestand, dit opent een pop-up.  Klik op "Unzip".
Nu zijn in de "Files" box all bestanden uit de zip zichtbaar. Selecteer het start bestand door er rechts op te klikken, een nieuwe pop-up verschijnt met de optie "Zet als hoofdbestand".

Na het toevoegen van het bestand moeten de module instellingen verder geconfigureerd worden. Klik hiervoor op "Verschijnen" onder de "Files" box. 

bij "toon": kies open

klik op "Toon meer" om de overige opties zichtbaar te maken.

bij "gebruik filters op de inhoud": kies "Enkel HTML bestanden"

onder aan de pagina moet als laatste de "Voltooide activiteiten" geconfigureerd worden. Klik op de titel "Voltooide activiteiten" en selecteer "Toon activiteit als aan de voorwaarde voldaan is".

Klik vervolgens op de checkbox "Bekijken vereist"

** geen "Voltooide activiteiten" optie? => Volg de instructies bij "Staat module completion aan?" hieronder.




Staat module completion aan?
============================
Het filter zet de status van de lectora module op completed wanneer de "lectora_module_completed" in de pagina is aangetroffen.

Hiervoor moet het moodle completion systeem ingeschakeld zijn op systeem en cursus niveau. 

Ga hiervoor naar:

"Site Beheer -> Geavanceerd" en controleer of "Bijhouden van voltooiing inschakelen" aan staat.

Controleer ook of voltooiing binnen de cursus ingeschakeld is:

Ga hiervoor eerst naar de cursus en klik dan op

"Cursusbeheer -> Bewerk Instellingen" en controleer of "Voltooien bijhouden" actief is.



