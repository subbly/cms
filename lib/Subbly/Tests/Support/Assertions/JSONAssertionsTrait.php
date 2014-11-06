<?php

namespace Subbly\Tests\Support\Assertions;

use Symfony\Component\PropertyAccess\PropertyAccess;

trait JSONAssertionsTrait
{
    private static $formats = array(
        'boolean',
        'integer',
        'double',
        'string',
        'array',
        'object',
        'null',
    );


    /**
     * Assert that the client content-type response is an application/json.
     */
    public function assertResponseJSONValid()
    {
        $response = $this->client->getResponse();

        $actual = $response->headers->get('content-type');
        $this->assertEquals('application/json', $actual, 'Expected content-type header application/json, got ' . $actual);

        /**
         * Test JSON content
         */
        $json = $this->getJSONContent(true);

        // Headers
        $this->assertArrayHasKey('headers', $json);
        $this->assertArrayHasKey('status', $json['headers']);
        $this->assertEquals($response->getStatusCode(), $json['headers']['status']['code']);
        $this->assertArrayHasKey('version', $json['headers']);

        // Content
        $this->assertArrayHasKey('response', $json);
    }

    /**
     *
     *
     * @param string
     */
    public function assertJSONCollectionResponse($collectionKey)
    {
        $json     = $this->getJSONContent(true);
        $response = $json['response'];

        $this->assertArrayHasKey($collectionKey, $response);
        $this->assertArrayHasKey('offset', $response);
        $this->assertArrayHasKey('limit', $response);
        $this->assertArrayHasKey('total', $response);

        $this->assertJSONTypes(array(
            $collectionKey => 'array',
            'offset'       => 'integer',
            'limit'        => 'integer',
            'total'        => 'integer',
        ));
    }

    /**
     *
     *
     * @param string
     * @param mixed
     */
    public function assertJSONEquals($key, $data)
    {
        $accessor = PropertyAccess::createPropertyAccessor(true, true);
        $json     = $this->getJSONContent();
        $content  = $accessor->getValue($json->response, $key);

        foreach ($content as $k=>$v)
        {
            $k = is_array($data)
                ? "[$k]"
                : $k
            ;

            // TODO change it or not?
            if (is_array($data) && !isset($data[$k])) {
                continue;
            }

            $dataValue = $accessor->getValue($data, $k);

            // \Illuminate\Support\Contracts\ArrayableInterface; ====> ->toArray();
            if ($dataValue instanceof \IteratorAggregate || $dataValue instanceof \ArrayAccess) {
                $dataValue = (array) $dataValue;
            }
            else if (is_object($dataValue) && method_exists($dataValue, '__toString')) {
                $dataValue = (string) $dataValue;
            }
            else if ($dataValue instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                $dataValue = $dataValue->getResults()->toArray();
            }

            $this->assertEquals($dataValue, $v);
        }
    }

    /**
     *
     *
     * TODO add required or optional definition. !key! or (key)
     */
    public function assertJSONTypes($keyOrFieldTypes)
    {
        // Formats function args
        $args   = func_get_args();
        $params = array(
            'key'         => null,
            'field_types' => array(),
        );

        if (count($args) === 1 && is_array($args[0]))
        {
            $params['field_types'] = $args[0];
        }
        else if (
            count($args) === 2
            && (is_string($args[0]) || is_null($args[0]))
            && is_array($args[1])
        ) {
            $params['key']         = $args[0];
            $params['field_types'] = $args[1];
        }
        else {
            // TODO
            throw new Exception('');
        }

        // Get JSON content
        $accessor = PropertyAccess::createPropertyAccessor(true, true);
        $json     = $this->getJSONContent();
        $content  = $this->getJSONContent()->response;

        if ($params['key'] !== null) {
            $content = $accessor->getValue($json->response, $params['key']);
        }

        // Check assertions
        foreach ($params['field_types'] as $fieldName=>$formats)
        {
            if (!property_exists($content, $fieldName)) {
                // TODO
                throw new Exception('');
            }

            if (is_array($formats) && $this->isKeyValueArray($formats))
            {
                // TODO nested func
                // $this->assertJSONTypes($fieldName, $formats, $value)
            }
            else
            {
                $value = $accessor->getValue($content, $fieldName);

                foreach ((array) $formats as $format)
                {
                    if (in_array($format, self::$formats)) {
                        $this->assertInternalType($format, $value);
                    }
                    else if ($format === 'datetime') {
                        $this->assertDateTimeString($value, \DateTime::ISO8601);
                    }
                    else if (class_exists($format)) {
                        $this->assertInstanceOf($format, $value);
                    }
                    else {
                        // TODO
                        throw new Exception('');
                    }
                }
            }
        }
    }

    /**
     * Access to an array value from a string path
     *
     * @param string  $path  Path to search into the array
     * @param string  $array The array
     *
     * @return mixed
     */
    private function accessArrayFromString($path, $array)
    {
        if (!is_string($path) || $path === null) {
            return $array;
        }

        if (strpos($path, '.') === false) {
            if ($array instanceof \StdClass) return $array->{$path};
            if (is_array($array))            return $array[$path];

            // TODO throw an exception?
            return null;
        }

        $pieces = explode('.', $path);
        $result = $array;

        foreach ($pieces as $piece)
        {
            if (is_array($result) && array_key_exists($piece, $result)) {
                $result = $result[$piece];
            }
            else if ($result instanceof \StdClass && property_exists($result, $piece)) {
                $result = $result->{$piece};
            }
            else {
                // TODO throw an exception?
                return null;
            }
        }

        return $result;
    }

    /**
     * Check if an array is associative array or simple array.
     *
     * @param mixed  $array The array to check
     *
     * @return boolean
     */
    private function isKeyValueArray($array)
    {
        if (!is_array($array)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
