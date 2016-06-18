<?php
/**
 * Author: Pavel Naumenko
 */

namespace frontend\widgets\openGraphMetaTags;

/**
 * Class Widget
 *
 * @package frontend\widgets\openGraphMetaTags
 */
class Widget extends \yii\base\Widget
{
    /**
     * @var
     */
    public $title;

    /**
     * @var
     */
    public $description;

    /**
     * @var
     */
    public $url;

    /**
     * @var
     */
    public $image;

    /**
     *
     */
    public function run()
    {
        $this->getView()->registerMetaTag(['name' => 'og:title', 'content' => $this->title]);
        $this->getView()->registerMetaTag(['name' => 'og:description', 'content' => $this->description]);
        $this->getView()->registerMetaTag(['name' => 'og:url', 'content' => $this->url]);
        $this->getView()->registerMetaTag(['name' => 'og:image', 'content' => $this->image]);

        //Twitter
        $this->getView()->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary']);
        $this->getView()->registerMetaTag(['name' => 'twitter:title', 'content' => $this->title]);
        $this->getView()->registerMetaTag(['name' => 'twitter:description', 'content' => $this->description]);
        $this->getView()->registerMetaTag(['name' => 'twitter:image', 'content' => $this->image]);
    }
}
