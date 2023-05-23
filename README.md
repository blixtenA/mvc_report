[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/blixtenA/mvc_report/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/blixtenA/mvc_report/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/blixtenA/mvc_report/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/blixtenA/mvc_report/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/blixtenA/mvc_report/badges/build.png?b=master)](https://scrutinizer-ci.com/g/blixtenA/mvc_report/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/blixtenA/mvc_report/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)


Kursrepo för MVC-kursen BTH vt-2023 
Innehåller ett bygge av ett äventyrsspel inspirerat av Cube-filmerna med ett överaskningsframträdandet av kaninen från The Holy Grail. Här finns ett databasbygge för SQLite, en spelarkitektur för webben byggd med Symfony. Fokus i bygget var objekt hanterade i php. Innehåller också ett bygg-interface för den som vill bygga sitt eget spel eller ordna om komponenterna. Konsten är tillverkad av Craion (https://www.craiyon.com/). 

Backup av databasen: var/data.bak
För att återställa: sqlite3 var/data.db < var/data.bak
Kör igång webbplatsen från projektrooten: php -S localhost:8888 -t public