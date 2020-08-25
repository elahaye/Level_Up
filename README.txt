Voici LevelUp, une application permettant d'optimiser et d'améliorer son temps et sa productivité. 

Elle a été réalisée dans le cadre de la formation Développeur-Intégrateur de la 3WA Academy afin de valider mes compétences. Les conditions de réalisation étaient d'utiliser les 5 langages de programmation appris durant la formation : HTML, CSS, JS, PHP et SQL.
J'y ai également ajouté plusieurs contraintes : la création de mon propre framework MVC, une page de CSS généralisée ainsi qu'aucune utilisation de librairie.


--------------------------- EXPLICATIONS TECHNIQUES ---------------------------------

J'ai donc utilisé pour cette application un framework MVC conçu from scratch. Il y a donc une démarcation entre les intéractions à la base de données (Model), les actions effectuées par l'utilisateur (Controller) et les données à afficher (View).

Le Router récupère le nom de la route indiqué dans l'URL et charge le Controller et la View correspondants. 

Le système de routing est également utlisé pour les liens AJAX. Selon le nom indiqué dans l'URL, une fonction du Controller est activée et effectue les action souhaitées puis retourne dans une variable les informations nécessaires. Pour résumer, on indique le nom de la route, la fonction du Controller concernée et le nom de la variable retournée.



--------------------------- STATUT DE L'UTILISATEUR ---------------------------------

Il y a plusieurs types de fonctionnalités selon le statut de l'utilisateur. Nous sommes sur un système pyramidale de fonctionnalités selon le poids du statut (plus haut statut = plus de fonctionnalités). 

1 - Non-Connecté : un utilisateur non-connecté ne pourra avoir accès qu'aux listes des articles publiés par les auteurs du site, les pages d'accueil et de contact ainsi qu'aux pages de connexion et d'inscription

    - Inscription / Connexion
    - Lecture des articles
    - Création commentaires

2 - Connecté basic : un utilisateur connecté basique aura accès à la fonctionnalité du calendrier (élément principal de l'application) ainsi qu'à la possibilité de modification de ses informations personnelles.

    - Création / Modification / Suppression de tâches (validation et invalidation)
    - Réduction du porte-monnaie virtuel
    - Modification profil

3 - Auteur : un utilisateur de statut "auteur" aura un accès administrateur restreint lui permettant d'accèder à ses articles et les commentaires qui leur auront été attribués. Il y aura donc la posibilité de créer, modifier et encore supprimer ses articles.

    - Création / Modification / Suppression d'articles
    - Suppression de commentaires

4- Administrateur : un utilisateur de statut "administrateur" aura un accès total à l'application avec l'accès à tous les articles publiés, tous les utilisateurs inscrits avec possibilité de modifier leur statut (basic, auteur, admin) ou de les supprimer. Dans un intérêt de protection des données des utilisateurs, l'administrateur pourra supprimer un article dont il n'est pas l'auteur mais ne pourra pas le modifier. 

    - Modification / Suppression d'utilisateurs (modification statut)
    - Suppression d'articles


--------------------------- FONCTIONNALITE CALENDRIER ---------------------------------

La fonctionnalité du calendrier est la principale de cette application. Son but est de permettre à l'utilisateur de pouvoir gérer son temps par un système de tâches qui offriront à leur réalisation une récompense.

Pour chaque tâche réalisée, une certaine somme d'argent virtuelle sera ajoutée au porte-monnaie de l'utilisateur selon le degré d'importance de la tâche accomplie. Après avoir accumulé une certaine somme, l'utilisateur pourra alors récompenser son assiduité par un achat réel correspondant à la somme ou moins accumulée. L'utlisateur n'aura plus qu'à déduire la somme de l'achat à son porte-monnaie et accumulé de nouveau de l'argent. Cette application foncitonne grâce à la bonne volonté de ses utilisateurs.

Les utilisateurs pourront donc créer une tâche en lui indiquant un titre accrocheur pour la reconnaître, une description rapide, une date de fin et l'importance de cette tâche. Ses tâches peuvent être modifiées ou supprimées grâce aux deux icônes situés en bas de la tâche (affichées lorsque l'on clique dessus). 
Lorsque la tâche est réalisée ou est un échec, on peut la valider ou l'invalider par les icônes de gauche et de droite, elles passeront donc dans une autre couleur (rouge ou verte) et ne pourront plus être modifier ou supprimer. Une certaine somme d'argent sera également ajouté ou déduit du porte-monnaie.

Le système de date utlise le plugin JS FullCalendar qui permet de gérer facilement le choix de la date choisie pour l'affichage des tâches.

Lorsque l'on clique sur le porte-monnaie, on a la possiblité de déduire une somme du total accumulé.



---------------------- FONCTIONNALITE CREATION D'ARTICLE --------------------------------

Pour la création d'un article, il faut y indiquer un titre, un contenu, une catégorie et une image. 

La création du contenu est possible grâce à TinyMCE un éditeur de HTML qui permet plus de liberté de mise en page aux auteurs (liberté tout de même contrôlé).

Les images sont stockées dans un ficher image interne au dossier View. Leur nom est modifié à l'ajout afin qu'elle soit unique à chaque article. 






