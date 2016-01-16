<?php

namespace Autowp\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class VkUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['uid'];
    }

    /**
     * Get perferred first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->response['first_name'];
    }

    /**
     * Get perferred last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->response['last_name'];
    }

    /**
     * Get screen name.
     *
     * @return string
     */
    public function getScreenName()
    {
        return $this->response['screen_name'];
    }

    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getPhotoMedium()
    {
        if (!empty($this->response['photo_medium'])) {
            return $this->response['photo_medium'];
        }
        return null;
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
