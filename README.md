activation du server : php -S 127.0.0.1:8001 -t public

- TODO Flash click créer une annonce
- TODO update_at \Datetime
- TODO published_at -> if public true => \Datetime
- TODO checkbox disapear when @media ≈ 1000
- TODO avatar si c'est upload/ OU http://.
- TODO avatar par defaut si rien
- remplacer les logos

* TODO dans UserController sur /me : créer des fonctions selon les besoins
* TODO form de créer une annonce

? {% if app.session is not empty %} ne mache pas sur firefox => user/profile
=> après 1h30 de recherche, il fallait remplacer app.session is not empty pas par app.session.start is not empty

Ajout de composer require knplabs/knp-time-bundle for date)

- TODO faire fil d'ariane
  Ajout de composer require knplabs/knp-menu-bundle (file d'ariane)

Changement d'horaire => php.ini date.timezone ?

/!\ Password with constrains (side back)

-TODO onclick(btn-profile-card) => app_login if app.user is empty OK
BUT /!\ after login, please come back previous page and not home
