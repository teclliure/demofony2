<?php
/**
 * Demofony2
 * 
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 * 
 * Date: 04/02/15
 * Time: 11:20
 */
namespace Demofony2\UserBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        ldd('entra');
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data


            if ('facebook' === $service) {
                $user->setUsername(explode('@',$response->getEmail())[0]);
                $user->setEmail($response->getEmail());
                $user->setName($response->getRealName());
            }elseif('twitter' === $service) {
//                ldd($response->getResponse());
                $user->setUsername($response->getResponse()['screen_name']);
                $user->setEmail($response->getResponse()['screen_name']);
                $user->setName($response->getResponse()['name']);
            }elseif('google' === $service) {
                $user->setEmail($response->getResponse()['email']);
                $user->setName($response->getResponse()['name']);
                $user->setUsername($response->getResponse()['given_name']);
            }
            $user->setPassword('');
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

}