# NL events parser

## Objective

1. Download & parse the html content **(completed)**
2. Store parsed data to DB (optional) **(completed)**
3. Populate a XML (to be specified) **(completed)**
4. Expose the XML file
5. Usage, tuning, deployment, terms of use

For deployment we are going to use phar archive (whole project in one file)
- **bin/create_phar.php**

Note: All the code will be programmed in PHP

## The solution

#### 1) Downloading & parsing the html content by  using _paquettg/php-html-parser_

- Download the list:
- http://www.the-night-life.cz/udalosti?filter[city]=Brno&count=50
- Snippet of html - list item:
```html
<div class="item">
    <a href="{detail_url}" title="{title}" class="image">
        <img src="{img_url_small}" />
    </a>
    <div class="texts">
        <div class="column_left">
            <h2><a href="{detail_url}" title="{title}">{title}</a></h2>
        </div>
        <div class="column_right">
            <div class="info">
                <div class="item">
                    <strong>Kde</strong><br />
                    <span><a href="{place_url}">{place}</a></span>
                </div>
                <div class="item">
                    <strong>Kdy</strong><br />
                    <span>{date}<br/>{time}</span>
                </div>
                <div class="item">
                    <strong>Vstup</strong><br />
                    <span>{entry_amount} Kč</span>
                </div>
            </div>
        </div>
    </div>
</div>
```
- for whole list **we have to follow** (click) **all links** in {detail_url}
- **We have to download all detail pages from previous step - !!50 requests!!**
- Exapmple of detail page
	- See http://www.the-night-life.cz/podniky?action=detail-event&id=1210
	- or http://www.the-night-life.cz/jazzova-legenda-jaromir-hnilicka-hostejam-session-3
	- !!! Different format of URL !!!
- Snippet of html - detail:
```html
<div class="part_head">
    <div class="left_side">
        <div class="image">
            <a href="{img_big_url}" class="CeraBox">
                <img src="{img_url}" alt="{title}" />
            </a>
        </div>
        <div class="contact">
            <div class="left_s">
                <div class="email">
                    {email}
                </div>
                <div class="address">
                    {street}<br />
                    {city} 
                </div>
                <div class="web">
                    <a href="{url_web}">Odkaz na web podniku</a>
                </div>
            </div>
            <div class="right_s">
                <div class="phone">
                    {phone}
                </div>
            </div>
        </div>
        <div class="map">
            <iframe src="{map_iframe_url}" width="390" height="294" frameborder="0" style="border:0"></iframe>
        </div>
    </div>
    <div class="right_side">
        <div class="main">
            <div class="title">
                <div class="info">
                    <div class="item">
                        <strong>Kde</strong><br />
                        <span><a href="{place_url_detail}">{place}</a></span>
                    </div>
                    <div class="item">
                        <strong>Kdy</strong><br />
                        <span>{date}<br/>{time}</span>
                    </div>
                    <div class="item">
                        <strong>Vstup</strong><br />
                        <span>{entry_amount} Kč</span>
                    </div>

                </div>
                <h1>{title}</h1>
                <h2>{pub_activity}</h2>
            </div>
            <div class="desc apply-ui-scrollbar">
                {description}
            </div>
            <div class="foot">
                <a class="drink_list" href="{drink_list_url}">
                    Nápojový <br />lístek
                </a>
                <div class="social">
                    <a href="{social_url}" target="_blank" class="facebook"></a>
                </div>
            </div>
        </div>
        <div class="bottom_left">
            <h2>Místní dj's</h2>
            <div class="inside">
                <p>Aktuální informace na webové stránce podniku.</p>
            </div>
        </div>
        <div class="bottom_right">
            <h2>Akční nabídka</h2>
            <div class="inside">
                <p>Podnik nabízí speciální nabídky v rámci akcí.</p>
            </div>
        </div>
    </div>
</div>
```

#### 2) Use Doctrine ORM to store data to DB

- for kick off - MySQL
- Use Entities defined in **module/Application/src/Entity**
- One table:
    - **event** _The list of events_
- Table columns:

