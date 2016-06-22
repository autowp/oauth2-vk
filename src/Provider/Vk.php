<?php

namespace Autowp\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Vk extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'user_id';

    /**
     * @var string If set, this will be sent as the "response_type" parameter.
     */
    protected $responseType = 'code';

    /**
     * @var string If set, this will be sent as the "display" parameter.
     */
    protected $display;

    /**
     * @var array Default fields to be requested from the user profile.
     * @link https://vk.com/dev/users.get
     */
    protected $defaultUserFields = [
        'uid',
        'first_name',
        'last_name',
        'screen_name',
        'photo_medium'
    ];

    /**
     * @var array Additional fields to be requested from the user profile.
     *            If set, these values will be included with the defaults.
     */
    protected $userFields = [];

    /**
     * @var string If set, this will be sent as the "lang" parameter.
     */
    protected $lang;

    /**
     * @var string If set, this will be sent as the "https" parameter.
     */
    protected $https = '1';

    /**
     * @var string If set, this will be sent as the "v" parameter.
     */
    protected $version;

    /**
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://oauth.vk.com/authorize';
    }

    /**
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://oauth.vk.com/access_token';
    }

    /**
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $fields = array_merge($this->defaultUserFields, $this->userFields);
        $url = 'https://api.vk.com/method/users.get?' . http_build_query([
            'user_id' => $token->getResourceOwnerId(),
            'fields'  => implode(',', $fields),
            'lang'    => $this->lang,
            'https'   => $this->https,
            'v'       => $this->version
        ]);

        return $url;
    }

    /*protected function prepareAccessTokenResponse(array $result)
    {
        $result = parent::prepareAccessTokenResponse($result);
        var_dump($result); exit;
        return $result;
    }*/

    protected function getAuthorizationParameters(array $options)
    {
        $params = array_merge(
            parent::getAuthorizationParameters($options),
            array_filter([
                'response_type' => $this->responseType,
                'display'       => $this->display,
            ])
        );

        return $params;
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function getScopeSeparator()
    {
        return ',';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!is_array($data)) {
            throw new IdentityProviderException("Failed to parse response", 0, $data);
        }

        if (isset($data['error'])) {
            $error = (string)$data['error_description'];

            throw new IdentityProviderException($error, 0, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        if (!isset($response['response'])) {
            $error = '`response` key not present';
            throw new IdentityProviderException($error, 0, $response);
        }

        if (!is_array($response['response'])) {
            $error = '`response` key is not array';
            throw new IdentityProviderException($error, 0, $response);
        }

        if (count($response['response']) <= 0) {
            $error = '`response` is empty';
            throw new IdentityProviderException($error, 0, $response);
        }

        return new VkUser($response['response'][0]);
    }

    /**
     * @param string $language
     * @throws \InvalidArgumentException
     */
    public function setLang($language)
    {
        if (!is_string($language) && !is_null($language)) {
            throw new \InvalidArgumentException('Language must be string or null');
        }
        $this->lang = $language;
    }
}
