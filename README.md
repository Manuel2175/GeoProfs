GeoProfs project
Groep van 4 : Manuel, Stijn, Alec, Angelo

Tijdens dit project gaan we een casus uitwerken over een tijd van 18 weken
We gaan de volgende talen / frameworks gebruiken : Laravel, Vue.js, Tailwind.
Voor het testen gebruiken we onder andere : Vitest, Swagger, Pest.

De casus samengevat waar dit project op gebaseerd is:
GeoProfs is een bedrijf dat alles kwa registratie en verlof nog via excel sheets doet.
Het is nu aan ons om dit te digitaliseren met talen / frameworks van onze keuze. 
Dit moet in samenwerking met elkaar en in een agile developement cycle. 

--------------------------------------------------------------------------
Instalatie stappen :
  -FrontendMmap:
    -npm install
    -npm run dev
    -
    -vitest install (indien nodig)
    -npm install --save-dev vitest @vitejs/plugin-vue @testing-library/vue @testing-library/jest-dom jsdom
    -npm run test
    -npm run test:ui (interactief op web)
  -BackendMap:
    - composer install
    - kopieer .env naar bestanden
    - php artisan key:generate
    - php artisan migrate --seed
    - php artisan serve