```sql
CREATE TABLE `event` (
  id int(11) NOT NULL AUTO_INCREMENT,
  url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  description text COLLATE utf8_unicode_ci,
  img_big_url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  img_url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  email varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  url_web varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  phone varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  map_iframe_url text COLLATE utf8_unicode_ci,
  place_url_detail varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  place varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  entry_amount int(11) DEFAULT NULL,
  drink_list_url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  social_url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  street varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  city varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_url (url),
  KEY idx_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

#### 3) Populate a XML (to be specified)

- xml definition:

```xml
<?xml version='1.0' encoding='utf-8'?>
<ARTICLES>
    <ARTICLE_ITEM>
        <ID>unikátní a neměnné označení příspěvku. Např: 0001</ID>
        <PEREX>Krátký úvodník, který se zobrazuje v těle článku. Max. délka = 225 znaků</PEREX>
        <PREVIEW>Upoutávka článku - zobrazí se ve výpisech článků jako podnadpis. Max. délka 225 znaků.</PREVIEW>
        <TITLE>titulek, H1, meta title</TITLE>
        <CONTENT>
        Tělo článku. Jsou podporovány základní html tagy. Pro jejich úspěšný import je potřeba uzavřít obsah javascript paramatrem CDATA. Příklad:
        <![CDATA[
        for (i=0; i < 10; $++)
        {
            document.writeln("<p>Zde má být obsah článku, který má podporovat základní html tagy pro formátování textu.</p>
                <h2>Test nadpisu h2</h2>
                <p>Normální text</p>
                <h3>Test nadpisu h3</h3>
                <p>Normální text se seznamen 
                <ul>
                    <li>položka 1</li>
                    <li>položka 2</li>
                </ul>
                Tady pokračuje normální text.
                <span  style="color: red; font-size: 150 %;">Tento text nebude červený</span>.
            </p> ");
        }
        ]]>
        </CONTENT>
        <IMGURL>URL k hlavnímu obrázku článku (ideální rozměr 300x300 px)</IMGURL>
        <DATE_DISPLAY_FROM>DD-MM-YYYY HH:MM:SS</DATE_DISPLAY_FROM>
        <DATE_DISPLAY_TO>DD-MM-YYYY HH:MM:SS</DATE_DISPLAY_TO>
        <EVENT>
            <DATE_FROM>DD-MM-YYYY HH:MM:SS</DATE_FROM>
            <DATE_TO>DD-MM-YYYY HH:MM:SS</DATE_TO>
            <PRICE_INFO>Např: "Vstupné 350 Kč" (informace by měla být krátká do cca 20 znaků)</PRICE_INFO>
            <URL>odkaz směřující na online rezervaci</URL>
            <ADDRESS>Ulice č.p., Město</ADDRESS>
        </EVENT>
        <PHOTOGALLERY>
            <IMGURL>http://zdrojfoto.cz/obrazek-fotogalerie-1.jpg</IMGURL>
            <IMGURL>http://zdrojfoto.cz/obrazek-fotogalerie-2.jpg</IMGURL>
        </PHOTOGALLERY>
    </ARTICLE_ITEM>
</ARTICLES>
```

#### 4) Expose the XML file

TBD

#### 5) Usage, tuning, deployment, terms of use

TBD

## Resources & links

- http://www.the-night-life.cz
- http://www.the-night-life.cz/udalosti?filter[city]=Brno&count=100

#### Tools

- https://github.com/paquettg/php-html-parser
- https://github.com/zendframework/zf2
- http://www.doctrine-project.org/projects/orm.html
- http://php.net/


## License

TBD (GNU/GPL?)

## Instalation / compilation / usage

1. Install dependencies
    - php composer.phar install
2. Setup configuration (db connection etc.)
    - nano config/local.php
3. Install the database schema
    - php index.php orm:schema-tool:create
4. Compile into package (optional)
    - php bin/create.phar
5. Deploy package
    - cp bin/nlp.phar <destination>
    - cp -R config <destination>
6. Usage
    - php index.php or php <destination>/nlp.phar