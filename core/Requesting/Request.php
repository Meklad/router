<?php

declare(strict_types=1);

namespace Core\Requesting;

use Core\Requesting\RequestInterface;

/**
 * This class prepare the request data for usage inside the app.
 */
class Request implements RequestInterface
{
    /**
     * Not Secured Http connection.
     */
    const HTTP = "http";

    /**
     * Secured Http connection.
     */
    const HTTPS = "https";

    /**
     * Check if the client request using https or http.
     *
     * @var boolean
     */
    private readonly bool $isSecure;

    /**
     * Base app url.
     *
     * @var string
     */
    private readonly string $url;

    /**
     * Clean domain without http || https || params || query string.
     *
     * @var string
     */
    private readonly string $cleanDomain;

    /**
     * Full app url.
     *
     * @var string
     */
    private readonly string $fullUrl;

    /**
     * Http method.
     *
     * @var string
     */
    private readonly string $httpMethod;

    /**
     * Uri full string.
     *
     * @var string
     */
    private readonly string $uri;

    /**
     * Url Parametrs.
     *
     * @var array
     */
    private readonly array $urlParams;

    /**
     * Url query string as array.
     *
     * @var array
     */
    private readonly array $queryString;

    /**
     * Request Constructor.
     */
    public function __construct(private array $server)
    {
        $this->bootstrapRequestComponents($server);
    }

    /**
     * Factory method that bootstrap the request.
     *
     * @param array $server
     * @return self
     */
    public function bootstrapRequestComponents(array $server): self
    {
        $this->setIsSecure(isset($server["HTTPS"]) ? $server["HTTPS"] : "")
                ->setUrl($server["HTTP_HOST"])
                ->setFullUrl($server["REQUEST_URI"])
                ->setHttpMethod($server["REQUEST_METHOD"])
                ->setCleanDomain($server["SERVER_NAME"])
                ->setUri(isset($server["REQUEST_URI"]) ? $server["REQUEST_URI"] : "")
                ->setQueryString($server["QUERY_STRING"])
                ->setUrlParams(isset($server["REDIRECT_URL"]) ? $server["REDIRECT_URL"] : "");
        return $this;
    }

    /**
     * Set isSecure.
     *
     * @param string $urisSecurei
     * @return self
     */
    public function setIsSecure(string|bool $isSecure): self
    {
        $this->isSecure = $isSecure == "on" ?? $isSecure;
        return $this;
    }

    /**
     * Set base url.
     *
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {       
        if(!str_contains($url, self::HTTP) || !str_contains($url, self::HTTPS)) {
            $this->url = !$this->isSecureRequest() ? self::HTTP . "://" . $url : self::HTTPS . "://" . $url;
        } else {
            $this->url = $url;
        }

        return $this;
    }

    /**
     * Set Full url.
     *
     * @param string $fullUrl
     * @return self
     */
    public function setFullUrl(string $fullUrl): self
    {
        $this->fullUrl = $this->getUrl() . $fullUrl;
        return $this;
    }

    /**
     * Set request http method.
     *
     * @param string $httpMethod
     * @return self
     */
    public function setHttpMethod(string $httpMethod): self
    {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /**
     * Set request uri.
     *
     * @param string $uri
     * @return self
     */
    public function setUri(string $uri): self
    {
        if($uri == "/" || $uri == "" || $uri == null || gettype($uri) == "boolean") {
            $this->uri = "/";
        } else {
            $this->uri = $uri;
        }
        return $this;
    }

    /**
     * Set request query string.
     *
     * @param string $queryString
     * @return self
     */
    public function setQueryString(string|bool $queryString): self
    {
        if($queryString == "") {
            $this->queryString = [];
        } else {
            $this->queryString = $this->storeQueryStringAsArray($queryString);
        }

        return $this;
    }

    /**
     * Convert Query string to array.
     *
     * @param string $queryString
     * @return array
     */
    private function storeQueryStringAsArray(string $queryString): array
    {
        $container = [];

        $explodeQueryString = explode("&", trim($queryString));

        array_map(function ($element) use(&$container) {
            $tempElement = explode("=", $element);
            if(isset($tempElement[0]) && isset($tempElement[1])) {
                $container[$tempElement[0]] = $tempElement[1];
            }
        }, $explodeQueryString);

        return $container;
    }

    /**
     * Convert url parameters to array.
     *
     * @param string $params
     * @return self
     */
    private function setUrlParams(string $params): self
    {
        if($params == "") {
            $this->urlParams = []; 
        } else {
            $this->urlParams = $this->storeUrlParamsAsArray($params);
        }
        return $this;
    }

    /**
     * Convert url parameters to array.
     *
     * @param string $params
     * @return array
     */
    private function storeUrlParamsAsArray(string $params): array
    {
        $container = [];
        
        $tempUrlParams = explode("/", trim($params));

        array_map(function ($element) use(&$container) {
            if(!empty($element)) {
                $container[] = $element;
            }
        }, $tempUrlParams);

        return $container;
    }

    /**
     * Set clean domain.
     *
     * @param string $domain
     * @return self
     */
    public function setCleanDomain(string $domain): self
    {
        $this->cleanDomain = $domain;
        return $this;
    }

    /**
     * Get is the request uses https than return true or false.
     *
     * @return boolean
     */
    public function isSecureRequest(): bool
    {
        return $this->isSecure;
    }

    /**
     * Get the full url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the full url with request uri.
     *
     * @return string
     */
    public function getFullUrl(): string
    {
        return $this->fullUrl;
    }

    /**
     * Get request http method.
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Get the request uri.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the request query string.
     *
     * @return array
     */
    public function getQueryString(): array
    {
        return $this->queryString;
    }

    /**
     * Get the request url params.
     *
     * @return array
     */
    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    /**
     * Clean domain without http || https || params || query string.
     *
     * @return string
     */
    public function getCleanDomain(): string
    {
        return $this->cleanDomain;
    }
}