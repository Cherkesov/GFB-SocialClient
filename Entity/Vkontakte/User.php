<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 03.03.2015
 * Time: 0:04
 */

namespace GFB\SocialClientBundle\Entity\Vkontakte;


class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var int */
    private $sex;

    /** @var string */
    private $nickName;

    /** @var string */
    private $screenName;

    /** @var int */
    private $cityId;

    /** @var int */
    private $countryId;

    /** @var string */
    private $photoUrl;

    /** @var string */
    private $photoMediumUrl;

    /** @var string */
    private $photoBigUrl;

    /** @var bool */
    private $hasMobile;

    /** @var bool */
    private $online;

    /** @var int */
    private $university;

    /** @var string */
    private $universityName;

    /** @var int */
    private $faculty;

    /** @var string */
    private $facultyName;

    /** @var int */
    private $graduationYear;

    /** @var string */
    private $educationForm;

    /** @var string */
    private $educationStatus;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return int
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param int $sex
     * @return $this
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @param string $nickName
     * @return $this
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;
        return $this;
    }

    /**
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * @param string $screenName
     * @return $this
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;
        return $this;
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @param int $cityId
     * @return $this
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param int $countryId
     * @return $this
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * @param string $photoUrl
     * @return $this
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoMediumUrl()
    {
        return $this->photoMediumUrl;
    }

    /**
     * @param string $photoMediumUrl
     * @return $this
     */
    public function setPhotoMediumUrl($photoMediumUrl)
    {
        $this->photoMediumUrl = $photoMediumUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoBigUrl()
    {
        return $this->photoBigUrl;
    }

    /**
     * @param string $photoBigUrl
     * @return $this
     */
    public function setPhotoBigUrl($photoBigUrl)
    {
        $this->photoBigUrl = $photoBigUrl;
        return $this;
    }

    /**
     * @return bool
     */
    public function getHasMobile()
    {
        return $this->hasMobile;
    }

    /**
     * @param bool $hasMobile
     * @return $this
     */
    public function setHasMobile($hasMobile)
    {
        $this->hasMobile = $hasMobile;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param bool $online
     * @return $this
     */
    public function setOnline($online)
    {
        $this->online = $online;
        return $this;
    }

    /**
     * @return int
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * @param int $university
     * @return $this
     */
    public function setUniversity($university)
    {
        $this->university = $university;
        return $this;
    }

    /**
     * @return string
     */
    public function getUniversityName()
    {
        return $this->universityName;
    }

    /**
     * @param string $universityName
     * @return $this
     */
    public function setUniversityName($universityName)
    {
        $this->universityName = $universityName;
        return $this;
    }

    /**
     * @return int
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * @param int $faculty
     * @return $this
     */
    public function setFaculty($faculty)
    {
        $this->faculty = $faculty;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacultyName()
    {
        return $this->facultyName;
    }

    /**
     * @param string $facultyName
     * @return $this
     */
    public function setFacultyName($facultyName)
    {
        $this->facultyName = $facultyName;
        return $this;
    }

    /**
     * @return int
     */
    public function getGraduationYear()
    {
        return $this->graduationYear;
    }

    /**
     * @param int $graduationYear
     * @return $this
     */
    public function setGraduationYear($graduationYear)
    {
        $this->graduationYear = $graduationYear;
        return $this;
    }

    /**
     * @return string
     */
    public function getEducationForm()
    {
        return $this->educationForm;
    }

    /**
     * @param string $educationForm
     * @return $this
     */
    public function setEducationForm($educationForm)
    {
        $this->educationForm = $educationForm;
        return $this;
    }

    /**
     * @return string
     */
    public function getEducationStatus()
    {
        return $this->educationStatus;
    }

    /**
     * @param string $educationStatus
     * @return $this
     */
    public function setEducationStatus($educationStatus)
    {
        $this->educationStatus = $educationStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->getLastName()} {$this->getFirstName()} [{$this->getNickName()}]";
    }
}