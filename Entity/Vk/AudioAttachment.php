<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.03.2015
 * Time: 0:45
 */

namespace GFB\SocialClientBundle\Entity\Vk;


class AudioAttachment extends AbstractAttachment
{
    /** @var string */
    private $artist;

    /** @var string */
    private $title;

    /** @var int */
    private $duration;

    /** @var string */
    private $url;

    /** @var string */
    private $performer;

    /** @var int */
    private $lyricsId;

    /** @var int */
    private $genre;

    /**
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     * @return $this
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * @param string $performer
     * @return $this
     */
    public function setPerformer($performer)
    {
        $this->performer = $performer;
        return $this;
    }

    /**
     * @return int
     */
    public function getLyricsId()
    {
        return $this->lyricsId;
    }

    /**
     * @param int $lyricsId
     * @return $this
     */
    public function setLyricsId($lyricsId)
    {
        $this->lyricsId = $lyricsId;
        return $this;
    }

    /**
     * @return int
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param int $genre
     * @return $this
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

    /**
     * @return string
     */
    public function toRichText()
    {
        return '<div class="audio"><a href="' . $this->getUrl() . '">' . $this->getArtist() . ' - ' . $this->getTitle() . '</a></div>';
    }
}