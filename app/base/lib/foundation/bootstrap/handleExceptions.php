<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2012 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

//use ErrorException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Debug\Exception\FatalErrorException;
use base_contracts_exception_handler as ContractsExceptionHandler;

class base_foundation_bootstrap_handleExceptions
{

    protected $exceptionHandler = null;
    
	/**
	 * Bootstrap the given application.
	 *
	 * @param  \Illuminate\Contracts\Foundation\Application  $app
	 * @return void
	 */
    public function bootstrap()
    {
        //error_reporting(E_ERROR | E_USER_ERROR | E_PARSE | E_COMPILE_ERROR | E_DEPRECATED);
        error_reporting(E_ERROR | E_USER_ERROR | E_PARSE | E_COMPILE_ERROR);

		set_error_handler([$this, 'handleError']);

		set_exception_handler([$this, 'handleException']);

		register_shutdown_function([$this, 'handleShutdown']);

        /*
		if ( ! kernel::environment('testing'))
		{
			ini_set('display_errors', 'Off');
		}
        */
    }

	/**
	 * Convert a PHP error to an ErrorException.
	 *
	 * @param  int  $level
	 * @param  string  $message
	 * @param  string  $file
	 * @param  int  $line
	 * @param  array  $context
	 * @return void
	 *
	 * @throws \ErrorException
	 */
	public function handleError($level, $message, $file = '', $line = 0, $context = array())
	{
		if (error_reporting() & $level)
		{
			throw new \ErrorException($message, 0, $level, $file, $line);
		}
	}

	/**
	 * Handle an uncaught exception from the application.
	 *
	 * Note: Most exceptions can be handled via the try / catch block in
	 * the HTTP and Console kernels. But, fatal error exceptions must
	 * be handled differently since they are not normal exceptions.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function handleException($e)
	{
		$this->getExceptionHandler()->report($e);

		if (kernel::runningInConsole())
		{
			$this->renderForConsole($e);
		}
		else
		{
			$this->renderHttpResponse($e);
		}
	}

	/**
	 * Render an exception to the console.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	protected function renderForConsole($e)
	{
		$this->getExceptionHandler()->renderForConsole(new ConsoleOutput, $e);
	}

	/**
	 * Render an exception as an HTTP response and send it.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	protected function renderHttpResponse($e)
	{
		$this->getExceptionHandler()->render(request::instance(), $e)->send();
	}

	/**
	 * Handle the PHP shutdown event.
	 *
	 * @return void
	 */
	public function handleShutdown()
	{
		if ( ! is_null($error = error_get_last()) && $this->isFatal($error['type']))
		{
			$this->handleException($this->fatalExceptionFromError($error, 0));
		}
	}

	/**
	 * Create a new fatal exception instance from an error array.
	 *
	 * @param  array  $error
	 * @param  int|null  $traceOffset
	 * @return \Symfony\Component\Debug\Exception\FatalErrorException
	 */
	protected function fatalExceptionFromError(array $error, $traceOffset = null)
	{
		return new FatalErrorException(
			$error['message'], $error['type'], 0, $error['file'], $error['line'], $traceOffset
		);
	}
    
	/**
	 * Determine if the error type is fatal.
	 *
	 * @param  int  $type
	 * @return bool
	 */
	protected function isFatal($type)
	{
        $a = in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
        
        return $a;
	}

	/**
	 * Get an instance of the exception handler.
	 *
	 * @return \Illuminate\Contracts\Debug\ExceptionHandler
	 */
	protected function getExceptionHandler()
	{
        // return new base_exception_handler;
        if(! $this->exceptionHandler instanceof ContractsExceptionHandler)
        {
            $this->exceptionHandler = new base_exception_handler;
        }
        
	    return $this->exceptionHandler;
	}
    
	/**
	 * set an instance of the exception handler.
	 *
	 * @return void
	 */
	
	public function setExceptionHandler(ContractsExceptionHandler $handler)
	{
	    
	    $this->exceptionHandler = $handler;
	}
}
