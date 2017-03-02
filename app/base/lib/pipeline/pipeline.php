<?php

use Closure;
use base_support_contracts_interface_pipeline as PipelineContract;

class base_pipeline_pipeline implements PipelineContract {

	/**
	 * The object being passed through the pipeline.
	 *
	 * @var mixed
	 */
	protected $passable;

	/**
	 * The array of class pipes.
	 *
	 * @var array
	 */
	protected $pipes = array();

	/**
	 * The method to call on each pipe.
	 *
	 * @var string
	 */
	protected $method = 'handle';

	/**
	 * Create a new class instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Set the object being sent through the pipeline.
	 *
	 * @param  mixed  $passable
	 * @return $this
	 */
	public function send($passable)
	{
		$this->passable = $passable;

		return $this;
	}

	/**
	 * Set the array of pipes.
	 *
	 * @param  dynamic|array  $pipes
	 * @return $this
	 */
	public function through($pipes)
	{
		$this->pipes = is_array($pipes) ? $pipes : func_get_args();

		return $this;
	}

	/**
	 * Set the method to call on the pipes.
	 *
	 * @param  string  $method
	 * @return $this
	 */
	public function via($method)
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * Run the pipeline with a final destination callback.
	 *
	 * @param  \Closure  $destination
	 * @return mixed
	 */
	public function then(Closure $destination)
	{
		$firstSlice = $this->getInitialSlice($destination);

		$pipes = array_reverse($this->pipes);

		return call_user_func(
			array_reduce($pipes, $this->getSlice(), $firstSlice), $this->passable
		);
	}

	/**
	 * Get a Closure that represents a slice of the application onion.
	 *
	 * @return \Closure
	 */
	protected function getSlice()
	{
		return function($stack, $pipe)
		{
			return function($passable) use ($stack, $pipe)
			{
				// If the pipe is an instance of a Closure, we will just call it directly but
				// otherwise we'll resolve the pipes out of the container and call it with
				// the appropriate method and arguments, returning the results back out.
				if ($pipe instanceof Closure)
				{
					return call_user_func($pipe, $passable, $stack);
				}
				else
				{
                    return kernel::single($pipe)->{$this->method}($passable, $stack);
				}
			};
		};
	}

	/**
	 * Get the initial slice to begin the stack call.
	 *
	 * @param  \Closure  $destination
	 * @return \Closure
	 */
	protected function getInitialSlice(Closure $destination)
	{
		return function($passable) use ($destination)
		{
			return call_user_func($destination, $passable);
		};
	}

}
