<?php

namespace Wei;

/**
 * A Soap client that works like HTTP service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Soap extends Base
{
    /**
     * The request URL
     *
     * @var string
     */
    protected $url;

    /**
     * The name of the SOAP function to call.
     *
     * @var string
     */
    protected $method;

    /**
     * The parameters send to the SOAP function
     *
     * @var array
     */
    protected $data = [];

    /**
     * Whether use the global options in `$wei->http` object when create a
     * new object
     *
     * @var bool
     */
    protected $global = false;

    /**
     * Whether throw exception or keep silent when request error
     *
     * Note that the exception is thrown after triggered complete callback, rather than triggered error callback
     *
     * @var bool
     */
    protected $throwException = true;

    /**
     * A callback triggered after prepared the data and before the `beforeSend` callback
     *
     * @var callable
     */
    protected $beforeExecute;

    /**
     * A callback triggered after prepared the data and before the process the request
     *
     * @var callable
     */
    protected $beforeSend;

    /**
     * A callback triggered after the request is called success
     *
     * @var callable
     */
    protected $success;

    /**
     * A callback triggered when the request fails
     *
     * The `$textStatus` could be `curl`, `http`, and `parser`
     *
     * @var callable
     */
    protected $error;

    /**
     * A callback triggered when request finishes (after `success` and `error` callbacks are executed)
     *
     * @var callable
     */
    protected $complete;

    /**
     * The parsed response data
     *
     * @var mixed
     */
    protected $response;

    /**
     * The instance of soap client
     *
     * @var \SoapClient
     */
    protected $soapClient;

    /**
     * The error exception object
     *
     * @var \ErrorException
     */
    protected $errorException;

    /**
     * The default options of current object
     *
     * @var array
     */
    private $defaultOptions;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        // Merges options from default Soap service
        if (isset($options['global']) && true === $options['global']) {
            $options += (array) $options['wei']->getConfig('soap');
        }
        parent::__construct($options);
        $this->defaultOptions = $options;
    }

    /**
     * Create a new Soap service and execute
     *
     * @param array $options A options array if the first parameter is string
     * @return $this A new Soap object
     */
    public function __invoke(array $options = [])
    {
        $soap = new self($options + $this->defaultOptions);
        return $soap->execute();
    }

    /**
     * Execute the request, parse the response data and trigger relative callbacks
     */
    public function execute()
    {
        try {
            // Note that when provided a non-existing WSDL, PHP will still generate an error in error_get_last()
            // https://github.com/bcosca/fatfree/issues/404
            // https://bugs.php.net/bug.php?id=65779

            // Prepare request
            $soapClient = $this->soapClient = new \SoapClient($this->url, [
                'trace' => $this->wei->isDebug(),
            ]);

            // Execute beforeExecute and beforeSend callbacks
            $this->beforeExecute && call_user_func($this->beforeExecute, $this, $soapClient);
            $this->beforeSend && call_user_func($this->beforeSend, $this, $soapClient);

            // Execute request
            $this->response = $soapClient->__soapCall($this->method, [$this->data]);
        } catch (\SoapFault $e) {
            $soapClient = null;
            $this->errorException = $e;
        }

        // Trigger success, error and complete callbacks
        if (!$this->errorException) {
            $this->success && call_user_func($this->success, $this->response, $this, $soapClient);
        } else {
            $this->error && call_user_func($this->error, $this, $soapClient);
        }
        $this->complete && call_user_func($this->complete, $this, $soapClient);

        if ($this->throwException && $this->errorException) {
            throw $this->errorException;
        }

        return $this;
    }

    /**
     * Returns the parsed response data
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the request URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the request method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the data to send to the server
     *
     * @return array|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the error exception object
     *
     * @return \SoapFault
     */
    public function getErrorException()
    {
        return $this->errorException;
    }
}
