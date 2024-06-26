<?php

namespace App\Router;

use App\Helpers\Str;

/**
 * Class Route
 *
 * Represents a route in the application router.
 */
class Route {
	/**
	 * The registered routes.
	 *
	 * @var array An array containing all registered routes.
	 */
	private static array $routes = [];
	
	/**
	 * The route path.
	 *
	 * @var string The route path.
	 */
	protected string $route;
	
	/**
	 * The HTTP methods allowed for this route.
	 *
	 * @var array|string The HTTP methods allowed for this route.
	 */
	protected array|string $method;
	
	/**
	 * The action to be performed when the route is matched.
	 *
	 * @var callable|array|string The action to be performed when the route is matched.
	 */
	protected mixed $action;
	
	/**
	 * The route parameter constraints.
	 *
	 * @var array The route parameter constraints.
	 */
	protected array $where = [];
	
	/**
	 * The middleware stack for the route.
	 *
	 * @var array $middleware An array of middleware classes/functions to be executed before the route action.
	 */
	protected array $middleware = [];
	
	/**
	 * Route constructor.
	 *
	 * @param string $route The route path.
	 * @param array|string $method The HTTP methods allowed for this route.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 * @param array|string $middleware An array or string of middleware classes/functions to be executed before the route action.
	 */
	public function __construct(string $route, array|string $method, callable|array|string $action, array|string $middleware = []) {
		$this->route = strtolower($route);
		$this->method = (array) Str::toUpperCase($method);
		$this->action = $action;
		$this->middleware = (array) $middleware;
		static::add($this);
	}
	
	/**
	 * Create a new route instance.
	 *
	 * @param string $route The route path.
	 * @param array|string $method The HTTP methods allowed for this route.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 * @param array|string $middleware An array or string of middleware classes/functions to be executed before the route action.
	 *
	 * @return static The newly created route instance.
	 */
	public static function new(string $route, array|string $method, callable|array|string $action, array|string $middleware = []): static {
		return new static(...func_get_args());
	}
	
	/**
	 * Add a route to the registered routes.
	 *
	 * @param Route $route The route to add.
	 *
	 * @return void
	 */
	private static function add(Route $route): void {
		static::$routes[$route->route] = $route;
	}
	
	/**
	 * Add parameter constraints to the route.
	 *
	 * @param string $param The parameter name.
	 * @param string $regex The regular expression constraint.
	 *
	 * @return static The current Route instance.
	 */
	public function where(string $param, string $regex): static {
		$this->where[$param] = $regex;
		return $this;
	}
	
	/**
	 * Set the middleware for the route.
	 *
	 * @param array|string $middleware An array or string of middleware classes/functions to be executed before the route action.
	 *
	 * @return static This route instance.
	 */
	public function middleware(array|string $middleware): static {
		$this->middleware = [...$this->middleware, ...(array) $middleware];
		return $this;
	}
	
	/**
	 * Define a GET route.
	 *
	 * @param string $route The route path.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 *
	 * @return static The newly created GET route instance.
	 */
	public static function get(string $route, callable|array|string $action, string|array $middleware = []): static {
		return static::new($route, 'GET', $action, $middleware);
	}
	
	/**
	 * Define a POST route.
	 *
	 * @param string $route The route path.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 *
	 * @return static The newly created POST route instance.
	 */
	public static function post(string $route, callable|array|string $action, string|array $middleware = []): static {
		return static::new($route, 'POST', $action, $middleware);
	}
	
	/**
	 * Define a PUT route.
	 *
	 * @param string $route The route path.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 *
	 * @return static The newly created PUT route instance.
	 */
	public static function put(string $route, callable|array|string $action, string|array $middleware = []): static {
		return static::new($route, 'PUT', $action, $middleware);
	}
	
	/**
	 * Define a DELETE route.
	 *
	 * @param string $route The route path.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 *
	 * @return static The newly created DELETE route instance.
	 */
	public static function delete(string $route, callable|array|string $action, string|array $middleware = []): static {
		return static::new($route, 'DELETE', $action, $middleware);
	}
	
	/**
	 * Define a PATCH route.
	 *
	 * @param string $route The route path.
	 * @param callable|array|string $action The action to be performed when the route is matched.
	 *
	 * @return static The newly created PATCH route instance.
	 */
	public static function patch(string $route, callable|array|string $action, string|array $middleware = []): static {
		return static::new($route, 'PATCH', $action, $middleware);
	}
	
	/**
	 * Get all registered routes.
	 *
	 * @return array An array containing all registered routes.
	 */
	public static function getRoutes(): array {
		return static::$routes;
	}
	
	/**
	 * Get the HTTP methods allowed for this route.
	 *
	 * @return array|string The HTTP methods allowed for this route.
	 */
	public function method(): array|string {
		return $this->method;
	}
	
	/**
	 * Magic method to access properties dynamically.
	 *
	 * @param string $name The property name.
	 *
	 * @return mixed|null The value of the property, or null if the property does not exist.
	 */
	public function __get(string $name): mixed {
		if (property_exists($this, $name)) {
			return $this->$name;
		}
		return null;
	}
}