Se projektet på min hjemmeside: [https://tracking.rasmuslauridsen.dk](https://tracking.rasmuslauridsen.dk)

Opret en bruger og aktiver tracking. Brug helt telefonen, da GPS'en er mest præcis på den. Gå lidt rundt og se prikkerne. Genindlæs evt. siden, hvis der skulle være nogen fejl. Der kommer prikker alle steder, du er blevet logget. 

Hvis du loader min konto (brugerid=1), vil du se en tur rundt, hvor jeg kørte i bil. 
Du starter som brugerniveau "free", men fx min konto er admin. Pt. gør det ingen forskel ud over den der label i toppen. Det er dog nemt at differentiere og laver funktioner specifikt til hvert brugerniveau.

### Database (MySQL)
Alle passwords er hashet 2 gange. Først client-side, så de ikke transporteres usikret på trods af en post-request. Dernæst hashes de igen på serveren før de sættes ind i databasen. 
Der er ikke rigtig grund til at hashe andet. Lokationerne er lavet, så alle kan se alle (hvis de kender eller gætter bruger id). Det er ikke sikret, men det er heller ikke meningen, da man i denne service skal deles om alle oplysninger. Ellers kunne man have lavet unikke, lange links til at se hinandens lokationer eller kun give bestemte brugere adgang. 

Brugerlevel er lavet som en tabel for sig selv, hvor brugerdatabasen holder en int, der linker til et bestemt niveau i membership_levels tabellen. 

Locations-tabellen holder et id for selve lokationen for at holde styr på dem. Så holdes der et bruger-id, der linker over til selve brugertabellen, så man ved, hvilken bruger, der har gemt denne lokation og dernæst et koordinatsæt. Sidst ligger der også et automatisk timestamp, så man kan se på tiden - både sortering, men også til brugeren, så man kan se, hvornår man var det pågældende sted. 

### Kode
Koden er lavet med formål at forhindre SQL-injection og diverse andre ubehageligheder. Alle SQL-statement er lavet som prepared statements samt alt user-input er filtreret med PHP's egen filter-funktioner. Sammen forhindrer de i høj grad SQL-injection. 

Jeg har brugt mit eget login-system, der er skrevet med en masse sikkerhedsfunktioner indbygget. Derfor er al koden heller ikke relevant/specifikt for projektet, men bare sikkerhed. 

Der bruges en kort-service til at vise kortet og så laves der punkter ud fra PHP og JS. 

Der kræves login for at se kortet - ellers bliver man sendt tilbage til login-siden. 

**FYI: Hvis du ikke har en SCSS-compiler, skal du bare kigge i style.css-filen og ignorere style.scss**

### Tabeller
Herunder er CREATE-statements til tabellerne i databasen. Der ligger filer med udprint af tabellerne her på GitHub ved navn *.sql. 

**Users**
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(45) DEFAULT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(256) NOT NULL,
  `membership` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

**Locations**
CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `loc_lat` float(10,8) DEFAULT NULL,
  `loc_long` float(10,8) DEFAULT NULL,
  `dt_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

**Member_levels**
CREATE TABLE `member_levels` (
  `member_level_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`member_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

