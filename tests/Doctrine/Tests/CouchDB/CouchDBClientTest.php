<?php

namespace Doctrine\Tests\CouchDB;

use Doctrine\CouchDB\CouchDBClient;
use Doctrine\CouchDB\HTTP\SocketClient;

class CouchDBClientTest extends \PHPUnit_Framework_TestCase
{
    public function testConstants()
    {
        $this->assertEquals("\xEF\xBF\xB0", CouchDBClient::COLLATION_END);
    }

    public function testCreateClient()
    {
        $client = CouchDBClient::create(array('dbname' => 'test'));
        $this->assertEquals('test', $client->getDatabase());
        $this->assertInstanceOf('\Doctrine\CouchDB\HTTP\Client', $client->getHttpClient());

        $httpClient = new SocketClient();
        $client->setHttpClient($httpClient);
        $this->assertEquals($httpClient, $client->getHttpClient());
    }

    public function testCreateClientWithLogging()
    {
        $client = CouchDBClient::create(array('dbname' => 'test', 'logging' => true));
        $this->assertInstanceOf('\Doctrine\CouchDB\HTTP\LoggingClient', $client->getHttpClient());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage 'dbname' is a required option to create a CouchDBClient
     */
    public function testCreateClientDBNameException()
    {
        CouchDBClient::create(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no client implementation registered for foo, valid options are: socket, stream
     */
    public function testCreateClientMissingClientException()
    {
        CouchDBClient::create(array('dbname' => 'test', 'type' => 'foo'));
    }
}
