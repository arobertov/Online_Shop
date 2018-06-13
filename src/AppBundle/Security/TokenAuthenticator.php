<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 8.6.2018 г.
 * Time: 16:06
 */

namespace AppBundle\Security;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator{

	const USERNAME_ERROR_MESSAGE =  'Username not found :( !';
	const PASSWORD_ERROR_MESSAGE = 'Password mishmash :( ';
	const REDIRECT_URL_REQUIRED_AUTH = '/login';
	const REDIRECT_URL_SUCCESS_AUTH = '/';

	/**
	 * @var UserPasswordEncoderInterface $encoder
	 */
	 private $encoder;

	/**
	 * @var string
	 */
	 private $rememberMe;

	 public function __construct(UserPasswordEncoderInterface $encoder) {
	 	$this->encoder = $encoder;
	 }

	/**
	 *
	 * @param Request $request The request that resulted in an AuthenticationException
	 * @param AuthenticationException $authException The exception that started the authentication process
	 *
	 * @return Response
	 */
	public function start( Request $request, AuthenticationException $authException = null ) {
		return new RedirectResponse(self::REDIRECT_URL_REQUIRED_AUTH);
	}

	/**
	 * Get the authentication credentials from the request and return them
	 * as any type (e.g. an associate array). If you return null, authentication
	 * will be skipped.
	 *
	 * Whatever value you return here will be passed to getUser() and checkCredentials()
	 *
	 * For example, for a form login, you might:
	 *
	 *      if ($request->request->has('_username')) {
	 *          return array(
	 *              'username' => $request->request->get('_username'),
	 *              'password' => $request->request->get('_password'),
	 *          );
	 *      } else {
	 *          return;
	 *      }
	 *
	 * Or for an API token that's on a header, you might use:
	 *
	 *      return array('api_key' => $request->headers->get('X-API-TOKEN'));
	 *
	 * @param Request $request
	 *
	 * @return mixed|null
	 */
	public function getCredentials( Request $request ) {

		if($request->request->has('_username')){
			if( ! $request->request->has('_remember_me')){
				$this->rememberMe = '';
			}
			return [
				'username'=>$request->request->get('_username'),
				'password'=>$request->request->get('_password'),
				'remember_me'=>$this->rememberMe
			];
		} else {
			return null;
		}
	}

	/**
	 * Return a UserInterface object based on the credentials.
	 *
	 * The *credentials* are the return value from getCredentials()
	 *
	 * You may throw an AuthenticationException if you wish. If you return
	 * null, then a UsernameNotFoundException is thrown for you.
	 *
	 * @param mixed $credentials
	 * @param UserProviderInterface $userProvider
	 *
	 * @throws AuthenticationException
	 *
	 * @return UserInterface|null
	 */
	public function getUser( $credentials, UserProviderInterface $userProvider ) {
		$username = $credentials['username'] ;
		$errorMessage = "User in username: $username not found :(";
		if(null === $username) throw new AuthenticationException(self::USERNAME_ERROR_MESSAGE);
		try {
			$user = $userProvider->loadUserByUsername($username);
		} catch (\Exception $e){
			throw new AuthenticationException($errorMessage);
		}
		return $user;
	}

	/**
	 * Returns true if the credentials are valid.
	 *
	 * If any value other than true is returned, authentication will
	 * fail. You may also throw an AuthenticationException if you wish
	 * to cause authentication to fail.
	 *
	 * The *credentials* are the return value from getCredentials()
	 *
	 * @param mixed $credentials
	 * @param UserInterface $user
	 *
	 * @return bool
	 *
	 * @throws AuthenticationException
	 */
	public function checkCredentials( $credentials, UserInterface $user ) {
		$encodePassword = $this->encoder->isPasswordValid($user,$credentials['password']);

		if(! $encodePassword ){
			throw new AuthenticationException(self::PASSWORD_ERROR_MESSAGE);
		}
		return true;
	}

	/**
	 * Called when authentication executed, but failed (e.g. wrong username password).
	 *
	 * This should return the Response sent back to the user, like a
	 * RedirectResponse to the login page or a 403 response.
	 *
	 * If you return null, the request will continue, but the user will
	 * not be authenticated. This is probably not what you want to do.
	 *
	 * @param Request $request
	 * @param AuthenticationException $exception
	 *
	 * @return Response|null
	 */
	public function onAuthenticationFailure( Request $request, AuthenticationException $exception ) {
		return new JsonResponse(['error'=>$exception->getMessage()],Response::HTTP_FORBIDDEN);
	}

	/**
	 * Called when authentication executed and was successful!
	 *
	 * This should return the Response sent back to the user, like a
	 * RedirectResponse to the last page they visited.
	 *
	 * If you return null, the current request will continue, and the user
	 * will be authenticated. This makes sense, for example, with an API.
	 *
	 * @param Request $request
	 * @param TokenInterface $token
	 * @param string $providerKey The provider (i.e. firewall) key
	 *
	 * @return Response|null
	 */
	public function onAuthenticationSuccess( Request $request, TokenInterface $token, $providerKey ) {

		return new RedirectResponse(self::REDIRECT_URL_SUCCESS_AUTH);
	}

	/**
	 * Does this method support remember me cookies?
	 *
	 * Remember me cookie will be set if *all* of the following are met:
	 *  A) This method returns true
	 *  B) The remember_me key under your firewall is configured
	 *  C) The "remember me" functionality is activated. This is usually
	 *      done by having a _remember_me checkbox in your form, but
	 *      can be configured by the "always_remember_me" and "remember_me_parameter"
	 *      parameters under the "remember_me" firewall key
	 *  D) The onAuthenticationSuccess method returns a Response object
	 *
	 * @return bool
	 */
	public function supportsRememberMe() {
		return true;
	}
}