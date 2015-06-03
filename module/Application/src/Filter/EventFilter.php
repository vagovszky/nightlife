<?php
namespace Application\Filter;
use Zend\InputFilter\InputFilter;

class EventFilter extends InputFilter
{
    public function init()
    {
        // id
        $this->add(array(
                'name' => 'id',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'Alnum'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 32),
                ),
        )));
        // url
        $this->add(array(
                'name' => 'url',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // title
        $this->add(array(
                'name' => 'title',
                'required' => true,
                'allow_empty' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // description
        $this->add(array(
                'name' => 'description',
                'required' => true,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    //array('name' => 'StripTags'),
                    //array('name' => 'HtmlEntities', 'options' => array('quotestyle' => ENT_COMPAT)),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 65535),
                ),
        )));
        // img_big_url
        $this->add(array(
                'name' => 'img_big_url',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // img_url
        $this->add(array(
                'name' => 'img_url',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // email
        $this->add(array(
                'name' => 'email',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 100)),
                    array( 'name' => 'EmailAddress', 'options' => array('domain' => false)),
                ),
        ));
        // url_web
        $this->add(array(
                'name' => 'url_web',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // phone
        $this->add(array(
                'name' => 'phone',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 50),
                ),
        )));
        // map_iframe_url
        $this->add(array(
                'name' => 'map_iframe_url',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 1024),
                ),
        )));
        // place_url_detail
        $this->add(array(
                'name' => 'place_url_detail',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // place
        $this->add(array(
                'name' => 'place',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // entry_amount
        $this->add(array(
                'name' => 'entry_amount',
                'required' => false,
                'allow_empty' => true,
                'validators' => array(
                    array( 'name' => 'IsInt',
                ),
        )));
        // drink_list_url
        $this->add(array(
                'name' => 'drink_list_url',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // social_url
        $this->add(array(
                'name' => 'social_url',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // date
        $this->add(array(
                'name' => 'date',
                'required' => false,
                'allow_empty' => true,
                'validators' => array(
                    array( 'name' => 'Date', 'options' => array('format' => 'Y-m-d'),
                ),
        )));
        // time
        $this->add(array(
                'name' => 'time',
                'required' => false,
                'allow_empty' => true,
                'validators' => array(
                    array( 'name' => 'Date', 'options' => array('format' => 'H:i:s'),
                ),
        )));
        // street
        $this->add(array(
                'name' => 'street',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 255),
                ),
        )));
        // city
        $this->add(array(
                'name' => 'city',
                'required' => false,
                'allow_empty' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array( 'name' => 'StringLength', 'options' => array('max' => 100),
                ),
        )));
    }
}    