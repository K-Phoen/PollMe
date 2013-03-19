<?php

namespace PollMe\Twig\Extensions;


class GravatarExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('gravatar', array($this, 'getGravatar')),
        );
    }

    public function getGravatar($mail, $size = 40)
    {
        return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($mail))) . '?s=' . $size . '&d=wavatar';
    }

    public function getName()
    {
        return 'gravatar';
    }
}
