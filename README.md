# NL events parser

## Objective

1. Download & parse the html content **(completed)**
2. Store parsed data to DB (optional) **(in progress)**
3. Populate a XML (to be specified)
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
    - TBD

#### 3) Populate a XML (to be specified)

I need the documentation (later)

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
- https://github.com/ziollek/PAXB
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