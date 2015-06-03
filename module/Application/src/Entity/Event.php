<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event", indexes={@ORM\Index(name="idx_url", columns={"url"}), @ORM\Index(name="idx_title", columns={"title"})})
 * @ORM\Entity
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string", length=32, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="img_big_url", type="string", length=255, nullable=true)
     */
    private $imgBigUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255, nullable=true)
     */
    private $imgUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="url_web", type="string", length=255, nullable=true)
     */
    private $urlWeb;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="map_iframe_url", type="text", length=65535, nullable=true)
     */
    private $mapIframeUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="place_url_detail", type="string", length=255, nullable=true)
     */
    private $placeUrlDetail;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @var integer
     *
     * @ORM\Column(name="entry_amount", type="integer", nullable=true)
     */
    private $entryAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="drink_list_url", type="string", length=255, nullable=true)
     */
    private $drinkListUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="social_url", type="string", length=255, nullable=true)
     */
    private $socialUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;


    /**
     * Set id
     *
     * @param string $id
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get id
     *
     * @return Event
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set url
     *
     * @param string $url
     *
     * @return Event
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgBigUrl
     *
     * @param string $imgBigUrl
     *
     * @return Event
     */
    public function setImgBigUrl($imgBigUrl)
    {
        $this->imgBigUrl = $imgBigUrl;

        return $this;
    }

    /**
     * Get imgBigUrl
     *
     * @return string
     */
    public function getImgBigUrl()
    {
        return $this->imgBigUrl;
    }

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     *
     * @return Event
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Event
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set urlWeb
     *
     * @param string $urlWeb
     *
     * @return Event
     */
    public function setUrlWeb($urlWeb)
    {
        $this->urlWeb = $urlWeb;

        return $this;
    }

    /**
     * Get urlWeb
     *
     * @return string
     */
    public function getUrlWeb()
    {
        return $this->urlWeb;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Event
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mapIframeUrl
     *
     * @param string $mapIframeUrl
     *
     * @return Event
     */
    public function setMapIframeUrl($mapIframeUrl)
    {
        $this->mapIframeUrl = $mapIframeUrl;

        return $this;
    }

    /**
     * Get mapIframeUrl
     *
     * @return string
     */
    public function getMapIframeUrl()
    {
        return $this->mapIframeUrl;
    }

    /**
     * Set placeUrlDetail
     *
     * @param string $placeUrlDetail
     *
     * @return Event
     */
    public function setPlaceUrlDetail($placeUrlDetail)
    {
        $this->placeUrlDetail = $placeUrlDetail;

        return $this;
    }

    /**
     * Get placeUrlDetail
     *
     * @return string
     */
    public function getPlaceUrlDetail()
    {
        return $this->placeUrlDetail;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Event
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set entryAmount
     *
     * @param integer $entryAmount
     *
     * @return Event
     */
    public function setEntryAmount($entryAmount)
    {
        $this->entryAmount = $entryAmount;

        return $this;
    }

    /**
     * Get entryAmount
     *
     * @return integer
     */
    public function getEntryAmount()
    {
        return $this->entryAmount;
    }

    /**
     * Set drinkListUrl
     *
     * @param string $drinkListUrl
     *
     * @return Event
     */
    public function setDrinkListUrl($drinkListUrl)
    {
        $this->drinkListUrl = $drinkListUrl;

        return $this;
    }

    /**
     * Get drinkListUrl
     *
     * @return string
     */
    public function getDrinkListUrl()
    {
        return $this->drinkListUrl;
    }

    /**
     * Set socialUrl
     *
     * @param string $socialUrl
     *
     * @return Event
     */
    public function setSocialUrl($socialUrl)
    {
        $this->socialUrl = $socialUrl;

        return $this;
    }

    /**
     * Get socialUrl
     *
     * @return string
     */
    public function getSocialUrl()
    {
        return $this->socialUrl;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Event
     */
    public function setDate($date)
    {
        if(isset($date)){
            if(is_string($date)){
                $date = \DateTime::createFromFormat('Y-m-d',trim($date));
            }
        }
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Event
     */
    public function setTime($time)
    {
        if(isset($time)){
            if(is_string($time)){
                $time = \DateTime::createFromFormat('H:i:s',trim($time));
            }
        }
        $this->time = $time;
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Event
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Event
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /*
     * Populate object from array
     * 
     * @param array $array
     * @return Event
     * 
     */
    public function populate(array $array){
        if(empty($array["id"])){
            throw new \Exception("Event entity: id must be defined!");
        }else{
            $this->setId($array['id']);
        }
        if(!empty($array["url"])) $this->setUrl($array["url"]);
        if(!empty($array["title"])) $this->setTitle($array["title"]);
        if(!empty($array["description"])) $this->setDescription($array["description"]);
        if(!empty($array["img_big_url"])) $this->setImgBigUrl($array["img_big_url"]);
        if(!empty($array["img_url"])) $this->setImgUrl($array["img_url"]);
        if(!empty($array["email"])) $this->setEmail($array["email"]);
        if(!empty($array["url_web"])) $this->setUrlWeb($array["url_web"]);
        if(!empty($array["phone"])) $this->setPhone($array["phone"]);
        if(!empty($array["map_iframe_url"])) $this->setMapIframeUrl($array["map_iframe_url"]);
        if(!empty($array["place_url_detail"])) $this->setPlaceUrlDetail($array["place_url_detail"]);
        if(!empty($array["place"])) $this->setPlace($array["place"]);
        if(!empty($array["entry_amount"])) $this->setEntryAmount($array["entry_amount"]);
        if(!empty($array["drink_list_url"])) $this->setDrinkListUrl($array["drink_list_url"]);
        if(!empty($array["social_url"])) $this->setSocialUrl($array["social_url"]);
        if(!empty($array["date"])) $this->setDate($array["date"]);
        if(!empty($array["time"])) $this->setTime($array["time"]);
        if(!empty($array["street"])) $this->setStreet($array["street"]);
        if(!empty($array["city"])) $this->setCity($array["city"]);
        return $this;
    }
}
