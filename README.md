#Pokepedia-middleware

Symfony based application, consuming data from pokeapi/bulbapedia,csv ... , transforming , and uploading them to pokepedia.fr

## Requirements :

- Postgres database
- Php >= 7.4
- symfony application
-
##Installation : 

- git clone git@github.com:programgames/pokepedia-mapper.git
- cd pokepedia-mapper
- Create a postgres database and setup credentials in .env.local file in the root directory of the project
- php `bin/console app:install` ( this will take some hours)
- symfony server:start
