<?php

namespace NextcloudApiWrapper;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NextcloudResponse
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var \SimpleXMLElement
     */
    protected $body;

    /**
     * NextcloudResponse constructor.
     * @param ResponseInterface $response
     * @throws NCException
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        try {
            $this->body = new \SimpleXMLElement($response->getContent());
        } catch (\Exception $e) {
            throw new NCException($response, "Failed parsing response");
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
    }

    /**
     * Returns nextcloud status message
     * @return string
     */
    public function getStatus()
    {

        return (string)$this->body->meta->status;
    }

    /**
     * Returns nextcloud message
     * @return string
     */
    public function getMessage()
    {

        return (isset($this->body->meta->message) ? (string)$this->body->meta->message : null);
    }

    /**
     * Returns nextcloud status code
     * @return int
     */
    public function getStatusCode()
    {

        return intval($this->body->meta->statuscode);
    }

    /**
     * Returns nextcloud response data if any
     * @return array|null
     */
    public function getData()
    {

        $data = $this->body->data;

        return empty((string)$data) ? null : $this->xml2array($data);
    }

    /**
     * Returns the raw httpClient response
     * @return ResponseInterface
     */
    public function getRawResponse()
    {

        return $this->response;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $out
     * @return array
     */
    protected function xml2array(\SimpleXMLElement $xml, $out = [])
    {

        foreach ((array)$xml as $index => $node) {
            $out[$index] = $node instanceof \SimpleXMLElement ? $this->xml2array($node) : $node;
        }

        return $out;
    }
}