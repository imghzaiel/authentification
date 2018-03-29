<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use App\Entity\User;
use App\Form\Type\UserFormType;

/**
 * Brand controller.
 *
 * @Route("/")
 */
class UserController extends Controller
{
    
    
    /**
     * @Route("/user", name="user")
     * @Method({"POST"})
     */
    public function postUserAction(Request $request)
    {
       $em=$this->getDoctrine()->getManager();
        $encoder = $this->container->get('security.password_encoder');
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush($user);
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }
    
    
    /**
     * @FOSRest\Get("/users")
     *
     * @return array
     */
    public function getUserAction()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        
        // query for a single Product by its primary key (usually "id")
        $user = $repository->findall();
        
        return View::create($user, Response::HTTP_CREATED , []);
    }
    
    /**
     * @FOSRest\Put("/userid/{id}")
     *
     * @return array
     */
    public function putUserAction(Request $request, $id) {

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);

        if (!$user) {
            throw new HttpException(404, "user with the id $id not found");
        }
        $view = View::create();
        $form = $this->createForm(UserFormType::class, $user, array('method' => 'PUT'));
        try {
            $form->setData($user);
            if ('PUT' === $request->getMethod()) {
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $encoder = $this->container->get('security.password_encoder');
                    $passwordEncoded = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($passwordEncoded);
                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush();
                    $view->setStatusCode(204);
                } else {
                    $view->setStatusCode(400);
                    $view->setData(array($form));
                }
            } else {
                $view->setStatusCode(400);
                $view->setData(array($form));
            }
        } catch (\Exception $ex) {
            throw new HttpException(500, $ex->getMessage());
        }

        return View::create($user, Response::HTTP_CREATED , []);
    }
    
    /**
     * @FOSRest\Delete("/delete/{id}")
     *
     * @return array
     */
     public function deleteUserAction($id)
        {
            
            $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneById($id);
            if ($user == null) {
                return new View(null, Response::HTTP_NOT_FOUND);
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return new View(null, Response::HTTP_NO_CONTENT);
        }
  /**
     * Logout user
     *
     * @Route("/logout")
     */
    public function logoutAction() {
        try {
            $this->get("request")->getSession()->invalidate();
            $this->get("security.context")->setToken(null);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
   
    /**
     * @Route("/api", name="api")
     */
    public function apiAction()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
     
}
