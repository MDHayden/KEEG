<?php

namespace KEEG\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use KEEG\WebsiteBundle\Entity\Contact;
use KEEG\WebsiteBundle\Entity\Image;

class WebsiteController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')->render('KEEGWebsiteBundle:Advert:index.html.twig');
		return new Response($content);
    }

    public function contactAction(Request $request)
    {
		
		$contact = new Contact();
		
		$form = $this->get('form.factory')->createBuilder('form', $contact)
		  	->add('title',		'text')
		  	->add('content',	'textarea')
		  	->add('name',		'text')
		  	->add('mail', 		'email')
		  	->add('envoyer',	'submit')
		  	->getForm()
		;
		
		$form-> handleRequest($request);
		
		if($form->isValid()){
			
			$message = \Swift_Message::newInstance()
				->setSubject($form->get('title')->getData())
				->setFrom($form->get('mail')->getData())
				->setTo('keegiut@gmail.com')
				->setBody(
					$this->renderView(
						'KEEGWebsiteBundle:Mail:contact.html.twig',
						array(
							'name' => $form->get('name')->getData(),
							'message' => $form->get('content')->getData(),
							'mail' => $form->get('mail')->getData()
						)
					)
				)
			;
				
			$this->get('mailer')->send($message);
			
			$session = $request->getSession();
			$session->getFlashBag()->add('accueil', 'Votre e-mail a bien été envoyé, merci !');
			return $this->redirect($this->generateUrl('keeg_website_homepage'));
			
		}
		
		return $this->render('KEEGWebsiteBundle:Advert:contact.html.twig', array(
		  'form' => $form->createView()
		));
	
    }
	
	public function aboutAction()
    {
    	$content = $this->get('templating')->render('KEEGWebsiteBundle:Advert:about.html.twig');
		return new Response($content);
    }
}
