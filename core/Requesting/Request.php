<?php

declare(strict_types=1);

namespace Core\Requesting;

/**
 * This class prepare the request data for usage inside the app.
 */
class Request
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
     * @var array
     */
    private readonly array $uri;

    /**
     * Url query string as array.
     *
     * @var array
     */
    private readonly array $queryString;

    /**
     * Factory method that bootstrap the request.
     *
     * @param array $server
     * @return self
     */
    public static function bootstrapRequestComponents(array $server): self
    {
        $request = new self;
        $request->setIsSecure(isset($server["HTTPS"]) ?? $server["HTTPS"])
                ->setUrl($server["HTTP_HOST"])
                ->setFullUrl($server["REQUEST_URI"])
                ->setHttpMethod($server["REQUEST_METHOD"])
                ->setUri($server["REDIRECT_URL"])
                ->setQueryString($server["QUERY_STRING"]);
        return $request;
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
        if($uri == "/" || $uri == "" || $uri == null) {
            $this->uri = [];
        } else {
            $this->uri = explode("/", parse_url(ltrim($uri, "/"))["path"]);
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
        $params = [];
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
            $container[$tempElement[0]] = $tempElement[1];
        }, $explodeQueryString);

        return $container;
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
        return $this->fullUrl;
    }

    /**
     * Get the request uri.
     *
     * @return array
     */
    public function getUri(): array
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
}